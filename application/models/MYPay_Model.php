<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MYPay_Model extends CI_Model {

    // Note if the child has no constructor then parent constructor is used
    function __construct()
    {
        // You have to explicitly call parent Constructor
        parent::__construct();
    }

    function MakeMYPay($customerEmail, $pay_type)
    {
        $url = "https://a.mypaynz.com/api/online";

        //$pay_type = "IE0022";

        $api_key = MYPAYKEY;
        $mid = MYPAYMID;
        $out_trade_no = $pay_type . date("Ymdhis") ;
        $amount = AMOUNT;
        $goods = "test";
        $goods_detail = "detaildetail";
        $md5_string = "";

        $params = [
            'goods'=>$goods,
            'goods_detail'=>$goods_detail,
            'mid'=>$mid,
            'out_trade_no'=>$out_trade_no,
            'pay_type'=>$pay_type,
            'total_fee'=>$amount,
            'version'=> 'v1'
        ];

        //Creating Signature
        foreach ($params as $key => $value) {
            $md5_string .=  $key . "=" . $value . "&";
        }

        $md5_string = rtrim($md5_string, "& ");

        $md5_string .= $api_key;
        $params['sign'] = md5($md5_string);

        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $params);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);
        $result = json_decode($result,true);

        //echo var_dump($result);

        return $result['extra'];
    }

    function CheckMYPay ($MYd)
    {
        $url = "https://a.mypaynz.com/api/check_order_status";
        $api_key = MYPAYKEY;


        $params = [
            'mid'=>$MYd['mid'],
            'out_trade_no'=>$MYd['out_trade_no'],
            'pay_type'=>$MYd['pay_type'],
            'version'=> 'v1'
        ];

        $md5_string = "";

        //Creating Signature
        foreach ($params as $key => $value) {
            $md5_string .=  $key . "=" . $value . "&";
        }

        $md5_string = rtrim($md5_string, "& ");

        $md5_string .= $api_key;
        $params['sign'] = md5($md5_string);

        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $params);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);
        $result = json_decode($result,true);

        return $result;

    }
}
