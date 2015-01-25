<script language="php"> include("setup.php");</script> 
<script language="php"> include("header.php");</script>
<?php
if (user('account_type','return')=='Poster'){
 	if ($_GET['client_id']) {
 	$client_id=$_GET['client_id'];
	 $_SESSION['client_id']=$client_id;	
	}
 	else {
 	$client_id=$_SESSION['client_id'];
	}
	
} elseif(user('account_type','return')=='Client Sales'){
	$client_id = user('owner_id','return');
	
} else {
  $client_id=$_SESSION['username'];
}
?>
<?php
$whereClause = "";
if($client_id != null && $client_id !=""){
	$whereClause= " and b.user_id ='$client_id' ";
}
$sql = "select distinct a.file_name,b.title from post_images a, dealer_ads b where a.ad_id=b.ad_id $whereClause order by rand() limit 10";
//echo $sql;
$post_images = @mysql_query($sql);
?>
<link rel="stylesheet" href="<script language="php"> pages_url();</script>resources/css/bxslider.css">
<script language="javascript" type="text/javascript" src="<script language="php"> pages_url();</script>resources/js/bxslider.js"></script>
<style>
    .bx-wrapper .bx-viewport
    {
        background:none !important;
        border:none !important;
        box-shadow: 0 1px 6px 2px #5f82ba !important;
    }
    .bx-wrapper .bx-viewport .img_des h4
    {
        padding-left:5px;
        padding-right:5px;
        text-align: center;
        text-transform: capitalize;
        margin-top:14px;
        font-size: 15px;
        font-style: italic;
    }
	.slider_div
	{
		width:405px;
		height:auto;
		float:right;
	}

</style>


<table width="100%" border="0" cellspacing="5" cellpadding="5">
<tr>
<td align="left" valign="top">
<div class"text"> 
<p>eAdsPosting is a Craigslist Posting Service.</p>
<p>All ads are guaranteed live, free image creation and link tracking!</p>
Ad Services
Ad Creation
Ad Posting
Ad Renewal
Link Tracing
</div>

</td> 
<td align="center" valign="top" class"text">

<div class="slider_div">

<div class="img_slider">
               <?php while ($imageRow = @mysql_fetch_array($post_images))
                { ?>
						<div class="slide">
                          <img style="height:250px;width:400px;" src="<?php echo pages_url."upload_images/".$imageRow['file_name']; ?>" />
                        <div class="img_des">
                          <h4><?php echo $imageRow['title']; ?></h4>
                        </div>
						
						</div>
						
                <?php } ?> 
</div>

</div>


</td>
</tr>
</table>

<script language="php"> include("footer.php");</script>
<script>
$(document).ready(function(){
  $('.img_slider').bxSlider({
    slideWidth: 400,
    minSlides: 1,
    maxSlides: 1,
    slideMargin: 10
  });
});
</script>
