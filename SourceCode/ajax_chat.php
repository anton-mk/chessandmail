<?php
	require_once('json/json.php');
	require_once('const_.php');
	require_once('Users.php');

//инициализация сессии
	session_name(NAME_SESSION_);
	session_start();

	header('Content-Type: text/plain; charset=UTF-8');
	header('Cache-Control: no-cache');

	class out_{
		public $countMessage;
		public $messages_;
		public $result_;

		function __construct(){
			$this->countMessage =0;
			$this->messages_ =array();
			$this->result_ ='';
		} #__construct
	} #out_

	class rec_message_{
		public $id_;
		public $message_;
		public $timeMake_;
		public $loginFrom_;
		public $loginTo_;

		function __construct(){
			$this->id_ =0;
			$this->message_ ='';
			$this->timeMake_ ='';
			$this->loginFrom_ ='';
			$this->loginTo_ ='';
		} #__construct
	} #rec_message_

	$oJSON =new Services_JSON();
	$connect_  =false;
	$transact_ =false;
	$cursor_   =false;
	try{
		if (isset($_SESSION[SESSION_ID_])){
			if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
			if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

			$id_from=$_SESSION[SESSION_ID_];

			$out_data =new out_;

			$p_=$oJSON->decode($HTTP_RAW_POST_DATA);
			if(is_array($p_) && isset($p_[0])) hints_();
				else chat_();
		}
	}catch(Exception $e){
		if ($cursor_) mysql_free_result($cursor_);
		if ($transact_) const_::Rollback_();
		if ($connect_) const_::Disconnect_();
		$out_data_error =new out_;
		$out_data_error->countMessage =0;
		$out_data_error->result_=(($e->getMessage() !='') ? $e->getMessage() : 'При обработке запроса возникла ошибка');
		$out_data_error->result_=iconv("windows-1251","UTF-8",$out_data_error->result_);
		echo($oJSON->encode($out_data_error));
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

	function chat_(){
		global $oJSON;
		global $connect_;
		global $transact_;
		global $cursor_;
		global $id_from;
		global $out_data;
		global $p_;

		if (isset($p_->message_add)){
#Добавляю сообщение
			if (trim($p_->message_add) ==='') throw new Exception('Сообщение не указано.');
			$id_to=CUsers_::Read_id_(iconv("UTF-8","windows-1251",$p_->login_to)); if ($id_to===0) throw new Exception('Логин получателя указан неверно.');
			$s ='insert into TPersonalMessage_(message_,timeMake_,from_id_gamer_,to_id_gamer_)'.
				' values(\''.mysql_escape_string(iconv("UTF-8","windows-1251",$p_->message_add)).'\',NOW(),'.$id_from.','.$id_to.')';
			$s =convert_cyr_string($s,'w','d');
			if (!mysql_query($s,const_::$connect_)) throw new Exception();
			$p_->rec_start =0;
		}
#Считываю id_ последней записи прочитанной записи
        $s ='select id_last_read_person_message from TGamers_ where id_ ='. $id_from;
        $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
        $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
        $id_last_read_person_message =$row_['id_last_read_person_message'];
        mysql_free_result($cursor_); $cursor_ =false;
#Подсчитываю количество записей
		$s ='select count(*) as count_ '.
			' from TPersonalMessage_ '.
			' where (from_id_gamer_ ='.$id_from.') or (to_id_gamer_ ='.$id_from.')';
		$cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
		$row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
		$out_data->countMessage =$row_['count_'];
		mysql_free_result($cursor_); $cursor_ =false;
#Запрашиваю требуемые записи
		$s ='select A.id_,A.message_,A.timeMake_,B.login_ as login_from,C.login_ as login_to,'.
			'       A.from_id_gamer_,A.to_id_gamer_'.
			' from TPersonalMessage_ A, TGamers_ B, TGamers_ C'.
			' where (A.from_id_gamer_ =B.id_) and (A.to_id_gamer_ =C.id_) and'.
			'       ((A.from_id_gamer_ ='.$id_from.') or (A.to_id_gamer_ ='.$id_from.'))'.
			' order by A.id_ desc'.
			' limit '.$p_->rec_start.', '.$p_->rec_count;
		$cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
		$a=0; $s=''; $id_person_message =null;
		while ($row_ =mysql_fetch_array($cursor_)){			if (is_null($id_person_message) || ($id_person_message < $row_['id_']))              $id_person_message =$row_['id_'];
			$out_data->messages_[$a] = new rec_message_;
			$out_data->messages_[$a]->id_ =$row_['id_'];
			$out_data->messages_[$a]->message_ =iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($row_['message_'],'d','w')));
			$out_data->messages_[$a]->timeMake_ =htmlspecialchars(convert_cyr_string($row_['timeMake_'],'d','w'));
			if ($id_from !=$row_['from_id_gamer_'])
					$out_data->messages_[$a]->loginFrom_ ='<A href="AboutGamer.php?login='.urlencode(convert_cyr_string($row_['login_from'],'d','w')).'">'.htmlspecialchars(iconv("windows-1251","UTF-8",convert_cyr_string($row_['login_from'],'d','w'))).'</A>';
				else
					$out_data->messages_[$a]->loginFrom_ =iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($row_['login_from'],'d','w')));
			if ($id_from !=$row_['to_id_gamer_'])
					$out_data->messages_[$a]->loginTo_ ='<A href="AboutGamer.php?login='.urlencode(convert_cyr_string($row_['login_to'],'d','w')).'">'.htmlspecialchars(iconv("windows-1251","UTF-8",convert_cyr_string($row_['login_to'],'d','w'))).'</A>';
				else
					$out_data->messages_[$a]->loginTo_ =iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($row_['login_to'],'d','w')));
			$a++;
		} #while
		mysql_free_result($cursor_); $cursor_ =false;
#Записываю последнюю прочитанную запись
        if (!is_null($id_person_message) &&
            (is_null($id_last_read_person_message) || ($id_person_message > $id_last_read_person_message))){          $s ='update TGamers_ set id_last_read_person_message ='.$id_person_message.' where id_='.$id_from;
          if (!mysql_query($s,const_::$connect_)) throw new Exception();        }
#Завершаю транзакцию и отправляю данные
		if (isset($p_->message_add))
			$out_data->result_='OK';
		if (!const_::Commit_()) throw new Exception();
		$transact_ =false;
		echo($oJSON->encode($out_data));
		const_::Disconnect_();
	}#chat_

?>