<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html data-wf-page="5e66056a415f1ee3724d7631" data-wf-site="5e66056a415f1ee8ad4d7630">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
  <div id="div-background" class="div-background">
    <img src="assets/images/ASPA-home-background.png">
    <div class="div-background-mobile"></div>
  </div>

  <div class="flex-container">
    <div class="container">
      <block class="title">
        <h1>ASPA Events Open for Signup</h1>
        <h3>Please select event to register</h3>
      </block>

      <block class="events">

        <?php
        for ($i = 0; $i < 6; $i++) {
          echo '
          <div class="card">
            <div class="card-container">
              <h1><b>Casual Tuesdays</b></h1>
              <h4>3rd August</h4>
              <p>Casual Tuesdays are a lot of fun, jump in with a team of 4 and pool!</p>
            </div>
          </div>';
        }
        ?>

      </block>

    </div>
  </div>

</body>

</html>