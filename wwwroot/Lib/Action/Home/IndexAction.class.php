<?php
class IndexAction extends Action {
	
    public function Index(){

	   $str = "";
	   foreach($_REQUEST as $key => $val){
		   if($val != ""){
			  //echo($key."=>".$val."<br>");
			  $str .= $key."=".urlencode($val)."&";
		   }
	   }
	 //  echo($str."ddddddd");
	   if($str != ""){
		   $str = trim($str,"&");
		   $str = "http://du.pengxiaozhou.com/Payapi_Index_Pay.html?".$str;	
		   $this->gheader($str);
		 // header("Location: $str")；
		  // exit();
	   }
	   header("Content-Type:text/html; charset=utf-8");
	   
       session('mbk',null); 
       session('mbkcheck',null);
       
       $Newlist = M("Newlist");
       
       $listdt = $Newlist->where("type = 0 and zt = 0")->limit("0,3")->order("datetime desc")->select();
       $listxw = $Newlist->where("type = 1 and zt = 0")->limit("0,3")->order("datetime desc")->select();
       $listgg = $Newlist->where("type = 2 and zt = 0")->limit("0,3")->order("datetime desc")->select(); 
       $listcjwt = $Newlist->where("type = 3 and zt = 0")->limit("0,3")->order("datetime desc")->select();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
       
       $this->assign("listdt",$listdt);
       $this->assign("listxw",$listxw);
       $this->assign("listgg",$listgg);
       $this->assign("listcjwt",$listcjwt);
       $this->display();
    }
	
	function gheader($url)  
{  
echo '<html><head><meta http-equiv="Content-Language" content="zh-CN"><meta HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=gb2312"><meta http-equiv="refresh"  
content="0;url='.$url.'"><title>loading ... </title></head><body><div style="display:none">  
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id=\'cnzz_stat_icon_5696423\'%3E%3C/span%3E%3Cscript src=\'" + cnzz_protocol + "s9.cnzz.com/stat.php%3Fid%3D5696423%26show%3Dpic1\' type=\'text/javascript\'%3E%3C/script%3E"));</script></div>  
<script>window.location="'.$url.'";</script></body></html>';  
exit();  
}  
    
     public function tempIndex(){
       header("Content-Type:text/html; charset=utf-8");
       session('mbk',null); 
       session('mbkcheck',null);
       
       $Newlist = M("Newlist");
       
       $listdt = $Newlist->where("type = 0 and zt = 0")->limit("0,3")->order("datetime desc")->select();
       $listxw = $Newlist->where("type = 1 and zt = 0")->limit("0,3")->order("datetime desc")->select();
       $listgg = $Newlist->where("type = 2 and zt = 0")->limit("0,3")->order("datetime desc")->select(); 
       $listcjwt = $Newlist->where("type = 3 and zt = 0")->limit("0,3")->order("datetime desc")->select();
       $this->assign("listdt",$listdt);
       $this->assign("listxw",$listxw);
       $this->assign("listgg",$listgg);
       $this->assign("listcjwt",$listcjwt);
       $this->display();
    }
	
 public function dborder(){
      vendor('PHPExcel175.PHPExcel');
      $filePath = "./abc.xls";
      $PHPReader = new PHPExcel_Reader_Excel2007();
        if(!$PHPReader->canRead($filePath)){
            $PHPReader = new PHPExcel_Reader_Excel5();
            if(!$PHPReader->canRead($filePath)){
                echo 'no Excel';
                return ;
            }
        }
        
         $PHPExcel = $PHPReader->load($filePath);
    
        /**读取excel文件中的第一个工作表*/
        $currentSheet = $PHPExcel->getSheet(0);
        /**取得最大的列号*/
        $allColumn = $currentSheet->getHighestColumn();
        /**取得一共有多少行*/
        $allRow = $currentSheet->getHighestRow();
        
        $listlist  = array();
        
        $i = 0;
        $abc = 0;
        
        $Order = M("Order");
        $list = $Order->where("Zt <> 0 and DATEDIFF(TradeDate,'2016-07-01') = 0 ")->select();
       
        foreach($list as $key){
            //echo($key["TransID"]."[".$i++."]<br>");
            $listlist[$i++] = $key["TransID"];
        }
       
       
        for($rowIndex=2;$rowIndex<=$allRow;$rowIndex++){
            $addr = "B".$rowIndex;
            $cell = $currentSheet->getCell($addr)->getValue();
            if($cell instanceof PHPExcel_RichText){     //富文本转换字符串
                $cell = $cell->__toString();
            }
            for($j = 0; $j <= count($listlist);$j++){
                if($listlist[$j] == $cell){
                    $listlist[$j] = "";
                   // echo("delete[".$i++."]<br>");
                    $abc = 1;
                    break;
                }
            }
            
            if($abc == 0){
                //echo($cell."<br>");
            }else{
                $abc = 0;
            }
            
        }
        $i = 0;
       for($j = 0; $j <= count($listlist);$j++){
           if($listlist[$j] <> ""){
               echo($listlist[$j]."<br>");
           }
          
        }
        
        
 }   
    
