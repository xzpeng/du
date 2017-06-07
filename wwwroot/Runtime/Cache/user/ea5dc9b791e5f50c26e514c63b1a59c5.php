<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport"
				content="width=device-width, initial-scale=1">
			<title><?php echo C("WEB_NAME");?>-商户中心</title>
			<link rel="stylesheet" type="text/css"
				href="Public/css/bootstrap.min.css?v=1483669556" />
			<link rel="stylesheet" type="text/css"
				href="Public/css/metisMenu.min.css?v=1483669556" />
			<link rel="stylesheet" type="text/css"
				href="Public/css/dataTables.bootstrap.css?v=1483669556" />
			<link rel="stylesheet" type="text/css"
				href="Public/css/sb-admin-2.css?v=1483669556" />
						<link href="//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css?v=1483670719" rel="stylesheet">
				
		</head>
		<body style="margin-top: -20px">
			<div id="wrapper"><!-- Navigation -->
				<nav class="navbar navbar-default navbar-static-top"
					role="navigation" style="margin-bottom: 0">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle"
							data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">
								Toggle navigation
							</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="/User">信付云计费</a>
					</div><!-- /.navbar-header -->
					<ul class="nav navbar-top-links navbar-right"><!-- /.dropdown -->
						<li class="dropdown">
							<a class="dropdown-toggle"
								data-toggle="dropdown" href="#">
								<?php echo ($_SESSION['UserName']); ?>	
								<i class="fa fa-caret-down"></i>
							</a>
							<ul class="dropdown-menu dropdown-user">
								<li>
									<a href="/User_Index_aqxx.html">
										<i class="fa fa-user fa-fw"></i>
										安全设置
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a
										href="/User_Index_ExitLogin.html">
										<i
											class="fa fa-sign-out fa-fw">
										</i>
										退出登录
									</a>
								</li>
							</ul><!-- /.dropdown-user -->
						</li><!-- /.dropdown -->
					</ul><!-- /.navbar-top-links -->
                                             ﻿<div class="navbar-default sidebar"
						role="navigation">
						<div class="sidebar-nav navbar-collapse">
							<ul class="nav" id="side-menu">
								<li>
									<a href="/User">
										<i
											class="fa fa-dashboard fa-fw">
										</i>
										首页
									</a>
								</li>
								<li>
									<a href="#">
										<i
											class="fa fa-bar-chart-o fa-fw">
										</i>
										交易管理
										<span class="fa arrow"></span>
									</a>
									<ul class="nav nav-second-level">
										<li>
											<a
												href="User_Index_wyjyjl.html">
												交易记录
											</a>
										</li>
										<li>
											<a
												href="User_Index_jltj.html">
												记录统计
											</a>
										</li>
									</ul><!-- /.nav-second-level -->
								</li>
								<li>
									<a href="#">
										<i class="fa fa-rmb fa-fw"></i>
										财务管理
										<span class="fa arrow"></span>
									</a>
									<ul class="nav nav-second-level">
										<li>
											<a
												href="User_Index_tkyh_banktype_0.html">
												银行设置
											</a>
										</li>
										<li>
											<a
												href="User_Index_tktx.html">
												申请提现
											</a>
										</li>
										<li>
											<a
												href="User_Index_tkjl.html">
												提现记录
											</a>
										</li>
										<li>
											<a
												href="User_Index_zdjsb.html">
												自动提款记录
											</a>
										</li>
										<li>
											<a
												href="User_Index_zjbdjl.html">
												资金记录
											</a>
										</li>
									</ul><!-- /.nav-second-level -->
								</li>
								<li>
									<a href="#">
										<i class="fa fa-user fa-fw"></i>
										用户管理
										<span class="fa arrow"></span>
									</a>
									<ul class="nav nav-second-level">
										<li>
											<a
												href="User_Index_shjk.html">
												接口信息
											</a>
										</li>
										<li>
											<a
												href="User_Index_shtd.html">
												通道信息
											</a>
										</li>
                                                                                 <li>
											<a
												href="User_Index_skym.html">
												收款主页
											</a>
										</li>
										<li>
											<a
												href="User_Index_sjtgg.html">
												平台公告
											</a>
										</li>
									</ul><!-- /.nav-second-level -->
								</li>
								<li>
									<a href="#">
										<i class="fa fa-cogs fa-fw"></i>
										系统管理
										<span class="fa arrow"></span>
									</a>
									<ul class="nav nav-second-level">
										<li>
											<a
												href="/User_Index_dllist.html">
												登录记录
											</a>
										</li>
										<li>
											<a
												href="/User_Index_aqxx.html">
												安全设置
											</a>
										</li>
									</ul><!-- /.nav-second-level -->
								</li>
							</ul>
						</div><!-- /.sidebar-collapse -->
					</div>
                                             <!-- /.navbar-static-side -->
				</nav>
				<div id="page-wrapper">
					<div class="row">
						<div class="col-lg-12">
							<h3 class="page-header">
								申请提现
								</h1>
						</div><!-- /.col-lg-12 -->
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">T+1</div>
						<div class="panel-body">
							<div class="col-lg-12">
								<div class="alert alert-info"
									role="alert">
									<strong>
										2016-11-15日开始，提现系统升级，只能提取2016-11-15日之前的款项，之后的款项改为自动结算，不用每天提款，升级期间余额显示不增加为正常现象，不影响计费。
									</strong>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="alert alert-info"
									role="alert">
									单笔最小提款金额：
									<strong><?php echo ($minmoney); ?></strong>
									元&nbsp;&nbsp;单笔最大提款金额：
									<strong><?php echo ($maxmoney); ?> </strong>
									元&nbsp;&nbsp;每天可提款总金额：
									<strong><?php echo ($mtsxmoney); ?></strong>
									元&nbsp;&nbsp;每天提款最大次数：
									<strong><?php echo ($mttkcs); ?> </strong>
									次&nbsp;&nbsp;
								</div>
							</div>
							<div class="col-lg-12">
								<div class="alert alert-success"
									role="alert">
									今天已申请提款总金额：<?php echo ($dttkcs); ?> 
									<strong></strong>
									元&nbsp;&nbsp;今天已申请提款：
									<strong><?php echo ($yqlmoney); ?></strong>
									次
								</div>
							</div>
						</div>
						<div id="t0content" class="tcontent">
							<table
								class="table table-striped table-bordered table-hover text-center">
								<tr>
									<td>可用余额：</td>
									<td>
										<span style="color:#F00">
											<?php echo ($mymoney); ?> 
										</span>
										   <input type="hidden" name="kyye" id="kyye" value="<?php echo ($mymoney); ?>">
										元
										<input type="hidden" name="kyye"
											id="kyye" value="0.000"></td>
								</tr>
								<tr>
									<td>提款金额：</td>
									<td>
										<input type="text" name="money"
											id="money" onkeyup="clearNoNum(this)"
											style="color:#F00; width:150px; vertical-align:middle;" />
										&nbsp;&nbsp;&nbsp;&nbsp;
										<button type="button"
											onclick="javascript:checkmoney();" class="btn btn-primary">
											计算费率
										</button>
									</td>
								</tr>
								<tr>
									<td>交易手续费（大于10000免手续费）：</td>
									<td>
										<span id="sxf"
											style="color:#0C0">
										</span>
										&nbsp;
									</td>
								</tr>
								<tr>
									<td>实际结算金额：</td>
									<td>
										<span id="sjdzjj"
											style="color:#F00">
										</span>
										&nbsp;元&nbsp;
									</td>
								</tr>
								<tr>
									<td>提款银行：<?php echo ($mrbank); ?>  </td>
									<td>
							<input type="hidden" id="tkyhhidden" value="<?php echo ($mrbankid); ?>">
   <span id="mrbank" style="color:#F63"><?php echo ($mrbank); ?></span>
   <span id="selecttkyh" style="display:none;">
   <select name="tkyh" id="tkyh" style="font-size:20px;">
   <?php if(is_array($listbank)): $i = 0; $__LIST__ = $listbank;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["BankName"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
   </select>
   <script type="text/javascript">
   $("#tkyh").val(<?php echo ($mrbankid); ?>);
   </script>
									</td>
								</tr>
								<tr>
									<td>支付密码(默认123456)：</td>
									<td>
										<input type="password"
											name="paypassword" id="paypassword" style="width:248px;">
										<input type="hidden" name="T"
											id="T" value="1">
										<input type="hidden"
											name="minmoney" id="minmoney" value="<?php echo ($minmoney); ?>">
										<input type="hidden"
											name="maxmoney" id="maxmoney" value="<?php echo ($maxmoney); ?>">
										<input type="hidden"
											name="mtsxmoney" id="mtsxmoney" value="<?php echo ($mtsxmoney); ?>">
										<input type="hidden"
											name="yqlmoney" id="yqlmoney" value="<?php echo ($mttkcs); ?>"></td>
								</tr>
								<tr>
									<td colspan="2"
										style="text-align:center;">
										<button type="button"
											onclick="sqtk();" class="btn btn-primary">
											申请提现
										</button>
									</td>
								</tr>
							</table>
						</div>
						<table
							class="tabts table table-striped table-bordered table-hover text-center"
							style="display:none;">
							<tr>
								<td
									style="height:400px; text-align:center; color:#F00;">
									正在处理，请稍后....
								</td>
							</tr>
						</table>
						<div class="panel-footer">
							注：申请提款成功后，所提金额去除手续费后第二个工作日打到您的提款银行账上！
						</div>
					</div>
				</div>
														<script type="text/javascript"
					src="Public/js/jquery.min.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="Public/js/bootstrap.min.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="Public/js/metisMenu.min.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="Public/js/jquery.dataTables.min.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="Public/js/dataTables.bootstrap.min.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="Public/js/sb-admin-2.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="Public/js/wyjyjl.js?v=1483670095">
				</script>

<script type="text/javascript" src="http://du.pengxiaozhou.com/Public/User/js/tktx.js?v=1483687757"></script><script type="text/javascript">

				</script>
				<script>
					$(document).ready(function() {
					$('#dataTables-example').DataTable({ responsive:
					true }); });
				</script>
		</body>
	</html>