<?php
return array(
    //禁js,css缓存
    'TMPL_PARSE_STRING' => array(
        '__Static__' => __ROOT__ . '/Static',
        '.css"' => '.css?v='.time().'"',
        '.js"' => '.js?v='.time().'"',
    ),
	//'配置项'=>'配置值'
	'LOAD_EXT_CONFIG' => array('db','website'), //加载扩展配置文
	'APP_FILE_CASE'         =>  true,  // 是否检查文件的大小写 对Windows平台有效
	'APP_GROUP_LIST'        => 'Home,Payapi,User,SjtAdminSjt',      // 项目分组设定
	'DB_FIELDS_CACHE'       =>  false,
	'TMPL_L_DELIM'=>'<{', 
	'TMPL_R_DELIM'=>'}>',
	
    
);
?>