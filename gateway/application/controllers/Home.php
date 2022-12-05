<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

  	public function __construct() {
  		  parent::__construct();
        if(check()) {
          redirect(base_url() . 'dashboard', 'refresh' );
        } else {
          redirect(base_url() . 'auth', 'refresh' );
        }

  	}
     public function index() {

     }
}
