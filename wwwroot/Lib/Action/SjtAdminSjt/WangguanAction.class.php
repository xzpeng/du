<?php
  class WangguanAction extends Action{
      
       public function __construct(){
         parent::__construct();
         if(!session("?SjtUserName") || !session("?SjtUserType")){
            
           $this->display("Index:login");
            exit;
         }
            
    }
      
      public function wgbank(){
          
          $Sjapi = M("Sjapi");
          
          $listbank = $Sjapi->select();
          
          $System = M("System");
          
          $DefaultBank = $System->where("UserID=0")->getField("DefaultBank");
          
          $id = $System->getField("id");
          
          $this->assign("listbank",$listbank);
          $this->assign("DefaultBank",$DefaultBank);
          $this->assign("id",$id);
          
          $this->display();
      }
      
      public function wggame(){
          
          $Gamepay = M("Gamepay");
          
          $list = $Gamepay->select();
          
          $this->assign("list",$list);
          
          $this->display();
      }
  
     public function wggamexg(){
         $id = $this->_post("id");
         $defaultname = $this->_post("defaultname");
         
         $Gamepay = M("Gamepay");
         $data["default"] = $defaultname;
         $Gamepay->where("id = ".$id)->save($data);
         echo "ok";
     }
  
     public function wgbankedit(){
         
         $id = $this->_post("id");
         
         $DefaultBank = $this->_post("DefaultBank");
         
         $System = M("System");
         
         $data["DefaultBank"] = $DefaultBank;
         
         $System->where("id=".$id)->save($data);
         
         $Sjapi = M("Sjapi");
         $fl = $Sjapi->where("id=".$DefaultBank)->getField("fl");
         
         $Paycost = M("Paycost");
         $data["wy"] = 1 - $fl;
         $Paycost->where("UserID=0")->save($data);
         
         $this->assign("msgTitle","");
                   $this->assign("message","统一网银通道设置成功！");
                   $this->assign("waitSecond",3);
                   $this->assign("jumpUrl","/SjtAdminSjt_Wangguan_wgbank_UserID.html");
                   $this->display("success");
     }    
      
  }
?>
