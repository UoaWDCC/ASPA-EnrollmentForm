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
</head>

<body>
  <div class="centre-page">
    <div class="qr" id="qr-code"></div>
    <h1>Scan me! 📷</h1>
    <p>Have this QR code ready to check-in to the event.</p>
    <p>Contact
      <a href="mailto:uoapool@gmail.com">uoapool@gmail.com</a>
      if you have any questions.
    </p>
  </div>

  <script type="module">
    import QrCreator from '../../assets/lib/qr-creator.js';

    // get url params
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    // add new entry if you need another param
    const email = urlParams.get('email');
    const eventName = urlParams.get('event');

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

    // render QR code, note this is a canvas element
    QrCreator.render({
      text: qrEncoding,
      radius: 0.5, // 0.0 to 0.5
      ecLevel: 'H', // L, M, Q, H
      fill: '#333', // foreground color
      background: null, // color or null for transparent
      size: qrsize // in pixels
    }, document.querySelector('#qr-code'));
  </script>
</body>

</html>