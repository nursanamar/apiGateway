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
		$user = $this->getBody();
		$data = $this->portal->login($user);
		$html = new DOMDocument();
		$html->validateOnParse = true;
		libxml_use_internal_errors(true);
		$html->loadHTML($data);
		libxml_use_internal_errors(false);

		$user_info = $html->getElementById('user-info');

		$result = array(
			"Nama" => $user_info->getElementsByTagName('h3')->item(0)->nodeValue,
			"Jurusan" => $user_info->getElementsByTagName('h4')->item(1)->nodeValue,
			"Nim" => $user_info->getElementsByTagName('h4')->item(0)->nodeValue,
		);

		$this->sendResponse($result);
		$html->getElementById('front-content-full');
	}
}
