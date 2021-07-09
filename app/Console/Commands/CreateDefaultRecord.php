<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\PeopleCulture;
use Illuminate\Console\Command;
use App\Interfaces\UserStatusInterface;
use App\Interfaces\AccountTypeInterface;

class CreateDefaultRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-default-record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Default Record';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $RoleItems = [
            [
                'slug' => 'user',
                'name' => 'User',
                'description' => 'User Role',
                'level' => 1
            ],
            [
                'slug' => 'admin',
                'name' => 'Admin',
                'description' => 'Admin Role',
                'level' => 2
            ],
            [
                'slug' => 'developer',
                'name' => 'Developer',
                'description' => 'Developer Role',
                'level' => 3
            ],
        ];

        $Permissionitems = [
            [
                'name'        => 'Can View Users',
                'slug'        => 'view.users',
                'description' => 'Can view users',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Create Users',
                'slug'        => 'create.users',
                'description' => 'Can create new users',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Edit Users',
                'slug'        => 'edit.users',
                'description' => 'Can edit users',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Delete Users',
                'slug'        => 'delete.users',
                'description' => 'Can delete users',
                'model'       => 'Permission',
            ],
        ];

        $peopleAndCultures = [
            [
                'key' => 'Key_1',
                'image' => "https://tdms-backend.herokuapp.com/images/culture/cu1.jpg",
                'created_by' => 1
            ],
            [
                'key' => 'Key_2',
                'image' => "https://tdms-backend.herokuapp.com/images/culture/cu2.jpg",
                'created_by' => 1
            ],
            [
                'key' => 'Key_3',
                'image' => "https://tdms-backend.herokuapp.com/images/culture/cu3.jpg",
                'created_by' => 1
            ],
            [
                'key' => 'Key_4',
                'image' => "https://tdms-backend.herokuapp.com/images/culture/cu4.jpg",
                'created_by' => 1
            ],
            [
                'key' => 'Key_5',
                'image' => "https://tdms-backend.herokuapp.com/images/culture/cu5.jpg",
                'created_by' => 1
            ],
            [
                'key' => 'Key_6',
                'image' => "https://tdms-backend.herokuapp.com/images/culture/cu6.jpg",
                'created_by' => 1
            ]
        ];

        /*
        * Add Role Items
        */
        dump("Running Roles table seeder");
        foreach ($RoleItems as $RoleItem) {
            $newRoleItem = config('roles.models.role')::where('slug', '=', $RoleItem['slug'])->first();
            if ($newRoleItem === null) {
                $newRoleItem = config('roles.models.role')::create([
                    'name'          => $RoleItem['name'],
                    'slug'          => $RoleItem['slug'],
                    'description'   => $RoleItem['description'],
                    'level'         => $RoleItem['level'],
                ]);
            }
        }
        dump("Role table seeder ran successfully");

        /*
         * Add Permission Items
         *
         */
        dump("Running Permission table seeder");
        foreach ($Permissionitems as $Permissionitem) {
            $newPermissionitem = config('roles.models.permission')::where('slug', '=', $Permissionitem['slug'])->first();
            if ($newPermissionitem === null) {
                $newPermissionitem = config('roles.models.permission')::create([
                    'name'          => $Permissionitem['name'],
                    'slug'          => $Permissionitem['slug'],
                    'description'   => $Permissionitem['description'],
                    'model'         => $Permissionitem['model'],
                ]);
            }
        }
        dump("Permission table seeder ran successfully");


        dump("Running User table seeder");
        $userRole = config('roles.models.role')::where('name', '=', 'User')->first();
        $developerRole = config('roles.models.role')::where('name', '=', 'Developer')->first();
        $AdminRole = config('roles.models.role')::where('name', '=', 'Admin')->first();
        $permissions = config('roles.models.permission')::all();

        /*
         * Add Record
         *
         */
        if (User::where('email', '=', 'admin@tdms.com')->first() === null) {
            $newUser = User::create([
                'firstname'     => 'Tourist',
                'lastname'     => 'Admin',
                'email'    => 'admin@tdms.com',
                'country' => 'Nigeria',
                'state' => 'Lagos',
                'username' => 'tdmsadmin',
                'account_type' => AccountTypeInterface::INDIVIDUAL,
                'phoneno' => '09088449933',
                'address' => 'Lagos Nigeria',
                'is_verified' => true,
                'is_active' => UserStatusInterface::ACTIVE,
                'can_login' => true,
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]);

            $newUser->attachRole($AdminRole);
            foreach ($permissions as $permission) {
                $newUser->attachPermission($permission);
            }
        }

        if (User::where('email', '=', 'developer@tdms.com')->first() === null) {
            $newUser = User::create([
                'firstname'     => 'Developer',
                'school_id'     => 1,
                'lastname'     => 'SBSC',
                'email'    => 'developer@tdms.com',
                'country' => 'Nigeria',
                'state' => 'Lagos',
                'username' => 'tdmsdeveloper',
                'account_type' => AccountTypeInterface::INDIVIDUAL,
                'phoneno' => '09051449933',
                'address' => 'Lagos Nigeria',
                'is_verified' => true,
                'is_active' => UserStatusInterface::ACTIVE,
                'can_login' => true,
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]);

            $newUser->attachRole($developerRole);
            foreach ($permissions as $permission) {
                $newUser->attachPermission($permission);
            }
        }

        if (User::where('email', '=', 'user@tdms.com')->first() === null) {
            $newUser = User::create([
                'firstname'     => 'User',
                'school_id'     => 1,
                'lastname'     => 'Tdms',
                'email'    => 'user@tdms.com',
                'phoneno' => '09088449693',
                'country' => 'Nigeria',
                'state' => 'Lagos',
                'username' => 'tdmsuser',
                'account_type' => AccountTypeInterface::INDIVIDUAL,
                'address' => 'Lagos Nigeria',
                'is_verified' => true,
                'is_active' => UserStatusInterface::ACTIVE,
                'can_login' => true,
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]);

            $newUser->attachRole($userRole);
            foreach ($permissions as $permission) {
                $newUser->attachPermission($permission);
            }
        }
        dump("User table seeder ran successfully");

        dump("Running People and Culture table seeder");
        foreach ($peopleAndCultures as $peopleAndCulture) {
            $peopleAndCultureExist = PeopleCulture::where('key', '=', $peopleAndCulture['key'])->first();
            if ($peopleAndCultureExist === null) {
                $newpeopleAndCulture = PeopleCulture::create([
                    'key'          => $peopleAndCulture['key'],
                    'image'          => $peopleAndCulture['image'],
                    'created_by'          => $peopleAndCulture['created_by']
                ]);
            }
        }
        dump("People and Culture table seeder ran successfully");
    }
}
