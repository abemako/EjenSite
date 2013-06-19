<?php

mb_language('ja');
mb_internal_encoding('UTF-8');

/* require('MDB2.php');			//PEAR Log output to DB */
require_once('Log.php');		//PEAR Log output to File 

/* GlobalVariable グローバル変数 */

/* Log Output to DB(MySQL) */
/*
$LogObj = &Log::singleton("sql","log_table","AccountName",
	array("dsn"=>"mysql://UserName:Password@localhost/DBName"));
*/
/* Log Output to File ファイルへの出力 */
$LogObj = &Log::singleton("file","/var/www/html/log/PHP.log","UserName");
/* ログ出力レベルの指定  Log Output Level Choice  EMERG > ALERT > CRIT > ERR > WARNING > NOTICE > INFO > DEBUG */
$LogObj->setMask( Log::UPTO(PEAR_LOG_DEBUG) );


/* DB接続情報 DB Connect Setting */
$DsnParams = array(
			 "phptype"=>"mysql"
//			,"dbsyntax"=>""
			,"username"=>"DBuserName"
			,"password"=>"DBPassword"
			,"hostspec"=>"localhost"
			,"database"=>"DBName"
		);
		
/* mail送信元情報 Send Mail Setting */
$MailParams=array(
	 "host"=>"localhost"	//SMTPサーバ
	,"port"=>25				//SMTPサーバポート
	,"auth"=>FALSE			//SMTP認証の有無
	,"username"=>"MailUserId"	//ユーザID
	,"password"=>"MailPassword"	//パスワード
	);

/* システム設定 System public Variable Setting */
$SystemInfo=array(
	 "mailaddr"=>"test@test.com"		// メール送信元アドレス
	,"tmpUserlimitday"=>10				// 仮登録利用者の本登録期限 ?日後 デフォルト10日
	,"UserDeleteSpan"=>1095				// 最終ログイン日から ?日経過後に利用者削除 デフォルト3年
	);


// Smartyライブラリを読み込みます import Smarty Lib
require('Smarty/Smarty.class.php');

class Smarty_EjenSite extends Smarty {

   function Smarty_EjenSite()
   {

        // クラスのコンストラクタ。
        // これらは新しいインスタンスで自動的にセットされます。
        // ini constractor

        $this->Smarty();

        $this->template_dir = './smarty_path/templates/';
        $this->compile_dir  = './smarty_path/templates_c/';
        $this->config_dir   = './smarty_path/configs/';
        $this->cache_dir    = './smarty_path/cache/';

        $this->caching = true;
        $this->assign('app_name', 'ejenSite');
		$this->assign('HOME_URL', 'http://SiteURL/');
   }

}

// String to Convert(for JP multi byte String)
function strconv($myStr,$output)
{ 
	if ($output!="e"){ 
		return mb_convert_encoding($myStr ,"UTF-8","EUC-JP");   //右から左へ変換 
	}else{ 
		return mb_convert_encoding($myStr ,"EUC-JP","UTF-8");   //右から左へ変換 
	} 
} 


?> 
