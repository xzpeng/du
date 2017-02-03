<?php
  class SjtadminsjtAction extends Action{
      
        public function denglu(){
        header("Content-Type:text/html; charset=utf-8");
        $UserName = $this->_request("UserName");
        $PassWord = $this->_request("PassWord");
        
        if($UserName == NULL || $UserName == "" || $PassWord == NULL || $PassWord == "" ){
            exit("no");
        }else{
            $Sjtadminsjt = M("Sjtadminsjt");
            $list = $Sjtadminsjt->where("SjtUserName = '".$UserName."' and SjtPassWord = '".md5($PassWord)."'")->select();
            $SjtUserType = $Sjtadminsjt->where("SjtUserName = '".$UserName."' and SjtPassWord = '".md5($PassWord)."'")->getField("SjtUserType");
            if($list){
                session("SjtUserName",$UserName);
                //$_SESSION["SjtUserName"] = $UserName;
                session("SjtUserType",$SjtUserType);
                //$_SESSION["SjtUserType"] = $SjtUserType;
                //exit("ok".session("SjtUserName")."|".session("SjtUserType"));
                echo 1;
				exit;
            }else{
                exit("no");
            }
        }
    }
    
    
    public function tuichu(){
        
        session("SjtUserName",null);
        session("SjtUserType",null);
        $this->display("Index:login");
    }
    
  }
?>
