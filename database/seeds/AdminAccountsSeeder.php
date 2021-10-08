<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_account = new User;

        $admin_account->name = "Master Account";
        $admin_account->email = "admin@admin.com";
        $admin_account->password = bcrypt("admin");
        $admin_account->user_role = "Admin";

        $admin_account->save();
    }
}
