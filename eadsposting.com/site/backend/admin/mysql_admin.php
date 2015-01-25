<?php
//LDF SCRIPTS
include("functions.inc.php");

$title='MySQL Admin';
admin_login();
$pagemax=20;
if ($_GET['gopg'])
$_GET['pg']=$_GET['gopg']-1;
$showall=true;
if(isset($_GET['tablename']) ){
$tablename=$_GET['tablename'];
}elseif(isset($_POST['tablename'])){
$tablename=$_POST['tablename'];
}
if ($tablename==mysql_prefix.'access_log' || preg_match("/".mysql_prefix.'access_log'."|".' database/i',$_POST['wyoqta'])){
echo 'Access Denied';
footer();
}
if(isset($_POST['pagemax'])){ 
$isnum=true;
for($o=0; $o<count($_POST['pagemax']); $o++){
    if($_POST['pagemax'][$o]>9){
	$isnum=false;
    }
}
if($_POST['pagemax']>0 && $isnum){    
$_SESSION['pagemax']=$_POST['pagemax'];
}
}
if(isset($_SESSION['pagemax'])){
$pagemax=$_SESSION['pagemax'];
}

if(isset($_POST['deltable'])){ 
$showall=false;
	$tablename=$_POST['tablename'];
	echo"<center><font color=red class=fsize4>!!! Warning !!! <br>You are about to delete the table '$tablename'<br>"; 
	echo"Are you sure you want to proceed?</font><table border=0><tr><td>"; 
    
	goto($tablename, 'droptab', 'YES'); 
echo '</td><td>';
	goto($tablename, 'start', 'NO' );
echo '</td></tr></table></center>';	

} 
if(isset($_POST['droptab'])){  
	$tablename=$_POST['tablename'];
	$dsql = "drop table $tablename"; 
	$result=exequery($dsql, $tablename); 
	unset($tablename); 
	unset($_POST['tablename']);
} 
if(isset($_POST['wyoq'])){  
	$value="Main"; 
	  if ($_POST['returnpage'])
                echo '<a href="'.$_POST['returnpage'].'">Return to report menu</a>';
                else
	goto($tablename, 'start', $value ); 
	echo"<form method='post'>\n";
	
	
	echo"<textarea name='wyoqta' cols='60' rows='5' style='overflow-y:visible'></textarea>\n";
	
	echo"<br><input type=submit name='runquery' value='Submit Query'>\n"; 
	echo"</form><br>\n"; 
} 

if(isset($_POST['runquery'])){ 
	$wyoqta = StripSlashes($_POST['wyoqta']); 
	$result=exequery($wyoqta, " ", " "); 
 
	if(@mysql_num_rows($result) >0){ 
		$numrows=mysql_num_rows($result); 
		$flds=mysql_num_fields($result);
		   $value="Main";
		if ($_POST['returnpage'])
		echo '<a href="'.$_POST['returnpage'].'">Return to report menu</a>';
		else
		goto($tablename, 'tablestart', $value ); 
		echo"<table border=1>";	 
		for($r=0; $r < $numrows; $r++){ 
			echo"<tr $bgcolor>"; 
			$row=mysql_fetch_array($result); 
			for($col = 0; $col < $flds; $col ++){ 
				$nslash = StripSlashes($row[$col]); 
				echo"<td>$nslash</td>";				 
			} 
			echo"</tr>"; 
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
		} 
		echo"</table>";			 
	}elseif (mysql_affected_rows()){ 
		echo"<br><br>Number of Rows affected: ".mysql_affected_rows();	 
	}else{ 
		echo" Nothing returned from the query."; 
	} 
} 

