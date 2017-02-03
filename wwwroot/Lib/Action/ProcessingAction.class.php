<?php
/**
 * 数据处理类
 * ============================================================================
 * API说明
 * init  初始化数据
 * setMerId  设置商户id
 * setCharset 设置编码
 * setGateUrl 设置服务器url
 * setCode  设置交易码
 * getCode  获取交易码
 * setParameter  设置参数
 * getParameter 获取参数
 * createXml  生成xml
 * getXml  获取xml
 * getServerData 和服务器通信
 * loadXml  解析xml
 * getDebugInfo  获取debug信息
 * ============================================================================
 */
class ProcessingAction extends Action{
    
    //支付交易代码
    var $code;
    
    //编码
    var $charset;
    
     //参数
    var $parameters;
    
    //debug信息
    var $debugInfo;
    
    //组织的xml字符串
    var $xml;
    
    //网关url
    var $gateUrl;
    
    //商户号
    var $merId;
    
    function __construct() {
        $this->parameters = array();
        $this->debugInfo = "";
        $this->xml = "";
        $this->init();
    }
    //初始化数据
    function init(){
        $this->setCode("ORD001");                            //支付交易代码,必输
        $this->setCharset("UTF-8");                          //设置编码
        
        $returnUrl = "https://xxxxxxxx/retUrl.php";
        $notifyUrl = "https://xxxxxxxx/notUrl.php";
        
        $this->setParameter("merOrderId", date("YmdHis")); //商品订单号,必输
        $this->setParameter("returnUrl", $returnUrl);      //同步返回URL,必输
        $this->setParameter("notifyUrl", $notifyUrl);      //异步通知URL,必输
        $this->setParameter("chkMethod", 1);               //签名方式,必输
        $this->setParameter("merBusType", "01");           //商户业务类型,必输
        $this->setParameter("payType", 0);                 //付款类型,必输
        $this->setParameter("merOrderAmt", 0.01);          //订单总金额,必输
        $this->setParameter("custPhone", "");              //买方手机号
        $this->setParameter("merOrderUrl", "");            //商品展示网址
        $this->setParameter("merOrderName", "");           //商品名称
        $this->setParameter("merShortName", "");           //商品简称
        $this->setParameter("merOrderDesc", "");           //商品描述
        $this->setParameter("Remark", "");                 //商户备注
        $this->setParameter("Price", "");                  //商品单价
        $this->setParameter("merOrderCount", "");          //购买数量
        $this->setParameter("saleAcct", "");               //卖方账号
        $this->setParameter("saleAmt", "");                //卖方金额
        $this->setParameter("payMethod", 1);               //默认支付方式
        $this->setParameter("merRemak", "");               //商户备注
    }
    //设置商户id
    function setMerId($merId){
        $this->merId=$merId;
    }
    //设置编码
    function setCharset($charset){
        $this->charset=$charset;
    }
    //设置服务端url
    function setGateUrl($gateUrl){
        $this->gateUrl=$gateUrl;
    }
    //设置支付交易代码
    function setCode($code){
        $this->code = $code;
    }
    //获取支付交易代码
    function getCode(){
        return $this->code;
    }
    //设置参数
    function setParameter($key, $value){
        $this->parameters[$key] = $value;        
    }
    
    //获取参数
    function getParameter($key){
        return $this->parameters[$key];
    }
    
    //组织xml
    function createXml(){
	$this->debugInfo="";

        $this->xml  =  '<?xml version="1.0" encoding="UTF-8"?>';
        $this->xml .= '<business code="'.$this->getCode().'">';
        $this->xml .= '<group>';
        foreach($this->parameters as $k=>$v){
            $this->xml .= '<data name="'.$k.'" value="'.$v.'"/>';
        }
        $this->xml .= '</group>';
        $this->xml .= '</business>';
        
        $this->debugInfo = "组织xml成功";
    }
    //获取xml
    function getXml(){
        return $this->xml;
    }
    
    /**
     * 和服务器通讯
     * @param  $data
     * @return 服务器返回信息 
     */
    function getServerData($data) 
    {
	$this->debugInfo="";
        if(empty ($data)){
            $this->debugInfo = "数据为空";
            return "";
        }
        $return = '';
        $limit = 0;
        $timeout = 15;
        $post = "merId=".$this->merId."&charset=".$this->charset."&data=".str_replace("+", "%2b", $data);
        $matches = parse_url($this->gateUrl);
        !isset($matches['host']) && $matches['host'] = '';
        !isset($matches['path']) && $matches['path'] = '';
        !isset($matches['query']) && $matches['query'] = '';
        !isset($matches['port']) && $matches['port'] = '';
        $host = $matches['host'];
        $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
        $port = !empty($matches['port']) ? $matches['port'] : 80;
        
        $out = "POST $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= 'Content-Length: '.strlen($post)."\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cache-Control: no-cache\r\n\r\n";
        $out .= $post;
        
        $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if(!$fp) 
        {
            $this->debugInfo = "连接服务器失败";
            return "";
        } 
        else 
        {
            stream_set_blocking($fp, TRUE);
            stream_set_timeout($fp, $timeout);
            @fwrite($fp, $out);
            $status = stream_get_meta_data($fp);
            if(!$status['timed_out']) 
            {
                while (!feof($fp))
                {
                    if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) 
                    {
                        break;
                    }
                }
                $stop = false;
                while(!feof($fp) && !$stop)
                {
                    $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                    $return .= $data;
                    if($limit) 
                    {
                         $limit -= strlen($data);
                         $stop = $limit <= 0;
                    }
                }
            }
            @fclose($fp);
            $this->debugInfo = "连接服务器成功";
            return $return;
        }
    } 
    //解析xml
    function loadXml($str){
	$this->debugInfo = "";
        $data = array();
        try{
            $str = str_replace('&', "00000000#######", $str);
            $xml = @simplexml_load_string($str);
            
            if(isset($xml[0]['code']))$data['code']= $xml[0]['code'];
            $count = isset($xml->group->data)?count($xml->group->data):0;
            for($i=0;$i<$count;$i++){
                $k=$v="";
                $j=0;
                foreach($xml->group->data[$i]->attributes() as $a => $b) {
                    if($j==0)$k=$b;
                    else $v=$b;
                    $j++;
                }
                $v = str_replace('00000000#######', "&", $v);
                $data["$k"] = $v;
            }
        }catch (Exception $e){
	    $this->debugInfo = "解析xml失败";
	}
        if(!isset($data['Errorcode'])){
            $data['Errorcode']="1000000001";
            $data['returnMessage'] = "返回信息错误或解析xml失败";
	    $this->debugInfo = "返回信息错误或解析xml失败";
        }
	$this->debugInfo = "xml解析成功";
        return $data;
    }
    //返回信息
    function getDebugInfo(){
        return $this->debugInfo."<br/>"; 
    }
}

?>
