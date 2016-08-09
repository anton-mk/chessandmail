<?php
	require_once('json/json.php');
	require_once('const_.php');
	require_once('Users.php');

//инициализация сессии
	session_name(NAME_SESSION_);
	session_start();

	header('Content-Type: text/plain; charset=UTF-8');
	header('Cache-Control: no-cache');

	$oJSON =new Services_JSON();
	$connect_  =false;
	$transact_ =false;
	$cursor_   =false;
	try{
		if (isset($_SESSION[SESSION_ID_])){
			if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
			if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

			$id_from=$_SESSION[SESSION_ID_];

			$p_=$oJSON->decode($HTTP_RAW_POST_DATA);
			if(is_array($p_) && isset($p_[0])) hints_();
				else throw new Exception();
		}
	}catch(Exception $e){
		if ($cursor_) mysql_free_result($cursor_);
		if ($transact_) const_::Rollback_();
		if ($connect_) const_::Disconnect_();
		$s ='error';
		echo($oJSON->encode($s));
	}

	function hints_(){
		global $oJSON;
		global $connect_;
		global $transact_;
		global $cursor_;
		global $p_;

		$result_ = array();
		$p_[0] =iconv("UTF-8","windows-1251",$p_[0]);
		$count_hints_ =0;
		if(isset($p_[1]) && is_int($p_[1])) $count_hints_ =$p_[1];
		if ($p_[1] > 0){
			$s='select login_'.
			   ' from TGamers_'.
			   (($p_[0] !=='') ? ' where login_ like \''.mysql_escape_string($p_[0]).'%\'':'').
			   ' order by login_'.
			   ' limit 0,'.$count_hints_;
			$s =convert_cyr_string($s,'w','d');
			$cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
			while ($row_ =mysql_fetch_array($cursor_))
				$result_[]=iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($row_['login_'],'d','w')));
		} #while
		mysql_free_result($cursor_); $cursor_ =false;
		if (!const_::Commit_()) throw new Exception();
		$transact_ =false;
		echo($oJSON->encode($result_));
		const_::Disconnect_();
	}#hints_
?>