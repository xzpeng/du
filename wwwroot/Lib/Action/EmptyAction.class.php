<?php
  class EmptyAction extends Action{
      public function Index(){
           $ActionName = MODULE_NAME;
           $this->mypay($ActionName);

          
      }
      
      public function mypay($ActionName){
          $User = M("User");
          $UserID = $User->where("mypayurlname='".$ActionName."'")->getField("id");
          if(!$UserID){
                //echo "您访问的地址不存在！<a href='http://".C("WEB_URL")."'>返回</a>"; 
               // $this->display("Home:Index:sls");    
                 
               if($ActionName == C("ADMIN_NAME")){
                       if(!session("?SjtUserName") || !session("?SjtUserType")){
                           
                           $this->display("SjtAdminSjt:Index:login");
                       }else{
                           header("location:".U("/SjtAdminSjt"));
                       }
               }else{
                       $this->display("Home:Index:sls");
               }
               
          }else{
             $Sjt_BType = $_POST["Sjt_BType"];
             
             if($Sjt_BType){
                  ////////////////////////////////////////////////////////////////////////////
                       $Userapiinformation = M("Userapiinformation");      
                   $keykey = $Userapiinformation->where("UserID=".$UserID)->getField("key");     
                   $Sjt_MerchantID = $_REQUEST["Sjt_MerchantID"];
                    $Sjt_Username = $_REQUEST["Sjt_Username"];
                    $Sjt_TransID = $_REQUEST["Sjt_TransID"];
                    $Sjt_Return = $_REQUEST["Sjt_Return"];
                    $Sjt_Error = $_REQUEST["Sjt_Error"];
                    $Sjt_factMoney = $_REQUEST["Sjt_factMoney"];
                    $Sjt_SuccTime = $_REQUEST["Sjt_SuccTime"];
                    $Sjt_BType = $_REQUEST["Sjt_BType"];
                    $Sjt_Sign = $_REQUEST["Sjt_Sign"];
                    $key = $keykey;
                    
                    $Sign = md5($Sjt_MerchantID.$Sjt_Username.$Sjt_TransID.$Sjt_Return.$Sjt_Error.$Sjt_factMoney.$Sjt_SuccTime.$Sjt_BType.$key);
                    
                    if($Sjt_Sign == $Sign){
                        if($Sjt_BType == 1){
                        
                       // echo "充值成功！<br>";
                        //echo "订单号：".$Sjt_TransID."<br>";
                       // echo "充值时间：".$Sjt_SuccTime;
                        
                           $this->assign("Sjt_TransID",$Sjt_TransID);
                            $this->assign("Sjt_SuccTime",date("Y-m-d H:i:s"));
                            $this->assign("Sjt_factMoney",$Sjt_factMoney);
                            $this->assign("sk","ok");
                            $this->display("Index:Merchanturl");
                        
                        }else{
                             if($Sjt_BType == 2){
                                 echo "ok";
                             }    
                        }
                        
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
                        
                        
                    }
                  ///////////////////////////////////////////////////////////////////////////
             }else{
              
             $RegDate = $User->where("id=".$UserID)->getField("RegDate");      
             
               $Userbasicinformation = M("Userbasicinformation");    
             $Compellation = $Userbasicinformation->where("UserID=".$UserID)->getField("Compellation");
             $this->assign("youname",$Compellation);
             $this->assign("ActionName",$ActionName);
             $this->assign("weburl","http://".C("WEB_URL")."/".$ActionName);
             $this->assign("RegDate",$RegDate);
             $this->display("Home:Index:mypay");
             } 
          }
      }
      
     
  }
?>
