<?php
// Set flag that this is a parent file
define( "_VALID_MOS", 1 );
/** security check */
/*$basePath 	= dirname( __FILE__ );
$path 		= $basePath . '/../../includes/auth.php';
require( $path ); 
global $my;
#defined( '_VALID_MOS' ) or die();
if (!$my->id) die(); // eto lishnee?
if (!(($my->usertype == "Super Administrator") || ($my->usertype == "Administrator"))) die(); // eto lishnee?
*/
// Add values to the graph
$graphValues=explode(',',$_GET['grids']);//array(11,190,245,240,55);
$kol_items = count($graphValues);

$imgWidth=250;
$imgHeight=25*$kol_items;
$imgLineWdth = 25;
$imgLineWdth_Offset = 3;
$max_value = intval($_GET['total']);
#$max_value = 0;
#for ($i=0;$i<$kol_items;$i++) {
#	if ($graphValues[$i] > $max_value) $max_value = $graphValues[$i];
#}
for ($i=0;$i<$kol_items;$i++) {
	if ($max_value != 0) { $graphValues[$i] = intval($graphValues[$i]*$imgWidth/$max_value); }
	if ($graphValues[$i] >= $imgWidth) $graphValues[$i] = $imgWidth - 1;
}
// Define .PNG image
header("Content-type: image/png");
header("Content-Disposition: inline; filename=grid.png");
// Create image and define colors
$image=imagecreate($imgWidth, $imgHeight);
$colorWhite=imagecolorallocate($image, 255, 255, 255);
$colorGrey=imagecolorallocate($image, 192, 192, 192);
$colorDarkBlue=imagecolorallocate($image, 104, 157, 228);
$colorLightBlue=imagecolorallocate($image, 184, 212, 250);
// Create border around image
imageline($image, 0, 0, 0, $imgHeight, $colorGrey);
imageline($image, 0, 0, $imgWidth, 0, $colorGrey);
imageline($image, $imgWidth - 1, 0, $imgWidth - 1, $imgHeight - 1, $colorGrey);
imageline($image, 0, $imgHeight - 1, $imgWidth - 1, $imgHeight - 1, $colorGrey);
// Create grid
for ($i=1; $i<11; $i++){
	$stw = ($i*25 > $imgWidth)?$imgWidth:$i*25;
	$sth = ($i*25 > $imgHeight)?$imgHeight:$i*25;
	imageline($image, $stw, 0, $stw, $imgHeight, $colorGrey);
	imageline($image, 0, $sth, $imgWidth, $sth, $colorGrey);
}
// Create bar charts
for ($i=0; $i<$kol_items; $i++){
	if ($graphValues[$i] > 0) {
		imagefilledrectangle($image, 0, (($i)*$imgLineWdth) + $imgLineWdth_Offset, $graphValues[$i], (($i+1)*$imgLineWdth) - $imgLineWdth_Offset, $colorDarkBlue);
		imagefilledrectangle($image, 1, (($i)*$imgLineWdth) + $imgLineWdth_Offset + 1, $graphValues[$i] - 1, (($i+1)*$imgLineWdth) - $imgLineWdth_Offset - 1, $colorLightBlue);
	}
}
// Output graph and clear image from memory
imagepng($image);
imagedestroy($image);
?>