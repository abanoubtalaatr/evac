<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;
use Illuminate\Http\Request;
use App\Models\Ad;

class MyFatoorahController extends Controller
{
    public $budget = 0;

    public function pay(Request $request, Ad $ad)
    {
        $this->budget = $ad->budget;

        if (session()->has('user_discount')) {
            $response = $this->budgetAfterDiscount($ad);
            $discountRow = Discount::where('discount_code', session()->get('user_discount'))->first();
            $discountCode = $discountRow->discount_code;

            // if remaining > 0 go to pay online if not update only and redirect to back
            if ($response > 0) {
                $this->budget = $response;
                $discount_info_ar = " ميزانية الأعلان قبل الخصم كانت $ad->budget بعد الخصم اصبحت  $this->budget";
                $discount_info_en = "Budget for ads before discount code was $ad->budget became $this->budget";

                // store it in session to used when payment return successfully
                session()->put('discount_info_ar', $discount_info_ar);
                session()->put('discount_info_en', $discount_info_en);
            } else {
                // here will update ad that is paid and add notes is paid by discount code
                $discount_info_ar = " ميزانية الأعلان قبل الخصم كانت $ad->budget بعد الخصم اصبحت 0 ";
                $discount_info_en = "Budget for ads before discount code was $ad->budget became  0";

                $ad->update([
                    'status' => 'reviewing',
                    'notes' => "$discountCode تم الدفع بواسطة كودالخصم ",
                    'remaining_budget' => $ad->budget,
                    'discount_info_ar' => $discount_info_ar,
                    'discount_info_en' => $discount_info_en,
                ]);

                $discountRow->update([
                    'number_of_times' => $discountRow->number_of_times -= 1,
                    'number_of_times_is_used' => $discountRow->number_of_times_is_used += 1
                ]);
                return back();
            }
        }
        //Test
        // dd($id);
        //   $apiURL = 'https://apitest.myfatoorah.com/v2/';


        //Live
        //$apiKey = 'JmUyaGp_mJuDvZ5gQzbPraEjjMZUptn7NcQZMQYsUifoF7AH1oLrll1s860zO_h5HulTJxh91hiAE5HMqOZzquAKNjAwK4MAOZPOeVfwwk77G_bKLPgx_BTliMvwdHZaL-H1cE1n74IV4ZmeUn4dcY-kaw-WaOXiXPXkNpJe0cF1f9Kmz5s9SazwonfUgqnCJmGzjhQS1o8RgcACAWy_P62PIvdNPojk7evi4vK_StzfFm0b4HGeoYHWMxMnvApsLQmX6sQ1xKz_WrB1jg9CCK9fegI-0-UvZDUTICQjpsnOigPflJkp_pGlicJnrWBXFNruxBiFBNCe4ey8v7NxUrTVqUIqA2stRJ8Lp3Dq92HknCbZMPK4EVkP3JRBGqBHEVkEh9ivp58bZbBxo3c9ZQylVHkTNoKiKH4xnbtXiYlMOC-BJlM1GKQ7rauX4Jq20RHiMO8c4ZFJyh8jjlJUwQZjl0Mp_FOJtB9ItytHxtksQJbZ_s8LxTy910Z5cewVfuHqAu4gJa0cPfZtvEIKes0mvuUlnVxQrNuGOTtENDJoao2ziQilrSXs7p2KZoF5Yh7y7RFCwyPQ-yBdluzwypE-TMA_qODUsjtqNurSSZo2QsHJ9JVfa3xPkgO_fo1iPEdfFxjG485Fdti6K2aPYoJb9OkEtcwMuyg3YSHXXP73yRtyDzym7XLB51KRw9BpH8lXnw'; //Test token value to be placed here: https://myfatoorah.readme.io/docs/test-token
        //$apiURL = 'https://api-sa.myfatoorah.com/v2/SendPayment/';
        //Test
        $apiKey = 'A4JODUv5e_MuMyZsq7UkYbNDw9M5nBsdusuuJrtlswyFfWNgPtcyxgboPbCvFo1QK-dt2Cb1EkNhRMMkgcKZCyGfW6yJs2-l-j_yqBy21qaZJ--3bJiiGZhHZBGZkuy96-HgKKFmOhyQ4vSQt0QXO4oFq3SkF0Pac09KlqA5ht847_EoYu4zKR9A61zu8mR48jlkt0RxmKgpx6cgQFzeBITtMvSnzedEX92P5T9DV4JKTWsTSrGgfE9IQJbC4Ab25fzjz7BEC5MxLSCQbR01Fm553GAfcu8gBB_9KMbjFwh9zbMRF-fNWfylbH9dWLhvPBQixsLNdejl-tzgUw-hOH7XA_X5a6dOWxEcn4ya4aUzHRq9LTfO1431UsK-LXD8RwRhndmOftHR__BQeRGof1T97pTaQk0Nrp1r76PdpNsk-ovbEuzNWbtLlwLGXV9IyHSf80DOy5d3s3mcUwdhb_KKSWob5OgmgLz8Pjq7oeB83np8kXfjKvg43c9MfdgV9V87SJy_tDOvsao1AkzkJl_6jUCUahXxA_-a9fIUJ1RAbclWebt_gekE_qqmUksyLIDeACwaFx-95MFjY6LG2-K4y31bQwmx0oBK4z2RirhSNEfZTx59aq6QJuG7Nv73EBnbMINmIQ0sqp1T0lB_rTbj-3pbKraYI1q8WofKEVatmMAugvLEpDpnHLMuzxdKziBagdy8RbmuGOOxGQIPLDmvKM08RsvrARLpCMT04HO8oMqv'; //Test token value to be placed here: https://myfatoorah.readme.io/docs/test-token
        $apiURL = 'https://apitest.myfatoorah.com/v2/SendPayment/';
        //$apiKey = ''; //Live token value to be placed here: https://myfatoorah.readme.io/docs/live-token


        /* ------------------------ Call SendPayment Endpoint ----------------------- */
        //Fill customer address array
        $customerAddress = array(
            'Block' => 'Riyadh #', //optional
            //   'Street'              => 'Str', //optional
            //   'HouseBuildingNo'     => 'Bldng #', //optional
            //   'Address'             => 'Addr', //optional
            //   'AddressInstructions' => 'More Address Instructions', //optional
        );
        //   dd($ad);
        //Fill invoice item array
        $invoiceItems[] = [
            'ItemName' => $ad->title, //ISBAN, or SKU
            'Quantity' => '1', //Item's quantity
            'UnitPrice' => $this->budget, //Price per item
        ];

        //Fill POST fields array
        $postFields = [
            //Fill required data
            'NotificationOption' => 'Lnk', //'SMS', 'EML', or 'ALL'
            'paymentMethodId' => 2,
            'InvoiceValue' => $this->budget,
            'CustomerName' => $ad->advertiser->username,
            //Fill optional data
            "CurrencyIso" => "SAR",
            'DisplayCurrencyIso' => 'SAR',
            'MobileCountryCode' => '+966',
            'CustomerMobile' => $ad->advertiser->mobile,
            'CustomerEmail' => $ad->advertiser->email,
//            'ErrorUrl' => 'https://adsoldiers.com/public/' . app()->getLocale() . '/user/ads/fail/' . $ad->id . '/' . '?status=fail', //or 'https://example.com/error.php'
            'CallBackUrl' => 'https://adsoldiers.com/public/' . app()->getLocale() . '/user/ads/sucess/' . $ad->id . '/' . '?status=success',
            'Language' => 'ar', //or 'ar'
            'CustomerReference' => date('YmdHis'),
            'CustomerCivilId' => 1,
            'UserDefinedField' => 1,
            'ExpiryDate' => '', //The Invoice expires after 3 days by default. Use 'Y-m-d\TH:i:s' format in the 'Asia/Kuwait' time zone.
            'SourceInfo' => 'Pure PHP', //For example: (Symfony, CodeIgniter, Zend Framework, Yii, CakePHP, etc)
            'CustomerAddress' => $customerAddress,
            'InvoiceItems' => $invoiceItems,
        ];

        //Call endpoint
        $data = $this->sendPayment($apiURL, $apiKey, $postFields);

        // dd($data);
        //You can save payment data in database as per your needs
        $invoiceId = $data->InvoiceId;
        $paymentLink = $data->InvoiceURL;

        //Redirect your customer to the invoice page to complete the payment process
        //Display the payment link to your customer
        return redirect($paymentLink);
        echo "Click on <a href='$paymentLink' target='_blank'>$paymentLink</a> to pay with invoiceID $invoiceId.";
        die;

    }

