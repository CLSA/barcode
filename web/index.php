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
    <span class="help">Please select which barcode template you wish to print:</span>
    <h2>
      <a href="interview.php">Interviews</a>
      <a href="vial.php">Aliquoits</a>
      <a href="box.php">Boxes</a>
    </h2>
  </div>
  <div class="gradient-footer"></div>
</body>

</html>
