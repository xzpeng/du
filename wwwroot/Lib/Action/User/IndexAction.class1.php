<?php

class IndexAction extends Action{
	
    public function __construct(){
         parent::__construct();
         if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
            
            $this->display("Index:exit");
            exit;
         }
            
    }
	
	public function index(){
		
		if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			
		}else{
			
            $Money = D("Money");
            $Money_Money =  $Money->where("UserID=".session("UserID"))->getField("Money");
            
            $Userbasicinformation = M("Userbasicinformation");
            $Compellation = $Userbasicinformation->where("UserId=".session("UserID"))->getField("Compellation");
          
            $Userapiinformation = D("Userapiinformation");
            $zt = $Userapiinformation->where("UserId=".session("UserID"))->getField("Zt");
            
            if(!session("?scdate")){
                
                $Dldate = M("Dldate");
                
                $dldldate = $Dldate->where("UserID=".session("UserID"))->order("dldate desc")->getField("dldate");
                $dldlip = $Dldate->where("UserID=".session("UserID"))->order("dldate desc")->getField("ip");  
                
                if(!$dldldate){
                    session("scdate","无");
                }else{
                    session("scdate",$dldldate);
                }
                
                if(!$dldlip){
                    session("scip","无");
                }else{
                    session("scip",$dldlip);
                }
                
                
                //////////////////////////////////////////////////////////
                if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) 
                { 
                $ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]; 
                } 
                elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) 
                { 
                $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"]; 
                }
                elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]) 
                { 
                $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
                } 
                elseif (getenv("HTTP_X_FORWARDED_FOR")) 
                { 
                $ip = getenv("HTTP_X_FORWARDED_FOR"); 
                } 
                elseif (getenv("HTTP_CLIENT_IP")) 
                { 
                $ip = getenv("HTTP_CLIENT_IP"); 
                } 
                elseif (getenv("REMOTE_ADDR"))
                { 
                $ip = getenv("REMOTE_ADDR"); 
                } 
                else 
                { 
                $ip = "Unknown"; 
                } 
                /////////////////////////////////////////////////////////
                
                $data["dldate"] = date("Y-m-d H:i:s");
                $data["UserID"] = session("UserID");
                $data["ip"] = $ip;
                
                $Dldate->add($data);
            }
            //通知
            $Tongzhi = M("Tongzhi");
            $listTongzhi = $Tongzhi->where("UserID=".session("UserID"))->order("id desc")->limit(0,10)->select();
            
            $Bank = M("Bank");
                
            $count = $Bank->where("UserID=".session("UserID")." and banktype = 0 ")->count();
            
            $BankName = $Bank->where("UserID=".session("UserID")." and moren = 1")->getField("BankName");
            
            //交易记录
            $Order = M("Order");
           // $jylist = $Order->where("UserID=".session("UserID")." or (typepay = 5 and Username = '".session("UserID")."')")->order("TradeDate desc")->limit("0,10")->select();
            $jylist = $Order->where("(UserID=".session("UserID")." or (Username = '".session("UserID")."' and typepay = 5) ) and Zt = 1")->order("TradeDate desc")->limit("0,10")->select();
            
            $sq_date = date("Y-m-d");
            $dayorder = $Order->where("UserID = '".session("UserID")."' and DATEDIFF('".$sq_date."',TradeDate) = 0 and zt=1")->count();
            $daymoney = $Order->where("UserID = '".session("UserID")."' and DATEDIFF('".$sq_date."',TradeDate) = 0 and zt=1")->sum("trademoney");
            
            $Newlist = M("Newlist");
            $listgg = $Newlist->where("type = 2 and zt = 0")->limit("0,3")->order("datetime desc")->select();     
            
            
            $Tklist = M("Tklist");
            $wclmoney1 = $Tklist->where("wt = 0 and UserID=".session("UserID")." and ZT = 0")->sum("tk_money");     //未处理提款金额
            
            $yclmoney1 = $Tklist->where("wt = 0 and UserID=".session("UserID")." and ZT = 2")->sum("money");     //已处理提款金额       
            
            $Wttklist = M("Wttklist");  
            $wclmoney2 = $Wttklist->where("UserID=".session("UserID")." and ZT = 0")->sum("tk_money");   //未处理委托提款金额 
            
            $yclmoney2 = $Wttklist->where("UserID=".session("UserID")." and ZT = 2")->sum("tk_money");   //已处理委托提款金额  
            
            $zhye = $wclmoney1 + $wclmoney2 +  $Money_Money; 
            
            $ytxmoney =  $yclmoney1 + $yclmoney2;                                                                                                                                                                                                            
            
            $this->assign("listgg",$listgg);
            $this->assign("jylist",$jylist);                                                                                                                                                                                                                                 
            $this->assign("dayorder",$dayorder);
            $this->assign("UserID",session("UserID"));
            $this->assign("BankName",$BankName);
            $this->assign("bankcount",$count);
            $this->assign("Compellation",$Compellation);
            $this->assign("Money",$Money_Money);
            $this->assign("zt",$zt);
            $this->assign("zhye",$zhye);
            $this->assign("ytxmoney",$ytxmoney); 
            $this->assign("listTongzhi",$listTongzhi);
			$this->display();
			
		}
		
	}
	
     public function sjtgg(){   //盛捷通公告
    
          $wherestr = "type = 2 and zt = 0"; 
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
    
    public function sjtggshow(){
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
    
	public function zzzm(){
        $this->display();
    }
    
	public function basic(){     //基本信息
	    
		if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			exit;
			
		}else{
			
			$Userbasicinformation = D("Userbasicinformation");
			
			$list = $Userbasicinformation->where("UserID = ".session("UserID"))->select();
			
			if($list != null){
			    $this->assign("list",$list);
			}
			
			$this->display();
			
		}
			
	}
	
	
	public function basicsave(){     //保存基本信息
	
 		 header("Content-Type:text/html; charset=utf-8");
		
		if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			
		}else{
			
			$Userbasicinformation = D("Userbasicinformation");
		
		    $Userbasicinformation->create();
		
			if($Userbasicinformation->save() >= 0){
				exit("<script type='text/javascript'>alert('修改成功！'); location.href='/User_Index_basic.html'</script>");
			}else{
				exit("<script type='text/javascript'>alert('修改失败，请稍后再试！'); history.go(-1);</script>");
			}
		}
		

	}
	
	
	
	public function aqxx(){     //安全信息
	
	    if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			
		}else{
		    $Usersafetyinformation = D("Usersafetyinformation");
		    $list = $Usersafetyinformation->where("UserID=".session("UserID"))->select();
			$this->assign("list",$list);
			$this->display();
		}
	}
	
	public function anquantiwen(){     //安全提问和回答设置
	    
		header("Content-Type:text/html; charset=utf-8");
		
		if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			exit;
			
		}
		
		$Usersafetyinformation = D("Usersafetyinformation");
		
		$data["AffirmTitle"] = $this->_post("AffirmTitle");
		$data["AffirmAnswer"] = $this->_post("AffirmAnswer");
		
		$list = $Usersafetyinformation->where("UserID=".session("UserID"))->save($data);
		if($list >= 0){
			exit("<script type='text/javascript'>alert('修改成功！'); location.href='/User_Index_aqxx.html'</script>");
		}else{
			exit("<script type='text/javascript'>alert('修改失败，请稍后再试！'); history.go(-1);</script>");
		}
		
	}
	
	
	public function EditLoginPassWord(){     //修改登录密码
	    
		header("Content-Type:text/html; charset=utf-8");
		
		if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			exit;
			
		}
		
		$User = D("User");
		
		if($this->_post("Y_LoginPassWord") == ""){
			
			exit("<script type='text/javascript'>alert('原密码不能为空！'); history.go(-1);</script>");
		
		}else{
			
			$list = $User->where("id=".session("UserID")." and LoginPassWord = '".md5($this->_post("Y_LoginPassWord"))."'")->select();
			if(!$list){
				exit("<script type='text/javascript'>alert('原密码错误！'); history.go(-1);</script>");
			}else{
				if($this->_post("X_LoginPassWord") == ""){
					exit("<script type='text/javascript'>alert('新密码不能为空！'); history.go(-1);</script>");
				}else{
					if($this->_post("X_LoginPassWord") != $this->_post("XX_LoginPassWord")){
						exit("<script type='text/javascript'>alert('两次新密码输入不一致！'); history.go(-1);</script>");
					}else{
						$data["LoginPassWord"] = md5($this->_post("X_LoginPassWord"));
						$list = $User->where("id=".session("UserID"))->save($data);
						if($list >= 0){
							exit("<script type='text/javascript'>alert('登录密码修改成功！'); location.href='/User_Index_aqxx.html'</script>");
						}else{
							exit("<script type='text/javascript'>alert('修改失败，请稍后再试！'); history.go(-1);</script>");
						}
					}
				}
			}
		}
		
	}
	
	
	public function EditPayPassWord(){      //修改支付密码
		header("Content-Type:text/html; charset=utf-8");
		
		if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			exit;
			
		}
		
		$Usersafetyinformation = D("Usersafetyinformation");
		
		if($this->_post("Y_PayPassWord") == ""){
			
			exit("<script type='text/javascript'>alert('原密码不能为空！'); history.go(-1);</script>");
		
		}else{
			
			$list = $Usersafetyinformation->where("UserID=".session("UserID")." and PayPassWord = '".md5($this->_post("Y_PayPassWord"))."'")->select();
			if(!$list){
				exit("<script type='text/javascript'>alert('原密码错误！'); history.go(-1);</script>");
			}else{
				if($this->_post("X_PayPassWord") == ""){
					exit("<script type='text/javascript'>alert('新密码不能为空！'); history.go(-1);</script>");
				}else{
					if($this->_post("X_PayPassWord") != $this->_post("XX_PayPassWord")){
						exit("<script type='text/javascript'>alert('两次新密码输入不一致！'); history.go(-1);</script>");
					}else{
						$data["PayPassWord"] = md5($this->_post("X_PayPassWord"));
						$list = $Usersafetyinformation->where("UserID=".session("UserID"))->save($data);
						if($list >= 0){
							exit("<script type='text/javascript'>alert('支付密码修改成功！'); location.href='/User_Index_aqxx.html'</script>");
						}else{
							exit("<script type='text/javascript'>alert('修改失败，请稍后再试！'); history.go(-1);</script>");
						}
					}
				}
			}
		}
		
	}
	
	
	public function tkyh(){
		
		
		$Bank = D("Bank");
		
        $banktype = $this->_get("banktype");
        
        $tkyhtypename = "";
        
        if($banktype == 0){
            $tkyhtypename = "提款银行";
        }else{
            $tkyhtypename = "委托提款银行";
        }
        
		$list = $Bank->where("UserID=".session("UserID")." and banktype = ".$banktype)->select();
        
        $ytjtkyh = $Bank->where("UserID=".session("UserID")." and banktype = ".$banktype)->count();
        
        $Userbasicinformation = M("Userbasicinformation");
        
        $Compellation = $Userbasicinformation->where("UserID=".session("UserID"))->getField("Compellation");
        
        $Tkconfig = M("Tkconfig");
        
        $wtyh = $Tkconfig->getField("wtyh");
        
        $this->assign("Compellation",$Compellation);
        
        $this->assign("wtyh",$wtyh);
        
        $this->assign("ytjtkyh",$ytjtkyh);
        
        $this->assign("tkyhtypename",$tkyhtypename);
        
        $this->assign("banktype",$banktype);
		
		$this->assign("list",$list);
		
	    $this->display();
	}
	
	public function AddBank(){       //添加提款银行
	
	header("Content-Type:text/html; charset=utf-8");
	
	if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			exit;
			
		}
	
	    $Bank = M("Bank");
		
        if($this->_post("moren") == 1 ){
            
             $data["moren"] = 0;
        
             $Bank->where("UserID=".$this->_post("UserID"))->save($data);
        }
        
       
        
		$Bank->create();
		
		$list = $Bank->add();
		
		if($list){
		    exit("<script type='text/javascript'>alert('添加成功！'); location.href='/User_Index_tkyh_banktype_".$this->_get("banktype").".html'</script>");
		}else{
			exit("<script type='text/javascript'>alert('添加失败！'); history.go(-1);</script>");
		}
		
	
	}
	
	public function DelBank(){      //删除提款银行
	
	    header("Content-Type:text/html; charset=utf-8");
		
		if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			exit;
			
		}
		
	     $Bank = M("Bank");
		 
		 $list = $Bank->where("id=".$this->_get("id")." and UserID = ".session("UserID"))->delete();
		 
		if($list){
		    exit("<script type='text/javascript'>alert('删除成功！'); location.href='/User_Index_tkyh_banktype_".$this->_get("banktype").".html'</script>");
		}else{
			exit("<script type='text/javascript'>alert('删失败！'); history.go(-1);</script>");
		}
	
	}
	
	
	public function edittkyh(){
		
		if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			exit;
			
		}
		
		$Bank = M("Bank");
		
		$list = $Bank->where("id=".$this->_get("id"))->select();
		
		$this->assign("list",$list);
        
        $this->assign("banktype",$this->_get("banktype"));
		
	    $this->display();
	}
	
	public function AddEditthyh(){
		header("Content-Type:text/html; charset=utf-8");
		
		if(!session("?UserName") || !session("?UserType") || !session("?UserID")){
			
			$this->display("Index:exit");
			exit;
			
		}
		
		$Bank = M("Bank");
		
        if($this->_post("moren") == 1 ){
            
             $data["moren"] = 0;
        
             $Bank->where("UserID=".session("UserID"))->save($data);
        }
        
        
		$Bank->create();
		
		if($Bank->save()){
			exit("<script type='text/javascript'>alert('修改成功！'); location.href='/User_Index_tkyh_banktype_".$this->_get("banktype").".html'</script>");
		}else{
			exit("<script type='text/javascript'>alert('修改失败！'); history.go(-1);</script>");
		}
	}
	
    
    
    public function shjk(){   //商户接口首页
    
        $Userapiinformation = D("Userapiinformation");  
        $list = $Userapiinformation->where("UserID=".session("UserID"))->select();
        $this->assign('list',$list);
        
        $this->display();
    }
    
    public function sqtjl(){
        $Userapiinformation = D("Userapiinformation"); 
        $zt = $Userapiinformation->where("UserID=".session("UserID"))->getField("Zt");   
        
        
        
        $Tkconfig = M("Tkconfig");
          
        $tjlnf = $Tkconfig->where("UserID=0")->getField("tjlnf");
        
         $Money = D("Money");
         $Money_Money =  $Money->where("UserID=".session("UserID"))->getField("Money");
        
        $t0 = 0;  //默认T+0是关闭的
        
        $Kttjl = D("Kttjl");
        $datedate = date("Y-m-d",time());
        $Kttjlcount = $Kttjl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count();  
        if($Kttjlcount >0){
             $t0 = 1;
             $list =  $Kttjl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->select();
             $listls =  $Kttjl->where("DATEDIFF('".$datedate."',js_date) > 0 and UserID=".session("UserID"))->select(); 
        } 
        
        $this->assign("zt",$zt); 
        $this->assign("tjlnf",$tjlnf);   
        $this->assign("Money",$Money_Money);
        $this->assign("t0",$t0);
        $this->assign("list",$list);
        $this->assign("listls",$listls);
        $this->display();
    }
    
    public function wyfljl(){
        $Userapiinformation = D("Userapiinformation"); 
        $zt = $Userapiinformation->where("UserID=".session("UserID"))->getField("Zt");   
        
        
        
        //$Tkconfig = M("Tkconfig");
          
       // $tjlnf = $Tkconfig->where("UserID=0")->getField("tjlnf");
        
         $Money = D("Money");
         $Money_Money =  $Money->where("UserID=".session("UserID"))->getField("Money");
        
        $t0 = 0;  //默认T+0是关闭的
        
        $Wyfljl = D("Wyfljl");
        $datedate = date("Y-m-d",time());
        $Kttjlcount = $Wyfljl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count();  
        if($Kttjlcount >0){
             $t0 = 1;
             $list =  $Wyfljl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->select();
             $listls =  $Wyfljl->where("DATEDIFF('".$datedate."',js_date) > 0 and UserID=".session("UserID"))->select(); 
        } 
        
        $this->assign("zt",$zt); 
        $this->assign("tjlnf",$tjlnf);   
        $this->assign("Money",$Money_Money);
        $this->assign("t0",$t0);
        $this->assign("list",$list);
        $this->assign("listls",$listls);
        $this->display();
    }
    
    public function sqtjlok(){  //确认开通T+0
    
        $Tkconfig = M("Tkconfig");
          
        $tjlnf = $Tkconfig->where("UserID=0")->getField("tjlnf");  //T+0年费
        
        $Money = D("Money");
        
        $Money_Money =  $Money->where("UserID=".session("UserID"))->getField("Money");  //商户账户余额
        
        if($tjlnf > $Money_Money){
            exit("1"); //余额不足
        }
        
        $Usersafetyinformation = M("Usersafetyinformation");
        
        $PayPassWord = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("PayPassWord");
        
        if($PayPassWord != md5($this->_post("paypassword"))){
            exit("2");  //支付密码错误
        }
        
        

             $Kttjl = D("Kttjl");
             $Kttjlcount = $Kttjl->where("UserID=".session("UserID"))->count();
             if($Kttjlcount <=0){
                $data["UserID"] = session("UserID");
                $data["Money"] = $tjlnf;
                $data["sq_date"] = date('Y-m-d H:i:s',time());
                $data["ks_date"] = date('Y-m-d',time());
                $data["js_date"] = date('Y-m-d',strtotime("+1 years"));
                $Kttjl->add($data);
                
                 $Usersafetyinformation = M("Usersafetyinformation");
                 $data["t0"] = 1;
                 $list = $Usersafetyinformation->where("UserID=".session("UserID"))->save($data);
                
                $data["Money"] = $Money_Money - $tjlnf; 
                $list = $Money->where("UserID=".session("UserID"))->save($data); //更新余额  
                
             }else{
                 $datedate = date("Y-m-d",time());
                 $Kttjlcount = $Kttjl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count();
                 if($Kttjlcount <=0){
                     $data["UserID"] = session("UserID");
                     $data["Money"] = $tjlnf;
                     $data["sq_date"] = date('Y-m-d H:i:s',time());
                     $data["ks_date"] = date('Y-m-d',time());
                     $data["js_date"] = date('Y-m-d',strtotime("+1 years"));
                     $Kttjl->add($data);
                     
                     $Usersafetyinformation = M("Usersafetyinformation");
                     $data["t0"] = 1;
                     $list = $Usersafetyinformation->where("UserID=".session("UserID"))->save($data);
                     
                     $data["Money"] = $Money_Money - $tjlnf; 
                     $list = $Money->where("UserID=".session("UserID"))->save($data);   //更新余额  
                     
                 }else{
                      exit("3"); 
                 }
                 
             }

        exit("4");
    
    }
    
   public function gmflok(){  //确认开通T+0
    
      
          
        $tjlnf = $this->_post("jemoney");  //T+0年费
        
        $Money = D("Money");
        
        $Money_Money =  $Money->where("UserID=".session("UserID"))->getField("Money");  //商户账户余额
        
        if($tjlnf > $Money_Money){
            exit("1"); //余额不足
        }
        
        $Usersafetyinformation = M("Usersafetyinformation");
        
        $PayPassWord = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("PayPassWord");
        
        if($PayPassWord != md5($this->_post("paypassword"))){
            exit("2");  //支付密码错误
        }
        
        

             $Wyfljl = D("Wyfljl");
             $Kttjlcount = $Wyfljl->where("UserID=".session("UserID"))->count();
             if($Kttjlcount <=0){
                $data["UserID"] = session("UserID");
                $data["Money"] = $tjlnf;
                $data["sq_date"] = date('Y-m-d H:i:s',time());
                $data["ks_date"] = date('Y-m-d',time());
                $data["js_date"] = date('Y-m-d',strtotime("+1 years"));
                $data["fl"] = $this->_post("fl");
                $Wyfljl->add($data);
                
                 $Paycost = M("Paycost");
                 $data["wy"] = $this->_post("fl");
                 $list = $Paycost->where("UserID=".session("UserID"))->save($data);
                
                $data["Money"] = $Money_Money - $tjlnf; 
                $list = $Money->where("UserID=".session("UserID"))->save($data); //更新余额  
                
             }else{
                 $datedate = date("Y-m-d",time());
                 $Kttjlcount = $Wyfljl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count();
                 if($Kttjlcount <=0){
                     $data["UserID"] = session("UserID");
                     $data["Money"] = $tjlnf;
                     $data["sq_date"] = date('Y-m-d H:i:s',time());
                     $data["ks_date"] = date('Y-m-d',time());
                     $data["js_date"] = date('Y-m-d',strtotime("+1 years"));
                     $data["fl"] = $this->_post("fl");
                     $Wyfljl->add($data);
                     
                      $Paycost = M("Paycost");
                      $data["wy"] = $this->_post("fl");
                      $list = $Paycost->where("UserID=".session("UserID"))->save($data);
                     
                     $data["Money"] = $Money_Money - $tjlnf; 
                     $list = $Money->where("UserID=".session("UserID"))->save($data);   //更新余额  
                     
                 }else{
                      exit("3"); 
                 }
                 
             }

        exit("4");
    
    }
    
    /*
    public function shjkEdit(){   //修改商户基本信息
    header("Content-Type:text/html; charset=utf-8");
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2097152;// 设置附件上传大小      5M
        $upload->allowExts  = array('jpg','gif','tmp','jpeg');// 设置附件上传类型
        $upload->savePath =  './Public/Uploads/';// 设置附件上传目录
       // $upload->saveRule = 'com_create_guid';
        if(!$upload->upload()) {// 上传错误提示错误信息
            //$this->error($upload->getErrorMsg());
           // exit($upload->getErrorMsg());
            $this->assign("msgTitle","");
            $this->assign("message",$upload->getErrorMsg());
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","User_Index_shjk.html");
            $this->display("Index:success");
            exit;
        }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
        }
        
        $Userapiinformation = D("Userapiinformation");
        $Userapiinformation->create();
        $Userapiinformation->IdentificationFront = $info[0]["savename"];
        $Userapiinformation->IdentificationReverse  = $info[1]["savename"];
        if(session("UserType") == 1){
               $Userapiinformation->BusinessLicense = $info[2]["savename"];  
        }
        
         $Userapiinformation = D("Userapiinformation");
        $Userapiinformation->create();
        switch($this->_post("scnumber")){
            case 0:
                $Userapiinformation->IdentificationFront = $info[0]["savename"];
                $tsname = "本人的身份证（正面）上传成功";
                break;
            case 1:
                $Userapiinformation->IdentificationReverse  = $info[0]["savename"];
                $tsname = "本人的身份证（反面）上传成功";
                break;
            case 2:
                $Userapiinformation->scsfzbsz  = $info[0]["savename"];
                $tsname = "手持身份证半身照片上传成功";
                break;     
            case 3:
                $Userapiinformation->BusinessLicense = $info[0]["savename"];
                $tsname = "公司营业执照上传成功";
                break;
        }
        $list = $Userapiinformation->where("UserID=".session("UserID"))->save();
       
        if($list){
            $this->assign("msgTitle","");
            $this->assign("message",$tsname);
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","User_Index_shjk.html");
            $this->display("Index:success");
        }else{
            $this->assign("msgTitle","");
            $this->assign("message","操作失败！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","User_Index_shjk.html");
            $this->display("Index:success"); 
        }
    }
    */
    
     public function shjkEdits(){   //修改商户基本信息
        header("Content-Type:text/html; charset=utf-8");
        
        $Userapiinformation = D("Userapiinformation");
        $Userapiinformation->create();
        
        $data["CompanyName"] = $this->_post("CompanyName");
        $data["WebsiteName"] = $this->_post("WebsiteName");
        $data["WebsiteUrl"] = $this->_post("WebsiteUrl");
               
        $list = $Userapiinformation->where("UserID=".session("UserID"))->save($data);
        
        if($list){
            $this->assign("msgTitle","");
            $this->assign("message","商户接口基本信息修改成功！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","User_Index_shjk.html");
            $this->display("Index:success");
        }else{
            $this->assign("msgTitle","");
            $this->assign("message","操作失败！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","User_Index_shjk.html");
            $this->display("Index:success"); 
        }
    }
    
    
    public function shjksh(){      //提交审核
    header("Content-Type:text/html; charset=utf-8");
        $Userapiinformation = D("Userapiinformation");
        ////////////////////////////////////////////////////////////////////
          $WebsiteUrl = $Userapiinformation->where("UserID=".session("UserID"))->getField("WebsiteUrl");
           $CompanyName = $Userapiinformation->where("UserID=".session("UserID"))->getField("CompanyName");
           $WebsiteName = $Userapiinformation->where("UserID=".session("UserID"))->getField("WebsiteName");
           $IdentificationFront = $Userapiinformation->where("UserID=".session("UserID"))->getField("IdentificationFront"); 
           $IdentificationReverse = $Userapiinformation->where("UserID=".session("UserID"))->getField("IdentificationReverse");
           $BusinessLicense = $Userapiinformation->where("UserID=".session("UserID"))->getField("BusinessLicense");
           
           //if($WebsiteUrl == "" || $WebsiteUrl == NULL || $CompanyName == "" || $CompanyName == NULL || $WebsiteName == "" || $WebsiteName == NULL || $IdentificationFront == "" || $IdentificationFront == NULL || $IdentificationReverse == "" || $IdentificationReverse == NULL){
               if($WebsiteUrl == "" || $WebsiteUrl == NULL || $CompanyName == "" || $CompanyName == NULL || $WebsiteName == "" || $WebsiteName == NULL){
               
    if(session("UserType") == 1 && ($BusinessLicense == "" || $BusinessLicense == NULL)){
                  exit("<script type='text/javascript'>alert('请完善审核信息后再提交审核！'); history.go(-1);</script>");
              }
               
               
           }
        ///////////////////////////////////////////////////////////////////
        
        
        
        $data["Zt"] = 1;
        $list = $Userapiinformation->where("UserID=".session("UserID"))->save($data);
        if($list){
            $this->assign("msgTitle","");
            $this->assign("message","商户接口基本信息已提交审核，请等待审核结果！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","User_Index_shjk.html");
            $this->display("Index:success");
        }else{
            $this->assign("msgTitle","");
            $this->assign("message","操作失败！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","User_Index_shjk.html");
            $this->display("Index:success");
        }
    }
    
    public function shjkshtg(){  //审核通过
           $Userapiinformation = D("Userapiinformation");
           $WebsiteUrl = $Userapiinformation->where("UserID=".$this->_post("UserID"))->getField("WebsiteUrl");
           $CompanyName = $Userapiinformation->where("UserID=".$this->_post("UserID"))->getField("CompanyName");
           $WebsiteName = $Userapiinformation->where("UserID=".$this->_post("UserID"))->getField("WebsiteName");
           $IdentificationFront = $Userapiinformation->where("UserID=".$this->_post("UserID"))->getField("IdentificationFront"); 
           $IdentificationReverse = $Userapiinformation->where("UserID=".$this->_post("UserID"))->getField("IdentificationReverse");
           $BusinessLicense = $Userapiinformation->where("UserID=".$this->_post("UserID"))->getField("BusinessLicense");
           
           if($WebsiteUrl == "" || $WebsiteUrl == NULL || $CompanyName == "" || $CompanyName == NULL || $WebsiteName == "" || $WebsiteName == NULL || $IdentificationFront == "" || $IdentificationFront == NULL || $IdentificationReverse == "" || $IdentificationReverse == NULL || $BusinessLicense == "" || $BusinessLicense == NULL){
               exit("<script type='text/javascript'>alert('请完善审核信息后再提交审核！'); history.go(-1);</script>");
           }
           
           $User = M("User");
           $UserName = $User->where("id = ".$this->_post("UserID"))->getField("UserName");
           $data["Zt"] = 2;
           $data["Key"] = md5($UserName.$WebsiteUrl);
           $list = $Userapiinformation->where("UserID=".$this->_post("UserID"))->save($data);
        if($list){
            //$this->assign("msgTitle","");
           // $this->assign("message","商户接口审核通过");
           // $this->assign("waitSecond",3);
           // $this->assign("jumpUrl","User_Index_shjk.html");
            //$this->display("Index:success");
            echo "ok";
        }else{
            //$this->assign("msgTitle","");
           // $this->assign("message","操作失败！");
           // $this->assign("waitSecond",3);
           // $this->assign("jumpUrl","User_Index_shjk.html");
           // $this->display("Index:success");
           echo "no";
        }
    }
    
    
    public function czsxf(){
        
        $Paycost = M("Paycost");
        
        $list = $Paycost->where("UserID=0")->select();
        
        $listuser = $Paycost->where("UserID=".session("UserID"))->select();
        
        if(!$listuser){
            
            $data["UserID"] = session("UserID");
            
            $Paycost->add($data);
            
            $listuser = $Paycost->where("UserID=".session("UserID"))->select();
        }
        
        $this->assign("list",$list);
        $this->assign("listuser",$listuser);
        
        $this->display();
        
    }
    
    
    public function chongzhi(){     //平台冲值
        
        $Userapiinformation = D("Userapiinformation");
    $Sjt_Key = $Userapiinformation->where("UserID=".session("UserID"))->getField("Key");
        
        $Sjt_Merchant_url = "http://".C("WEB_URL")."/Index_Merchanturl.html";
        $Sjt_Return_url = "http://".C("WEB_URL")."/Index_returnurl.html";
        
        $this->assign("Sjt_Merchant_url",$Sjt_Merchant_url);
        $this->assign("Sjt_Return_url",$Sjt_Return_url);
        $this->assign("Sjt_MerchantID",intval(session("UserID"))+10000);
        $this->assign("Sjt_Key",$Sjt_Key);
        $this->display();
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
           // md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$_factMoney.$_SuccTime."1".$Sjt_Key);
    
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
    
    public function tksxf(){
        
        $Tkfl = M("Tkfl");
        
        
        $list0 = $Tkfl->where("T=0")->order("k_money asc")->select();
        
        $list1 = $Tkfl->where("T=1")->order("k_money asc")->select();
        
        
        $this->assign("list0",$list0);
        
        $this->assign("list1",$list1);
        
        $this->display();
    }
    
    
    public function mbk(){
        
        $User = M("User");
        
        $mbk = $User->where("id=".session("UserID"))->getField("mbk");
        
        $Passwordblock = M("Passwordblock");
        
        $list = $Passwordblock->where("UserID=".session("UserID"))->select();
        
        if(!$list){
           
           $Arraylist = array("A","B","C","D","E","F","G","H","I");
        
            for($i = 0; $i < 9; $i++){
                for($j = 1; $j < 10; $j++){
                    $data[$Arraylist[$i].$j] = "";
                 }
            }
            
            $data["UserID"] = session("UserID");
            
            $Passwordblock->add($data);
            
            $list = $Passwordblock->where("UserID=".session("UserID"))->select();
        }
        
        
        $A1 = $Passwordblock->where("UserID=".session("UserID"))->getField("A1");
        
        $this->assign("mbk",$mbk);
        $this->assign("A1",$A1);
        $this->assign("list",$list);
        
        $this->display();
    }
    
    
    public function mbkqty(){
        
        $mbk = $this->_post("mbk");
        
        $User = M("User");
       
        $data["mbk"] = $mbk;
                   
        if($User->where("id=".session("UserID"))->save($data)){
            exit("ok");
        }else{
            exit("no");
            
        }
    }
    
    public function scmbk(){
        
        $Passwordblock = M("Passwordblock");
        
        $Arraylist = array("A","B","C","D","E","F","G","H","I");
        
        for($i = 0; $i < 9; $i++){
            for($j = 1; $j < 10; $j++){
                $data[$Arraylist[$i].$j] = rand(10,99);
            }
        }
        
        $Passwordblock->where("UserID=".session("UserID"))->save($data);
        
        $this->assign("msgTitle","");
        $this->assign("message","新的密保卡生成成功！");
        $this->assign("waitSecond",3);
        $this->assign("jumpUrl","User_Index_mbk.html");
        $this->display("Index:success");
        
    }
    
    public function ncwmbk(){
      
    Header("Content-type:image/gif; charset=utf-8");
    Header("Content-Disposition:attachment;filename=mbk.gif");
    $im = imagecreate(500,500);
    $black = ImageColorAllocate($im,255,255,255);
    $white = ImageColorAllocate($im,94,92,92);
    $abc = ImageColorAllocate($im,39,161,214);
    
    $ccc = ImageColorAllocate($im,224,227,248);
    
    //$str = chr(0xE7).chr(0x9B).chr(0x9B).chr(0xE6).chr(0x8D).chr(0xB7).chr(0xE9).chr(0x80).chr(0x9A).chr(0xE5).chr(0xAF).chr(0x86).chr(0xE4).chr(0xBF).chr(0x9D).chr(0xE5).chr(0x8D).chr(0xA1);
   
    //$str = chr(0xE9).chr(0x9B).chr(0xB6).chr(0xE6).chr(0x94).chr(0xAF).chr(0xE4).chr(0xBB).chr(0x98).chr(0xE5).chr(0xAF).chr(0x86).chr(0xE4).chr(0xBF).chr(0x9D).chr(0xE5).chr(0x8D).chr(0xA1);  
     //$str = chr(0xE5).chr(0xAF).chr(0x86).chr(0xE4).chr(0xBF).chr(0x9D).chr(0xE5).chr(0x8D).chr(0xA1);                                                                                                                                                                                                                                                              
    $str=C("WEB_NAME"); 
    ImageTTFText($im,50,25,150,350,$ccc,"Fonts/simhei.ttf",$str);
    
    $hei = ImageColorAllocate($im,0,0,0);
    imagerectangle($im,0,0,499,499,$hei);
    
    imageline($im,0,49,498,49,$hei);
    imageline($im,0,98,498,98,$hei);
    imageline($im,0,147,498,147,$hei);
    imageline($im,0,196,498,196,$hei);
    imageline($im,0,245,498,245,$hei);
    imageline($im,0,294,498,294,$hei);
    imageline($im,0,343,498,343,$hei);
    imageline($im,0,392,498,392,$hei);
    imageline($im,0,441,498,441,$hei);
    
    imagerectangle($im,0,0,50,499,$hei);
    imagerectangle($im,0,0,100,499,$hei);
    imagerectangle($im,0,0,150,499,$hei);
    imagerectangle($im,0,0,200,499,$hei);
    imagerectangle($im,0,0,250,499,$hei);
    imagerectangle($im,0,0,300,499,$hei);
    imagerectangle($im,0,0,350,499,$hei);
    imagerectangle($im,0,0,400,499,$hei);
    imagerectangle($im,0,0,450,499,$hei);
    
    ImageTTFText($im,25,0,65,35,$abc,"Fonts/cambriab.ttf","A");
    ImageTTFText($im,25,0,115,35,$abc,"Fonts/cambriab.ttf","B");
    ImageTTFText($im,25,0,165,35,$abc,"Fonts/cambriab.ttf","C");
    ImageTTFText($im,25,0,215,35,$abc,"Fonts/cambriab.ttf","D");
    ImageTTFText($im,25,0,265,35,$abc,"Fonts/cambriab.ttf","E");
    ImageTTFText($im,25,0,315,35,$abc,"Fonts/cambriab.ttf","F");
    ImageTTFText($im,25,0,365,35,$abc,"Fonts/cambriab.ttf","G");
    ImageTTFText($im,25,0,415,35,$abc,"Fonts/cambriab.ttf","H");
    ImageTTFText($im,25,0,465,35,$abc,"Fonts/cambriab.ttf","I");
    
    ImageTTFText($im,25,0,18,85,$abc,"Fonts/cambriab.ttf","1");
    ImageTTFText($im,25,0,18,135,$abc,"Fonts/cambriab.ttf","2");
    ImageTTFText($im,25,0,18,185,$abc,"Fonts/cambriab.ttf","3");
    ImageTTFText($im,25,0,18,235,$abc,"Fonts/cambriab.ttf","4");
    ImageTTFText($im,25,0,18,285,$abc,"Fonts/cambriab.ttf","5");
    ImageTTFText($im,25,0,18,335,$abc,"Fonts/cambriab.ttf","6");
    ImageTTFText($im,25,0,18,385,$abc,"Fonts/cambriab.ttf","7");
    ImageTTFText($im,25,0,18,435,$abc,"Fonts/cambriab.ttf","8");
    ImageTTFText($im,25,0,18,485,$abc,"Fonts/cambriab.ttf","9");
    
    
    
    $Passwordblock = M("passwordblock");
     
    
    
    /////////////////////////////////////////////////////////////
    ImageTTFText($im,20,0,60,85,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("A1"));
    ImageTTFText($im,20,0,110,85,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("B1"));
    ImageTTFText($im,20,0,160,85,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("C1"));
    ImageTTFText($im,20,0,210,85,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("D1"));
    ImageTTFText($im,20,0,260,85,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("E1"));
    ImageTTFText($im,20,0,310,85,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("F1"));
    ImageTTFText($im,20,0,360,85,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("G1"));
    ImageTTFText($im,20,0,410,85,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("H1"));
    ImageTTFText($im,20,0,460,85,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("I1"));
    
    ImageTTFText($im,20,0,60,135,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("A2"));
    ImageTTFText($im,20,0,110,135,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("B2"));
    ImageTTFText($im,20,0,160,135,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("C2"));
    ImageTTFText($im,20,0,210,135,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("D2"));
    ImageTTFText($im,20,0,260,135,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("E2"));
    ImageTTFText($im,20,0,310,135,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("F2"));
    ImageTTFText($im,20,0,360,135,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("G2"));
    ImageTTFText($im,20,0,410,135,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("H2"));
    ImageTTFText($im,20,0,460,135,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("I2"));
    
    ImageTTFText($im,20,0,60,185,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("A3"));
    ImageTTFText($im,20,0,110,185,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("B3"));
    ImageTTFText($im,20,0,160,185,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("C3"));
    ImageTTFText($im,20,0,210,185,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("D3"));
    ImageTTFText($im,20,0,260,185,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("E3"));
    ImageTTFText($im,20,0,310,185,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("F3"));
    ImageTTFText($im,20,0,360,185,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("G3"));
    ImageTTFText($im,20,0,410,185,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("H3"));
    ImageTTFText($im,20,0,460,185,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("I3"));
    
    ImageTTFText($im,20,0,60,235,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("A4"));
    ImageTTFText($im,20,0,110,235,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("B4"));
    ImageTTFText($im,20,0,160,235,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("C4"));
    ImageTTFText($im,20,0,210,235,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("D4"));
    ImageTTFText($im,20,0,260,235,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("E4"));
    ImageTTFText($im,20,0,310,235,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("F4"));
    ImageTTFText($im,20,0,360,235,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("G4"));
    ImageTTFText($im,20,0,410,235,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("H4"));
    ImageTTFText($im,20,0,460,235,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("I4"));
    
    ImageTTFText($im,20,0,60,285,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("A5"));
    ImageTTFText($im,20,0,110,285,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("B5"));
    ImageTTFText($im,20,0,160,285,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("C5"));
    ImageTTFText($im,20,0,210,285,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("D5"));
    ImageTTFText($im,20,0,260,285,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("E5"));
    ImageTTFText($im,20,0,310,285,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("F5"));
    ImageTTFText($im,20,0,360,285,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("G5"));
    ImageTTFText($im,20,0,410,285,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("H5"));
    ImageTTFText($im,20,0,460,285,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("I5"));
    
    ImageTTFText($im,20,0,60,335,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("A6"));
    ImageTTFText($im,20,0,110,335,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("B6"));
    ImageTTFText($im,20,0,160,335,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("C6"));
    ImageTTFText($im,20,0,210,335,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("D6"));
    ImageTTFText($im,20,0,260,335,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("E6"));
    ImageTTFText($im,20,0,310,335,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("F6"));
    ImageTTFText($im,20,0,360,335,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("G6"));
    ImageTTFText($im,20,0,410,335,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("H6"));
    ImageTTFText($im,20,0,460,335,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("I6"));
    
    ImageTTFText($im,20,0,60,385,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("A7"));
    ImageTTFText($im,20,0,110,385,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("B7"));
    ImageTTFText($im,20,0,160,385,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("C7"));
    ImageTTFText($im,20,0,210,385,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("D7"));
    ImageTTFText($im,20,0,260,385,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("E7"));
    ImageTTFText($im,20,0,310,385,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("F7"));
    ImageTTFText($im,20,0,360,385,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("G7"));
    ImageTTFText($im,20,0,410,385,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("H7"));
    ImageTTFText($im,20,0,460,385,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("I7"));
    
    ImageTTFText($im,20,0,60,435,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("A8"));
    ImageTTFText($im,20,0,110,435,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("B8"));
    ImageTTFText($im,20,0,160,435,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("C8"));
    ImageTTFText($im,20,0,210,435,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("D8"));
    ImageTTFText($im,20,0,260,435,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("E8"));
    ImageTTFText($im,20,0,310,435,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("F8"));
    ImageTTFText($im,20,0,360,435,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("G8"));
    ImageTTFText($im,20,0,410,435,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("H8"));
    ImageTTFText($im,20,0,460,435,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("I8"));
    
    ImageTTFText($im,20,0,60,485,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("A9"));
    ImageTTFText($im,20,0,110,485,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("B9"));
    ImageTTFText($im,20,0,160,485,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("C9"));
    ImageTTFText($im,20,0,210,485,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("D9"));
    ImageTTFText($im,20,0,260,485,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("E9"));
    ImageTTFText($im,20,0,310,485,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("F9"));
    ImageTTFText($im,20,0,360,485,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("G9"));
    ImageTTFText($im,20,0,410,485,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("H9"));
    ImageTTFText($im,20,0,460,485,$white,"Fonts/cambriab.ttf",$Passwordblock->where("UserID=".session("UserID"))->getField("I9"));
    ////////////////////////////////////////////////////////////

    imagegif($im);
    ImageDestroy($im);
        
    }
    
    
    public function npay(){
        
        
        $this->display();
        
    }
    
    public function npdy(){
        $Paycost = M("Paycost");
        
        $nbzz = $Paycost->where("UserID=".session("UserID"))->getField("nbzz");
        
        if($nbzz == 0){
            
            $nbzz = $Paycost->where("UserID=0")->getField("nbzz");
            
            if($nbzz == 0){
                
                $nbzz = 1;
                
            }
            
        }
        
        $nbzz = (1-$nbzz)*100;
        
        $nbzz = $nbzz."%";
        
        $this->assign("nbzz",$nbzz);
        $this->display();

    }
    
    public function npdycheck(){
        
        $User = M("User");
        
        $UserID =$User->where("UserName='".$this->_post("UserName")."'")->getField("id");
        
        if($UserID){
            
            if(session("UserName") == $this->_post("UserName")){
                
                exit("不能自己给自己转账！");
                
            }else{
                
                exit("ok");
                
            }
            
        }else{
            
            exit("转账账号不存在！");
            
        }
    }
    
    public function npdycheckxm(){
        
        $User = M("User");
        
        $UserID =$User->where("UserName='".$this->_post("UserName")."'")->getField("id");
        
        $Userbasicinformation = M("Userbasicinformation");
        
        $Compellation = $Userbasicinformation->where("UserID=".$UserID)->getField("Compellation"); 
        
        exit($Compellation);

    }
    
    public function kymoney(){
        
        $Money = M("Money");
        
        $kymoney = $Money->where("UserID=".session("UserID"))->getField("Money");
        
        exit($kymoney);
    }
    
    
    public function yzzfmm(){
        
        $Usersafetyinformation = M("Usersafetyinformation");
        
        $PayPassWord = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("PayPassWord");
        
        if($PayPassWord == md5($this->_post("paypassword"))){
            echo 1;
			exit;
        }else{
            exit("no");
        }
        
    }
    
    public function npaycl(){
        
        
        $User = M("User");
        
        $UserID =$User->where("UserName='".$this->_post("UserName")."'")->getField("id");
        
        if($UserID){
            
            if(session("UserName") == $this->_post("UserName")){
                
                exit("不能自己给自己转账！");
                
            }else{
                
                //exit("ok");
                //////////////////////////////////////////////////////////////////
                $Money = M("Money");
        
                $kymoney = $Money->where("UserID=".session("UserID"))->getField("Money");
        
                if($this->_post("money") > $kymoney){
                    exit("余额不足！".$kymoney."--------".$this->_post("money"));
                }else{
                    
                    ///////////////////////////////////////////////////////////
                    
                     $Usersafetyinformation = M("Usersafetyinformation");
        
                     $PayPassWord = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("PayPassWord");
                        
                     if($PayPassWord == md5($this->_post("paypassword"))){
                            //exit("ok");
                            
    /////////////////////////////////////////////////////////////////////////////////
     $Paycost = M("Paycost");
        
        $nbzz = $Paycost->where("UserID=".session("UserID"))->getField("nbzz");
        
        if($nbzz == 0){
            
            $nbzz = $Paycost->where("UserID=0")->getField("nbzz");
            
            if($nbzz == 0){
                
                $nbzz = 1;
                
            }
            
        }                        
    /////////////////////////////////////////////////////////////////////////////////
    $Money = M("Money");
    
    $zmoney = $Money->where("UserID=".$UserID)->getField("Money");
    
    $data["Money"] = $zmoney + $this->_post("money")*$nbzz;
    
    $Money->where("UserID=".$UserID)->save($data);
    
    $cmoney = $Money->where("UserID=".session("UserID"))->getField("Money");
    
    $data["Money"] = $cmoney - $this->_post("money");
    
    $Money->where("UserID=".session("UserID"))->save($data);
    
     ///////////////////////////////////////////////////////////
     $Moneydb = M("Moneybd");
     $data["UserID"] = $UserID;
     $data["money"] = $this->_post("money")*$nbzz;
     $data["ymoney"] = $zmoney;    //原金额
     $data["gmoney"] = $zmoney + $this->_post("money")*$nbzz;    //变动后金额
     $data["datetime"] = date("Y-m-d H:i:s");
     $data["lx"] = 3;
     $Moneydb->add($data);
     
     $Moneydb = M("Moneybd");
     $data["UserID"] = session("UserID");
     $data["money"] = $this->_post("money")*(-1);
     $data["ymoney"] = $cmoney;    //原金额
     $data["gmoney"] = $cmoney - $this->_post("money");    //变动后金额
     $data["datetime"] = date("Y-m-d H:i:s");
     $data["lx"] = 3;
     $Moneydb->add($data);
     //////////////////////////////////////////////////////////
    
    ////////添加到订单信息
    $Order = M("Order");
    
    $data["UserID"] = session("UserID");
    $data["typepay"] = 5;
    $data["TradeDate"] = date("Y-m-d H:i:s");
    $data["trademoney "] = $this->_post("money");
    $data["OrderMoney"] = $this->_post("money")*$nbzz;
    $data["sxfmoney"] = $this->_post("money")*(1-$nbzz);
    $data["Zt"] = 1;
    $data["Username"] = $UserID;
    $data["AdditionalInfo"] = $this->_post("AdditionalInfo");
    $Order->add($data);
    
    exit("ok");
    ////////////////////////////////////////////////////////////////////////////////
                            
                            
                            
                     }else{
                            exit("支付密码错误!");
                     }
                    
                    //////////////////////////////////////////////////////////
                    
                }
                /////////////////////////////////////////////////////////////////
                
            }
            
        }else{
            
            exit("转账账号不存在！");
            
        }
        
        
    }
    
      
    public function dllist(){
        
        
        $Dldate = M('Dldate'); 
        import("ORG.Util.Page");       //导入分页类 
        $count = $Dldate->where("UserID=".session("UserID"))->count();    //计算总数 
        $p = new Page($count, 20); 
        
        
        
        $list = $Dldate->where("UserID=".session("UserID"))->limit($p->firstRow . ',' . $p->listRows)->order('dldate desc')->select(); 
        //$p->firstRow 当前页开始记录的下标，$p->listRows 每页显示记录数 
        //一般定义分页样式 通过分页对象的setConfig定义其config属性； 
        /* 
          默认值为$config = array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页', 
          'theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%'); 
          修改显示的元素的话设置theme就行了，可对其元素加class 
         */ 
        $p->setConfig('header', '条记录&nbsp;&nbsp;'); 
        $p->setConfig('prev', "&nbsp;&nbsp;上一页&nbsp;&nbsp;"); 
        $p->setConfig('next', '&nbsp;&nbsp;下一页&nbsp;&nbsp;'); 
       // $p->setConfig('first', '<<'); 
      //  $p->setConfig('last', '>>'); 
        $page = $p->show();            //分页的导航条的输出变量 
        $page = str_replace("/index.php","",$page);
       // $this->assign("wherestr",$wherestr);
        $this->assign("page", $page); 
        $this->assign("pp",$this->_get("p")==""?1:$this->_get("p"));
        $this->assign("list", $list); //数据循环变量 
        $this->display(); 

    }
    
    public function tktx(){
        
        $Bank = M("Bank");
        
        $mrbank = $Bank->where("UserID=".session("UserID")." and moren = 1")->getField("BankName");
        $BankAccountNumber = $Bank->where("UserID=".session("UserID")." and moren = 1")->getField("BankAccountNumber");
        
         $mrbankid= $Bank->where("UserID=".session("UserID")." and banktype=0 and moren = 1")->getField("id");
        
        $mrbank = $mrbank."(<span style='color:#00F;'>****".substr($BankAccountNumber,-4)."</span>)";
        
        $listbank = $Bank->where("UserID=".session("UserID")." and banktype=0")->select();
        
        
        $Money = M("Money");
        
        $mymoney = $Money->where("UserID=".session("UserID"))->getField("Money");
        
        $Usersafetyinformation = M("Usersafetyinformation");
        
        $t0 = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("t0");
        
        if($t0 == 1){
            $Kttjl = D("Kttjl");
            $datedate = date("Y-m-d",time());
            $Kttjlcount = $Kttjl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count(); 
            if($Kttjlcount <= 0){
                $t0 = 0;
                $data["t0"] = 0;
                $Usersafetyinformation->where("UserID=".session("UserID"))->save($data);  
            }    
        }
        
        $Tkconfig = M("Tkconfig");
        
        //////////////////////////////////////////////////////
        $list = $Tkconfig->where("UserID=".session("UserID"))->select();
        if($list == null){
            $data["UserID"] = session("UserID");
            $Tkconfig->add($data);
        }
        /////////////////////////////////////////////////////
        
        
        //每天提款次数
        $mttkcs = $Tkconfig->getField("mttkcs");
        //单笔最小提款金额
        $minmoney = $Tkconfig->getField("minmoney");
        //单笔最大提款金额
        $maxmoney = $Tkconfig->getField("maxmoney");
        //每天最大提款金额
        $mtsxmoney = $Tkconfig->getField("mtsxmoney");
        
        ////////////////////////////////////////////////////////////
        $sz = $Tkconfig->where("UserID=".session("UserID"))->getField("sz");
        if($sz == 1){
           //每天提款次数
           $mttkcs = $Tkconfig->where("UserID=".session("UserID"))->getField("mttkcs");
           //单笔最小提款金额
           $minmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("minmoney");
           //单笔最大提款金额
           $maxmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("maxmoney");
           //每天最大提款金额
           $mtsxmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("mtsxmoney");
        }
       ///////////////////////////////////////////////////////////
        
        
        $Tklist = M("Tklist");
        //
        $dttkcs = $Tklist->where("DATEDIFF(now(),sq_date) = 0 and UserID = ".session("UserID"))->count();
        
        $yqlmoney = $Tklist->where("DATEDIFF(now(),sq_date) = 0 and UserID = ".session("UserID"))->sum("tk_money");
        
        if($dttkcs >= $mttkcs){
            $tkif = 0;  //当天不能再添加了
            $tkiflx = 0;
        }else{
            $tkif = 1;   //当天可以再添加
        }
        
        //判断是否启用提款
        /////////////////////////////////////////////////////////////////////
        $tk_if = $Tkconfig->where("UserID=0")->getField("tksz");
        if($tk_if == 1){
            $tkif = 0;
            $tkiflx = 1;
        }else{
            $sz = $Tkconfig->where("UserID=".session("UserID"))->getField("sz");
            if($sz == 1){
                $tk_if = $Tkconfig->where("UserID=".session("UserID"))->getField("tksz"); 
                if($tk_if == 1){
                    $tkif = 0;
                    $tkiflx = 1;
                }   
            }
        }
        ////////////////////////////////////////////////////////////////////
        
        $this->assign("t0",$t0);
        $this->assign("yqlmoney",$yqlmoney);
        $this->assign("minmoney",$minmoney);
        $this->assign("maxmoney",$maxmoney);
        $this->assign("mtsxmoney",$mtsxmoney);
        $this->assign("tkif",$tkif);
        $this->assign("dttkcs",$dttkcs);
        $this->assign("mttkcs",$mttkcs);
        $this->assign("mymoney",$mymoney);
        $this->assign("mrbankid",$mrbankid);
        $this->assign("listbank",$listbank);
        $this->assign("mrbank",$mrbank);
        $this->assign("tkiflx",$tkiflx);
        $this->display();
        
    }
    
    
    public function tkjsfl(){
        
        $tkmoney = $this->_post("tkmoney");
        
        if($tkmoney == NULL || $tkmoney == ""){
            exit("no|no");
        }
        
        $Tkfl = M("Tkfl"); 
        
        $fl_money = $Tkfl->where("k_money <= ".$tkmoney." and s_money >= ".$tkmoney." and T =".$this->_post("T"))->getField("fl_money");
        
        exit($fl_money."|ok");
        
    }
    
    
    public function tkyhajax(){
        
        $tkyh = $this->_post("tkyh");
        
        $Bank = M("Bank");
        
        $BankName = $Bank->where("id=".$tkyh)->getField("BankName");
        
        $BankAccountNumber = $Bank->where("id=".$tkyh)->getField("BankAccountNumber");
        
        echo "ok|".$BankName."(<span style='color:#00F;'>****".substr($BankAccountNumber,-4)."</span>)|".$tkyh;
        
    }
    
    public function tkmoney(){
        
        $tkmoney = $this->_post("money");
        $tkyhid = $this->_post("tkyhid");
        $paypassword = $this->_post("paypassword");
        $t = $this->_post("T");
        
       // exit($t);
        
        //判断金额是否正确
        
        $Money = M("Money");
        
        $symoney = $Money->where("UserID=".session("UserID"))->getField("Money");
        
        $hjmoney = $tkmoney;
        
        if($this->_post("wtwt") == 1){
            $hjmoney = $tkmoney * $this->_post("sl");
        }
        
        if($hjmoney <= $symoney){
            
            //exit("余额够了！");
            
            $Usersafetyinformation = M("Usersafetyinformation");
            $paypass = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("PayPassWord");
            if(md5($paypassword) != $paypass){
                
                exit("支付密码错误！");

            }else{
   
                 $Tkfl = M("Tkfl"); 
        
                 $fl_money = $Tkfl->where("k_money <= ".$tkmoney." and s_money >= ".$tkmoney." and T = ".$t)->getField("fl_money");
                 
                 //exit($fl_money);
                 
                 $sj_money = $tkmoney - $fl_money;  //实际金额
                 
                 $Bank = M("Bank");
                 
                 $BankName = $Bank->where("id=".$tkyhid." and UserID=".session("UserID"))->getField("BankName");
                 
                 $BankBranch = $Bank->where("id=".$tkyhid." and UserID=".session("UserID"))->getField("BankBranch");

                 $BankAccountNumber = $Bank->where("id=".$tkyhid." and UserID=".session("UserID"))->getField("BankAccountNumber"); 
                 
                 $BankCompellation = $Bank->where("id=".$tkyhid." and UserID=".session("UserID"))->getField("BankCompellation");   
                 
                 $zhihang = $Bank->where("id=".$tkyhid." and UserID=".session("UserID"))->getField("zhihang");
                 
                 $Tklist = M("Tklist");
                 
                 if($this->_post("wtwt") != 1){
                     /////////////////////////////////////////////////////////////
                     $data["UserID"] = session("UserID");
                     $data["tk_money"] = $tkmoney;
                     $data["sxf_money"] = $fl_money;
                     $data["money"] = $sj_money;
                     $data["bankname"] = $BankName;
                     $data["fen_bankname"] = $BankBranch;
                     $data["bank_number"] = $BankAccountNumber;
                     $data["myname"] = $BankCompellation;
                     $data["sq_date"] = date("Y-m-d H:i:s");
                     $data["zhi_bankname"] = $zhihang; 
                     $data["T"] = $t;
                     
                     $Tklistid = $Tklist->add($data);
                     
                     if($Tklistid){
                         
                         $data["Money"] = $symoney - $tkmoney;
                         
                         if($Money->where("UserID=".session("UserID"))->save($data)){
                   ///////////////////////////////////////////////////////////
                   $Moneydb = M("Moneybd");
                   $data["UserID"] = session("UserID");
                   $data["money"] = $tkmoney*(-1);
                   $data["ymoney"] = $symoney;    //原金额
                   $data["gmoney"] = $symoney - $tkmoney;    //变动后金额
                   $data["datetime"] = date("Y-m-d H:i:s");
                   $data["lx"] = 4;
                   $Moneydb->add($data);
                   //////////////////////////////////////////////////////////
                   $Tkconfig = M("Tkconfig");
      $femail = $Tkconfig->where("UserID=0")->getField("Email");             
      $Email = new EmailAction();
      $Email->SendEmail($femail,"提款提醒","有人申请提款，请及时处理！申请时间：".date("Y-m-d H:i:s")); 
                             exit("ok");
                         }else{
                             $Tklist->where("id=".$Tklistid)->delete();
                             exit("系统错误!");
                         }
                         
                     }else{
                         
                         exit("没有选择提款银行！");
                     }              
                     ////////////////////////////////////////////////////////////
                 }else{
                     
                     if($this->_post("sl") == 0){
                         exit("委托提款的银行数量不能为0");
                     }
                     
                     $data["UserID"] = session("UserID");
                     $data["tk_money"] = $tkmoney * $this->_post("sl");
                     $data["sxf_money"] = $fl_money * $this->_post("sl");
                     $data["money"] = $sj_money * $this->_post("sl");
                     $data["sq_date"] = date("Y-m-d H:i:s");
                     $data["T"] = $t;
                     $data["wt"] = 1;
                     
                     $Tklistid = $Tklist->add($data);
                     
                     if($Tklistid){
                         
                         $data["Money"] = $symoney - $tkmoney * $this->_post("sl");
                         
                         if($Money->where("UserID=".session("UserID"))->save($data)){
                             //exit("ok");
                             ///////////////////////////////////////////////////////
                             $Moneydb = M("Moneybd");
                               $data["UserID"] = session("UserID");
                               $data["money"] = $tkmoney * $this->_post("sl")*(-1);
                               $data["ymoney"] = $symoney;    //原金额
                               $data["gmoney"] = $symoney - $tkmoney * $this->_post("sl");      //变动后金额
                               $data["datetime"] = date("Y-m-d H:i:s");
                               $data["lx"] = 4;
                               $Moneydb->add($data);
                             ////////////////////////////////////////////////////
                             $wtbanklisttext = $this->_post("wtbanklisttext");
                             $arraylist = explode(",",$wtbanklisttext);
                             
                             $Wttklist = M("Wttklist");
                             $Bank = M("Bank");
                             
                             foreach($arraylist as $val){
                                 //echo $val;
                               $BankName = $Bank->where("id=".$val)->getField("BankName");                            
                                $BankBranch = $Bank->where("id=".$val)->getField("BankBranch");
                                $BankAccountNumber = $Bank->where("id=".$val)->getField("BankAccountNumber");     
                                $BankCompellation = $Bank->where("id=".$val)->getField("BankCompellation"); 
                                $zhihang = $Bank->where("id=".$val)->getField("zhihang");
    
                             $data["tklist_id"] = $Tklistid;
                             $data["UserID"] = session("UserID");
                             $data["bankname"] = $BankName;
                             $data["fen_bankname"] = $BankBranch;
                             $data["bank_number"] = $BankAccountNumber;
                             $data["myname"] = $BankCompellation;
                             $data["tk_money"] = $tkmoney;
                             $data["sxf_money"] = $fl_money;
                             $data["money"] = $sj_money;
                             $data["zhi_bankname"] = $zhihang;
                             $data["sq_date"] = date("Y-m-d H:i:s");
                             $data["T"] = $t;
                             
                             $Wttklist->add($data);
                              
                                    
                             }
                             
                            $Tkconfig = M("Tkconfig");
      $femail = $Tkconfig->where("UserID=0")->getField("Email");             
      $Email = new EmailAction();
      $Email->SendEmail($femail,"提款提醒","有人申请提款，请及时处理！申请时间：".date("Y-m-d H:i:s")); 
                             exit("ok");
                             //exit($arraylist[0]);
                             ///////////////////////////////////////////////////
                         }else{
                             $Tklist->where("id=".$Tklistid)->delete();
                             exit("系统错误!");
                         }
                         
                     }else{
                         
                         exit("系统错误");
                     }              
                 }
            }
            
            
        }else{
            exit("余额不足！");
        }
        
        
    }
    
    public function wttk(){
        
        
          $Bank = M("Bank");
        
       
        
        $listbank = $Bank->where("UserID=".session("UserID")." and banktype = 1")->select();
        
        
        $Money = M("Money");
        
        $mymoney = $Money->where("UserID=".session("UserID"))->getField("Money");
        
        $Usersafetyinformation = M("Usersafetyinformation");
        
        $t0 = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("t0");
        
         if($t0 == 1){
            $Kttjl = D("Kttjl");
            $datedate = date("Y-m-d",time());
            $Kttjlcount = $Kttjl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count(); 
            if($Kttjlcount <= 0){
                $t0 = 0;
                $data["t0"] = 0;
                $Usersafetyinformation->where("UserID=".session("UserID"))->save($data);  
            }    
        }
        
        
        $Tkconfig = M("Tkconfig");
         //////////////////////////////////////////////////////
        $list = $Tkconfig->where("UserID=".session("UserID"))->select();
        if($list == null){
            $data["UserID"] = session("UserID");
            $Tkconfig->add($data);
        }
        /////////////////////////////////////////////////////
        
        
        //每天提款次数
        $mttkcs = $Tkconfig->getField("mttkcs");
        //单笔最小提款金额
        $minmoney = $Tkconfig->getField("minmoney");
        //单笔最大提款金额
        $maxmoney = $Tkconfig->getField("maxmoney");
        //每天最大提款金额
        $mtsxmoney = $Tkconfig->getField("mtsxmoney");
        
        ////////////////////////////////////////////////////////////
        $sz = $Tkconfig->where("UserID=".session("UserID"))->getField("sz");
        if($sz == 1){
           //每天提款次数
           $mttkcs = $Tkconfig->where("UserID=".session("UserID"))->getField("mttkcs");
           //单笔最小提款金额
           $minmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("minmoney");
           //单笔最大提款金额
           $maxmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("maxmoney");
           //每天最大提款金额
           $mtsxmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("mtsxmoney");
        }
       ///////////////////////////////////////////////////////////
        
        
        $Tklist = M("Tklist");
        //
        $dttkcs = $Tklist->where("DATEDIFF(now(),sq_date) = 0 and UserID = ".session("UserID"))->count();
        
        $yqlmoney = $Tklist->where("DATEDIFF(now(),sq_date) = 0 and UserID = ".session("UserID"))->sum("tk_money");
        
            if($dttkcs >= $mttkcs){
            $tkif = 0;  //当天不能再添加了
            $tkiflx = 0;
        }else{
            $tkif = 1;   //当天可以再添加
        }
        
         //判断是否启用提款
        /////////////////////////////////////////////////////////////////////
        $tk_if = $Tkconfig->where("UserID=0")->getField("wttksz");
        if($tk_if == 1){
            $tkif = 0;
            $tkiflx = 1;
        }else{
            $sz = $Tkconfig->where("UserID=".session("UserID"))->getField("sz");
            if($sz == 1){
                $tk_if = $Tkconfig->where("UserID=".session("UserID"))->getField("wttksz"); 
                if($tk_if == 1){
                    $tkif = 0;
                    $tkiflx = 1;
                }else{
                    $User = M("User");
                    $gmwttk = $User->where("id=".session("UserID"))->getField("gmwttk");
                    
                    if($gmwttk == 0){
                         $tkif = 0;
                         $tkiflx = 1;
                    }
                }   
            }else{
                $User = M("User");
                $gmwttk = $User->where("id=".session("UserID"))->getField("gmwttk");
                    
                if($gmwttk == 0){
                    $tkif = 0;
                    $tkiflx = 1;
                }
            }
        }
        ////////////////////////////////////////////////////////////////////
        
        
        $this->assign("t0",$t0);
        $this->assign("yqlmoney",$yqlmoney);
        $this->assign("minmoney",$minmoney);
        $this->assign("maxmoney",$maxmoney);
        $this->assign("mtsxmoney",$mtsxmoney);
        $this->assign("tkif",$tkif);
        $this->assign("tkiflx",$tkiflx);
        $this->assign("dttkcs",$dttkcs);
        $this->assign("mttkcs",$mttkcs);
        $this->assign("mymoney",$mymoney);
       // $this->assign("mrbankid",$mrbankid);
        $this->assign("listbank",$listbank);
        //$this->assign("mrbank",$mrbank);
        $this->display();  
    }
    
    
    public function wttkselect(){
         
        $Money = M("Money");
        
        $mymoney = $Money->where("UserID=".session("UserID"))->getField("Money");
        
        $Usersafetyinformation = M("Usersafetyinformation");
        
        $t0 = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("t0");
        
        
        $Tkconfig = M("Tkconfig");
       
       //////////////////////////////////////////////////////
        $list = $Tkconfig->where("UserID=".session("UserID"))->select();
        if($list == null){
            $data["UserID"] = session("UserID");
            $Tkconfig->add($data);
        }
        /////////////////////////////////////////////////////
        
        
        //每天提款次数
        $mttkcs = $Tkconfig->getField("mttkcs");
        //单笔最小提款金额
        $minmoney = $Tkconfig->getField("minmoney");
        //单笔最大提款金额
        $maxmoney = $Tkconfig->getField("maxmoney");
        //每天最大提款金额
        $mtsxmoney = $Tkconfig->getField("mtsxmoney");
        
        ////////////////////////////////////////////////////////////
        $sz = $Tkconfig->where("UserID=".session("UserID"))->getField("sz");
        if($sz == 1){
           //每天提款次数
           $mttkcs = $Tkconfig->where("UserID=".session("UserID"))->getField("mttkcs");
           //单笔最小提款金额
           $minmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("minmoney");
           //单笔最大提款金额
           $maxmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("maxmoney");
           //每天最大提款金额
           $mtsxmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("mtsxmoney");
        }
       ///////////////////////////////////////////////////////////
        
        
        $Tklist = M("Tklist");
        //
        $dttkcs = $Tklist->where("DATEDIFF(now(),sq_date) = 0 and UserID = ".session("UserID"))->count();
        
        $yqlmoney = $Tklist->where("DATEDIFF(now(),sq_date) = 0 and UserID = ".session("UserID"))->sum("tk_money");
        
        if($dttkcs >= $mttkcs){
            $tkif = 0;  //当天不能再添加了
            $tkiflx = 0;
        }else{
            $tkif = 1;   //当天可以再添加
        }
        
         //判断是否启用提款
        /////////////////////////////////////////////////////////////////////
        $tk_if = $Tkconfig->where("UserID=0")->getField("wttksz");
        if($tk_if == 1){
            $tkif = 0;
            $tkiflx = 1;
        }else{
            $sz = $Tkconfig->where("UserID=".session("UserID"))->getField("sz");
            if($sz == 1){
                $tk_if = $Tkconfig->where("UserID=".session("UserID"))->getField("wttksz"); 
                if($tk_if == 1){
                    $tkif = 0;
                    $tkiflx = 1;
                }else{
                    $User = M("User");
                    $gmwttk = $User->where("id=".session("UserID"))->getField("gmwttk");
                    
                    if($gmwttk == 0){
                         $tkif = 0;
                         $tkiflx = 1;
                    }
                }   
            }else{
                $User = M("User");
                $gmwttk = $User->where("id=".session("UserID"))->getField("gmwttk");
               
                if($gmwttk == 0){
                    $tkif = 0;
                    $tkiflx = 1;
                } 
            }
        }
        ////////////////////////////////////////////////////////////////////

        $this->assign("t0",$t0);
        $this->assign("yqlmoney",$yqlmoney);
        $this->assign("minmoney",$minmoney);
        $this->assign("maxmoney",$maxmoney);
        $this->assign("mtsxmoney",$mtsxmoney);
        $this->assign("tkif",$tkif);
        $this->assign("dttkcs",$dttkcs);
        $this->assign("mttkcs",$mttkcs);
        $this->assign("mymoney",$mymoney);
        $this->assign("tkiflx",$tkiflx);
        $this->display();  
    }
    
    public function wttkf(){
        
        
          $Bank = M("Bank");
        
        $data["tk_money"] = 0;
        $data["sxf_money"] = 0;
        $data["sj_money"] = 0;
        $data["tk_if"] = 0;
        $data["T"] = 0;
        
        $Bank->where("UserID=".session("UserID")." and banktype = 1")->save($data);
        
         $listbank = $Bank->where("UserID=".session("UserID")." and banktype = 1")->select();
        
        
         $Money = M("Money");
        
        $mymoney = $Money->where("UserID=".session("UserID"))->getField("Money");
        
        $Usersafetyinformation = M("Usersafetyinformation");
        
        $t0 = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("t0");
        
        
         if($t0 == 1){
            $Kttjl = D("Kttjl");
            $datedate = date("Y-m-d",time());
            $Kttjlcount = $Kttjl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count(); 
            if($Kttjlcount <= 0){
                $t0 = 0;
                $data["t0"] = 0;
                $Usersafetyinformation->where("UserID=".session("UserID"))->save($data);  
            }    
        }
        
        $Tkconfig = M("Tkconfig");
        //////////////////////////////////////////////////////
        $list = $Tkconfig->where("UserID=".session("UserID"))->select();
        if($list == null){
            $data["UserID"] = session("UserID");
            $Tkconfig->add($data);
        }
        /////////////////////////////////////////////////////
        
        
        //每天提款次数
        $mttkcs = $Tkconfig->getField("mttkcs");
        //单笔最小提款金额
        $minmoney = $Tkconfig->getField("minmoney");
        //单笔最大提款金额
        $maxmoney = $Tkconfig->getField("maxmoney");
        //每天最大提款金额
        $mtsxmoney = $Tkconfig->getField("mtsxmoney");
        
        ////////////////////////////////////////////////////////////
        $sz = $Tkconfig->where("UserID=".session("UserID"))->getField("sz");
        if($sz == 1){
           //每天提款次数
           $mttkcs = $Tkconfig->where("UserID=".session("UserID"))->getField("mttkcs");
           //单笔最小提款金额
           $minmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("minmoney");
           //单笔最大提款金额
           $maxmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("maxmoney");
           //每天最大提款金额
           $mtsxmoney = $Tkconfig->where("UserID=".session("UserID"))->getField("mtsxmoney");
        }
       ///////////////////////////////////////////////////////////
        
        
        $Tklist = M("Tklist");
        //
        $dttkcs = $Tklist->where("DATEDIFF(now(),sq_date) = 0 and UserID = ".session("UserID"))->count();
        
        $yqlmoney = $Tklist->where("DATEDIFF(now(),sq_date) = 0 and UserID = ".session("UserID"))->sum("tk_money");
        
       if($dttkcs >= $mttkcs){
            $tkif = 0;  //当天不能再添加了
            $tkiflx = 0;
        }else{
            $tkif = 1;   //当天可以再添加
        }
        
         //判断是否启用提款
        /////////////////////////////////////////////////////////////////////
        $tk_if = $Tkconfig->where("UserID=0")->getField("wttksz");
        if($tk_if == 1){
            $tkif = 0;
            $tkiflx = 1;
        }else{
            $sz = $Tkconfig->where("UserID=".session("UserID"))->getField("sz");
            if($sz == 1){
                $tk_if = $Tkconfig->where("UserID=".session("UserID"))->getField("wttksz"); 
                if($tk_if == 1){
                    $tkif = 0;
                    $tkiflx = 1;
                }else{
                    $User = M("User");
                    $gmwttk = $User->where("id=".session("UserID"))->getField("gmwttk");
                    
                    if($gmwttk == 0){
                         $tkif = 0;
                         $tkiflx = 1;
                    }
                }   
            }else{
                $User = M("User");
                    $gmwttk = $User->where("id=".session("UserID"))->getField("gmwttk");
                    
                    if($gmwttk == 0){
                         $tkif = 0;
                         $tkiflx = 1;
                    }
            }
        }
        ////////////////////////////////////////////////////////////////////

        $this->assign("t0",$t0);
        $this->assign("listbank",$listbank);
        $this->assign("yqlmoney",$yqlmoney);
        $this->assign("minmoney",$minmoney);
        $this->assign("maxmoney",$maxmoney);
        $this->assign("mtsxmoney",$mtsxmoney);
        $this->assign("tkif",$tkif);
        $this->assign("tkiflx",$tkiflx);
        $this->assign("dttkcs",$dttkcs);
        $this->assign("mttkcs",$mttkcs);
        $this->assign("mymoney",$mymoney);
        $this->display();  
    }
    
    public function editbank(){
        $tk_money = $this->_post("tk_money");
        
        $sxf_money = $this->_post("sxf_money");
        
        $sj_money = $this->_post("sj_money");
        
        $id = $this->_post("id");
        
        $Bank = M("Bank");
        
        $data["tk_money"] = $tk_money;
        $data["sxf_money"] = $sxf_money;
        $data["sj_money"] = $sj_money;
        $data["tk_if"] = $this->_post("tk_if");
        $data["T"] = $this->_post("T");
        
        $Bank->where("id=".$id)->save($data);
        
        exit("ok");
    }
    
    
    public function sqtkwt(){
       $Bank = M("Bank");
        
       $listbank = $Bank->where("UserID=".session("UserID")." and tk_if = 1")->select();
        
    $sum_tk_money = $Bank->where("UserID=".session("UserID")." and tk_if = 1")->sum("tk_money");
    $sum_sxf_money = $Bank->where("UserID=".session("UserID")." and tk_if = 1")->sum("sxf_money");
    $sum_sj_money = $Bank->where("UserID=".session("UserID")." and tk_if = 1")->sum("sj_money");
    
    //exit($sum_tk_money."----".$sum_sxf_money."----".$sum_sj_money);
    
       $Tklist = M("Tklist");
       
       $data["UserID"] = session("UserID");
       $data["tk_money"] = $sum_tk_money;
       $data["sxf_money"] = $sum_sxf_money;
       $data["money"] = $sum_sj_money;
       $data["sq_date"] = date("Y-m-d H:i:s");
       $data["wt"] = 1;
       
       $TklistID = $Tklist->add($data);
        
      // if(!$TklistID){
           //exit($TklistID);
      // } 
       if($TklistID){
           $Money = M("Money");
           
           $mymoney = $Money->where("UserID=".session("UserID"))->getField("Money");
           
           if($mymoney < $sum_tk_money){
               
               $Tklist->where("id=".$TklistID)->delete();
               
               exit("余额不足");
                 
           }else{
               
               $data["Money"] = $mymoney - $sum_tk_money;
               
               $Money->where("UserID=".session("UserID"))->save($data);
               
           }
       }else{
           exit("系统错误！");
       }
        
           ///////////////////////////////////////////////////////
                             $Moneydb = M("Moneybd");
                               $data["UserID"] = session("UserID");
                               $data["money"] = $sum_tk_money*(-1);
                               $data["ymoney"] = $mymoney;    //原金额
                               $data["gmoney"] = $mymoney - $sum_tk_money;        //变动后金额
                               $data["datetime"] = date("Y-m-d H:i:s");
                               $data["lx"] = 4;
                               $Moneydb->add($data);
                             ////////////////////////////////////////////////////
        
       foreach($listbank as $row){
          
           $Wttklist = M("Wttklist");  
           $data["tklist_id"] = $TklistID;
           $data["UserID"] = session("UserID");
           $data["tk_money"] = intval($row["tk_money"]);
           $data["sxf_money"] = intval($row["sxf_money"]);
           $data["money"] = intval($row["sj_money"]);
           $data["bankname"] = $row["BankName"];
           $data["fen_bankname"] = $row["BankBranch"];
           $data["bank_number"] = $row["BankAccountNumber"];
           $data["myname"] = $row["BankCompellation"];
           $data["zhi_bankname"] = $row["zhihang"];
           $data["sq_date"] = date("Y-m-d H:i:s");
           $data["T"] = $row["T"];
           
           //////////////////////////////////////////////////////////

           /////////////////////////////////////////////////////////
           
          $Wttklist->add($data);
           
       }
       
          ///////////////////////////////////////////////////////////
                  
                   //////////////////////////////////////////////////////////
      $Tkconfig = M("Tkconfig");
      $femail = $Tkconfig->where("UserID=0")->getField("Email");             
      $Email = new EmailAction();
      $Email->SendEmail($femail,"提款提醒","有人申请提款，请及时处理！申请时间：".date("Y-m-d H:i:s")); 
      exit("ok");
        
    }
    
    public function tjyg(){
        
         $wherestr = "SjUserID = ".session("UserID");
            
        $User = M('User'); 
        import("ORG.Util.Page");       //导入分页类 
        $count = $User->where($wherestr)->count();    //计算总数 
        $p = new Page($count, 10); 
        
        
        
        $list = $User->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select(); 
      
        $p->setConfig('header', '个员工'); 
    
        $page = $p->show();            //分页的导航条的输出变量 
        $page = str_replace("/index.php","",$page);
        $this->assign("page", $page); 
        $this->assign("list", $list); //数据循环变量 
        $this->display(); 
        
    }
    
    public function tkjl(){
          
          $T = $this->_get("T");
                  
          $sq_date = $this->_get("sq_date");
          
          $zt = $this->_get("zt");
          
          $pagepage = $this->_get("pagepage");
          
          if($pagepage == "" || $pagepage == NULL){
              $pagepage = 20;
          }
                                  
          $wherestr = "wt = 0 and UserID=".session("UserID");
          
          if($T != "" && $T != NULL){
             $wherestr = $wherestr." and T = ".$T;
          }   

          if($sq_date != "" && $sq_date != NULL){
              $wherestr = $wherestr." and DATEDIFF('".$sq_date."',sq_date) = 0";
          }    
          
          if($zt != "" && $zt != NULL){
              $wherestr = $wherestr." and ZT = ".$zt;
          }                 
                                  
          $Tklist = M("Tklist");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Tklist->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Tklist->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('sq_date desc')->select(); 
        
          $page = $p->show();   
          $page = str_replace("/index.php","",$page);
          
           $datedate = date("Y-m-d");
     
          $drtkmoney = $Tklist->where("wt = 0 and UserID=".session("UserID")." and DATEDIFF('".$datedate."',sq_date) = 0")->sum("tk_money"); 
          
           $drtknum = $Tklist->where("wt = 0 and UserID=".session("UserID")." and DATEDIFF('".$datedate."',sq_date) = 0")->count(); 
     
          $drtksxfmoney =  $Tklist->where("wt = 0 and UserID=".session("UserID")." and DATEDIFF('".$datedate."',sq_date) = 0")->sum("sxf_money");        
     
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->assign("drtkmoney",$drtkmoney);
          $this->assign("drtknum",$drtknum);
          $this->assign("drtksxfmoney",$drtksxfmoney);
          $this->display();
        
    }
    
    
    public function ygdel(){
       
        if(!$_POST["id"]){
            exit("no1");
        }else{
            $id = $_POST["id"];
            $User = D("User");
            if($User->where('id='.$id)->delete()){
                exit("ok");
            }else{
                exit("no2");
            }
        }
    }
    
    public function wtjl(){
        
        
         $T = $this->_get("T");
                  
          $sq_date = $this->_get("sq_date");
          
          $zt = $this->_get("zt");
          
          $pagepage = $this->_get("pagepage");
          
          if($pagepage == "" || $pagepage == NULL){
              $pagepage = 20;
          }
                                  
          $wherestr = "UserID=".session("UserID");
          
          if($T != "" && $T != NULL){
             $wherestr = $wherestr." and T = ".$T;
          }   

          if($sq_date != "" && $sq_date != NULL){
              $wherestr = $wherestr." and DATEDIFF('".$sq_date."',sq_date) = 0";
          }    
          
          if($zt != "" && $zt != NULL){
              $wherestr = $wherestr." and ZT = ".$zt;
          }                 
                                  
          $Wttklist = M("Wttklist");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Wttklist->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Wttklist->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('sq_date desc')->select(); 
        
          $page = $p->show();         
          $page = str_replace("/index.php","",$page);   
     
       $datedate = date("Y-m-d");
     
          $drtkmoney = $Wttklist->where("UserID=".session("UserID")." and DATEDIFF('".$datedate."',sq_date) = 0")->sum("tk_money"); 
          
           $drtknum = $Wttklist->where("UserID=".session("UserID")." and DATEDIFF('".$datedate."',sq_date) = 0")->count(); 
     
          $drtksxfmoney =  $Wttklist->where("UserID=".session("UserID")." and DATEDIFF('".$datedate."',sq_date) = 0")->sum("sxf_money");        
     
          $this->assign("page", $page); 
          $this->assign("list",$list);
           $this->assign("drtkmoney",$drtkmoney);
          $this->assign("drtknum",$drtknum);
          $this->assign("drtksxfmoney",$drtksxfmoney);
          $this->display();
        
    }
    
    public function GetUsername($id){
       
        $User = M("User");
        
        $UserName = $User->where("id=".$id)->getField("UserName");
        echo $UserName;
    }
    
    
	public function ExitLogin(){    //退出登录
	    session(null);
		$this->display("Index:exit");
	}
	
    
    //////////////////////////////////////////////////////////////
    //交易记录
    public function wyjyjl(){
        
          $wherestr = "UserID = ".session("UserID")." and (typepay = 0 or typepay = 1) and Zt = 1";                 
                                  
          $Order = M("Order");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Order->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Order->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('TradeDate desc')->select(); 
        
          $page = $p->show();            
          
          $datedate = date("Y-m-d");
     
          $daymoney = $Order->where("UserID = ".session("UserID")." and (typepay = 0 or typepay = 1) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
           $daysjmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 0 or typepay = 1) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $daynum =  $Order->where("UserID = ".session("UserID")." and (typepay = 0 or typepay = 1) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
          $datedate = date("Y-m-d",time()-60*60*24);
     
          $zmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 0 or typepay = 1) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
           $zsjmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 0 or typepay = 1) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $znum =  $Order->where("UserID = ".session("UserID")." and (typepay = 0 or typepay = 1) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
     
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->assign("daymoney",$daymoney);
          $this->assign("daysjmoney",$daysjmoney);
          $this->assign("daynum",$daynum);
          $this->assign("zmoney",$zmoney);
          $this->assign("zsjmoney",$zsjmoney);
          $this->assign("znum",$znum);
          $this->display();
        
        
    }
    
     public function wyskjl(){
        
          $wherestr = "UserID = ".session("UserID")." and (typepay = 3) and Zt = 1";                 
                                  
          $Order = M("Order");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Order->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Order->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('TradeDate desc')->select(); 
        
          $page = $p->show();         
          $page = str_replace("/index.php","",$page);   
          
          $datedate = date("Y-m-d");
     
          $daymoney = $Order->where("UserID = ".session("UserID")." and (typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
           $daysjmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $daynum =  $Order->where("UserID = ".session("UserID")." and (typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
          $datedate = date("Y-m-d",time()-60*60*24);
     
          $zmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
           $zsjmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $znum =  $Order->where("UserID = ".session("UserID")." and (typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
     
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->assign("daymoney",$daymoney);
          $this->assign("daysjmoney",$daysjmoney);
          $this->assign("daynum",$daynum);
          $this->assign("zmoney",$zmoney);
          $this->assign("zsjmoney",$zsjmoney);
          $this->assign("znum",$znum);
          $this->display();
        
        
    }
    
    
    public function gamejl(){
        
          $wherestr = "UserID = ".session("UserID")." and (typepay = 2) and Zt = 1";                 
                                  
          $Order = M("Order");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Order->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Order->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('TradeDate desc')->select(); 
        
          $page = $p->show();      
          $page = str_replace("/index.php","",$page);      
     
          $datedate = date("Y-m-d");
     
          $daymoney = $Order->where("UserID = ".session("UserID")." and (typepay = 2) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
          $daysjmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 2) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $daynum =  $Order->where("UserID = ".session("UserID")." and (typepay = 2) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
          $datedate = date("Y-m-d",time()-60*60*24);
     
          $zmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 2) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
          $zsjmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 2) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $znum =  $Order->where("UserID = ".session("UserID")." and (typepay = 2) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
     
     
          $this->assign("page", $page); 
          $this->assign("list",$list);
           $this->assign("daymoney",$daymoney);
          $this->assign("daysjmoney",$daysjmoney);
          $this->assign("daynum",$daynum);
          $this->assign("zmoney",$zmoney);
          $this->assign("zsjmoney",$zsjmoney);
          $this->assign("znum",$znum);
          $this->display();
        
        
    }         
    
    
     public function dkskjl(){
        
          $wherestr = "UserID = ".session("UserID")." and (typepay = 4) and Zt = 1";                 
                                  
          $Order = M("Order");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Order->where($wherestr)->count();    //计算总数 
          $p = new Page($count, $pagepage); 
          
          $list = $Order->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('TradeDate desc')->select(); 
        
          $page = $p->show();          
          $page = str_replace("/index.php","",$page);  
     
          $datedate = date("Y-m-d");
     
          $daymoney = $Order->where("UserID = ".session("UserID")." and (typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
          $daysjmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $daynum =  $Order->where("UserID = ".session("UserID")." and (typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
          $datedate = date("Y-m-d",time()-60*60*24);
     
          $zmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
          $zsjmoney = $Order->where("UserID = ".session("UserID")." and (typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $znum =  $Order->where("UserID = ".session("UserID")." and (typepay = 4) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
     
     
          $this->assign("page", $page); 
          $this->assign("list",$list);
           $this->assign("daymoney",$daymoney);
          $this->assign("daysjmoney",$daysjmoney);
          $this->assign("daynum",$daynum);
          $this->assign("zmoney",$zmoney);
          $this->assign("zsjmoney",$zsjmoney);
          $this->assign("znum",$znum);
          $this->display();
        
        
    }
    
    public function xgtz(){
        
        $TransID = $this->_post("TransID");
       // $Ordertz = M("Ordertz");
        //$data["Sjt_num"] = 0;
        //$data["success"] = 1;
       /// $list = $Ordertz->where("Sjt_TransID = '".$TransID."'")->save($data);
       // if($list){
         //   exit("ok");
       // }else{
        //    exit("no");
       // }
        
        ///////////////////////////////////////////////////////////////////
        $Ordertz = M("Ordertz");
        $list = $Ordertz->where("Sjt_TransID = '".$TransID."'")->select();
        $urlname = "";  //提交址地
        $datastr = "";  //提交数据
        $TransID = "";  //订单编号
         foreach($list as $row){
                $urlname = $row["Sjt_urlname"];
                $datastr = "Sjt_MerchantID=".$row["Sjt_MerchantID"]."&Sjt_Username=".$row["Sjt_UserName"]."&Sjt_TransID=".$row["Sjt_TransID"]."&Sjt_Return=".$row["Sjt_Return"]."&Sjt_Error=".$row["Sjt_Error"]."&Sjt_factMoney=".$row["Sjt_factMoney"]."&Sjt_SuccTime=".$row["Sjt_SuccTime"]."&Sjt_Sign=".$row["Sjt_Sign"]."&Sjt_BType=2";
               // $TransID = $row["Sjt_TransID"];
                
           }
         
         $tjurl = $urlname."?".$datastr; 
         
$contents = fopen($tjurl,"r"); 
$contents=fread($contents,4096); 
        
         if($contents == "ok"){
             $data["success"] = 1;
             $Ordertz->where("Sjt_TransID = '".$TransID."'")->save($data);
             echo "ok";
         }else{
             echo "no";
         }
 
        //////////////////////////////////////////////////////////////////
        
        
        
    }
    
    
     public function tzshow($TransID){
         header("Content-Type:text/html; charset=utf-8"); 
          //$TransID = $this->_get("TransID");
          $Ordertz = M("Ordertz");
          $success = $Ordertz->where("Sjt_TransID = '".$TransID."'")->getField("success");
          if($success == 1){
              echo "<span style='color:#f00'>已成功通知</span>";
          }else{
              if($success != null && $success != ""){
                 
          $Sjt_num = $Ordertz->where("Sjt_TransID = '".$TransID."'")->getField("Sjt_num");
          
         // if($Sjt_num < 5){
             /// echo "正在通知";
          //}else{
              echo "<a href=\"javascript:xgtz('".$TransID."')\">通知</a>";   
         // }
                 
                   
              }else{
                  //echo "[1".$success.$TransID."1]";
                  echo "无";
              }
          }
      }
      
      public function jltj(){
          
          $ksjy_date = $this->_post("ksjy_date");
          $jsjy_date = $this->_post("jsjy_date");
          $jylx = $this->_post("jylx");
          
          if($ksjy_date == NULL && $jsjy_date == NULL && $jylx == NULL){
               //$this->assign("ksjy_date","sdgsadgg");
              $this->display();
          }else{
              ////////////////////////////////////////////////////////////
            
          if($jylx == ""){
              $wherestr = "";
          }else{
              $wherestr = " and typepay = ".$jylx;
          }
          //exit("UserID = ".session("UserID").$wherestr." and Zt = 1 and DATEDIFF('".$ksjy_date."',TradeDate) <= 0 and DATEDIFF('".$jsjy_date."',TradeDate) >= 0");
          $wherestr = "UserID = ".session("UserID").$wherestr." and Zt = 1 and DATEDIFF('".$ksjy_date."',TradeDate) <= 0 and DATEDIFF('".$jsjy_date."',TradeDate) >= 0";
          
          //$wherstr = "UserID = ".session("UserID");
          
          $Order = M("Order");
          
          $daymoney = $Order->where($wherestr)->sum("trademoney"); 
          
          $daysjmoney = $Order->where($wherestr)->sum("OrderMoney"); 
     
          $daynum =  $Order->where($wherestr)->count(); 
          
          $this->assign("ksjy_date",$ksjy_date);
          $this->assign("jsjy_date",$jsjy_date);
          $this->assign("jylx",$jylx);
           $this->assign("daymoney",$daymoney);
          $this->assign("daysjmoney",$daysjmoney);
          $this->assign("daynum",$daynum);
          $this->display();
              ///////////////////////////////////////////////////////////
          }
          
         // $this->display();
      }
      
      public function ptzz(){
          //$wherestr = "(UserID = ".session("UserID")." or (Username = '".session("UserID")."' and typepay = 5)) and Zt = 1";                 
          
          $wherestr = "((UserID = ".session("UserID")." or Username = '".session("UserID")."') and typepay = 5) and Zt = 1";                        
                                  
          $Order = M("Order");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Order->where($wherestr)->count();    //计算总数 
          $p = new Page($count, 10); 
          
          $list = $Order->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('TradeDate desc')->select(); 
        
          $page = $p->show();  
          $page = str_replace("/index.php","",$page);
          $this->assign("page", $page); 
          $this->assign("list",$list);   
           $this->assign("UserID",session("UserID"));       
          $this->display();
      }
      
      public function wtplxf(){  //委托批量下发
            $Userapiinformation = D("Userapiinformation"); 
            $zt = $Userapiinformation->where("UserID=".session("UserID"))->getField("Zt");   
            
            
            
            $Tkconfig = M("Tkconfig");
              
            $tjlnf = $Tkconfig->where("UserID=0")->getField("wtplxfnf");
            
             $Money = D("Money");
             $Money_Money =  $Money->where("UserID=".session("UserID"))->getField("Money");
            
            $t0 = 0;  //默认T+0是关闭的
            
            $Wtplxf = D("Wtplxf");
            $datedate = date("Y-m-d",time());
            $Kttjlcount = $Wtplxf->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count();  
            if($Kttjlcount >0){
                 $t0 = 1;
                 $list =  $Wtplxf->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->select();
                 $listls =  $Wtplxf->where("DATEDIFF('".$datedate."',js_date) > 0 and UserID=".session("UserID"))->select(); 
            } 
            
            $this->assign("zt",$zt); 
            $this->assign("tjlnf",$tjlnf);   
            $this->assign("Money",$Money_Money);
            $this->assign("t0",$t0);
            $this->assign("list",$list);
            $this->assign("listls",$listls);
            $this->display();
      }
      
      
      public function wtplxfok(){
          
        $Tkconfig = M("Tkconfig");
          
        $tjlnf = $Tkconfig->where("UserID=0")->getField("wtplxfnf");  //T+0年费
        
        $Money = D("Money");
        
        $Money_Money =  $Money->where("UserID=".session("UserID"))->getField("Money");  //商户账户余额
        
        if($tjlnf > $Money_Money){
            exit("1"); //余额不足
        }
        
        $Usersafetyinformation = M("Usersafetyinformation");
        
        $PayPassWord = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("PayPassWord");
        
        if($PayPassWord != md5($this->_post("paypassword"))){
            exit("2");  //支付密码错误
        }
        
        

             $Wtplxf = D("Wtplxf");
             $Kttjlcount = $Wtplxf->where("UserID=".session("UserID"))->count();
             if($Kttjlcount <=0){
                $data["UserID"] = session("UserID");
                $data["Money"] = $tjlnf;
                $data["sq_date"] = date('Y-m-d H:i:s',time());
                $data["ks_date"] = date('Y-m-d',time());
                $data["js_date"] = date('Y-m-d',strtotime("+1 years"));
                $Wtplxf->add($data);
                
                 $User = M("User");
                 $data["gmwttk"] = 1;
                 $list = $User->where("id=".session("UserID"))->save($data);
                
                $data["Money"] = $Money_Money - $tjlnf; 
                $list = $Money->where("UserID=".session("UserID"))->save($data); //更新余额  
                
             }else{
                 $datedate = date("Y-m-d",time());
                 $Kttjlcount = $Wtplxf->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count();
                 if($Kttjlcount <=0){
                     $data["UserID"] = session("UserID");
                     $data["Money"] = $tjlnf;
                     $data["sq_date"] = date('Y-m-d H:i:s',time());
                     $data["ks_date"] = date('Y-m-d',time());
                     $data["js_date"] = date('Y-m-d',strtotime("+1 years"));
                     $Wtplxf->add($data);
                     
                      $User = M("User");
                     $data["gmwttk"] = 1;
                     $list = $User->where("id=".session("UserID"))->save($data);
                     
                     $data["Money"] = $Money_Money - $tjlnf; 
                     $list = $Money->where("UserID=".session("UserID"))->save($data);   //更新余额  
                     
                 }else{
                      exit("3"); 
                 }
                 
             }

        exit("4");
      }
    
    public function sqzdjs(){
         $Userapiinformation = D("Userapiinformation"); 
        $zt = $Userapiinformation->where("UserID=".session("UserID"))->getField("Zt");   
        
        
        
        $Tkconfig = M("Tkconfig");
          
        $tjlnf = $Tkconfig->where("UserID=0")->getField("zdtknf");
        
         $Money = D("Money");
         $Money_Money =  $Money->where("UserID=".session("UserID"))->getField("Money");
        
        $t0 = 0;  //默认T+0是关闭的
        
        $Zdjsjl = D("Zdjsjl");
        $datedate = date("Y-m-d",time());
        $Kttjlcount = $Zdjsjl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count();  
        if($Kttjlcount >0){
             $t0 = 1;
             $list =  $Zdjsjl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->select();
             $listls =  $Zdjsjl->where("DATEDIFF('".$datedate."',js_date) > 0 and UserID=".session("UserID"))->select(); 
        } 
        
        $this->assign("zt",$zt); 
        $this->assign("tjlnf",$tjlnf);   
        $this->assign("Money",$Money_Money);
        $this->assign("t0",$t0);
        $this->assign("list",$list);
        $this->assign("listls",$listls);
        $this->display();
    }
    
    
    public function zdjsok(){
         $Tkconfig = M("Tkconfig");
          
        $tjlnf = $Tkconfig->where("UserID=0")->getField("zdtknf");  //T+0年费
        
        $Money = D("Money");
        
        $Money_Money =  $Money->where("UserID=".session("UserID"))->getField("Money");  //商户账户余额
        
        if($tjlnf > $Money_Money){
            exit("1"); //余额不足
        }
        
        $Usersafetyinformation = M("Usersafetyinformation");
        
        $PayPassWord = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("PayPassWord");
        
        if($PayPassWord != md5($this->_post("paypassword"))){
            exit("2");  //支付密码错误
        }
        
        

             $Zdjsjl = D("Zdjsjl");
             $Kttjlcount = $Zdjsjl->where("UserID=".session("UserID"))->count();
             if($Kttjlcount <=0){
                $data["UserID"] = session("UserID");
                $data["Money"] = $tjlnf;
                $data["sq_date"] = date('Y-m-d H:i:s',time());
                $data["ks_date"] = date('Y-m-d',time());
                $data["js_date"] = date('Y-m-d',strtotime("+1 years"));
                $data["txmoney"] = $this->_post("txmoney");
                $Zdjsjl->add($data);
                
                 //$User = M("User");
                 //$data["gmwttk"] = 1;
                //$list = $User->where("id=".session("UserID"))->save($data);
                
                $data["Money"] = $Money_Money - $tjlnf; 
                $list = $Money->where("UserID=".session("UserID"))->save($data); //更新余额  
                
             }else{
                 $datedate = date("Y-m-d",time());
                 $Kttjlcount = $Zdjsjl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->count();
                 if($Kttjlcount <=0){
                     $data["UserID"] = session("UserID");
                     $data["Money"] = $tjlnf;
                     $data["sq_date"] = date('Y-m-d H:i:s',time());
                     $data["ks_date"] = date('Y-m-d',time());
                     $data["js_date"] = date('Y-m-d',strtotime("+1 years"));
                      $data["txmoney"] = $this->_post("txmoney");
                     $Zdjsjl->add($data);
                     
                      //$User = M("User");
                    // $data["gmwttk"] = 1;
                    // $list = $User->where("id=".session("UserID"))->save($data);
                     
                     $data["Money"] = $Money_Money - $tjlnf; 
                     $list = $Money->where("UserID=".session("UserID"))->save($data);   //更新余额  
                     
                 }else{
                      exit("3"); 
                 }
                 
             }

        exit("4");
    }
    
    public function zdjsxgok(){
         $Zdjsjl = D("Zdjsjl");
         $datedate = date("Y-m-d",time());
         $data["txmoney"] = $this->_post("txmoney");
         $Kttjlcount = $Zdjsjl->where("DATEDIFF('".$datedate."',js_date) <= 0 and UserID=".session("UserID"))->save($data);
         exit("4");
    }
    
    public function skym(){
        
        $User = M("User");
        $mypayurlname = $User->where("id=".session("UserID"))->getField("mypayurlname");
        $if = 0;
        $weburl = "";
        if($mypayurlname){
               $if = 1;
               $weburl = "http://".C("WEB_URL")."/".$mypayurlname;
        }
        
        $this->assign("if",$if);
        $this->assign("weburl",$weburl);
        $this->assign("web_url",C("WEB_URL"));
        $this->display();
    }
    
    public function addskym(){
         $mypayurlname = $this->_post("mypayurlname");
         if($mypayurlname == ""){
             exit("2");       //不能为空
         }
         
         $User = M("User");
         $UserID = $User->where("mypayurlname='".$mypayurlname."'")->getField("id");
         if($UserID){
             exit("1");
         }
         
         $data["mypayurlname"] = $mypayurlname;
         
         $list = $User->where("id=".session("UserID"))->save($data);
         
         if($list){
             exit("3");
         }else{
             exit("4");
         }
    }
    
    
public function mypay(){
      header("Content-Type:text/html; charset=utf-8"); 
      $Userapiinformation = M("Userapiinformation");
      $keykey = $Userapiinformation->where("UserID=".session("UserID"))->getField("key");   
          
      $p0_Cmd = "Buy";
      
      $p1_MerId = intval(session("UserID"))+10000;
      
      $p2_Order = "";
      
      $p3_Amt = $this->_request("money");
      
      $p4_Cur = "CNY";
      
      $p5_Pid = "lzf";
      
      $p6_Pcat = "lzf";
      
      $p7_Pdesc = "lzf";
      
      $p8_Url = "http://".C("WEB_URL")."/";  //跳转地址
      
      $p9_SAF = "0";
      
      $pa_MP = "0";
      
      $pd_FrpId = $this->_request("pd_FrpId");
      
      $pr_NeedResponse = "1";
      
      $Sjt_Paytype = $this->_request("Sjt_Paytype");
      
      $Sjt_ProudctID = $this->_request("Sjt_ProudctID");      //卡面额
      
      $Sjt_CardNumber = $this->_request("Sjt_CardNumber");    //卡号
      
      $Sjt_CardPassword = $this->_request("Sjt_CardPassword");  //卡密
      
      $Sjt_UserName = "sjt";
      
      $key = $keykey;   //密钥
      
      $hmacstr = $p0_Cmd.$p1_MerId.$p2_Order.$p3_Amt.$p4_Cur.$p5_Pid.$p6_Pcat.$p7_Pdesc.$p8_Url.$p9_SAF.$pa_MP.$pd_FrpId.$pr_NeedResponse.$key;
      
      $hmac = MD5($hmacstr);
      
       if($Sjt_Paytype == "g"){   //如果是点卡
               $urlStr = "http://".C("WEB_URL")."/Payapi_Index_Pay.html?p0_Cmd=".$p0_Cmd."&p1_MerId=".$p1_MerId."&p2_Order=".$p2_Order."&p3_Amt=".$p3_Amt."&p4_Cur=".$p4_Cur."&p5_Pid=".$p5_Pid."&p6_Pcat=".$p6_Pcat."&p7_Pdesc=".$p7_Pdesc."&p8_Url=".$p8_Url."&p9_SAF=".$p9_SAF."&pa_MP=".$pa_MP."&pd_FrpId=".$pd_FrpId."&Sjt_CardNumber=".$Sjt_CardNumber."&Sjt_CardPassword=".$Sjt_CardPassword."&Sjt_ProudctID=".$Sjt_ProudctID."&pr_NeedResponse=".$pr_NeedResponse."&Sjt_Paytype=".$Sjt_Paytype."&Sjt_UserName=".$Sjt_UserName."&hmac=".$hmac;
               $contents = fopen($urlStr,"r"); 
               $contents=fread($contents,4096); 
              exit($contents);
              //$a = split("&",$contents);
               // exit($a[0]);
              // if($a[0] == "ok"){
                //   echo "检验成功！正在获取充值状态......<br>";  
                 
              // }else{
                  //  exit($a[0]);
              //}
           }
        
    }
    
   public function hqdkzt(){
          sleep(20);
          $Userapiinformation = M("Userapiinformation");
          $keykey = $Userapiinformation->where("UserID=".session("UserID"))->getField("key");  
          $Sjt_TransID = $this->_request("Sjt_TransID"); 
           $Sign = MD5($Sjt_TransID.$key);
                 $urlStr = "http://".C("WEB_URL")."/Payapi_Pay_SelectOK.html?Sjt_TransID=".$Sjt_TransID."&Sign=".$Sign;
                 $contents = fopen($urlStr,"r"); 
                 $contents=fread($contents,4096);
                 if($contents == "ok"){
                     exit("ok");
                 }else{
                    exit("no");   
                 }
   } 
    
    
   public function xgjk(){
       $Sjapi = M("Sjapi");
       $Sjapilist = $Sjapi->where("xz=1")->select();
       
       $System = M("System");
       $mrapiid = $System->where("UserID=0")->getField("DefaultBank");
       $mrapiname = $Sjapi->where("id=".$mrapiid)->getField("myname");
       
       $Usersafetyinformation = M("Usersafetyinformation");   
       $myapiid = $Usersafetyinformation->where("UserID=".session("UserID"))->getField("PayBank");
       if(intval($myapiid) == 0){
           $myapiname = $mrapiname;
       }else{
           $myapiname = $Sjapi->where("id=".$myapiid)->getField("myname");
       }
       
       $this->assign("myapiname",$myapiname);
       $this->assign("mrapiname",$mrapiname);
       $this->assign("Sjapilist",$Sjapilist);
       $this->display();
   } 
   
   public function xgjkedit(){
       $xgjk = $this->_post("xgjk");
       $Usersafetyinformation = M("Usersafetyinformation");   
       $data["PayBank"] = $xgjk;
       $list = $Usersafetyinformation->where("UserID=".session("UserID"))->save($data);
       if($list){
            $this->assign("msgTitle","");
            $this->assign("message","支付接口修改成功！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","User_Index_xgjk.html");
            $this->display("Index:success");
        }else{
            $this->assign("msgTitle","");
            $this->assign("message","操作失败！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl","User_Index_xgjk.html");
            $this->display("Index:success"); 
        }
   }
   
   
      public function EditFl(){
       $id = $this->_get("id");
       $User = M("User");
       $xjUserName = $User->where("id=".$id)->getField("UserName");
       
       $Paycost = M("Paycost");
       $fl = $Paycost->where("UserID=".$id)->getField("wy");
       $sjfl = $Paycost->where("UserID=".session("UserID"))->getField("wy");
       
       if($sjfl == 0){
           $sjfl = $Paycost->where("UserID=0")->getField("wy");
       }
       
        if($fl == 0){
           $fl = $Paycost->where("UserID=0")->getField("wy");
       }
     
           $fl = 1 - $fl;
       $sjfl = 1 - $sjfl;
       
       $this->assign("fl",$fl);
       $this->assign("sjfl",$sjfl);
       $this->assign("xjUserName",$xjUserName);
       $this->display();
   }
   
   
   public function EditFlEdit(){
       $id = $this->_post("id");
       $fl = $this->_post("fl");
       $Paycost = M("Paycost");
       
       $sjfl = $Paycost->where("UserID=".session("UserID"))->getField("wy");
       
       if($sjfl == 0){
           $sjfl = $Paycost->where("UserID=0")->getField("wy");
       }
       
       if($sjfl == 1 || $sjfl == 0){
            $this->assign("msgTitle","");
            $this->assign("message","您设置的下级费率不能比您的费率低！");
            $this->assign("waitSecond",3);
            $this->assign("jumpUrl",U("Index/EditFl","id=".$id));
            $this->display("Index:success"); 
       }else{
           $sjfl = 1 - $sjfl;
           if($fl < $sjfl){
               $this->assign("msgTitle","");
               $this->assign("message","您设置的下级费率不能比您的费率低！");
               $this->assign("waitSecond",3);
               $this->assign("jumpUrl",U("Index/EditFl","id=".$id));
               $this->display("Index:success"); 
           }else{
               
                   $fl = 1-$fl;
              
               
               $data["wy"] = $fl;
               $Edittrue = $Paycost->where("UserID=".$id)->save($data);
               if($Edittrue){
                   $this->assign("msgTitle","");
                   $this->assign("message","修改成功！");
                   $this->assign("waitSecond",3);
                   $this->assign("jumpUrl",U("Index/EditFl","id=".$id));
                   $this->display("Index:success"); 
               }else{
                   $this->assign("msgTitle","");
                   $this->assign("message","修改失败！");
                   $this->assign("waitSecond",3);
                   $this->assign("jumpUrl",U("Index/EditFl","id=".$id));
                   $this->display("Index:success"); 
               }
           }
       }
              
   }
   
       public function cxjlxj(){
        
          $User = M("User");
          
          $UserName = $User->where("id=".$this->_get("id"))->getField("UserName");
          
          $sq_date = $this->_get("sq_date");
          $sq_date_js = $this->_get("sq_date_js");
        
         $wherestr = "UserID = ".$this->_get("id")." and (typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1";        
         
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
          
          $datedate = date("Y-m-d");
     
          $daymoney = $Order->where("UserID = ".$this->_get("id")." and (typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
           $daysjmoney = $Order->where("UserID = ".$this->_get("id")." and (typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $daynum =  $Order->where("UserID = ".$this->_get("id")." and (typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
          $datedate = date("Y-m-d",time()-60*60*24);
     
          $zmoney = $Order->where("UserID = ".$this->_get("id")." and (typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("trademoney"); 
          
           $zsjmoney = $Order->where("UserID = ".$this->_get("id")." and (typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->sum("OrderMoney"); 
     
          $znum =  $Order->where("UserID = ".$this->_get("id")." and (typepay = 0 or typepay = 1 or typepay = 3) and Zt = 1 and DATEDIFF('".$datedate."',TradeDate) = 0")->count(); 
          
          
       $Paycost = M("Paycost");
       
        $sjfl = $Paycost->where("UserID=".session("UserID"))->getField("wy");
        
        $fl = $Paycost->where("UserID=".$this->_get("id"))->getField("wy");
        
        $tcfl = (1-$fl)-(1-$sjfl);
        
        
        $daytjmoney = $daymoney * $tcfl;
        
        $daytjmoney = round($daytjmoney,3);
        
        $ztjmoney = $zmoney * $tcfl;
        
        
        $ztjmoney = round($ztjmoney,3);
        
        $sousuomoney = $Order->where($wherestr)->sum("TcMoney"); 
        $sousuojymoney = $Order->where($wherestr)->sum("trademoney"); 
        
        $sousuotc = $sousuomoney; 
        
        $sousuotc = round($sousuotc,3);
        
        $sousuojymoney = round($sousuojymoney,3);
        
        
         $this->assign("sousuotc", $sousuotc);   
         $this->assign("sousuojymoney", $sousuojymoney);   
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->assign("UserName",$UserName);
          $daymoney = round($daymoney,3);
          $this->assign("daymoney",$daymoney);
          $daysjmoney = round($daysjmoney,3);
          $this->assign("daysjmoney",$daysjmoney);
          $this->assign("daynum",$daynum);
          $zmoney = round($zmoney,3);
          $this->assign("zmoney",$zmoney);
          $zsjmoney = round($zsjmoney,3);
          $this->assign("zsjmoney",$zsjmoney);
          $this->assign("znum",$znum);
          
          $daytjmoney = round($daytjmoney,3);
          $this->assign("daytjmoney",$daytjmoney);
          $ztjmoney = round($ztjmoney,3);
          $this->assign("ztjmoney",$ztjmoney);
          $this->display();
    }
    
     public function zjbdjl(){   //资金变动记录
    
          $Moneybd = M("Moneybd");
          
          $wherestr = "1=1";
          
        $sq_date = $this->_request("sq_date");
        $sq_date_js = $this->_request("sq_date_js");
        $shbh = session("UserID");
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
            $wherestr = $wherestr." and UserID = '".($shbh)."'";
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
    
}

?>