<?php defined('BASEPATH') OR exit('No direct script access allowed');

class REST
{
    public function callAPI($method, $url, $data, $token = false)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, true);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }

                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }

                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }

        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);

        //Tell cURL that it should only spend 10 seconds
        //trying to connect to the URL in question.
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);

        //A given cURL operation should only take
        //30 seconds max.
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        
        if ($token) {

            curl_setopt($curl, CURLOPT_HTTPHEADER, $token);

        }

        // EXECUTE:
        $result = curl_exec($curl);

        $headerSent = curl_getinfo($curl, CURLINFO_HEADER_OUT ); 

        if (!$result)  { log_message('DEBUG', '#TRAZA | #REST | #CURL >> Fallo Conexión'); return ['status' => false, 'header' => false, 'data' => false, 'code'=> '404']; }

        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        $headers = substr($result, 0, $header_size);

        $body = substr($result, $header_size);

        curl_close($curl);

        if ($response_code >= 300) {

            log_message('DEBUG', '#TRAZA | #REST | #CURL | #HTTP_CODE >> ' . $response_code);

            log_message('DEBUG', '#TRAZA | #REST | #CURL | #HEADER >> ' . $headers);

            log_message('DEBUG', '#TRAZA | #REST | #CURL | #BODY >> ' . json_encode($body));

        } 

        return ['status' => ($response_code<300), 'header' => $headers, 'data' => $body, 'code'=> $response_code];
        
    }

}