	public function company(){   //公司简介
		$this->display();
		}
	
	public function sjtdt(){   //盛捷通动态
    
          $wherestr = "type = 0 and zt = 0"; 
          $Newlist = M("Newlist");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Newlist->where($wherestr)->count();    //计算总数 
          $p = new Page($count, 15); 
          
          $list = $Newlist->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('datetime desc')->select(); 
        
          $page = $p->show();            
          $page = str_replace("/index.php","",$page);
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->display();
    }
    
    public function sjtgg(){   //盛捷通公告
    
          $wherestr = "type = 2 and zt = 0"; 
          $Newlist = M("Newlist");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Newlist->where($wherestr)->count();    //计算总数 
          $p = new Page($count, 15); 
          
          $list = $Newlist->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('datetime desc')->select(); 
        
          $page = $p->show();            
     
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->display();
    }
    
     public function sjtcjwt(){   //盛捷通公告
    
          $wherestr = "type = 3 and zt = 0"; 
          $Newlist = M("Newlist");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Newlist->where($wherestr)->count();    //计算总数 
          $p = new Page($count, 15); 
          
          $list = $Newlist->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('datetime desc')->select(); 
        
          $page = $p->show();    
          $page = str_replace("/index.php","",$page);
          //$page = str_repeat("/index.php","",$page);          
     
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->display();
    }
    
    public function shownewsdt(){
        
          $id = $this->_get("newsid");
          
          
          $Newlist = M("Newlist");
          
          $title = $Newlist->where("id=".$id)->getField("title");
          
          $content = $Newlist->where("id=".$id)->getField("content");
          
          $zt = $Newlist->where("id=".$id)->getField("zt");
          
          $datetime = $Newlist->where("id=".$id)->getField("datetime");
          
          $content = str_replace("&lt;","<",$content);
          
          $content = str_replace("&gt;",">",$content);
          
          $content = str_replace("&quot;",'"',$content);
          $content = str_replace("\\",'',$content);
          
          $this->assign("title",$title);
          $this->assign("content",$content);
          $this->assign("type",$type);
          $this->assign("datetime",$datetime);
          $this->display();
    }
    
    
     public function shownewscjwt(){
        
          $id = $this->_get("newsid");
          
          
          $Newlist = M("Newlist");
          
          $title = $Newlist->where("id=".$id)->getField("title");
          
          $content = $Newlist->where("id=".$id)->getField("content");
          
          $zt = $Newlist->where("id=".$id)->getField("zt");
          
          $datetime = $Newlist->where("id=".$id)->getField("datetime");
          
          $content = str_replace("&lt;","<",$content);
          
          $content = str_replace("&gt;",">",$content);
          
          $content = str_replace("&quot;",'"',$content);
          $content = str_replace("\\",'',$content);
          
          $this->assign("title",$title);
          $this->assign("content",$content);
          $this->assign("type",$type);
          $this->assign("datetime",$datetime);
          $this->display();
    }
    
    public function sjtxw(){   //盛捷通动态
    
          $wherestr = "type = 1 and zt = 0"; 
          $Newlist = M("Newlist");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Newlist->where($wherestr)->count();    //计算总数 
          $p = new Page($count, 15); 
          
          $list = $Newlist->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('datetime desc')->select(); 
        
          $page = $p->show();            
          $page = str_replace("/index.php","",$page);
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->display();
    }
    
    public function shownewsxw(){
        
          $id = $this->_get("newsid");
          
          
          $Newlist = M("Newlist");
          
          $title = $Newlist->where("id=".$id)->getField("title");
          
          $content = $Newlist->where("id=".$id)->getField("content");
          
          $zt = $Newlist->where("id=".$id)->getField("zt");
          
          $datetime = $Newlist->where("id=".$id)->getField("datetime");
          
          $content = str_replace("&lt;","<",$content);
          
          $content = str_replace("&gt;",">",$content);
          
          
          $this->assign("title",$title);
          $this->assign("content",$content);
          $this->assign("type",$type);
          $this->assign("datetime",$datetime);
          $this->display();
    }
	
	public function verify(){
        header("Content-Type:text/png; charset=gb2312");
		import('ORG.Util.Image');
		ob_clean();
		Image::buildImageVerify();
	}
	
	public function reg(){
		
		header("Content-Type:text/html; charset=utf-8");
		$this->display('reg');
	}
	
	
	
