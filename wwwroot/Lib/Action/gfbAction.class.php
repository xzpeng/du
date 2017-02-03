<?php
  class gfbAction extends Action{
      public function getGopayServerTime(){
          require_once('HttpClient.class.php');  
          return HttpClient::getGopayServerTime();
      }
  }
?>
