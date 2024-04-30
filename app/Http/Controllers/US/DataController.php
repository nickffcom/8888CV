<?php

namespace App\Http\Controllers\US;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Repository\HistoryRepository;
use App\Repository\ServiceRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DataController extends Controller
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
    public function getData()
    {
        try {

            $listServiceAds69  = $this->serviceRepo->getServiceWeb();
            $historyPayment = $this->historyRepo->getHistory(NAP_TIEN);   // lịch sử nạp tiền
            $historyTransaction = $this->historyRepo->getHistory(GIAO_DICH);  // lịch sử giao dịch

            // $listServiceFromWebsiteMuaFbNet = Cache::get('muafb.net');
            // $listServiceFromWebsiteMuaViaBm = Cache::get('muaviabm.vn');
            // if (!isset($listServiceFromWebsiteMuaFbNet)) {
            //     $priceIncrement = 1.07;
            //     $listServiceFromWebsiteMuaFbNet = $this->getDataAndCache('muafb.net', $priceIncrement);
            // }
            // if (!isset($listServiceFromWebsiteMuaViaBm)) {
            //     $priceIncrement = 1.07;
            //     $listServiceFromWebsiteMuaViaBm = $this->getDataAndCache('muaviabm.vn', $priceIncrement);
            // }

            // if (isset($listServiceFromWebsiteMuaFbNet)) {
            //     $listServiceFromWebsiteMuaFbNet = (array)json_decode($listServiceFromWebsiteMuaFbNet);
            //     $listServiceAds69 = array_merge_recursive($listServiceAds69, $listServiceFromWebsiteMuaFbNet);
            // }
            // if (isset($listServiceFromWebsiteMuaViaBm)) {
            //     $listServiceFromWebsiteMuaViaBm = (array)json_decode($listServiceFromWebsiteMuaViaBm);
            //     $listServiceAds69 = array_merge_recursive($listServiceAds69, $listServiceFromWebsiteMuaViaBm);
            // }
            return response()->json(["transactions" => $historyTransaction, "datas" => $listServiceAds69, 'payments' => $historyPayment]);
        } catch (Exception $e) {
            Note::note('HomeController', Conver_ToString($e->getMessage()), LEVEL_EXCEPTION);
            return response()->json(["message" => "Vui lòng ấn load lại trang ! Xin cảm ơnnn"]);
        }
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