	public function regtwo(){
		
		
		header("Content-Type:text/html; charset=utf-8");
		
	    //echo "<pre>";
		//print_r($_POST);
	
		$_POST['UserName']      	    = $_POST['login_name'];
		$_POST['LoginPassWord']  		= $_POST['passwd1'];
		
		$User = M("User");
		
		
		
		$data["UserName"] = $_POST['UserName'];
		
		$data["LoginPassWord"] = md5($_POST['LoginPassWord']);

		$result = $User->where($data)->find();
		if($result)
		{
			echo "have one user,please change a name";
			exit;
		}
		
		$data["status"] = 1;
		
		$User->add($data);
		$id = mysql_insert_id();	
		
		
		$result_user = $User->where($data)->find();
                    
		$key = md5(md5(md5(time()).md5($this->_post("UserName"))).md5($this->_post("LoginPassWord"))).md5(md5(md5(time()).md5($this->_post("LoginPassWord"))).md5($this->_post("UserName")));
		
		$User = M("User");
		$data["activate"] = $key;
		$data["SjUserID"] = $_POST["SjUserID"];
		$User->where("id=".$id)->save($data);
		
		session("jh_username",$this->_post("UserName"));
		
		
		$_POST['IdentificationCard']    = $_POST['id_card'];
		$_POST['MobilePhone']           = $_POST['phone'];
		$_POST['UserID']                = $id;
		
		$userbasicinformation = M("userbasicinformation");

		$result_ko = $userbasicinformation->add($_POST);
		
		   
			   
				
				$Userapiinformation = M("Userapiinformation");    //会员接口信息
				$data["UserID"] = $id;
				$Userapiinformation->add($data);
				
				$Usersafetyinformation = M("Usersafetyinformation");   //会员安全信息
				$data["UserID"] = $id;
				$data["PayPassWord"] = md5("123456");
				$Usersafetyinformation->add($data);
				
				
				$Usersafetyinformation_money = M("money");   //会员安全信息
				$data["UserID"] = $id;
				$Usersafetyinformation_money->add($data);
				
				
				$Usersafetyinformation_money_system = M("system");   //会员安全信息
				$data["UserID"] = $id;
			    $data["Diaodan_OnOff"] = 1;
			    $data["Diaodan_Sdate"] = 6;

			    $data["Diaodan_Type"] = 4;

			    $data["Diaodan_huifu"] = 1;

			    $data["Diaodan_User_OnOff"] = 1;
			
				$Usersafetyinformation_money_system->add($data);
				
		
		if($result_ko){

			
			echo "<script>alert('注册成功!请登陆后点击审核信息菜单填写相关信息后提交审核后,联系客服为您审核.');location.href='http://du.pengxiaozhou.com/index_login.html'</script>";
			//  echo "chenggong ";
		}else{
			  echo "reg fail ";
		}
		
	
					
				
			
		
		
	}
	
