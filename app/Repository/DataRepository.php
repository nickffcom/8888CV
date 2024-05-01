<?php

namespace App\Repository;

use App\Repository\BaseRepo;
use App\Models\Data;
use Illuminate\Support\Facades\Auth;

class DataRepository extends BaseRepo
{

  public function getModel()
  {
    return Data::class;
  }


  public function updateStatusData($id, $status = HET_HANG)
  {
    return Data::where('id', $id)->update([
      'status' => HET_HANG
    ]);
  }
  public function getdataSeviceLive($id, $quantity)
  {
    return Data::where('service_id', $id)
      ->where('status', CON_HANG)
      ->inRandomOrder()->take($quantity)->get();
  }

  public function getDataWithStatus($status, $type)
  {

    $rs = $this->model->whereHas('service', function ($query) use ($type) {
      $query->where('type', $type);
    });
    if (!$status == 'all') {
      $rs = $rs->where('status', $status);
    }
    return $rs->get();
  }

}
