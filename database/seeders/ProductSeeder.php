<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            $this->seedProductsForCategory($category);
        }
    }

    public function seedProductsForCategory(Category $category)
    {
        $products = [];

        if ($category->name === 'Guitarras') {
            $products = [
                [
                    'name' => 'Guitarra Elétrica Gibson Les Paul',
                    'description' => 'A lendária guitarra elétrica Gibson Les Paul. Possui corpo em mogno e tampo em maple, proporcionando um timbre encorpado e sustain prolongado.',
                    'image' => 'https://images-americanas.b2w.io/produtos/01/00/sku/9416/2/9416295_1GG.jpg',
                    'price' => 1999.99,
                    'stock' => 10,
                ],
                [
                    'name' => 'Guitarra Acústica Taylor 214ce',
                    'description' => 'A guitarra acústica Taylor 214ce possui tampo em spruce sólido, proporcionando um som brilhante e equilibrado. Perfeita para músicos que buscam um instrumento de alta qualidade.',
                    'image' => 'https://x5music.vteximg.com.br/arquivos/ids/183229-1920-1920/Violao-Taylor-214CE-Eletro-Acustico-Natural.jpg?v=637048517664800000',
                    'price' => 1499.99,
                    'stock' => 5,
                ],
            ];
        } elseif ($category->name === 'Baixos') {
            $products = [
                [
                    'name' => 'Baixo Elétrico Fender Jazz Bass',
                    'description' => 'O baixo elétrico Fender Jazz Bass é conhecido por seu timbre versátil e característico. Possui corpo em alder e captadores single-coil, proporcionando um som rico e definido.',
                    'image' => 'https://harmoniamusical.com.br/media/catalog/product/cache/7c65d8a16c1a9fa15447e0ab5eeb833b/j/a/jazz-bass-player-plus-v-mex-5c-active-pf-hd-180927_1.jpg',
                    'price' => 1799.99,
                    'stock' => 8,
                ],
                [
                    'name' => 'Baixo Acústico Yamaha TRBX304',
                    'description' => 'O baixo acústico Yamaha TRBX304 oferece uma ótima combinação de timbre e conforto. Possui corpo em mahogany e tampo em spruce, proporcionando um som encorpado e ressonante.',
                    'image' => 'https://serenata.vteximg.com.br/arquivos/ids/330547-1000-1000/CB-YAMAHA-TRBX304-CAR_IMG2.jpg?v=638121715864500000',
                    'price' => 1299.99,
                    'stock' => 3,
                ],
            ];
        } elseif ($category->name === 'Baterias') {
            $products = [
                [
                    'name' => 'Bateria Acústica Pearl Export',
                    'description' => 'A bateria acústica Pearl Export é uma escolha popular entre bateristas de todos os níveis. Possui cascos em poplar e oferece uma excelente projeção sonora e durabilidade.',
                    'image' => 'https://www.musitechinstrumentos.com.br/files/pro_41169_e.jpg',
                    'price' => 2999.99,
                    'stock' => 6,
                ],
                [
                    'name' => 'Bateria Eletrônica Roland TD-17KVX',
                    'description' => 'A bateria eletrônica Roland TD-17KVX é perfeita para prática silenciosa ou performances ao vivo. Oferece uma ampla gama de sons realistas e recursos avançados de expressão.',
                    'image' => 'https://d3alv7ekdacjys.cloudfront.net/Custom/Content/Products/11/18/1118245_bateria-eletronica-roland-td-17kvx-com-fonte-bivolt-ms_m2_637795809675490631.jpg',
                    'price' => 3999.99,
                    'stock' => 2,
                ],
            ];
        } elseif ($category->name === 'Teclados') {
            $products = [
                [
                    'name' => 'Teclado Yamaha P-125',
                    'description' => 'O teclado Yamaha P-125 é compacto e portátil, mas oferece um som e uma sensibilidade de teclado excepcionais. Possui amostras de piano de alta qualidade e recursos avançados de acompanhamento.',
                    'image' => 'https://www.musitechinstrumentos.com.br/files/pro_20974_g.jpg',
                    'price' => 899.99,
                    'stock' => 7,
                ],
                [
                    'name' => 'Piano Digital Kawai ES8',
                    'description' => 'O piano digital Kawai ES8 é um instrumento de alta qualidade para músicos exigentes. Possui teclado com ação de martelo, amostras de piano autênticas e uma ampla variedade de sons.',
                    'image' => 'https://intermezzo.vteximg.com.br/arquivos/ids/155998-1000-1000/piano-digital-kawai-es100-intermezzo-spina.jpg?v=636573666513500000',
                    'price' => 2499.99,
                    'stock' => 4,
                ],
            ];
        }

        foreach ($products as $product) {
            $product['category_id'] = $category->id;
            Product::create($product);
        }
    }
}