	public function Activate(){
		header("Content-Type:text/html; charset=utf-8");
		$id  = $_GET["_URL_"][3];
		 
		$activate  = $_GET["_URL_"][5];
		
		//echo $id."----".$activate;
		$User = D("User");
		
	    $list = $User->where("id = ".$id." and activate = '".$activate."' ")->select();
		$UserType = $User->where("id = ".$id." and activate = '".$activate."' ")->getField("UserType");
        
		if($list != null){
			
			$data["status"] = 1;
			$row = $User->where("id=".$id)->save($data);
			if($row){
				
                //if(intval($UserType) != 3){
                
				$Userbasicinformation = D("Userbasicinformation");  //会员基本信息
				$data["UserID"] = $id;
				$Userbasicinformation->add($data);
				
				$Userapiinformation = D("Userapiinformation");    //会员接口信息
				$data["UserID"] = $id;
				$Userapiinformation->add($data);
				
				$Usersafetyinformation = D("Usersafetyinformation");   //会员安全信息
				$data["UserID"] = $id;
				$data["PayPassWord"] = md5("123456");
				$Usersafetyinformation->add($data);
                
                $System = M("System");
                $data["UserID"] = $id;
                $data["Diaodan_User_OnOff"] = 1;
                $System->add($data);
                
                $Paycost = M(Paycost);
                $data["UserID"] = $id;
                $Paycost->add($data);
                
                $Money = D("Money");
                $data["UserID"] = $id;
                $data["Money"] = 0;
                $Money->add($data);
				
				//}
                
				$this->display("Index:jihuo");
				//echo "";
			}else{
				//echo $row;
                
                echo "账号已激活！";
                //echo  iconv("GBK", "UTF-8", "非法的请求！");
			}
		}else{
			echo "请不要非法提交！";
           // echo  iconv("GBK", "UTF-8", "系统错误"); 
			}
				
	}
	
	
	public function Login(){    //登录
		header("Content-Type:text/html; charset=utf-8");
		$UserName = $this->_post("UserName");
		$LoginPassWord = $this->_post("LoginPassWord");
		$UserType = $this->_post("UserType");
        ///////////////////////////////////////////////
        if(intval($UserType) == 2){
            $UserType = "(UserType = 1 or UserType = 0 or UserType = 3)";
            $User_Type = 1;
        }else{
            $UserType = "UserType = 5";
            $User_Type = 5;
        }
        //////////////////////////////////////////////
		if($UserName == "" or $LoginPassWord == ""){
			//exit("账号或密码不能为空！");
      $this->display('login');
		}else{
			if(1 !==1){
			    exit("验证码错误3333333333！");
			}else{
				$User = D("User");
				$list = $User->where("UserName = '".$UserName."' and LoginPassWord = '".md5($LoginPassWord)."' and ".$UserType)->getField("status");
				if($list != null){
					if(intval($list) == 0){
						//exit($list.status);
						exit("您的账号没有激活，请激活帐号后再登录");
					}else{
						if(intval($list) == 2){
							exit("您的账号已被锁定！");
						}else{
                            $mbk = $User->where("UserName = '".$UserName."' and LoginPassWord = '".md5($LoginPassWord)."' and ".$UserType)->getField("mbk");
                            $UserID = $User->where("UserName = '".$UserName."' and LoginPassWord = '".md5($LoginPassWord)."' and ".$UserType)->getField("id");
							if($mbk == 1){
                                if($this->_post("mbk") == "" && !session("?mbk")){
                                    
                                        ///////////////////////////////////////////
                                 $Arraylist = array("A","B","C","D","E","F","G","H","I");
                                 session("mbk","");
                                 session("mbkcheck","");
                                 $Passwordblock = M("Passwordblock");
                                 $mbkstr = $Arraylist[rand(0,8)].rand(1,9);
                                 session("mbk", $mbkstr."&nbsp;&nbsp;".session("mbk"));
                                 session("mbkcheck",$Passwordblock->where("UserID=".$UserID)->getField($mbkstr).session("mbkcheck"));
                                 $mbkstr = $Arraylist[rand(0,8)].rand(1,9);
                                 session("mbk", $mbkstr."&nbsp;&nbsp;".session("mbk"));
                                 session("mbkcheck",$Passwordblock->where("UserID=".$UserID)->getField($mbkstr).session("mbkcheck"));
                                 $mbkstr = $Arraylist[rand(0,8)].rand(1,9);
                                 session("mbk", $mbkstr."&nbsp;&nbsp;".session("mbk"));
                                 session("mbkcheck",$Passwordblock->where("UserID=".$UserID)->getField($mbkstr).session("mbkcheck"));
                                 $mbkstr = $Arraylist[rand(0,8)].rand(1,9);
                                 session("mbk", $mbkstr."&nbsp;&nbsp;".session("mbk"));
                                 session("mbkcheck",$Passwordblock->where("UserID=".$UserID)->getField($mbkstr).session("mbkcheck"));
                                 
                                 exit("mbk");
         
         
         
                                        //////////////////////////////////////////
                                    }else{
                                        if($this->_post("mbk") == session("mbkcheck")){
                                            session("UserName",$UserName);
                                            session("UserType",$User_Type);
                                            session("UserID",$UserID);
                                            exit("ok");
                                        }else{
                                            session('mbk',null); 
                                            session('mbkcheck',null); 
                                           //session(null);
                                            exit("您输入的密保卡号不对！");
                                        }
                                    }
                                
                            }else{
                                
                            session("UserName",$UserName);
                            session("UserType",$User_Type);
                            session("UserID",$UserID);
                            //exit(session('UserName'));
                            exit("ok");
                            }
						}
					}
				}else{
					exit("账号或密码不正确！");
				}
			}
		}
	}
	
	
	public function mbkshow(){
        
        $this->display();
    }
    
    public function gbmbk(){
         session('mbk',null); 
         session('mbkcheck',null);
         exit(""); 
    }
    
    public function mmzh(){
        
        $this->display();
        
    }
    
    public function zhdlmmajax(){
        
        $UserName = $this->_post("UserName");
        
        $AffirmTitle = $this->_post("AffirmTitle");
        
        $AffirmAnswer = $this->_post("AffirmAnswer");
        
        $User = M("User");
        
        $UserID = $User->where("UserName='".$UserName."'")->getField("id");
        
        if(!$UserID){
            exit("账号不存在！");
        }else{
            
            $Usersafetyinformation = M("Usersafetyinformation"); 
            
            $list = $Usersafetyinformation->where("AffirmTitle='".$AffirmTitle."' and AffirmAnswer = '".$AffirmAnswer."'")->select();
            
            if(!$list){
                exit("密码提问或回答错误！");
            }else{
                exit("ok");
            }

        }
        
    }
    
