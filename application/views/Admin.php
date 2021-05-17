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

  <link href="assets/css/admin.css" rel="stylesheet" type="text/css">

</head>

<body>
  <div class="admin-page">
    <img src="assets/images/ASPA-admin.jpg"/>

    <div class="top">
      <h2>Sign In</h2>
    </div>
    <br>

    <div class="email-button">
      <button class="button">Email/UPI</button>
    </div>
    <br>


    <button class="button">QR Code</button>
    <br>
    <button class="button">Back</button>
    
  </div>

  <div class="email-page">
    <p>UPI:*</p>
    <input type="text" id="login-upi" name="login-upi">
    <p>Email:*</p>
    <input type="text" id="login-email" name="login-email">
    <br>
    <button class="button">Log In</button>
    <br>
    <button class="button">Back</button> 
  </div>


  <div class="QR-code-page">
    <p>Scan QR Code</p>
    <br>
    <button class="button">Back</button>
</div>


  
</body>

</html>