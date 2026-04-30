<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryHierarchySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Clear all categories and products
        Category::truncate();
        Product::truncate();

        $data = [
            [
                "category_name" => "Food",
                "sub_categories" => [
                    [
                        "name" => "Sembako",
                        "products" => [
                            ["brand" => "Beras Ramos Setra Ramos", "size" => 5, "unit" => "kg"],
                            ["brand" => "Beras Pandan Wangi Cianjur", "size" => 5, "unit" => "kg"],
                            ["brand" => "Minyak Goreng Filma Pouch", "size" => 2000, "unit" => "ml"],
                            ["brand" => "Minyak Goreng Bimoli Klasik", "size" => 1000, "unit" => "ml"],
                            ["brand" => "Minyak Goreng SunCo Refill", "size" => 2000, "unit" => "ml"],
                            ["brand" => "Minyak Goreng Tropical Botol", "size" => 1000, "unit" => "ml"],
                            ["brand" => "Minyak Goreng Sania Pouch", "size" => 2000, "unit" => "ml"],
                            ["brand" => "Gula Pasir Gulaku Kuning", "size" => 1000, "unit" => "gr"],
                            ["brand" => "Gula Pasir Rose Brand", "size" => 1000, "unit" => "gr"],
                            ["brand" => "Gula Halus Claris", "size" => 500, "unit" => "gr"],
                            ["brand" => "Tepung Terigu Segitiga Biru", "size" => 1000, "unit" => "gr"],
                            ["brand" => "Tepung Kunci Biru", "size" => 1000, "unit" => "gr"],
                            ["brand" => "Tepung Cakra Kembar", "size" => 1000, "unit" => "gr"],
                            ["brand" => "Garam Dapur Cap Kapal", "size" => 500, "unit" => "gr"],
                            ["brand" => "Garam Meja Refina", "size" => 250, "unit" => "gr"],
                            ["brand" => "Telur Ayam Negeri Pack", "size" => 10, "unit" => "butir"],
                            ["brand" => "Telur Ayam Kampung Pack", "size" => 6, "unit" => "butir"]
                        ]
                    ],
                    [
                        "name" => "Mie & Instan",
                        "products" => [
                            ["brand" => "Indomie Goreng Spesial", "size" => 85, "unit" => "gr"],
                            ["brand" => "Indomie Rasa Ayam Bawang", "size" => 75, "unit" => "gr"],
                            ["brand" => "Indomie Rasa Soto Mie", "size" => 70, "unit" => "gr"],
                            ["brand" => "Indomie Goreng Aceh", "size" => 90, "unit" => "gr"],
                            ["brand" => "Sedaap Mie Goreng", "size" => 90, "unit" => "gr"],
                            ["brand" => "Sedaap Mie Kuah Soto", "size" => 75, "unit" => "gr"],
                            ["brand" => "Sedaap Mie Kari Spesial", "size" => 75, "unit" => "gr"],
                            ["brand" => "Mie Lemonilo Ayam Bawang", "size" => 70, "unit" => "gr"],
                            ["brand" => "Mie Lemonilo Kari Ayam", "size" => 70, "unit" => "gr"],
                            ["brand" => "Nongshim Shin Ramyun Cup", "size" => 75, "unit" => "gr"],
                            ["brand" => "Nongshim Neoguri Bag", "size" => 120, "unit" => "gr"],
                            ["brand" => "Samyang Hot Chicken Ramen", "size" => 140, "unit" => "gr"],
                            ["brand" => "Samyang Carbonara", "size" => 130, "unit" => "gr"],
                            ["brand" => "Pop Mie Rasa Baso", "size" => 75, "unit" => "gr"],
                            ["brand" => "Pop Mie Goreng Pedas", "size" => 75, "unit" => "gr"],
                            ["brand" => "Sarimi Gelas Ayam Bawang", "size" => 30, "unit" => "gr"],
                            ["brand" => "Supermi Kaldu Ayam", "size" => 75, "unit" => "gr"],
                            ["brand" => "Bihunku Rasa Soto", "size" => 55, "unit" => "gr"],
                            ["brand" => "Mie Burung Dara Pipih", "size" => 200, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Bumbu & Bahan Masak",
                        "products" => [
                            ["brand" => "Masako Rasa Ayam", "size" => 250, "unit" => "gr"],
                            ["brand" => "Royco Kaldu Sapi", "size" => 230, "unit" => "gr"],
                            ["brand" => "Ladaku Merica Bubuk", "size" => 3, "unit" => "gr"],
                            ["brand" => "Saus Sambal ABC Ekstra Pedas", "size" => 335, "unit" => "ml"],
                            ["brand" => "Saus Tomat Indofood", "size" => 275, "unit" => "ml"],
                            ["brand" => "Kecap Manis Bango Refill", "size" => 520, "unit" => "ml"],
                            ["brand" => "Kecap Manis ABC Black Gold", "size" => 400, "unit" => "ml"],
                            ["brand" => "Santan Kara", "size" => 65, "unit" => "ml"],
                            ["brand" => "Santan Sasa Bubuk", "size" => 20, "unit" => "gr"],
                            ["brand" => "Saori Saus Tiram", "size" => 133, "unit" => "ml"],
                            ["brand" => "Saori Saus Teriyaki", "size" => 133, "unit" => "ml"],
                            ["brand" => "Minyak Wijen Lee Kum Kee", "size" => 115, "unit" => "ml"],
                            ["brand" => "Cuka Masak DIXI", "size" => 100, "unit" => "ml"],
                            ["brand" => "Blue Band Serbaguna", "size" => 200, "unit" => "gr"],
                            ["brand" => "Pasta Tomat Del Monte", "size" => 170, "unit" => "gr"],
                            ["brand" => "Bumbu Bamboe Rendang", "size" => 50, "unit" => "gr"],
                            ["brand" => "Bumbu Bamboe Nasi Goreng", "size" => 40, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Makanan Kaleng",
                        "products" => [
                            ["brand" => "Sarden ABC Tomat", "size" => 155, "unit" => "gr"],
                            ["brand" => "Sarden ABC Cabai", "size" => 425, "unit" => "gr"],
                            ["brand" => "Kornet Sapi Pronas", "size" => 198, "unit" => "gr"],
                            ["brand" => "Kornet Sapi Cip", "size" => 340, "unit" => "gr"],
                            ["brand" => "King's Fisher Sarden Goreng", "size" => 155, "unit" => "gr"],
                            ["brand" => "Spam Luncheon Meat", "size" => 340, "unit" => "gr"],
                            ["brand" => "Tuna Maya Chunk in Brine", "size" => 185, "unit" => "gr"],
                            ["brand" => "Mili Whole Mushroom", "size" => 425, "unit" => "gr"],
                            ["brand" => "Del Monte Sweet Corn", "size" => 425, "unit" => "gr"],
                            ["brand" => "Naraya Grass Jelly", "size" => 540, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Snack & Biskuit",
                        "products" => [
                            ["brand" => "Chitato Sapi Panggang", "size" => 68, "unit" => "gr"],
                            ["brand" => "Chitato Ayam Bumbu", "size" => 68, "unit" => "gr"],
                            ["brand" => "Qtela Singkong Original", "size" => 185, "unit" => "gr"],
                            ["brand" => "Qtela Tempe Cabai Rawit", "size" => 55, "unit" => "gr"],
                            ["brand" => "Potabee Seaweed", "size" => 68, "unit" => "gr"],
                            ["brand" => "Piattos Sapi Panggang", "size" => 75, "unit" => "gr"],
                            ["brand" => "Pringles Original", "size" => 107, "unit" => "gr"],
                            ["brand" => "Pringles Sour Cream", "size" => 107, "unit" => "gr"],
                            ["brand" => "Oreo Sandwich Vanilla", "size" => 119, "unit" => "gr"],
                            ["brand" => "Oreo Double Stuf", "size" => 131, "unit" => "gr"],
                            ["brand" => "Roma Kelapa Biskuit", "size" => 300, "unit" => "gr"],
                            ["brand" => "Roma Malkist Abon", "size" => 135, "unit" => "gr"],
                            ["brand" => "Tango Wafer Cokelat", "size" => 130, "unit" => "gr"],
                            ["brand" => "Tango Wafer Vanila", "size" => 130, "unit" => "gr"],
                            ["brand" => "Good Time Choco Chip", "size" => 72, "unit" => "gr"],
                            ["brand" => "Slai O Lai Strawberry", "size" => 128, "unit" => "gr"],
                            ["brand" => "Tic Tac Sapi Panggang", "size" => 90, "unit" => "gr"],
                            ["brand" => "Garuda Kacang Atom", "size" => 100, "unit" => "gr"],
                            ["brand" => "Kusuka Keripik Singkong", "size" => 180, "unit" => "gr"],
                            ["brand" => "Lays Rumput Laut", "size" => 68, "unit" => "gr"],
                            ["brand" => "Cheetos Jagung Bakar", "size" => 75, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Confectionery",
                        "products" => [
                            ["brand" => "Silverqueen Cashew", "size" => 58, "unit" => "gr"],
                            ["brand" => "Silverqueen Almond", "size" => 58, "unit" => "gr"],
                            ["brand" => "Silverqueen Chunky Bar", "size" => 95, "unit" => "gr"],
                            ["brand" => "Cadbury Dairy Milk", "size" => 62, "unit" => "gr"],
                            ["brand" => "Cadbury Hazelnut", "size" => 62, "unit" => "gr"],
                            ["brand" => "Kinder Joy Boys", "size" => 20, "unit" => "gr"],
                            ["brand" => "Kinder Joy Girls", "size" => 20, "unit" => "gr"],
                            ["brand" => "Kopiko Blister", "size" => 32, "unit" => "gr"],
                            ["brand" => "Fisherman's Friend Mint", "size" => 25, "unit" => "gr"],
                            ["brand" => "Mentos Roll Mint", "size" => 37, "unit" => "gr"],
                            ["brand" => "Mentos Fruit", "size" => 37, "unit" => "gr"],
                            ["brand" => "Yupi Gummy Bears", "size" => 45, "unit" => "gr"],
                            ["brand" => "Yupi Burger", "size" => 45, "unit" => "gr"],
                            ["brand" => "Beng-Beng Original", "size" => 20, "unit" => "gr"],
                            ["brand" => "KitKat 4 Finger", "size" => 38, "unit" => "gr"],
                            ["brand" => "Choki Choki Stick", "size" => 10, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Breakfast & Bakery",
                        "products" => [
                            ["brand" => "Sari Roti Tawar Double Soft", "size" => 380, "unit" => "gr"],
                            ["brand" => "Sari Roti Sobek Cokelat", "size" => 216, "unit" => "gr"],
                            ["brand" => "Sari Roti Sandwich Cokelat", "size" => 48, "unit" => "gr"],
                            ["brand" => "Kellogg's Corn Flakes", "size" => 150, "unit" => "gr"],
                            ["brand" => "Kellogg's Froot Loops", "size" => 150, "unit" => "gr"],
                            ["brand" => "Nestle Koko Krunch", "size" => 170, "unit" => "gr"],
                            ["brand" => "Nestle Honey Stars", "size" => 150, "unit" => "gr"],
                            ["brand" => "Quaker Oats Biru (Instant)", "size" => 800, "unit" => "gr"],
                            ["brand" => "Quaker Oats Merah (Cook)", "size" => 800, "unit" => "gr"],
                            ["brand" => "Selai Nutella Hazelnut", "size" => 200, "unit" => "gr"],
                            ["brand" => "Selai Skippy Peanut Butter", "size" => 170, "unit" => "gr"],
                            ["brand" => "Madu Pramuka Randu", "size" => 350, "unit" => "ml"],
                            ["brand" => "Madu Nusantara", "size" => 250, "unit" => "ml"]
                        ]
                    ]
                ]
            ],
            [
                "category_name" => "Beverages",
                "sub_categories" => [
                    [
                        "name" => "Air Mineral",
                        "products" => [
                            ["brand" => "Aqua Botol", "size" => 600, "unit" => "ml"],
                            ["brand" => "Aqua Botol Besar", "size" => 1500, "unit" => "ml"],
                            ["brand" => "Aqua Botol Kecil", "size" => 330, "unit" => "ml"],
                            ["brand" => "Le Minerale Botol", "size" => 600, "unit" => "ml"],
                            ["brand" => "Le Minerale Besar", "size" => 1500, "unit" => "ml"],
                            ["brand" => "Nestle Pure Life", "size" => 600, "unit" => "ml"],
                            ["brand" => "Nestle Pure Life Besar", "size" => 1500, "unit" => "ml"],
                            ["brand" => "Pristine 8.4+", "size" => 400, "unit" => "ml"],
                            ["brand" => "Pristine 8.4+ Besar", "size" => 1500, "unit" => "ml"],
                            ["brand" => "Evian Glass Bottle", "size" => 330, "unit" => "ml"],
                            ["brand" => "Equil Sparkling", "size" => 380, "unit" => "ml"]
                        ]
                    ],
                    [
                        "name" => "Minuman Berperisa",
                        "products" => [
                            ["brand" => "Teh Pucuk Harum", "size" => 350, "unit" => "ml"],
                            ["brand" => "Teh Pucuk Harum Less Sugar", "size" => 350, "unit" => "ml"],
                            ["brand" => "Teh Kotak Jasmine", "size" => 300, "unit" => "ml"],
                            ["brand" => "Teh Botol Sosro Kotak", "size" => 250, "unit" => "ml"],
                            ["brand" => "Teh Botol Sosro Pet", "size" => 450, "unit" => "ml"],
                            ["brand" => "Nu Green Tea Honey", "size" => 450, "unit" => "ml"],
                            ["brand" => "Coca-Cola Pet", "size" => 390, "unit" => "ml"],
                            ["brand" => "Coca-Cola Zero Sugar", "size" => 390, "unit" => "ml"],
                            ["brand" => "Sprite Pet", "size" => 390, "unit" => "ml"],
                            ["brand" => "Fanta Strawberry Pet", "size" => 390, "unit" => "ml"],
                            ["brand" => "Pocari Sweat Botol", "size" => 500, "unit" => "ml"],
                            ["brand" => "Pocari Sweat Kaleng", "size" => 330, "unit" => "ml"],
                            ["brand" => "Mizone Cherry Blossom", "size" => 500, "unit" => "ml"],
                            ["brand" => "Minute Maid Pulpy Orange", "size" => 300, "unit" => "ml"],
                            ["brand" => "Buavita Jambu Kotak", "size" => 250, "unit" => "ml"],
                            ["brand" => "Buavita Apple Kotak", "size" => 250, "unit" => "ml"],
                            ["brand" => "ABC Juice Orange", "size" => 250, "unit" => "ml"],
                            ["brand" => "Ultra Juice Strawberry", "size" => 250, "unit" => "ml"]
                        ]
                    ],
                    [
                        "name" => "Minuman Bubuk",
                        "products" => [
                            ["brand" => "Luwak White Koffie Sachet", "size" => 20, "unit" => "gr"],
                            ["brand" => "Kapal Api Spesial Mix", "size" => 24, "unit" => "gr"],
                            ["brand" => "Nescafe Classic Jar", "size" => 100, "unit" => "gr"],
                            ["brand" => "Nescafe 3 in 1", "size" => 17, "unit" => "gr"],
                            ["brand" => "Torabika Cappuccino", "size" => 25, "unit" => "gr"],
                            ["brand" => "Good Day Mocacinno", "size" => 20, "unit" => "gr"],
                            ["brand" => "Nutrisari Jeruk Peras", "size" => 14, "unit" => "gr"],
                            ["brand" => "Nutrisari Sweet Mango", "size" => 14, "unit" => "gr"],
                            ["brand" => "Milo Bubuk Activ-Go", "size" => 300, "unit" => "gr"],
                            ["brand" => "Ovaltin Choco Malt", "size" => 280, "unit" => "gr"],
                            ["brand" => "MaxTea Tarikk", "size" => 25, "unit" => "gr"],
                            ["brand" => "Teh Celup Sariwangi isi 25", "size" => 46, "unit" => "gr"],
                            ["brand" => "Teh Celup Sosro isi 25", "size" => 50, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Susu & Olahan (UHT)",
                        "products" => [
                            ["brand" => "Ultra Milk Full Cream", "size" => 1000, "unit" => "ml"],
                            ["brand" => "Ultra Milk Full Cream Kecil", "size" => 200, "unit" => "ml"],
                            ["brand" => "Ultra Milk Cokelat", "size" => 250, "unit" => "ml"],
                            ["brand" => "Ultra Milk Strawberry", "size" => 250, "unit" => "ml"],
                            ["brand" => "Greenfields Full Cream", "size" => 1000, "unit" => "ml"],
                            ["brand" => "Greenfields Choco Malt", "size" => 250, "unit" => "ml"],
                            ["brand" => "Indomilk Steril Plain", "size" => 189, "unit" => "ml"],
                            ["brand" => "Frisian Flag UHT Strawberry", "size" => 225, "unit" => "ml"],
                            ["brand" => "Frisian Flag UHT Cokelat", "size" => 225, "unit" => "ml"],
                            ["brand" => "Susu Kental Manis Frisian Flag", "size" => 370, "unit" => "gr"],
                            ["brand" => "Susu Kental Manis Indomilk", "size" => 370, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Minuman Kesehatan",
                        "products" => [
                            ["brand" => "Bear Brand Sterilized Milk", "size" => 189, "unit" => "ml"],
                            ["brand" => "Bear Brand Gold White Tea", "size" => 189, "unit" => "ml"],
                            ["brand" => "You C-1000 Orange", "size" => 140, "unit" => "ml"],
                            ["brand" => "You C-1000 Lemon", "size" => 140, "unit" => "ml"],
                            ["brand" => "Kratingdaeng Energi Kaleng", "size" => 250, "unit" => "ml"],
                            ["brand" => "Red Bull Energy Drink", "size" => 250, "unit" => "ml"],
                            ["brand" => "Hydro Coco Original", "size" => 250, "unit" => "ml"],
                            ["brand" => "Hydro Coco Besar", "size" => 500, "unit" => "ml"],
                            ["brand" => "Adem Sari Ching Ku", "size" => 350, "unit" => "ml"],
                            ["brand" => "Larutan Cap Kaki Tiga Kaleng", "size" => 320, "unit" => "ml"]
                        ]
                    ]
                ]
            ],
            [
                "category_name" => "Fresh & Frozen",
                "sub_categories" => [
                    [
                        "name" => "Dairy Product",
                        "products" => [
                            ["brand" => "Keju Kraft Cheddar", "size" => 160, "unit" => "gr"],
                            ["brand" => "Keju Kraft Singles isi 10", "size" => 150, "unit" => "gr"],
                            ["brand" => "Keju Prochiz Gold", "size" => 160, "unit" => "gr"],
                            ["brand" => "Butter Anchor Unsalted", "size" => 227, "unit" => "gr"],
                            ["brand" => "Butter Elle & Vire", "size" => 200, "unit" => "gr"],
                            ["brand" => "Cimory Yogurt Stick Strawberry", "size" => 40, "unit" => "gr"],
                            ["brand" => "Cimory Yogurt Drink Blueberry", "size" => 240, "unit" => "ml"],
                            ["brand" => "Yakult isi 5", "size" => 325, "unit" => "ml"],
                            ["brand" => "Diamond Milk Fresh Full Cream", "size" => 946, "unit" => "ml"]
                        ]
                    ],
                    [
                        "name" => "Frozen Food",
                        "products" => [
                            ["brand" => "Fiesta Chicken Nugget", "size" => 500, "unit" => "gr"],
                            ["brand" => "Fiesta Spicy Chick", "size" => 500, "unit" => "gr"],
                            ["brand" => "Kanzler Singles Bakso Keju", "size" => 65, "unit" => "gr"],
                            ["brand" => "Kanzler Singles Bakso Original", "size" => 65, "unit" => "gr"],
                            ["brand" => "Kanzler Frankfurter Cheese", "size" => 300, "unit" => "gr"],
                            ["brand" => "So Good Ayam Potong", "size" => 450, "unit" => "gr"],
                            ["brand" => "Belfoods Chicken Stick", "size" => 250, "unit" => "gr"],
                            ["brand" => "Bernardi Bakso Sapi Kuah", "size" => 300, "unit" => "gr"],
                            ["brand" => "Cedea Fish Dumpling Cheese", "size" => 200, "unit" => "gr"],
                            ["brand" => "Cedea Crab Stick", "size" => 250, "unit" => "gr"],
                            ["brand" => "French Fries Aviko", "size" => 1000, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Ice Cream",
                        "products" => [
                            ["brand" => "Walls Magnum Almond", "size" => 90, "unit" => "ml"],
                            ["brand" => "Walls Magnum Classic", "size" => 90, "unit" => "ml"],
                            ["brand" => "Walls Cornetto Disc Chocolate", "size" => 110, "unit" => "ml"],
                            ["brand" => "Aice Mochi Strawberry", "size" => 30, "unit" => "gr"],
                            ["brand" => "Aice Mochi Cokelat", "size" => 30, "unit" => "gr"],
                            ["brand" => "Aice Corn Soft", "size" => 70, "unit" => "gr"],
                            ["brand" => "Campina Neapolitan Tubs", "size" => 350, "unit" => "ml"],
                            ["brand" => "Campina Hula Hula Kacang Hijau", "size" => 60, "unit" => "ml"]
                        ]
                    ],
                    [
                        "name" => "Fresh Produce",
                        "products" => [
                            ["brand" => "Apel Fuji Pack", "size" => 500, "unit" => "gr"],
                            ["brand" => "Pear Century Pack", "size" => 500, "unit" => "gr"],
                            ["brand" => "Bawang Merah Kupas", "size" => 200, "unit" => "gr"],
                            ["brand" => "Bawang Putih Kupas", "size" => 200, "unit" => "gr"],
                            ["brand" => "Sayur Bayam Ikat", "size" => 1, "unit" => "ikat"],
                            ["brand" => "Sayur Kangkung Ikat", "size" => 1, "unit" => "ikat"],
                            ["brand" => "Wortel Lokal Pack", "size" => 500, "unit" => "gr"],
                            ["brand" => "Kentang Dieng Pack", "size" => 500, "unit" => "gr"]
                        ]
                    ]
                ]
            ],
            [
                "category_name" => "Personal Care",
                "sub_categories" => [
                    [
                        "name" => "Hair Care",
                        "products" => [
                            ["brand" => "Pantene Shampoo Anti-Dandruff", "size" => 290, "unit" => "ml"],
                            ["brand" => "Pantene Conditioner Hair Fall", "size" => 180, "unit" => "ml"],
                            ["brand" => "Sunsilk Soft & Smooth", "size" => 160, "unit" => "ml"],
                            ["brand" => "Sunsilk Black Shine", "size" => 160, "unit" => "ml"],
                            ["brand" => "Gatsby Styling Pomade", "size" => 75, "unit" => "gr"],
                            ["brand" => "Gatsby Water Gloss Hard", "size" => 150, "unit" => "gr"],
                            ["brand" => "Clear Men Menthol", "size" => 160, "unit" => "ml"],
                            ["brand" => "Head & Shoulders Cool Menthol", "size" => 160, "unit" => "ml"]
                        ]
                    ],
                    [
                        "name" => "Body Care",
                        "products" => [
                            ["brand" => "Lifebuoy Sabun Cair Refill", "size" => 450, "unit" => "ml"],
                            ["brand" => "Lifebuoy Lemon Fresh", "size" => 450, "unit" => "ml"],
                            ["brand" => "Biore Body Wash Guard", "size" => 450, "unit" => "ml"],
                            ["brand" => "Biore Lovely Sakura", "size" => 450, "unit" => "ml"],
                            ["brand" => "Dettol Original Bar Soap", "size" => 100, "unit" => "gr"],
                            ["brand" => "Rexona Men Roll On Quantum", "size" => 50, "unit" => "ml"],
                            ["brand" => "Rexona Women Free Spirit", "size" => 50, "unit" => "ml"],
                            ["brand" => "Nivea Body Lotion Extra White", "size" => 200, "unit" => "ml"],
                            ["brand" => "Vaseline Healthy White", "size" => 200, "unit" => "ml"],
                            ["brand" => "Citra Hand Body Lotion Pearly", "size" => 230, "unit" => "ml"]
                        ]
                    ],
                    [
                        "name" => "Oral Care",
                        "products" => [
                            ["brand" => "Pepsodent Economy", "size" => 190, "unit" => "gr"],
                            ["brand" => "Pepsodent Whitening", "size" => 190, "unit" => "gr"],
                            ["brand" => "CloseUp Ever Fresh", "size" => 160, "unit" => "gr"],
                            ["brand" => "Formula Pasta Gigi Strong", "size" => 190, "unit" => "gr"],
                            ["brand" => "Sensodyne Repair & Protect", "size" => 100, "unit" => "gr"],
                            ["brand" => "Listerine Cool Mint", "size" => 250, "unit" => "ml"],
                            ["brand" => "Systema Mouthwash", "size" => 250, "unit" => "ml"],
                            ["brand" => "Sikat Gigi Oral-B Soft", "size" => 1, "unit" => "pcs"]
                        ]
                    ],
                    [
                        "name" => "Skin Care & Cosmetics",
                        "products" => [
                            ["brand" => "Wardah Lightening Day Cream", "size" => 30, "unit" => "gr"],
                            ["brand" => "Wardah Night Cream", "size" => 30, "unit" => "gr"],
                            ["brand" => "Garnier Men Face Wash", "size" => 100, "unit" => "ml"],
                            ["brand" => "Ponds White Beauty", "size" => 100, "unit" => "gr"],
                            ["brand" => "Nivea Sun Face Serum", "size" => 30, "unit" => "ml"],
                            ["brand" => "Vaseline Petroleum Jelly", "size" => 50, "unit" => "ml"],
                            ["brand" => "Biore UV Aqua Rich", "size" => 50, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Sanitary",
                        "products" => [
                            ["brand" => "Laurier Relax 40cm isi 8", "size" => 8, "unit" => "pcs"],
                            ["brand" => "Laurier Active Day", "size" => 20, "unit" => "pcs"],
                            ["brand" => "Charm Body Fit Night", "size" => 10, "unit" => "pcs"],
                            ["brand" => "Softex Daun Sirih Night", "size" => 10, "unit" => "pcs"],
                            ["brand" => "Charm Pantyliner Non-perfume", "size" => 40, "unit" => "pcs"]
                        ]
                    ]
                ]
            ],
            [
                "category_name" => "Baby & Kids",
                "sub_categories" => [
                    [
                        "name" => "Baby Food",
                        "products" => [
                            ["brand" => "Milna Bubur Bayi Pisang", "size" => 120, "unit" => "gr"],
                            ["brand" => "Milna Bubur Bayi Beras Merah", "size" => 120, "unit" => "gr"],
                            ["brand" => "Promina Puffs Blueberry", "size" => 15, "unit" => "gr"],
                            ["brand" => "Promina Puffs Banana", "size" => 15, "unit" => "gr"],
                            ["brand" => "SUN Bubur Sereal Kacang Hijau", "size" => 120, "unit" => "gr"],
                            ["brand" => "Cerelac Beras Merah", "size" => 120, "unit" => "gr"],
                            ["brand" => "Bebalak Step 3 Vanilla", "size" => 800, "unit" => "gr"],
                            ["brand" => "SGM Eksplor 1 Plus Madu", "size" => 900, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Baby Care",
                        "products" => [
                            ["brand" => "MamyPoko Pants standar M", "size" => 30, "unit" => "pcs"],
                            ["brand" => "MamyPoko Pants standar L", "size" => 28, "unit" => "pcs"],
                            ["brand" => "Sweety Bronze Pants L", "size" => 30, "unit" => "pcs"],
                            ["brand" => "Merries Good Skin XL", "size" => 26, "unit" => "pcs"],
                            ["brand" => "Zwitsal Baby Powder", "size" => 300, "unit" => "gr"],
                            ["brand" => "Zwitsal Baby Bath Aloe Vera", "size" => 450, "unit" => "ml"],
                            ["brand" => "Cussons Baby Milk Bath", "size" => 400, "unit" => "ml"],
                            ["brand" => "Cussons Baby Oil Blue", "size" => 100, "unit" => "ml"],
                            ["brand" => "Mitu Baby Wipes Blue", "size" => 50, "unit" => "sheets"],
                            ["brand" => "Johnson's Baby Powder", "size" => 300, "unit" => "gr"],
                            ["brand" => "Pigeon Baby Bottle 120ml", "size" => 1, "unit" => "pcs"]
                        ]
                    ],
                    [
                        "name" => "Toys",
                        "products" => [
                            ["brand" => "Hot Wheels Basic Car", "size" => 1, "unit" => "unit"],
                            ["brand" => "Lego City Starter Set", "size" => 1, "unit" => "box"],
                            ["brand" => "Uno Card Game Classic", "size" => 1, "unit" => "set"],
                            ["brand" => "Bola Karet Karakter", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Boneka Plush Kecil", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Mainan Pasir Ajaib", "size" => 200, "unit" => "gr"]
                        ]
                    ]
                ]
            ],
            [
                "category_name" => "Household",
                "sub_categories" => [
                    [
                        "name" => "Cleaning Supplies",
                        "products" => [
                            ["brand" => "Rinso Molto Deterjen Cair", "size" => 750, "unit" => "ml"],
                            ["brand" => "Rinso Bubuk Anti Noda", "size" => 800, "unit" => "gr"],
                            ["brand" => "Attack Jasmine Deterjen Cair", "size" => 750, "unit" => "ml"],
                            ["brand" => "Daia Putih Deterjen Bubuk", "size" => 850, "unit" => "gr"],
                            ["brand" => "Mama Lemon Jeruk Nipis", "size" => 680, "unit" => "ml"],
                            ["brand" => "Sunlight Pencuci Piring", "size" => 755, "unit" => "ml"],
                            ["brand" => "Sunlight Higienis", "size" => 755, "unit" => "ml"],
                            ["brand" => "Super Pell Lantai Apple", "size" => 770, "unit" => "ml"],
                            ["brand" => "Wipol Karbol Wangi", "size" => 750, "unit" => "ml"],
                            ["brand" => "Vixal Pembersih Porselen", "size" => 750, "unit" => "ml"],
                            ["brand" => "So Klin Pemutih", "size" => 500, "unit" => "ml"],
                            ["brand" => "Downy Mistique Refill", "size" => 650, "unit" => "ml"],
                            ["brand" => "Molto All in One Blue", "size" => 720, "unit" => "ml"]
                        ]
                    ],
                    [
                        "name" => "Pesticides",
                        "products" => [
                            ["brand" => "Baygon Aerosol Tea Blossom", "size" => 600, "unit" => "ml"],
                            ["brand" => "Baygon Aerosol Lavender", "size" => 600, "unit" => "ml"],
                            ["brand" => "Hit Aerosol Orange", "size" => 600, "unit" => "ml"],
                            ["brand" => "Hit Expert Aerosol", "size" => 600, "unit" => "ml"],
                            ["brand" => "Vape Mat Alat & Refill", "size" => 1, "unit" => "set"],
                            ["brand" => "Vape Mat Refill isi 30", "size" => 30, "unit" => "pcs"]
                        ]
                    ],
                    [
                        "name" => "Kitchenware",
                        "products" => [
                            ["brand" => "Maspion Frypan Teflon", "size" => 20, "unit" => "cm"],
                            ["brand" => "Maspion Frypan Teflon Besar", "size" => 24, "unit" => "cm"],
                            ["brand" => "Maxim Venice Wok Set", "size" => 1, "unit" => "set"],
                            ["brand" => "Korek Api Gas Tokai", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Spons Cuci Piring Scotch Brite", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Kantong Sampah Hitam", "size" => 10, "unit" => "pcs"]
                        ]
                    ],
                    [
                        "name" => "Air Freshener",
                        "products" => [
                            ["brand" => "Glade Automatic Spray Refill", "size" => 225, "unit" => "ml"],
                            ["brand" => "Stella Hanging Apple", "size" => 7, "unit" => "ml"],
                            ["brand" => "Stella Matic Refill Lemon", "size" => 225, "unit" => "ml"],
                            ["brand" => "Dahlia Kapur Barus", "size" => 150, "unit" => "gr"],
                            ["brand" => "Bagus Camphor", "size" => 150, "unit" => "gr"]
                        ]
                    ]
                ]
            ],
            [
                "category_name" => "Medicine & Health",
                "sub_categories" => [
                    [
                        "name" => "Obat Bebas (OTC)",
                        "products" => [
                            ["brand" => "Panadol Biru (Blister)", "size" => 10, "unit" => "kaplet"],
                            ["brand" => "Panadol Merah (Extra)", "size" => 10, "unit" => "kaplet"],
                            ["brand" => "Bodrex Tablet", "size" => 20, "unit" => "tablet"],
                            ["brand" => "Promag Tablet Kunyah", "size" => 12, "unit" => "tablet"],
                            ["brand" => "Diapet Kapsul", "size" => 4, "unit" => "kapsul"],
                            ["brand" => "Antangin JRG Cair", "size" => 15, "unit" => "ml"],
                            ["brand" => "Tolak Angin Sachet", "size" => 15, "unit" => "ml"],
                            ["brand" => "OBH Combi Batuk Flu", "size" => 100, "unit" => "ml"],
                            ["brand" => "Vicks Formula 44", "size" => 100, "unit" => "ml"],
                            ["brand" => "Insto Eye Drops", "size" => 7.5, "unit" => "ml"]
                        ]
                    ],
                    [
                        "name" => "P3K & Sanitasi",
                        "products" => [
                            ["brand" => "Betadine Antiseptic", "size" => 15, "unit" => "ml"],
                            ["brand" => "Hansaplast Kain Elastis", "size" => 10, "unit" => "pcs"],
                            ["brand" => "Dettol Antiseptic Liquid", "size" => 95, "unit" => "ml"],
                            ["brand" => "Minyak Kayu Putih Cap Lang", "size" => 60, "unit" => "ml"],
                            ["brand" => "Minyak Telon My Baby", "size" => 60, "unit" => "ml"],
                            ["brand" => "Hand Sanitizer Dettol", "size" => 50, "unit" => "ml"],
                            ["brand" => "Masker Sensi Earloop isi 5", "size" => 5, "unit" => "pcs"],
                            ["brand" => "Kapas Wajah Selection", "size" => 35, "unit" => "gr"]
                        ]
                    ],
                    [
                        "name" => "Vitamin & Suplemen",
                        "products" => [
                            ["brand" => "Enervon-C Tablet", "size" => 30, "unit" => "tablet"],
                            ["brand" => "Imboost Force", "size" => 10, "unit" => "kaplet"],
                            ["brand" => "CDR Effervescent Orange", "size" => 10, "unit" => "tablet"],
                            ["brand" => "Sangobion Kapsul", "size" => 10, "unit" => "kapsul"],
                            ["brand" => "Fatigon Spirit", "size" => 6, "unit" => "kaplet"],
                            ["brand" => "Hemaviton Stamina Plus", "size" => 10, "unit" => "kapsul"],
                            ["brand" => "Scott's Emulsion", "size" => 200, "unit" => "ml"]
                        ]
                    ]
                ]
            ],
            [
                "category_name" => "General Merchandise & Digital",
                "sub_categories" => [
                    [
                        "name" => "Stationery (ATK)",
                        "products" => [
                            ["brand" => "Pulpen Standard AE7", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Pulpen Pilot Hi-Tec-C", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Buku Tulis Sinar Dunia 38", "size" => 10, "unit" => "pcs"],
                            ["brand" => "Buku Tulis Kiky Hardcover", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Pensil Faber-Castell 2B", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Penghapus Joyko B40", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Double Tape Joyko", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Gunting Joyko Besar", "size" => 1, "unit" => "pcs"]
                        ]
                    ],
                    [
                        "name" => "Electronics & Hardware",
                        "products" => [
                            ["brand" => "Philips LED Bulb 10W", "size" => 1, "unit" => "unit"],
                            ["brand" => "Philips LED Bulb 12W", "size" => 1, "unit" => "unit"],
                            ["brand" => "Panasonic Battery AA isi 4", "size" => 4, "unit" => "pcs"],
                            ["brand" => "Panasonic Battery AAA isi 2", "size" => 2, "unit" => "pcs"],
                            ["brand" => "Eveready Flashlight Metal", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Kabel Charger Robot Micro USB", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Stop Kontak Uticon 3 Lubang", "size" => 1, "unit" => "pcs"]
                        ]
                    ],
                    [
                        "name" => "Apparel",
                        "products" => [
                            ["brand" => "Kaos Oblong Rider Size L", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Kaos Oblong GT Man Size M", "size" => 1, "unit" => "pcs"],
                            ["brand" => "Sandal Swallow Biru", "size" => 1, "unit" => "pasang"],
                            ["brand" => "Sandal Swallow Hijau", "size" => 1, "unit" => "pasang"],
                            ["brand" => "Kaus Kaki Kantor Hitam", "size" => 1, "unit" => "pasang"],
                            ["brand" => "Payung Lipat Polos", "size" => 1, "unit" => "pcs"]
                        ]
                    ],
                    [
                        "name" => "Digital Goods",
                        "products" => [
                            ["brand" => "Pulsa Telkomsel 50k", "size" => 50000, "unit" => "rupiah"],
                            ["brand" => "Pulsa Indosat 25k", "size" => 25000, "unit" => "rupiah"],
                            ["brand" => "Token PLN 100k", "size" => 100000, "unit" => "rupiah"],
                            ["brand" => "Google Play Voucher 50k", "size" => 50000, "unit" => "rupiah"],
                            ["brand" => "Spotify Premium Voucher", "size" => 1, "unit" => "pcs"]
                        ]
                    ]
                ]
            ],
            [
                "category_name" => "Kategori Khusus",
                "sub_categories" => [
                    [
                        "name" => "Tobacco",
                        "products" => [
                            ["brand" => "Sampoerna Mild isi 16", "size" => 16, "unit" => "batang"],
                            ["brand" => "Sampoerna Menthol isi 16", "size" => 16, "unit" => "batang"],
                            ["brand" => "Gudang Garam Filter isi 12", "size" => 12, "unit" => "batang"],
                            ["brand" => "Gudang Garam Surya 16", "size" => 16, "unit" => "batang"],
                            ["brand" => "Marlboro Red 20", "size" => 20, "unit" => "batang"],
                            ["brand" => "Marlboro Light 20", "size" => 20, "unit" => "batang"],
                            ["brand" => "Djarum Super 12", "size" => 12, "unit" => "batang"],
                            ["brand" => "Djarum Black 16", "size" => 16, "unit" => "batang"],
                            ["brand" => "Camel Yellow 20", "size" => 20, "unit" => "batang"],
                            ["brand" => "Esse Change Blue 20", "size" => 20, "unit" => "batang"]
                        ]
                    ],
                    [
                        "name" => "Alcoholic Beverages",
                        "products" => [
                            ["brand" => "Bir Bintang Pilsener Can", "size" => 330, "unit" => "ml"],
                            ["brand" => "Bir Bintang Crystal Botol", "size" => 330, "unit" => "ml"],
                            ["brand" => "Guinness Stout Bottle", "size" => 325, "unit" => "ml"],
                            ["brand" => "Heineken Can", "size" => 330, "unit" => "ml"],
                            ["brand" => "Anker Bir Can", "size" => 330, "unit" => "ml"],
                            ["brand" => "Singaraja Bir Kaleng", "size" => 500, "unit" => "ml"]
                        ]
                    ]
                ]
            ]
        ];

        foreach ($data as $catIndex => $catData) {
            $parent = Category::create([
                'name' => $catData['category_name'],
                'storage_note' => 'Penyimpanan retail standar',
            ]);

            foreach ($catData['sub_categories'] as $subIndex => $subData) {
                $child = Category::create([
                    'name' => $subData['name'],
                    'parent_id' => $parent->id,
                    'storage_note' => 'Penyimpanan retail standar',
                ]);

                foreach ($subData['products'] as $prodIndex => $prod) {
                    $fullName = $prod['brand'] . ' ' . $prod['size'] . $prod['unit'];
                    
                    // Generate Unique SKU using indices to avoid collisions
                    $sku = strtoupper(substr($catData['category_name'], 0, 1)) . 
                           ($catIndex + 1) . '-' . 
                           strtoupper(substr($subData['name'], 0, 1)) . 
                           ($subIndex + 1) . '-' . 
                           str_pad($prodIndex + 1, 3, '0', STR_PAD_LEFT);

                    Product::create([
                        'category_id' => $child->id,
                        'sku' => $sku,
                        'name' => $fullName,
                        'unit' => $prod['unit'],
                        'barcode' => rand(1000000000, 9999999999),
                        'min_stock_threshold' => 10,
                        'description' => $fullName,
                    ]);
                }
            }
        }

        // 3. Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
