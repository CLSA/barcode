<?php
require_once 'common.php';

$db->query( 'INSERT INTO vial SET id = NULL' )
  or die( 'Unable to get vial code from the database.' );
$base_code1 = $db->insert_id;

$db->query( 'INSERT INTO vial SET id = NULL' )
  or die( 'Unable to get vial code from the database.' );
$base_code2 = $db->insert_id;

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
$code = 1;
$labelset1 = array(
  array( '', NULL, 0, 0, 44.5, 32 ),
  array( '2.7 mL Light Blue PPP', $code++, 49, 0, 44.5, 32 ),
  array( '2.7 mL Light Blue Plasma', $code++, 99.5, 0, 44.5, 32 ),
  array( '10 mL Red', $code++, 148, 0, 44.5, 32 ),
  array( '6 mL Green', $code++, 0, 31.5, 44.5, 32 ),
  array( '3 mL Lav CBC', $code++, 49, 31.5, 44.5, 32 ),
  array( '6 mL Lav #1', $code++, 99.5, 31.5, 44.5, 32 ),
  array( '6 mL Lav #2', $code++, 148, 31.5, 44.5, 32 ),
  array( '6 mL Lav #3', $code++, 0, 63, 44.5, 32 ),
  array( '3 mL Discard', $code++, 49, 63, 44.5, 32 ),
  array( '3 mL Yellow', $code++, 99.5, 63, 44.5, 32 ),
  array( '8 mL Blue/Black', $code++, 148, 63, 44.5, 32 ),
  array( 'Urine', $code++, 0, 95, 44.5, 32 ),
  array( 'Buffy Coat', $code++, 49, 95, 44.5, 32 ),
  array( 'CPT Conical Centrifuge', $code++, 99.5, 95, 44.5, 32 ),
  array( '2nd SPIN PPP Centrifuge', $code++, 148, 95, 44.5, 32 ) );

$code = 1;
$labelset2 = array(
  array( '', NULL, 0, 127, 44.5, 32 ),
  array( '2.7 mL Light Blue PPP', $code++, 49, 127, 44.5, 32 ),
  array( '2.7 mL Light Blue Plasma', $code++, 99.5, 127, 44.5, 32 ),
  array( '10 mL Red', $code++, 148, 127, 44.5, 32 ),
  array( '6 mL Green', $code++, 0, 158.5, 44.5, 32 ),
  array( '3 mL Lav CBC', $code++, 49, 158.5, 44.5, 32 ),
  array( '6 mL Lav #1', $code++, 99.5, 158.5, 44.5, 32 ),
  array( '6 mL Lav #2', $code++, 148, 158.5, 44.5, 32 ),
  array( '6 mL Lav #3', $code++, 0, 190, 44.5, 32 ),
  array( '3 mL Discard', $code++, 49, 190, 44.5, 32 ),
  array( '3 mL Yellow', $code++, 99.5, 190, 44.5, 32 ),
  array( '8 mL Blue/Black', $code++, 148, 190, 44.5, 32 ),
  array( 'Urine', $code++, 0, 222, 44.5, 32 ),
  array( 'Buffy Coat', $code++, 49, 222, 44.5, 32 ),
  array( 'CPT Conical Centrifuge', $code++, 99.5, 222, 44.5, 32 ),
  array( '2nd SPIN PPP Centrifuge', $code++, 148, 222, 44.5, 32 ) );

// now draw each label going onto the image
foreach( $labelset1 as $label )
  draw_label( $image, $label[0], is_null( $label[1] ) ? NULL : $base_code1 + $label[1] / 100,
              $label[2], $label[3], $label[4], $label[5],
              $ppi, '%0.2f' );

foreach( $labelset2 as $label )
  draw_label( $image, $label[0], is_null( $label[1] ) ? NULL : $base_code2 + $label[1] / 100,
              $label[2], $label[3], $label[4], $label[5],
              $ppi, '%0.2f' );

sem_release( $semaphore ) or die( 'Unable to release semaphore.' );

// and finally, draw the image
header( 'Content-Type: image/png' );
imagepng( $image );
imagedestroy( $image );
