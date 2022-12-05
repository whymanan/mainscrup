<?php
defined('BASEPATH') or exit('No direct script access allowed');


use GuzzleHttp\Client;

class Demo extends Vite
{

    public $data = array();
    public $client;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('List_model');

        $this->client = new Client();

        $this->data['menu'] = $this->db->select("menu_permission_id ")->get_where('menu_permission', array("parent_id" => ""))->result();

        $this->data['active'] = 'dashboard';
        $this->data['breadcrumbs'] = [array('url' => base_url('dashboard'), 'name' => 'Dashboard')];
    }


    public function index() {

        $url = 'http://dummy.restapiexample.com/api/v1/create';
        #guzzle
        try {

          $response = $this->client->request('POST', $url, [
          'form_params' => [
              'name' => 'jon - due',
              'salary' => '100055',
              'age' => '54',
            ],
          ]
        );

          # guzzle post request example with form parameter
          #guzzle repose for future use
          echo $response->getStatusCode(); // 200
          echo $response->getReasonPhrase(); // OK
          echo $response->getProtocolVersion(); // 1.1
          echo $response->getBody();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
          #guzzle repose for future use
          $response = $e->getResponse();
          $responseBodyAsString = $response->getBody()->getContents();
          print_r($responseBodyAsString);
        }
    }
}