if( ! isset($tablename) || $tablename==" " ){ 
	$result = mysql_query('show tables');

	if (isset($_POST['runquery'])){  
		$value="Main"; 
	  if ($_POST['returnpage'])
                echo '<a href="'.$_POST['returnpage'].'">Return to report menu</a>';
                else
	goto("", 'tablestart', $value ); 
 
	}elseif (! isset($_POST['wyoq']) && ! isset($_POST['runquery'])){ 
		echo"<center><font class=fsize4 color=red>Do not use this unless you know what you are doing.<br>Misuse of this utility can cause permanent data loss</font></center>";
		echo"<table width=100% border=0><form method=post><tr><td><center>Select a table below to work with<br><select size=25 name=tablename>"; 
	
	$selected=' selected';

 	while ($table=@mysql_fetch_row($result)){
			echo '<option value='.$table[0].$selected.'>'.$table[0].' ('.@mysql_result(@mysql_query('select count(*) from '.$table[0]),0,0).')';
                        $selected='';}
                
echo "</select></td><td><center><br><b>Table Actions</b><br><input type=submit  value='View' name='view'> <input type=submit  value='Drop' name='deltable'><br><br><b>Search Table</b><br><input type=text name='searchval'> <input type=submit name='search' value='Search'></form><form method='post'>
<b>Submit a MySQL Query</b><br><textarea name='wyoqta' cols='30' rows='5'></textarea>
<br><input type=submit name='runquery' value='Submit Query'>
</form><br>";
	
		echo"</td></tr></table>\n";		 
	} 

}else{ 
	echo"<table><tr><td>\n"; 
	$value="Main"; 
          if ($_POST['returnpage'])
                echo '<a href="'.$_POST['returnpage'].'">Return to report menu</a>';
                else
	goto($tablename, 'tablestart', $value ); 
	echo"</td>\n"; 

echo"<td>\n"; 
$value="Query"; 
	goto(" ", 'wyoq', $value ); 
echo"</td>\n"; 

	if (!isset($_POST['add']) && !isset($_POST['deltable']) && isset($tablename)){	 
		echo"<td>";
		
		$va="Add"; 
		goto($tablename, 'add', $va ); 
		echo"</td>\n"; 
	}		 
 
	if (!isset($_POST['deltable'])){ 
		echo"<td>\n"; 
		searchtableform($tablename); 
		echo"</td>\n"; 
	} 
	echo"</tr></table>\n";			 
	echo"<br />\n"; 

       
	if(isset($_POST['addrec'])){ 
   
		$result=addrecord($tablename, $_POST['array']); 
	}elseif(isset($_POST['add'])){ 
    $showall=false;
		addform($tablename); 
	}elseif(isset($_POST['delete'])){
		
   
		$whr=buildwhr($_POST['pk'], $_POST['pv']); 
		$sql = "delete from $tablename where $whr"; 
		$result=exequery($sql, $tablename); 
	}elseif (isset($_POST['edit'])){
    $showall=false;
		$whr = buildwhr( $_POST['pk'], $_POST['pv']); 
		
		$sql= "Select * from $tablename where $whr"; 
 
		$result=exequery($sql, $tablename); 
		editform($tablename,  $result, 'edit', $_POST['pk'], $_POST['pv']); 
	}elseif(isset($_POST['editrec'])){ 
   
		$result=editrec($tablename, $_POST['pk'], $_POST['pv'], $_POST['array']); 
	} 
	if(isset($_POST['searchval'])){
		$searchval=$_POST['searchval'];
	}elseif(isset($_GET['searchval'])){
		$searchval=$_GET['searchval'];
	}else{
		$searchval="";
	}
	
	if (isset($_GET['tablename'])){ 
		$tablename = $_GET['tablename'];
	}
	
	if((isset($_POST['search'])|| isset($searchval)) && $searchval !=""){ 
		$result=searcht($tablename,  $searchval); 
	}else{	
		
		$query = "select * from $tablename"; 
		$result=exequery($query, $tablename);			 
	} 

if($showall){
    $num_rows = mysql_num_rows($result); 
    
	    if(!isset($_GET['pg']) && !isset($pg)){ 
		    $beg=0; 
	$pg=0;
	    }else{
	if(isset($_GET['pback'])){
	    $pg=$_GET['pg'];
	}else{
	    $pg=$_GET['pg'];
	}			    
	 if($pg < 0 ){
	    $pg=0;
	}
	if($pg > $num_rows/$pagemax){
	    $pg=ceil($num_rows/$pagemax)-1;
	}
	$beg = $pg * $pagemax;
		
	    }
	    if (!isset($_POST['add'])){
		    $pscrol=" "; 
		    $pagescrol =" "; 
       
		    $pagescrol = whichpage($num_rows, $pagemax, $pg, $tablename, $searchval);
		
		    echo "$pagescrol\n"; 

		    $flds = mysql_num_fields($result);
		    echo '<center><font class=fsize4>'.$tablename.'</font></center>'; 
		    echo"<table border=1 width='100%'>\n"; 
		    echo"<tr><td></td><td></td>\n"; 
		     $fields = getfields($tablename);      

		    $z=0; 
		    $x =0; 
		    $pkfield=array(); 

		    for ($i = 0; $i < $flds; $i++) {			 
			    echo "<td>".$fields[$i]."</td>\n"; 

			    
			    $flagstring = mysql_field_flags ($result, $i); 
			    if(preg_match("/primary/i",$flagstring )){ 
				    $pk[$z] = $i; 
								 
				    $pkfield[$z]= $fields[$i]; 
				    $z++; 
			    } 
		    } 
		    echo"</tr>\n";		
		    $tbl=$tablename; 
		    
		    if($z > 0){ 
			    $cpk=count($pk); 
		    }else{ 
			$cpk=0; 
		    } 

		 
		    for ($s=$beg; $s < $beg + $pagemax; $s++){	 
			    if($s < $num_rows){ 
				    if (!mysql_data_seek ($result, $s)) { 
			echo "Cannot seek to row $s\n"; 
			continue; 
			    } 
				    $row=mysql_fetch_array($result); 
				    if(!isset($pk)){ 
					$pk=" "; 
					$pkfield= array(); 
				    } 
				    displayrow($tbl, $pk, $pkfield, $cpk, $row, $flds); 
			    }			 
		    }						 
	    }
	    echo"</table>\n"; 
	    if (!isset($_POST['add']) && !isset($_POST['edit']) && !isset($_POST['deltable']) && !isset($_POST['droptab']) && !isset($_POST['wyoq']) && $tablename){ 
		    echo"<br>"; 
		    echo "$pagescrol\n"; 
	    }	 
	    echo"<br><br>\n"; 
	 }
} 

