<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateMoneyRequest;
use App\Jobs\SendThongBaoCongTienAdminQueue;
use App\Models\Note;
use App\Models\User;
use App\Repository\HistoryRepository;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageUserController extends Controller
{
    protected $userRepo;
    protected $historyRepo;
    public function __construct(UserRepository $userRepo, HistoryRepository $historyRepo)
    {
        $this->userRepo = $userRepo;
        $this->historyRepo = $historyRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->userRepo->getAllandPaginate();
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
            $data = $request->all();
            $data['money'] = 0;
            $this->userRepo->create($data);
            return response()->json(["message" => SUCCESS]);
        } catch (Exception $e) {
            Note::note("API Store User", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION);
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
     * @param Request $equest
     * @param  User $user
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        DB::beginTransaction();
        try {
            return response()->json(["message" => SUCCESS]);
            DB::commit();
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * @param UpdateMoneyRequest $equest
     * @param  User $user
     * Update money by username and create history then send mail to admin
     */
    public function updateMoney(UpdateMoneyRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepo->getUserByUserName($request);
            $this->userRepo->updateMoneyByUserName($request, $user);
            $this->historyRepo->createHistoryRecharge($request, $user);
            $data['username'] = $user->username;
            $data['tongtien'] = $user;
            dispatch(new SendThongBaoCongTienAdminQueue($data));
            $this->userRepo->update($data);
            DB::commit();
            return response()->json(["message" => SUCCESS]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }

    /**
     * @param User $user
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(["status" => true, "message" => "Xóa thành công"]);
        } catch (Exception $e) {
            return response()->json(["message" => SEVER_ERROR]);
        }
    }
}
