<?php

namespace App\Http\Controllers\US;

use App\Http\Controllers\Controller;
use App\Repository\HistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{

    protected $historyRepo;
    protected $dataRepo;
    public function __construct(HistoryRepository $historyRepo)
    {
        $this->historyRepo = $historyRepo;
    }

    /**
     * Get current user's deposit history
     * @param Request $request
     * @return mixed
     */
    public function getDepositHistory(Request $request)
    {
        return $this->historyRepo->getHistoryByUser(Auth::user(), NAP_TIEN);
    }
}
