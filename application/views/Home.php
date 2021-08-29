<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html data-wf-page="5e66056a415f1ee3724d7631" data-wf-site="5e66056a415f1ee8ad4d7630">

<head>
  <meta charset="utf-8">
  <title>ASPA | Welcome</title>
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <base href="<?php echo base_url(); ?>">
  <link href="assets/css/normalize.css?random=<?php echo uniqid(); ?>" rel="stylesheet" type="text/css">
  <link href="assets/css/webflow.css?random=<?php echo uniqid(); ?>" rel="stylesheet" type="text/css">
  <link href="assets/css/aspa.webflow.css?random=<?php echo uniqid(); ?>" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript">
    WebFont.load({
      google: {
        families: ["Droid Sans:400,700", "PT Serif:400,400italic,700,700italic"]
      }
    });
  </script>
  <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
  <script type="text/javascript">
    ! function(o, c) {
      var n = c.documentElement,
        t = " w-mod-";
      n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch")
    }(window, document);
  </script>
  <link href="assets/images/favicon.png?random=<?php echo uniqid(); ?>" rel="icon" type="image/png">
  <link href="assets/images/webclip.png?random=<?php echo uniqid(); ?>" rel="apple-touch-icon">
  <link href="assets/css/home.css?random=<?php echo uniqid(); ?>" rel="stylesheet">
</head>

<body>
  <div id="div-background" class="div-background"><img src="assets/images/ASPA-background.png" id="img-background" srcset="assets/images/ASPA-background-p-800.png 800w, assets/images/ASPA-background-p-1080.png 1080w, assets/images/ASPA-background-p-1600.png 1600w, assets/images/ASPA-background.png 1920w" sizes="(max-width: 1920px) 100vw, 1920px" alt="" class="img-background">
    <div class="div-background-mobile"></div>
  </div>

  <div class="container">
    <block class="title">
      <h1>ASPA Events Open for Signup</h1>
      <p>Please select event to register</p>
    </block>

    <block class="events">
      <div class="card">
        <div class="card-container">
          <h1><b>Casual Tuesdays</b></h1>
          <h4>3rd August</h4>
          <p>Casual Tuesdays are a lot of fun,
            jump in with a team of 4 and pool!</p>
        </div>
      </div>

      <div class="card">
        <div class="card-container">
          <h1><b>Casual Tuesdays</b></h1>
          <h4>5th August</h4>
          <p>Casual Tuesdays are a lot of fun,
            jump in with a team of 4 and pool!</p>
        </div>
      </div>

    </block>

    <div class="div-main-page"><a id="register" data-w-id="dfbe5add-65ea-95a7-6380-331c1db905e2" href="#" class="button w-button">Register</a>
      <p class="paragraph-pressenter">press <strong class="bold-enter">Enter ↵</strong></p>
    </div>
  </div>

  <div id="div-back-page3" class="div-back div-page-page3"><a id="btn-back-page3" href="#" class="btn-back w-button">← Back</a></div>

</body>

</html>