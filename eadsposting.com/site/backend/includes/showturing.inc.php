<?
if (!defined('version')){
exit;}
$string=strval($string);
header ("Content-Type: image/jpeg");
if (function_exists('imagetypes') && gdoff!='YES'){
if (imagetypes() & IMG_JPG){
if (is_dir(scripts_dir.'fonts'))
$fontdir=scripts_dir.'fonts/';
else
$fontdir='../fonts/'; 
if (substr(turingfont,-4)=='.ttf' && file_exists($fontdir.turingfont) && function_exists('imagettftext'))
$font='ttf';
if (substr(turingfont,-4)=='.gdf') 
$font=@imageloadfont($fontdir.turingfont);

if (!$font || turingfont=='System-5')
  $font=5;
if (turingfont=='System-4')
  $font=4;
if (turingfont=='System-3')
$font=3;
if (turingfont=='System-2')
$font=2;
if (turingfont=='System-1')
$font=1;
if ($font!='ttf'){ 
$width=imagefontwidth($font);
$height=imagefontheight($font);}
else {
$angle=mt_rand(-15,15);
$bbox = imagettfbbox(20,$angle,$fontdir.turingfont,$string{0});
       $bbox0['width'] = $bbox[0] + $bbox[4];
       $bbox0['height'] = $bbox[1] - $bbox[5];
$bbox = imagettfbbox(20,$angle,$fontdir.turingfont,$string{1});
       $bbox1['width'] = $bbox[0] + $bbox[4];
       $bbox1['height'] = $bbox[1] - $bbox[5];
$width = $bbox0['width']+5; 
if ($bbox1['width']>$bbox0['width'])
$width = $bbox1['width']+5;
$height = $bbox0['height'];
if ($bbox1['height']>$bbox0['height'])
$height = $bbox1['height'];
}

$fullsize=round($width*2)+3;
if ($height>$fullsize)
$fullsize=$height+2;

if ($font=='ttf')
$fullsize+=5;
if (function_exists('ImageCreatetruecolor'))
$im = ImageCreatetruecolor($fullsize,$fullsize);
else
$im = ImageCreate($fullsize,$fullsize);
if ( function_exists('imageantialias'))
@imageantialias($im,1);
$back = ImageColorAllocate($im, 255, 255, 255);
ImageFill($im, 0, 0, $back);
for($i=0;$i<strlen($string);$i++)
{
       $color[$i]=ImageColorAllocate($im, mt_rand(0,turingcolors),mt_rand(0,turingcolors),mt_rand(0,turingcolors));
       if ($i==0){
       $x = mt_rand(1,$fullsize-round($width*2));
       }
       else {$x=mt_rand($x+$width+1,$fullsize-$width-1);}
        if ($font=='ttf')
        $y=  mt_rand($height+2,$fullsize-5);
        else
        $y = mt_rand(1,$fullsize-$height-1);
if ($font!='ttf')
       Imagestring($im, $font , $x, $y, $string{$i}, $color[$i]);
 else
       imagettftext ( $im, 20, $angle, $x,$y , $color[$i], $fontdir.turingfont, $string{$i});
}
if (function_exists('ImageCreatetruecolor'))
$newim = ImageCreatetruecolor(60,60);
else 
$newim = ImageCreate(60,60);
if (function_exists('imagecopyresampled'))
imagecopyresampled ($newim,$im,0,0,0,0,60,60,$fullsize,$fullsize);
else 
imagecopyresized ($newim,$im,0,0,0,0,60,60,$fullsize,$fullsize);
ImageDestroy($im);
if ($font!='ttf' && function_exists('imagerotate')){
if (function_exists('ImageCreatetruecolor'))
$rotated = ImageCreatetruecolor(60,60);
else
$rotated = ImageCreate(60,60);
if ( function_exists('imageantialias'))
@imageantialias($rotated,1);
$rotated=imagerotate($newim,mt_rand(-15,15),$back);
ImageDestroy($newim);
if (function_exists('ImageCreatetruecolor'))
$newim = ImageCreatetruecolor(60,60);
else 
$newim = ImageCreate(60,60); 
if (function_exists('imagecopyresampled'))
imagecopyresampled($newim,$rotated,0,0,0,0,60,60,imagesx($rotated),imagesy($rotated));
else 
imagecopyresized($newim,$rotated,0,0,0,0,60,60,imagesx($rotated),imagesy($rotated));
ImageDestroy($rotated);}
$fullsize=60;
$line_y = mt_rand(0,5);
while($line_y<$fullsize)
{
        imageline($newim, 0, $line_y, $fullsize, $line_y, ImageColorAllocate($newim, mt_rand(0,turingcolors),mt_rand(0,turingcolors),mt_rand(0,turingcolors))); 
        $y_inc_rate = mt_rand(turinglines,turinglines+10);
        $line_y+=$y_inc_rate;
}

$line_x = mt_rand(0,5);
while($line_x<$fullsize)
{
        imageline($newim, $line_x, 0, $line_x, $fullsize, ImageColorAllocate($newim, mt_rand(0,turingcolors),mt_rand(0,turingcolors),mt_rand(0,turingcolors))); 
        $x_inc_reate = mt_rand(turinglines,turinglines+10);
        $line_x+=$x_inc_reate;
}
for($i=0;$i<1000/turinglines;$i++)         
    {
    imagesetpixel($newim, rand(0,60), rand(0,60), ImageColorAllocate($newim, mt_rand(0,turingcolors),mt_rand(0,turingcolors),mt_rand(0,turingcolors)));
    }
ImageJPEG($newim,'',60);
ImageDestroy($newim);
$shown=1;
}}
if (!$shown) {
readfile (scripts_dir . 'images/' . $string . '.jpg');
}
return 1;
?>
