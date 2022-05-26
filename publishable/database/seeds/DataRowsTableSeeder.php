<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class DataRowsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        // 1. Roles
        $this->roles();
        
        // 2. Users
        $this->users();
        
        // 3. Menus
        $this->menus();
        
        // 4. Posts start
        $this->posts();
        
        // 5. Pages
        $this->pages();
        
        // 6. Events 
        $this->events();
        
        // 7. Categories 
        $this->categories();
        
        // 8. Taxes 
        $this->taxes();
        
        // 9. Banners 
        $this->banners();
        
        // 10. Contacts 
        $this->contacts();
        
        // 11. Bookings 
        $this->bookings();
        
        // 12. Commissions 
        $this->commissions();
        
        // 13. Tags 
        $this->tags();
    }

    protected function roles()
    {
        $DataType       = DataType::where('slug', 'roles')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "number", "display_name" => "ID", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "name");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Name", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "display_name");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Display Name", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "required|max:255" 
            ] 
        ], "order" => 5, ])->save();
        }
    }

    protected function users()
    {
        $DataType       = DataType::where('slug', 'users')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "number", "display_name" => "ID", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "name");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Name", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "required|max:255" 
            ] 
        ], "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "email");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Email", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "required|email|max:255|unique:users,email,1" 
            ] 
        ], "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "password");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "password", "display_name" => "Password", "required" => 1, "browse" => 0, "read" => 0, "edit" => 1, "add" => 1, "delete" => 0, "details" => "{}", "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "remember_token");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Remember Token", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 7, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "avatar");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "image", "display_name" => "Avatar", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 8, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "user_belongsto_role_relationship");
        // if (!$dataRow->exists) {
            $dataRow->fill(["type" => "relationship", "display_name" => "Role", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 0, "details" => [
        "model" => "TCG\Voyager\Models\Role", 
        "table" => "roles", 
        "type" => "belongsTo", 
        "column" => "role_id", 
        "key" => "id", 
        "label" => "display_name", 
        "pivot_table" => "roles", 
        "pivot" => "0", 
        "taggable" => "0" 
        ], "order" => 10, ])->save();
        // }
        $dataRow = $this->dataRow($DataType, "user_belongstomany_role_relationship");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "relationship", "display_name" => "Roles", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => [
        "model" => "TCG\Voyager\Models\Role", 
        "table" => "roles", 
        "type" => "belongsToMany", 
        "column" => "id", 
        "key" => "id", 
        "label" => "display_name", 
        "pivot_table" => "user_roles", 
        "pivot" => "1", 
        "taggable" => "0" 
        ], "order" => 11, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "settings");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "hidden", "display_name" => "Settings", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 12, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "role_id");
        // if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Role", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "required" 
            ] 
        ], "order" => 9, ])->save();
        // }
        $dataRow = $this->dataRow($DataType, "email_verified_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Email Verified At", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "organisation");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Organisation", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable|max:255" 
            ] 
        ], "order" => 12, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "bank_name");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Bank Name", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable|max:255" 
            ] 
        ], "order" => 13, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "bank_code");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Bank Code", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable|max:255" 
            ] 
        ], "order" => 14, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "bank_branch_name");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Bank Branch Name", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable|max:255" 
            ] 
        ], "order" => 15, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "bank_branch_code");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Bank Branch Code", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable|max:255" 
            ] 
        ], "order" => 16, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "bank_account_number");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Bank Account Number", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable|max:255" 
            ] 
        ], "order" => 17, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "bank_account_name");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Bank Account Name", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable|max:255" 
            ] 
        ], "order" => 18, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "bank_account_phone");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Bank Account Phone", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable|max:255" 
            ] 
        ], "order" => 19, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "address");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Address", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable|max:255" 
            ] 
        ], "order" => 20, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "phone");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Phone", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable|max:255" 
            ] 
        ], "order" => 21, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "fb_access_token");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Fb Access Token", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 22, ])->save();
        }

        // v1.3.1
        $dataRow = $this->dataRow($DataType, "status");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Status", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1" => "Enabled", 
                "0" => "Disabled"
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 22, ])->save();
        }
    }
    
    protected function menus()
    {
        $DataType       = DataType::where('slug', 'menus')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "number", "display_name" => "ID", "required" => 1, "browse" => 1, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "name");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Name", "required" => 1, "browse" => 1, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 4, ])->save();
        }
    }
    
    protected function posts()
    {
        $DataType       = DataType::where('slug', 'posts')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "number", "display_name" => "ID", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "author_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Author", "required" => 1, "browse" => 0, "read" => 1, "edit" => 1, "add" => 0, "delete" => 1, "details" => "{}", "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "category_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Category", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 0, "details" => "{}", "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Title", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "excerpt");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text_area", "display_name" => "Excerpt", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "body");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "rich_text_box", "display_name" => "Body", "required" => 1, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "image");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "image", "display_name" => "Post Image", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "resize" => [
                "width" => "1000", 
                "height" => "null" 
            ], 
        "quality" => "70%", 
        "upsize" => true, 
        "thumbnails" => [
                    [
                    "name" => "medium", 
                    "scale" => "50%" 
                    ], 
                    [
                        "name" => "small", 
                        "scale" => "25%" 
                    ], 
                    [
                            "name" => "cropped", 
                            "crop" => [
                                "width" => "300", 
                                "height" => "250" 
                            ] 
                        ] 
                ] 
        ], "order" => 7, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "slug");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Slug", "required" => 1, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "slugify" => [
                "origin" => "title", 
                "forceUpdate" => true 
            ], 
        "validation" => [
                    "rule" => "unique:posts,slug" 
                ] 
        ], "order" => 8, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "meta_description");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text_area", "display_name" => "Meta Description", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 9, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "meta_keywords");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text_area", "display_name" => "Meta Keywords", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 10, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "status");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Status", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" =>  [
        "default" => "DRAFT", 
        "options" => [
                "PUBLISHED" => "published", 
                "DRAFT" => "draft", 
                "PENDING" => "pending" 
            ] 
        ], "order" => 11, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 12, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 13, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "seo_title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "SEO Title", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 14, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "featured");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "checkbox", "display_name" => "Featured", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 15, ])->save();
        }
    }

    protected function pages()
    {
        $DataType       = DataType::where('slug', 'pages')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "number", "display_name" => "ID", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "author_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Author", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Title", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "excerpt");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text_area", "display_name" => "Excerpt", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "body");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "rich_text_box", "display_name" => "Body", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "slug");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Slug", "required" => 1, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "slugify" => [
                "origin" => "title" 
            ], 
        "validation" => [
                    "rule" => "unique:pages,slug" 
                ] 
        ], "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "meta_description");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Meta Description", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 7, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "meta_keywords");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Meta Keywords", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 8, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "status");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Status", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "INACTIVE", 
        "options" => [
                "INACTIVE" => "INACTIVE", 
                "ACTIVE" => "ACTIVE" 
            ] 
        ], "order" => 9, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 10, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 11, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "image");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "image", "display_name" => "Page Image", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 12, ])->save();
        }
    }

    protected function events()
    {
        $DataType      = DataType::where('slug', 'events')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Id", "required" => 1, "browse" => 1, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Title", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "max:256|unique:events,title" 
            ] 
        ], "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "description");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "rich_text_box", "display_name" => "Description", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "faq");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "rich_text_box", "display_name" => "Faq", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "thumbnail");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "image", "display_name" => "Thumbnail", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 10, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "poster");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "image", "display_name" => "Poster", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 11, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "images");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "multiple_images", "display_name" => "Images", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 12, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "video_link");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Video Link", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 13, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "venue");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Venue", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" =>  [
        "validation" => [
                "rule" => "max:256" 
            ], 
        "display" => [
                    "width" => "6" 
                ] 
        ], "order" => 14, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "address");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text_area", "display_name" => "Address", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
                "validation" => [
                    "rule" => "max:512" 
                ], 
                "display" => [
                    "width" => "6" 
                ]
            ], "order" => 16, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "city");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "City", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "max:256" 
            ], 
        "display" => [
                    "width" => "3" 
                ] 
        ], "order" => 17, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "state");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "State", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "max:256" 
            ], 
        "display" => [
                    "width" => "3" 
                ] 
        ], "order" => 18, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "zipcode");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Zipcode", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "max:64" 
            ], 
        "display" => [
                    "width" => "3" 
                ] 
        ], "order" => 19, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "start_date");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "date", "display_name" => "Start Date", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "date|after_or_equal:tomorrow" 
            ], 
        "display" => [
                    "width" => "6" 
                ] 
        ], "order" => 21, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "end_date");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "date", "display_name" => "End Date", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "date|after_or_equal:start_date" 
            ], 
        "display" => [
                    "width" => "6" 
                ] 
        ], "order" => 22, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "start_time");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "time", "display_name" => "Start Time", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "" 
            ], 
        "display" => [
                    "width" => "6" 
                ] 
        ], "order" => 23, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "end_time");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "time", "display_name" => "End Time", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "" 
            ], 
        "display" => [
                    "width" => "6" 
                ] 
        ], "order" => 24, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "repetitive");
        if (!$dataRow->exists) {
        $dataRow->fill(["type" => "select_dropdown", "display_name" => "Repetitive", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Yes", 
                "0"=>"No", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 25, ])->save();
        }

        
        $dataRow = $this->dataRow($DataType, "featured");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Featured", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1" =>"Yes", 
                "0" =>"No", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 35, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "status");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Status", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" =>  [
        "default" => "1", 
        "options" => [
                "1" =>"Enabled",
                "0" =>"Disabled"
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 36, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "meta_title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Meta Title", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" =>  [
        "validation" => [
                "rule" => "max:256" 
            ] 
        ], "order" => 31, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "meta_description");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text_area", "display_name" => "Meta Description", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 33, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "add_to_facebook");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Add To Facebook", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ] 
        ], "order" => 34, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 37, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 38, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_hasone_user_relationship");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "relationship", "display_name" => "Organiser", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "model" => "Classiebit\Eventmie\Models\User", 
        "table" => "users", 
        "type" => "belongsTo", 
        "column" => "user_id", 
        "key" => "id", 
        "label" => "name", 
        "pivot_table" => "categories", 
        "pivot" => "0", 
        "taggable" => "0" 
        ], "order" => 7, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_belongsto_category_relationship");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "relationship", "display_name" => "Category", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "model" => "Classiebit\Eventmie\Models\Category", 
        "table" => "categories", 
        "type" => "belongsTo", 
        "column" => "category_id", 
        "key" => "id", 
        "label" => "name", 
        "pivot_table" => "categories", 
        "pivot" => "0", 
        "taggable" => "0" 
        ], "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_belongsto_country_relationship");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "relationship", "display_name" => "Country", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "model" => "Classiebit\Eventmie\Models\Country", 
        "table" => "countries", 
        "type" => "belongsTo", 
        "column" => "country_id", 
        "key" => "id", 
        "label" => "country_name", 
        "pivot_table" => "categories", 
        "pivot" => "0", 
        "taggable" => "0" 
        ], "order" => 20, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "country_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Country Id", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "integer" 
            ] 
        ], "order" => 15, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "category_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Category Id", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "integer" 
            ] 
        ], "order" => 29, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "user_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "User Id", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "integer" 
            ] 
        ], "order" => 30, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "slug");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Slug", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "max:512" 
            ], 
        "slugify" => [
                    "origin" => "title" 
                ], 
        "readonly" => true 
        ], "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "price_type");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Price Type", "required" => 1, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 31, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "latitude");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Latitude", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 32, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "longitude");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Longitude", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 33, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "item_sku");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Item Sku", "required" => 1, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 34, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "publish");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Publish", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Published", 
                "0"=>"Draft", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 35, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "is_publishable");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Is Publishable", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 36, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "merge_schedule");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Payment Frequency", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Monthly/Weekly", 
                "0"=>"Full Advance", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 37, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "meta_keywords");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Meta Keywords", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 23, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "online_location");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Online Location", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 36, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "excerpt");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Excerpt", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 2, ])->save();
        }
    }

    protected function categories()
    {
        $DataType      = DataType::where('slug', 'categories')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Id", "required" => 1, "browse" => 1, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "name");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Name", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" =>  [
        "validation" => [
                "rule" => "required|max:64|unique:categories,name,1" 
            ] 
        ], "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "slug");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Slug", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "required|max:128|unique:categories,slug,1" 
            ], 
        "slugify" => [
                    "origin" => "name" 
                ] 
        ], "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 7, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 8, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "status");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Status", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1" => "Enabled", 
                "0" => "Disabled"
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 9, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "thumb");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "image", "display_name" => "Thumb (480x270 px)", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "resize" => [
                    "width" => "480", 
                    "height" => "270" 
                ], 
        "quality" => "70%", 
        "upsize" => true 
        ], "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "image");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "image", "display_name" => "Image", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "resize" => [
                    "width" => "157", 
                    "height" => "467" 
                ], 
        "quality" => "70%", 
        "upsize" => true 
        ], "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "template");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "radio_btn", "display_name" => "Template", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => [
        "default" => "1", 
        "options" => [
                "Template 1", 
                "Template 2", 
                "Template 3" 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 6, ])->save();
        }
    }

    protected function taxes()
    {
        $DataType      = DataType::where('slug', 'taxes')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Id", "required" => 1, "browse" => 1, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Title", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "required|max:64|unique:categories,name,1" 
            ] 
        ], "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "rate_type");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Rate Type", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "percent", 
        "options" => [
                "percent" => "Percent", 
                "fixed" => "Fixed" 
            ], 
        "validation" => [
                    "rule" => "required|in:fixed,percent" 
                ] 
        ], "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "rate");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Rate", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "required|numeric" 
            ] 
        ], "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "net_price");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Net Price", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "including", 
        "options" => [
                "including" => "Including", 
                "excluding" => "Excluding" 
            ], 
        "validation" => [
                    "rule" => "required|in:including,excluding" 
                ] 
        ], "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "status");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Status", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Enabled", 
                "0"=>"Disabled", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 7, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 8, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "admin_tax");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Admin Tax", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Yes", 
                "0"=>"No", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 9, ])->save();
        }
    }

    protected function banners()
    {
        $DataType      = DataType::where('slug', 'banners')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Id", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Title", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "max:64" 
            ] 
        ], "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "subtitle");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Subtitle", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "max:64" 
            ] 
        ], "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "image");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "image", "display_name" => "Image", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "resize" => [
                "width" => "1920", 
                "height" => "1080" 
            ], 
        "quality" => "60%", 
        "upsize" => true 
        ], "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "status");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Status", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Enabled",
                "0"=>"Disabled", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 7, ])->save();
        }

        // v1.6
        $dataRow = $this->dataRow($DataType, "button_url");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Button URL", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "button_title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Button Title", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "order");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "number", "display_name" => "Order", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 4, ])->save();
        }
    }

    protected function contacts()
    {
        $DataType      = DataType::where('slug', 'contacts')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "number", "display_name" => "Id", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "name");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Name", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "email");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Email", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Title", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "message");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Message", "required" => 1, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "read");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Read", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "read_by");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "number", "display_name" => "Read By", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 7, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 8, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 9, ])->save();
        }
    }
    
    protected function bookings()
    {
        $DataType      = DataType::where('slug', 'bookings')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Id", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "customer_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Customer Id", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 7, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "organiser_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Organiser Id", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 8, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Event Id", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 9, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "ticket_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Ticket Id", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 10, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "quantity");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Quantity", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 12, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "price");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Price", "required" => 1, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 13, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "tax");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Tax", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 14, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "net_price");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Net Price", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 15, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "status");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Status", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 0, "delete" => 0, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Enabled",
                "0"=>"Disabled", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 26, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 27, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 28, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Event Title", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" =>  [
        "disabled" => "true" 
        ], "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_start_date");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Event Start Date", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 17, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_end_date");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Event End Date", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 18, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_start_time");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Event Start Time", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 19, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_end_time");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Event End Time", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 20, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_repetitive");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Event Repetitive", "required" => 1, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 21, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "ticket_title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Ticket Title", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" =>  [
        "disabled" => "true" 
        ], "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "ticket_price");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Ticket Price", "required" => 1, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 11, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_category");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Event Category", "required" => 1, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "booking_cancel");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Booking Cancel", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 0, "delete" => 0, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Cancellation Pending", 
                "2"=>"Approved", 
                "3"=>"Refunded", 
                "0"=>"No Cancellation", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 25, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "item_sku");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Item Sku", "required" => 1, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "order_number");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Order Number", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => [
        "disabled" => "true" 
        ], "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "transaction_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Transaction Id", "required" => 1, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 22, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "customer_name");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Customer Name", "required" => 1, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => [
        "disabled" => "true" 
        ], "order" => 23, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "customer_email");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Customer Email", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => [
        "disabled" => "true" 
        ], "order" => 24, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "currency");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Currency", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 16, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "checked_in");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Checked In", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Yes", 
                "0"=>"No", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 29, ])->save();
        }

        // v1.3.1
        $dataRow = $this->dataRow($DataType, "payment_type");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Payment Type", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "online", 
        "options" => [
                "online"=>"Online", 
                "offline"=>"Offline", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 30, ])->save();
        }

        // v1.4.0
        $dataRow = $this->dataRow($DataType, "is_paid");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Is Paid", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Yes", 
                "0"=>"No", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 31, ])->save();
        }
    }

    protected function commissions()
    {
        $DataType      = DataType::where('slug', 'commissions')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Id", "required" => 1, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "organiser_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Organiser Id", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "booking_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Booking Id", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "admin_commission");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Admin Commission", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "customer_paid");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Customer Paid", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "organiser_earning");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Organiser Earning", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "transferred");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Transferred", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 7, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "month_year");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Month Year", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 8, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "status");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Status", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1"=>"Enabled", 
                "0"=>"Disabled", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 9, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 0, "delete" => 1, "details" => "{}", "order" => 10, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 0, "read" => 0, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 11, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "event_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Event Id", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 2, ])->save();
        }
    }

    protected function tags()
    {
        $DataType      = DataType::where('slug', 'tags')->firstOrFail();

        // add rows (auto-generated)
        $dataRow = $this->dataRow($DataType, "id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Id", "required" => 1, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 1, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Title", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "required|max:512" 
            ], 
        "placeholder" => "e.g John Doe" 
        ], "order" => 4, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "type");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Type", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" =>  [
        "validation" => [
                "rule" => "required|max:512" 
            ], 
        "placeholder" => "e.g speaker" 
        ], "order" => 5, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "organizer_id");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "organiser", "display_name" => "Organizer", "required" => 1, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => "{}", "order" => 2, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "description");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "rich_text_box", "display_name" => "Description", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "description" => "Fill this only if you selected Profile Page - Yes" 
        ], "order" => 9, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "image");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "image", "display_name" => "Image", "required" => 0, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "resize" => [
                    "width" => "512", 
                    "height" => "512" 
                ], 
        "quality" => "70%", 
        "upsize" => true, 
        "description" => "upload image of size 512x512 pixels." 
        ], "order" => 3, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "sub_title");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Sub Title", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "placeholder" => "e.g Business coach & Entrepreneur" 
        ], "order" => 6, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "website");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Website", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "placeholder" => "e.g http://example.com" 
        ], "order" => 7, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "is_page");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Profile Page", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1"=> "Yes", 
                "0"=> "No", 
            ], 
        "validation" => [
                    "rule" => "required" 
                ], 
        "description" => "Select Yes only if you want to show this tag on it's profile page." 
        ], "order" => 8, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "email");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Email", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "placeholder" => "Fill this only if you selected Profile Page - Yes" 
        ], "order" => 11, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "phone");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Phone", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "placeholder" => "Fill this only if you selected Profile Page - Yes" 
        ], "order" => 10, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "facebook");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Facebook", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "placeholder" => "Fill this only if you selected Profile Page - Yes" 
        ], "order" => 12, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "instagram");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Instagram", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "placeholder" => "Fill this only if you selected Profile Page - Yes" 
        ], "order" => 13, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "twitter");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Twitter", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "placeholder" => "Fill this only if you selected Profile Page - Yes" 
        ], "order" => 14, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "linkedin");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "text", "display_name" => "Linkedin", "required" => 0, "browse" => 0, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "validation" => [
                "rule" => "nullable" 
            ], 
        "placeholder" => "Fill this only if you selected Profile Page - Yes" 
        ], "order" => 15, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "status");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "select_dropdown", "display_name" => "Status", "required" => 1, "browse" => 1, "read" => 1, "edit" => 1, "add" => 1, "delete" => 1, "details" => [
        "default" => "1", 
        "options" => [
                "1" => "Enabled",
                "0" => "Disabled"
            ], 
        "validation" => [
                    "rule" => "required" 
                ] 
        ], "order" => 16, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "created_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Created At", "required" => 0, "browse" => 0, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 17, ])->save();
        }
        $dataRow = $this->dataRow($DataType, "updated_at");
        if (!$dataRow->exists) {
            $dataRow->fill(["type" => "timestamp", "display_name" => "Updated At", "required" => 0, "browse" => 1, "read" => 1, "edit" => 0, "add" => 0, "delete" => 0, "details" => "{}", "order" => 18, ])->save();
        }
    }



    /**
     * [dataRow description].
     *
     * @param [type] $type  [description]
     * @param [type] $field [description]
     *
     * @return [type] [description]
     */
    protected function dataRow($type, $field)
    {
        return DataRow::firstOrNew([
            'data_type_id' => $type->id,
            'field'        => $field,
        ]);
    }
}