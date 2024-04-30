<?php

namespace Database\Seeders;

use App\Models\Data;
use App\Models\Service;
use App\Models\User;
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
        User::updateOrCreate([
            "username" => "hainao9tk@gmail.com",
            "email" => "hainao9tk@gmail.com",
            "money" => 25000,
            "password" => Hash::make(env("ADMIN_PASS", "123456789"))
        ]);
        Service::updateOrCreate([
            "name" => "Via XMDT 2029-2022",
            "description" => "Mua di ae oi re lam luon",
            "price" => 25000,
            "type" => "VIA"
        ]);
        Service::updateOrCreate([
            "name" => "Via Co Full Email Password",
            "description" => "Mua di ae oi re lam luon",
            "price" => 15000,
            "type" => "VIA"
        ]);
        Service::updateOrCreate([
            "name" => "Philipin Co Xmdt Full VIA",
            "description" => "Mua di ae oi re lam luon",
            "price" => 45000,
            "type" => "VIA"
        ]);

        Service::updateOrCreate([
            "name" => "BM5 XMDN Limit 5m8",
            "description" => "Mua di ae oi re lam luon",
            "price" => 1125000,
            "type" => "BM"
        ]);

        Service::updateOrCreate([
            "name" => "BM1 Bao Pay Len Bm3",
            "description" => "Mua di ae oi re lam luon",
            "price" => 105000,
            "type" => "BM"
        ]);
        Service::updateOrCreate([
            "name" => "BM0 Die NLM Bao Pay Len Bm5 Kick Tut",
            "description" => "Mua di ae oi re lam luon",
            "price" => 800000,
            "type" => "BM"
        ]);

        Service::updateOrCreate([
            "name" => "Clone US Reg Lau",
            "description" => "Mua di ae oi re lam luon",
            "price" => 7500,
            "type" => "CLONE"
        ]);

        Service::updateOrCreate([
            "name" => "Clone VN NoVery",
            "description" => "Mua di ae oi re lam luon",
            "price" => 500,
            "type" => "CLONE"
        ]);
        Service::updateOrCreate([
            "name" => "Clone VN Qua 282 >50 bb",
            "description" => "Mua di ae oi re lam luon",
            "price" => 2500,
            "type" => "CLONE"
        ]);

        // ****************** 
        DB::table('datas')->updateOrInsert([
            "status" => CON_HANG,
            "service_id" => "1",
            "attr" => json_encode(DB_VIA("1000032532532", "nonamekkkkk", "2KFANALMGJAGSLMFSFKKF", "hainaot9k@outlook.com", "gasfaga", "Ok con vk")),
        ]);
        DB::table('datas')->updateOrInsert([
            "status" => CON_HANG,
            "service_id" => "1",
            "attr" => json_encode(DB_VIA("103252364353", "provjplam", "9252KKFANALMGJKFSKGSKDASFKKF", "caogiadongnai@outlook.com", "kksafksaf", "Ok con vk")),
        ]);
        DB::table('datas')->updateOrInsert([
            "status" => CON_HANG,
            "service_id" => "1",
            "attr" => json_encode(DB_VIA("100043625352335", "haizzhuocnua", "52532KHASFSKKKK@KFSFADS", "hahahahhabun@outlook.com", "kakashiii", "Ok con vk")),
        ]);

        DB::table('datas')->updateOrInsert([
            "status" => CON_HANG,
            "service_id" => "1",
            "attr" => json_encode(DB_VIA("100087541020292", "haizzhuocnua", "52532KHASFSKKKK@KFSFADS", "hahahahhabun@outlook.com", "kakashiii", "Ok con vk")),
        ]);
    }
}
