<?php
/****************************************************************************************************/
/* <<処理名>>                                                                                       */
/* トークン制御クラス Token control class                                                           */
/* <<処理概要>>                                                                                     */
/* トークンの生成とセッション毎のチェックを行い、不正なリクエストを排除する。                       */
/* check every generation and session of the token and remove an unjust request                     */
/* <<更新履歴>>                                                                                     */
/* 新規作成	v1.0	2012年10月31日	阿部　誠                                                        */
/****************************************************************************************************/
require_once('./setup.php');
/* 処理開始ログ出力 */
$LogObj->log(">>> clsToken.php Started --------------------", PEAR_LOG_INFO );

/* クラス定義 */
class clsToken {

	/* コンストラクタ */
	function clsToken()
	{
		global $LogObj;
		/* セッション開始 */
		$LogObj->log(" セッション開始", PEAR_LOG_INFO );
		session_start();
		$this->Token = "";
	}

	/* セッションのクローズ処理 */
	function SessionClose()
	{
		$_SESSION = array();	//セッション変数初期化
		session_destroy();		//セッション破棄
	}

	/* トークン生成処理 */
	function GetToken()
	{
		global $LogObj;
		//トークン生成
		$this->Token = sha1(uniqid(mt_rand(), true));
		$LogObj->log(" GetToken トークン生成:[".$this->Token."]", PEAR_LOG_INFO );
		// トークンをセッションに追加する
		$_SESSION['token'][] = $this->Token;
		$LogObj->log(" GetToken セッションにトークン保存", PEAR_LOG_DEBUG );
		return $this->Token;
	}

	/* トークン文字列取得処理 */
	function ShowToken()
	{
		return $this->Token;
	}

	/* トークンチェック処理 */
	function ChkToken()
	{
		global $LogObj;
		if ( isset($_POST['token'] ) == false ) {
			$LogObj->log(" ChkToken POST内に token なし", PEAR_LOG_DEBUG );
			return false;
		}
		if ( isset($_SESSION['token']) == false ) {
			$LogObj->log(" ChkToken SESSION内に token なし", PEAR_LOG_DEBUG );
			return false;		
		}
		
		// 送信されたトークンがセッションのトークン配列の中にあるか調べる
		$key = array_search($_POST['token'], $_SESSION['token']);
		
		if ($key !== false) {
		    // 正常な POST
		    unset($_SESSION['token'][$key]); // 使用済みトークンを破棄
		    $LogObj->log(" ChkToken トークンOK", PEAR_LOG_DEBUG );
			return true;
		} else {
			$LogObj->log(" ChkToken セッション中にに同一トークンなし", PEAR_LOG_DEBUG );
			return false;
		}
	}

}

/* 処理終了ログ */
$LogObj->log("<<< clsToken.php Ended --------------------", PEAR_LOG_INFO );
?> 