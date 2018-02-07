<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
  
  function __construct()
  {
    parent::__construct();
    //$this->load->library('');
  }
  
  public function login_show()
  {
    $this->load->view('user/login');
  }
  
  public function login_handle()
  {
  }
  
  public function logout()
  {
  }
  
  public function show_nav()
  {
  }
}
