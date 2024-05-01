<?php

namespace App\Repository;

use App\Repository\BaseRepo;
use App\Models\Data;
use Illuminate\Http\Request;
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

    /**
     * Save Data
     * @param Request $request
     */
    public function saveData(Request $request)
    {
        $dataInput = $request->input('data');
        $typeId = $request->input('type_id');
        $data = trim($dataInput);
        $data = explode("\n", $data);
        $data = array_map('trim', $data);

        $success = 0;
        $error = 0;
        $dataErr = [];

        foreach ($data as $item) {
            list($attribute1, $attribute2, $attribute3, $attribute4, $attribute5, $note) = explode('|', $item);

            if (strlen($attribute1) < 0) {
                $error++;
                array_push($dataErr, $attribute1);
            }
            $resultAdd = Data::create([
                'status' => CON_HANG,
                'service_id' => $typeId,
                'attr' => json_encode(FORMAT_JSON($attribute1, $attribute2, $attribute3, $attribute4, $attribute5, $note))
            ]);
            if ($resultAdd) {
                $success++;
            } else {
                $error++;
                array_push($dataErr, $attribute1);
            }
        }
        $count = [
            "error" => $error,
            "success" => $success
        ];
        return  ["count" => $count, "message" => $dataErr];
    }
}
