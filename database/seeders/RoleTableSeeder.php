<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_employee = new Role();
        $role_employee->name = 'admin';
        $role_employee->description = 'Full quyền quản trị';
        $role_employee->save();
 
        $role_employee = new Role();
        $role_employee->name = 'ctv';
        $role_employee->description = 'Người giúp tăng doanh số mà không cần bỏ tiền ra nhập hàng => Chỉ việc tìm khách hàng ';
        $role_employee->save();
 
        $role_manager = new Role();
        $role_manager->name = 'other';
        $role_manager->description = 'Những người khác , sắp tới sẽ thêm ví dụ như người muốn vào test websites';
        $role_manager->save();
    }
}
