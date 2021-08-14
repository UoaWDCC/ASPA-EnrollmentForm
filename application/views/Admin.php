<!DOCTYPE html>

<html>

<head>
  <base href="<?php echo base_url(); ?>" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


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

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" type="text/css">

  <link href="assets/css/admin.css" rel="stylesheet" type="text/css">

  <!-- QR Code Scanning Library -->
  <script type="text/javascript" src="assets/lib/qr-scanner.umd.min.js"></script>
  <script type="text/javascript" src="assets/lib/qr-scanner-worker.min.js"></script>
</head>

<body>
  <div id="base_url" style="display: none"><?php echo base_url(); ?></div>
  <div class="div-back div-page-page3"><a id="btn-back-page3" onClick="switchPage(1)" class="btn-back w-button">← Back</a></div>

  <div class="page" id="home-page">
    <div class="flex-container">
      <!-- <img src="assets/images/ASPA-admin.jpg"/> -->
      <div class="flex-filler"></div>
      <div>
        <button class="button" id="email-btn" onClick="switchPage(2)"><i class="far fa-keyboard"></i></button>
        <button class="button" id="qr-btn" onClick="switchPage(3)"><i class="fas fa-qrcode"></i></button>
      </div>
      <div class="flex-filler"></div>
    </div>
  </div>

  <div class="page" id="email-page">
    <div class="flex-container">
      <div class="flex-filler"></div>
      <div class="content">
        <input type="text" id="check-email" name="login-email" placeholder="email (priority)">
        <p>OR ...</p>
        <input type="text" id="check-upi" name="login-upi" placeholder="upi">

        <button class="button" onClick="checkMemberPaymentStatus()">GO</button>
      </div>
      <div class="flex-filler"></div>
    </div>
  </div>


  <div class="page" id="qr-code-page">
    <div class="flex-container">
      <div class="flex-filler"></div>
      <div class='content'>
        <div class="video-container" id="reader">
          <video id="preview" width="100px" height="100px"></video>
        </div>
        <p class="p" id="log"></p>
      </div>
      <div class="flex-filler"></div>
    </div>
  </div>

  <div class="page" id="message-page">
    <div id="message-page-container">
      <h4>ID:
        <span id="member-identifier-span">...</span>
        <i id="loading-spinner" class="fa fa-circle-o-notch fa-spin" style="font-size: 1em;"></i>
      </h4>

      <div id="message1">
        <p>Member registered and paid online.</p>
      </div>
      <div id="message2">
        <p>Member registered but not paid.</p>
        <button class="button" onClick="markUserAsPaid()">Manual Payment</button>
      </div>
      <div id="message3">
        <p>Member has already checked in! (QR code scanned twice?)</p>
      </div>
      <div id="message4">
        <p>Member has not registered for the event.</p>
      </div>
    </div>
  </div>

  <!-- This script block sets the qrscanner up -->
  <script type="text/javascript">
    QrScanner.WORKER_PATH = 'assets/lib/qr-scanner-worker.min.js';

    function logTextError() {
      logField.innerHTML = "Error: Wrong QR code format";
      setTimeout(() => {
        logField.innerHTML = ""
      }, 8000);
    }

    function onScanResult(result) {
      try {
        const decoded = JSON.parse(result);

        if (!decoded.email) {
          logTextError();
        }

        // This check prevents multiple calls to our server from being made
        if (memberEmail !== decoded.email) {
          memberEmail = decoded.email;
          checkMemberPaymentStatus(decoded.email);
        }
      } catch (e) {
        logTextError();
      }
    }

    const videoElem = document.getElementById("preview");
    const qrScanner = new QrScanner(videoElem, onScanResult);
  </script>

  <script type="text/javascript">
    const ADMIN_ENDPOINT = document.getElementById("base_url").innerHTML + "index.php/Admin";

    const homePage = document.getElementById("home-page");
    const emailPage = document.getElementById("email-page");
    const qrCodePage = document.getElementById("qr-code-page");
    const messagePage = document.getElementById("message-page");
    const loadingSpinner = document.getElementById("loading-spinner");

    // 1 - home page, 2 - email page, 3 - qr code page, 4 - message page
    const pages = [homePage, emailPage, qrCodePage, messagePage];

    // Messages to display on the message page
    const message1 = document.getElementById("message1"); // Registered and paid
    const message2 = document.getElementById("message2"); // Registered not paid
    const message3 = document.getElementById("message3"); // QR Code/Email Input Duplicate
    const message4 = document.getElementById("message4"); // Not registered for event

    // 1 - 200 true (registered and paid), 2 - 200 false (registered, not paid), 3 - 409 (duplicate entry), 4 - 404 (not registered)
    const messages = [message1, message2, message3, message4];

    // Input boxes for the input page
    const emailInput = document.getElementById("check-email");
    const upiInput = document.getElementById("check-upi");

    // Status text display for the QR code page
    const logField = document.getElementById("log");
    const memberIdentifierElem = document.getElementById("member-identifier-span");

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

      loadingSpinner.style.visibility = 'visible';

      // Unhide the specified page
      pages[pageNumber - 1].style.display = 'block';

      // If we go back to home, reset all values to empty
      if (pageNumber == 1) {
        memberEmail = "";
        memberUpi = "";
        emailInput.value = "";
        upiInput.value = "";

        memberIdentifierElem.innerHTML = "-";

        memberIdentifierElem.style.backgroundColor = '#d2d2d2';
        memberIdentifierElem.style.color = 'inherit';
      }

      if (pageNumber === 1) {
        qrScanner.start();
      } else if (pageNumber !== 3) {
        qrScanner.stop();
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
        error: function(errorBody) {
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
      // before showing response hide the loading spinner
      loadingSpinner.style.visibility = 'hidden';

      switch (messageIndex) {
        case 1:
          memberIdentifierElem.style.backgroundColor = '#509350';
          memberIdentifierElem.style.color = '#ffffff';
          break;
        case 2:
          memberIdentifierElem.style.backgroundColor = '#eeb62c';
          memberIdentifierElem.style.color = '#040403';
          break;
        case 3:
          memberIdentifierElem.style.backgroundColor = '#a10c5d';
          memberIdentifierElem.style.color = '#ffffff';
          break;
        default:
          memberIdentifierElem.style.backgroundColor = '#d2d2d2';
          memberIdentifierElem.style.color = 'inherit';
          break;
      }

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

      // If neither input is set, do nothing
      if (!memberEmail && !memberUpi) {
        return;
      }

      // Set the identifier
      memberIdentifierElem.innerHTML = memberEmail ? memberEmail : memberUpi;



      console.log(memberEmail, memberUpi);

      // Hide all current messages and switch to 4 (messages page)
      for (const message of messages) {
        message.style.display = 'none';
      }
      switchPage(4);

      $.ajax({
        cache: false,
        url: ADMIN_ENDPOINT + "/paymentStatus",
        method: "GET",
        data: {
          "email": memberEmail,
          "upi": memberUpi
        },
        statusCode: {
          200: function(data) {
            const paymentMade = JSON.parse(data).paymentMade;
            console.log("Successfully checked for the user: " + (paymentMade ? "Paid member" : "Unpaid"));
            showResponseMessage(paymentMade ? 1 : 2);
          },
          404: function(data) {
            console.log("Error, user not found!");
            showResponseMessage(4);
          },
          409: function(data) {
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
</body>

</html>