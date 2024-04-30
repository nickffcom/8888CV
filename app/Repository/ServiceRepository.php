<?php

namespace App\Repository;

use App\Repository\BaseRepo;
use App\Http\Controllers\Concerns\Paginatable;
use App\Models\Data;
use App\Models\History;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceRepository extends BaseRepo
{

    public function getModel()
    {
        return Service::class;
    }

    /**
     * Get List Services and Count Data Each Service 
     * @param String $type
     * @return mixed
     */
    public function getServiceWeb($type = null)
    {
        $query = $this->model->where("status", STATUS_OK);
        if (isset($type)) {
            $query->where("type", $type);
        }
        $listServices = $query->orderBy('id', 'ASC')->withCount('datas')->get();
        $listServices->map->setAttribute('typeApi', MAIN_SHOP);
        return $listServices;
    }

    public function checkSerViceExits($id)
    {
        $service = $this->model->where('id', $id)->first();
        return isset($service) ? $service : null;
    }
}
