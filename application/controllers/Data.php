<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require_once APPPATH.'/third_party/simpel_parser.php';


class Data extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('portal');  
        $this->checkAuth();      
    }


    public function krs()
    {
        $user = $this->payload;
        $this->portal->setCookie(array("username" => $user->id, "password" => $user->pec ));
        $htmlPage = $this->portal->krsPage();
        var_dump($htmlPage);
    }

}