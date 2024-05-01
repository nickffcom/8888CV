<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Repository\NoteRepository;
use Exception;
use Illuminate\Http\Request;

class ManageLogController extends Controller
{

    protected $logRepo;
    public function __construct(NoteRepository $logRepo)
    {
        $this->logRepo = $logRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->logRepo->getAllandPaginate();
            return response()->json(["data" => $data]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            return response()->json(["data" => []]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return response()->json(["data" => []]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            return response()->json(["data" => []]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * @param Note $note 
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        try {
            $note->delete();
            return response()->json(["status" => true, "message" => "Xóa thành công"]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }
}