footer();

function exequery($sql, $tablename){ 
	$result= @mysql_query( $sql ); 
	if($result){ 
		
		return $result; 
	}else{		 
		echo"Sorry your Query failed: $sql <br> error:".mysql_error()."\n"; 
		return false; 
	}	 
} 
 
$fieldtypes = array("BIGINT", "BLOB", "CHAR", "DATE", "DATETIME", "DECIMAL", "DOUBLE", "ENUM", "FLOAT",  
  "INT", "INTEGER", "LONGBLOB", "LONGTEXT", "MEDIUMBLOB", "MEDIUMINT", "MEDIUMTEXT", "NUMERIC", "PRECISION",  
 "REAL","SET", "SMALLINT", "TEXT", "TIME", "TIMESTAMP", "TINYBLOB", "TINYINT", "TINYTEXT", "VARCHAR", "YEAR" ); 
  	 
		      		     
function searchtableform($tablename){ 
	echo"<form method='post' action='mysql_admin.php'>\n"; 
	echo"<input type=hidden name='tablename' value='$tablename' />\n"; 
	echo"<input type=text name='searchval' />\n"; 
	echo"<input type=submit name='search' value='Search' />\n"; 
	echo"</form>\n";	 
} 

function searcht($tablename,$searchval){ 
	if(! empty($searchval)){ 
		
        $result=exequery("Select * from $tablename", $tablename);
		
		$num = mysql_num_fields($result); 
	         $fields = getfields($tablename);     	
		$whr="where "; 
		$tok=explode(" ",$searchval); 
		for ($t =0; $t < count($tok); $t++){					 
			for ( $c = 0; $c < $num; $c++){ 
				$fn =$fields[$c];				 
				$whr .=" $fn like '%$tok[$t]%' or ";													 
			} 
		} 
		$whr=trim(substr_replace($whr, " ", -3));		 
		$query="Select * from $tablename $whr"; 
		$result=exequery($query, $tablename); 
		return $result;	 
	}		 
 
} 

