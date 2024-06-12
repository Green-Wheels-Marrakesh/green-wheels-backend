<?php

namespace App\Console\Commands;

use App\Enums\PermissionsEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class SetupPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup All app permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permissions = collect(PermissionsEnum::toValues());
        $permissions->each(function (string $permission, int $key) {
            Permission::findOrCreate($permission);
        });
        $this->info(Str::ucfirst(__('permissions was successfully set up')));
    }
}
