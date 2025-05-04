<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exhibition;

class ExhibitionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exhibitions = [
            [
                'name' => 'メンズ腕時計',
                'price' => 15000,
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition' => '良好',
                'user_id' => 1,
                'category' => 'メンズ,アクセサリー'
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'detail' => '高速で信頼性の高いハードディスク',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
                'user_id' => 2,
                'category' => '家電'
            ],
            [
                'name' => '玉ねぎ３束',
                'price' => 300,
                'detail' => '新鮮な玉ねぎ３束のセット',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition' => 'やや傷や汚れあり',
                'user_id' => 3,
                'category' => 'キッチン'
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'detail' => 'クラシックなデザインの革靴',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition' => '状態が悪い',
                'user_id' => 4,
                'category' => 'メンズ,ファッション'
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'detail' => '高機能なノートパソコン',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition' => '良好',
                'user_id' => 5,
                'category' => '家電'
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'detail' => '高音質のレコーディング用マイク',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
                'user_id' => 6,
                'category' => '家電'
            ],
            [
                'name' => 'ショルダーバック',
                'price' => 3500,
                'detail' => 'おしゃれなショルダーバック',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition' => 'やや傷や汚れあり',
                'user_id' => 7,
                'category' => 'レディース,ファッション'
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'detail' => '使いやすいタンブラー',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition' => '状態が悪い',
                'user_id' => 8,
                'category' => 'キッチン'
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'detail' => '手動のコーヒーミル',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition' => '良好',
                'user_id' => 9,
                'category' => 'キッチン'
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'detail' => '便利なメイクアップセット',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition' => '目立った傷や汚れなし',
                'user_id' => 10,
                'category' => 'レディース,コスメ'
            ]
        ];

        foreach ($exhibitions as $exhibition) {
            Exhibition::create($exhibition);
        }
    }
}
