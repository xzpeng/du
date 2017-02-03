<?php
  class ShangHuAction extends Action{
      
       public function __construct(){
         parent::__construct();
         if(!session("?SjtUserName") || !session("?SjtUserType")){
            
           $this->display("Index:login");
            exit;
         }
            
    }
      
      
      public function listuser(){
   
        $wherestr = "";
        if($this->_get("SearchContent")){
            $wherestr = $wherestr." (Shh like '%".(intval($this->_get("SearchContent"))-10000)."%' or UserName like '%".$this->_get("SearchContent")."%' or qq like '%".$this->_get("SearchContent")."%' or MobilePhone like '%".$this->_get("SearchContent")."%' or Compellation like '".$this->_get("SearchContent")."')";
        }else{
            $wherestr = $wherestr."1=1";
        }
        
        if($this->_get("UserType") != "" && $this->_get("UserType") != Null){
            $Sjapi = M("Sjapi");
            $PayBank = $Sjapi->where("apiname='".$this->_get("UserType")."'")->getField("id");
            $System = M("System");
            $DefaultBank = $System->where("UserID=0")->getField("DefaultBank");
            if($PayBank == $DefaultBank){
               $wherestr = $wherestr." and (PayBank = ".$PayBank." or PayBank = 0)";
            }else{
                $wherestr = $wherestr." and PayBank = ".$PayBank;
            }
            
            //exit($wherestr);
         
        }else{
            $wherestr = $wherestr." and 1=1";
              //exit($wherestr);
        }
        
        if($this->_get("Zt") != NULL){
            $wherestr = $wherestr." and Zt = ".$this->_get("Zt");
        }else{
            $wherestr = $wherestr." and 1=1";
        }
        
        if($this->_get("status") != NULL){
            $wherestr = $wherestr." and status = ".$this->_get("status");
        }else{
            $wherestr = $wherestr." and 1=1";
        }
        
        if($this->_get("Userlx") != NULL){
            if($this->_get("Userlx") == 5){
               $wherestr = $wherestr." and UserType = 5";
            }else{
                $wherestr = $wherestr." and UserType <> 5";
            }
           
        }else{
            $wherestr = $wherestr." and 1=1";
        }
        
          
        $Listuser = M('Listuser'); 
        import("ORG.Util.Page");       //导入分页类 
			
			
        $count = $Listuser->where($wherestr)->count();    //计算总数 
        $p = new Page($count, 10); 
        
        
        
        $list = $Listuser->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('Zt desc,Shh desc')->select(); 
        //$p->firstRow 当前页开始记录的下标，$p->listRows 每页显示记录数 
        //一般定义分页样式 通过分页对象的setConfig定义其config属性； 
        /* 
          默认值为$config = array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页', 
          'theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%'); 
          修改显示的元素的话设置theme就行了，可对其元素加class 
         */ 
        $p->setConfig('header', '个商户'); 
       // $p->setConfig('prev', "<"); 
       // $p->setConfig('next', '>'); 
       // $p->setConfig('first', '<<'); 
      //  $p->setConfig('last', '>>'); 
        $page = $p->show();            //分页的导航条的输出变量 
        $page = str_replace("/index.php","",$page);
       // $this->assign("wherestr",$wherestr);
       
       $Sjapi = M("Sjapi");
       $listtongdao = $Sjapi->select();
       
        $this->assign("page", $page); 
        $this->assign("listtongdao",$listtongdao);
        $this->assign("list", $list); //数据循环变量 
        $this->display(); 

      }
      
      
      public function ShowShenhe(){
          
          $UserID = $this->_get("UserID");
          
          $Userapiinformation = M("Userapiinformation");
          
          $list = $Userapiinformation->where("UserID = ".$UserID)->select();
          
          $this->assign("list",$list);
          $this->assign("UserID",$UserID);
          $this->display();

      }
      
      
      public function Dongjie(){
          $status = $this->_get("status");
          $UserIDList = $this->_post("UserIDList");
          if($status == Null){
              exit("s");
          }else{
              
              $User = M("User");
              
              $data["status"] = $status;
              
              if($User->where("id in (".$UserIDList.")")->save($data)){
                  exit("ok");
              }
          
          }
      }
      
      public function Deletesh(){
          $UserIDList = $this->_post("UserIDList");
          
          $User = M("User");
          
          if($User->where("id in (".$UserIDList.")")->delete()){
              
              $Userapiinformation = M("Userapiinformation");
              
              if($Userapiinformation->where("UserID in (".$UserIDList.")")->delete()){
                  
                  $Userbasicinformation = M("Userbasicinformation");
                  
                  if($Userbasicinformation->where("UserID in (".$UserIDList.")")->delete()){
                   
                        $Usersafetyinformation = M("Usersafetyinformation"); 
                        
                   if($Usersafetyinformation->where("UserID in (".$UserIDList.")")->delete()){
                      // exit("ok");
                   }else{
                      // exit("no");
                   }
                      
                  }else{
                      //exit("no");
                  }
                  
              }else{
                  //exit("no");
                  
              }
          }else{
              //exit("no");
          }
          
          exit("ok");
      }
      
      
      public function DengluEmail(){
          $Email = new EmailAction();
          
          $UserID = $this->_post("UserID");
          
          $User = M("User");
       //   $rand = rand(100000,999999);
       $rand  = "123456";
          $data["LoginPassWord"] = md5($rand);
          $User->where("id = ".$UserID)->save($data);
              
          /*
          $Emailname = $User->where("id = ".$UserID)->getField("UserName");
          $a = $a."------".$Emailname;
          $EmailHTML = "您".C("WEB_NAME")."新的登录密码是：<b>".$rand."</b>";
          $ReturnEamil = $Email->SendEmail($Emailname,"您".C("WEB_NAME")."新的登录密码",$EmailHTML);
          */
          //if($ReturnEamil == "1"){
            exit("ok");
          //}else{
            //exit($Emailname);
         // }
        
          
      }
      
      
      public function PayEmail(){
          
          $Email = new EmailAction();
          
          $UserID = $this->_post("UserID");
          
          $Usersafetyinformation = M("Usersafetyinformation");
        // $rand = rand(100000,999999);
        $rand = "123456";
          $data["PayPassWord"] = md5($rand);
          $Usersafetyinformation->where("UserID = ".$UserID)->save($data);

          /*
          $User = M("User");    
          $Emailname = $User->where("id = ".$UserID)->getField("UserName");
          $a = $a."------".$Emailname;
          $EmailHTML = "您".C("WEB_NAME")."新的支付密码是：<b>".$rand."</b>";
          $ReturnEamil = $Email->SendEmail($Emailname,"您".C("WEB_NAME")."新的支付密码",$EmailHTML);
          */
      //    if($ReturnEamil == "1"){
            exit("ok");
        //  }else{
           // exit($Emailname);
       //   }  
      }
      
      
      public function KaiTongT0(){
          $UserIDList = $this->_post("UserIDList");
          
          $Usersafetyinformation = M("Usersafetyinformation");
              
          $data["t0"] = 1;
              
          $Usersafetyinformation->where("UserID in (".$UserIDList.")")->save($data);
            exit("ok");
          
      }
      
      public function KaiTongT1(){
          $UserIDList = $this->_post("UserIDList");
          
          $Usersafetyinformation = M("Usersafetyinformation");
              
          $data["t0"] = 0;
              
          $Usersafetyinformation->where("UserID in (".$UserIDList.")")->save($data);
            exit("ok");
          
      }
      
      
     public function PayBank(){
         
         $UserIDList = $this->_get("UserIDList");
         
         $Listuser  = M("Listuser");
         
         $list = $Listuser->where("Shh in (".$UserIDList.")")->select();
         
         $Sjapi = M("Sjapi");
         
         $banklist = $Sjapi->select();
         
         $this->assign("banklist",$banklist);
         $this->assign("list",$list);
         $this->assign("UserIDList",$UserIDList);
         
         $this->display();
     }
     
     public function Fstz(){
         $UserIDList = $this->_get("UserIDList");
         
         $Listuser  = M("Listuser");
         
         $list = $Listuser->where("Shh in (".$UserIDList.")")->select();
         
         //$Sjapi = M("Sjapi");
         
         //$banklist = $Sjapi->select();
         
        // $this->assign("banklist",$banklist);
         $this->assign("list",$list);
         $this->assign("UserIDList",$UserIDList);
         
         $this->display();
     }
     
     public function Sjapi(){
         
         $id = $this->_post("id");
         
         if($id == NULL){
             exit("NULL");
         }else{
             $Sjapi = M("Sjapi");
             $myname = $Sjapi->where("id = ".$id)->getField("myname");
             exit($myname);
         }
     }
     
     public function Plbank(){
         $UserIDList = $this->_post("UserIDList");
         $PayBank = $this->_post("PayBank");
         
         $Usersafetyinformation = M("Usersafetyinformation");
         
         $data["PayBank"] = $PayBank;
         
         $Usersafetyinformation->where("UserID in (".$UserIDList.")")->save($data);
         
         exit("ok");
        
       // exit("UserID in ("+$UserIDList+")");

     }
     
     public function Pltongzhi(){
          $UserIDList = $this->_post("UserIDList");
          $Title = $this->_post("Title");
          $Content = $this->_post("TzContent");
          $Content = str_replace("\r\n","<br>",$Content);
          $list = split(",",$UserIDList);
          $Tongzhi = M("Tongzhi");
          foreach($list as $key=>$value){
              $data["Title"] = $Title;
              $data["Content"] = $Content;
              $data["datetime"] = date("Y-m-d H:i:s");
              $data["UserID"] = $value;
              $Tongzhi->add($data);
          }
          
           exit("ok");
     }
     
     
     public function ShowEdit(){
         
         $UserID = $this->_get("UserID");
         
         $Userbasicinformation = M("Userbasicinformation");
         
         $basiclist = $Userbasicinformation->where("UserID=".$UserID)->select();
         
         $Userapiinformation = M("Userapiinformation");
         
         $apilist = $Userapiinformation->where("UserID=".$UserID)->select();
         
         $User = M("User");
         
         $UserType = $User->where("id=".$UserID)->getField("UserType");
         
         $SjUserID = $User->where("id=".$UserID)->getField("SjUserID");
         
         $sjusername = $User->where("id=".$SjUserID)->getField("UserName");
         
         $sjname = $Userbasicinformation->where("UserID=".$SjUserID)->getField("Compellation");
         
         if($UserType == 5){
             $Userlx = 5;
         }else{
             $Userlx = 1;
         }
         
         $this->assign("Userlx",$Userlx);
         $this->assign("sjusername",$sjusername);
         $this->assign("sjname",$sjname);
         $this->assign("basiclist",$basiclist);
         $this->assign("apilist",$apilist);
         
         $this->display();
           
     }
     
     public function EditSjdl(){
         $sjusername = $this->_post("sjusername");
         $UserID = $this->_post("UserID");
         $User = M("User");
         if(trim($sjusername) == ""){
             $sjUserID = 0;
             $data["SjUserID"] = $sjUserID;
             $User->where("id=".$UserID)->save($data);
             exit("ok");
         }else{
             
             $sjUserID = $User->where("UserName='".$sjusername."'")->getField("id"); 
             if(!$sjUserID || $sjUserID == $UserID){
                 eixt("no");
             }else{
                 $data["SjUserID"] = $sjUserID;
                 $User->where("id=".$UserID)->save($data);
                  exit("ok"); 
             }
         }
        
         
         
     }
     
     public function Userlx(){
         $User = M("User");
         $UserID  = $this->_post("UserID");
         $Userlx = $this->_post("Userlx");
         
         $data["UserType"] = $Userlx;
         
         $User->where("id=".$UserID)->save($data);
         
         exit("ok");
     }
     
     
     public function Userbasicinformationedit(){
         
         $Userbasicinformation = M("Userbasicinformation");
         
         $data["Compellation"] = $this->_post("Compellation");
         $data["MobilePhone"] = $this->_post("MobilePhone");
         $data["Tel"] = $this->_post("Tel");
         $data["IdentificationCard"] = $this->_post("IdentificationCard");
         $data["Address"] = $this->_post("Address");
         $data["Province"] = $this->_post("province");
         $data["City"] = $this->_post("city");
         $data["qq"] = $this->_post("qq");
         
         $Userbasicinformation->where("UserID=".$this->_post("UserID"))->save($data);
         
         exit("ok");
         
     }
     
     
     public function Userapiinformationedit(){
         
         $Userapiinformation = M("Userapiinformation");
         
         $data["CompanyName"] = $this->_post("CompanyName");
         $data["WebsiteName"] = $this->_post("WebsiteName");
         $data["WebsiteUrl"] = $this->_post("WebsiteUrl");
        
         $Userapiinformation->where("UserID=".$this->_post("UserID"))->save($data);
         
         exit("ok");
         
     }
     
     //////////////////////////////////////////////////////////////////////////////////
     public function listuserwjh(){
         
         $wherestr = "status=0";
         $User = M('User'); 
         import("ORG.Util.Page");       //导入分页类 
         $count = $User->where($wherestr)->count();    //计算总数 
         $p = new Page($count, 10); 
         
         $list = $User->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('RegDate desc')->select(); 
        $p->setConfig('header', '个商户'); 
        $page = $p->show();            //分页的导航条的输出变量 
        $page = str_replace("/index.php","",$page);
        $this->assign("page", $page); 
        $this->assign("list", $list); //数据循环变量 
        $this->display(); 
     }
     
     
     
     public function JiHuoEmail(){
         
          $Email = new EmailAction();
          
          $UserID = $this->_post("UserID");
          
          $User = M("User");    
          $Emailname = $User->where("id = ".$UserID)->getField("UserName");
          $activate = $User->where("id = ".$UserID)->getField("activate");

           $EmailHTML = "亲爱的会员：".$Emailname."您好！ <br>感谢您注册".C("WEB_NAME")."账户！<br>您现在可以激活您的".C("WEB_NAME")."账户，激活成功后，您可以使用".C("WEB_NAME")."提供的各种支付服务。 <br><a href='http://". C("WEB_URL") ."/Index_Activate_id_".$UserID."_activate_".$activate.".html'>点此激活".C("WEB_NAME")."账户 </a><br>如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：<br>http://". C("WEB_URL") ."/Index_Activate_id_".$id."_activate_".$key.".html<br>此为系统邮件，请勿回复<br>请保管好您的邮箱，避免".C("WEB_NAME")."账户被他人盗用<br>如有任何疑问，可查看 ".C("WEB_NAME")."相关规则，".C("WEB_NAME")."网站访问 http://". C("WEB_URL")."/<br>Copyright ".C("WEB_NAME")." 2013 All Right Reserved";
           $ReturnEamil = $Email->SendEmail($Emailname,C("WEB_NAME")."账户激活邮件！",$EmailHTML); 
          
          if($ReturnEamil == "1"){
            exit("ok");
          }else{
            exit($Emailname);
          }  
         
     }
     
     public function Sxf(){   //单独设置手续费
         
         $UserID = $this->_get("UserID");
         
         $User = M("User");
         
         $UserName = $User->where("id=".$UserID)->getField("UserName");
         
         $Paycost = M("Paycost");
         
         $listpaycost = $Paycost->where("UserID=".$UserID)->select();
         
         if(!$listpaycost){
             
             $data["UserID"] = $UserID;
             
             $Paycost->add($data);
             
             $listpaycost = $Paycost->where("UserID=".$UserID)->select();
         }
         
         $this->assign("UserName",$UserName);
         $this->assign("listpaycost",$listpaycost);
         $this->display();
     }
     
     public function Sxfs(){   //单独设置手续费
         
         $UserID = $this->_get("UserID");
         
         $User = M("User");
         
         $UserName = $User->where("id=".$UserID)->getField("UserName");
         $tongdao = $User->where("id=".$UserID)->getField("tongdao");
         $sjapi = M("Sjapi");
         
         $listpaycost = $sjapi->where("xz=1")->select();

         $this->assign("UserName",$UserName);
         $this->assign("UserID",$UserID);
         $this->assign("tongdao",$tongdao);
         $this->assign("listpaycost",$listpaycost);
         $this->display();
     }
      public function czfledits(){   //修改冲值费率
            $Paycost = M("User");
			if(is_array($_POST['tongdao'])){
			foreach ($_POST['tongdao'] as $k=>$v){
			$td.=$v."|";
			}}
            $data["tongdao"] = $td;
            $Paycost->where("id=".$this->_post("UserID"))->save($data);
            
          $this->assign("msgTitle","");
          $this->assign("message","修改成功！");
          $this->assign("waitSecond",3);
          $this->assign("jumpUrl","/SjtAdminSjt_ShangHu_Sxfs_UserID_".$this->_post("UserID").".html");
          $this->display("success");
        }
      public function czfledit(){   //修改冲值费率
            
            $Paycost = M("Paycost");
            
            $data["thykt"] = $this->_post("thykt");
            $data["wy"] = $this->_post("wy");
            $data["wmykt"] = $this->_post("wmykt");
            $data["wyykt"] = $this->_post("wyykt");
            $data["ltczk"] = $this->_post("ltczk");
            $data["jyykt"] = $this->_post("jyykt");
            $data["qqczk"] = $this->_post("qqczk");
            $data["shykt"] = $this->_post("shykt");
            $data["ztyxk"] = $this->_post("ztyxk");
            $data["jwykt"] = $this->_post("jwykt");
            $data["sdykt"] = $this->_post("sdykt");
            $data["qgszx"] = $this->_post("qgszx");
            $data["txykt"] = $this->_post("txykt");
            $data["dxczk"] = $this->_post("dxczk");
            $data["gyykt"] = $this->_post("gyykt");
            $data["zyykt"] = $this->_post("zyykt");
            $data["yddx"] = $this->_post("yddx");
            $data["ltdx"] = $this->_post("ltdx");
            $data["dxdx"] = $this->_post("dxdx");
            $data["nbzz"] = $this->_post("nbzz");
            
            $Paycost->where("id=".$this->_post("id"))->save($data);
            
          $this->assign("msgTitle","");
          $this->assign("message","修改成功！");
          $this->assign("waitSecond",3);
          $this->assign("jumpUrl","/SjtAdminSjt_ShangHu_Sxf_UserID_".$this->_post("UserID").".html");
          $this->display("success");
        }
        
        
        public function xgje(){
              
         $UserID = $this->_get("UserID");
         
         $User = M("User");
         
         $UserName = $User->where("id=".$UserID)->getField("UserName");
         
         $Money = M("Money");
         
         $UserMoney = $Money->where("UserID=".$UserID)->getField("money");
         
         $Paycost = M("Paycost");
         
         $listpaycost = $Paycost->where("UserID=".$UserID)->select();
         
         if(!$listpaycost){
             
             $data["UserID"] = $UserID;
             
             $Paycost->add($data);
             
             $listpaycost = $Paycost->where("UserID=".$UserID)->select();
         }
         
         $this->assign("UserName",$UserName);
         $this->assign("listpaycost",$listpaycost);
         $this->assign("Money",$UserMoney);
         $this->display();
        }
        
        
        public function xgjejj(){
            
            $jj = $this->_post("jj");
            
            $moneymoney = $this->_post("money");
            
            $content = $this->_post("content");
            
            $lr = $this->_post("lr");
            
            $Money = M("Money");
            
            $Adminmoney = M("Adminmoney");
            
            $data["UserID"] = $this->_post("UserID");
            $data["jj"] = $jj;
            $data["money"] = $moneymoney;
            $data["content"] = $content;
            $data["datetime"] = date("Y-m-d");
            $data["lr"] = $lr;
            
            $Adminmoney_id = $Adminmoney->add($data);
            
            if(!$Adminmoney_id){
                
               $this->assign("msgTitle","");
               $this->assign("message","修改失败，请稍后重试！");
               $this->assign("waitSecond",3);
               $this->assign("jumpUrl","/SjtAdminSjt_ShangHu_xgje_UserID_".$this->_post("UserID").".html");
               $this->display("success");
            }else{
                
               $ymoney = $Money->where("UserID=".$this->_post("UserID"))->getField("money");
               
               //$data["money"] = floatval($ymoney) +　floatval($moneymoney) * intval($jj); 
               
               $data["Money"] = $moneymoney * $jj + $ymoney;
               
               if($Money->where("UserID=".$this->_post("UserID"))->save($data)){
                   
                   $data["zt"] = 1;
                   
                   //$Adminmoney->where("UserID = ".$this->_post("UserID"))->save($data);
                    $Adminmoney->where("id = ".$Adminmoney_id)->save($data);
                    
                   ///////////////////////////////////////////////////////////
                   $Moneydb = M("Moneybd");
                   $data["UserID"] = $this->_post("UserID");
                   $data["money"] = $moneymoney * $jj;  //变动金额
                   $data["ymoney"] = $ymoney;    //原金额
                   $data["gmoney"] = $moneymoney * $jj + $ymoney;    //变动后金额
                   $data["datetime"] = date("Y-m-d H:i:s");
                   if($jj > 0){
                      $data["lx"] = 6; 
                   }else{
                      $data["lx"] = 5;  
                   }
                   $Moneydb->add($data);
                   //////////////////////////////////////////////////////////
                   
                   $this->assign("msgTitle","");
                   $this->assign("message","商户金额修改成功！");
                   $this->assign("waitSecond",3);
                   $this->assign("jumpUrl","/SjtAdminSjt_ShangHu_xgje_UserID_".$this->_post("UserID").".html");
                   $this->display("success");
               }else{
                   $this->assign("msgTitle","");
               $this->assign("message","修改失败，请稍后重试!！");
               $this->assign("waitSecond",3);
               $this->assign("jumpUrl","/SjtAdminSjt_ShangHu_xgje_UserID_".$this->_post("UserID").".html");
               $this->display("success");
               }
                
            }
            
            
        }
        
        
        public function tkyh(){
            
            $UserID = $this->_get("UserID");
            
            $User = M("User");
         
            $UserName = $User->where("id=".$UserID)->getField("UserName");
            
            $Bank = M("Bank");
            
            $list = $Bank->where("UserID=".$UserID)->select();
            
            $this->assign("list",$list);
            $this->assign("UserName",$UserName);
            
            $this->display();
        }
        
        
        public function tkyhedit(){
            
            $id = $this->_request("id");
            
            $UserID = $this->_request("UserID");
            
            $User = M("User");
         
            $UserName = $User->where("id=".$UserID)->getField("UserName");
            
            $Bank = M("Bank");
            
            $list = $Bank->where("id=".$id)->select();
            
            $this->assign("list",$list);
            
            $this->assign("UserName",$UserName);
            
            $this->display();
            
        }
        
        public function tkyheditedit(){
            
             $Bank = M("Bank");
             
             $Bank->create();
             
             $Bank->save();
             
              $this->assign("msgTitle","");
              $this->assign("message","提款银行修改成功！");
              $this->assign("waitSecond",3);
              $this->assign("jumpUrl","/SjtAdminSjt_ShangHu_tkyh_UserID_".$this->_get("UserID").".html");
                   $this->display("success");
             
            
        }
        
        public function Diaodan(){
            
            $UserID = $this->_get("UserID");
            
            $System = M("System");
            
            $list = $System->where("UserID=".$UserID)->select();
            
            if(!$list){
                
                $data["UserID"] = $UserID;
                
                if($System->add($data)){
                    $list = $System->where("UserID=".$UserID)->select();
                }
                
            }
            
            $this->assign("list",$list);
            
            $this->display();
            
        }
        
        public function Diaodanedit(){
            
            $UserID = $this->_post("UserID");
            
            $id = $this->_post("id");
            
            $System = M("System");
            
            $data["Diaodan_OnOff"] = $this->_post("Diaodan_OnOff");
            
            $data["Diaodan_Kdate"] = $this->_post("Diaodan_Kdate");
            
            $data["Diaodan_Sdate"] = $this->_post("Diaodan_Sdate");
            
            $data["Diaodan_Kmoney"] = $this->_post("Diaodan_Kmoney");
            
            $data["Diaodan_Smoney"] = $this->_post("Diaodan_Smoney");
            
            $data["Diaodan_Pinlv"] = $this->_post("Diaodan_Pinlv");
            
            $data["Diaodan_Type"] = $this->_post("Diaodan_Type");
            
            $data["Diaodan_huifu"] = $this->_post("Diaodan_huifu");
            
            $data["Diaodan_User_OnOff"] = $this->_post("Diaodan_User_OnOff");
            
            $System->where("id=".$id)->save($data);
            
            
            $this->assign("msgTitle","");
            $this->assign("message","修改成功！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","/SjtAdminSjt_ShangHu_Diaodan_UserID_".$UserID.".html");
            $this->display("success");
        }
        
        
         public function shjkshtg(){  //审核通过
           $Userapiinformation = D("Userapiinformation");
           $WebsiteUrl = $Userapiinformation->where("UserID=".$this->_post("UserID"))->getField("WebsiteUrl");
           $User = M("User");
           $UserName = $User->where("id = ".$this->_post("UserID"))->getField("UserName");
           $data["Zt"] = 2;
           $data["Key"] = md5($UserName.$WebsiteUrl);
           $list = $Userapiinformation->where("UserID=".$this->_post("UserID"))->save($data);
        if($list){
          
            echo "ok";
        }else{
          
           echo "no";
        }
    }
    
    public function dhsh(){  //打回审核
           $Userapiinformation = D("Userapiinformation");
           $WebsiteUrl = $Userapiinformation->where("UserID=".$this->_post("UserID"))->getField("WebsiteUrl");
           $User = M("User");
           $UserName = $User->where("id = ".$this->_post("UserID"))->getField("UserName");
           $data["Zt"] = 0;
           //$data["Key"] = md5($UserName.$WebsiteUrl);
           $list = $Userapiinformation->where("UserID=".$this->_post("UserID"))->save($data);
        if($list){
          
            echo "ok";
        }else{
          
           echo "no";
        }
    }
    
    public function jiechumbk(){
        
        $UserIDList = $this->_post("UserIDList");
        
        $User = M("User");
        
        $data["mbk"] = 0;
        
        $User->where("id in (".$UserIDList.")")->save($data);
        
        echo "ok";
    }
    
    
    public function zjbdjl(){   //资金变动记录
    
          $Moneybd = M("Moneybd");
          
          $wherestr = "1=1";
          
        $sq_date = $this->_request("sq_date");
        $sq_date_js = $this->_request("sq_date_js");
        $shbh = $this->_request("shbh");
        $lx = $this->_request("lx");
        $pagepage = $this->_request("pagepage");
        
        if($pagepage == "" || $pagepage == NULL){
            $pagepage = 10;
        }
        
         if($sq_date != "" && $sq_date != NULL){
            $wherestr = $wherestr." and DATEDIFF('".$sq_date."',datetime) <= 0";
        }
        
        if($sq_date_js != "" && $sq_date_js != NULL){
            $wherestr = $wherestr." and DATEDIFF('".$sq_date_js."',datetime) >= 0"; 
        }
        
        
        if($shbh != "" && $shbh != NULL){
            $wherestr = $wherestr." and UserID = '".($shbh-10000)."'";
        }
        
        if($lx != "" && $lx != NULL){
            
            $wherestr = $wherestr." and lx = ".$lx;
              
        }
          
          import("ORG.Util.Page");       //导入分页类 
          $count = $Moneybd->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Moneybd->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('datetime desc')->select(); 
        
          $page = $p->show();
          $page = str_replace("/index.php","",$page);
          
          $hjje = $Moneybd->where($wherestr)->sum("money");   
          
          $this->assign("page",$page);
          $this->assign("list",$list);
          $this->assign("shbh",$shbh);
          $this->assign("hjje",$hjje);
          
          $this->display();
    }
    
    public function tksz(){  //个人提款设置
        
        $UserID = $this->_get("UserID");
        
        $Tkconfig = M("Tkconfig");
          
          $list = $Tkconfig->where("UserID=".$UserID)->select();
          
          if($list == null){
              $data["UserID"] = $UserID;
              $Tkconfig->add($data);
              $list = $Tkconfig->where("UserID=".$UserID)->limit(1)->select(); 
          }
              
          $this->assign("list",$list);
          $this->assign("UserID",$UserID);
          $this->display();
    }
    
    public function tkszedit(){   //修改个人提款设置
           $Tkconfig = M("Tkconfig");
           $data["minmoney"] = $this->_post("minmoney");
           $data["maxmoney"] = $this->_post("maxmoney");
           $data["mtsxmoney"] = $this->_post("mtsxmoney");
           $data["mttkcs"] = $this->_post("mttkcs");
           $data["wtyh"] = $this->_post("wtyh");
           $data["sz"] = $this->_post("sz");
           $data["tksz"] = $this->_post("tksz");
           $data["wttksz"] = $this->_post("wttksz");
           $UserID = $this->_post("UserID");
           
           $Tkconfig->where("UserID = ".$UserID)->save($data);
           
           echo "ok";
           
    }
    
    public function dluser(){
    	$userid = $this->_get("userid");
    	$User = M("User");
    	$UserName = $User->where("id=".$userid)->getField("UserName");
    	$UserType = $User->where("id=".$userid)->getField("UserType");
    	session("UserName",$UserName);
    	session("UserType",$UserType);
    	session("UserID",$userid);
    	header("Location: ".U("/User"));
    	exit;
    }
                         
  }
?>
