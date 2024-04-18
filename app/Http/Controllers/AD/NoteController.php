<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Repository\NoteRepository;

class NotController extends Controller
{
    protected $logRepo;
    public function __construct(NoteRepository $logRepo)
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
    public function delete(Note $note)
    {
        $result = $note->delete();
        return response()->json(["status" => true, "message" => "Xóa thành công"]);
    }
}
