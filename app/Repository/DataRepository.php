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

    /**
     * Update Status Data by Id
     * @param String $id
     * @param String $status
     */
    public function updateStatusData($id, $status = HET_HANG)
    {
        return Data::where('id', $id)->update([
            'status' => $status
        ]);
    }

    /**
     * Get random data service have status live
     * @param String $id
     * @param String $status
     */
    public function getDataSeviceLive($id, $quantity)
    {
        return Data::where('service_id', $id)
            ->where('status', CON_HANG)
            ->inRandomOrder()->take($quantity)->get();
    }

    /**
     * Get data with status and type
     * @param String $id
     * @param String $status
     */
    public function getDataWithStatus($status, $type)
    {
        $query = $this->model->whereHas('service', function ($query) use ($type) {
            $query->where('type', $type);
        });
        if (!$status == 'all') {
            $query->where('status', $status);
        }
        return $query->get();
    }

    /**
     * Get data with status and type
     * @param String $id
     * @param String $status
     */
    public function getAllData($status, $type)
    {
        $query = $this->model->whereHas('service', function ($query) use ($type) {
            $query->where('type', $type);
        });
        if (!$status == 'all') {
            $query->where('status', $status);
        }
        return $query->paginate();
    }
}
