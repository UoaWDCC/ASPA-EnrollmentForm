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

  <title>QR Scanner • ASPA</title>

  <meta charset="utf-8">
  <meta name="description" content="QR scanner">
  <meta name='viewport' content='initial-scale=1.0' />
  <meta name="author" content="UoA Web Development & Consulting Club members">

  <link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
  <link href="assets/css/webflow.css" rel="stylesheet" type="text/css">
  <link href="assets/css/aspa.webflow.css" rel="stylesheet" type="text/css">
  <link href='assets/css/qrScanner.css' rel='stylesheet' type='text/css' />
  <link href="assets/images/favicon.png" rel="icon" type="image/png">

  <script type="text/javascript" src="assets/js/instascan.min.js"></script>
</head>

<body>
  <div class="centre-page">
    <h1>Scan QR:</h1>
    <div class="video-container">
      <video id="preview" width="100px" height="100px"></video>
    </div>
    <p class="p" id="log"></p>
  </div>

  <!-- TODO: implement going back to admin dashboard -->
  <div class="div-back div-page-page3"><a id="btn-back-page3" href="#" class="btn-back w-button">← Back</a></div>

  <script type="text/javascript">
    // HTML fields
    const logField = document.getElementById("log");

    // create scanner instance
    const args = {
      video: document.getElementById('preview')
    };
    window.URL.createObjectURL = (stream) => {
      args.video.srcObject = stream;
      return stream;
    };
    let scanner = new Instascan.Scanner(args);

    // listens to detection of a qrcode
    scanner.addListener('scan', function(content) {
      if (content) {
        // incase content not in JSON format
        try {
          // JSON in the form {"email":"...","eventName":"..."}
          const details = JSON.parse(content);
          console.log(details);

          // TODO: mark as present

          logField.textContent = "Email: " + details.email + " ✔️";

        } catch (e) {
          console.error(e);
          logField.textContent = "QR code not correct";
        }
      }
    });

    // unlocks access to camera
    Instascan.Camera.getCameras().then(function(cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        console.error('No cameras found.');
      }
    }).catch(function(e) {
      console.error(e);
      logField.textContent = e;
    });
  </script>
</body>

</html>