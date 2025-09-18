<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view users',
            'edit users',
            'delete users',
            'create users',
            'view roles',
            'edit roles',
            'delete roles',
            'create roles',
            'view permission',
            'edit permission',
            'delete permission',
            'create permission',
            'assign permissions user',
            'view banners',
            'edit banners',
            'delete banners',
            'create banners',
            'view abouts',
            'edit abouts',
            'delete abouts',
            'create abouts',
            'view service categories',
            'edit service categories',
            'delete service categories',
            'create service categories',
            'view service details',
            'edit service details',
            'delete service details',
            'create service details',
            'view projects',
            'edit projects',
            'delete projects',
            'create projects',
            'view testimonials',
            'edit testimonials',
            'delete testimonials',
            'create testimonials',
            'view teams',
            'edit teams',
            'delete teams',
            'create teams',
            'view products',
            'edit products',
            'delete products',
            'create products',
            'view blogs',
            'edit blogs',
            'delete blogs',
            'create blogs',
            'view blog categories',
            'edit blog categories',
            'delete blog categories',
            'create blog categories',
            'view testimonials',
            'edit testimonials',
            'delete testimonials',
            'create testimonials',
            'chat-anyone',
            'inbox-access',
            'view contact',
            'delete contact',
            'view seo',
            'edit seo',
            'delete seo',
            'create seo',
            'vview careers',
            'edit careers',
            'delete careers',
            'create careers',
            

        


        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
