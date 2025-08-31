<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddInitialTenantsAndUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:add-initial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add initial tenants and users for testing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Creating initial tenants and users...');

        // Create First Tenant
        $tenant1 = Tenant::create([
            'id' => 'company1',
            'name' => 'Company One Ltd',
            'cnpj' => '12345678000190'
        ]);
        $tenant1->domains()->create(['domain' => 'company1.localhost']);

        $tenant1->run(function () {
            User::create([
                'name' => 'John Doe',
                'email' => 'john@company1.com',
                'password' => Hash::make('password123')
            ]);
        });

        // Create Second Tenant
        $tenant2 = Tenant::create([
            'id' => 'company2',
            'name' => 'Company Two Ltd',
            'cnpj' => '98765432000190'
        ]);
        $tenant2->domains()->create(['domain' => 'company2.localhost']);

        $tenant2->run(function () {
            User::create([
                'name' => 'Jane Smith',
                'email' => 'jane@company2.com',
                'password' => Hash::make('password123')
            ]);
        });

        $this->info('Successfully created 2 tenants and 2 users!');
        $this->table(
            ['Tenant ID', 'Name', 'CNPJ', 'Domain', 'User', 'Email', 'Password'],
            [
                ['company1', 'Company One Ltd', '12345678000190', 'company1.localhost', 'John Doe', 'john@company1.com', 'password123'],
                ['company2', 'Company Two Ltd', '98765432000190', 'company2.localhost', 'Jane Smith', 'jane@company2.com', 'password123'],
            ]
        );

        return 0;
    }
}
