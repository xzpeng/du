<?php if (!defined('THINK_PATH')) exit();?>
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
					<div class="navbar-default sidebar"
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
												href="/User_Index_shjk.html">
												接口信息
											</a>
										</li>
										<li>
											<a
												href="/User_Index_sjtgg.html">
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
					</div><!-- /.navbar-static-side -->
				</nav>
				<div id="page-wrapper">
					<div class="row">
						<div class="col-lg-12">
							<h3 class="page-header">
								提现记录
								</h1>
						</div><!-- /.col-lg-12 -->
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">T+1</div>
						<div class="panel-body">
							<div class="col-lg-12">
								<div class="alert alert-info"
									role="alert">
									当日提款总额：
									<strong><?php echo ($drtkmoney); ?> </strong>
									元&nbsp;&nbsp;当日提款次数：
									<strong><?php echo ($drtknum); ?></strong>
									次&nbsp;&nbsp;当日提款总手续费：
									<strong></strong>
									<?php echo ($drtksxfmoney); ?> 笔
								</div>
							</div>
							<div class="form-group input-group">
								<input type="text" name="sq_date"
									id="sq_date" class="form-control"
									onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:false})"
									value="<?php echo ($_GET['sq_date']); ?>">
								<span
									class="input-group-addon fix-border"
									style="border-right: 0;border-left: 0;">
									状态
								</span>
								<select name="zt" id="zt"
									class="form-control">
									<option value="">全部</option>
									<option value="0">未处理</option>
									<option value="2">已打款</option>
								</select>
								<span
									class="input-group-addon fix-border"
									style="border-right: 0;border-left: 0;">
									类型
								</span>
								<select name="T" id="T"
									class="form-control">
									<option value="">全部</option>
									<option value="1">T + 1</option>
									<option value="0">T + 0</option>
								</select>
								<span class="input-group-btn">
									<button class="btn btn-default"
										id="SearchButton" type="submit">
										<i class="fa fa-search"></i>
									</button>
								</span>
							</div>
						</div>
		
<table border="0" cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover text-center">   <tr style="color:#69C; font-weight:bold;">
   <td>提款金额</td>
   <td>手续费</td>
   <td>到账金额</td>
   <td>银行名称</td>
   <td>分行名称</td>
   <td>开户姓名</td>
   <td>申请时间</td>
   <td>类型</td>
   <td>状态</td>
   </tr>
     
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
   <td style="color:#666; font-weight:bold;">￥<?php echo ($vo["tk_money"]); ?></td>
   <td style="color:#666; font-weight:bold;">￥<?php echo ($vo["sxf_money"]); ?></td>
   <td style="color:#666; font-weight:bold;">￥<?php echo ($vo["money"]); ?></td>
   <td style="color:#333;"><?php echo ($vo["bankname"]); ?></td>
   <td style="color:#333;"><?php echo ($vo["fen_bankname"]); ?></td>
   <td style="color:#333;"><?php echo ($vo["bank_number"]); ?></td>
   <td style="color:#666;"><?php echo ($vo["sq_date"]); ?></td>
   <td>
   <?php if($vo["T"] == 0): ?>T + 0
   <?php else: ?>
   T + 1<?php endif; ?>
   </td>
   <td>
   
   <?php if($vo["ZT"] == 0): ?><span style="color:#F00">未处理</span> 
<?php else: ?>
    <?php if($vo["ZT"] == 1): ?>正在处理中
    <?php else: ?>
        已打款<?php endif; endif; ?>

   
   </td>
   </tr><?php endforeach; endif; else: echo "" ;endif; ?> 
   
   </table>
      
      <div class="selectclass" style="text-align:center;">
      <?php echo ($page); ?>
      </div>
  </div>
		<div class="panel-footer"></div>
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
					"/User_Index_tkjl.html?sq_date="+$("#sq_date").val()+"&zt="+$("#zt").val()+"&T="+$("#T").val();
					});

					});
				</script>
				<script type="text/javascript">
					$("#zt").val(); $("#T").val();
				</script>
		</body>
	</html>
</element>