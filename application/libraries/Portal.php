<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Portal
{

    public $cookie = '';

    public function login()
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
            echo "cURL Error #:" . $err;
        } else {
            $header = $this->getHeader($response);
            $code = explode(" ", $header['http_code'])[1];
            $this->cookie = isset($header['Set-Cookie']) ? explode(';', $header['Set-Cookie'])[0] : $this->cookie;
            if ((int) $code == 302) {
                return $this->redirect($header['Location'], $data);
                // return $header['Location'];
            }
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
            echo "cURL Error #:" . $err;
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

    public function getHeader($response)
    {
        $headers = array();

        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }
        }

        return $headers;
    }
}
