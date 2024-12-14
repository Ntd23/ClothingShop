<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MomoController extends Controller
{
  function execPostRequest($url, $data)
  {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
      $ch,
      CURLOPT_HTTPHEADER,
      array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
      )
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    return $result;
  }

  public function store(Request $request)
  {

    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    $partnerCode = 'MOMOBKUN20180529';
    $accessKey = 'klm05TvNBzhg7h7j';
    $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    $orderInfo = "Thanh toán qua MoMo";
    $amount = $request->amount;
    $orderId = time() . "";
    $redirectUrl = 'http://127.0.0.1:8080';
    $ipnUrl = 'http://127.0.0.1:8080';
    $extraData = "";


    $requestId = time() . "";
    $requestType = "payWithATM";
    //before sign HMAC SHA256 signature
    $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
    $signature = hash_hmac("sha256", $rawHash, $secretKey);

    $data = array(
      'partnerCode' => $partnerCode,
      'partnerName' => "Test",
      "storeId" => "MomoTestStore",
      'requestId' => $requestId,
      'amount' => $amount,
      'orderId' => $orderId,
      'orderInfo' => $orderInfo,
      'redirectUrl' => $redirectUrl,
      'ipnUrl' => $ipnUrl,
      'lang' => 'vi',
      'extraData' => $extraData,
      'requestType' => $requestType,
      'signature' => $signature
    );
    $result = $this->execPostRequest($endpoint, json_encode($data));
    $jsonResult = json_decode($result, true);  // decode json
    if (isset($jsonResult['payUrl'])) {
      return response()->json(['redirect' => $jsonResult['payUrl']]);
    } else {
      return response()->json(['error' => 'Không thể tạo giao dịch MoMo'], 500);
    }
  }
  public function returnUrl(Request $request)
  {
    $response = $request->all();

    // Kiểm tra trạng thái giao dịch
    if (isset($response['resultCode']) && $response['resultCode'] == 0) {
      // Thanh toán thành công
      return redirect()->route('momo.success');
    } else {
      // Thanh toán thất bại hoặc bị hủy
      return redirect()->route('momo.cancel');
    }
  }
}
