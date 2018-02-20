<?php
/**
 * @name C-基本
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-07
 * @version V1.0-B180207 2018-02-07
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('RBAC_model');
    $this->allMenu=$this->RBAC_model->getAllMenu();
  }


  public function index()
  {
    $this->load->view('index',["navData"=>$this->allMenu]);
  }
}
