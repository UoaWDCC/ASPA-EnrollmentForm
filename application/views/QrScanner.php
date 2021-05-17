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
  <meta name="description" content="QR reader">
  <meta name='viewport' content='initial-scale=1.0' />
  <meta name="author" content="UoA Web Development & Consulting Club members">

  <link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
  <link href="assets/css/webflow.css" rel="stylesheet" type="text/css">
  <link href="assets/css/aspa.webflow.css" rel="stylesheet" type="text/css">
  <link href='assets/css/formDisabled.css' rel='stylesheet' type='text/css' />
  <link href="assets/images/favicon.png" rel="icon" type="image/png">

  <script type="text/javascript" src="assets/js/instascan.min.js"></script>
</head>

<body>
  <video id="preview"></video>
  <script type="text/javascript">

    //great issue fix
    //https://github.com/schmich/instascan/issues/251
    const args = {
      video: document.getElementById('preview')
    };
    window.URL.createObjectURL = (stream) => {
      args.video.srcObject = stream;
      return stream;
    };

    let scanner = new Instascan.Scanner(args);

    scanner.addListener('scan', function(content) {
      console.log(content);
    });

    Instascan.Camera.getCameras().then(function(cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        console.error('No cameras found.');
      }
    }).catch(function(e) {
      console.error(e);
    });
  </script>
</body>

</html>