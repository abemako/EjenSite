<?php
/****************************************************************************************************/
/* <<処理名>>                                                                                       */
/* SQL作成支援クラス                                                                                */
/* <<処理概要>>                                                                                     */
/* SQLの作成とパラメタ置き換えを支援する。                                                          */
/* <<更新履歴>>                                                                                     */
/* 新規作成	v1.0	2012年10月31日	阿部　誠                                                        */
/****************************************************************************************************/
require_once('./setup.php');

/* 処理開始ログ出力 */
$LogObj->log('>>> clsEditSql.php Started --------------------', PEAR_LOG_INFO );
/* クラス定義 */
class clsEditSql {

	function clsEditSql()
	{
		global $LogObj;
		$LogObj->log(' clsEditSql コンストラクタ', PEAR_LOG_DEBUG );
		$this->blnMultiLine = true;
		$this->blnCutSpace = true;
		$this->strVarSymbol = "?";
		$this->blnOmitLines = true;
		$this->strRepSql = "";
		$this->sqlbuff="";
		$this->TMP="";
		$this->Parms=array();
	}
	
	function AddStr( $buff, $tmp = false )
	{
		global $LogObj;
		$LogObj->log(' AddStr:'.$buff." tmp:".$tmp, PEAR_LOG_DEBUG );
		if ( $tmp ) {
			$this->TMP .= $buff."\n";
		} else {
			$this->sqlbuff .= $buff."\n";
		}
	}

	
	public function ClearSql()
	{
		global $LogObj;
		$LogObj->log(' ClearSql', PEAR_LOG_DEBUG );
		$this->sqlbuff="";
	}
	public function ClearTmp()
	{
		global $LogObj;
		$LogObj->log(' ClearTmp', PEAR_LOG_DEBUG );
		$this->TMP="";
	}
	public function ClearParm()
	{
		global $LogObj;
		$LogObj->log(' Clear', PEAR_LOG_DEBUG );
		$this->Parms=array();
	}

	// SQL、パラメタ、ワーク領域のクリア
	public function ClearAll()
	{
		global $LogObj;
		$LogObj->log(' ClearAll', PEAR_LOG_DEBUG );
		$this->strRepSql = "";
		$this->ClearSql();
		$this->ClearParm();
		$this->ClearTmp();
	}
	
	//パラメータのアイテム設定
	public function Parm( $strKey, $strValue )
	{
		global $LogObj;
		$LogObj->log(' Parm strkey:'.$strKey.' strValue:'.$strValue, PEAR_LOG_DEBUG );
		$this->Parms[$strKey]=$this->quote_smart($strValue);
	}

	public function getSQL()
	{
		global $LogObj;
		$LogObj->log(' getSQL', PEAR_LOG_DEBUG );
		$this->CopySQL();
		if ( $this->blnCutSpace ) {
			$this->CutSpace();
		}
		$this->ReplaceParms();
		if ( $this->blnOmitLines ) {
			$this->OmitLines();
		}
		if ( $this->blnMultiLine == false ) {
			$this->CutCrLf();
		}
		return $this->strRepSql;
	}
	
	function CopySQL()
	{
		global $LogObj;
		$LogObj->log(' CopySQL', PEAR_LOG_DEBUG );
		$this->strRepSql = $this->sqlbuff;
	}

	function CutSpace()
	{
		global $LogObj;
		$LogObj->log(' CutSpace', PEAR_LOG_DEBUG );
		$this->strRepSql = str_replace( "  ", " ", $this->strRepSql );	
	}

	function OmitLines()
	{
		global $LogObj;
		$LogObj->log(' OmitLines', PEAR_LOG_DEBUG );
		$buff="";
		$vntArray = split( "\n", $this->strRepSql );
		foreach( $vntArray as $line ) {
			if ( strpos( $line, $this->strVarSymbol ) == false ) {
				$buff .= $line . "\n";
			}
		}
		$this->strRepSql = $buff;
		
	}

	function ReplaceParms()
	{
		global $LogObj;
		$LogObj->log(' ReplaceParms', PEAR_LOG_DEBUG );
		foreach( $this->Parms as $key => $value ) {
			$s1 = $this->strVarSymbol.$key.$this->strVarSymbol;
			$this->strRepSql = str_replace( $s1, $value, $this->strRepSql );
		}
	}
	
	function CutCrLf()
	{
		global $LogObj;
		$LogObj->log(' CutCrLf', PEAR_LOG_DEBUG );
		if ( $this->blnCutSpace ) {
			$this->strRepSql = str_replace( " \n", "\n", $this->strRepSql );
			$this->strRepSql = str_replace( "\n ", "\n", $this->strRepSql );
		}
		$this->strRepSql = str_replace( "\n", " ", $this->strRepSql );
	}

	// 安全性を確保するために変数をクオートする
	function quote_smart($value)
	{
	    // Stripslashes
	    if (get_magic_quotes_gpc()) {
	        $value = stripslashes($value);
	    }
	    // 数値以外をクオートする
	    if (!is_numeric($value)) {
	        $value = "'" . mysql_real_escape_string($value) . "'";
	    }
	    return $value;
	}

}
/* 処理終了ログ */
$LogObj->log("<<< clsEditSql.php Ended --------------------", PEAR_LOG_INFO );
?> 