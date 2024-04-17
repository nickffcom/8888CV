<?php

namespace App\Http\Controllers\US;

use App\Repository\HistoryRepo;
use App\Repository\ServiceRepo;
use App\Http\Controllers\Controller;
use App\Models\Notify;
use App\Repository\HistoryRepository;
use App\Repository\ServiceRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    protected $serviceRepo;
    protected $historyRepo;
    public function __construct(ServiceRepository $serviceRepo, HistoryRepository $historyRepo)
    {
        $this->serviceRepo = $serviceRepo;
        $this->historyRepo = $historyRepo;
    }
    
    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function home()
    {
        try {
            $ListServiceAds69  = $this->serviceRepo->getServiceWeb();
            // dd($ListServiceAds69);
            $HistoryPayment = $this->historyRepo->getHistory(NAP_TIEN);   // lịch sử nạp tiền
            $HistoryTransaction = $this->historyRepo->getHistory(GIAO_DICH);  // lịch sử giao dịch

            $listServiceFromMuaFbNet = Cache::get('muafb.net');
            $listServiceFromMuaViaBm = Cache::get('muaviabm.vn');
            // dd($listServiceFromMuaViaBm);
            if (!isset($listServiceFromMuaFbNet)) {
                $priceIncrement = 1.07;
                $listServiceFromMuaFbNet = $this->getDataAndCache('muafb.net', $priceIncrement);
            }
            if (!isset($listServiceFromMuaViaBm)) {
                $priceIncrement = 1.07;
                $listServiceFromMuaViaBm = $this->getDataAndCache('muaviabm.vn', $priceIncrement);
            }

            if (isset($listServiceFromMuaFbNet)) {
                $listServiceFromMuaFbNet = (array)json_decode($listServiceFromMuaFbNet);
                $ListServiceAds69 = array_merge_recursive($ListServiceAds69, $listServiceFromMuaFbNet);
            }
            if (isset($listServiceFromMuaViaBm)) {
                $listServiceFromMuaViaBm = (array)json_decode($listServiceFromMuaViaBm);
                $ListServiceAds69 = array_merge_recursive($ListServiceAds69, $listServiceFromMuaViaBm);
            }

            $me = Auth::user();
            return view('User.home', [
                'services' => $ListServiceAds69,
                'payments' => $HistoryPayment,
                'transactions' => $HistoryTransaction,
                'me' => $me,
            ]);
        } catch (Exception $e) {
            return "Vui lòng ấn load lại trang ! Xin cảm ơnnn";
            addLogg('HomeController', Conver_ToString($e->getMessage()), LEVEL_EXCEPTION);
        }
    }

    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function napTien()
    {

        return view('User.pay');
    }
    
    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function lichSuNapTien()
    {
        $me = Auth::user();
        $historyPayment = $this->historyRepo->getHistoryByUser($me->id, NAP_TIEN);
        return view('User.history_pay')->with('historyPayment', $historyPayment);
    }
    
    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function Hotro()
    {
        return view('User.support');
    }
    
    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function getTaiKhoan()
    {
        $me = Auth::user();
        return view('User.profile', [
            'me' => $me
        ]);
    }
    
    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function LichSuThanhToan()
    {
        $list = $this->historyRepo->getHistory(GIAO_DICH, 20);
        $count = count($list);
        return view('User.history_buy', [
            'lists' => $list,
            'counts' => $count
        ]);
    }
    
    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function getDataAndCache($domain, $incrementPrice)
    {
        $tk = env("USER_API");
        $mk = env("PASS_API");
        $getDataFromApi = Http::get("https://$domain/api/ListResource.php?username=$tk&password=$mk");
        if ($getDataFromApi->ok()) {
            $data = json_decode($getDataFromApi->body());
            $dataFromAPI = [
                'VIA' => [],
                'BM' => [],
                'CLONE' => []
            ];
            foreach ($data->categories as $item) {
                foreach ($item->accounts as $valueTemp) {
                    if (
                        str_contains(mb_strtoupper($item->name), 'VIA')
                        || str_contains(mb_strtoupper($item->name), 'CLONE')
                        || str_contains(mb_strtoupper($item->name), 'BM')
                    ) {

                        if ((int)$valueTemp->amount > 3) {
                            foreach (SERVICE as $service) {  // via bm clone
                                if (str_contains(mb_strtoupper($valueTemp->name), $service)) {
                                    $valueTemp->type_Api = $domain === 'muafb.net' ? 2 : 3;
                                    $priceLast = intval($valueTemp->price) * $incrementPrice;
                                    $valueTemp->price = $priceLast > 0 ? $priceLast : 333333;
                                    array_push($dataFromAPI[$service], $valueTemp);
                                }
                            }
                        }
                    }
                }
            }
            $expiresAt = Carbon::now()->addMinutes(15);
            Cache::add($domain, json_encode($dataFromAPI), $expiresAt);
        }
        return empty($dataFromAPI['VIA']) ? null : json_encode($dataFromAPI);
    }
}
