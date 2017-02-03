<?php
  class NewsAction extends Action{
      
      public function __construct(){
         parent::__construct();
         if(!session("?SjtUserName") || !session("?SjtUserType")){
            
           $this->display("Index:login");
            exit;
         }
            
    }
      
      public function add(){
          
          $this->display();
          
      }
      
      
      public function addnews(){
          
          $type = $this->_get("type");
          
          $title = $this->_post("title");
          
          $content = $this->_post("content");
          
          $zt = $this->_post("zt");
          
          if($zt == "" || $zt == NULL){
              $zt = 0;
          }
          
          $Newlist = M("Newlist");
          
          $data["title"] = $title;
          $data["content"] = $content;
          $data["type"] = $type;
          $data["zt"] = $zt;
          $data["datetime"] = date("Y-m-d H:i:s");
          
          $Newlist->add($data);
          
           $this->assign("msgTitle","");
           $this->assign("message","添加成功");
           $this->assign("waitSecond",3);
           $this->assign("jumpUrl","/SjtAdminSjt_News_add_type_".$type.".html");
           $this->display("success");          
      }
      
      public function newslist(){
          
          $type = $this->_get("type");
          
          $title = $this->_get("title");
          
          $zt = $this->_get("zt");
          
          $wherestr = "type = ".$type;
          
          if($title != "" && $title != NULL){
              $wherestr = $wherestr." and title like '%".$title."%' ";
          }
          
          if($zt != "" && $zt != NULL){
              $wherestr = $wherestr." and zt = ".$zt;
          }
          
          $Newlist = M("Newlist");
          import("ORG.Util.Page");       //导入分页类 
          $count = $Newlist->where($wherestr)->count();    //计算总数 
          $p = new Page($count, 10); 
          
          $list = $Newlist->where($wherestr)->limit($p->firstRow . ',' . $p->listRows)->order('datetime desc')->select(); 
        
          $page = $p->show();   
          
          $page = str_replace("/index.php","",$page);         
     
          $this->assign("page", $page); 
          $this->assign("list",$list);
          $this->display();
          
      }
      
      
      public function delnews(){
          
          $idstr = $this->_post("idstr");
          
          $Newlist = M("Newlist");
          
          $Newlist->where("id in (".$idstr.")")->delete();
          
          exit("ok");
          
      }
      
      
      public function pingbinews(){
          
          $idstr = $this->_post("idstr");
          
          $Newlist = M("Newlist");
          
          $data["zt"] = 1;
          
          $Newlist->where("id in (".$idstr.")")->save($data);
          
          exit("ok");
          
      }
      
      public function huifunews(){
        
         $idstr = $this->_post("idstr");
          
          $Newlist = M("Newlist");
          
          $data["zt"] = 0;
          
          $Newlist->where("id in (".$idstr.")")->save($data);
          
          exit("ok");
          
      }
      
      public function editnew(){
          
          $id = $this->_get("id");
          
          $type = $this->_get("type");
          
          $Newlist = M("Newlist");
          
          $title = $Newlist->where("id=".$id)->getField("title");
          
          $content = $Newlist->where("id=".$id)->getField("content");
          
          $zt = $Newlist->where("id=".$id)->getField("zt");
          
          $this->assign("title",$title);
          $this->assign("content",$content);
          $this->assign("id",$id);
          $this->assign("zt",$zt);
          $this->assign("type",$type);
          $this->display();
          
      }
      
      public function editeditnews(){
          
          $type = $this->_get("type");
          
          $id = $this->_post("id");
          
          $title = $this->_post("title");
          
          $content = $this->_post("content");
          
          $zt = $this->_post("zt");
          
          if($zt == "" || $zt == NULL){
              $zt = 0;
          }
          
          $Newlist = M("Newlist");
          
          $data["title"] = $title;
          $data["content"] = $content;
          $data["datetime"] = date("Y-m-d H:i:s");
          $data["zt"] = $zt;
          
          $Newlist->where("id=".$id)->save($data);
          
          $this->assign("msgTitle","");
           $this->assign("message","修改成功");
           $this->assign("waitSecond",3);
           $this->assign("jumpUrl","/SjtAdminSjt_News_newslist_type_".$type.".html");
           $this->display("success");          
          
      }
      
  }
?>
