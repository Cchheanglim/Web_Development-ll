<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', User::ROLE_SELLER)->get();

        $sampleProducts = [
            ['title' => 'Mountain Bike (21-speed)', 'price' => 180.00, 'condition' => 'used', 'category' => 'Sports & Outdoors'],
            ['title' => 'Wireless Noise-Cancelling Headphones', 'price' => 95.00, 'condition' => 'new', 'category' => 'Electronics'],
            ['title' => 'IKEA Study Desk', 'price' => 45.00, 'condition' => 'used', 'category' => 'Home & Living'],
            ['title' => 'Graphing Calculator TI-84', 'price' => 60.00, 'condition' => 'used', 'category' => 'Books & Study'],
            ['title' => 'Mini Fridge (3.2 cu ft)', 'price' => 70.00, 'condition' => 'used', 'category' => 'Home & Living'],
            ['title' => 'Acoustic Guitar', 'price' => 120.00, 'condition' => 'used', 'category' => 'Others'],
            ['title' => 'Standing Desk Lamp', 'price' => 18.00, 'condition' => 'new', 'category' => 'Home & Living'],
            ['title' => 'Electric Kettle', 'price' => 15.00, 'condition' => 'new', 'category' => 'Home & Living'],
            ['title' => 'Used Textbook Bundle (Calculus I & II)', 'price' => 40.00, 'condition' => 'used', 'category' => 'Books & Study'],
            ['title' => 'Bluetooth Speaker', 'price' => 25.00, 'condition' => 'new', 'category' => 'Electronics'],
            ['title' => 'Office Chair', 'price' => 55.00, 'condition' => 'used', 'category' => 'Home & Living'],
            ['title' => 'Dorm Room Rug 5x7', 'price' => 22.00, 'condition' => 'new', 'category' => 'Home & Living'],
            ['title' => 'Men\'s Denim Jacket (M)', 'price' => 30.00, 'condition' => 'used', 'category' => 'Fashion'],
            ['title' => 'Running Shoes (Size 9)', 'price' => 38.00, 'condition' => 'used', 'category' => 'Fashion'],
            ['title' => 'Electric Scooter', 'price' => 210.00, 'condition' => 'used', 'category' => 'Vehicles'],
            ['title' => '4K Webcam', 'price' => 42.00, 'condition' => 'new', 'category' => 'Electronics'],
        ];

        foreach ($sampleProducts as $i => $data) {
            $seller = $sellers[$i % $sellers->count()];

            $product = Product::create([
                'user_id' => $seller->id,
                'title' => $data['title'],
                'description' => "Great condition, {$data['condition']} item. Message the seller for more photos or to arrange pickup.",
                'price' => $data['price'],
                'condition' => $data['condition'],
                'category' => $data['category'],
            ]);

            // Seeded with a placeholder marker as the "path" so the UI has
            // something to render without needing real uploaded files.
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'seed-placeholder',
            ]);
        }
    }
}
