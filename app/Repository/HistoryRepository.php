<?php

namespace App\Repository;

use App\Repository\BaseRepo;
use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;

class HistoryRepository extends BaseRepo
{
    public function getModel()
    {
        return History::class;
    }

    /**
     * Get History
     * @param String $type
     * @param Integer $amount
     * @return Array|Collection
     */
    public function getHistory($type, $amount = 15)
    {
        return $this->model->where('type', $type)->orderBy("created_at", "desc")->take($amount)->get();
    }

    /**
     * Get History By User and type
     * @param User $user
     * @param String $type
     * @return Array|Collection
     */
    public function getHistoryByUser(User $user, String $type)
    {
        return $user->histories()->orderBy("created_at", "desc")->paginate();
    }

    /**
     * Get a list of revenue statistics
     * @return Array|Collection
     */
    public function getRevenueStatistics()
    {
        return  $this->model->where('type', NAP_TIEN)
            ->selectRaw('SUM(total_money) as TONG_TIEN')
            ->first();
    }

    /**
     * Get a list of revenue statistics by time
     * @param String $time
     * @return Array|Collection
     */
    public function getThongKeDoanhThuBy($time = null)
    {
        $query = $this->model->where('type', NAP_TIEN);
        if ($time) {
            $query->whereRaw("DATE_FORMAT(created_at, '%Y%m%d') = {$time}");
        }
        return $query->sum('total_money');
    }

    /**
     * Get all history for screen admin
     * @return Array|Collection
     */
    public function getAllHistoryToManage()
    {
        $value = $this->model->join('user', 'user.id', 'history.user_id')
            ->select('history.*', 'user.username')
            ->orderBy('history.created_at', 'asc')
            ->get();
        return $value;
    }

    /**
     * Get all history for screen admin
     * @param Request $request
     * @param User $user
     * @return Array|Collection
     */
    public function createHistoryRecharge(Request $request, User $user)
    {
        return $this->model->create([
            'action_id' => $request->transactionID,
            'action_content' => 'Admin + tiền',
            'content' => 'Nạp tiền vào tài khoản',
            'total_money' => $request->money,
            'type' => $request->action,
            'user_id' => $user->id
        ]);
    }
}
