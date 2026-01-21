<?php
ini_set( 'display_errors', '1' );
ini_set( 'date.timezone', 'US/Eastern' );
error_reporting( E_ALL );

require_once '../settings.ini.php';

// pixels per inch
define( 'PPI', 89 );

/**
 * Returns the Code125B barcode for the provided string
 * 
 * This function will return a string of digits from 1 to 4.  Each digit represents how wide the bar must be.
 * The first bar must be black and then the bars alternate between black and white, the last bar must be black.
 * @param string $string The string to convert into a barcode
 * @return string
 */
function get_barcode_widths( $string )
{
  // Code128 widths from https://en.wikipedia.org/wiki/Code_128
  $CODE_WIDTHS = [
    '0' => '212222', '1' => '222122', '2' => '222221', '3' => '121223', '4' => '121322', '5' => '131222',
    '6' => '122213', '7' => '122312', '8' => '132212', '9' => '221213', '10' => '221312', '11' => '231212',
    '12' => '112232', '13' => '122132', '14' => '122231', '15' => '113222', '16' => '123122', '17' => '123221',
    '18' => '223211', '19' => '221132', '20' => '221231', '21' => '213212', '22' => '223112', '23' => '312131',
    '24' => '311222', '25' => '321122', '26' => '321221', '27' => '312212', '28' => '322112', '29' => '322211',
    '30' => '212123', '31' => '212321', '32' => '232121', '33' => '111323', '34' => '131123', '35' => '131321',
    '36' => '112313', '37' => '132113', '38' => '132311', '39' => '211313', '40' => '231113', '41' => '231311',
    '42' => '112133', '43' => '112331', '44' => '132131', '45' => '113123', '46' => '113321', '47' => '133121',
    '48' => '313121', '49' => '211331', '50' => '231131', '51' => '213113', '52' => '213311', '53' => '213131',
    '54' => '311123', '55' => '311321', '56' => '331121', '57' => '312113', '58' => '312311', '59' => '332111',
    '60' => '314111', '61' => '221411', '62' => '431111', '63' => '111224', '64' => '111422', '65' => '121124',
    '66' => '121421', '67' => '141122', '68' => '141221', '69' => '112214', '70' => '112412', '71' => '122114',
    '72' => '122411', '73' => '142112', '74' => '142211', '75' => '241211', '76' => '221114', '77' => '413111',
    '78' => '241112', '79' => '134111', '80' => '111242', '81' => '121142', '82' => '121241', '83' => '114212',
    '84' => '124112', '85' => '124211', '86' => '411212', '87' => '421112', '88' => '421211', '89' => '212141',
    '90' => '214121', '91' => '412121', '92' => '111143', '93' => '111341', '94' => '131141', '95' => '114113',
    '96' => '114311', '97' => '411113', '98' => '411311', '99' => '113141', '100' => '114131', '101' => '311141',
    '102' => '411131', '103' => '211412', '104' => '211214', '105' => '211232', '106' => '233111'
  ];

  $start_code = 104;
  $stop_code = 106;

  $checksum = $start_code;
  $widths = $CODE_WIDTHS[$start_code];
  foreach( str_split( $string ) as $index => $char )
  {
    $code = ord( $char ) - 32;
    $checksum += ($index + 1) * $code;
    $widths .= $CODE_WIDTHS[$code];
  }
  $checksum = $checksum % 103;
  $widths .= $CODE_WIDTHS[$checksum];
  $widths .= $CODE_WIDTHS[$stop_code] . '2';
  return $widths;
}

