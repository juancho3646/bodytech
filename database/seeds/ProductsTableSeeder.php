<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($kg = 15; $kg <= 60; $kg += 15) {
            $offerPrices = $kg % 10 == 0 ? ($kg * 1000) - 10000 : 0;
            $product = new \App\Models\Product([
                'name' => 'mancuerna '.$kg.'kg',
                'description' => 'mancuernas de '.$kg.'kg',
                'price' => $kg * 1000,
                'offer_price' => $offerPrices,
                'image' => 'https://http2.mlstatic.com/D_NQ_NP_2X_698461-MCO48775547830_012022-F.webp'
            ]);
            $product->save();
        }
    }
}
