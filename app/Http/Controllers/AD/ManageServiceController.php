<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Traits\ThrottlesAttempts;
use App\Models\Service;
use App\Repository\ServiceRepository;
use Exception;

class ManageServiceController extends Controller
{
    use ThrottlesAttempts;
    protected $serviceRepo;
    public function __construct(ServiceRepository $serviceRepo)
    {
        $this->serviceRepo = $serviceRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->serviceRepo->getAllandPaginate();
            return response()->json(["data" => $data]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * @param CreateServiceRequest $request
     * Store a newly created resource in storage.
     */
    public function store(CreateServiceRequest $request)
    {
        try {
            $data = $request->all();
            $this->serviceRepo->create($data);
            return response()->json(["message" => SUCCESS]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * @param CreateServiceRequest $request
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request)
    {
        try {
            $data = $request->all();
            $this->serviceRepo->update($data);
            return response()->json(["message" => SUCCESS]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * @param Service $service
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return response()->json(["status" => true, "message" => "Xóa thành công"]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }
}
