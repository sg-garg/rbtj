<?
if (!defined('version')){
exit;}
        $parts=explode(strtolower($find), strtolower($string));

        $pos=0;

        foreach ($parts as $key => $part)
            {
            $parts[$key]=substr($string, $pos, strlen($part));

            $pos+=strlen($part) + strlen($find);
            }

        return (join($replace, $parts));
?>
