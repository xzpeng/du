<?php
  class JiluAction extends Action{
      
      public function __construct(){
         parent::__construct();
         if(!session("?SjtUserName") || !session("?SjtUserType")){
            
           $this->display("Index:login");
            exit;
         }
            
    }
      
      public function wyjl(){

          $pagepage = $this->_get("pagepage");
          
          $sq_date = $this->_get("sq_date");
          
          $sq_date_js = $this->_get("sq_date_js");
          
          $typepay = $this->_get("typepay");
          
          $shbh = $this->_get("shbh");
          
          $TransID = $this->_get("TransID");
          
          if($pagepage == NULL || $pagepage == ""){
              $pagepage = 10;
          }
          
          $zt = $this->_get("zt");
          
          $wherestr = "(typepay = 0 or typepay = 1 or typepay = 3)";      
          
          if($zt != NULL && $zt != ""){
              $wherestr = $wherestr." and Zt = ".$zt;
          }   
          
          if($sq_date != "" && $sq_date != NULL){
              $wherestr = $wherestr." and DATEDIFF('".$sq_date."',TradeDate) <= 0";
          }   
          
          if($sq_date_js != "" && $sq_date_js != NULL){
              $wherestr = $wherestr." and DATEDIFF('".$sq_date_js."',TradeDate) >= 0";
          }           
          
          if($typepay != NULL && $typepay != ""){
              $wherestr = $wherestr." and typepay = ".$typepay;
          } 
          
          if($shbh != NULL && $shbh != ""){
              $wherestr = $wherestr." and UserID = ".($shbh - 10000);
          }   
          
          if($TransID != NULL && $TransID != ""){
              $wherestr = $wherestr." and TransID = '".$TransID."'";
          }     
                                  
          $Order = M("Order");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Order->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Order->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('TradeDate desc')->select(); 
        
          $page = $p->show();  
          $page = str_replace("/index.php","",$page);          
     //////////////////////////////////////////////////////////////////////////////////
      $datedate = date("Y-m-d");
     
          $daymoney = $Order->where("(typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
          $daysjmoney = $Order->where("(typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $daynum =  $Order->where("(typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
          $daylrmoney =  $Order->where("(typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("sxfmoney"); 
          
          $datedate = date("Y-m-d",time()-60*60*24);
     
          $zmoney = $Order->where("(typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
          $zsjmoney = $Order->where("(typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $znum =  $Order->where("(typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
          $zlrmoney =  $Order->where("(typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("sxfmoney"); 
        
     /////////////////////////////////////////////////////////////////////////////////
          $this->assign("page", $page); 
          $this->assign("list",$list);
          
           $this->assign("daymoney",$daymoney);
          $this->assign("daysjmoney",$daysjmoney);
          $this->assign("daynum",$daynum);
          $this->assign("zmoney",$zmoney);
          $this->assign("zsjmoney",$zsjmoney);
          $this->assign("znum",$znum);
          $this->assign("daylrmoney",$daylrmoney);
          $this->assign("zlrmoney",$zlrmoney);
          $this->assign("shbh",$shbh);
          $this->display();
        
      }
      
      public function tzshow($TransID){
         header("Content-Type:text/html; charset=utf-8"); 
          //$TransID = $this->_get("TransID");
          $Ordertz = M("Ordertz");
          $success = $Ordertz->where("Sjt_TransID = '".$TransID."'")->getField("success");
          if($success == 1){
              //echo "<span style='color:#ff0'>".mb_convert_encoding('已成功通知！','UTF-8','GBK')."</span>";
               echo "<span style='color:#f00'>已成功通知！</span>";
          }else{
              if($success != null && $success != ""){
                 
          $Sjt_num = $Ordertz->where("Sjt_TransID = '".$TransID."'")->getField("Sjt_num");
          
          if($Sjt_num < 5){
              //echo mb_convert_encoding('正在通知','UTF-8','GBK');
              echo "正在通知";
          }else{
              echo "<a href=\"javascript:xgtz('".$TransID."')\">".mb_convert_encoding('通知','UTF-8','GBK')."</a>";   
          }
                 
                   
              }else{
                  //echo "[1".$success.$TransID."1]";
                  //echo mb_convert_encoding('无','UTF-8','GBK');
                  echo "无";
              }
          }
      }
      
      public function xgtz(){
        
        $TransID = $this->_post("TransID");
        $Ordertz = M("Ordertz");
        $data["Sjt_num"] = 0;
        $data["success"] = 0;
        $list = $Ordertz->where("Sjt_TransID = '".$TransID."'")->save($data);
        if($list != NULL){
            exit("ok");
        }else{
            exit("no");
        }
    }
    
    
    
     public function gamejl(){
          
          $pagepage = $this->_get("pagepage");
          
          $sq_date = $this->_get("sq_date");
          
          $sq_date_js = $this->_get("sq_date_js");
          
          $typepay = $this->_get("typepay");
          
          $shbh = $this->_get("shbh");
          
          $ddh = $this->_get("ddh");
          
          $kahao = $this->_get("kahao");
          
          if($pagepage == NULL || $pagepage == ""){
              $pagepage = 10;
          }
          
          $zt = $this->_get("zt");
          
          $wherestr = "(typepay = 2 or typepay = 4)";      
          
          if($zt != NULL && $zt != ""){
              $wherestr = $wherestr." and Zt = ".$zt;
          }   
          
          if($sq_date != "" && $sq_date != NULL){
              $wherestr = $wherestr." and DATEDIFF('".$sq_date."',TradeDate) <= 0";
          }     
          
          if($sq_date_js != "" && $sq_date_js != NULL){
              $wherestr = $wherestr." and DATEDIFF('".$sq_date_js."',TradeDate) >= 0";
          }         
          
          if($typepay != NULL && $typepay != ""){
              $wherestr = $wherestr." and typepay = ".$typepay;
          }  
          
          if($shbh != NULL && $shbh != ""){
              $wherestr = $wherestr." and UserID = ".($shbh - 10000);
          }       
          
          if($ddh != NULL && $ddh != ""){
              $wherestr = $wherestr." and TransID = '".$ddh."'";
          }
          
          if($kahao != NULL && $kahao != ""){
              $wherestr = $wherestr." and CardNO = '".$kahao."'";
          }       
                                  
          $Order = M("Order");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Order->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Order->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('TradeDate desc')->select(); 
        
          $page = $p->show();      
          $page = str_replace("/index.php","",$page);      
      //////////////////////////////////////////////////////////////////////////////////
      $datedate = date("Y-m-d");
     
          $daymoney = $Order->where("(typepay = 2 or typepay = 4 ) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
          $daysjmoney = $Order->where("(typepay = 2 or typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $daynum =  $Order->where("(typepay = 2 or typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
          $daylrmoney =  $Order->where("(typepay = 2 or typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("sxfmoney"); 
          
          $datedate = date("Y-m-d",time()-60*60*24);
     
          $zmoney = $Order->where("(typepay = 2 or typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
          $zsjmoney = $Order->where("(typepay = 2 or typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $znum =  $Order->where("(typepay = 2 or typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
           $zlrmoney =  $Order->where("(typepay = 2 or typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("sxfmoney"); 
        
     /////////////////////////////////////////////////////////////////////////////////
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->assign("daymoney",$daymoney);
          $this->assign("daysjmoney",$daysjmoney);
          $this->assign("daynum",$daynum);
          $this->assign("zmoney",$zmoney);
          $this->assign("zsjmoney",$zsjmoney);
          $this->assign("znum",$znum);
           $this->assign("daylrmoney",$daylrmoney);
          $this->assign("zlrmoney",$zlrmoney);
          $this->display();
        
      }
    
    
     public function ptzzjl(){
          
          $pagepage = $this->_get("pagepage");
          
          $sq_date = $this->_get("sq_date");
          
          $sq_date_js = $this->_get("sq_date_js");
          
          
          if($pagepage == NULL || $pagepage == ""){
              $pagepage = 10;
          }
          
          $zt = $this->_get("zt");
          
          $wherestr = "(typepay = 5)";      
          
          if($zt != NULL && $zt != ""){
              $wherestr = $wherestr." and Zt = ".$zt;
          }   
          
          if($sq_date != "" && $sq_date != NULL){
              $wherestr = $wherestr." and DATEDIFF('".$sq_date."',TradeDate) <= 0";
          }     
          
          if($sq_date_js != "" && $sq_date_js != NULL){
              $wherestr = $wherestr." and DATEDIFF('".$sq_date_js."',TradeDate) >= 0";
          }         
          
         
                                  
          $Order = M("Order");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Order->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Order->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('TradeDate desc')->select(); 
        
          $page = $p->show();            
          $page = str_replace("/index.php","",$page);
      //////////////////////////////////////////////////////////////////////////////////
      $datedate = date("Y-m-d");
     
          $daymoney = $Order->where("(typepay = 5) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
          $daysjmoney = $Order->where("(typepay = 5) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $daynum =  $Order->where("(typepay = 5) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
          $daylrmoney =  $Order->where("(typepay = 5) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("sxfmoney"); 
          
          $datedate = date("Y-m-d",time()-60*60*24);
     
          $zmoney = $Order->where("(typepay = 5) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
          $zsjmoney = $Order->where("(typepay = 5) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $znum =  $Order->where("(typepay = 5) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
           $zlrmoney =  $Order->where("(typepay = 5) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("sxfmoney"); 
        
     /////////////////////////////////////////////////////////////////////////////////
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->assign("daymoney",$daymoney);
          $this->assign("daysjmoney",$daysjmoney);
          $this->assign("daynum",$daynum);
          $this->assign("zmoney",$zmoney);
          $this->assign("zsjmoney",$zsjmoney);
          $this->assign("znum",$znum);
           $this->assign("daylrmoney",$daylrmoney);
          $this->assign("zlrmoney",$zlrmoney);
          $this->display();
        
      }
    
    public function Tongji(){
        
        $ksjy_date = $this->_request("ksjy_date");
        $jsjy_date = $this->_request("jsjy_date");
        $jylx = $this->_request("jylx");
        $shbh = $this->_request("shbh");
        $ddh = $this->_request("ddh");
        $zfyh = $this->_request("zfyh");
        $zfdk = $this->_request("zfdk");
        
        $wherestr = "Zt = 1";
        
        if($ksjy_date != NULL && $ksjy_date != ""){
            $wherestr = $wherestr." and DATEDIFF('".$ksjy_date."',TradeDate) <= 0";
        }
        
        if($jsjy_date != "" && $jsjy_date != NULL){
              $wherestr = $wherestr." and DATEDIFF('".$jsjy_date."',TradeDate) >= 0";
        } 
        
        if($jylx != NULL && $jylx != ""){
            $wherestr = $wherestr." and typepay = ".$jylx;
        }        
        
        if($shbh != NULL && $shbh != ""){
            $wherestr = $wherestr." and UserID = ".($shbh - 10000);
        }
        
        if($ddh != NULL && $ddh != ""){
            $wherestr = $wherestr." and TransID = '".$ddh."'";
        }
        
        if($zfyh != NULL && $zfyh != ""){
            
            if($jylx == 0 || $jylx ==1){
                $wherestr = $wherestr." and bankname = '".$zfyh."'";
            }
            
        }
        
        if($zfdk != NULL && $zfdk != ""){
            
            if($jylx == 2 || $jylx ==3){
                $wherestr = $wherestr." and payname  = '".$zfyh."'";
            }
            
        }
        
        // exit($wherestr);
          
          $Order = M("Order");         
         
          $daymoney = $Order->where($wherestr)->sum("trademoney"); 
          
          $daysjmoney = $Order->where($wherestr)->sum("OrderMoney"); 
     
          $daynum =  $Order->where($wherestr)->count(); 
          
          $daylrmoney =  $Order->where($wherestr)->sum("sxfmoney"); 
          
         
          
        
        $Bankpay = M("Bankpay");
        $banklist = $Bankpay->select();
        $Gamepay = M("Gamepay");
        $paylist = $Gamepay->select();
        $this->assign("banklist",$banklist);
        $this->assign("paylist",$paylist);
        
         $this->assign("daymoney",$daymoney);
         $this->assign("daysjmoney",$daysjmoney);
         $this->assign("daynum",$daynum);
         $this->assign("daylrmoney",$daylrmoney);
         $this->assign("wherestr",$wherestr);
        $this->display();
    }
    
    public function XTongji(){
        
        $daydate = date("Y年m月d日");
        
        $ks_date = $this->_request("ks_date");
        $ks_dx = "<=";
        $js_date = $this->_request("js_date");
        $js_dx = ">=";
        
        if($ks_date == "" || $ks_date == NULL){
            $ks_date = date("Y-m-d");
            $ks_dx = "=";
        }
        
        if($js_date == "" || $js_date == NULL){
            $js_date = date("Y-m-d");
            $js_dx = "=";
        }
        
        if($ks_date == $js_date){
            $daydate = $ks_date;
        }else{
            $daydate = $ks_date." 至 ".$js_date;
            
        }
        
        $wheredate = "(DATEDIFF('".$ks_date."',sq_date) ".$ks_dx." 0";
        $wheredate = $wheredate." and DATEDIFF('".$js_date."',sq_date) ".$js_dx." 0)";
        
        $wheredatetime = "(DATEDIFF('".$ks_date."',datetime) ".$ks_dx." 0";
        $wheredatetime = $wheredatetime." and DATEDIFF('".$js_date."',datetime) ".$js_dx." 0)";
        
        $whereTradeDate = "(DATEDIFF('".$ks_date."',TradeDate) ".$ks_dx." 0";
        $whereTradeDate = $whereTradeDate." and DATEDIFF('".$js_date."',TradeDate) ".$js_dx." 0)";
        
        
        $Tklist = M("Tklist");
       // $wherestr = "T=".$T." and wt = 0";
        $wherestr = $wheredate." and wt = 0";
        $wherestry = $wheredate." and wt = 0 and ZT =2";
        $count = $Tklist->where($wherestr)->count();  //申请提款总笔数
        $countmoney =  $Tklist->where($wherestr)->sum("money");  //申请提款总金额
        
        
        $county = $Tklist->where($wherestry)->count();  //申请已提款总笔数
        $countmoneyy =  $Tklist->where($wherestry)->sum("money");  //申请已提款总金额
        $tklr = $Tklist->where($wherestry)->sum("sxf_money"); //申请已提款利润
        
        $Wttklist  = M("Wttklist");
        $wherestr = $wheredate;
        $wherestry = $wheredate." and ZT =2";
        $sqtkbs = $Wttklist->where($wherestr)->count();  //委托提款总笔数
        $sqtkbsmoney = $Wttklist->where($wherestr)->sum("money");  //委托提款总金额
        
        $sqtkbsy = $Wttklist->where($wherestry)->count();  //委托已提款总笔数
        $sqtkbsmoneyy = $Wttklist->where($wherestry)->sum("money");  //委托已提款总金额
        $wttklr = $Wttklist->where($wherestry)->sum("sxf_money"); //委托已提款利润
        
        $Adminmoney = M("Adminmoney");
        $wherestr = $wheredatetime." and jj = -1 and zt = 1 and lr = 0";
        $jjzhs = $Adminmoney->where($wherestr)->count();  //减金账户数
        $jjzje = $Adminmoney->where($wherestr)->sum("money");  //减金总额
        
        $Order = M("Order");
        $wherestr = $whereTradeDate." and typepay = 5 and Zt = 1";
        $ptzzbs = $Order->where($wherestr)->count();   //平台转账笔数
        $ptzzsxfze = $Order->where($wherestr)->sum("sxfmoney");  //平台转账总金额
        
        $wherestr = $whereTradeDate." and (typepay = 0 or typepay = 1) and Zt = 1";
        $wywgcb = $Order->where($wherestr)->sum("sjflmoney");  //网银网关成本
        $wysxf = $Order->where($wherestr)->sum("sxfmoney"); //网银手续费
        $wysjsxf = $wysxf - $wywgcb;   //网银手续费实际金额        
        
        $wherestr = $whereTradeDate." and typepay = 2 and Zt = 1";
        $yxwgcb = $Order->where($wherestr)->sum("sjflmoney");  //点卡网关成本
        $yxsxf = $Order->where($wherestr)->sum("sxfmoney"); //点卡手续费
        $yxsjsxf = $yxsxf - $yxwgcb;   //点卡手续费实际金额        
        
        $sqtkbs = $sqtkbs + $count;
        $sqtkbsmoney = $sqtkbsmoney + $countmoney;
        
        $sqtkbsy = $sqtkbsy + $county;
        $sqtkbsmoneyy = $sqtkbsmoneyy + $countmoneyy;
        
        $sqtklr = $wttklr + $tklr;   //提款利润
        $jssqtklr = $sqtklr * 0.05;   //技术提款利润
        
        $Ordertz = M("Ordertz");
        $wherestr = $wheredatetime." and Diaodang = 1";
        $ddsl = $Ordertz->where($wherestr)->count(); //掉单数量
        $ddzje = $Ordertz->where($wherestr)->sum("Sjt_factMoney"); //掉单总金额
        
        $hjlr = $ptzzsxfze + $wysjsxf + $yxsjsxf + $jjzje + $ddzje;   //利润合计
        $jshjlr = $hjlr * 0.05;
        
        $this->assign("ddsl",$ddsl);
        $this->assign("ddzje",$ddzje);
        $this->assign("sqtkbs",$sqtkbs);
        $this->assign("sqtkbsy",$sqtkbsy);
        $this->assign("sqtkbsmoney",$sqtkbsmoney);
        $this->assign("sqtkbsmoneyy",$sqtkbsmoneyy);
        $this->assign("sqtklr",$sqtklr);
        $this->assign("jssqtklr",$jssqtklr);
        $this->assign("jjzhs",$jjzhs);
        $this->assign("jjzje",$jjzje);
        $this->assign("ptzzbs",$ptzzbs);
        $this->assign("ptzzsxfze",$ptzzsxfze);
        $this->assign("daydate",$daydate);
        $this->assign("wywgcb",$wywgcb);
        $this->assign("wysjsxf",$wysjsxf);
        $this->assign("yxwgcb",$yxwgcb);
        $this->assign("yxsjsxf",$yxsjsxf);
        $this->assign("hjlr",$hjlr);
        $this->assign("jshjlr",$jshjlr);
        $this->display();
    }
    
    public function ddjl(){       //掉单记录
        
        $Ordertz = M("Ordertz");
        
        $pagepage = $this->_get("pagepage");
        
        if($pagepage == "" || $pagepage == NULL){
            $pagepage = 10;
        }
        
        $wherestr = "Diaodang=1";
        
        $sq_date = $this->_request("sq_date");
        $sq_date_js = $this->_request("sq_date_js");
        $shbh = $this->_request("shbh");
        
        if($sq_date != "" && $sq_date != NULL){
            $wherestr = $wherestr." and DATEDIFF('".$sq_date."',datetime) <= 0";
        }
        
        if($sq_date_js != "" && $sq_date_js != NULL){
            $wherestr = $wherestr." and DATEDIFF('".$sq_date_js."',datetime) >= 0"; 
        }
        
        
        if($shbh != "" && $shbh != NULL){
            $wherestr = $wherestr." and Sjt_MerchantID = '".($shbh-10000)."'";
        }
        
          
        
          import("ORG.Util.Page");       //导入分页类 
          $count = $Ordertz->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Ordertz->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('datetime desc')->select(); 
        
          $page = $p->show();        
          $page = str_replace("/index.php","",$page);    
        
        $hjje = $Ordertz->where($wherestr)->sum(Sjt_factMoney);
        
        $this->assign("page",$page);
        $this->assign("list",$list);
        
        $this->assign("shbh",$shbh);
        $this->assign("sq_date",$sq_date);
        $this->assign("sq_date_js",$sq_date_js);
        $this->assign("hjje",$hjje);
        
        $this->display();
        
    }
    
    public function zjjejl(){   //增减金额记录
        
        $Adminmoney = M("Adminmoney");
         
        $pagepage = $this->_get("pagepage");
        
        if($pagepage == "" || $pagepage == NULL){
            $pagepage = 10;
        }
        
        $wherestr = "zt=1";
        
        $sq_date = $this->_request("sq_date");
        $sq_date_js = $this->_request("sq_date_js");
        $shbh = $this->_request("shbh");
        $lx = $this->_request("lx");
        
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
            switch($lx){
                case 1:
                $wherestr = $wherestr." and jj = 1";
                break;
                case 2:
                $wherestr = $wherestr." and jj = -1 and lr = 0";
                break;
                case 3:
                $wherestr = $wherestr." and jj = -1 and lr = 1";
                break;
            }
        }
        
          
        
          import("ORG.Util.Page");       //导入分页类 
          $count = $Adminmoney->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Adminmoney->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('datetime desc')->select(); 
        
          $page = $p->show();  
           $page = str_replace("/index.php","",$page);
          
          //////////////////////////////////////////////////////////////////////////////////
      $datedate = date("Y-m-d");
     
          $drjjsl = $Adminmoney->where("(jj = -1) and Zt = 1 and DATEDIFF('".$datedate."',datetime) = 0")->count(); 
          
          $drjjje = $Adminmoney->where("(jj = -1) and Zt = 1 and DATEDIFF('".$datedate."',datetime) = 0")->sum("money"); 
     
          $drzjsl =  $Adminmoney->where("(jj = 1) and Zt = 1 and DATEDIFF('".$datedate."',datetime) = 0")->count(); 
          
          $drzjje =  $Adminmoney->where("(jj = 1) and Zt = 1 and DATEDIFF('".$datedate."',datetime) = 0")->sum("money"); 
          
          $datedate = date("Y-m-d",time()-60*60*24);
     
          $zrjjsl = $Adminmoney->where("(jj = -1) and Zt = 1 and DATEDIFF('".$datedate."',datetime) = 0")->count(); 
          
          $zrjjje = $Adminmoney->where("(jj = -1) and Zt = 1 and DATEDIFF('".$datedate."',datetime) = 0")->sum("money"); 
     
          $zrzjsl =  $Adminmoney->where("(jj = 1) and Zt = 1 and DATEDIFF('".$datedate."',datetime) = 0")->count(); 
          
          $zrzjje =  $Adminmoney->where("(jj = 1) and Zt = 1 and DATEDIFF('".$datedate."',datetime) = 0")->sum("money"); 
        
     /////////////////////////////////////////////////////////////////////////////////
          
          
          $this->assign("page",$page);
          $this->assign("list",$list);
          $this->assign("shbh",$shbh);
          
          $this->assign("drjjsl",$drjjsl);
          $this->assign("drjjje",$drjjje);
          $this->assign("drzjsl",$drzjsl);
          $this->assign("drzjje",$drzjje);
          
          $this->assign("zrjjsl",$zrjjsl);
          $this->assign("zrjjje",$zrjjje);
          $this->assign("zrzjsl",$zrzjsl);
          $this->assign("zrzjje",$zrzjje);
          
          
          $this->display();                
    }
    
  }
?>
