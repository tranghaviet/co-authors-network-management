<?php

    echo json_encode('sdsds');
    echo 'sdsds';
    echo utf8_decode('ssố');
    echo filter_var('số', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH);

    $arr1 = [
           ['name' => 'luu', 'value' => 'foo'],
           ['name' => 'qwerty', 'value' => 'bar'],
           ['name' => 'uiop', 'value' => 'baz'],
        ];

$arr2 = [
           ['name' => 'zxcv', 'value' => 'stuff'],
           ['name' => 'asdfjkl;', 'value' => 'foo'],
           ['name' => '12345', 'value' => 'junk'],
           ['name' => 'uiop', 'value' => 'baz'],
        ];

$intersect = array_uintersect($arr1, $arr2, 'compareDeepValue');
print_r($intersect);

function compareDeepValue($val1, $val2)
{
    return strcmp($val1['value'], $val2['value']);
}
