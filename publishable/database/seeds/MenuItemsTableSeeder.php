<?php

use Illuminate\Database\Seeder;

use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

class MenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('name', 'admin')->firstOrFail();

        // Dashboard
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Dashboard", "url" => "", "route" => "voyager.dashboard", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-boat", "color" => "#000000", "parent_id" => null, "order" => "1", ])->save();
        }

        // Categories
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Categories", "url" => "", "route" => "voyager.categories.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-categories", "color" => "", "parent_id" => null, "order" => "2", ])->save();
        }

        // Tags
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Tags", "url" => "", "route" => "voyager.tags.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-puzzle", "color" => "", "parent_id" => null, "order" => "3", ])->save();
        }

        // Events
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Events", "url" => "", "route" => "voyager.events.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-calendar", "color" => "#000000", "parent_id" => null, "order" => "4", ])->save();
        }

        // Bookings
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Bookings", "url" => "", "route" => "voyager.bookings.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-dollar", "color" => "", "parent_id" => null, "order" => "5", ])->save();
        }

        // Commissions
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Commissions", "url" => "", "route" => "voyager.commissions.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-wallet", "color" => "", "parent_id" => null, "order" => "6", ])->save();
        }

        // Taxes
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Taxes", "url" => "", "route" => "voyager.taxes.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-documentation", "color" => "#000000", "parent_id" => null, "order" => "7", ])->save();
        }

        // Users
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Users", "url" => "", "route" => "voyager.users.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-people", "color" => "#000000", "parent_id" => null, "order" => "8", ])->save();
        }

        // Contacts
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Contacts", "url" => "", "route" => "voyager.contacts.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-mail", "color" => "#000000", "parent_id" => null, "order" => "9", ])->save();
        }

        // Media
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Media", "url" => "", "route" => "voyager.media.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-images", "color" => "", "parent_id" => null, "order" => "10", ])->save();
        }

        // Banners
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Banners", "url" => "", "route" => "voyager.banners.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-photo", "color" => "#000000", "parent_id" => null, "order" => "11", ])->save();
        }

        // Pages
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Pages", "url" => "", "route" => "voyager.pages.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-file-text", "color" => "", "parent_id" => null, "order" => "12", ])->save();
        }

        // Blog Posts
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Blog Posts", "url" => "", "route" => "voyager.posts.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-news", "color" => "#000000", "parent_id" => null, "order" => "13", ])->save();
        }
        
        // Header Menu
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Header Menu", "url" => "", "route" => "voyager.menus.builder", "parameters" => 2 ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-list", "color" => "#000000", "parent_id" => null, "order" => "13", ])->save();
        }
        
        // Footer Menu
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Footer Menu", "url" => "", "route" => "voyager.menus.builder", "parameters" => 3 ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-list", "color" => "#000000", "parent_id" => null, "order" => "13", ])->save();
        }

        // Settings
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Settings", "url" => "", "route" => "voyager.settings.index", ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "voyager-settings", "color" => "", "parent_id" => null, "order" => "14", ])->save();
        }


        /* Extra header menu items */
        $menu = Menu::where('name', 'header')->firstOrFail();
        
        // Parent
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Popular Events", "url" => "#", "route" => null, ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "fas fa-star", "color" => "", "parent_id" => null, "order" => "1", ])->save();
        }
        // Child
        $menuItemChild = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Digital Marketing Seminar", "url" => "#", "route" => null, ]);
        if (!$menuItemChild->exists) {
            $menuItemChild->fill(["target" => "_self", "icon_class" => "", "color" => "", "parent_id" => $menuItem->id, "order" => "1", ])->save();
        }
        
        // parent
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Popular Blogs", "url" => "#", "route" => null, ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => "fas fa-blog", "color" => "", "parent_id" => null, "order" => "2", ])->save();
        }
        // child
        $menuItemChild = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Daily Meditation Classes", "url" => "#", "route" => null, ]);
        if (!$menuItemChild->exists) {
            $menuItemChild->fill(["target" => "_self", "icon_class" => "", "color" => "", "parent_id" => $menuItem->id, "order" => "1", ])->save();
        }


        /* Extra footer menu items */
        $menu = Menu::where('name', 'footer')->firstOrFail();
        
        // Parent
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Popular Links", "url" => "#", "route" => null, ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => null, "color" => "", "parent_id" => null, "order" => "1", ])->save();
        }

        // Child
        $menuItemChild = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Catalogue Downloads", "url" => "#", "route" => null, ]);
        if (!$menuItemChild->exists) {
            $menuItemChild->fill(["target" => "_self", "icon_class" => "", "color" => "", "parent_id" => $menuItem->id, "order" => "1", ])->save();
        }
        
        // Parent
        $menuItem = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Top Categories", "url" => "#", "route" => null, ]);
        if (!$menuItem->exists) {
            $menuItem->fill(["target" => "_self", "icon_class" => null, "color" => "", "parent_id" => null, "order" => "2", ])->save();
        }
        // Child
        $menuItemChild = MenuItem::firstOrNew(["menu_id" => $menu->id, "title" => "Boat Parties", "url" => "#", "route" => null, ]);
        if (!$menuItemChild->exists) {
            $menuItemChild->fill(["target" => "_self", "icon_class" => "", "color" => "", "parent_id" => $menuItem->id, "order" => "1", ])->save();
        }
        
    }

    protected function menuItem($field, $for)
    {
        return MenuItem::firstOrNew([$field => $for]);
    }
}