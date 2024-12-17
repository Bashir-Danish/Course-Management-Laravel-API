<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;
use PDOException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class InitializeDatabase extends Command
{
    protected $signature = 'db:initialize {--refresh : Refresh all tables}';
    protected $description = 'Initialize database and create tables if they do not exist';

    public function handle()
    {
        try {
            // First check if MySQL service is running
            if (!$this->checkMySQLConnection()) {
                $this->error('MySQL service is not running. Please start MySQL in XAMPP.');
                return Command::FAILURE;
            }

            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');

            $this->info("Checking MySQL connection...");

            try {
                // try toconnect to MySQL server without database
                $dsn = "mysql:host={$host};port={$port}";
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $this->info("MySQL connection successful!");

                // Check if database exists
                $this->info("Checking if database '{$database}' exists...");
                $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$database}'");
                $exists = $stmt->fetch();

                if (!$exists) {
                    $this->info("Creating database '{$database}'...");
                    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    $this->info("Database created successfully!");
                } else {
                    $this->info("Database already exists.");
                }

                // Select the database
                $pdo->exec("USE `{$database}`");
                
                // Reconnect to the new database
                $this->info("Reconnecting to database...");
                DB::purge('mysql');
                DB::reconnect('mysql');

                // Create tables if they don't exist
                $this->createTables();

                // Create test admin user if not exists
                $this->createAdminUser();

                $this->info('Database initialization completed successfully!');
                return Command::SUCCESS;

            } catch (PDOException $e) {
                $this->error("PDO Error: " . $e->getMessage());
                Log::error("PDO Error during database initialization: " . $e->getMessage());
                $this->showTroubleshootingTips();
                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            Log::error("Error during database initialization: " . $e->getMessage());
            $this->showTroubleshootingTips();
            return Command::FAILURE;
        }
    }

    private function createTables()
    {
        $this->info("Checking and creating tables...");

        if (!Schema::hasTable('branches')) {
            Schema::create('branches', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('address');
                $table->timestamps();
            });
            $this->info('Created branches table');
        }

        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function ($table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
            $this->info('Created departments table');
        }

        if (!Schema::hasTable('teachers')) {
            Schema::create('teachers', function ($table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->unique();
                $table->enum('gender', ['Male', 'Female']);
                $table->decimal('salary', 10, 2);
                $table->timestamps();
            });
            $this->info('Created teachers table');
        }

        if (!Schema::hasTable('teacher_departments')) {
            Schema::create('teacher_departments', function ($table) {
                $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
                $table->foreignId('department_id')->constrained()->onDelete('cascade');
                $table->primary(['teacher_id', 'department_id']);
                $table->timestamps();
            });
            $this->info('Created teacher_departments table');
        }

        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function ($table) {
                $table->id();
                $table->string('name');
                $table->decimal('fees', 10, 2);
                $table->string('duration');
                $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
                $table->json('available_time_slots');
                $table->timestamps();
            });
            $this->info('Created courses table');
        }

        if (!Schema::hasTable('students')) {
            Schema::create('students', function ($table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->enum('gender', ['Male', 'Female']);
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->date('dob');
                $table->date('enrolls_date');
                $table->timestamps();
            });
            $this->info('Created students table');
        }

        if (!Schema::hasTable('registrations')) {
            Schema::create('registrations', function ($table) {
                $table->id();
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->date('registration_date');
                $table->decimal('fees_total', 10, 2);
                $table->decimal('fees_paid', 10, 2);
                $table->json('time_slot');
                $table->enum('status', ['Unpaid', 'Paid', 'Cancelled']);
                $table->timestamps();
            });
            $this->info('Created registrations table');
        }

        if (!Schema::hasTable('monthly_reportings')) {
            Schema::create('monthly_reportings', function ($table) {
                $table->id();
                $table->date('month');
                $table->integer('total_students');
                $table->integer('new_registrations');
                $table->decimal('total_revenue', 10, 2);
                $table->integer('total_teachers');
                $table->integer('total_courses');
                $table->decimal('total_expenses', 10, 2);
                $table->json('data')->nullable();
                $table->timestamps();
            });
            $this->info('Created monthly_reportings table');
        }

        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function ($table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('role');
                $table->timestamps();
            });
            $this->info('Created admins table');
        }
    }

    private function createAdminUser()
    {
        $this->info("Checking admin user...");
        if (DB::table('admins')->count() === 0) {
            DB::table('admins')->insert([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->info('Test admin user created successfully!');
            Log::info('Created test admin user');
        }
    }

    private function checkMySQLConnection()
    {
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        $socket = @fsockopen($host, $port, $errno, $errstr, 5);
        if (!$socket) {
            return false;
        }
        fclose($socket);
        return true;
    }

    private function showTroubleshootingTips()
    {
        $this->line('');
        $this->line('Troubleshooting Tips:');
        $this->line('1. Make sure XAMPP is running and MySQL service is started');
        $this->line('2. Check your .env file has correct database credentials:');
        $this->line('   DB_HOST=' . config('database.connections.mysql.host'));
        $this->line('   DB_PORT=' . config('database.connections.mysql.port'));
        $this->line('   DB_USERNAME=' . config('database.connections.mysql.username'));
        $this->line('   DB_DATABASE=' . config('database.connections.mysql.database'));
        $this->line('3. Try these commands in order:');
        $this->line('   - php artisan config:clear');
        $this->line('   - php artisan cache:clear');
        $this->line('   - php artisan db:initialize');
        $this->line('4. If using XAMPP, default credentials are usually:');
        $this->line('   Username: root');
        $this->line('   Password: (empty)');
    }
}