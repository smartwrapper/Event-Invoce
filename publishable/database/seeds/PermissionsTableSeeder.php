<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $keys = [
            'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');
        Permission::generateFor('roles');
        Permission::generateFor('users');
        Permission::generateFor('settings');
        Permission::generateFor('posts');
        Permission::generateFor('pages');
        Permission::generateFor('events');
        Permission::generateFor('curriencies');
        Permission::generateFor('categories');
        Permission::generateFor('taxes');
        Permission::generateFor('banners');
        Permission::generateFor('contacts');
        Permission::generateFor('bookings');
        Permission::generateFor('commissions');
        Permission::generateFor('tags');
                
    }
}