<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Portal
{

    public $cookie = '';

    public function login($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://portalakademik.uin-alauddin.ac.id/index.php?pAct=proses&pSub=login&pModule=login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "content-type: multipart/form-data",
            ),
        ));

        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            $header = $this->getHeader($response);
            $code = explode(" ", $header[0]['http_code'])[1];
            $this->cookie = isset($header[0]['Set-Cookie']) ? explode(';', $header[0]['Set-Cookie'])[0] : $this->cookie;
            if ((int) $code == 302) {
                return $this->redirect($header[0]['Location'], $data);
                // return $header['Location'];
            }
        }

    }

    public function setCookie($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://portalakademik.uin-alauddin.ac.id/index.php?pAct=proses&pSub=login&pModule=login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "content-type: multipart/form-data",
            ),
        ));

        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            var_dump($err);
        } else {
            $header = $this->getHeader($response);
            $code = explode(" ", $header[0]['http_code'])[1];
            $this->cookie = isset($header[0]['Set-Cookie']) ? explode(';', $header[0]['Set-Cookie'])[0] : $this->cookie;
            if ((int) $code == 302) {
                return $this->redirect($header[0]['Location'], array());
            }

            return ($this->cookie !== '');
        }

    }

    public function krsPage()
    {

        $curl = curl_init();
        $url = "http://portalakademik.uin-alauddin.ac.id/index.php?pModule=academic_plan&pSub=academic_plan&pAct=view";

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://portalakademik.uin-alauddin.ac.id/index.php?pModule=academic_plan&pSub=academic_plan&pAct=view",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_COOKIE => $this->cookie,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return $response;
        }

    }

    public function khsList()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://portalakademik.uin-alauddin.ac.id/index.php?pAct=view&pSub=academic_report&pModule=academic_report",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_COOKIE => $this->cookie,
            CURLOPT_HTTPHEADER => array(
                "content-type: multipart/form-data",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return $response;
        }

    }

    public function khsSemester($id)
    {
        $curl = curl_init();

        $data = array(
            "lstSemester" => $id,
            "btnLihat" => "Lihat"
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://portalakademik.uin-alauddin.ac.id/index.php?pAct=view&pSub=academic_report&pModule=academic_report",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_COOKIE => $this->cookie,
            CURLOPT_HTTPHEADER => array(
                "content-type: multipart/form-data",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return $response;
        }

    }

    public function redirect($location, $data)
    {
        $curl = curl_init();
        // var_dump($location);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $location,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_COOKIE => $this->cookie,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "content-type: multipart/form-data",
            ),
        ));

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            // $header = $this->getHeader($response);
            // var_dump($header);
            // $code = explode(" ", $header['http_code'])[1];
            // $this->cookie = isset($header['Set-Cookie']) ? explode(';', $header['Set-Cookie']) : $this->cookie;
            // if ((int) $code == 302) {
            //     return $this->redirect($header['Location'], $data);
            // }else{
            //     return htmlentities($response);
            // }
            return $response;

        }
    }

    function print($nim) {
        $this->login(array("username" => "60200117026", "password" => "Ayobuka018"));
        return $this->getPdf($nim);
    }

    public function getPdf($nim)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://portalakademik.uin-alauddin.ac.id/index.php?prodi=9&niu=" . $nim . "&pAct=print&pSub=academic_plan&pModule=academic_plan",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_COOKIE => $this->cookie,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }

    }

    public function getHeader($response)
    {
        $headers = array();
        $prevHeader = 0;
        $nextHeader = strpos($response, "\r\n\r\n");
        $index = 0;
        $isMore = true;
        $header_text = substr($response, $prevHeader, $nextHeader);
// var_dump($header_text,"GEtHEdaer()");
        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0) {
                $headers[$index]['http_code'] = $line;
            } else {
                list($key, $value) = explode(': ', $line);

                $headers[$index][$key] = $value;
            }
        }

        return $headers;
    }
}
