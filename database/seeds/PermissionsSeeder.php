<?php

use App\Models\Users\Permission;
use App\Models\Users\PermissionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('permissions')->delete();

        DB::table('permissions_groups')->delete();


        $permissionGroups = [



            'users' => [
                [
                    'title' => "roles",
                ],

                [
                    'title' => "users",
                ],
            ],

            'organizations' => [
                [
                    'title' => "organizations",
                ],
            ],

            'settings' => [
                [
                    'title' => "financial_policy",
                ],

                [
                    'title' => "departments",
                ],
                [
                    'title' => "banks",
                ],
            ],



        ];


        collect($permissionGroups)->map(function ($permissions, $permissionGroupTitle) {
            $group = PermissionGroup::create([
                'title' => $permissionGroupTitle
            ]);
            collect($permissions)->map(function ($item) use ($group) {
                Permission::create([
                    'title'    => $item['title'],
                    'group_id' => $group->id
                ]);
            });

        });
    }
}
