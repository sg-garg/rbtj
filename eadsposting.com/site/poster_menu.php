<table border=0 width=100><tr><td nowrap>
<table  width=100>
<? 
$user_sections=scandir(pages_dir.'Poster_Sections'); 
 
 foreach ($user_sections as $section)
 	{
 	
	if (strpos('.'.$section,'menu.item.'))
		{
    	echo '<tr><td class="displaytag_color"><b><a href="'.pages_url.'Poster_Sections/'.$section.'/'.$section.'.php'.'">'.str_replace('menu.item.','',str_replace('_',' ',$section)).'</a></td></tr>';
		$user_options=scandir(pages_dir.'Poster_Sections/'.$section);
	
		foreach ($user_options as $option)
			{
			if (strpos('.'.$option,'menu.item.') && $option!=$section.'.php')
    		echo '<tr><td class="displaytag_color">&nbsp;&nbsp;<b><a href="'.pages_url.'Poster_Sections/'.$section.'/'.$option.'">'.substr(str_replace('menu.item.','',str_replace('_',' ',$option)),0,-4).'</a></td></tr>';
  			}	
 
 		echo '</b>';
		}
 	} 
if ($_SESSION['client_id']){
echo '<b>Client Menu: <font color=blue>'.$_SESSION['client_id'].'</font></b><br><br>';
$user_sections=scandir(pages_dir.'User_Sections');
 
 foreach ($user_sections as $section)
 	{
 	
	if (strpos('.'.$section,'menu.item.'))
		{
    	echo '<b><a href="'.pages_url.'User_Sections/'.$section.'/'.$section.'.php'.'">'.str_replace('menu.item.','',str_replace('_',' ',$section)).'</a><br>';
		$user_options=scandir(pages_dir.'User_Sections/'.$section);
	
		foreach ($user_options as $option)
			{
			if (strpos('.'.$option,'menu.item.') && $option!=$section.'.php')
    		echo '&nbsp;&nbsp;<a href="'.pages_url.'User_Sections/'.$section.'/'.$option.'">'.substr(str_replace('menu.item.','',str_replace('_',' ',$option)),0,-4).'</a><br>';
  			}	
 
 		echo '<br></b>';
		}
 	} 
 }

 ?>
</table> 
<hr />
<b><?php action('show_chat_link','Live Support Chat|EFEEDA|ChatAds|History|Main')?></b>
<br><b><a href="<?php pages_url();?>inbox.php">Inbox (<?php inboxcount(); ?>)</a> </b>
<br><b><a href="<?php pages_url();?>transactions.php">Transactions</a></b>
<br><b><a href="<?php pages_url();?>userinfo.php">Profile</a></b>
<br><b><a href="<?php pages_url();?>index.php?username=LOGOUT&amp;password=LOGOUT">Log-Out</a></b>
</td></tr></table>
