<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="<?= base_url();?>assets/js/qrcode.js"></script>


<?php 
    //echo var_dump($MYd);
?>

<div id="qrcode"></div>
<script type="text/javascript">

var url = "<?php echo $MYd['pay_url']?>";

new QRCode(document.getElementById("qrcode"), url);
</script>
