<?php

namespace App\Http\Controllers\US;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Traits\ThrottlesAttempts;
use App\Models\Log;
use App\Models\Note;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    use ThrottlesAttempts;
    protected $userRepo;
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Login User by credentials ! Prevent login if attempted incorrectly many times
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');
            $remember = true;
            if ($this->hasTooManyAttempts($request)) {
                return $this->sendLockoutResponse($request);
            }
            if (Auth::attempt($credentials, $remember)) {
                $this->clearAttempts($request);
                $user = Auth::user();
                $tokenResult = $user->createToken("auth8888", ["view:any"], Carbon::now()->addMonths(3))->plainTextToken;
                return response()->json([
                    'user' => $user,
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                ], 200);
            }
            $this->incrementAttempts($request);
            return response()->json(["message" => "Account/Password is incorrect"], 403);
        } catch (Exception $e) {
            Note::note("Login User Exception : ", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION, Auth::user()->id);
            return response()->json(["message" => SEVER_ERROR], 403);
        }
    }

    /**
     * Index
     * @param  Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->user()->tokens()->delete();
            return response()->json(["status" => false, "message" => "Success"]);
        } catch (Exception $e) {
            Log::note("Logout User Exception : ", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION, Auth::user()->id);
            return response()->json(["status" => false, "message" => SEVER_ERROR]);
        }
    }

    /**
     * Update Information Of User Detail
     * @param UpdateUserRequest $request
     * @return mixed
     */
    public function updateUser(UpdateUserRequest $request)
    {
        $me = Auth::user();
        try {
            $pass = $request->password;
            $newPass = $request->new_password;
            if (Hash::check($pass, $me->password) !== false) {

                $this->userRepo->update($me->id, [
                    'password' => Hash::make($newPass),
                ]);
                return response()->json(["status" => true, "message" => "Update Password Successfuly :$newPass"]);
            } else {
                return response()->json(["status" => false, "message" => "Update failed =>> Old password is invalid"]);
            }
        } catch (Exception $e) {
            Log::note("Update Info User", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION, $me->id);
            return response()->json(["status" => false, "message" => SEVER_ERROR]);
        }
    }
}
