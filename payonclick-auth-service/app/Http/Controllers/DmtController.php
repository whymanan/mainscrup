<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use GuzzleHttp\Client;


class DmtController extends Controller
{

    public $client;

    public $baseUrl = 'https://app.youcloudpayment.in/sdk/MobileReq/dmtTxn';

    public $requestcode = '';  // 'aepsTxn' = for aepsTransaction , 'aepsBanks' = for Bank List

    private $referenceCompany = "51VIT"; //Reference Company id provided by Saraswat infotech

    private $retailerCode = "9935000268"; //Merchant Id

    private $retailerState = "9935000268"; //State Of the Merchant

    private $mid = "EMO000000000002";

    private $referenceNo = '';

    private $key = 'AABBCCDDFFGGHHIIJJKKLLMMNNOOPPQQ';



    public function __construct() {

      $this->middleware('auth');

      $this->client = new \GuzzleHttp\Client();

    }

     public function dmtTransaction(Request $request) {

         $this->validate($request, [
             'beneficiaryAccountNumber' => 'required|string',
             'beneficiaryIfscCode' => 'required|string',
             'transactionAmount' => 'required|string',
             'referenceNo' => 'required|string',
             'customerName' => 'required|string',
             'customerMobile' => 'required|string',
         ]);
        $getHash = self::get_hash($request->beneficiaryAccountNumber, $request->customerMobile, $request->beneficiaryIfscCode, $request->transactionAmount, $this->referenceCompany);

        $data = [
              'headers' => [
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                ],
              'json' => [
                 "reqCode" => "dmtTx",
                 "referenceCompany" => $this->referenceCompany,
                 "hash" => $getHash,
                 "beneAccNo" => $request->beneficiaryAccountNumber,
                 "beneIfsc" => $request->beneficiaryIfscCode,
                 "amount" => $request->transactionAmount,
                 "referenceNo" => $request->referenceNo,
                 "custName" => $request->customerName,
                 "custMobile" => $request->customerMobile,
                 "remark" => $request->remark,
                 "retailerCode" => $this->retailerCode,
                 "retailerState" => $this->retailerState,
              ]
            ];

         $response = $this->client->request('post', $this->baseUrl,
            [
              'headers' => [
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                ],
              'json' => [
                 "reqCode" => "dmtTx",
                 "referenceCompany" => $this->referenceCompany,
                 "hash" => $getHash,
                 "beneAccNo" => $request->beneficiaryAccountNumber,
                 "beneIfsc" => $request->beneficiaryIfscCode,
                 "amount" => $request->transactionAmount,
                 "referenceNo" => $request->referenceNo,
                 "custName" => $request->customerName,
                 "custMobile" => $request->customerMobile,
                 "remark" => $request->remark,
                 "retailerCode" => $this->retailerCode,
                 "retailerState" => $this->retailerState,
              ]
            ]
          );

        $data = [
            'request' =>     $data,
            'responce' => $response->getBody()->getContents()
        ];

        return response($data);
     }

     private function get_hash($custMobile, $beneAccNo, $beneIfsc, $amount) {
       $string = $custMobile . ':' . $beneAccNo . ':' . $beneIfsc . ':' . $amount . ':' . $this->key;
        return hash('sha256', $string);
     }

}
