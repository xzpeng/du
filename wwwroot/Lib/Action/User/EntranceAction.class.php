<?php
   class EntranceAction extends Action{
      
      
         public function __construct(){   //初始函数
         parent::__construct();
       
         }
   
   
    public function httpjson(){
        switch($_REQUEST["type"]){
            case "VerifyCode":
            $Codestr = $_REQUEST["Codestr"];
            $Entrance = M("Entrance");
            $list = $Entrance->where("Code = '".$Codestr."'")->find();
            if($list){
                $ArrayJson = array(
                            "Errorcode"     => "0000000000",
                            "ReturnMessage" => "获取数据成功",
                            "urlajax"       => $list["urlajax"],
                            "namename"      => $list["namename"],
                            "VerifyCode"    => $Codestr
                    );
                    $this->HttpPostData($ArrayJson);    //返回提交
                
            }else{
               $ArrayJson = array(
                        "Errorcode"     => "0000000001",
                        "ReturnMessage" => "平台编码错误!"
                );
                $this->HttpPostData($ArrayJson);    //返回提交
             }
             exit;
             break;
             case "status":
             $Codestr = $_REQUEST["Codestr"];
             $Entrance = M("Entrance");
             $list = $Entrance->where("Code = '".$Codestr."'")->find();
              if($list){
                $ArrayJson = array(
                            "Errorcode"     => "0000000000",
                            "ReturnMessage" => "获取数据成功",
                            "urlajax"       => $list["urlajax"],
                            "namename"      => $list["namename"],
                            "tel"           => $list["tel"],
                            "VerifyCode"    => $Codestr,
                            "status"    => $list["status"]
                    );
                    $this->HttpPostData($ArrayJson);    //返回提交
                
            }else{
               $ArrayJson = array(
                        "Errorcode"     => "0000000001",
                        "ReturnMessage" => "您访问的平台不存在!"
                );
                $this->HttpPostData($ArrayJson);    //返回提交
             }
             exit;
             break;
          } 
    }
    
      private function HttpPostData($ArryJson) {
            
            echo json_encode($ArryJson);
            
  
        }
}
?>
