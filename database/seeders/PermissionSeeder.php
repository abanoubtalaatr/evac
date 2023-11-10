<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $crudPermissionNames = [
            'Manage admins' => ' أدراة المدراء',
            'Manage roles' => ' أدراة الأدوار والصلاحيات',
            'Manage dashboard' => 'ادارة لوحة التحكم',
            'Manage users' => 'أدراة المستخدمين',
            'Manage contact_us' => 'أدراة تواصل معانا',
            'Manage pages' => 'أدراة الصفحات',
            'Manage partners' => 'أدراة الشركاء',
            'Manage settings' => 'أدارة الاعدادات',
            'Manage sliders' => 'أدارة السسلايدر',
            'Manage car brands' => 'أدارة ماركات السيارات',
            'Manage car modules' => "أدارة موديلات السيارات",
            'Manage car cylinders' => "أدارة محركات السيارات",
            'Manage oil brands' => "أدارة ماركات الزيوت",
            'Manage oils' => "أدارة الزيوت",
            'Manage sub services' => 'أدارة الخدمات الفرعية',
            'Manage services' => 'أدارة الخدمات',
            'Manage orders' => 'أدارة الطبات',
            'Manage opinions' => 'أدارة أراء العملاء',
            'Manage notifications' => 'أدراة الاشعارات'
        ];

        foreach ($crudPermissionNames as $en_permission => $ar_permission) {
                Permission::updateOrCreate(
                    [
                        'name' => $en_permission,
                        'name_ar' =>$ar_permission,
                        'guard_name' => 'admin',
                    ],
                    [
                        'name' => $en_permission,
                        'name_ar' =>$ar_permission,
                        'guard_name' => 'admin',
                    ]
                );
        }

        $role = Role::where(['name' => 'super admin', 'guard_name' => 'admin'])->first();

        $role->givePermissionTo(Permission::where('guard_name', 'admin')->pluck('id'));
    }
}