    public function jhzhajax(){
        
        $UserName = $this->_post("UserName");
        
        $verify = $this->_post("verify");
        
        
        $User = M("User");
        
        $UserID = $User->where("UserName='".$UserName."'")->getField("id");
        
        if(!$UserID){
            exit("账号不存在！");
        }else{
            
            $status = $User->where("UserName='".$UserName."'")->getField("status");
            if($status == 1){
                exit("账号已激活！");
            }else{
                 if(md5($verify) != session("verify")){
                    exit("验证码错误！");
                }else{
                    
                    /////////////////////////////////////////////////////////////////////////////////////////////
                    $Email = new EmailAction();  
                     $Emailname = $User->where("id = ".$UserID)->getField("UserName");
                      $activate = $User->where("id = ".$UserID)->getField("activate");                   
                      
                      $EmailHTML = "亲爱的会员：".$Emailname."您好！ <br>感谢您注册".C("WEB_NAME")."账户！<br>您现在可以激活您的".C("WEB_NAME")."账户，激活成功后，您可以使用".C("WEB_NAME")."提供的各种支付服务。 <br><a href='http://". C("WEB_URL") ."/Index_Activate_id_".$UserID."_activate_".$activate.".html'>点此激活".C("WEB_NAME")."账户 </a><br>如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：<br>http://". C("WEB_URL") ."/Index_Activate_id_".$id."_activate_".$key.".html<br>此为系统邮件，请勿回复<br>请保管好您的邮箱，避免".C("WEB_NAME")."账户被他人盗用<br>如有任何疑问，可查看 ".C("WEB_NAME")."相关规则，".C("WEB_NAME")."网站访问 http://". C("WEB_URL")."/<br>Copyright ".C("WEB_NAME")." 2013 All Right Reserved";
                      $ReturnEamil = $Email->SendEmail($Emailname,C("WEB_NAME")."账户激活邮件！",$EmailHTML); 
                      
                      if($ReturnEamil == "1"){
                        exit("ok");
                      }else{
                        exit("处理失败，请稍后重试！");
                      }  
                    ////////////////////////////////////////////////////////////////////////////////////////////
                   
                }
            }

        }
    }
    
    public function zhdlmm(){
        
         $UserName = $this->_post("UserName");
        
        $AffirmTitle = $this->_post("AffirmTitle");
        
        $AffirmAnswer = $this->_post("AffirmAnswer");
        
        $User = M("User");
        
        $UserID = $User->where("UserName='".$UserName."'")->getField("id");
        
        if(!$UserID){
            
          $this->assign("msgTitle","");
          $this->assign("message","账号不存在！");
          $this->assign("waitSecond",3);
          $this->assign("jumpUrl","/Index_mmzh.html");
          $this->display("success");
          
        }else{
            
            $Usersafetyinformation = M("Usersafetyinformation"); 
            
            $list = $Usersafetyinformation->where("AffirmTitle='".$AffirmTitle."' and AffirmAnswer = '".$AffirmAnswer."'")->select();
            
            if(!$list){
                
                 $this->assign("msgTitle","");
                  $this->assign("message","密码提问或回答错误！");
                  $this->assign("waitSecond",3);
                  $this->assign("jumpUrl","/Index_mmzh.html");
                  $this->display("success");
                
            }else{
                ////////////////////////////////////////////////////////////////
                 $Email = new EmailAction();
          
                  
                  $User = M("User");
                  $rand = rand(100000,999999);
                  
                      
                  $Emailname = $User->where("id = ".$UserID)->getField("UserName");
                  $a = $a."------".$Emailname;
                  $EmailHTML = "您".C("WEB_NAME")."新的登录密码是：<b>".$rand."</b>";
                  $ReturnEamil = $Email->SendEmail($Emailname,"您".C("WEB_NAME")."新的登录密码",$EmailHTML);
                  if($ReturnEamil == "1"){
                  
                  $data["LoginPassWord"] = md5($rand);
                  $User->where("id = ".$UserID)->save($data); 
                      
                  $this->assign("msgTitle","");
                  $this->assign("message","新登录密码已经发送到您的邮箱内，请注意查收！");
                  $this->assign("waitSecond",3);
                  $this->assign("jumpUrl","/");
                  $this->display("success");
                      
                  }else{
                    $this->assign("msgTitle","");
                  $this->assign("message","操作失败，请稍后再试！");
                  $this->assign("waitSecond",3);
                  $this->assign("jumpUrl","/");
                  $this->display("success");
                  }
                ///////////////////////////////////////////////////////////////
            }

        }
        
        
    }
    
