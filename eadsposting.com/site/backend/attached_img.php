<?php

include ('../frontend/setup.php');

// Create Temp name from hash of url
$nm=md5($_GET['imgurl']).'.jpg';

// If the image is not on server download and compress
if (!file_exists('../Temp/Misc_Images/large-'.$nm))
	copy($_GET['imgurl'], '../Temp/Misc_Images/large-'.$nm);

if (!file_exists('../Temp/Misc_Images/'.$_GET['w'].'-'.$nm))
	imageCompression('../Temp/Misc_Images/large-'.$nm,$_GET['w'],'../Temp/Misc_Images/'.$_GET['w'].'-'.$nm);
	

// Redirect to show compressed image
header('Location: /Temp/Misc_Images/'.$_GET['w'].'-'.$nm);
?>
