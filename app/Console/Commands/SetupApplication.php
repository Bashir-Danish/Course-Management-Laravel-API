<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupApplication extends Command
{
    protected $signature = 'app:setup';
    protected $description = 'Setup the application with database and initial data';

    public function handle()
    {
        try {
            $this->info('Starting application setup...');

            // Clear all caches
            $this->call('config:clear');
            $this->call('cache:clear');

            // Generate app key if not set
            if (!config('app.key')) {
                $this->call('key:generate');
            }

            // Initialize database
            $this->call('db:initialize');

            // Install JWT if not already installed
            if (!file_exists(config_path('jwt.php'))) {
                $this->info('Installing JWT...');
                $this->call('vendor:publish', [
                    '--provider' => 'PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider'
                ]);
                $this->call('jwt:secret');
            }

            $this->info('Application setup completed successfully!');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Setup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 