function draw_label( $img, $code, $x, $y, $w, $h, $ppi )
{
  if( is_null( $code ) ) return;

  // convert x, y, width and height to pixels
  $x = intval( floor( $x * $ppi ) );
  $y = intval( floor( $y * $ppi ) );
  $w = intval( floor( $w * $ppi ) );
  $h = intval( floor( $h * $ppi ) );

  // dimensions and settings
  $padding = 4;
  $font = sprintf( '%s/../doc/%s', __DIR__, '/DejaVuSansMono.ttf' );
  $font_size = 10;
  $dims = imagettfbbox( $font_size, 0, $font, $code );
  $text_w = $dims[2] - $dims[0];
  $text_h = $dims[1] - $dims[7] + $padding;

  $f = 2/5;
  $barcode_w = intval( floor( $f * $w ) );
  $barcode_h = intval( floor( $f * $h ) ) - $text_h;
  $barcode_x = intval( floor( ((1-$f) * $w)/2 ) ) + $x;
  $barcode_y = intval( floor( ((1-$f) * $h)/2 ) ) + $y ;
  $text_x = intval( floor( ($barcode_w-$text_w)/2 ) ) + $barcode_x;
  $text_y = $barcode_y + $barcode_h + $text_h;

  $white = imagecolorallocate( $img, 255, 255, 255 );
  $black = imagecolorallocate( $img, 0, 0, 0 );

  // get the widths and print each one
  $widths = get_barcode_widths( strval( $code ) );
  $divisions = 0;
  foreach( str_split( $widths ) as $width ) $divisions += intval( $width );
  $division_w = $barcode_w / $divisions;
  $bar_x = $barcode_x;
  $total = strlen( $widths );
  foreach( str_split( $widths ) as $i => $width )
  {
    $width = intval( $width );
    // NOTE: for some reason the last bar is always 1 width too big, so we must reduce it by 1
    if( $i == $total-1 ) $width--;
    $bar_w = intval( floor( $width * $division_w ) );
    imagefilledrectangle(
      $img,
      $bar_x,
      $barcode_y,
      $bar_x + $bar_w,
      $barcode_y + $barcode_h,
      0 == $i%2 ? $black : $white
    );

    $bar_x += $bar_w;
  }

  // paint the outline
  imagerectangle( $img, $x, $y, $x + $w, $y + $h, $black );

  // paint text
  imagettftext( $img, $font_size, 0, $text_x, $text_y, $black, $font, $code );
}

// create a semaphore to make sure we don't duplicate any codes
$semaphore = sem_get( getmyinode() );
sem_acquire( $semaphore ) or die( 'Unable to acquire semaphore.' );

// Get the next 8 codes from the database
$db = new \mysqli( DB_SERV, DB_USER, DB_PASS, DB_NAME );
$code = array();
for( $i = 0; $i < 8; $i++ )
{
  $db->query( 'INSERT INTO interview SET id = NULL' )
    or die( 'Unable to get interview code from the database.' );
  $code[] = strval( $db->insert_id );
}
$db->close();

// width(pixels) = inch * pixel/inch
$width = floor( 7 * PPI );
// height(pixels) = inch * pixel/inch
$height = floor( 71/8 * PPI );
$image = imagecreatetruecolor( $width, $height );
$white = imagecolorallocate( $image, 255, 255, 255 );
imagefill( $image, 0, 0, $white );

imagealphablending( $image, true );
imagesavealpha( $image, true );

// now define all the labels going onto the image
$labelset = array(
  array( $code[0],   0,      0, 7/2, 9/4 ),
  array( $code[1], 7/2,      0, 7/2, 9/4 ),
  array( $code[2],   0,  36/16, 7/2, 9/4 ),
  array( $code[3], 7/2,  36/16, 7/2, 9/4 ),
  array( $code[4],   0,  72/16, 7/2, 9/4 ),
  array( $code[5], 7/2,  72/16, 7/2, 9/4 ),
  array( $code[6],   0, 108/16, 7/2, 9/4 ),
  array( $code[7], 7/2, 108/16, 7/2, 9/4 )
);

// now draw each label going onto the image
foreach( $labelset as $label )
{
  draw_label( $image, $label[0], $label[1], $label[2], $label[3], $label[4], PPI );
}

sem_release( $semaphore ) or die( 'Unable to release semaphore.' );

// and finally, draw the image
header( 'Content-Type: image/png' );
imagepng( $image );
imagedestroy( $image );
