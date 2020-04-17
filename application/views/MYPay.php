<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="<?= base_url();?>assets/js/qrcode.js"></script>


<?php 
    //echo var_dump($MYd);
    $this->load->model('MYPay_Model');
    $result = $this->MYPay_Model->CheckMYPay($MYd);

    //echo var_dump($result);

    //echo var_dump($MYd);
?>

<div id="qrcode"></div>

<script type="text/javascript">

var payType = "<?php echo $MYd['pay_type']?>";
var url = "<?php echo $MYd['pay_url']?>";
var has_paid = 0;

if (payType == "IE0011" || payType == "IE0021" || payType == "IE0022" || payType == "IE0023") {
    new QRCode(document.getElementById("qrcode"), url);
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



