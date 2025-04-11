<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Category::create([
            'name' => 'InfoProdutos',
        ]);
        Category::create([
            'name' => 'Produto Físico',
        ]);
        Category::create([
            'name' => 'Eletrodomésticos',
        ]);
        Category::create([
            'name' => 'Produto de Limpeza',
        ]);
    }
}
