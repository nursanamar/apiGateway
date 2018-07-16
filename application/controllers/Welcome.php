<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require_once APPPATH.'/third_party/simpel_parser.php';


class Welcome extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('portal');
	}

	public function login()
	{
		$user = $this->getBody();
		$data = $this->portal->login($user);

		if(!($data)){
			$result = array(
				"error" => "Login gagal"
			);
		}else{
			$html = new DOMDocument();
			$html->validateOnParse = true;
			libxml_use_internal_errors(true);
			$html->loadHTML($data);
			libxml_use_internal_errors(false);
	
			$user_info = $html->getElementById('user-info');
			$result = array(
				"nama" => $user_info->getElementsByTagName('h3')->item(0)->nodeValue,
				"jurusan" => $user_info->getElementsByTagName('h4')->item(1)->nodeValue,
				"nim" => $user_info->getElementsByTagName('h4')->item(0)->nodeValue,
			);

			$this->user = array(
				"nim" => $result['nim'],
				'nama' => $result['nama']
			);

			$result['token'] = $this->generateToken($user['password']);

		}

		$this->sendResponse($result);
	}

	public function enc()
	{
		$this->checkAuth();
		$this->sendResponse($this->payload);
	}

	public function print($nim)
	{
		$res = $this->portal->print($nim);

		echo $res;
	}
}
