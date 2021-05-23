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

  <!-- QR Code Scanning Library -->
  <script type="text/javascript" src="assets/lib/qr-scanner.umd.min.js"></script>
  <script type="text/javascript" src="assets/lib/qr-scanner-worker.min.js"></script>
</head>

<body>
  <div id="base_url" style="display: none"><?php echo base_url(); ?></div>
  <div class="div-back div-page-page3"><a id="btn-back-page3" onClick="switchPage(1)" class="btn-back w-button">← Back</a></div>

  <div class="page" id="home-page">
    <!-- <img src="assets/images/ASPA-admin.jpg"/> -->
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
      <button class="button" onClick="checkMemberPaymentStatus()">Check Status</button>
      <br>
    </center>
  </div>


  <div class="page" id="qr-code-page">
    <div class="flex-container">
      <div class="flex-filler">
      </div>
      <center>
        <div class="video-container" id="reader">
          <video id="preview" width="100px" height="100px"></video>
        </div>
        <p class="p" id="log"></p>
      </center>
      <div class="flex-filler"></div>
    </div>
  </div>

  <div class="page" id="message-page">

    <div id="HTTP-200-true">
      <p>Member has paid for the event. All good!</p>
    </div>

    <div id="HTTP-200-false">
      <p>Member has registered but hasn't paid yet.</p>
      <button class="button" onClick="markUserAsPaid()">Manual Payment</button>
    </div>

    <div id="HTTP-409">
      <p>QR Code or Input Email has already been used.</p>
    </div>

    <div id="HTTP-404">
      <p>Member has not registered for the event.</p>
    </div>

  </div>

  <script type="text/javascript">
    const ADMIN_ENDPOINT = document.getElementById("base_url").innerHTML + "index.php/Admin";

    const homePage = document.getElementById("home-page");
    const emailPage = document.getElementById("email-page");
    const qrCodePage = document.getElementById("qr-code-page");
    const messagePage = document.getElementById("message-page");
    const pages = [homePage, emailPage, qrCodePage, messagePage];

    // Messages to display on the message page
    const message1 = document.getElementById("HTTP-200-true"); // Registered and paid
    const message2 = document.getElementById("HTTP-200-false"); // Registered not paid
    const message3 = document.getElementById("HTTP-409"); // QR Code/Email Input Duplicate
    const message4 = document.getElementById("HTTP-404"); // Not registered for event
    // 1 = HTTP-200-true, 2 = HTTP-200-false, 3 = HTTP-409, 4 = HTTP-404
    const messages = [message1, message2, message3, message4];

    // Input boxes for the input page
    const emailInput = document.getElementById("check-email");
    const upiInput = document.getElementById("check-upi");

    // Status text display for the QR code page
    const logField = document.getElementById("log");

    let memberEmail = "";
    let memberUpi = "";

    /**
     * Switches page to the specified page number.
     * 1 – Home page
     * 2 – Email / UPI input page
     * 3 - QR code page
     * 4 - Message response page
     */
    function switchPage(pageNumber) {
      // Hide all pages
      for (const page of pages) {
        page.style.display = 'none';
      }

      // Unhide the specified page
      pages[pageNumber - 1].style.display = 'block';

      // If we go back to home, reset all values to empty
      if (pageNumber == 1) {
        memberEmail = "";
        memberUpi = "";
        emailInput.value = "";
        upiInput.value = "";
      }
    }

    /**
     * Marks the given user (by the current global email/upi) as paid.
     */
    function markUserAsPaid() {
      $.ajax({
        cache: false,
        url: ADMIN_ENDPOINT + "/markAsPaid",
        method: "GET",
        data: {
          "email": memberEmail,
          "upi": memberUpi
        },
        error: function (errorBody) {
          console.log(`Failed to mark ${memberEmail || memberUpi} as paid: ${errorBody}`)
        }
      });

      switchPage(1);
    }

    /**
     * Loads the relevant response message.
     * 1 - Registered and paid
     * 2 - Registered and NOT paid
     * 3 - Duplicate input (i.e. paid and attended)
     * 4 - Not registered for the event yet
     */
    function showResponseMessage(messageIndex) {
      // Clear all messages
      for (message of messages) {
        message.style.display = 'none';
      }

      // Switch to messages page and show the correct response message
      switchPage(4);
      messages[messageIndex - 1].style.display = 'block';
    }

    /**
     * Checks if the member has paid / other attributes.
     * Based on this, will call the relevant command to display response message.
     */
    function checkMemberPaymentStatus(email = null) {
      // Set our global member fields (for use in the rest of the application)
      memberEmail = email || emailInput.value;
      memberUpi = upiInput.value;

      console.log(memberEmail, memberUpi);

      $.ajax({
        cache: false,
        url: ADMIN_ENDPOINT + "/paymentStatus",
        method: "GET",
        data: {
          "email": memberEmail,
          "upi": memberUpi
        },
        statusCode: {
          200: function (data) {
            const paymentMade = JSON.parse(data).paymentMade;
            console.log("Successfully checked for the user: " + (paymentMade ? "Paid member" : "Unpaid"));
            showResponseMessage(paymentMade ? 1 : 2);
          },
          404: function (data) {
            console.log("Error, user not found!");
            showResponseMessage(4);
          },
          409: function (data) {
            console.log("Error, conflicting user!");
            showResponseMessage(3);
          }
        }
  	  });
    }

    // Set the page height by window for CSS and show home page by default
    $(".page").css("height", `${window.innerHeight}px`);
    switchPage(1);
  </script>

  <script type="text/javascript">
    QrScanner.WORKER_PATH = 'assets/lib/qr-scanner-worker.min.js';

    function onScanResult(result) {
      try {
        const decoded = JSON.parse(result);
        logField.innerHTML = "Email: " + decoded.email;

        // This check prevents multiple calls to our server from being made
        if (memberEmail !== decoded.email) {
          memberEmail = decoded.email;
          checkMemberPaymentStatus(decoded.email);
        }
      } catch (e) {
        console.log(e);
        logField.innerHTML = "QR code not correct";
      }
    }

    const videoElem = document.getElementById("preview");
    const qrScanner = new QrScanner(videoElem, onScanResult);
    qrScanner.start();
  </script>


</body>

</html>