function getfields($tbl){
$result=mysql_query('SHOW COLUMNS FROM '.$tbl);
$names='';
while ($row=mysql_fetch_row($result)){
$names[]=$row[0];
}
return $names;
}

function goto($tablename, $name, $va ){ 
	
    
	echo"<form action=mysql_admin.php method='post' >\n"; 

		if(!preg_match('/tablestart/i', $name)){ 
			echo"<input type=hidden name=tablename value='$tablename' />\n"; 
		} 
		echo"<input type=submit  value='$va' name='$name' />\n"; 
		
	echo"</form>\n"; 
	
	
	
} 


 
function buildwhr($pk, $pv){ 
	$whr=""; 
	$pn =count($pv); 
	for($t =0; $t < $pn; $t++){		 
		$whr.="$pk[$t]='$pv[$t]'"; 
		if($t < $pn-1){ 
			$whr.=" and "; 
		} 
	} 
	if ($whr !=" "){ 
		return $whr; 
	}else{ 
		return false; 
	} 
} 

 
function addrecord($tablename, $array){ 
     $result=exequery("Select * from $tablename", $tablename);
	
	 
	$flds = mysql_num_fields($result); 
	
   	$qry=" "; 
    $query = "Insert into $tablename Values( "; 
	for ($x =0; $x < $flds; $x++){ 
        
     
       if(is_array($array[$x])){
            $mval="";
            for($m=0; $m < count($array[$x]); $m++){
                if($m+1 == count($array[$x])){
                    $mval.= AddSlashes($array[$x][$m]); 
               
                }else{
                    $mval.= AddSlashes($array[$x][$m]).","; 
                }
                $fval = $mval;
            }
        }else{
		    $fval = AddSlashes($array[$x]); 
        }
		$qry .= "'$fval'"; 
		if ($x < $flds-1){ 
			$qry.= ", "; 
		} 
	} 
	$query .= $qry.")"; 
   
	$result=exequery($query, $tablename); 
	if($result){ 
		return $result; 
	}else{ 
		return false; 
	}	 
} 
 


function addform($tablename){
 
 echo"<form action='mysql_admin.php' method='post'>\n";
 echo"<table border=0 width='100%' align='center'>\n";
 echo"<tr><td>Field Name</td><td>Type</td><td>Value</td></tr>\n";
  $result=exequery("Select * from $tablename", $tablename);
 
 $flds = mysql_num_fields($result);
 $fields = getfields($tablename);
 echo"<input type=hidden name=tablename value='$tablename' />\n";
 echo"<tr>\n";
  
 $mxlen = 80;
 for($i=0; $i < $flds; $i++){
      $auto = "false";
      echo "<th>".$fields[$i];
      $fieldname = $fields[$i];  
      $type  = mysql_field_type($result, $i);
      $flen = mysql_field_len($result, $i);
      $flagstring = mysql_field_flags ($result, $i);
    
      $newsql = "show columns from $tablename like '%".$fieldname."'";
      $newresult = exequery($newsql, $tablename); 
      
      $arr=mysql_fetch_array($newresult);
    
      if (preg_match("/primary/i",$flagstring )){
       $type .= " PK ";
      }
      if(preg_match("/auto/i",$flagstring )){
       $type .= " auto_increment";
       $auto = "true";
      }
      if ($auto=="true"){
        echo"<td>$type</td><td><input type=text name='array[$i]' size='$flen' value=0 /></td></tr>\n";
      }elseif($flen > $mxlen){
        $rws= $flen/$mxlen;
        if($rws>10){
             $rws=10; 
        }
        echo"<td>$type</td><td><textarea name='array[$i]' rows=$rws cols=$mxlen></textarea></td></tr>\n";
        
      }elseif (strncmp($arr[1],'set',3)==0 || strncmp($arr[1],'enum',4)==0){  
       $num=substr_count($arr[1],',') + 1;  
       $pos=strpos($arr[1],'(' ); 
       $newstring=substr($arr[1],$pos+1);  
       $snewstring=str_replace(')','',$newstring); 
       $nnewstring=explode(',',$snewstring,$num); 
       if(strncmp($arr[1],'set',3)==0 ){
           echo "<td>Set (select one or more)</td>";
           echo"<td><select name='array[$i][]' size='3' multiple>";
       }else{
        echo "<td>Enum</td>";
           echo"<td><select name='array[$i]'>";
       }
       for($y=0; $y<$num;$y++){
       echo"<option value=$nnewstring[$y]>$nnewstring[$y]";
       }
        echo"</select></td></tr>\n";
    
      }else{      
       echo"<td>$type</td><td><input type=text name='array[$i]' size='$flen' /></td></tr>\n";
      }
 }  
 echo"<tr><td><input type=submit name='addrec' value='Add Record' /></td>\n";
 echo"<td><input type=reset name='reset' value='Reset Form' /></td>\n";
 echo"</tr>";
 echo"</table>\n";
 echo"</form>\n";
}


