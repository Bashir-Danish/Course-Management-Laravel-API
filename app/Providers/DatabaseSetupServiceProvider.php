<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use PDO;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Schema\Blueprint;

class DatabaseSetupServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $isConsole = App::runningInConsole();
        
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');

            // Try to connect to MySQL server without database
            $dsn = "mysql:host={$host};port={$port}";
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($isConsole) {
                echo "\nStarting database setup...\n";
                echo "MySQL connection successful!\n";
            }

            // Check if database exists
            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$database}'");
            $exists = $stmt->fetch();

            if (!$exists) {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                if ($isConsole) {
                    echo "Database '{$database}' created successfully!\n";
                }
            } else if ($isConsole) {
                echo "Database '{$database}' already exists.\n";
            }

            // Select the database
            $pdo->exec("USE `{$database}`");
            if ($isConsole) {
                echo "Connected to database.\n";
            }

            // Reconnect to the new database
            DB::purge('mysql');
            DB::reconnect('mysql');

            // Set default string length
            Schema::defaultStringLength(191);

            // Check for missing tables
            $requiredTables = [
                'branches', 'departments', 'teachers', 'teacher_departments',
                'courses', 'students', 'registrations', 'monthly_reportings', 
                'admins', 'cache', 'cache_locks', 'personal_access_tokens'
            ];

            $missingTables = [];
            foreach ($requiredTables as $table) {
                if (!Schema::hasTable($table)) {
                    $missingTables[] = $table;
                }
            }

            if (!empty($missingTables)) {
                if ($isConsole) {
                    echo "Missing tables detected: " . implode(', ', $missingTables) . "\n";
                    echo "Creating missing tables...\n";
                }

                // Create missing tables
                foreach ($missingTables as $table) {
                    $this->createTable($table);
                }
                
                if ($isConsole) {
                    echo "Tables created successfully!\n";
                }

                // Create demo admin if not exists
                $this->createDemoAdmin();
            } else if ($isConsole) {
                echo "All required tables exist.\n";
                
                // Check if demo admin exists, if not create it
                $this->createDemoAdmin();
            }

            if ($isConsole) {
                echo "Database setup completed successfully!\n";
            }

        } catch (\Exception $e) {
            if ($isConsole) {
                echo "\nError: " . $e->getMessage() . "\n";
                echo "Please check your database configuration and make sure MySQL is running.\n";
            }
            Log::error("Database setup error: " . $e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }

    private function createTable($table)
    {
        switch ($table) {
            case 'branches':
                Schema::create('branches', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('address');
                    $table->timestamps();
                });
                break;

            case 'departments':
                Schema::create('departments', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->text('description')->nullable();
                    $table->timestamps();
                });
                break;

            case 'teachers':
                Schema::create('teachers', function (Blueprint $table) {
                    $table->id();
                    $table->string('first_name');
                    $table->string('last_name');
                    $table->string('email')->unique();
                    $table->string('address')->nullable();
                    $table->string('phone')->nullable();
                    $table->enum('gender', ['male', 'female']);
                    $table->decimal('salary', 10, 2);
                    $table->timestamps();
                });
                break;

            case 'teacher_departments':
                Schema::create('teacher_departments', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
                    $table->foreignId('department_id')->constrained()->onDelete('cascade');
                    $table->timestamps();
                });
                break;

            case 'courses':
                Schema::create('courses', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->decimal('fees', 10, 2);
                    $table->string('duration')->comment('Format: "X months", "X years", "X weeks", "X days" where X is a number');
                    $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
                    $table->json('available_time_slots');
                    $table->timestamps();
                });
                break;

            case 'students':
                Schema::create('students', function (Blueprint $table) {
                    $table->id();
                    $table->string('first_name');
                    $table->string('last_name');
                    $table->enum('gender', ['Male', 'Female']);
                    $table->string('address')->nullable();
                    $table->string('phone')->nullable();
                    $table->date('dob');
                    $table->timestamps();
                });
                break;

            case 'registrations':
                Schema::create('registrations', function (Blueprint $table) {
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
                break;

            case 'monthly_reportings':
                Schema::create('monthly_reportings', function (Blueprint $table) {
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
                break;

            case 'admins':
                Schema::create('admins', function (Blueprint $table) {
                    $table->id();
                    $table->string('first_name');
                    $table->string('last_name');
                    $table->string('email')->unique();
                    $table->string('password');
                    $table->enum('role', ['super_admin', 'employee']);
                    $table->string('profile_image')->nullable()->default('default-profile.png');
                    $table->timestamps();
                });
                break;

            case 'cache':
                Schema::create('cache', function (Blueprint $table) {
                    $table->string('key')->primary();
                    $table->mediumText('value');
                    $table->integer('expiration');
                });
                break;

            case 'cache_locks':
                Schema::create('cache_locks', function (Blueprint $table) {
                    $table->string('key')->primary();
                    $table->string('owner');
                    $table->integer('expiration');
                });
                break;

            case 'personal_access_tokens':
                Schema::create('personal_access_tokens', function (Blueprint $table) {
                    $table->id();
                    $table->morphs('tokenable');
                    $table->string('name');
                    $table->string('token', 64)->unique();
                    $table->text('abilities')->nullable();
                    $table->timestamp('last_used_at')->nullable();
                    $table->timestamp('expires_at')->nullable();
                    $table->timestamps();
                });
                break;
        }
    }

    private function createDemoAdmin()
    {
        try {
            $adminExists = DB::table('admins')
                ->where('email', 'admin@admin.com')
                ->exists();

            if (!$adminExists) {
                DB::table('admins')->insert([
                    'first_name' => 'Admin',
                    'last_name' => 'User',
                    'email' => 'admin@admin.com',
                    'password' => bcrypt('admin123'), 
                    'role' => 'super_admin',
                    'profile_image' => 'default-profile.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                if (App::runningInConsole()) {
                    echo "Demo admin created successfully!\n";
                    echo "Email: admin@admin.com\n";
                    echo "Password: admin123\n";
                }
            }
        } catch (\Exception $e) {
            if (App::runningInConsole()) {
                echo "Error creating demo admin: " . $e->getMessage() . "\n";
            }
            Log::error("Error creating demo admin: " . $e->getMessage());
        }
    }
}