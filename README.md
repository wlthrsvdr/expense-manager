Process
1. Run Composer Install/Update
2. Run the Migration (php artisan migrate)
3. Run the seeder for the default admin account (php artisan db:seed --class=AdminAccountsSeeder). Please see below for the default account of admin.
 Email : admin@admin.com
 Password: admin
4. To login just go to  127.0.0.1:8000/admin/login for the admin login and   127.0.0.1:8000/user/login for the user
