<!DOCTYPE html>

<html>

<head>
  <base href="<?php echo base_url(); ?>" />

  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
  <script type="text/javascript">
    WebFont.load({
      google: {
        families: ["Droid Sans:400,700", "PT Serif:400,400italic,700,700italic"]
      }
    });
  </script>

  <title>QR Code • ASPA</title>

  <meta charset="utf-8">
  <meta name="description" content="Form Disabled">
  <meta name='viewport' content='initial-scale=1.0' />
  <meta name="author" content="UoA Web Development & Consulting Club members">

  <link href="assets/css/normalize.css?random=<?php echo uniqid(); ?>" rel="stylesheet" type="text/css">
  <link href="assets/css/webflow.css?random=<?php echo uniqid(); ?>" rel="stylesheet" type="text/css">
  <link href="assets/css/aspa.webflow.css?random=<?php echo uniqid(); ?>" rel="stylesheet" type="text/css">
  <link href='assets/css/qrCode.css?random=<?php echo uniqid(); ?>' rel='stylesheet' type='text/css' />
  <link href="assets/images/favicon.png?random=<?php echo uniqid(); ?>" rel="icon" type="image/png">

  <script src="assets/lib/qrcode.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>

</head>

<body>
  <div class="centre-page">
    <div id="qr-code"></div>
    <h1>Scan me! 📷</h1>
    <p>Have this QR code ready to check-in to the event.</p>
    <p>Contact
      <a href="mailto:uoapool@gmail.com">uoapool@gmail.com</a>
      if you have any questions.
    </p>
  </div>

  <script>
    // get url params
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    // add new entry if you need another param
    const email = urlParams.get('email');
    const eventName = urlParams.get('event');

    // qr data to object
    const qrData = {
      email: email,
      eventName: eventName,
    }

    // convert qrData json to string
    const qrEncoding = JSON.stringify(qrData);
    console.log(qrEncoding);

    // width scaling
    var qrsize = 256;
    if (window.innerWidth < 450) {
      qrsize = 225;
    } else {
      qrsize = 256;
    }

    // qr code generation and display as png image
    var qrcode = new QRCode("qr-code", {
      text: qrEncoding,
      width: qrsize,
      height: qrsize,
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H
    });

    var doc = new jsPDF();

    doc.setFontSize(15);

    setTimeout(() => {
      var base64Image = document.querySelector('#qr-code img').attributes.src.value;

      doc.addImage(base64Image, 'png', 0, 0, 40, 40);
      doc.save('generated.pdf');
    }, 1000);
  </script>
</body>

</html>