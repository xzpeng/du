<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
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
	<?php echo ($_SESSION['UserName']); ?>							<i class="fa fa-caret-down"></i>
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
								订单汇总
								</h1>
						</div><!-- /.col-lg-12 -->
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									交易记录
									<em style="float: right;">
										线上保留7天
									</em>
								</div><!-- /.panel-heading -->
								<div class="panel-body">
									<div class="table-responsive">
<table
											class="table table-striped table-bordered table-hover text-center">   <tr style="color:#69C; font-weight:bold;">

   <tr style="color:#69C; font-weight:bold;">
  <!-- <td>支付类型</td>
   <td style="width:20%;">订单号</td>
   <td style="width:13%">交易时间</td>
   <td>交易金额</td>
   <td>手续费</td>
   <td>实收金额</td>
   <td style="width:10%;">充值银行</td>
   <td>状态</td>
   <td>通知</td>-->
   <td style="width:12%;">支付方式</td>
   <td style="width:24%;">商户订单号</td>
   <td style="width:12%;">成功时间</td>
   <td style="width:8%;">成功金额</td>
   <td style="width:8%;">实收金额</td>
   <td style="width:8%;">类型</td>
   <td style="width:8%;">状态</td>
   <td style="width:8%;">通知</td>
   </tr>
 
     <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
      <td style="width:12%;"><?php echo ($vo["bankname"]); ?></td>
      <td style="width:24%;"><?php echo ($vo["TransID"]); ?></td>
      <td style="width:12%;"><?php echo ($vo["TradeDate"]); ?></td>
      <td style="width:8%;"><?php echo ($vo["trademoney"]); ?></td>
      <td style="width:8%;"><?php echo ($vo["OrderMoney"]); ?></td>
      <td style="width:8%;">
       <?php if($vo["typepay"] == 0): ?>订单
   <?php else: ?>
   充值<?php endif; ?>
      </td>
      <td style="width:8%;">
       <?php if($vo["Zt"] == 1): ?>成功
  <?php else: ?>
  未处理<?php endif; ?>
      </td>
      <td style="width:8%;">
      <?php echo R('/User/Index/tzshow',array($vo["TransID"]));?>
      </td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?> 
      </table>
      
 


      <div class="selectclass" style="text-align:center;">
      <?php echo ($page); ?>
      </div>
									
									
									</div><!-- /.table-responsive -->
								</div><!-- /.panel-body -->
							</div><!-- /.panel -->
						</div>
					</div>
				</div>
				<script type="text/javascript"
					src="public/js/jquery.min.js">
				</script>
				<script type="text/javascript"
					src="public/js/bootstrap.min.js">
				</script>
				<script type="text/javascript"
					src="public/js/metisMenu.min.js">
				</script>
				<script type="text/javascript"
					src="public/js/jquery.dataTables.min.js">
				</script>
				<script type="text/javascript"
					src="public/js/dataTables.bootstrap.min.js">
				</script>
				<script type="text/javascript"
					src="public/js/sb-admin-2.js">
				</script>
				<script type="text/javascript"
					src="public/js/wyjyjl.js">
				</script>
				<script>
					$(document).ready(function() {
					$('#dataTables-example').DataTable({ responsive:
					true }); });
				</script>
		</body>
	</html>
</element>