function editform($tablename, $result, $edit, $pk, $pv){ 
	$row=mysql_fetch_array($result); 
	echo"<form action='mysql_admin.php'  method=post>\n"; 
	echo"<table border=0 width ='100%' align='center'>\n"; 
	 
	$flds = mysql_num_fields($result); 
	 $fields = getfields($tablename);     
	echo"<input type=hidden name=tablename value='$tablename' />\n"; 
 
	echo"<tr>"; 
	$mxlen = 80;
	for($i=0; $i < $flds; $i++){ 
        $fname=$fields[$i];
		echo "<th>$fname"; 
	 	$flen = mysql_field_len($result, $i);
		$nslash = StripSlashes($row[$i]);		 
        
      $newsql = "show columns from $tablename like '%".$fname."'";
      $newresult = exequery($newsql, $tablename); 
      $arr=mysql_fetch_array($newresult);
    
        
		if($flen > $mxlen){ 
			$rws= $flen/$mxlen; 
				if($rws>10){ 
				$rws=10; 
			} 
			echo"<td><textarea name='array[$i]' rows=$rws cols=$mxlen>$nslash</textarea></td></tr>\n"; 

          }elseif (strncmp($arr[1],'set',3)==0 || strncmp($arr[1],'enum',4)==0){  
           $num=substr_count($arr[1],',') + 1;  
           $pos=strpos($arr[1],'(' ); 
           $newstring=substr($arr[1],$pos+1);  
           $snewstring=str_replace(')','',$newstring); 
           $nnewstring=explode(',',$snewstring,$num); 
           if(strncmp($arr[1],'set',3)==0 ){
               echo"<td><select name='array[$i][]' multiple size='3'>";
           }else{
               echo"<td><select name='array[$i]'>";
           }
           $nsel=explode(",",$nslash);
          for($y=0; $y<$num;$y++){
                
                $sel="";
                for($e=0; $e<count($nsel);$e++){        
                    if($nnewstring[$y]=="'".$nsel[$e]."'"){
                        $sel="selected";
                    }
                }
                echo"<option value=$nnewstring[$y] $sel>$nnewstring[$y]";
           }
            echo"</select></td></tr>\n";

        
        
        }else{    		 
			echo"<td><input type=text name='array[$i]' size='$flen' value='$nslash' /></td></tr>\n"; 
		} 
		for($f =0; $f< count($pk);$f++){			 
			echo"<input type=hidden name=pk[$f] value='$pk[$f]' />"; 
			echo"<input type=hidden name=pv[$f] value='$pv[$f]' />\n"; 
		} 
	} 
	echo"<tr><td><input type=submit name='editrec' value='Update' /></td>\n"; 
	echo"<td><input type=reset name='reset' value='Reset Form' /></td>\n"; 
	echo"</tr>"; 
	echo"</table>\n"; 
	echo"</form>\n"; 
} 
function editrec($tablename, $pk, $pv, $array){ 
 
	
    $result = exequery("Select * from $tablename", $tablename); 
	$flds = mysql_num_fields($result); 
         $fields = getfields($tablename);      

   	$qry=""; 
    $query = "UPDATE $tablename set "; 
	for ($x =0; $x < $flds; $x++){ 
		$fie = $fields[$x]; 
        
         if(is_array($array[$x])){
            $mval="";
            for($m=0; $m < count($array[$x]); $m++){
                if($m+1 == count($array[$x])){
                    $mval.= AddSlashes($array[$x][$m]);               
                }else{
                    $mval.= AddSlashes($array[$x][$m]).","; 
                }
                $fval = $mval;
            }
        }else{
		    $fval = AddSlashes($array[$x]); 
        }
        
		
		$qry .= "$fie = '$fval'"; 
		if ($x < $flds-1){ 
			$qry.= ", "; 
		} 
	} 
	$whr = buildwhr( $pk, $pv); 
	$whr =StripSlashes($whr); 
	$query .= "$qry"; 
	$query .= " where $whr"; 
 
    $result=exequery($query, $tablename); 
	if($result){ 
		return $result; 
	}else{ 
		return false; 
	} 
} 

