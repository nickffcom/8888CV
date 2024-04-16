<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Data;
use App\Repository\DataRepo;
use App\Repository\DataRepository;
use App\Repository\ServiceRepo;
use App\Repository\ServiceRepository;
use Exception;
use Illuminate\Http\Request;

class BMController extends Controller
{

    protected $serviceRepo;
    protected $dataRepo;
    protected $type;
    public function __construct(ServiceRepository $serviceRepo, DataRepository $dataRepo)
    {
        $this->serviceRepo = $serviceRepo;
        $this->dataRepo = $dataRepo;
        $this->type = "BM";
    }

    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $listData = $this->dataRepo->getDataWithStatus(CON_HANG, $this->type);
        return view('Admin.service.manage', [
            'listData' => $listData,
            'type' => $this->type
        ]);
    }


    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $services = $this->serviceRepo->getServiceWeb();
        return view('Admin.service.add', [
            'services' => $services,
            'type' => $this->type
        ]);
    }



    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function store(Request $request)
    {
        $dataInput = $request->input('data', null);
        $typeId = $request->input('type_id', null);
        if (!isset($dataInput) || !is_numeric($typeId)) {
            return response()->json(["status" => false, "message" => "Phải có dữ liệu và chọn loại "]);
        }
        $checkService = $this->serviceRepo->checkSerViceExits($typeId);
        if (!isset($checkService)) {
            return response()->json(["status" => false, "message" => "Vui lòng chọn loại hợp lệ"]);
        }
        $data = trim($dataInput);
        $data = explode("\n", $data);
        $data = array_map('trim', $data);

        $success = 0;
        $error = 0;
        $dataErr = [];
        try {
            foreach ($data as $item) {
                list($uid, $password, $twofa, $email, $password_email, $note) = explode('|', $item);

                if (strlen($uid) < 0) {
                    $error++;
                    array_push($dataErr, $uid);
                }

                $resultAdd = Data::create([
                    'status' => CON_HANG,
                    'service_id' => $typeId,
                    'attr' => json_encode(DB_VIA($uid, $password, $twofa, $email, $password_email, $note))
                ]);
                if ($resultAdd) {
                    $success++;
                } else {
                    $error++;
                    array_push($dataErr, $uid);
                }
            }
            $count = [
                "error" => $error,
                "success" => $success
            ];
            return response()->json(["status" => true, "count" => $count, "message" => $dataErr]);
        } catch (Exception $e) {
            return response()->json(['status' => false, "message" => "Error" . $error . "--" . $success . "=>Message:" . $e]);
        }
    }


    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        //
    }


    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        //
    }


    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function destroy($id)
    {
        //
    }
}
