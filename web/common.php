<?php
ini_set( 'display_errors', '1' );
error_reporting( E_ALL );

require_once 'Image/Barcode2.php';
require_once '../settings.ini.php';

$semaphore = sem_get( getmyinode() );
sem_acquire( $semaphore ) or die( 'Unable to acquire semaphore.' );

ini_set( 'date.timezone', 'US/Eastern' );

// Get the next code from the database
$db = new \mysqli( DB_SERV, DB_USER, DB_PASS, DB_NAME );

function draw_label( $img, $title, $code, $x, $y, $width, $height, $ppi, $printf )
{
  // millimeters per inch and inches per millimeter
  $mpi = 25.4;
  $ipm = 1 / $mpi;

  // convert x, y, width and height to pixels
  $x = round( $x * $ipm * $ppi );
  $y = round( $y * $ipm * $ppi );
  $width = round( $width * $ipm * $ppi );
  $height = round( $height * $ipm * $ppi );
  $f = 1/12;
  $f_width = round( $f * $width );
  $f_height = round( $f * $height );
  $f1_width = round( (1+$f) * $width );
  $f1_height = round( (1+$f) * $height );
  $white = imagecolorallocate( $img, 255, 255, 255 );
  $black = imagecolorallocate( $img, 0, 0, 0 );

  if( is_null( $code ) )
  {
    imagefilledrectangle( $img,
      $x - $f_width, $y - $f_height,
      $x + $f1_width, $y + $f1_height,
      $white );
  }
  else
  {
    $barcode = Image_Barcode2::draw( sprintf( $printf, $code ), 'code128', 'png', false );
    imagealphablending( $barcode, true );
    imagesavealpha( $barcode, true );

    // dimensions and settings
    $font = '/usr/share/fonts/truetype/dejavu/DejaVuSansMono.ttf';
    $font_size = 8;
    $padding = 4;
    $dims = imagettfbbox( $font_size, 0, $font, $title );
    $tw = $dims[2] - $dims[0];
    $th = $dims[1] - $dims[7] + $padding;
    $bw = imagesx( $barcode );
    $bh = imagesy( $barcode );
    $tx = round( $x + ( $width - $tw ) / 2 );
    $ty = round( $y + ( $height - $th - $bh ) / 2 - $padding );
    $bx = round( $x + ( $width - $bw ) / 2 );
    $by = round( $y + ( $height - $th - $bh ) / 2 + $th );

    // paint barcode
    imagefill( $barcode, 0, 0, $white );
    imagecopy(
      $img,
      $barcode,
      $bx, $by,
      0, 0,
      $width - 1, $height - 1
    );

    // fill in black background
    imagefilledrectangle(
      $img,
      $x - $f_width, $y - $f_height,
      $x + $f1_width, $by,
      $white );
    imagefilledrectangle(
      $img,
      $x - $f_width, $by + $bh,
      $x + $f1_width, $y + $f1_height,
      $white
    );
    imagefilledrectangle(
      $img,
      $x - $f_width, $y - $f_height,
      $bx, $y + $f1_height,
      $white
    );
    imagefilledrectangle(
      $img,
      $bx + $bw, $y - $f_height,
      $x + $f1_width, $y + $f1_height,
      $white
    );
    
    // For debugging purposes
    imagerectangle(
      $img,
      $x, $y,
      $x + $width - 1, $y + $height - 1,
      $black
    );

    // paint text
    imagettftext( $img, $font_size, 0, $tx, $ty + $th, $black, $font, $title );
  }
}
