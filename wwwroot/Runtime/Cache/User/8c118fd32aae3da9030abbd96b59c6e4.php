<?php if (!defined('THINK_PATH')) exit();?>﻿	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport"
				content="width=device-width, initial-scale=1">
				<title>商户中心</title>
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
								首页
								</h1>
						</div><!-- /.col-lg-12 -->
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							商户ID：
							<strong><?php echo ($_SESSION["UserID"]+10000); ?></strong>
				(
<?php if($zt == 0): ?>未审核
<?php elseif($zt == 1): ?>
审核中
<?php elseif($zt == 2): ?>
已审核<?php endif; ?>
)</span></span>
							<span
								style="color:#48A981;float: right; font-size: 13px;">
								上次登录时间：<?php echo ($_SESSION['scdate']); ?>
							</span>
							<span
								style="color:#48A981; float: right; font-size: 13px;">
								登录IP：<?php echo ($_SESSION['scip']); ?>
							</span>
						</div>
						<div class="panel-body">
							<div class="list-group">
								<a href="#"
									class="list-group-item disabled">
									平台公告
								</a>
										    <?php if(is_array($listgg)): $i = 0; $__LIST__ = $listgg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="/User_Index_sjtggshow_newsid_<?php echo ($vo["id"]); ?>.html" class="list-group-item" ><?php echo ($vo["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
								<a href="/User_Index_sjtgg.html"
									class="list-group-item" style="text-align: right;">
									更多
								</a>
							</div>
						</div>
						<div class="panel-footer">
							当日订单数量：
							<strong></strong><?php echo ($dayorder); ?>
							笔&nbsp;&nbsp;/&nbsp;&nbsp;当前余额:<?php echo ($Money); echo ($daymoney); ?>
							<strong></strong>
							元&nbsp;&nbsp;/&nbsp;&nbsp;银行卡：<?php echo ($BankName); ?>
							<strong></strong>
						</div>
					</div>
					<script type="text/javascript"
				src="Public/js//jquery.min.js">
			</script>
			<script type="text/javascript"
				src="Public/js/bootstrap.min.js">
			</script>
			<script type="text/javascript"
				src="Public/js/metisMenu.min.js">
			</script>
			<script type="text/javascript"
				src="Public/js/sb-admin-2.js">
			</script>
			<script type="text/javascript"
				src="Public/js/js.js">
			</script>
		</body>
	</html>