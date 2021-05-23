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
    <div id="message1">
      <p>Member has successfully paid!</p>
    </div>
    <div id="message2">
      <p>Member has registered but hasn't paid yet.</p>
      <button class="button">Manual Payment</button>
    </div>
    <div id="message3">
      <p>QR Code or Input Email has already been used.</p>
    </div>
    <div id="message4">
      <p>Member has not registered for the event.</p>
    </div>
    <!--<button class="button" onClick="window.location.reload();">Check New User</button> this button refreshes the page for new input--> 
  </div>


  <script type="text/javascript">

  const homePage = document.getElementById("home-page");
  const emailPage = document.getElementById("email-page");
  const qrCodePage = document.getElementById("qr-code-page");
  const messagePage = document.getElementById("message-page");

  // 1 - home page, 2 - email page, 3 - qr code page, 4 - message page
  const pages = [homePage, emailPage, qrCodePage, messagePage];

  function switchPage(pageNumber) {
    for (const page of pages) {
      page.style.display = 'none';
    }

    pages[pageNumber - 1].style.display = 'block';
  }

  switchPage(1);

  // 1 - 200 true (registered and paid), 2 - 200 false (registered, not paid), 3 - 409 (duplicate entry), 4 - 404 (not registered)
  const message1 = document.getElementById("message1");
  const message2 = document.getElementById("message2");
  const message3 = document.getElementById("message3");
  const message4 = document.getElementById("message4");

  const messages = [message1, message2, message3, message4];

  function checkUser() {
    
    const upi = document.getElementById('check-upi').value;
    const email = document.getElementById('check-email').value;

    for (const message of messages) {
      message.style.display = 'none';
    }

    switchPage(4);

    //check if user has registered/paid
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
          paid = JSON.parse(data).paymentMade;
          if (paid == true) {
            message1.style.display = 'block';
          } else {
            message2.style.display = 'block';
          }
        },
        404: function (data) {
          console.log("Error, user not found!");
          message3.style.display = 'block';
        },
        409: function (data) {
          console.log("Error, conflicting user!");
          message4.style.display = 'block';
        }
      }
	});
  }
  </script>
</body>
</html>