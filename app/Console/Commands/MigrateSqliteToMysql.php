<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateSqliteToMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrate-to-mysql {sqlite_path : Absolute path to the sqlite file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from SQLite to MySQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sqlitePath = $this->argument('sqlite_path');

        if (!file_exists($sqlitePath)) {
            $this->error("SQLite file not found at: {$sqlitePath}");
            return 1;
        }

        // Setup dynamic sqlite connection
        config(['database.connections.sqlite_source' => [
            'driver' => 'sqlite',
            'database' => $sqlitePath,
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]]);

        $this->info("Connecting to SQLite database...");

        try {
            $tables = DB::connection('sqlite_source')->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        } catch (\Exception $e) {
            $this->error("Could not connect to SQLite: " . $e->getMessage());
            return 1;
        }

        $this->info("Found " . count($tables) . " tables.");

        // Disable foreign key checks on MySQL
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $tableObj) {
            $table = $tableObj->name;
            
            // Skip migrations table if you want, but maybe better to keep it so it doesn't re-run migrations
            // if ($table === 'migrations') continue;

            $this->info("Migrating table: {$table}");

            // Clear table in MySQL first
            DB::connection('mysql')->table($table)->truncate();

            // Fetch data from sqlite
            $rows = DB::connection('sqlite_source')->table($table)->get();

            $dataToInsert = [];
            foreach ($rows as $row) {
                $dataToInsert[] = (array) $row;
            }

            if (!empty($dataToInsert)) {
                // Insert in chunks to avoid memory / payload issues
                $chunks = array_chunk($dataToInsert, 500);
                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table($table)->insert($chunk);
                }
                $this->line("  Migrated " . count($dataToInsert) . " rows.");
            } else {
                $this->line("  Table is empty.");
            }
        }

        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info("Migration from SQLite to MySQL completed successfully!");
        return 0;
    }
}
