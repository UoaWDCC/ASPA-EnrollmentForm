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

  <title>Form disabled • <?php echo $orgName?></title>

  <meta charset="utf-8">
  <meta name="description" content="Form Disabled">
  <meta name='viewport' content='initial-scale=1.0' />
  <meta name="author" content="UoA Web Development & Consulting Club members">

  <link href="assets/css/normalize.css?random=<?php echo uniqid(); ?>" rel="stylesheet" type="text/css">
  <link href="assets/css/webflow.css?random=<?php echo uniqid(); ?>" rel="stylesheet" type="text/css">
  <link href="assets/css/aspa.webflow.css?random=<?php echo uniqid(); ?>" rel="stylesheet" type="text/css">
  <link href='assets/css/formDisabled.css?random=<?php echo uniqid(); ?>' rel='stylesheet' type='text/css' />
  <link href="assets/images/favicon.png?random=<?php echo uniqid(); ?>" rel="icon" type="image/png">
</head>

<body>
  <div class="centre-page">
    <img src="assets/images/ASPA_logo.png" />
    <h1>Sorry about that.</h1>
    <p>Sign up is not open yet. Stay posted to <?php echo $orgName ?> social media pages to stay up to date on the latest events!</p>
    <p>Contact
      <a href="mailto:uoapool@gmail.com"><?php echo $orgSupportEmail ?></a>
      if you have any questions.
    </p>
  </div>
</body>

</html>
