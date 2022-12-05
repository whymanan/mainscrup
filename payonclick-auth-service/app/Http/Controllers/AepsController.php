<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use GuzzleHttp\Client;


class AepsController extends Controller
{

    public $client;

    public $baseUrl = 'http://199.34.22.225:9087/YouCloudMiddleware/MobileReq/public/aepssbm';
    
   

    public $requestcode = '';  // 'aepsTxn' = for aepsTransaction , 'aepsBanks' = for Bank List

    private $username = "9935000268";

    private $imei = "";

    public $imsi = "";

    private $mid = "EMO000000000001";

    public $tid = "EMO00001";


    public function __construct() {

      $this->middleware('auth');

      $this->client = new \GuzzleHttp\Client();

      $this->imei = strval( abs( crc32( uniqid() ) ) );


    }

     public function aepsbanks() {

          $response = $this->client->request('post', $this->baseUrl,
            [
              'headers' => [
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                ],
              'json' => [
                "requestcode" => "aepsBanks",
                "username" => $this->username,
                "imei" => $this->imei,
                "imsi" => "null",
                "mid" => $this->mid,
                "tid" => $this->tid,
               ]
            ]
          );

          return response( $response->getBody()->getContents() );
     }


     public function aepsTransaction(Request $request) {

         $this->validate($request, [
             'bankCode' => 'required|string',
             'ifscCode' => 'required|string',
             'location' => 'required|string',
             'txType' => 'required|string',
             'aadharNo' => 'required|string',
             'amount' => 'required|string',
             'stan' => 'required|string',
             'data' => 'required|string',
         ]);

         $requestData =   [
             'headers' => [
               'cache-control' => 'no-cache',
               'content-type' => 'application/json',
               ],
             'json' => [
               "requestcode" => "aepsTxn",
               "username" => $this->username,
               "imei" => $this->imei,
               "imsi" => "null",
               "mid" => $this->mid,
               "tid" => $this->tid,
               "txType" => $request->txType,
               "aadharNo" => $request->aadharNo,
               "amount" => $request->amount,
               "bankCode" => $request->bankCode,
               "ifscCode" => $request->ifscCode,
               "location" => $request->location,
               "stan" => $request->stan,
               "data" => $request->data,
               "pidData" => $request->pidData
             ]
           ];

         $response = $this->client->request('post', $this->baseUrl, $requestData);
            
        // return  $requestData ;    
            
        //   print_r($requestData);
        
        // $data = [
        //         'req' => $requestData,
        //         'res' => json_decode($response->getBody()->getContents()),
        //     ];

        return response( $response->getBody()->getContents() );
     }

}
