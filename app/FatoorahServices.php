<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class FatoorahServices
{
    private $base_url;
    private $headers;
    private $request_client;

    public function __construct(Client $request_client)
    {
        $this->request_client = $request_client;
        $this->base_url = env('fatoora_base_url', 'https://apitest.myfatoorah.com/');

        $this->headers = [
         'Content-Type' => 'application/json',
         'authorization' => 'Bearer y_gOTkQ4okHtP5WZ0xDsaBLpByU_LJTwUHWf9oSCWsL2I6vBZj8vbfNz6mvkrrv75wJF9954ykMx9hUATRCCE-dnQbTwhnENzK4qWCmQHOMYcj7MROtprlm128juDi5lBg_Q__nPgvIOB0Flx7i4llXYMxnR_6nvSOWfW79QxEPrWyDYo2BZ0F8wMot9YxgYtVeTuz7_0UvXpe8Cewzl1X2R3WxoKO2rcZ6ON9ikAYVYMQTJvBnYQ479Iqw1o5mihM8rJV_TKAiyho1zLd4SEpa0YOJM5yQmj3F25GrX3KIwoji5Jv6eaevTrHbdGrcriejo9GY25nDVUrZnFOvA4hqETmusn0htkjJYxW_5BmIcN0u9hZ0ple4QVvAry7_YkEKkW3ra-t4-pl9rYVQei2Oir-qaRtnG468NA_pDUy1sppv3ruzCvx_kfcrNPisstnv6jbwB-I3OcfRcuBMiPfBxZnsRmiZS5nuZ4R-r9SjqHpdRUeJDTALPHf05aDvcO8zgV9dEWTgZdB-V52jPy8cSr_LGvxubtE4fTZ0xzKHKKr9vEghHCUGdSHEokCl7-83qZkR5EmYlxpQVphwl_yRFlgBxlJwbWuUx64BXT5LIGbqgh8Q-YHX7O8LpIby1L4rXD4CZPHBJQyEd8o007RtpLr59jof19lU3WiZwlo56gT3dY9CB4FxzAs4WP_g4TdqxLw',
        //  'authorization' => 'Bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL',
       ];
    }

    public function buildRequest($url, $mothod, $data = [])
    {
        $request = new Request($mothod, $this->base_url.$url, $this->headers);
        if (!$data) {
            return false;
        }
        $response = $this->request_client->send($request, ['json' => $data]);
        if ($response->getStatusCode() != 200) {
            return false;
        }
        $response = json_decode($response->getBody(), true);

        return $response;
    }

    public function sendPayment($data)
    {
        $response = $this->buildRequest('v2/SendPayment', 'POST', $data);

        return $response;
    }

    public function getPaymentStatus($data)
    {
        $response = $this->buildRequest('v2/getPaymentStatus', 'POST', $data);

        return $response;
    }

    public function callAPI($endpointURL, $apiKey, $postFields = [])
    {
        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => ["Authorization: Bearer $apiKey", 'Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $response = curl_exec($curl);
        $curlErr = curl_error($curl);
        curl_close($curl);

        return $response;
    }
}
