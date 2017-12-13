<?php 
	echo json_encode('sdsds');
	echo 'sdsds';
	echo utf8_decode('ssố');
	echo filter_var('số', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH);

	$arr1 = array(
           array('name' => 'luu', 'value' => 'foo'),
           array('name' => 'qwerty', 'value' => 'bar'),
           array('name' => 'uiop', 'value' => 'baz'),
        );

$arr2 = array(
           array('name' => 'zxcv', 'value' => 'stuff'),
           array('name' => 'asdfjkl;', 'value' => 'foo'),
           array('name' => '12345', 'value' => 'junk'),
           array('name' => 'uiop', 'value' => 'baz'),
        );

$intersect = array_uintersect($arr1, $arr2, 'compareDeepValue');
print_r($intersect);

function compareDeepValue($val1, $val2)
{
   return strcmp($val1['value'], $val2['value']);
}
?>