function numpk($result){ 
	$z =0; 
	for ($i = 0; $i < $flds; $i++) {			 		 	 
		
		$flagstring = mysql_field_flags ($result, $i); 
		if(preg_match("/primary/i",$flagstring )){ 
			$z++; 
		} 
	} 
	return $z; 
} 

function fieldformsize($ft, $i, $l){ 
	$ft= trim(strtoupper($ft)); 
	if($ft =="DATE" || $ft=="TIME" || $ft== "DATETIME" ){			 
	}elseif( $ft=="TINYTEXT" || $ft=="BLOB" || $ft=="TEXT" || $ft =="MEDIUMBLOB"){	 
		echo"<input type=hidden name='leng[$i]' value=$l>"; 
	}elseif($ft=="MEDIUMTEXT" || $ft=="LONGBLOB"|| $ft=="LONGTEXT" || $ft=="TINYBLOB"){ 
		echo"<input type=hidden name='leng[$i]' value=$l>";				 
	}elseif($ft=="INT" || $ft=="TINYINT"|| $ft=="SMALLINT"|| $ft=="MEDIUMINT"|| $ft=="BIGINT" || $ft=="INTEGER"){ 
		echo"<input type=text name='leng[$i]' size=5  value=$l>"; 			 
	}elseif($ft=="YEAR" ){ 
		echo"<select name='leng[$i]'>"; 
		echo"<option value='4'>4"; 
		echo"<option value='2'>2"; 
		echo"</select>\n";	
    }elseif($ft=="SET"|| $ft=="ENUM"){
        echo"<input type=text name='leng[$i]' title='values eg \"a\", \"b\", \"c\"' value='' />"; 			 
	}else{		 
		echo"<input type=text name='leng[$i]' size=5 value=$l />\n";				 
	} 
} 
 