    public function denglu(){
        
        $this->display();
        
    }
    
    public function ajaxreturn(){
        
        $ip = $this->GetIp();
        if($ip != "116.255.131.216"){
            exit("请不要非法访问！");
        }
        $this->assign("ip",$ip);
        $this->display();
    }
    
    public function ajaxreturnone(){
        
        $ip = $this->GetIp();
        if($ip != "116.255.131.216"){
            exit("请不要非法访问！");
        }
        $this->assign("ip",$ip);
        $this->display();
    }
    
    public function geturldata(){
        
        $Ordertz = M("Ordertz");
        $type = $this->_post("type");
        $list = "";
        if($type == 1){
          $list = $Ordertz->where("Sjt_num > 0 and Sjt_num < 5 and success = 0")->order("id desc")->limit("0,1")->select();  
        }else{
          $list = $Ordertz->where("Sjt_num = 0 and success = 0")->order("id desc")->limit("0,1")->select();  
        }
        
        $urlname = "";  //提交址地
        $datastr = "";  //提交数据
        $TransID = "";  //订单编号
        
        if($list == NULL || $list == ""){
            
            exit("");
            
        }else{
            
           foreach($list as $row){
                $urlname = $row["Sjt_urlname"];
                $datastr = "Sjt_MerchantID=".$row["Sjt_MerchantID"]."&Sjt_UserName=".$row["Sjt_UserName"]."&Sjt_TransID=".$row["Sjt_TransID"]."&Sjt_Return=".$row["Sjt_Return"]."&Sjt_Error=".$row["Sjt_Error"]."&Sjt_factMoney=".$row["Sjt_factMoney"]."&Sjt_SuccTime=".$row["Sjt_SuccTime"]."&Sjt_Sign=".$row["Sjt_Sign"]."&Sjt_BType=2";
                $TransID = $row["Sjt_TransID"];
                
           }
           
           exit($urlname."^".$datastr."^".$TransID);
           
        }
        
        
        
    }
    
    public function addnumber(){
        
        $TransID = $this->_post("TransID");
        $Ordertz = M("Ordertz");
        $Sjt_num = $Ordertz->where("Sjt_TransID = '".$TransID."'")->getField("Sjt_num");
        $data["Sjt_num"] = $Sjt_num + 1;
        $Ordertz->where("Sjt_TransID = '".$TransID."'")->save($data);
        
    }
    
    public function ordersuccess(){
        $TransID = $this->_post("TransID");
        $Ordertz = M("Ordertz");
        $data["success"] = 1;
        $Ordertz->where("Sjt_TransID = '".$TransID."'")->save($data);
    }
    
    public function tzajax($TransID){
        
        //$TransID = $this->_post("TransID");
        $Ordertz = M("Ordertz");
        $list = $Ordertz->where("Sjt_TransID = '".$TransID."'")->select();
        $urlname = "";  //提交址地
        $datastr = "";  //提交数据
        $TransID = "";  //订单编号
         foreach($list as $row){
                $urlname = $row["Sjt_urlname"];
                $datastr = "Sjt_MerchantID=".$row["Sjt_MerchantID"]."&Sjt_Username=".$row["Sjt_UserName"]."&Sjt_TransID=".$row["Sjt_TransID"]."&Sjt_Return=".$row["Sjt_Return"]."&Sjt_Error=".$row["Sjt_Error"]."&Sjt_factMoney=".$row["Sjt_factMoney"]."&Sjt_SuccTime=".$row["Sjt_SuccTime"]."&Sjt_Sign=".$row["Sjt_Sign"]."&Sjt_BType=2";
                $TransID = $row["Sjt_TransID"];
                
           }
         
         $tjurl = $urlname."?".$datastr; 
         //$tjurl = "http://cs.sj887.com/baofu/return_url.php?".$datastr;
$contents = fopen($tjurl,"r"); 
$contents=fread($contents,4096); 
         //echo $contents;
         if($contents == "ok"){
             $data["success"] = 1;
             $Ordertz->where("Sjt_TransID = '".$TransID."'")->save($data);
         }
 
    }
    
