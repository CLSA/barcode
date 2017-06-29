<?php
require_once 'common.php';

$code = array();
for( $i = 0; $i < 8; $i++ )
{
  $db->query( 'INSERT INTO interview SET id = NULL' )
    or die( 'Unable to get interview code from the database.' );
  $code[$i] = $db->insert_id;
}

// Create an image and fill the background in white

// millimeters per inch and inches per millimeter
$mpi = 25.4;
$ipm = 1 / $mpi;
$ppi = 89;

// width(pixels) = inch * pixel/inch
$width = round( 7 * $ppi );
// height(pixels) = inch * pixel/inch
$height = round( 71/8 * $ppi );
$image = imagecreatetruecolor( $width, $height );
$white = imagecolorallocate( $image, 255, 255, 255 );
imagealphablending( $image, true );
imagesavealpha( $image, true );

// now define all the labels going onto the image
$labelset = array(
  array( ' ', $code[0],   0 * $mpi,      0 * $mpi, 7/2 * $mpi, 9/4 * $mpi ),
  array( ' ', $code[1], 7/2 * $mpi,      0 * $mpi, 7/2 * $mpi, 9/4 * $mpi ),
  array( ' ', $code[2],   0 * $mpi,  36/16 * $mpi, 7/2 * $mpi, 9/4 * $mpi ),
  array( ' ', $code[3], 7/2 * $mpi,  36/16 * $mpi, 7/2 * $mpi, 9/4 * $mpi ),
  array( ' ', $code[4],   0 * $mpi,  71/16 * $mpi, 7/2 * $mpi, 9/4 * $mpi ),
  array( ' ', $code[5], 7/2 * $mpi,  71/16 * $mpi, 7/2 * $mpi, 9/4 * $mpi ),
  array( ' ', $code[6],   0 * $mpi, 107/16 * $mpi, 7/2 * $mpi, 9/4 * $mpi ),
  array( ' ', $code[7], 7/2 * $mpi, 107/16 * $mpi, 7/2 * $mpi, 9/4 * $mpi ) );

// now draw each label going onto the image
foreach( $labelset as $label )
  draw_label( $image, $label[0], $label[1],
              $label[2], $label[3], $label[4], $label[5],
              $ppi, '%d' );

sem_release( $semaphore ) or die( 'Unable to release semaphore.' );

// and finally, draw the image
header( 'Content-Type: image/png' );
imagepng( $image );
imagedestroy( $image );
