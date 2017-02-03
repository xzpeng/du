<?php
class IndexAction extends SjtadminsjtAction {
    
     public function __construct(){
         parent::__construct();
         if(!session("?SjtUserName") || !session("?SjtUserType")){
          // $this->display("Index:login");
          //  exit;
            $admin_name = C("ADMIN_NAME");    
             if($admin_name <> "" and MODULE_NAME <> $admin_name){
                 $this->display("Home:Index:sls");
                exit;
             }else{
                  $this->display("SjtAdminSjt:Index:loginlogin");
                 exit;
             }     
         }
            
    }
    
    public function index(){
        $sq_date = date("Y-m-d");
        $this->assign("sq_date",$sq_date);
        $this->display();
    }
    
    public function login(){
        if(!session("?SjtUserName") || !session("?SjtUserType")){
            //exit(session("SjtUserName"));
            $this->display();
        }else{
            //exit(session("SjtUserName"));
            $this->display("Index:index");  
        }
        
    }
    
   
}
?>
