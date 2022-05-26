<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $singular       = 'user';
        $slug           = 'users';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),
                'icon'                  => 'voyager-person',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\User',
                'policy_name'           => 'TCG\\Voyager\\Policies\\UserPolicy',
                'controller'            => '\\Classiebit\\Eventmie\\Http\\Controllers\\Voyager\\VoyagerUserController',
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 1,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":"email","scope":null}'),
            ])->save();
        // }

        $singular       = 'menu';
        $slug           = 'menus';
        $dataType       = $this->dataType('slug', $slug);
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),
                'icon'                  => 'voyager-list',
                'model_name'            => 'TCG\\Voyager\\Models\\Menu',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }

        $singular       = 'role';
        $slug           = 'roles';
        $dataType       = $this->dataType('slug', $slug);
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),
                'icon'                  => 'voyager-lock',
                'model_name'            => 'TCG\\Voyager\\Models\\Role',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }

        $singular       = 'post';
        $slug           = 'posts';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),
                'icon'                  => 'voyager-news',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\Post',
                'policy_name'           => 'TCG\\Voyager\\Policies\\PostPolicy',
                'controller'            => NULL,
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 1,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":"title","scope":null}'),
            ])->save();
        // }

        $singular       = 'page';
        $slug           = 'pages';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),
                'icon'                  => 'voyager-file-text',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\Page',
                'policy_name'           => NULL,
                'controller'            => NULL,
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 0,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":"title","scope":null}'),
            ])->save();
        // }
        
        $singular       = 'event';
        $slug           = 'events';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),

                'icon'                  => 'voyager-calendar',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\Event',
                'policy_name'           => NULL,
                'controller'            => '\\Classiebit\\Eventmie\\Http\\Controllers\\Voyager\\EventsController',
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 1,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":"title","scope":null}'),
            ])->save();
        // }
        
        $singular       = 'category';
        $slug           = 'categories';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),

                'icon'                  => 'voyager-categories',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\Category',
                'policy_name'           => NULL,
                'controller'            => NULL,
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 1,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":"name","scope":null}'),
            ])->save();
        // }
        
        $singular       = 'tax';
        $slug           = 'taxes';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),

                'icon'                  => 'voyager-documentation',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\Tax',
                'policy_name'           => NULL,
                'controller'            => NULL,
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 1,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":"title","scope":null}'),
            ])->save();
        // }
        
        $singular       = 'banner';
        $slug           = 'banners';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),

                'icon'                  => 'voyager-photo',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\Banner',
                'policy_name'           => NULL,
                'controller'            => NULL,
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 0,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":"title","scope":null}'),
            ])->save();
        // }
        
        $singular       = 'contact';
        $slug           = 'contacts';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),

                'icon'                  => 'voyager-mail',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\Contact',
                'policy_name'           => NULL,
                'controller'            => '\\Classiebit\\Eventmie\\Http\\Controllers\\Voyager\\ContactsController',
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 1,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":"name","scope":null}'),
            ])->save();
        // }
        
        $singular       = 'booking';
        $slug           = 'bookings';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),

                'icon'                  => 'voyager-dollar',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\Booking',
                'policy_name'           => NULL,
                'controller'            => '\\Classiebit\\Eventmie\\Http\\Controllers\\Voyager\\BookingsController',
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 1,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":"event_title","scope":null}'),
            ])->save();
        // }
        
        $singular       = 'commission';
        $slug           = 'commissions';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),

                'icon'                  => 'voyager-wallet',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\Commission',
                'policy_name'           => NULL,
                'controller'            => '\\Classiebit\\Eventmie\\Http\\Controllers\\Voyager\\CommissionsController',
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 0,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":null,"scope":null}'),
            ])->save();
        // }
        
        $singular       = 'tag';
        $slug           = 'tags';
        $dataType       = $this->dataType('slug', $slug);
        // if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => $slug,
                'slug'                  => $slug,
                'display_name_singular' => ucfirst($singular),
                'display_name_plural'   => ucfirst($slug),

                'icon'                  => 'voyager-puzzle',
                'model_name'            => 'Classiebit\\Eventmie\\Models\\Tag',
                'policy_name'           => NULL,
                'controller'            => NULL,
                'description'           => NULL,
                'generate_permissions'  => 1,
                'server_side'           => 1,
                'details'               => json_decode('{"order_column":"updated_at","order_display_column":"id","order_direction":"desc","default_search_key":"title","scope":null}'),
            ])->save();
        // }

        // v1.4 update
        $singular               = 'page';
        $slug                   = 'pages';
        $dataType               = $this->dataType('slug', $slug);
        $dataType->model_name   = 'Classiebit\\Eventmie\\Models\\Page';
        $dataType->save();
    }

    /**
     * [dataType description].
     *
     * @param [type] $field [description]
     * @param [type] $for   [description]
     *
     * @return [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
}