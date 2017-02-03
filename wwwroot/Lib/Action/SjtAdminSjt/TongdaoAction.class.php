<?php
  class TongdaoAction extends Action{

      private $BankArray = array(
              "zsyh" => "",
              "gsyh" => "",
              "jsyh" => "",
              "shpdfzyh" => "",
              "nyyh" => "",
              "msyh" => "",
              "szfzyh" => "",
              "xyyh" => "",
              "jtyh" => "",
              "gdyh" => "",
              "zgyh" => "",
              "payh" => "",
              "gfyh" => "",
              "zxyh" => "",
              "nbyh" => "",
              "fdyh" => "",
              "zgyzcxyh" => ""
              );
      
      private $GameArray = array(
             "1" => "",
             "2" => "",
             "3" => "",
             "4" => "",
             "5" => "",
             "6" => "",
             "7" => "",
             "8" => "",
             "9" => "",
             "10" => "",
             "11" => "",
             "12" => "",
             "13" => "",
             "14" => "",
             "15" => "",
             "16" => "",
             "17" => "",
             "18" => ""
      );
      
       public function __construct(){
         parent::__construct();
         if(!session("?SjtUserName") || !session("?SjtUserType")){
            
           $this->display("Index:login");
            exit;
         }
            
    }
      
      public function Index(){
          $apiname = $this->_get("apiname");
          $Sjapi = M("Sjapi");
          $list = $Sjapi->where("apiname='".$apiname."'")->select();
          $this->assign("list",$list);
          $this->assign("datetime",strval(date("Y-m-d")));
          $this->display();
      }
      
      public function EditTongdao(){
          $apiname = $this->_get("apiname");
          $shid = $this->_post("shid");
          $key = $this->_post("key");
          $zhanghu = $this->_post("zhanghu");
          $datetime = $this->_post("datetime");
          $xz = $this->_post("xz");
          $fl = $this->_post("fl");
          
          $Sjapi = M("Sjapi");
          $data["shid"] = $shid;
          $data["key"] = $key;
          $data["zhanghu"] = $zhanghu;
          $data["edit_date"] = $datetime;
          $data["xz"] = $xz;
          $data["fl"] = $fl;
          $Sjapi->where("apiname = '".$apiname."'")->save($data);
          
        $this->assign("msgTitle","");
        $this->assign("message","修改成功！");
        $this->assign("waitSecond",3);
        $this->assign("jumpUrl","/SjtAdminSjt_Tongdao_Index_apiname_".$apiname.".html");
        $this->display("success");
      }
      
      
      public function bankpay(){
          $apiname = $this->_get("apiname");
          
          $Bankpay = M("Bankpay");
          
          foreach($this->BankArray as $key => $val){
             $this->BankArray[$key] = $Bankpay->where("Sjt='".$key."'")->getField($apiname);
          }
          
          $this->assign("apiname",$apiname);
          $this->assign("BankArray",$this->BankArray);
          
          $this->display();
           
      }
      
       public function gamepay(){
          $apiname = $this->_get("apiname");

          $Gamepay = M("Gamepay");
          
          foreach($this->GameArray as $key => $val){
             $this->GameArray[$key] = $Gamepay->where("Sjt='".$key."'")->getField($apiname);
          }
          
          $this->assign("apiname",$apiname);
          $this->assign("GameArray",$this->GameArray);
          
          $this->display();
           
      }
      
      public function EditBankpay(){
        
          $apiname = $this->_get("apiname");
          
          $Bankpay = M("Bankpay");
          
          foreach($this->BankArray as $key => $val){
              $data[$apiname] = $this->_post($key);
              $Bankpay->where("Sjt = '".$key."'")->save($data);
          }
          

          $this->assign("msgTitle","");
          $this->assign("message","修改成功！");
          $this->assign("waitSecond",3);
          $this->assign("jumpUrl","/SjtAdminSjt_Tongdao_bankpay_apiname_".$apiname.".html");
          $this->display("success");
          
      }
      
      
       public function EditGamepay(){
        
          $apiname = $this->_get("apiname");
          
          $Gamepay = M("Gamepay");
          
          foreach($this->GameArray as $key => $val){
              $data[$apiname] = $this->_post($key);
              $Gamepay->where("Sjt = '".$key."'")->save($data);
          }
          

          $this->assign("msgTitle","");
          $this->assign("message","修改成功！");
          $this->assign("waitSecond",3);
          $this->assign("jumpUrl","/SjtAdminSjt_Tongdao_gamepay_apiname_".$apiname.".html");
          $this->display("success");
          
      }
      
      public function sjfl(){
          $apiname = $this->_request("apiname");
          
          $Sjfl = M("Sjfl");
          
          $list = $Sjfl->where("jkname='".$apiname."'")->select();
          
          $this->assign("list",$list);
          $this->display();
      }
      
      public function sjfledit(){
           $Sjfl = M("Sjfl");
            
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
            
            $Sjfl->where("id=".$this->_post("id"))->save($data);
            
            $this->assign("msgTitle","");
            $this->assign("message","修改成功！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","/SjtAdminSjt_Tongdao_sjfl_apiname_".$this->_request("apiname").".html");
            $this->display("success");
      }
  }
?>
