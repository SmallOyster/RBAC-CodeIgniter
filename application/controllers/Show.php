<?php
/**
* @name C-显示
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-06
* @version V1.0-B180207 2018-02-07
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Show extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('RBAC_model');
  }


  public function index()
  {
    $this->load->view('index',["navData"=>$this->RBAC_model->getAllMenu()]);
  }


  public function blank()
  {
    $this->load->view('blank');
  }


  public function login()
  {
    $this->load->view('login');
  }
}
