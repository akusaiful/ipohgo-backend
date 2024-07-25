<?php


namespace Themes\Mytravel;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\User\Helpers\PermissionHelper;
use Modules\User\Models\Role;

class UpdaterProvider extends ServiceProvider
{

    public function boot(){
        if (file_exists(storage_path().'/installed') and !app()->runningInConsole()) {
            $this->runUpdateTo130();
        }
    }

    public function runUpdateTo130(){
        $version = '1.3.0';
        if (version_compare(setting_item('MT_update_to_130'), $version, '>=')) return;
        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
        $tables = array_map('current',$tables);
        foreach($tables as $table)
        {
            if(str_contains($table, 'bc_') == true){
                $table_new = str_ireplace("bc_","bravo_",$table);
                if(!Schema::hasTable($table_new)){
                    Schema::rename($table,$table_new);
                }
            }
        }
        $roleAdmin = Role::query()->where('name','administrator')->first();
        $roleAdmin->givePermission(PermissionHelper::all());
        Artisan::call('migrate', [
            '--force' => true,
        ]);
        setting_update_item('MT_update_to_130',$version);
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }
}
