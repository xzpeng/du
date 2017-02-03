<?php
//mysql set
define("DASHAN",substr(dirname(__FILE__),0,-7));
define('CALL_FROM','');
define('DINGjin','500');
include_once(DASHAN."/system/db_units.php");
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ALL ^ E_NOTICE);
$dbhost = 'localhost';
$dbuser = 'sindpay';
$dbpw = '777777';	
$dbname = 'sindpay';
$pconnect = 0;
$dbcharset = 'utf8';
$DBTABLEPRE = 'pay_';

//mysql类
Class DBS {
	var $from = "";
	var $querynum = 0;

	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0) {
		if($pconnect) {
			if(!@mysql_pconnect($dbhost, $dbuser, $dbpw)) {
				$this->halt('Can not connect to MySQL server');
			}
		} else {
			if(!@mysql_connect($dbhost, $dbuser, $dbpw)) {
				$this->halt('Can not connect to MySQL server');
			}
		}

		if($this->version() > '4.1') {
			global $charset, $dbcharset;
			if(!$dbcharset && in_array(strtolower($charset), array('gbk', 'big5', 'utf-8'))) {
				$dbcharset = str_replace('-', '', $charset);
			}
			if($dbcharset) {
				mysql_query("SET NAMES '$dbcharset'");
			}
		}

		if($this->version() > '5.0.1') {
			mysql_query("SET sql_mode=''");
		}

		if($dbname) {
			mysql_select_db($dbname);
		}

	}

	function select_db($dbname) {
		return mysql_select_db($dbname);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	function query($sql, $type = '') {
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql)) && $type != 'SILENT') {
			$this->halt('MySQL Query Error', $sql);
		}
		//$handle = @fopen("sql.txt",a);
		//@fwrite($handle,$sql."\n");
		//@fclose($handle);
		$this->querynum++;
		return $query;
	}

	function affected_rows() {
		return mysql_affected_rows();
	}

	function error() {
		return mysql_error();
	}

	function errno() {
		return intval(mysql_errno());
	}

	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		$id = mysql_insert_id();
		return $id;
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function version() {
		return mysql_get_server_info();
	}

	function close() {
		return mysql_close();
	}
	
	//========== 网站额外添加，论坛中不用 ==========
	function get_one($SQL){
		$query = $this->query($SQL,'U_B');
		$rs = &mysql_fetch_array($query, MYSQL_ASSOC);
		return $rs;
	}

	function pw_update($SQL_1,$SQL_2,$SQL_3){
		$rt = $this->get_one($SQL_1);
		if ($rt) {
			$this->update($SQL_2);
		} else {
			$this->update($SQL_3);
		}
	}

	function update($SQL) {
		$GLOBALS['PW'] == 'pw_' or $SQL = str_replace(' pw_',' '.$GLOBALS['PW'],$SQL);
		if ($GLOBALS['db_lp'] == 1) {
			if (substr($SQL,0,7) == 'REPLACE') {
				$SQL = substr($SQL,0,7).' LOW_PRIORITY'.substr($SQL,7);
			} else {
				$SQL = substr($SQL,0,6).' LOW_PRIORITY'.substr($SQL,6);
			}
		}
		if (function_exists('mysql_unbuffered_query')){ 
			$query = mysql_unbuffered_query($SQL);
		} else {
			$query = mysql_query($SQL);
		}
		$this->query_num++;
		
		if (!$query)  $this->halt('Update Error: ' . $SQL);
		return $query;
	}
	//========== 网站额外 ==========

	function halt($message = '', $sql = '') {
		if ($this->from == "jinco") {
			require_once DISCUZ_ROOT.'./system/db_mysql_error.inc.php';
		} else {
			require_once('db_mysql_error.php');
			new DB_ERROR($sql."<br>".$msg);
		}
	}
}
session_start();
if(!ob_start()){
	ob_start();
}
//实例化
$db = new DBS(CALL_FROM);

$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
?>