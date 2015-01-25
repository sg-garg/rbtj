<div class="horizontalcssmenu">
<ul id="cssmenu1">

<?php 
$user_sections=scandir(pages_dir.'User_Sections');
 
 foreach ($user_sections as $section)
 	{
 	
	if (strpos('.'.$section,'menu.item.'))
		{
    	 $menuitem = '<a href="'.pages_url.'User_Sections/'.$section.'/'.$section.'.php'.'">'.str_replace('menu.item.','',str_replace('_',' ',$section)).'</a>';
		$user_options=scandir(pages_dir.'User_Sections/'.$section); ?>
	    <li style="border-left: 1px solid #202020;"><?php echo $menuitem; ?>
		<ul>
		<?php 
		   $flag = 1;
		   foreach ($user_options as $option)
			{
			 if($flag == 1){
                       $flag = 0; ?>
			  <ul>
			<?php
     			}			 
			 if (strpos('.'.$option,'menu.item.') && $option!=$section.'.php')
    		     $menuitems = '<a href="'.pages_url.'User_Sections/'.$section.'/'.$option.'">'.substr(str_replace('menu.item.','',str_replace('_',' ',$option)),0,-4).'</a>';
		    ?>
           	<li><?php echo $menuitems; ?></li>	 
  	     <?php	
     		}
         if($flag == 0) {       
		 ?>			
         </ul>
		 <?php } ?>
		 </li>
 		<?php
		}
 	} 
 ?>
</ul>
<br style="clear: left;" />
</div>