<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="/Public/SjtAdminSjt/css/css.css" />
<script type="text/javascript" src="/Public/User/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="/Public/SjtAdminSjt/js/listuser.js"></script>

</head>

<body>
<div class="listmenu">
<input type="button" value="重置登录密码为123456" id="czdlmm">
<input type="button" value="重置支付密码为123456" id="czzfmm">
<input type="button" value="删 除" id="shanchu">
<input type="button" value="审 核" id="plsh">
<input type="button" value="冻 结" id="dongjie">
<input type="button" value="解 冻" id="jiedong">
<input type="button" value="删除密码保卡限制" id="scmbkxz">
<!--<input type="button" value="金 额">-->
<!--<input type="button" value="导 出">-->
<!--<input type="button" value="手续费">-->
<input type="button" value="开通T+0" id="kaitongT0">
<input type="button" value="关闭T+0" id="kaitongT1">
<input type="button" value="网银通道" id="wytd">
<input type="button" value="发送通知" id="fstz">

</div>
<div class="listmenu" style="text-align:right;">
&nbsp;&nbsp;&nbsp;&nbsp;请输入【商户号】或【用户名】或【QQ号】或【手机号】或【姓名】：
<input type="text" size="30" name="SearchContent" id="SearchContent" value="<?php echo ($_GET['SearchContent']); ?>" />&nbsp;&nbsp;
<select name="UserType" id="UserType">
   <option value="">通道类型</option>
   <?php if(is_array($listtongdao)): $i = 0; $__LIST__ = $listtongdao;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["apiname"]); ?>"><?php echo ($vo["myname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
  
</select>&nbsp;&nbsp;
<script type="text/javascript">
$("#UserType").val('<?php echo ($_GET['UserType']); ?>');
</script>
<select name="Zt" id="Zt">
   <option value="">全部商户审核</option>
   <option value="2">已审核</option>
   <option value="1">等待审核</option>
   <option value="0">未提交</option>
</select>
<script type="text/javascript">
$("#Zt").val(<?php echo ($_GET['Zt']); ?>);
</script>
<select name="status" id="status">
   <option value="">全部状态</option>
<!--   <option value="0">未激活</option>-->
   <option value="1">正常</option>
   <option value="2">锁定</option>
</select>
<script type="text/javascript">
$("#status").val(<?php echo ($_GET['status']); ?>);
</script>
<select name="Userlx" id="Userlx">
<option value="">全部用户类型</option>
<option value="1">普通商户</option>
<option value="5">代理商</option>
</select>
<script type="text/javascript">
$("#Userlx").val(<?php echo ($_GET['Userlx']); ?>);
</script>
<input type="button" value="搜 索" id="SearchButton" />
</div>
<table cellpadding="0" cellspacing="0" border="0" id="listuser">
<tr style="background-color:#5d7b9d; color:#fff;">
<td id="xzxz" xz="0" style="cursor:pointer;">选择</td>
<td>用户类型</td>
<td>用户名</td>
<td>商户号</td>
<td>网银通道</td>
<td>姓名</td>
<td>QQ</td>
<td>身份证号</td>
<td>手机号</td>
<td>状态</td>
<td>商户审核</td>
<td>T+0</td>
<td colspan="2">金额</td>
<td colspan="2">通道</td>
<td colspan="2">提款设置</td>
<td>上级账号</td>
<td>下级数量</td>
</tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["Zt"] == 0): ?><tr style="background-color:#E4E4E4">
<?php else: ?>
  <?php if($vo["Zt"] == 1): ?><tr style="background-color:#FAC7B6">
  <?php else: ?>  <tr><?php endif; endif; ?>

<td style="width:5%;"><input type="checkbox" class="xzxz" name="xz" value="<?php echo ($vo["Shh"]); ?>" zt="<?php echo ($vo["Zt"]); ?>"></td>
<td>
<?php if($vo["UserType"] == 5): ?><span style="font-weight:bold; color:#F00">代理商</span>
<?php else: ?>
商户<?php endif; ?>
</td>
<td><a href="javascript:editusername(<?php echo ($vo["Shh"]); ?>)"><?php echo ($vo["UserName"]); ?></a>&nbsp;</td>
<td><a href="<?php echo U("ShangHu/dluser","userid=".$vo["Shh"]);?>" target="_blank"><?php echo ($vo['Shh']+10000); ?></a>&nbsp;</td>
<td style="color:#00F;">
<?php echo (getttongdao($vo['PayBank'])); ?>&nbsp;
</td>
<td><?php echo ($vo["Compellation"]); ?>&nbsp;</td>
<td><?php echo ($vo["qq"]); ?>&nbsp;</td>
<td><?php echo ($vo["IdentificationCard"]); ?>&nbsp;</td>
<td><?php echo ($vo["MobilePhone"]); ?>&nbsp;</td>
<td>
<?php switch($vo["status"]): case "0": ?>未激活<?php break;?>
<?php case "1": ?>正常<?php break;?>
<?php case "2": ?><font style="color:#F00">锁定</font><?php break; endswitch;?>
</td>

<td>
<?php if($vo["Zt"] == 0): ?>未提交
<?php else: ?>
  <?php if($vo["Zt"] == 1): ?><a href="javascript:dakai('<?php echo ($vo["Shh"]); ?>')">等待审核</a>
  <?php else: ?><a href="javascript:dakai('<?php echo ($vo["Shh"]); ?>')">已审核</a><?php endif; endif; ?>
</td>
<td>
<?php if($vo["t0"] == 0): ?>否
<?php else: ?>
<span style="color:#090">是</span><?php endif; ?>
</td>
<td><span style="color:#F00; width:30px;"><b>￥</b><?php echo ($vo["money"]); ?></span>
</td>
<td><input type="button" value="金额" onclick="javascript:xgje(<?php echo ($vo["Shh"]); ?>);"></td>
<td><span style="color:#090; width:30px;"><?php echo (getsxf($vo['Shh'])); ?></span></td>
<td><input type="button" value="通道" onclick="javascript:sxfs(<?php echo ($vo["Shh"]); ?>);"></td>
<td><input type="button" value="设置" onclick="javascript:tksz(<?php echo ($vo["Shh"]); ?>);"></td>
<td><input type="button" value="银行" onclick="javascript:tkyh(<?php echo ($vo["Shh"]); ?>);"></td>
<td>
 <?php echo (getshangji($vo['Shh'])); ?>
</td>
<td><?php echo (getshanghu($vo['Shh'])); ?></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr style="font-size:14px;"><td colspan="20"><?php echo ($page); ?>&nbsp;</td></tr>
</table>
</body>
</html>