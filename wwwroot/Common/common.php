<?php
  function GetShangJi($id){
      $User = M("User");
      $SjUserID = $User->where("id=".$id)->getField("SjUserID");
      $UserName = $User->where("id=".$SjUserID)->getField("UserName");
      return $UserName;
  }
  
  function GetShangHu($id){
      $User = M("User");
      $Number = $User->where("SjUserID=".$id)->count();
      return $Number;
  }
  
  function GetFl($id){
      
         $Paycost = M("Paycost");
         $wy = $Paycost->where("UserID=".$id)->getField("wy");
         
         
             $wy = (1-$wy)*100;
            
             $wy = round($wy,3);
             
        // return $wy;
         
         $str =  "<span style='color:#F00'>".$wy."%</span>";
         
         if($wy == 100){
             $str = $str."&nbsp;<a href='".U("Index/EditFl","id=".$id)."'>".TransCode("修改")."</a>";
         }
         
         return $str;
  }
  
  function GetMoney($id){
        $Order = M("Order");
        $datedate = date("Y-m-d");
        $daysjmoney =  $Order->where("UserID = ".$id." and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
        if(!$daysjmoney){
            $daysjmoney = 0;
        }
        
        $daysjmoney = round($daysjmoney,3);
        
        return $daysjmoney;  
  }
  
  function GetDayNum($id){
       $Order = M("Order");
       $datedate = date("Y-m-d");
       $daynum =  $Order->where("UserID=".$id." and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
       return $daynum;
  }
  
  function GetTiCheng($id){
        $Order = M("Order");
        $datedate = date("Y-m-d");
		 $trademoney =  $Order->where("UserID = ".$id." and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("TcMoney"); 
    
        
		$trademoney = round($trademoney,3);
		
        return $trademoney;  
  }
  
  function GetzTiCheng($id){
        $Order = M("Order");
     
	   $trademoney =  $Order->where("UserID = ".$id." and Zt = 1")->sum("TcMoney"); 
     
		
        $trademoney = round($trademoney,3);
        return $trademoney;  
  }
  
  function xgDate($RegDate){
      return date("Y-m-d",strtotime($RegDate));
  }
  
  function GetSxf($UserID){

       
         
         $Paycost = M("Paycost");
         
         $wy = $Paycost->where("UserID=".$UserID)->getField("wy");
        $wy = (1 - $wy)*100; 
		
		$wy = round($wy,3);
        if($wy == 100){
            $wy = TransCode("<SPAN STYLE='COLOR:#F00'>默认</SPAN>");
        }else{
            $wy = $wy."%";
        }
        return $wy;
  }
  
  
  function GettTongdao($id){
         
         if($id == 0){
			$System = M("System");
            $DefaultBank = $System->where("UserID=0")->getField("DefaultBank");
			$Sjapi = M("Sjapi");
            $myname = $Sjapi->where("id = ".$DefaultBank)->getField("myname");
             return $myname;
         }else{
             $Sjapi = M("Sjapi");
             $myname = $Sjapi->where("id = ".$id)->getField("myname");
             return $myname;
         }
  }
  
  
  function GetYuE($id){
	  $Money = M("Money");
	  $ye = $Money->where("UserID=".$id)->getField("Money");
	  return round($ye,3);
  }
  
       function TransCode($Code){     //中文转码
           return iconv("GBK", "UTF-8", $Code);
           //return iconv("UTF-8", "GBK", $Code);
        }
  function is_mobile_request()  
{  
 $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
 $mobile_browser = '0';  
 if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
  $mobile_browser++;  
 if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
  $mobile_browser++;  
 if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
  $mobile_browser++;  
 if(isset($_SERVER['HTTP_PROFILE']))  
  $mobile_browser++;  
 $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
 $mobile_agents = array(  
    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
    'wapr','webc','winw','winw','xda','xda-'
    );  
 if(in_array($mobile_ua, $mobile_agents))  
  $mobile_browser++;  
 if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
  $mobile_browser++;  
 // Pre-final check to reset everything if the user is on Windows  
 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
  $mobile_browser=0;  
 // But WP7 is also Windows, with a slightly different characteristic  
 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
  $mobile_browser++;  
 if($mobile_browser>0)  
  return true;  
 else
  return false;
  
  
}
function radios($a,$b){
	if(strstr($a,$b)){
	return "checked";
	}else{
	return "";
	}
} 
?>
