<?php
  require_once('json/json.php');
  require_once('const_.php');

  class cl_comment{    public $id_;
    public $comment_;
    public $isWhite_;
  }#cl_comment

  class cl_return{
    public $text_error_ =''; #сообщение об ошибке, если ='' - запрос выполнен успешно
    public $comments_ =array(); #[]['id_'], []['comment_'], []['isWhite_']
  }#cl_return

//инициализация сессии
  session_name(NAME_SESSION_);
  session_start();

  header('Content-Type: text/plain; charset=UTF-8');
  header('Cache-Control: no-cache');

  $oJSON =new Services_JSON();
  $connect_  =false;
  $transact_ =false;
  try{
    if (isset($_SESSION[SESSION_ID_])){
      if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
      if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

      $p_=$oJSON->decode($HTTP_RAW_POST_DATA);
      $w_id_ =0;
      $b_id_ =0;
      getIDs($p_->id_game_,$w_id_,$b_id_);
      if (($w_id_ == $_SESSION[SESSION_ID_]) || ($b_id_ == $_SESSION[SESSION_ID_])){        $o_return =new cl_return;
        ReadComments();
      }
      const_::Commit_();
      const_::Disconnect_();
      echo($oJSON->encode($o_return));
    }
  }catch(Exception $e){
     if ($transact_) const_::Rollback_();
     if ($connect_) const_::Disconnect_();
     $r =new cl_return;
     $r->text_error_ =(($e->getMessage() !='') ? $e->getMessage() : 'При обработке запроса возникла ошибка');
     $r->text_error_ =iconv("windows-1251","UTF-8",$r->text_error_);
     echo($oJSON->encode($r));
	}

  function ReadComments(){    global $p_;
    global $o_return;
    $cursor_ =false;
    try{
       $s ='select id_,comment_,isWhite_ from TCommentsGame_'.
           ' where (id_game_='.$p_->id_game_.') and (id_ > '.$p_->last_id_.') order by id_';
       $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении комментариев произошла ошибка.');
       $i=0;
       while ($row_ =mysql_fetch_array($cursor_)){          $o_return->comments_[$i] =new cl_comment;
          $o_return->comments_[$i]->id_ =$row_['id_'];          $o_return->comments_[$i]->comment_ =trim(iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($row_['comment_'],'d','w'))));
          $o_return->comments_[$i++]->isWhite_ =($row_['isWhite_'] == 'Y');
       } //while
       mysql_free_result($cursor_);
    }catch (Exception $e){
       if ($cursor_) mysql_free_result($cursor_);
       throw new Exception($e->getMessage());
    }
  }#ReadComments

  function getIDs($id_,&$w_id_,&$b_id_){
    $cursor_ =false;
    try{
       $s ='select A.idWGamer_,A.idBGamer_ from TGames_ A where (A.id_='.$id_.')';
       $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
       $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
       $w_id_ =$row_['idWGamer_'];
       $b_id_ =$row_['idBGamer_'];
       mysql_free_result($cursor_);
    }catch(Exception $e){
       if ($cursor_) mysql_free_result($cursor_);
       throw new Exception('При чтении информации о партии произошла ошибка.');
    }#try
  }#getLogins
?>