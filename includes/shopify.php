<?php

class Shopify {
    public $api_key = '288ed9761c40bbe96c327012a6a69588';
    public $shop_url;
    public $access_token;

    public function __construct($shop_url, $access_token) {
        $this->shop_url = $shop_url;
        $this->access_token = $access_token;
    }

    public function reset_api($endPoint, $query = [], $method = 'GET')
    {
        $url = 'https://' . $this->shop_url . $endPoint;
        if (!is_null($query) && in_array($method, ['GET', 'DELETE'])) {
            $url .= '?' . http_build_query($query);
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        $headers = [
            'X-Shopify-Access-Token: ' . $this->access_token,
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        if (in_array($method, ['POST', 'PUT'])) {
            if (is_array($query)) {
                $query = json_encode($query);
            }

            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        }

        $response = curl_exec($curl);
        $error = curl_error($curl);
        
        if (curl_errno($curl)) {
            $message = curl_error($curl);
            curl_close($curl);

            return $message;
        } else {
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);

            return json_decode($body, true);
        }
    }
}