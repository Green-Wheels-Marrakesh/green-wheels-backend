<?php

namespace App\Console\Commands;

use App\Enums\RoleEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SetupRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup All app roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roles = collect(RoleEnum::toValues());
        $roles->each(function (string $role, int $key) {
            Role::create([
                'name' => $role,
            ]);
        });
        $this->info(__(Str::ucfirst('roles was successfully set up')));
    }
}
