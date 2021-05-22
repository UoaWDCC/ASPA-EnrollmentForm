<!DOCTYPE html>

<html>

<head>
  <base href="<?php echo base_url(); ?>" />


  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript">
    WebFont.load({
      google: {
        families: ["Droid Sans:400,700", "PT Serif:400,400italic,700,700italic"]
      }
    });
  </script>

  <title>Admin Check In • ASPA</title>

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
  <div id="base_url" style="display: none"><?php echo base_url(); ?></div>
  <button class="button" id="back-btn" onClick="switchPage(1)">Back</button>

  <div class="page" id="home-page">
    <!-- <img src="assets/images/ASPA-admin.jpg"/>

    <div class="top">
      <h2>Sign In</h2>
    </div>
    <br> -->
    <center>
      <button class="button" id="email-btn" onClick="switchPage(2)">Email/UPI</button>
      <button class="button" id="qr-btn" onClick="switchPage(3)">QR Code</button>
    </center>
  </div>

  <div class="page" id="email-page">
    <center>
      <p>UPI:*</p>
      <input type="text" id="check-upi" name="login-upi">
      <br>
      <p>Email:*</p>
      <input type="text" id="check-email" name="login-email">
      <br><br><br>
      <button class="button" onClick="checkUser()">Check User</button>
      <br>
    </center>
  </div>


  <div class="page" id="qr-code-page">
    <center>
      <p>Scan QR Code</p>
    </center>
    <br>
  </div>

  <div class="page" id="message-page">

    <div id="HTTP-200-true">
      <p>Member has successfully paid!</p>
    </div>

    <div id="HTTP-200-false">
      <p>Member has registered but hasn't paid yet.</p>
      <button class="button">Manual Payment</button>
    </div>

    <div id="HTTP-409">
      <p>QR Code or Input Email has already been used.</p>
    </div>

    <div id="HTTP-404">
      <p>Member has not registered for the event.</p>
    </div>

  </div>


  <script type="text/javascript">

  const homePage = document.getElementById("home-page");
  const emailPage = document.getElementById("email-page");
  const qrCodePage = document.getElementById("qr-code-page");
  const messagePage = document.getElementById("message-page");

  // 1 - home page, 2 - email page, 3 - qr code page
  const pages = [homePage, emailPage, qrCodePage, messagePage];

  function switchPage(pageNumber) {
    for (const page of pages) {
      page.style.display = 'none';
    }

    pages[pageNumber - 1].style.display = 'block';
  }

  switchPage(1);

  const message1 = document.getElementById("HTTP-200-true"); //Registered and paid
  const message2 = document.getElementById("HTTP-200-false"); //Registered not paid
  const message3 = document.getElementById("HTTP-409"); //QR Code/Email Input Duplicate
  const message4 = document.getElementById("HTTP-404"); //Not registered for eventt

  // 1 = HTTP-200-true, 2 = HTTP-200-false, 3 = HTTP-409, 4 = HTTP-404
  const messages = [message1, message2, message3, message4];

  function checkUser() {
    
    const upi = document.getElementById('check-upi').value;
    const email = document.getElementById('check-email').value;

    console.log(upi, email);

    //check if user has registered/paid using ASPA-14

    $.ajax({
      cache: false,
      url: document.getElementById("base_url").innerHTML + "index.php/Admin/paymentStatus",
      method: "GET",
      data: { 
        "upi": upi, 
        "email": email
      },
      statusCode: {
        200: function (data) {
          console.log("Successfully check user.")
          console.log(JSON.parse(data).paymentMade);
        },
        404: function (data) {
          console.log("Error, user not found!");
        },
        409: function (data) {
          console.log("Error, conflicting user!");
        }
      }
	});

  }
  

  </script>

  
</body>

</html>