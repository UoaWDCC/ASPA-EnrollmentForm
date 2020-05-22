<!DOCTYPE html>

<?php
    //echo var_dump($MYd);
    $this->load->model('MYPay_Model');
    $result = $this->MYPay_Model->CheckMYPay($MYd);

    //echo var_dump($result);

    //echo var_dump($MYd);
?>

<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="<?php base_url(); ?>/assets/js/qrcode.js"></script>

        <base href='<?php echo base_url(); ?>' />
        <meta charset='utf-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <title>ASPA | QR Code</title>

        <script type="text/javascript">WebFont.load({  google: {    families: ["Droid Sans:400,700","PT Serif:400,400italic,700,700italic"]  }});</script>
        <link rel='stylesheet' href='assets/css/enrollmentForm.css' type='text/css' />
        <link rel='stylesheet' href='assets/css/webflow.css' type='text/css' />
        <link rel="stylesheet" href="assets/css/aspa.webflow.css" type="text/css">
    </head>

    <body>
        <div id="div-back-page3" class="div-back div-page-page3"><a id="btn-back-page3" href="" class="btn-back w-button">← Back</a></div>

        <div class='qr-container'>
            <div>
                <h4 id='price'></h4>
                <div id="qrcode"></div>
                <p>Scan to proceed to <img id='payment-method'></p>
            </div>
        </div>


        <script type="text/javascript">

            var payType = "<?php echo $MYd['pay_type']?>";
            var url = "<?php echo $MYd['pay_url']?>";
            var has_paid = 0;

            let paymentDOM = document.getElementById("payment-method");
            let priceDOM = document.getElementById("price")

            // Display QR Code based on destination
            if (payType == "IE0011" || payType == "IE0012" || payType == "IE0021" || payType == "IE0022" || payType == "IE0023") {
                new QRCode(document.getElementById("qrcode"), url);

                priceDOM.innerHTML = 'NZD: $<?php echo $price; ?>';

                // Changing icons of text messages
                // AliPay
                if (payType == "IE0011" || payType == "IE0012") {
                    paymentDOM.src = "assets/images/alipay.png";

                } else if (payType == "IE0021" || payType == "IE0022" || payType == "IE0023") {
                    paymentDOM.src = "assets/images/wechatpay.png";
                }

            }

            else {
                location.href = "<?php echo $MYd['pay_url']?>";
            }





            var checkPayment = function(){
                if(has_paid = 0){
                    // run when condition is met
                    alert('Hi');
                }
                else {
                    has_paid = "<?php echo $result['extra']['order_status']?>"
                    setTimeout(checkPayment, 1000); // check again in a second
                }
            }

            checkPayment();

        </script>
    </body>
</html>
