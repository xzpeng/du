<?php
$theusagt = $_SERVER["HTTP_USER_AGENT"];
echo $theusagt.'<br>';
$is_mobile = false;
if(stripos($theusagt , "iPhone") !== false || stripos($theusagt , "iPod") !== false){
    //$thetargetsite = $siteurl_mobile;
    $is_mobile = true;
}
else if(stripos($theusagt , "Mac OS") !== false){
    //$thetargetsite = $siteurl_pc;
    $is_mobile = false;
}
else if(stripos($theusagt , "Mobile") !== false){
    //$thetargetsite = $siteurl_mobile;
    $is_mobile = true;
}
else if(stripos($theusagt , "Android") !== false){
    //$thetargetsite = $siteurl_pc;
    $is_mobile = false;
}
else if(stripos($theusagt , "Windows Phone") !== false){
    //$thetargetsite = $siteurl_mobile;
    $is_mobile = true;
}
else {
    //$thetargetsite = $siteurl_pc;
    $is_mobile = false;
}

if($is_mobile)
{
  echo 'mobile';	
}else
{
   echo 'pc';	
}
?>