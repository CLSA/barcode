<?php
require_once 'common.php';

$codes = array();
for( $i = 0; $i < 32; $i++ )
{
  $db->query( 'INSERT INTO box SET id = NULL' )
    or die( 'Unable to get box code from the database.' );
  $codes[] = $db->insert_id;
}

// millimeters per inch and inches per millimeter
$mpi = 25.4;
$ipm = 1 / $mpi;
$ppi = 137.5;

// width(pixels) = mm * inch/mm * pixel/inch
$width = round( 193 * $ipm * $ppi );
// height(pixels) = mm * inch/mm * pixel/inch
$height = round( 254 * $ipm * $ppi );
$image = imagecreatetruecolor( $width, $height );
$white = imagecolorallocate( $image, 255, 255, 255 );
imagealphablending( $image, true );
imagesavealpha( $image, true );

// now define all the labels going onto the image
$labelset1 = array(
  array( 'Box #'.( $codes[0] - 100000 ), $codes[0], 0, 0, 44.5, 32 ),
  array( 'Box #'.( $codes[1] - 100000 ), $codes[1], 49, 0, 44.5, 32 ),
  array( 'Box #'.( $codes[2] - 100000 ), $codes[2], 99.5, 0, 44.5, 32 ),
  array( 'Box #'.( $codes[3] - 100000 ), $codes[3], 148, 0, 44.5, 32 ),
  array( 'Box #'.( $codes[4] - 100000 ), $codes[4], 0, 31.5, 44.5, 32 ),
  array( 'Box #'.( $codes[5] - 100000 ), $codes[5], 49, 31.5, 44.5, 32 ),
  array( 'Box #'.( $codes[6] - 100000 ), $codes[6], 99.5, 31.5, 44.5, 32 ),
  array( 'Box #'.( $codes[7] - 100000 ), $codes[7], 148, 31.5, 44.5, 32 ),
  array( 'Box #'.( $codes[8] - 100000 ), $codes[8], 0, 63, 44.5, 32 ),
  array( 'Box #'.( $codes[9] - 100000 ), $codes[9], 49, 63, 44.5, 32 ),
  array( 'Box #'.( $codes[10] - 100000 ), $codes[10], 99.5, 63, 44.5, 32 ),
  array( 'Box #'.( $codes[11] - 100000 ), $codes[11], 148, 63, 44.5, 32 ),
  array( 'Box #'.( $codes[12] - 100000 ), $codes[12], 0, 95, 44.5, 32 ),
  array( 'Box #'.( $codes[13] - 100000 ), $codes[13], 49, 95, 44.5, 32 ),
  array( 'Box #'.( $codes[14] - 100000 ), $codes[14], 99.5, 95, 44.5, 32 ),
  array( 'Box #'.( $codes[15] - 100000 ), $codes[15], 148, 95, 44.5, 32 ),
  array( 'Box #'.( $codes[16] - 100000 ), $codes[16], 0, 127, 44.5, 32 ),
  array( 'Box #'.( $codes[17] - 100000 ), $codes[17], 49, 127, 44.5, 32 ),
  array( 'Box #'.( $codes[18] - 100000 ), $codes[18], 99.5, 127, 44.5, 32 ),
  array( 'Box #'.( $codes[19] - 100000 ), $codes[19], 148, 127, 44.5, 32 ),
  array( 'Box #'.( $codes[20] - 100000 ), $codes[20], 0, 158.5, 44.5, 32 ),
  array( 'Box #'.( $codes[21] - 100000 ), $codes[21], 49, 158.5, 44.5, 32 ),
  array( 'Box #'.( $codes[22] - 100000 ), $codes[22], 99.5, 158.5, 44.5, 32 ),
  array( 'Box #'.( $codes[23] - 100000 ), $codes[23], 148, 158.5, 44.5, 32 ),
  array( 'Box #'.( $codes[24] - 100000 ), $codes[24], 0, 190, 44.5, 32 ),
  array( 'Box #'.( $codes[25] - 100000 ), $codes[25], 49, 190, 44.5, 32 ),
  array( 'Box #'.( $codes[26] - 100000 ), $codes[26], 99.5, 190, 44.5, 32 ),
  array( 'Box #'.( $codes[27] - 100000 ), $codes[27], 148, 190, 44.5, 32 ),
  array( 'Box #'.( $codes[28] - 100000 ), $codes[28], 0, 222, 44.5, 32 ),
  array( 'Box #'.( $codes[29] - 100000 ), $codes[29], 49, 222, 44.5, 32 ),
  array( 'Box #'.( $codes[30] - 100000 ), $codes[30], 99.5, 222, 44.5, 32 ),
  array( 'Box #'.( $codes[31] - 100000 ), $codes[31], 148, 222, 44.5, 32 ) );

// now draw each label going onto the image
foreach( $labelset1 as $label )
  draw_label( $image, $label[0], $label[1],
              $label[2], $label[3], $label[4], $label[5],
              $ppi, '%d' );

sem_release( $semaphore ) or die( 'Unable to release semaphore.' );

// and finally, draw the image
header( 'Content-Type: image/png' );
imagepng( $image );
imagedestroy( $image );
