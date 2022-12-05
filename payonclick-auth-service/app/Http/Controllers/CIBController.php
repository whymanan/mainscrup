<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use GuzzleHttp\Client;


class CIBController extends Controller
{

    public $client;

    public $baseUrl = 'https://apibankingonesandbox.icicibank.com/api/Corporate/CIB/v1/';


    private $AGGRID = "CUST0496"; 

    private $AGGRNAME = "EMOPAY"; 
    
    private $CORPID = "PRACHICIB1"; 
    
    public $urn = ""; //UNIQUEREFERENCENumber

    private $key = 'gSqnakHnE8afeSoulgRj9tzA7ztdQZGU';



    public function __construct() {

      $this->middleware('auth');

      $this->client = new \GuzzleHttp\Client();
      $this->urn = strval( abs( crc32( uniqid() ) ) );

    }

     public function CibRegistration(Request $request) {
         
         $this->validate($request, [
             'userid' => 'required|string',
         ]);


        // $getHash = self::get_hash($request->beneficiaryAccountNumber, $request->customerMobile, $request->beneficiaryIfscCode, $request->transactionAmount, $this->referenceCompany);
         $response = $this->client->request('POST', $this->baseUrl . 'Registration',
            [
              'headers' => [
                'cache-control' => 'no-cache',
                'content-length' => '684',
                'content-type' =>  'text/plain',
                'apikey' =>  $this->key
                ],
              'json' => [
                "AGGRID" =>  $this->AGGRID,
                "CORPID" =>  $this->CORPID,
                "USERId" =>  $request->userid,
                "UNIQUEREFERENCENumber" =>  $this->urn,
                "AGGRNAME" =>  $this->AGGRNAME,
                "ALIASId" =>  "USER3"
              ]
            ]
          );

        // $data = [
        //     'request' =>     $response->getBody(),
        //     'responce' => $response->getBody()->getContents()
        // ];

        echo $response->getBody()->getContents();
        // return response($response->getBody()->getContents());
     }

     private function get_hash($custMobile, $beneAccNo, $beneIfsc, $amount) {
       $string = $custMobile . ':' . $beneAccNo . ':' . $beneIfsc . ':' . $amount . ':' . $this->key;
        return hash('sha256', $string);
     }

}
