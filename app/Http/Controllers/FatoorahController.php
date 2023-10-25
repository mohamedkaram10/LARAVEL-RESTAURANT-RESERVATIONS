<?php

namespace App\Http\Controllers;

use App\FatoorahServices;
use Illuminate\Support\Facades\Request;

class FatoorahController
{
    private FatoorahServices $fatoorahServices;

    public function __construct(FatoorahServices $fatoorahServices)
    {
        $this->$fatoorahServices = $fatoorahServices;
    }

    public function checkout()
    {
        $data = [
            'CustomerName' => 'mohamed',
            'Notificationoption' => 'LNK',
            'Invoicevalue' => 1232, // total_price
            'CustomerEmail' => 'mohamed@gmail.com',
            'CalLBackUrl' => 'http://rest_app.test/admin/callback',
            'Errorurl' => 'http://rest_app.test/admin//error',
            'Languagn' => 'en',
            'DisplayCurrencyIna' => 'SAR',
        ];

        $response = $this->fatoorahServices->sendPayment($data);

        dd($response);

        // return $response;

        // if (isset($response['IsSuccess'])) {
        //     if ($response['IsSuccess'] == true) {
        //         $InvoiceId = $response['Data']['InvoiceId']; // save this id with your order table
        //         $InvoiceURL = $response['Data']['InvoiceURL'];
        //     }
        // }

        // return redirect($response['Data']['InvoiceURL']); // redirect for this link to view payment page
    }

    public function callback(Request $request)
    {
        $apiKey = config('myfatoorah.api_key');
        $postFields = [
            'Key' => $request->paymentId,
            'KeyType' => 'paymentId',
            ];
        $response = $fatoorahServices->callAPI('https://apitest.myfatoorah.com/v2/getPaymentStatus', $apiKey, $postFields);
        $response = json_decode($response);
        if (!isset($response->Data->InvoiceId)) {
            return response()->json(['error' => 'error', 'status' => false], 404);
        }
        $InvoiceId = $response->Data->InvoiceId; // get your order by payment_id
        if ($response->IsSuccess == true) {
            if ($response->Data->InvoiceStatus == 'Paid') {// ||$response->Data->InvoiceStatus=='Pending'
                if ($your_order_total_price == $response->Data->InvoiceValue) {
                    /*
                     *
                     * The payment has been completed successfully. You can change the status of the order
                     *
                     */
                }
            }
        }

        return response()->json(['error' => 'error', 'status' => false], 404);
    }
}
