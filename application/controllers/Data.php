<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require_once APPPATH.'/third_party/simpel_parser.php';

class Data extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('portal');
        $this->checkAuth();
    }

    public function krs()
    {
        $user = $this->payload;
        $this->portal->setCookie(array("username" => $user->id, "password" => $user->pec));
        $htmlPage = $this->portal->krsPage();
        $response = array();
        if ($htmlPage) {
            $html = new DOMDocument();
            $html->validateOnParse = true;
            libxml_use_internal_errors(true);
            $html->loadHTML($htmlPage);
            libxml_use_internal_errors(false);
            $divWarning = $html->getElementById('warning')->getElementsByTagName('table')->item(0)->getElementsByTagName('tr')->item(1)->getElementsByTagName('td')->item(0)->nodeValue OR false;
            if($divWarning){
                $response['status'] = $divWarning;
                
                $xpath = new DOMXPath($html);
                $info = $xpath->query("//table[@class='table-list']")->item(0);
                $fieldInfo = array("nama","nim","prodi","semester","maxSks","pa");

                foreach ($info->getElementsByTagName('tr') as $key => $element) {
                    $response['info'][$fieldInfo[$key]] = $element->getElementsByTagName('td')->item(0)->nodeValue;
                }
                
                $krs = $xpath->query("//table[@class='table-common']")->item(0);
                $krsRow = $krs->getElementsByTagName('tr');
                
                for ($i=1; $i <= $krsRow->count() - 2 ; $i++) { 
                    // var_dump($krsRow->item($i)->getElementsByTagName('td'));
                    $response['matkul'][] = array(
                        "kode" => $krsRow->item($i)->getElementsByTagName('td')->item(1)->nodeValue,
                        "nama" => $krsRow->item($i)->getElementsByTagName('td')->item(2)->nodeValue,
                        "sks" => $krsRow->item($i)->getElementsByTagName('td')->item(3)->nodeValue,
                    ); 
                }
            }
        }else{
            $response['error'] = "error";
        }

        $this->sendResponse($response);
    }

}