function displayrow($tbl, $pk, $pkfield, $cpk, $row, $flds){ 
	$pkfs=""; 
	$hv=""; 
	$hf=""; 
global $bgcolor; 
	if($cpk >0 && !empty($pkfield)){ 
		for($a = 0; $a < $cpk; $a++){ 
			$fieldn = $pkfield[$a];			 
			$hf .= "<input type=hidden name=pk[$a] value='$pkfield[$a]' />"; 
			$hv .= "<input type=hidden name=pv[$a] value='$row[$fieldn]' />"; 
		} 
	}else{ 
	         $fields = getfields($tbl);     	
		for($b = 0; $b < $flds; $b++){ 
			$fie = $fields[$b];	 
			$hf .= "<input type=hidden name=pk[$b] value='$fie' />"; 
			$hv .= "<input type=hidden name=pv[$b] value='$row[$b]' />";	 
		} 
	}					 
	echo"<tr $bgcolor>\n"; 
	
	echo"<td><form action='mysql_admin.php' method=post>\n"; 
	echo"<input type=hidden name=tablename value='$tbl' />\n"; 
	echo"<input type=hidden name=npkeys value='$cpk' />\n"; 
	echo"$hf"; 
	echo"$hv"; 
	echo"<input type=submit name=edit value='Edit' />\n"; 
	echo"</form></td>\n"; 
				 
	echo"<td><form action='mysql_admin.php' method=post>\n"; 
	echo"<input type=hidden name=tablename value='$tbl' />\n"; 
	echo"<input type=hidden name=num value='$cpk' />\n"; 
	echo"$hf"; 
	echo"$hv"; 
	echo"<input type=submit name=delete value='Delete' />\n"; 
	echo"</form></td>"; 
 
	
	for($col = 0; $col < $flds; $col ++){ 
		$nslash = StripSlashes($row[$col]); 
		echo"<td valign=top>$nslash</td>";				 
	}			 
	echo"</tr>"; 
		if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}						 
} 


 
function removearraycopy($x){	 
	$leng= count($x); 
	sort($x); 
	$farr=array(); 
	 
	for ($i =0; $i < $leng; $i++){ 
		$flag=false;	 
		for ($s =0; $s < count($farr); $s++){ 
			if($x[$i]==$farr[$s]){ 
				$flag=true; 
			} 
		} 
		if ($flag == false){ 
			$farr[count($farr)] = $x[$i];			 
		} 
	} 
	return $farr;	 
} 

function whichpage($num_rows, $pagemax, $pg, $tablename, $searchval){
	$pgs = $num_rows/$pagemax; 
	$pgs=ceil($pgs);
    			
	echo"<form action='mysql_admin.php' id='recspage' method='post' name='recspage'>\n";
    echo"Total number of records $num_rows, displayed on $pgs pages of ";
    echo"<input type='text'  name='pagemax' value='$pagemax' size='4' onchange='javascript:this.form.submit();' title='Type the number records to display on a page then click outside the box' />"; 			 
	echo"<input type='hidden' name='searchval' value='$searchval'  />"; 
    echo"<input type='hidden' name='tablename' value='$tablename'  />"; 
    echo" records per page.</form> \n";
    $pagescrol="";
    $sval="";													 
	  if($pgs >1){    
            $pagescrol="<div>\n";
			$nxt=$pg+1;
            $bk=$pg-1;
            $lst=$pgs;
            $end=$lst-1;
            $showp=$pg+1;
           if($searchval !=""){
            $sval="&amp;searchval=$searchval";
           }           	
           $pagescrol .= "<form name='pages' id='pages' action='mysql_admin.php' method='get'>\n"; 
            if($pg>=1){ 
                $pagescrol .= " <a href='mysql_admin.php?tablename=$tablename&amp;pg=0$sval' title='To first page'> 1 :<< </a> \n"; 	
				$pagescrol .= " <a href='mysql_admin.php?tablename=$tablename&amp;pg=$bk$sval' title='Back one page'> < </a> \n";               
			}		           
           $pagescrol .= "<input type='text' name='gopg' value='$showp' size='4' onchange='javascript:this.form.submit();' title='Type a page number then click outside the box' />\n"; 
           $pagescrol .= "<input type='hidden' name='pback' value='true'  />\n"; 
           $pagescrol .= "<input type='hidden' name='searchval' value='$searchval'  />\n"; 
           $pagescrol .= "<input type='hidden' name='tablename' value='$tablename'  />\n"; 
           
           if($showp < $lst){ 				
                $pagescrol .= " <a href='mysql_admin.php?tablename=$tablename&amp;pg=$nxt$sval' title='Next page'> > </a> \n"; 
                $pagescrol .= " <a href='mysql_admin.php?tablename=$tablename&amp;pg=$end$sval' title='To Last page'> >>: $lst</a> \n"; 
           }   
           $pagescrol .= "</form>\n"; 
           $pagescrol.="</div>\n";
      }
	return $pagescrol;
} 



