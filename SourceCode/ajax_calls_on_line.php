<?php
  require_once('json/json.php');
  require_once('const_.php');
  require_once('lib/mylib.php');
  require_once('Users.php');

  class CCalls_on_line{
#исходящие вызовы: ['num'] - номер вызова, ['to_login'] - кому отправлен, ['reglament'] - регламент,
#                  ['rating'] - рейтинг, ['our_color'] - ваш цвет, ['time'] - время,
#                  ['comment'] - комментарий
    public $out_calls_ =array();
#персональные вызовы: ['num'] - номер вызова, ['from_login'] - кто отправил, ['reglament'] - регламент,
#                     ['rating'] - рейтинг, ['our_color'] - ваш цвет, ['time'] - время,
#                     ['comment'] - комментарий
    public $person_calls_ =array();
#общие вызовы: ['num'] - номер вызова, ['from_login'] - кто отправил, ['reglament'] - регламент,
#              ['rating'] - рейтинг, ['our_color'] - ваш цвет, ['time'] - время,
#              ['comment'] - комментарий
    public $calls_ =array();
    public $count_pages =0; #количество страниц с общими вызовами
    public $current_page =0; #переданная страница с общими вызовами
#начавшиеся партии: ['num'] - номер партии, ['login_white'] - логин игрока белыми
#                   ['login_black'] - логин игрока черными, ['reglament'] - регламент
    public $games_ =array();
  }#CCalls_on_line

  class CGamers_on_line{
    public $new_gamers =''; #новые игроки
    public $count_pages =0; #количество страниц
    public $current_page =0; #переданная страница
    public $gamers_ =array(); #логины игроков, находящихся на сайте
    public $time_; #слепок времени на момент которого определяются игроки, находящиееся на сайте
  }# CGamers_on_line

 class CReturn_{
    public $error_ =''; #сообщение об ошибке, если ='' - запрос выполнен успешно
    public $object_=''; #в зависимости от запроса CGamers_on_line,..
    public $game_start_ =0; #начало партии
  }#CReturn

  class CListGamers_on_line extends CPartOfQuery_{
     public $records_ =array();
     public $time_; #слепок времени для определения игроков находящихся на сайте;

     protected function str_select_for_countPage(){
          $result_ ='select count(*) as count_'.
                    ' from TGamers_ where (not last_connect_ is null) and (last_connect_ >='.$this->time_.')';
          return $result_;
     } #str_select_for_countPage

     protected function str_select_for_getRecords(){
          $result_ ='select login_ '.
                    ' from TGamers_ where (not last_connect_ is null) and (last_connect_ >='.$this->time_.')';
          return $result_;
     }#str_select_for_countPage

     public function get_records($page_){
          try{
              if (!$this->getRecords(false,$page_,array('login_')))
                  throw new Exception();
              for($i=0; $i<count($this->listRecords); $i++){
                  $this->records_[$i]  =iconv("windows-1251","UTF-8",convert_cyr_string($this->listRecords[$i]['login_'],'d','w'));
              } #for
          }catch(Exception $e){
#            throw new Exception(mysql_error());
             throw new Exception('При чтении информации об игроках произошла ошибка.');

          }
     }#get_records
  }#CListGamers_on_line

  class CListCalls_on_line extends CPartOfQuery_{
     public $records_ =array();

     protected function str_select_for_countPage(){
          $result_ ='select count(*) as count_'.
                         ' from TCallsToGame_ A'.
                         ' where (A.id_gamerMakeCall_  <> '.$_SESSION[SESSION_ID_].') and (A.id_gamer_ is null) and (A.class_ = \'B\')';
          return $result_;
     } #str_select_for_countPage

     protected function str_select_for_getRecords(){
          $result_ ='select A.id_,A.reglament_,A.gameIsRating_,A.timeMake_,adddate(A.timeMake_,interval A.callEnd_ minute) as timeEnd_,'.
                          '          A.gamerMakeCallIsWhite_,A.comment_,B.login_,B.ratingB_'.
                          ' from TCallsToGame_ A left join TGamers_ B on A.id_gamerMakeCall_  = B.id_'.
                          ' where (A.id_gamerMakeCall_  <> '.$_SESSION[SESSION_ID_].') and (A.id_gamer_ is null) and (A.class_ = \'B\')'.
                          ' order by A.id_';
          return $result_;
     }#str_select_for_countPage

     public function get_records($page_){
          global $reglaments_;

          try{
              if (!$this->getRecords(false,$page_,array('id_','reglament_','gameIsRating_','timeMake_',
                                                        'timeEnd_','gamerMakeCallIsWhite_','comment_','login_',
                                                        'ratingB_')))
                  throw new Exception();
              for($i=0; $i<count($this->listRecords); $i++){
                  $this->records_[$i]['num'] =$this->listRecords[$i]['id_'];
                  $this->records_[$i]['from_login'] =iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($this->listRecords[$i]['login_'],'d','w')));
                  $this->records_[$i]['reglament'] = iconv("windows-1251","UTF-8",$reglaments_['reglament'.$this->listRecords[$i]['reglament_'].'_']);
                  $this->records_[$i]['rating'] = iconv("windows-1251","UTF-8",$this->listRecords[$i]['gameIsRating_'] == 'Y' ? 'да' : 'нет');
                  $this->records_[$i]['our_color'] = iconv("windows-1251","UTF-8",$this->listRecords[$i]['gamerMakeCallIsWhite_'] != 'Y' ? 'белый' : 'черный');
                  $this->records_[$i]['comment'] =iconv("windows-1251","UTF-8",convert_cyr_string($this->listRecords[$i]['comment_'],'d','w'));
                  $this->records_[$i]['time'] = iconv("windows-1251","UTF-8",convert_cyr_string($this->listRecords[$i]['timeMake_'],'d','w').' - '.convert_cyr_string($this->listRecords[$i]['timeEnd_'],'d','w'));
                  $this->records_[$i]['ratingB_'] = iconv("windows-1251","UTF-8",$this->listRecords[$i]['ratingB_']);
              } #for
          }catch(Exception $e){
#            throw new Exception(mysql_error());
             throw new Exception('При чтении информации о вызовах произошла ошибка.');
          }
     }#get_records
  }#CListCalls_on_line

  //инициализация сессии
  session_name(NAME_SESSION_);
  session_start();

  header('Content-Type: text/plain; charset=UTF-8');
  header('Cache-Control: no-cache');

  $oJSON =new Services_JSON();
  $connect_  =false;
  $transact_  =false;
  $cursor_     =false;

  $r =new CReturn_;
  try{
    if (isset($_SESSION[SESSION_ID_])){
      if (const_::SetConnect_()) $connect_ =true; else throw new Exception('При подключении к базе данных возникла ошибка');
      if (const_::StartTransaction_()) $transact_ =true; else throw new Exception('При запуске транзакции возникла ошибка');

      $p_=$oJSON->decode($HTTP_RAW_POST_DATA);

      switch ($p_->type_){
        case 1: $r->object_ =new CGamers_on_line;
                $r->object_->time_ = time() - TIME_CHECK_ON_SITE;
                $l =new CListGamers_on_line(const_::$connect_);
                $l->time_ =$r->object_->time_;
                $l->get_records($p_->page_);
                $r->object_->gamers_ =$l->records_;
                $r->object_->current_page =$l->page_;
                $r->object_->count_pages =$l->cCountPages;
                $r->object_->new_gamers =get_last_enter_gamers();
                break;
        case 2: if ($p_->del_call !=0)
                  CUsers_::decline_call($p_->del_call);
                if ($p_->accept_call !=0){
                  CUsers_::accept_call($p_->accept_call);
                }
                del_calls();
                $r->object_ =new CCalls_on_line;
                $r->object_->out_calls_ =get_out_calls();
                $r->object_->person_calls_ =get_personal_calls();
                $l =new CListCalls_on_line(const_::$connect_);
                $l->get_records($p_->page_);
                $r->object_->calls_ =$l->records_;
                $r->object_->count_pages =$l->cCountPages;
                $r->object_->current_page =$l->page_;
                $r->object_->games_ =get_games();
                break;
        default:
          throw new Exception('Тип запроса указан неверно.');
      }#switch

      if (!const_::Commit_()) throw new Exception();
      $transact_ =false;
      const_::Disconnect_(); $connect_ =false;
      echo($oJSON->encode($r));

    } else throw new Exception('Не выполнен вход на сайт');

  }catch(Exception $e){
     if ($cursor_) mysql_free_result($cursor_);
     if ($transact_) const_::Rollback_();
     if ($connect_) const_::Disconnect_();
     $r->error_ =(($e->getMessage() !='') ? $e->getMessage() : 'При обработке запроса возникла ошибка');
     $r->error_ =iconv("windows-1251","UTF-8",$r->error_);
     echo($oJSON->encode($r));
  }

 function get_last_enter_gamers(){
    global $cursor_;

    $result_='';
    $s ='select login_ '.
        ' from TGamers_ where (date_sub(CURRENT_TIMESTAMP(), interval 120 second) <=last_visit_)';
    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
    while ($row_ =mysql_fetch_array($cursor_)){
      if ($result_ != '')  $result_ .="\n";
      $result_ .=iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($row_['login_'],'d','w')));
    }#while

    mysql_free_result($cursor_); $cursor_ =false;
    return $result_;
 }#get_last_enter_gamers

 function get_out_calls(){
    global $cursor_;
    global $reglaments_;

    $result_ = array();
    $s ='select A.id_,A.reglament_,A.gameIsRating_,A.timeMake_,adddate(A.timeMake_,interval A.callEnd_ minute) as timeEnd_,'.
           '       A.gamerMakeCallIsWhite_,A.comment_,B.login_'.
           ' from TCallsToGame_ A left join TGamers_ B on A.id_gamer_ = B.id_'.
           ' where (A.id_gamerMakeCall_ ='.$_SESSION[SESSION_ID_].') and (A.class_ = \'B\')'.
           ' order by A.id_';
    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
    $i =0;
    while ($row_ =mysql_fetch_array($cursor_)){
      $result_[$i]['num'] =$row_['id_'];
      $result_[$i]['to_login'] =iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($row_['login_'],'d','w')));
      $result_[$i]['reglament'] = iconv("windows-1251","UTF-8",$reglaments_['reglament'.$row_['reglament_'].'_']);
      $result_[$i]['rating'] = iconv("windows-1251","UTF-8",$row_['gameIsRating_'] == 'Y' ? 'да' : 'нет');
      $result_[$i]['our_color'] = iconv("windows-1251","UTF-8",$row_['gamerMakeCallIsWhite_'] == 'Y' ? 'белый' : 'черный');
      $result_[$i]['comment'] =iconv("windows-1251","UTF-8",convert_cyr_string($row_['comment_'],'d','w'));
      $result_[$i]['time'] = iconv("windows-1251","UTF-8",convert_cyr_string($row_['timeMake_'],'d','w').' - '.convert_cyr_string($row_['timeEnd_'],'d','w'));
      $i++;
    }#while

    mysql_free_result($cursor_); $cursor_ =false;
    return $result_;
 }#get_out_calls

 function get_personal_calls(){
    global $cursor_;
    global $reglaments_;

    $result_ = array();
    $s ='select A.id_,A.reglament_,A.gameIsRating_,A.timeMake_,adddate(A.timeMake_,interval A.callEnd_ minute) as timeEnd_,'.
           '       A.gamerMakeCallIsWhite_,A.comment_,B.login_,B.ratingB_'.
           ' from TCallsToGame_ A left join TGamers_ B on A.id_gamerMakeCall_  = B.id_'.
           ' where (A.id_gamer_ ='.$_SESSION[SESSION_ID_].') and (A.class_ = \'B\')'.
           ' order by A.id_';
    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
    $i =0;
    while ($row_ =mysql_fetch_array($cursor_)){
      $result_[$i]['num'] =$row_['id_'];
      $result_[$i]['from_login'] =iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($row_['login_'],'d','w')));
      $result_[$i]['reglament'] = iconv("windows-1251","UTF-8",$reglaments_['reglament'.$row_['reglament_'].'_']);
      $result_[$i]['rating'] = iconv("windows-1251","UTF-8",$row_['gameIsRating_'] == 'Y' ? 'да' : 'нет');
      $result_[$i]['our_color'] = iconv("windows-1251","UTF-8",$row_['gamerMakeCallIsWhite_'] != 'Y' ? 'белый' : 'черный');
      $result_[$i]['comment'] =iconv("windows-1251","UTF-8",convert_cyr_string($row_['comment_'],'d','w'));
      $result_[$i]['time'] = iconv("windows-1251","UTF-8",convert_cyr_string($row_['timeMake_'],'d','w').' - '.convert_cyr_string($row_['timeEnd_'],'d','w'));
      $result_[$i]['ratingB_'] = iconv("windows-1251","UTF-8",$row_['ratingB_']);
      $i++;
    }#while

    mysql_free_result($cursor_); $cursor_ =false;
    return $result_;
 }#get_personal_calls

 function del_calls(){
    $s ='delete from TCallsToGame_ where (NOW() > adddate(timeMake_,interval callEnd_ minute)) and (class_ = \'B\')';
    if (!mysql_query($s,const_::$connect_)) throw new Exception('При проверки времени завершения вызова произошла ошибка.');
 }#del_calls

 function get_games(){
    global $cursor_;
    global $reglaments_;

    $result_ = array();
    $s ='select A.id_,B.login_ as login_white,C.login_ as login_black,A.reglament_'.
           ' from TGames_ A left join TGamers_ B on A.idWGamer_ = B.id_'.
                          ' left join TGamers_ C on A.idBGamer_ = C.id_'.
           ' where ((A.idWGamer_ ='.$_SESSION[SESSION_ID_].') or (A.idBGamer_ ='.$_SESSION[SESSION_ID_].')) and'.
                 ' (A.class_ = \'B\') and (A.result_ is null) and'.
                 ' (not exists (select * from TMoves_ where idGame_ = A.id_) or'.
                 '  ((A.idBGamer_ ='.$_SESSION[SESSION_ID_].') and'.
                 '   not exists (select * from TMoves_ where (idGame_ = A.id_) and (num_=1) and (BMoveCell1_ is null))'.
                 '  )'.
                 ' )'.
           ' order by A.id_';
    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
    $i =0;
    while ($row_ =mysql_fetch_array($cursor_)){
      $result_[$i]['num'] =$row_['id_'];
      $result_[$i]['login_white'] =iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($row_['login_white'],'d','w')));
      $result_[$i]['login_black'] =iconv("windows-1251","UTF-8",htmlspecialchars(convert_cyr_string($row_['login_black'],'d','w')));
      $result_[$i]['reglament'] = iconv("windows-1251","UTF-8",$reglaments_['reglament'.$row_['reglament_'].'_']);
      $i++;
    }#while
    mysql_free_result($cursor_); $cursor_ =false;
    return $result_;
 }#get_games

?>