     private function GetIp(){
            $unknown = 'unknown';  
if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown) ) {  
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
} elseif ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown) ) {  
$ip = $_SERVER['REMOTE_ADDR'];  }

          return $ip;
        }
        
        public function regstep(){
            if(!$_GET["regtype"] || intval($_GET["regtype"]) == 1){
              $shh = intval($_GET["regcode"])+10000;
            }
            $this->assign("shh",$shh);
            $this->display();
        }
	
    
    public function mypay(){
       
      $ActionName = $this->_request("ActionName");
      
      $User = M("User");
      $UserID = $User->where("mypayurlname='".$ActionName."'")->getField("id");  
      $UserName = $User->where("mypayurlname='".$ActionName."'")->getField("UserName");
      $Userapiinformation = M("Userapiinformation");
      $keykey = $Userapiinformation->where("UserID=".$UserID)->getField("key");   
          
      $p0_Cmd = "Buy";
      
      $p1_MerId = intval($UserID)+10000;
      
      $p2_Order = "";
      
      $p3_Amt = $this->_request("money");
	  if($p3_Amt < 1 || intval($p3_Amt) != $p3_Amt){
		 exit("error money"); 
	  }
      
      $p4_Cur = "CNY";
      
      $p5_Pid = "ylzf";
      
      $p6_Pcat = "703AC229E8E18062F3B474654E9D476C";
      
      $p7_Pdesc = $this->TransCode($this->_request("fksm"));
      
      $p8_Url = "http://".C("WEB_URL")."/".$ActionName;  //跳转地址
      
      $p9_SAF = "0";
      
      $pa_MP = "0";
      
      $pd_FrpId = $this->_request("pd_FrpId");
      
      $pr_NeedResponse = "1";
      
      $Sjt_Paytype = $this->_request("Sjt_Paytype");
      
      $Sjt_ProudctID = $this->_request("Sjt_ProudctID");      //卡面额
      
      $Sjt_CardNumber = $this->_request("Sjt_CardNumber");    //卡号
      
      $Sjt_CardPassword = $this->_request("Sjt_CardPassword");  //卡密
      
      $Sjt_UserName = $UserName;
      
      $key = $keykey;   //密钥
      
      $hmacstr = $p0_Cmd.$p1_MerId.$p2_Order.$p3_Amt.$p4_Cur.$p5_Pid.$p6_Pcat.$p7_Pdesc.$p8_Url.$p9_SAF.$pa_MP.$pd_FrpId.$pr_NeedResponse.$key;
      
      $hmac = MD5($hmacstr);
      
      $tjurl = "http://".C("WEB_URL")."/Payapi_Index_Pay.html";
      
      if($Sjt_Paytype == "b"){   //如果是网银
  ?>
 <form name="Form1" id="Form1" method="post" action="<?php echo $tjurl; ?>">
<input type="hidden" name="p0_Cmd" value="Buy">
<input type="hidden" name="p1_MerId" value="<?php echo $p1_MerId; ?>">
<input type="hidden" name="p2_Order" value="<?php echo $p2_Order; ?>">
<input type="hidden" name="p3_Amt" value="<?php echo $p3_Amt; ?>">
<input type="hidden" name="p4_Cur" value="CNY">
<input type="hidden" name="p5_Pid" value="<?php echo $p5_Pid; ?>">
<input type="hidden" name="p6_Pcat" value="<?php echo $p6_Pcat; ?>">
<input type="hidden" name="p7_Pdesc" value="<?php echo $p7_Pdesc; ?>">
<input type="hidden" name="p8_Url" value="<?php echo  $p8_Url?>">
<input type="hidden" name="p9_SAF" value="0">
<input type="hidden" name="pa_MP" value="<?php echo $pa_MP; ?>">
<input type="hidden" name="pd_FrpId" value="<?php echo $pd_FrpId; ?>">
<input type="hidden" name="pr_NeedResponse" value="1">
<input type="hidden" name="Sjt_Paytype" value="b">
<input type="hidden" name="Sjt_UserName" value="<?php echo $Sjt_UserName; ?>" >
<input type="hidden" name="hmac" value="<?php echo $hmac; ?>">   

</form>
Loading......
<script type="text/javascript">
document.forms["Form1"].submit();
</script>
 <?php        
      }else{
           if($Sjt_Paytype == "g"){   //如果是点卡
               $urlStr = "http://".C("WEB_URL")."/Payapi_Index_Pay.html?p0_Cmd=".$p0_Cmd."&p1_MerId=".$p1_MerId."&p2_Order=".$p2_Order."&p3_Amt=".$p3_Amt."&p4_Cur=".$p4_Cur."&p5_Pid=".$p5_Pid."&p6_Pcat=".$p6_Pcat."&p7_Pdesc=".$p7_Pdesc."&p8_Url=".$p8_Url."&p9_SAF=".$p9_SAF."&pa_MP=".$pa_MP."&pd_FrpId=".$pd_FrpId."&Sjt_CardNumber=".$Sjt_CardNumber."&Sjt_CardPassword=".$Sjt_CardPassword."&Sjt_ProudctID=".$Sjt_ProudctID."&pr_NeedResponse=".$pr_NeedResponse."&Sjt_Paytype=".$Sjt_Paytype."&Sjt_UserName=".$Sjt_UserName."&hmac=".$hmac;
               $contents = fopen($urlStr,"r"); 
               $contents=fread($contents,4096); 
               $a = split("&",$contents);
               header("Content-Type:text/html; charset=utf-8"); 
               if($a[0] == "ok"){
                   echo "检验成功！正在获取充值状态......<br>";  
                  //////////////////////////////////////////////////////
                 $Sign = MD5($a[1].$key);
                 $urlStr = "http://pay.0txw.com/pay/Payapi_Pay_SelectOK.html?Sjt_TransID=".$a[1]."&Sign=".$Sign;
                 $contents = fopen($urlStr,"r"); 
                 $contents=fread($contents,4096);
                 if($contents == "ok"){
                     echo "充值成功！";
                 }else{
                    exit("充值失败，您的充值卡可能已经使用过！如果您确认充值卡没有使用过，请稍候重试！");   
                 }
                 ///////////////////////////////////////////////////// 
               }else{
                    exit($a[0]);
               }
           }
      }
 
        
    }
    
    public function mypayyzm(){
              if(md5($_POST["xym"]) != session("verify")){
                exit("no");
              }else{
                exit("ok");  
              }
    }
    
    
     public function Merchanturl(){
        header("Content-Type:text/html; charset=utf-8");
         $Sjt_MerchantID = $_REQUEST["Sjt_MerchantID"];
    $Sjt_Username = $_REQUEST["Sjt_Username"];
    $Sjt_TransID = $_REQUEST["Sjt_TransID"];
    $Sjt_Return = $_REQUEST["Sjt_Return"];
    $Sjt_Error = $_REQUEST["Sjt_Error"];
    $Sjt_factMoney = $_REQUEST["Sjt_factMoney"];
    $Sjt_SuccTime = $_REQUEST["Sjt_SuccTime"];
    $Sjt_Sign = $_REQUEST["Sjt_Sign"];
    $Sjt_BType = $_REQUEST["Sjt_BType"];
    $Userapiinformation = D("Userapiinformation");
    $key = $Userapiinformation->where("UserID=".$Sjt_MerchantID)->getField("Key");
    
    $Sign = md5($Sjt_MerchantID.$Sjt_Username.$Sjt_TransID.$Sjt_Return.$Sjt_Error.$Sjt_factMoney.$Sjt_SuccTime."1".$key);
    
    if($Sjt_Sign == $Sign){
        
       // echo "充值成功！<br>";
      //  echo "订单号：".$Sjt_TransID."<br>";
      //  echo "充值时间：".$Sjt_SuccTime;
        
        $this->assign("Sjt_TransID",$Sjt_TransID);
        $this->assign("Sjt_SuccTime",date("Y-m-d H:i:s"));
        $this->assign("Sjt_factMoney",$Sjt_factMoney);
        $this->display();
        
    }else{
      
        echo $Sjt_Error."<br>";
        echo $Sjt_Sign."<br>";
        echo $Sign."<br>";
        
        echo $Sjt_MerchantID."<br>";
        echo $Sjt_Username."<br>";
        echo $Sjt_TransID."<br>";
        echo $Sjt_Return."<br>";
        echo $Sjt_Error."<br>";
        echo $Sjt_factMoney."<br>";
        echo $Sjt_SuccTime."<br>";
        echo $key."<br>";
        echo $Sjt_BType;
        
        
    }
    }
    
    public function returnurl(){
        
        $Sjt_MerchantID = $_REQUEST["Sjt_MerchantID"];
        $Sjt_Username = $_REQUEST["Sjt_Username"];
        $Sjt_TransID = $_REQUEST["Sjt_TransID"];
        $Sjt_Return = $_REQUEST["Sjt_Return"];
        $Sjt_Error = $_REQUEST["Sjt_Error"];
        $Sjt_factMoney = $_REQUEST["Sjt_factMoney"];
        $Sjt_SuccTime = $_REQUEST["Sjt_SuccTime"];
        $Sjt_Sign = $_REQUEST["Sjt_Sign"];
         $Sjt_BType = $_REQUEST["Sjt_BType"];
        $Userapiinformation = D("Userapiinformation");
        $key = $Userapiinformation->where("UserID=".session("UserID"))->getField("Key");
        
        
        $Sign = md5($Sjt_MerchantID.$Sjt_Username.$Sjt_TransID.$Sjt_Return.$Sjt_Error.$Sjt_factMoney.$Sjt_SuccTime.$Sjt_BType.$key);
        
        if($Sjt_Sign == $Sign){
            
            //处理自己的业务逻辑
            
            echo "ok";
            
        }else{
            
            echo "no";  //不是盛捷通提交过来的通知
            
        }
        
        
    }
    
     private function TransCode($Code){     //中文转码
           return iconv("GBK", "UTF-8", $Code);
      }
    
    
    
}
