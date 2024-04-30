<?php

namespace App\Repository;

use App\Models\Note;
use App\Repository\BaseRepo;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class OrderRepository extends BaseRepo
{

    public function getModel()
    {
        return Order::class;
    }

    /**
     * Get Current user's purchase history of main shop
     * @param User $user
     * @param String $type
     * @return mixed|Collection
     */
    public function getOrderMainShop(User $user, String $type = null)
    {
        // return $user->orders()->where('type', $type)->get();
        $query  =  Order::whereHas('user', function (Builder $query) use ($user) {
            $query->where('user_id', $user);
        });
        if (isset($type)) {
            $query->where('type', $type);
        }
        return $query->paginate();
    }

    /**
     * Get Order Detail Main Shop
     * @param User $user
     * @return mixed|Collection
     */
    public function getOrderDetail(User $user, String $type)
    {
        return $user->orders()->where('type', $type)->get();
    }

    /**
     * Get History Order
     * @param User $user
     * @param String $type
     * @return mixed|Collection
     */
    public function getHistoryOrder($type, $userID)
    {
        try {
            DB::statement("SET SQL_MODE=''");
            $data = DB::table('order_service')
                ->join('data', 'data.id', '=', 'order_service.ref_id')
                ->join('service', function ($join) {
                    $join->on('service.id', '=', 'data.service_id');
                })
                ->select('order_service.*', 'service.name as service_name', 'service.type', DB::raw('COUNT(*) AS total_buy'), DB::raw('SUM(order_service.price_buy) AS total_price'))
                ->where('service.type', $type)
                ->whereRaw('order_service.user_id = ?', [$userID])
                ->groupBy('code')
                ->orderByRaw('order_service.id DESC')
                ->get();
            DB::statement('SET sql_mode = true;');
            return $data;
        } catch (Exception $e) {
            Note::note("SQL MODE NGUY HIỂM", "Lỗi:" . $e->getMessage(), LEVEL_BUG, Auth::user()->id);
            DB::statement('SET sql_mode = true;');
        }
    }

    /**
     * Get History Order API
     * @param String $type
     * @param String $userId
     * @return mixed|Collection
     */
    public function getHistoryOrderAPI($type, $userID)
    {
        return DB::table('order_service')
            ->join('data', 'data.id', '=', 'order_service.ref_id')
            ->select('order_service.*', 'data.attr->type as type', 'data.attr->service as service_name', DB::raw('COUNT(*) AS total_buy'), DB::raw('SUM(order_service.price_buy) AS total_price'))
            ->where('data.attr->type', '=', $type)
            ->whereRaw('order_service.user_id = ?', [$userID])
            ->groupByRaw('order_service.code')
            ->orderByRaw('order_service.id DESC')
            ->get();
    }
}
