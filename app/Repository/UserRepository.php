<?php

namespace App\Repository;

use App\Repository\BaseRepo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepo
{

    public function getModel()
    {
        return User::class;
    }


    public function updateMoney($money)
    {
        $me = Auth::user();
        $check = $this->model->where('id', $me->id)->update([
            'money' => $money
        ]);
        return $check;
    }

    /**
     * @param Request $equest
     * @param  User $user
     * Update money by userName
     */
    public function updateMoneyByUserName(Request $request,User $user)
    {
        $money = $request->input('money');
        $action = $request->input('action', TRU_TIEN);
        $moneyCaculate = 0;
        if ($action == CONG_TIEN) {
            $moneyCaculate = $user->money + (int)$money;
        } else if ($action == TRU_TIEN) {
            $moneyCaculate = $user->money - (int)$money;
        }
        $check = $user->update([
            'money' => $moneyCaculate
        ]);
        return $check;
    }

     /**
     * @param Request $equest
     * @param  User $user
     * Update money by userName
     */
    public function getUserByUserName(Request $request)
    {
        return $this->model->where('username', $request->userName)->firstOrFail();
    }
}
