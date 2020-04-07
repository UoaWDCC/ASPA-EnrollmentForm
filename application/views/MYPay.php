<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="~/assets/js/qrcode.js"></script>


<div id="qrcode"></div>
<script type="text/javascript">

var myData = "<?php echo $url?>";

new QRCode(document.getElementById("qrcode"), $myData);
</script>
