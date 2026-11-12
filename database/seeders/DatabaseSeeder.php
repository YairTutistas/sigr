<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Seeder principal para inicializar la Línea Base v1.0.0 (LB-01) del SIGR.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta la carga de datos semilla en la base de datos.
     */
    public function run(): void
    {
        $this->seedUsers();
        $this->seedCategoriesAndDishes();
        $this->seedTables();
    }

    /**
     * Crea los usuarios base del sistema (Administrador, Mesero y Cajero).
     */
    private function seedUsers(): void
    {
        $users = [
            [
                'name' => 'Administrador SIGR',
                'email' => 'admin@sigr.local',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Carlos Mesero',
                'email' => 'mesero@sigr.local',
                'password' => Hash::make('password'),
                'role' => User::ROLE_MESERO,
            ],
            [
                'name' => 'Laura Cajera',
                'email' => 'cajero@sigr.local',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CAJERO,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }

    /**
     * Crea las 5 categorías gastronómicas y los 15 platos iniciales.
     */
    private function seedCategoriesAndDishes(): void
    {
        $catalog = [
            [
                'name' => 'Entradas',
                'description' => 'Aperitivos y entradas frescas para compartir antes del plato principal.',
                'dishes' => [
                    [
                        'name' => 'Ceviche Clásico de Camarón',
                        'description' => 'Camarones tigre marinados en leche de tigre cítrica, cebolla morada, cilantro y maíz cancha.',
                        'price' => 28500.00,
                        'cost' => 11200.00,
                        'image_url' => '/images/dishes/ceviche-camaron.jpg',
                    ],
                    [
                        'name' => 'Carpaccio de Res al Parmesano',
                        'description' => 'Finas láminas de solomillo con reducción balsámica, alcaparras, rúgula y lascas de parmesano.',
                        'price' => 32000.00,
                        'cost' => 13500.00,
                        'image_url' => '/images/dishes/carpaccio-res.jpg',
                    ],
                    [
                        'name' => 'Empanadas Artesanales de Pipián (x4)',
                        'description' => 'Empanadas crocantes rellenas de guiso tradicional de papa y maní, acompañadas de ají de maní.',
                        'price' => 16000.00,
                        'cost' => 5400.00,
                        'image_url' => '/images/dishes/empanadas-pipian.jpg',
                    ],
                ],
            ],
            [
                'name' => 'Platos Fuertes',
                'description' => 'Especialidades de la casa en carnes maduradas, aves y mariscos.',
                'dishes' => [
                    [
                        'name' => 'Lomo al Trapo con Chimichurri de la Casa',
                        'description' => 'Medallón de lomo fino de 300g cocido en costra de sal y tela al carbón, con papas rústicas.',
                        'price' => 58000.00,
                        'cost' => 24000.00,
                        'image_url' => '/images/dishes/lomo-trapo.jpg',
                    ],
                    [
                        'name' => 'Salmón Austral en Salsa de Maracuyá',
                        'description' => 'Filete de salmón a la plancha bañado en reducción dulce de maracuyá sobre puré cremoso de criolla.',
                        'price' => 54000.00,
                        'cost' => 22500.00,
                        'image_url' => '/images/dishes/salmon-maracuya.jpg',
                    ],
                    [
                        'name' => 'Risotto de Hongos Silvestres y Trufa',
                        'description' => 'Arroz arbóreo cremoso con mezcla de portobellos, shiitake, aceite de trufa blanca y queso grana padano.',
                        'price' => 46000.00,
                        'cost' => 17800.00,
                        'image_url' => '/images/dishes/risotto-hongos.jpg',
                    ],
                ],
            ],
            [
                'name' => 'Bebidas',
                'description' => 'Jugos naturales de fruta fresca, limonadas especiales y bebidas sin alcohol.',
                'dishes' => [
                    [
                        'name' => 'Limonada Cerezada Frappé',
                        'description' => 'Refrescante mezcla de limón recién exprimido, cerezas marrasquino y hielo frappé.',
                        'price' => 12500.00,
                        'cost' => 3200.00,
                        'image_url' => '/images/dishes/limonada-cerezada.jpg',
                    ],
                    [
                        'name' => 'Limonada de Coco Cremosa',
                        'description' => 'Leche de coco natural batida con zumo de limón tahití y toque de coco rallado.',
                        'price' => 14000.00,
                        'cost' => 4100.00,
                        'image_url' => '/images/dishes/limonada-coco.jpg',
                    ],
                    [
                        'name' => 'Soda Saborizada de Frutos Rojos y Romero',
                        'description' => 'Agua con gas artesanal infusionada con reducción de mora, fresa, arándanos y rama de romero.',
                        'price' => 13500.00,
                        'cost' => 3800.00,
                        'image_url' => '/images/dishes/soda-frutos-rojos.jpg',
                    ],
                ],
            ],
            [
                'name' => 'Postres',
                'description' => 'Dulces artesanales de autor para cerrar la experiencia gastronómica.',
                'dishes' => [
                    [
                        'name' => 'Volcán Tibio de Chocolate Belga',
                        'description' => 'Bizcocho de chocolate semiamargo al 70% con centro fundido y helado artesanal de vainilla.',
                        'price' => 22000.00,
                        'cost' => 7500.00,
                        'image_url' => '/images/dishes/volcan-chocolate.jpg',
                    ],
                    [
                        'name' => 'Cheesecake de Frutos del Bosque',
                        'description' => 'Tarta cremosa de queso horneada sobre base de galleta crocante y coulis de frutos silvestres.',
                        'price' => 19500.00,
                        'cost' => 6400.00,
                        'image_url' => '/images/dishes/cheesecake-frutos.jpg',
                    ],
                    [
                        'name' => 'Tiramisú Clásico Italiano',
                        'description' => 'Capas de bizcocho savoiardi embebido en espresso y licor de café con crema de mascarpone.',
                        'price' => 21000.00,
                        'cost' => 7100.00,
                        'image_url' => '/images/dishes/tiramisu.jpg',
                    ],
                ],
            ],
            [
                'name' => 'Licores',
                'description' => 'Coctelería de autor, vinos seleccionados y destilados premium.',
                'dishes' => [
                    [
                        'name' => 'Gin Tonic Botánico de Frutos Rojos',
                        'description' => 'Ginebra Londres seca servida con bayas de enebro, frutos deshidratados y agua tónica premium.',
                        'price' => 34000.00,
                        'cost' => 11800.00,
                        'image_url' => '/images/dishes/gin-tonic.jpg',
                    ],
                    [
                        'name' => 'Margarita Tradicional en las Rocas',
                        'description' => 'Tequila reposado 100% agave, licor de naranja triple sec, zumo de limón fresco y borde de sal.',
                        'price' => 31000.00,
                        'cost' => 10500.00,
                        'image_url' => '/images/dishes/margarita.jpg',
                    ],
                    [
                        'name' => 'Copa de Vino Tinto Reserva Cabernet Sauvignon',
                        'description' => 'Vino tinto chileno con crianza de 12 meses en barrica de roble francés, notas a ciruela y vainilla.',
                        'price' => 27000.00,
                        'cost' => 9200.00,
                        'image_url' => '/images/dishes/copa-vino.jpg',
                    ],
                ],
            ],
        ];

        foreach ($catalog as $categoryItem) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($categoryItem['name'])],
                [
                    'name' => $categoryItem['name'],
                    'description' => $categoryItem['description'],
                    'is_active' => true,
                ]
            );

            foreach ($categoryItem['dishes'] as $dishData) {
                Dish::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $dishData['name'],
                    ],
                    [
                        'description' => $dishData['description'],
                        'price' => $dishData['price'],
                        'cost' => $dishData['cost'],
                        'is_available' => true,
                        'image_url' => $dishData['image_url'],
                    ]
                );
            }
        }
    }

    /**
     * Configura las 10 mesas iniciales del salón del restaurante.
     */
    private function seedTables(): void
    {
        $capacities = [
            1 => 2,
            2 => 2,
            3 => 4,
            4 => 4,
            5 => 4,
            6 => 4,
            7 => 6,
            8 => 6,
            9 => 8,
            10 => 10,
        ];

        foreach ($capacities as $number => $capacity) {
            Table::updateOrCreate(
                ['number' => $number],
                [
                    'capacity' => $capacity,
                    'status' => Table::STATUS_DISPONIBLE,
                ]
            );
        }
    }
}

