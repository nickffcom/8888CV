<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Logg;
use App\Repository\LogRepo;
use App\Repository\LogRepository;

class LogController extends Controller
{
    protected $logRepo;
    public function __construct(LogRepository $logRepo)
    {
        $this->logRepo = $logRepo;
    }


    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function viewIndex()
    {
        $log = $this->logRepo->getAll();
        return view('Admin.Log', [
            'type' => "OK",
            'logs' => $log
        ]);
    }


    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function delete(Log $log)
    {
        $result = $log->delete();
        return response()->json(["status" => true, "message" => "Xóa thành công"]);
    }
}
