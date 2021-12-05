<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Order;
use App\Models\Sizes;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        \App\Models\Product::factory(10)->create();
        \App\Models\Category::factory(4)->create();
        \App\Models\Sizes::factory(6)->create();
        \App\Models\Order::factory(6)->create();

        $products = Product::all()->map(function ($product) {
            return $product->id;
        })->all();
        $orders = Order::all()->map(function ($order) {
            return $order->id;
        })->all();

        $sizes = Sizes::all()->map(function ($size) {
            return $size->id;
        })->all();

        for ($i = 0;$i < 100; $i++) {
            shuffle($orders);
            shuffle($products);
            shuffle($sizes);
            $or = $orders[0];
            $pr = $products[0];
            $sz = $sizes[0];
            \DB::table('order_product')->insert([
                'order_id' => $or,
                'size_id'  => $sz,
                'product_id' => $pr,
                'quantity' => 5,
                'subtotal' => 6,
            ]);
        }

        // $orders = Order::where('user_id', 8003)->get('id');
        // $orders->each(function($orderId) {
        //     \DB::table('deliveries')->insert([
        //         'order_id' => $orderId->id,
        //         'name_of_deliver_company' => 'LBC',
        //         'delivery_date' => \Carbon\Carbon::now()
        //     ]);
        // });
    }
}
