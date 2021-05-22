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

  <title>Form disabled • ASPA</title>

  <meta charset="utf-8">
  <meta name="description" content="Admin Page">
  <meta name='viewport' content='initial-scale=1.0' />
  <meta name="author" content="UoA Web Development & Consulting Club members">

  <link href="assets/images/favicon.png" rel="icon" type="image/png">

  <link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
  <link href="assets/css/webflow.css" rel="stylesheet" type="text/css">
  <link href="assets/css/aspa.webflow.css" rel="stylesheet" type="text/css">

  <link href="assets/css/admin.css" rel="stylesheet" type="text/css">
</head>

<body>
  <button class="button" id="back-btn" onClick="switchPage(1)">Back</button>

  <div class="page" id="home-page">
    <!-- <img src="assets/images/ASPA-admin.jpg"/>

    <div class="top">
      <h2>Sign In</h2>
    </div>
    <br> -->

    <button class="button" onClick="switchPage(2)">Email/UPI</button>
    <button class="button" onClick="switchPage(3)">QR Code</button>
  </div>

  <div class="page" id="email-page">
    <p>UPI:*</p>
    <input type="text" id="login-upi" name="login-upi">
    <p>Email:*</p>
    <input type="text" id="login-email" name="login-email">
    <br>
    <button class="button">Check User</button>
    <br>
  </div>


  <div class="page" id="qr-code-page">
    <p>Scan QR Code</p>
    <br>
  </div>


  <script type="text/javascript">

  const homePage = document.getElementById("home-page");
  const emailPage = document.getElementById("email-page");
  const qrCodePage = document.getElementById("qr-code-page");

  // 1 - home page, 2 - email page, 3 - qr code page
  const pages = [homePage, emailPage, qrCodePage];

  function switchPage(pageNumber) {
    for (const page of pages) {
      page.style.display = 'none';
    }

    pages[pageNumber - 1].style.display = 'block';
  }

  function checkUser() {
    console.log("User is being checked :)");
  }

  switchPage(1);

  </script>

  
</body>

</html>