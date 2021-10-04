<?php



defined('BASEPATH') OR exit('No direct script access allowed');

class Polipay_Model extends CI_Model {

    Public function makePolipayPayment($customer_email, $eventData) {

        $client = new GuzzleHttp\Client();

        $res = $client->request('POST', 'https://poliapi.apac.paywithpoli.com/api/v2/Transaction/Initiate', [
            'Amount' => (float) $eventData["price"] * 100,
            'CurrencyCode' => 'NZD',
            'MerchantReference' => $customer_email, //probably use customer_email
            'MerchantData' => '',
            'MerchantHomepageURL' => 'aspa.wdcc.co.nz',
            'CancellationURL' => '', 
            'SuccessURL' => '',
            'FailureURL' => ''
        ]);
        echo $res->getStatusCode();
        // "200"
        echo $res->getHeader('content-type')[0];
        // 'application/json; charset=utf8'
        echo $res->getBody();
        // {"type":"User"...'

        $navigationUrl = $res->getBody()["NavigationURL"];
        if(isset($navigationUrl)) {
            redirect($navigationUrl);
        } else {
            show_error("error");
        }
    }

    Public function polipaySuccessful() {
        $email = $this->input->get("email");
        $token = $this->input->get("token");

        $client = new GuzzleHttp\Client();

        $res = $client->request('GET', 'https://poliapi.apac.paywithpoli.com/api/v2/Transaction/GetTransaction?token='. $token);

        if($res->TransactionStatusCode == "Completed") {
            echo "polipay success";
        } else {
            echo "payment not successful";
        }
    }

}