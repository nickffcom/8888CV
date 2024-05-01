<?php

namespace App\Http\Controllers\US;

use App\Http\Controllers\Controller;
use App\Http\Requests\ViewOrderDetailRequest;
use App\Http\Requests\ViewOrderRequest;
use App\Models\Note;
use App\Models\Order;
use App\Repository\DataRepository;
use App\Repository\OrderRepository;
use Carbon\Carbon;
use Exception;
use PDF;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    protected $orderRepo;
    protected $dataRepo;
    public function __construct(OrderRepository $orderRepo, DataRepository $dataRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->dataRepo = $dataRepo;
    }


    /**
     * Get Current user's purchase history
     * @param ViewOrderRequest $request
     * @return \Illuminate\Contracts\View\View
     */
    public function getOrders(ViewOrderRequest $request)
    {
        $user = Auth::user();
        try {
            $type = $request->query('type');
            $listOrder = $this->orderRepo->getOrderMainShop($user, $type);
            return response()->json(["data" => $listOrder, 'type' => $type]);
        } catch (Exception $e) {
            Note::note("API Get Order", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION, $user->id);
            return response()->json(["message" => SEVER_ERROR]);
        }
    }


    /**
     * Get Order Detail
     * @param ViewOrderDetailRequest $request
     * @param Order $order
     * @return Array|Collection
     */
    public function getOrderDetail(ViewOrderDetailRequest $request, Order $order)
    {
        $user = Auth::user();
        try {
            $orderDetail = $this->orderRepo->getOrderDetail($order);
            return response()->json(["data" => $orderDetail]);
        } catch (Exception $e) {
            Note::note("getViewOrderDetail Exception ", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION, $user->id);
            return response()->json(["message" => SEVER_ERROR]);
        }
    }


    /**
     * Index
     * @param 
     * @return \Illuminate\Contracts\View\View
     */
    public function downloadOrder(ViewOrderDetailRequest $request)
    { // file Txt
        $user = Auth::user();
        try {
            $code = $request->query("code");
            $type = $request->query("type");
            $typeFile = $request->query('typeFile', 'txt');
            $lists = $this->dataRepo->getAllDataOrder($code, $type);
            if (!isset($lists)) {
                return response()->json(["message" => "Sai Rồi"]);
            }
            $lists = $lists->map(function ($item, $key) {
                if (isset($item->attr)) {
                    $data = $item->attr->uid . '|' . $item->attr->pass . '|' . $item->attr->key2fa . '|' . $item->attr->email . '|' . $item->attr->passmail . '|' . $item->attr->note;
                    return $data;
                }
                return null;
            });
            $now = Carbon::now();
            if ($typeFile == 'txt') {
                $fileName = date_format($now, 'd-m-Y H:i') . "-Ads69.Net";
                $headers = [
                    'Content-Type' => 'text/plain',
                    'Cache-Control' => 'no-store, no-cache',
                    'Content-Disposition' => 'attachment; filename=' . $fileName . ".txt",
                ];

                return response()->make(implode("\n", $lists->toArray()), 200, $headers);
            } else if ($typeFile == 'zip') {
                $time = date('d-m-Y', strtotime($now));
                $fileNameZip = $time . "-Ads69.zip";
                $zip = new \ZipArchive();
                $zip->open($fileNameZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                $dataZip = "";
                foreach ($lists as $item) {
                    if (!$dataZip) {
                        $dataZip = (string)$item . "\n";
                    } else {
                        $dataZip = $dataZip . $item . "\n";
                    }
                }
                $zip->addFromString($fileNameZip . '.txt', $dataZip);
                $zip->close();
                return response()->download($fileNameZip)->deleteFileAfterSend();
            } else if ($typeFile == 'pdf') {
                $time = date('d-m-Y', strtotime($now));
                $fileNamePdf = $time . "-Ads69.zip";
                $lists = null;
                $code = $request->query("code");
                $type = $request->query("type", "Ads69.net");
                $lists = $this->dataRepo->getAllDataOrder($code, $type);
                $pdfContent = PDF::loadView('User.view_order', compact('lists', 'type'));
                return $pdfContent->download("$fileNamePdf.pdf");
                return $pdfContent->stream('myPDF.pdf');
            }
        } catch (Exception $e) {
            Note::note("downloadOrderByCode", "Lỗi:" . $e->getMessage(), LEVEL_EXCEPTION, $user->id);
        }
    }
}
