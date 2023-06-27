<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Guitarras',
                'description' => 'Categoria que inclui guitarras elétricas e acústicas.',
            ],
            [
                'name' => 'Baixos',
                'description' => 'Categoria que inclui baixos elétricos e acústicos.',
            ],
            [
                'name' => 'Baterias',
                'description' => 'Categoria que inclui baterias acústicas e eletrônicas.',
            ],
            [
                'name' => 'Teclados',
                'description' => 'Categoria que inclui teclados e pianos.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
