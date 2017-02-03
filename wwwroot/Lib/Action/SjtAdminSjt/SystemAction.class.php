<?php
    class SystemAction extends Action{
        
        private $PayCost  = array(
        
            "wy" => "网银",
            "thykt" => "天宏一卡通",
            "wmykt" => "完美一卡通",
            "wyykt" => "网易一卡通",
            "ltczk" => "联通充值卡",
            "jyykt" => "久游一卡通",
            "qqczk" => "QQ币充值卡",
            "shykt" => "搜狐一卡通",
            "ztyxk" => "征途游戏卡",
            "jwykt" => "骏网一卡通",
            "sdykt" => "盛大一卡通",
            "qgszx" => "全国神州行",
            "txykt" => "天下一卡通",
            "dxczk" => "电信充值卡",
            "gyykt" => "光宇一卡通",
            "zyykt" => "纵游一卡通",
            "yddx" => "移动短信",
            "ltdx" => "联通短信",
            "dxdx" => "电信短信"
            
        );
        
         public function __construct(){
         parent::__construct();
         if(!session("?SjtUserName") || !session("?SjtUserType")){
            
           $this->display("Index:login");
            exit;
         }
            
    }
        
        public function czfl(){   //显示冲值费率
        
           $Paycost = M("Paycost");
           
           $list = $Paycost->where("UserID=0")->select();
           
           $this->assign("list",$list);
           $this->assign("PayCost",$this->PayCost);
           
           $this->display();
            
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
            $this->assign("jumpUrl","/SjtAdminSjt_System_czfl.html");
            $this->display("success");
        }
        
        public function Diaodan(){
            
            $System = M("System");
            
            $list = $System->where("UserID=0")->select();
            
            $this->assign("list",$list);
            
            $this->display();
        } 
        
        public function Diaodanedit(){
            
            $System = M("System");
            
            $data["Diaodan_OnOff"] = $this->_post("Diaodan_OnOff");
            
            $data["Diaodan_Kdate"] = $this->_post("Diaodan_Kdate");
            
            $data["Diaodan_Sdate"] = $this->_post("Diaodan_Sdate");
            
            $data["Diaodan_Kmoney"] = $this->_post("Diaodan_Kmoney");
            
            $data["Diaodan_Smoney"] = $this->_post("Diaodan_Smoney");
            
            $data["Diaodan_Pinlv"] = $this->_post("Diaodan_Pinlv");
            
            $data["Diaodan_Type"] = $this->_post("Diaodan_Type");
            
            $data["Diaodan_huifu"] = $this->_post("Diaodan_huifu");
            
            $System->where("UserID=0")->save($data);
            
            
            $this->assign("msgTitle","");
            $this->assign("message","修改成功！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","/SjtAdminSjt_System_Diaodan.html");
            $this->display("success");
            
        } 
        
        public function txfl(){
            
            $Tkfl = M("Tkfl");
            
            $t = $this->_get("t");
            
            $list = $Tkfl->where("t=".$t)->order("k_money asc")->select();
            
            $this->assign("list",$list);
            
            $this->display();
            
        }
        
        public function txfladd(){
            $Tkfl = M("Tkfl");
            
            $Tkfl->create();
            
            $Tkfl->add();
            
            $this->assign("msgTitle","");
            $this->assign("message","添加成功！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","/SjtAdminSjt_System_txfl_t_".$this->_post("T").".html");
            $this->display("success");
        }
        
        public function txfldel(){
            
            $id = $this->_get("id");
            
            $Tkfl = M("Tkfl");
            
            $Tkfl->where("id=".$id)->delete();
            
            $this->assign("msgTitle","");
            $this->assign("message","删除成功！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","/SjtAdminSjt_System_txfl_t_".$this->_get("t").".html");
            $this->display("success");
        }
        
        public function txflbj(){
            
            $id = $this->_get("id");
            
            $Tkfl = M("Tkfl");
            
            $list = $Tkfl->where("id=".$id)->select();
            
            $this->assign("list",$list);
            
            $this->display();
        }
        
        public function txflbjedit(){
            
            $Tkfl = M("Tkfl");
            
            //$Tkfl->create();
            $data["k_money"] = $this->_post("k_money");
            $data["s_money"] = $this->_post("s_money");
            $data["fl_money"] = $this->_post("fl_money");
            
            $Tkfl->where("id=".$this->_post("id"))->save($data);
            
            $this->assign("msgTitle","");
            $this->assign("message","修改成功！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","/SjtAdminSjt_System_txflbj_id_".$this->_post("id").".html");
            $this->display("success");
            
        }
        
       
       public function xgmm(){
           
           $this->display();
           
       }
       
       public function xgmmedit(){
           header("Content-Type:text/html; charset=utf-8");
           $YPassWord = $this->_post("YPassWord");
           $XPassWord = $this->_post("XPassWord");
           $PassWord = $this->_post("PassWord");
           
           if($YPassWord == NULL || $YPassWord == ""){
               exit("<script type='text/javascript'>alert('原密码不能为空！'); history.go(-1);</script>");
           }
           
           if($XPassWord == NULL || $XPassWord == ""){
               exit("<script type='text/javascript'>alert('新密码不能为空！'); history.go(-1);</script>");
           }
           
           if($XPassWord != $PassWord){
               exit("<script type='text/javascript'>alert('两次新密码输入不一致！'); history.go(-1);</script>");
           }
           
           $Sjtadminsjt = M("Sjtadminsjt");
           
           $listnum = $Sjtadminsjt->where("SjtUserName = '".session("SjtUserName")."' and SjtPassWord = '".md5($YPassWord)."'")->count();
           if($listnum > 0){
               $data["SjtPassWord"] = md5($PassWord);
               $xgmm = $Sjtadminsjt->where("SjtUserName = '".session("SjtUserName")."' and SjtPassWord = '".md5($YPassWord)."'")->save($data);
               
               if($xgmm){
                   exit("<script type='text/javascript'>alert('密码修改成功！'); location.href='/SjtAdminSjt_System_xgmm.html'</script>");
               }else{
                   exit("<script type='text/javascript'>alert('修改失败！'); history.go(-1);</script>");
               }
           }else{
               exit("<script type='text/javascript'>alert('原密码错误！'); history.go(-1);</script>");
           }
           
       }
    }
?>
