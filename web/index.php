<?php
require_once( '../settings.ini.php' );
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CLSA-&Eacute;LCV <?php print TITLE; ?></title>
  <link rel="stylesheet" href="https://common.clsa-elcv.ca/css/index.css">
</head>
<body>
  <div class="header">
    <h1 class="title">CLSA-&Eacute;LCV <?php print TITLE; ?></h1>
  </div>
  <div class="view">
    <span class="help">
      Please click the button below to generate a new set of interview barcodes.<br/>
      Note that barcodes can never be used more than once and it doesn't matter if some are unused.<br/>
      You can reload the barcode page to generate more barcodes.
    </span>
    <h2><a href="interview.php" style="width:16em;">Generate Interview Barcodes</a></h2>
  </div>
  <div class="gradient-footer"></div>
</body>

</html>
