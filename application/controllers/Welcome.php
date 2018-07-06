<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'/third_party/simpel_parser.php';


class Welcome extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('portal');
	}

	public function index()
	{
		$data = $this->portal->login('d','a');
		$html = new DOMDocument();
		$html->validateOnParse = true;
		libxml_use_internal_errors(true);
		$html->loadHTML($data);
		libxml_use_internal_errors(false);
		var_dump($html->getElementById('user-info'));
		// $html->getElementById('front-content-full');
	}
}
