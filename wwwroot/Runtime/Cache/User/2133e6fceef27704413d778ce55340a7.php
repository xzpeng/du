<?php if (!defined('THINK_PATH')) exit();?>﻿
<!DOCTYPE html>
<element>
	<html>
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport"
				content="width=device-width, initial-scale=1">
		<title><?php echo C("WEB_NAME");?>-商户中心</title>
			<link rel="stylesheet" type="text/css"
				href="public/css/bootstrap.min.css?v=1483669556" />
			<link rel="stylesheet" type="text/css"
				href="public/css/metisMenu.min.css?v=1483669556" />
			<link rel="stylesheet" type="text/css"
				href="public/css/dataTables.bootstrap.css?v=1483669556" />
			<link rel="stylesheet" type="text/css"
				href="public/css/sb-admin-2.css?v=1483669556" />
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
								资金记录
								</h1>
						</div><!-- /.col-lg-12 -->
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							资金变动记录
							<em style="float: right;">线上保留7天</em>
						</div>
						<div class="panel-body">
							<div class="form-group input-group">
								<input type="text" name="sq_date"
									id="sq_date" class="form-control"
									onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:false})"
									value="<?php echo ($_GET['sq_date']); ?>">
								<span
									class="input-group-addon fix-border"
									style="border-right: 0;border-left: 0;">
									至
								</span>
								<input type="text" name="sq_date_js"
									id="sq_date_js" class="form-control"
									onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:false})"
									value="<?php echo ($_GET['sq_date_js']); ?>">
								<span
									class="input-group-addon fix-border"
									style="border-right: 0;border-left: 0;">
									类型
								</span>
								<select name="lx" id="lx"
									class="form-control">
									<option value="">全部类型</option>
									<option value="1">网银交易</option>
									<option value="2">提成记录</option>
									<option value="4">提款记录</option>
									<option value="5">减金记录</option>
									<option value="6">增金记录</option>
								</select>
								<span
									class="input-group-addon fix-border"
									style="border-right: 0;border-left: 0;">
									每页
								</span>
								<select name="pagepage" id="pagepage"
									class="form-control">
									<option value="10">10条</option>
									<option value="15">15条</option>
									<option value="20">20条</option>
									<option value="25">25条</option>
									<option value="30">30条</option>
									<option value="35">35条</option>
									<option value="40">40条</option>
									<option value="45">45条</option>
									<option value="50">50条</option>
								</select>
								<span class="input-group-btn">
									<button class="btn btn-default"
										id="SearchButton" type="submit">
										<i class="fa fa-search"></i>
									</button>
								</span>
							</div>
						</div>
						<table cellpadding="0" cellspacing="0"
							border="0" id="listuser"
							class="table table-striped table-bordered table-hover text-center">
<tr>
<td colspan="2" style="text-align:right; font-size:20px; font-weight:bold;">合计金额：</td>
<td style="font-size:20px; color:#00F; font-weight:bold;"><?php echo ($hjje); ?></td>
<td colspan="2">&nbsp;</td>


</tr>
<tr style="background-color:#5d7b9d; color:#fff;">

<td style="width:30%">交易时间</td>
<td>原金额</td>
<td>变动金额</td>
<td>变动后金额</td>
<td style="width:10%;">交易类型</td>
</tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
<td style="font-size:18px; width:30%;">
<?php echo ($vo["datetime"]); ?>
&nbsp;</td>
<td style="font-size:20px; font-weight:bold; color:#060;"><?php echo ($vo["ymoney"]); ?></td>
<td style="font-size:20px; font-weight:bold;">
<?php if($vo["money"] > 0): ?><span style="color:#0F0;">+
<?php else: ?>
<span style="color:#F00"><?php endif; ?>
<?php echo ($vo["money"]); ?>
</span>
</td>
<td style="font-size:20px; font-weight:bold; color:#66F"><?php echo ($vo["gmoney"]); ?></td>
<td style="width:10%;">
<?php switch($vo["lx"]): case "1": ?>网银交易<?php break;?>
  <?php case "2": ?>提成记录<?php break;?>
  <?php case "4": ?>提款记录<?php break;?>
  <?php case "5": ?>减金记录<?php break;?>
  <?php case "6": ?>增金记录<?php break; endswitch;?></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr>
<td colspan="20"><?php echo ($page); ?></td>
</tr>
</table>
   </div>
						<div class="panel-footer"></div>
					</div>
				</div>
			</div>
												<script type="text/javascript"
					src="public/js/jquery.min.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="public/js/bootstrap.min.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="public/js/metisMenu.min.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="public/js/jquery.dataTables.min.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="public/js/dataTables.bootstrap.min.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="public/js/sb-admin-2.js?v=1483670095">
				</script>
				<script type="text/javascript"
					src="public/js/wyjyjl.js?v=1483670095">
				</script>
			<script type="text/javascript">
				$(document).ready(function(e) {

				$("#SearchButton").click(function(e) {
				window.location.href =
				"/User_Index_zjbdjl.html?sq_date="+$("#sq_date").val()+"&sq_date_js="+$("#sq_date_js").val()+"&pagepage="+$("#pagepage").val()+"&lx="+$("#lx").val();
				});

				});
			</script>
			<script type="text/javascript">
				$("#pagepage").val(); $("#lx").val();
			</script>
		</body>
	</html>
</element>