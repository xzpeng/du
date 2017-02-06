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
						<link href="//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
				
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
								记录统计
								</h1>
						</div><!-- /.col-lg-12 -->
					</div>
					<form name="Form1" id="Form1" method="post"
						action="/User_Index_jltj.html" onsubmit="return check()">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group input-group">
									<input type="text" name="ksjy_date"
										id="ksjy_date" class="form-control" value="<?php echo ($ksjy_date); ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})">
									<span
										class="input-group-addon fix-border"
										style="border-right: 0;border-left: 0;">
										至
									</span>
									<input type="text" name="jsjy_date"
										id="jsjy_date" class="form-control" value="<?php echo ($jsjy_date); ?>"
										onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})">
									<input type="hidden" id="jylx"
										name="jylx" value="" />
									<span class="input-group-btn">
										<button class="btn btn-default"
											type="submit">
											<i class="fa fa-search"></i>
										</button>
									</span>
								</div>
							</div><!-- /.col-lg-12 -->
						</div>
						<div class="row">
							<div class="col-lg-6">
								<div class="panel panel-success">
									<div class="panel-heading">总额</div>
									<div class="panel-body">
										<p><?php echo ($daymoney); ?>元</p>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="panel panel-success">
									<div class="panel-heading">
										成功订单
									</div>
									<div class="panel-body">
										<p><?php echo ($daynum); ?>笔</p>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="__hash__"
							value="4b37961ab8cab39bd016450a8a61077f_8ef7d43bea592db7ec678dd0b2cb037f" />
					</form>
			
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
<script type="text/javascript" src="/Public/My97DatePicker/WdatePicker.js"></script>

		<script>	function check(){
	if($("#ksjy_date").val() == ""){
	    alert("请选择交易开始时间");
		//$("#ksjy_date").focus();
		return false;
	}
	
	if($("#jsjy_date").val() == ""){
	    alert("请选择交易结束时间");
		//$("#jsjy_date").focus();
		return false;
	}
}
$("#jylx").val('');

function daochu(){
	if(confirm("确认导出吗？")){
	window.open("/User_Index_ExportExcel.html?sq_date="+$("#sq_date").val());
	}
	}
	
    </script></body></html>