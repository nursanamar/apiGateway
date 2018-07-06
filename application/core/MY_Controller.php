<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/***********************************************************



*************************************************************/

class MY_Controller extends CI_Controller {

	private $jwtToken;
	public $payload;
	public $user;

	 public function __construct()
	{
		parent::__construct();
		$this->load->library('jwt');
		// $this->load->model('user','login');
	}

	 public function sendResponse($data,$headers = array())
	{
		foreach($headers as $key => $value){
			$this->output->set_header($key." : ".$value);
		}
		 $this->output->set_content_type("application/json");
		 $this->output->set_header("Access-Control-Allow-Origin: *");
		 $this->output->set_header("X-Message: ApiBuilder/1.0");
		 $this->output->set_header("Server: ApiBuilder",true);
		$this->output->set_output(json_encode($data));
	}

	public function getBody()
	{
		 $data = json_decode($this->input->raw_input_stream,true);
		 return $data;
	}

	public function checkToken()
	{
		$status = "auth";
		$headers = $this->input->get_request_header("Authorization");
		list($token) = sscanf($headers,"Bearer %s");

		if($token === null){
		 $this->output->set_status_header(401);
		 die();
		}

		$this->jwtToken = $token;
	}

	public function checkAuth()
	{
		$this->checkToken();

		 try{

			$valid = $this->jwt->decode($this->jwtToken,$this->input->get_request_header("User-agent",true)."nursan");

			} catch( \UnexpectedValueException $ec) {
			 $this->output->set_status_header(401);
		die();
		}

		$this->payload = $valid;
	}

	public function generateToken()
	{
		$input = $this->getBody();
		$iss = $this->input->get_request_header('User-agent',true);
		$user = $this->user;
		$payload = array('id' => $user['nim'] ,'name' => $user['nama'] ,'iss' => $iss );
		$token = $this->jwt->encode($payload,$iss."nursan");

		return $token;
	}

	public function validateLogin()
	{
		$input = $this->getBody();

		if (isset($input['nim']) && isset($input['pass'])) {

			$result = $this->login->chekUser($input['nim']);

			if ($result === null) {
				throw new Exception("NIM tidak terdaftar atau salah", 1);
			}else {
				if (password_verify($input['pass'],$result['pass'])) {
					$this->user = array(
						'nim' => $result['nim'],
						'nama' => $result['nama']
					);
					return true;
				}else{
					throw new Exception("Password anda salah", 1);
				}
			}

		}else {
			throw new Exception("check your field", 1);
		}
	}

	public function checkTable($table)
	{
		if ($this->db->table_exists($table)) {
			if (in_array($table,array('tables','users'))) {
				throw new Exception("Table forbiden", 1);
			}else {
					return true;
			}
		}else{
			throw new Exception("Table ".$table." doest'n exists", 1);

		}
	}

	public function sendError($message,$code = null)
	{
		$data = array('status' => 'error' ,'desc' => $message );
		$this->output->set_content_type("application/json");
		$this->output->set_header("Access-Control-Allow-Origin: *");
		$this->output->set_header("X-Message: ApiBuilder/1.0");
		$this->output->set_header("Server: ApiBuilder",true);
	 $this->output->set_output(json_encode($data));
	}

}

 ?>
