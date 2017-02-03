<?php
  class AppAction extends Action{
      
      public $Json;    //获取收到的JSON数据
      public $obj;    //转换成PHP支付的json对象
      public $ArrayJson;   //返回数据变量
     // public $ReturnUrl;    //返回地址
      
       public function __construct(){   //初始函数
         parent::__construct();
         $json =  file_get_contents("php://input"); 
         $obj = json_decode($json);  
        // exit("[".$obj->cmd."]");
        // $ReturnUrl = $obj->ReturnUrl;    //返回地址
         if($obj->cmd != "USER0001"){   //如果不是登录申请，判断登录令牌是正否有效
             $LoginToken = $obj->data->LoginToken;  //获取登录令牌信息
             error_reporting(0);
             if(date("Y-m-d H:i:s",$LoginToken) == ""){  //如果登录令牌不能正常的转换为时间，直接提示登录失效
                   $ArrayJson = array(
                            "Errorcode"     => "0000000001",
                            "ReturnMessage" => $this->TransCode("登录失效，请重新登录")
                    );
                    $this->HttpPostData($ArrayJson);    //返回提交
                    exit;
             }
             
            $t = time() - $LoginToken;
            if($t <= 0){  //如果令牌时间比当前时间要大，直接提示登录失效
                   $ArrayJson = array(
                            "Errorcode"     => "0000000001",
                            "ReturnMessage" => $this->TransCode("登录失效，请重新登录")
                    );
                    $this->HttpPostData($ArrayJson);    //返回提交
                    exit;
            }else{
                  $y = floor($t/(3600*24*360));
                  $m = floor($t/(3600*24*31));
                  $d = floor($t/(3600*24));
                  $h = floor($t/(3600));
                  $s = floor($t/(60));
                  if($y == 0 && $m == 0 && $d == 0 && $h == 0 && $s >= 50){   //令牌时间与当前时间相差超过50分钟，直接提示登录失败
                       $ArrayJson = array(
                                "Errorcode"     => "0000000001",
                                "ReturnMessage" => $this->TransCode("登录失效，请重新登录")
                        );
                        $this->HttpPostData($ArrayJson);    //返回提交
                        exit;
                  }
            }
         }
         
       
            
    }
      
        public function httpjson(){
        
            $json =  file_get_contents("php://input"); 
            $obj = json_decode($json);  
            switch($obj->cmd){
                case "USER0001":   //用户登陆
               
                $UserName = $obj->data->Username;
                $Password = $obj->data->Password;
                if(trim($UserName) == "" || trim($Password) == ""){
                    $ArrayJson = array(
                            "Errorcode"     => "0000000001",
                            "ReturnMessage" => $this->TransCode("账号或密码不能为空")
                    );
                    $this->HttpPostData($ArrayJson);    //返回提交
                    exit;
                }
                $User = M("User");
                $id = $User->where("UserName = '".$UserName."' and LoginPassWord = '".$Password."'")->getField("id");
                if(!$id){
                    $ArrayJson = array(
                            "Errorcode"     => "0000000002",
                            "ReturnMessage" => $this->TransCode("账号或密码错误")
                    );
                    $this->HttpPostData($ArrayJson);    //返回提交
                    exit;
                }else{
                    $status = $User->where("id=".$id)->getField("status");
                    $RegDate = $User->where("id=".$id)->getField("RegDate");
                    $Money = M("Money");
                    $yeMoney = $Money->where("UserID=".$id)->getField("Money");
                    $ArrayJson = array(
                            "Errorcode"     => "0000000000",
                            "ReturnMessage" => $this->TransCode("登录成功"),
                            "data"          => array(
                                         "LoginToken"       =>   time(),  //登录令牌是当间的时间戳
                                         "AccountBalance "  =>   $yeMoney,    //账户余额
                                         "userName"         =>   $UserName,   //用户名
                                         "UserStatus"       =>   $status,   //账户状态
                                         "ActiveTime"       =>   date("YmdHis",strtotime($RegDate)),    //激活时间
                                         "AccountTime"      =>   "",    //资金最后变动时间
                            )
                            
                    );
                    $this->HttpPostData($ArrayJson);    //返回提交
                    exit;
                }
                
                
                /**************************************************************************************************************/
                break;
                case "USER0002":   //登陆密码修改
                /*************************************************************************************************/
                $Username = $obj->data->Username;
                $OldPassword = $obj->data->OldPassword;
                $NewPassword = $obj->data->NewPassword;
                if(trim($Username) == "" || trim($OldPassword) == "" || trim($NewPassword) == ""){
                    $ArrayJson = array(
                            "Errorcode"     => "0000000001",
                            "ReturnMessage" => $this->TransCode("账号或密码不能为空"),
                    );
                    $this->HttpPostData($ArrayJson);    //返回提交
                    exit;
                }
                
                if(trim($OldPassword) == trim($NewPassword)){
                    $ArrayJson = array(
                            "Errorcode"     => "0000000004",
                            "ReturnMessage" => $this->TransCode("新旧密码相同"),
                    );
                    $this->HttpPostData($ArrayJson);    //返回提交
                    exit;
                }
                
                $User = M("User");
                $id = $User->where("UserName = '".$UserName."' and LoginPassWord = '".$OldPassword."'")->getField("id");
                if(!$id){
                    $ArrayJson = array(
                            "Errorcode"     => "0000000002",
                            "ReturnMessage" => $this->TransCode("账号或密码错误")
                    );
                    $this->HttpPostData($ArrayJson);    //返回提交
                    exit;
                }else{
                    $data["LoginPassWord"] = $NewPassword;
                    if($User->where("id=".$id)->save($data)){
                        $ArrayJson = array(
                                "Errorcode"     => "0000000000",
                                 "ReturnMessage" => $this->TransCode("成功"),
                                "data"          => array(
                                        "LoginToken"       =>   time(),  //登录令牌是当间的时间戳
                                )
                                
                        );
                        $this->HttpPostData($ArrayJson);    //返回提交
                        exit;
                    }else{
                        $ArrayJson = array(
                                "Errorcode"     => "00000000005",
                                "ReturnMessage" => $this->TransCode("系统错误"),
                                
                        );
                        $this->HttpPostData($ArrayJson);    //返回提交
                        exit;
                    }
                    
                }
                /**************************************************************************************************/
                break;
                case "USER0010":    //订单浏览
                break;
                case "USER0011":    //单笔订单查询
                break;
                case "SYST0001":     //查询系统信息
                break;
                case "BANK0001":    //银行支付要素查询
                break;
                case "PAY00001":    //银行卡支付
                break;
                default:
                /**********************************************************************************************/
                $ArrayJson = array(
                                "Errorcode"     => "00000000005",
                                "ReturnMessage" => $this->TransCode("系统错误"),
                                
               );
               $this->HttpPostData($ArrayJson);    //返回提交
               exit;
              /********************************************************************************************************/  
            }
            
            
            
        }      
        private function HttpPostData($ArryJson) {   //[php]通过http post发送json数据 
            
            echo json_encode($ArryJson);
            
           // echo "11223344";
      
           // $ch = curl_init();  
            //curl_setopt($ch, CURLOPT_POST, 1);  
           // curl_setopt($ch, CURLOPT_URL, $url);  
           // curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
              //  'Content-Type: application/json; charset=utf-8',  
               // 'Content-Length: ' . strlen($data_string))  
          //  );  
            
            
            //ob_start();  
           // curl_exec($ch);  
           // $return_content = ob_get_contents();  
            //ob_end_clean();  
      
            //$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
            //return array($return_code, $return_content);  
        }
        
        private function BankName($BankCode){   //银行编码与银行全称
            
            switch($BankCode){
                  case "ICBC":
                  return "中国工商银行";
                break;
                 case "CCB":
                 return "中国建设银行";
                break;
                 case "CMB":
                 return "招商银行";
                break;
                 case "ABC":
                 return "中国农业银行";
                break;
                 case "BOC":
                 return "中国银行";
                break;
                 case "SPDB":
                 return "浦发银行";
                break;
                 case "GDB":
                 return "广发银行";
                break;
                 case "HXB":
                 return "华夏银行";
                break;
                 case "CIB":
                 return "兴业银行";
                break;
                 case "CEB":
                 return "中国光大银行";
                break;
                 case "CMBC":
                 return "中国民生银行";
                break;
                 case "BOCM":
                 return "交通银行";
                break;
                 case "PSBC":
                 return "中国邮政储蓄银行";
                break;
                 case "CNCB":
                 return "中信银行";
                break;
                 case "SDB":
                 return "深圳发展银行";
                break;
            }
        }
        
        private function OrderStatus($OrderCode){   //返回订单状态祥细内容
            
            switch($OrderCode){
                case "00":
                return "等待支付订单";
                break;
                case "01":
                return "支付成功订单";
                break;
                case "02":
                return "支付失败订单";
                break;
                case "03":
                return "退款中订单";
                break;
                case "04":
                return "退款成功订单";
                break;
                case "05":
                return "退款失败订单";
                break;
                case "06":
                return "超时订单";
                break;
            }
        }
        
        private function TransCode($Code){     //中文转码
           return iconv("GBK", "UTF-8", $Code);
           //return iconv("UTF-8", "GBK", $Code);
        }
    
  }
?>
