<?php

namespace App\Http\Controllers;

class MyfatoorahCheckPaymentStatusController extends Controller
{
    public function checkStatus($paymentId)
    {

        //Test
        $apiURL = 'https://apitest.myfatoorah.com';
        $apiKey = 'A4JODUv5e_MuMyZsq7UkYbNDw9M5nBsdusuuJrtlswyFfWNgPtcyxgboPbCvFo1QK-dt2Cb1EkNhRMMkgcKZCyGfW6yJs2-l-j_yqBy21qaZJ--3bJiiGZhHZBGZkuy96-HgKKFmOhyQ4vSQt0QXO4oFq3SkF0Pac09KlqA5ht847_EoYu4zKR9A61zu8mR48jlkt0RxmKgpx6cgQFzeBITtMvSnzedEX92P5T9DV4JKTWsTSrGgfE9IQJbC4Ab25fzjz7BEC5MxLSCQbR01Fm553GAfcu8gBB_9KMbjFwh9zbMRF-fNWfylbH9dWLhvPBQixsLNdejl-tzgUw-hOH7XA_X5a6dOWxEcn4ya4aUzHRq9LTfO1431UsK-LXD8RwRhndmOftHR__BQeRGof1T97pTaQk0Nrp1r76PdpNsk-ovbEuzNWbtLlwLGXV9IyHSf80DOy5d3s3mcUwdhb_KKSWob5OgmgLz8Pjq7oeB83np8kXfjKvg43c9MfdgV9V87SJy_tDOvsao1AkzkJl_6jUCUahXxA_-a9fIUJ1RAbclWebt_gekE_qqmUksyLIDeACwaFx-95MFjY6LG2-K4y31bQwmx0oBK4z2RirhSNEfZTx59aq6QJuG7Nv73EBnbMINmIQ0sqp1T0lB_rTbj-3pbKraYI1q8WofKEVatmMAugvLEpDpnHLMuzxdKziBagdy8RbmuGOOxGQIPLDmvKM08RsvrARLpCMT04HO8oMqv'; //Test token value to be placed here: https://myfatoorah.readme.io/docs/test-token


        //Live
        //$apiURL = 'https://api.myfatoorah.com';
        //$apiKey = ''; //Live token value to be placed here: https://myfatoorah.readme.io/docs/live-token

        /* ------------------------ Call getPaymentStatus Endpoint ------------------ */
        //Inquiry using paymentId
        $keyId = $paymentId;
        $KeyType = 'paymentId';

        //Inquiry using invoiceId
        /*$keyId   = '613842';
        $KeyType = 'invoiceId';*/

        //Fill POST fields array
        $postFields = [
            'Key' => $keyId,
            'KeyType' => $KeyType
        ];

//Call endpoint
        $json = $this->callAPI("$apiURL/v2/getPaymentStatus", $apiKey, $postFields);

//Display the payment result to your customer
        return $json->Data->InvoiceStatus;
    }
    /* ------------------------ Functions --------------------------------------- */
    /*
     * Call API Endpoint Function
     */

    function callAPI($endpointURL, $apiKey, $postFields = [])
    {

        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
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
