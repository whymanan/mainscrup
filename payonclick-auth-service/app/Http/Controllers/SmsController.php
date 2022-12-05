<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use GuzzleHttp\Client;

use App\Sms;


class SmsController extends Controller {

    public $client;

    public $baseUrl = 'http://websms.smsxperts.com/app/smsapi/index.php';

    public $type = 'text';  // 'text' = for aepsTransaction

    private $senderId = 'EMOHAA';

    private $api_key = '55F97D20AD4303';

    public $routeid = 45;

    private $campaign = '0';

    public $contacts = "";

    public $massege = "";


    public function __construct() {

      $this->middleware('auth');

      $this->client = new \GuzzleHttp\Client();

    }

     public function textsms(Request $request) {

          if (!empty($request->phone)) {

            $this->contacts = $request->phone;

            $this->massege = $request->msgtext;

            $response = $this->client->request('POST', $this->baseUrl,
            [
              'form_params' => [
                'key' => $this->api_key,
                'campaign' => $this->campaign,
                'routeid' => $this->routeid,
                'type' => $this->type,
                'contacts' => $this->contacts,
                'senderid' => $this->senderId,
                'msg' => $this->massege
              ]
            ]
          );
          $result  = $response->getBody()->getContents();
          $output = [
            'status' => $response->getStatusCode(),
            'msg' => $result,
          ];

          return response()->json($output);

        } else {

          return response()->json(['error' => 403, 'msg' => 'Access Denied']);

        }
     }
}
