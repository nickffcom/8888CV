<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->updateOrInsert([
            "username" => "hainao9tk",
            "money" => 25000,
            "password" => Hash::make("Nqdiencuboy99**")
        ]);
        DB::table('services')->updateOrInsert([
            "name" => "Via XMDT 2029-2022",
            "description" => "Mua di ae oi re lam luon",
            "price" => 25000,
            "type" => "VIA"
        ]);
        DB::table('services')->updateOrInsert([
            "name" => "Via Co Full Email Password",
            "description" => "Mua di ae oi re lam luon",
            "price" => 15000,
            "type" => "VIA"
        ]);
        DB::table('services')->updateOrInsert([
            "name" => "Philipin Co Xmdt Full VIA",
            "description" => "Mua di ae oi re lam luon",
            "price" => 45000,
            "type" => "VIA"
        ]);

        DB::table('services')->updateOrInsert([
            "name" => "BM5 XMDN Limit 5m8",
            "description" => "Mua di ae oi re lam luon",
            "price" => 1125000,
            "type" => "BM"
        ]);

        DB::table('services')->updateOrInsert([
            "name" => "BM1 Bao Pay Len Bm3",
            "description" => "Mua di ae oi re lam luon",
            "price" => 105000,
            "type" => "BM"
        ]);
        DB::table('services')->updateOrInsert([
            "name" => "BM0 Die NLM Bao Pay Len Bm5 Kick Tut",
            "description" => "Mua di ae oi re lam luon",
            "price" => 800000,
            "type" => "BM"
        ]);

        DB::table('services')->updateOrInsert([
            "name" => "Clone US Reg Lau",
            "description" => "Mua di ae oi re lam luon",
            "price" => 7500,
            "type" => "CLONE"
        ]);

        DB::table('services')->updateOrInsert([
            "name" => "Clone VN NoVery",
            "description" => "Mua di ae oi re lam luon",
            "price" => 500,
            "type" => "CLONE"
        ]);
        DB::table('services')->updateOrInsert([
            "name" => "Clone VN Qua 282 >50 bb",
            "description" => "Mua di ae oi re lam luon",
            "price" => 2500,
            "type" => "CLONE"
        ]);

        // ****************** 
        DB::table('datas')->updateOrInsert([
            "status" => CON_HANG,
            "services_id" => "1",
            "attr" => json_encode(DB_VIA("1000032532532", "nonamekkkkk", "2KFANALMGJAGSLMFSFKKF", "hainaot9k@outlook.com", "gasfaga", "Ok con vk")),
        ]);
        DB::table('datas')->updateOrInsert([
            "status" => CON_HANG,
            "services_id" => "1",
            "attr" => json_encode(DB_VIA("103252364353", "provjplam", "9252KKFANALMGJKFSKGSKDASFKKF", "caogiadongnai@outlook.com", "kksafksaf", "Ok con vk")),
        ]);
        DB::table('datas')->updateOrInsert([
            "status" => CON_HANG,
            "services_id" => "1",
            "attr" => json_encode(DB_VIA("100043625352335", "haizzhuocnua", "52532KHASFSKKKK@KFSFADS", "hahahahhabun@outlook.com", "kakashiii", "Ok con vk")),
        ]);

        DB::table('data')->updateOrInsert([
            'id' => 44,
            "status" => CON_HANG,
            "services_id" => "1",
            "attr" => json_encode(DB_VIA("100087541020292", "haizzhuocnua", "52532KHASFSKKKK@KFSFADS", "hahahahhabun@outlook.com", "kakashiii", "Ok con vk")),
        ]);
    }
}
