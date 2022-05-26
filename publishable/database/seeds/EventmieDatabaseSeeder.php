<?php

use Illuminate\Database\Seeder;
use Classiebit\Eventmie\Traits\Seedable;

class EventmieDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* ========== ORDER IS IMPORTANT ========== */
        /* ========== KEEP THE ORDER SAME ========== */
        
        // eventmie pro tables
        $this->seed('BannersTableSeeder');
        $this->seed('CategoriesTableSeeder');
        $this->seed('CountriesTableSeeder');
        $this->seed('CurrenciesTableSeeder');
        $this->seed('TaxesTableSeeder');
        $this->seed('RolesTableSeeder');
        $this->seed('UsersTableSeeder');
        $this->seed('EventsTableSeeder');
        $this->seed('TicketsTableSeeder');
        $this->seed('PagesTableSeeder');
        $this->seed('PostsTableSeeder');

        // voyager tables
        $this->seed('DataTypesTableSeeder');
        $this->seed('DataRowsTableSeeder');
        $this->seed('MenusTableSeeder');
        $this->seed('MenuItemsTableSeeder');
        $this->seed('PermissionsTableSeeder');
        $this->seed('PermissionRoleTableSeeder');
        $this->seed('TranslationsTableSeeder');
        $this->seed('SettingsTableSeeder');
    }
}
