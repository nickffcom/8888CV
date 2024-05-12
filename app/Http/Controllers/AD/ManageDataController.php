<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDataRequest;
use App\Http\Requests\UpdateDataRequest;
use App\Models\Data;
use App\Models\Note;
use App\Repository\DataRepository;
use Exception;
use Illuminate\Http\Request;

class ManageDataController extends Controller
{

    protected $dataRepo;
    public function __construct(DataRepository $dataRepo)
    {
        $this->dataRepo = $dataRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->dataRepo->getAllData();
            return response()->json(["data" => $data]);
        } catch (Exception $e) {
            Note::note("API Get List User", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION);
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param CreateDataRequest $request
     * @param Data $data
     * @return mixed;
     */
    public function store(CreateDataRequest $request, Data $data)
    {
        try {
            $data = $this->dataRepo->saveData($request);
            return response()->json($data);
        } catch (Exception $e) {
            Note::note("API Store Data", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION);
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * Display the specified resource.
     * @param Data $data
     */
    public function show(Data $data)
    {
        try {
            return response()->json(["data" => $data]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateDataRequest $request
     * @param Data $data
     * @return mixed;
     */
    public function update(UpdateDataRequest $request, Data $data)
    {
        try {
            $data->update(['attr' => json_encode($request->all())]);
            return response()->json([]);
        } catch (Exception $e) {
            Note::note("API Update User", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION);
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param Data $data
     */
    public function destroy(Request $request, Data $data)
    {
        try {
            $data->delete();
            return response()->json(["status" => true, "message" => "Xóa thành công"]);
        } catch (Exception $e) {
            Note::note("API Delete User", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION);
            return response()->json(["message" => SEVER_ERROR]);
        }
    }
}