    public function budgetAfterDiscount($ad)
    {
        $discount = Discount::where('discount_code', session()->get('user_discount'))->first();
        if ($discount->type == 'value') {
            return $ad->budget - $discount->value;
        } else {
            return $ad->budget - ($discount->value / 100) * $ad->budget;
        }
    }
    /* ------------------------ Functions --------------------------------------- */
    /*
     * Send Payment Endpoint Function
     */

    function sendPayment($apiURL, $apiKey, $postFields)
    {
        // dd($apiKey);
        $json = $this->callAPI("$apiURL", $apiKey, $postFields);

        return $json->Data;
    }

    //------------------------------------------------------------------------------
    /*
     * Call API Endpoint Function
     */

    function callAPI($endpointURL, $apiKey, $postFields = [], $requestType = 'POST')
    {

        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => $requestType,
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));

        $response = curl_exec($curl);
        $curlErr = curl_error($curl);

        curl_close($curl);

        if ($curlErr) {
            //Curl is not working in your server
            die("Curl Error: $curlErr");
        }

        $error = $this->handleError($response);
        if ($error) {
            die("Error: $error");
        }

        return json_decode($response);
    }

    //------------------------------------------------------------------------------
    /*
     * Handle Endpoint Errors Function
     */

    function handleError($response)
    {

        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }

        //Check for the errors
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');

            $error = implode(', ', array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }

        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }

        return $error;
    }

    /* -------------------------------------------------------------------------- */
}
