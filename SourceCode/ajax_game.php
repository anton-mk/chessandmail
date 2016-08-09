<?php
  require_once('json/json.php');
  require_once('const_.php');
  require_once('Users.php');
  require_once('Games.php');
  require_once('rule_game_.php');

  class cl_move_{
    public $num_=0;
    public $cell1_white_ ='';
    public $cell2_white_ ='';
    public $cell1_black_ ='';
    public $cell2_black_ ='';
    public $WPiece_        = '';
    public $w_isCheck_     =false;
    public $w_isCheckMate_ =false;
    public $BPiece_        = '';
    public $b_isCheck_     =false;
    public $b_isCheckMate_ =false;
  }#cl_moves_

  class cl_status_game{
    public $time_white_ ='';
    public $time_black_ ='';
    public $result_ ='';
    public $may_move =false;
    public $moves_= array();
    public $color_ =''; #w - пользователь, запросивший страницу, играет белыми; b - черными; '' - партию не играет
    public $offerDrawn =''; #информация о предложении ничьей
    public $in_variants_ =array();
  }#status_game

  class cl_return{
    public $text_error_ =''; #сообщение об ошибке, если ='' - запрос выполнен успешно
    public $o_status_game;

    function __construct(){
      $this->o_status_game =new cl_status_game;
    }#__construct
  }#cl_return

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
    if (isset($_SESSION[SESSION_ID_])){      const_::$e_mails_ =array();
      if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
      if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

      $p_=$oJSON->decode($HTTP_RAW_POST_DATA);

      switch ($p_->type_){
        case 1: type_1($_SESSION[SESSION_ID_]); break;
        case 2: type_2($_SESSION[SESSION_ID_]); break;
        case 3: type_3($_SESSION[SESSION_ID_]); break;
        default:
          throw new Exception('Тип запроса указан неверно.');
      }#switch

      const_::send_mails();
    }
  }catch(Exception $e){
     if ($cursor_) mysql_free_result($cursor_);
     if ($transact_) const_::Rollback_();
     if ($connect_) const_::Disconnect_();
     $r =new cl_return;
     $r->text_error_ =(($e->getMessage() !='') ? $e->getMessage() : 'При обработке запроса возникла ошибка');
     $r->text_error_ =iconv("windows-1251","UTF-8",$r->text_error_);
     echo($oJSON->encode($r));
	}

#функция возвращает состояние партии
  function type_1($id_user_){
    global $p_;
    global $oJSON;
    global $connect_;
    global $transact_;
    global $cursor_;

    $result_ =new cl_return;

# Статус партии
    $s ='select idWGamer_,idBGamer_,clockWhite_,clockBlack_,beginMove_,isMoveWhite_,result_'.
        ' from TGames_'.
        ' where id_='.$p_->num_game_;
    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
    if (!($row_ =mysql_fetch_array($cursor_))) throw new Exception('Партия не найдена.');

    if ($row_['result_'] != ''){
      switch ($row_['result_']){
        case 0 : $result_->o_status_game->result_ ='0:1'; break;
        case 1 : $result_->o_status_game->result_ ='1:0'; break;
        case 2 : $result_->o_status_game->result_ ='1/2:1/2'; break;
        default :
          throw new Exception('Результат партии указан неверно.');
      }#switch
      $result_->o_status_game->time_white_  = iconv("windows-1251","UTF-8",clockToStr($row_['clockWhite_']));
      $result_->o_status_game->time_black_ = iconv("windows-1251","UTF-8",clockToStr($row_['clockBlack_']));
    }elseif ($row_['isMoveWhite_'] == 'Y'){
      $time_=$row_['clockWhite_'];
      if ($row_['beginMove_'] <> 0) $time_ -=time() - $row_['beginMove_'];
      if ($time_ <= 0){
        СGames_::EndGame($p_->num_game_,1,0);
        $result_->o_status_game->result_ ='0:1';
        $time_ =0;
      }
      $result_->o_status_game->time_white_  = iconv("windows-1251","UTF-8",clockToStr($time_));
      $result_->o_status_game->time_black_ = iconv("windows-1251","UTF-8",clockToStr($row_['clockBlack_']));
    }else{
      $time_=$row_['clockBlack_'];
      if ($row_['beginMove_'] <> 0) $time_ -=time() - $row_['beginMove_'];
      if ($time_ <= 0){
        СGames_::EndGame(const_::$connect_,$p_->num_game_,2,1);
        $result_->o_status_game->result_ ='1:0';
        $time_ =0;
      }
      $result_->o_status_game->time_white_  = iconv("windows-1251","UTF-8",clockToStr($row_['clockWhite_']));
      $result_->o_status_game->time_black_ = iconv("windows-1251","UTF-8",clockToStr($time_));
    }

    if ($result_->o_status_game->result_ === ''){      $result_->o_status_game->offerDrawn =CGames_::getInfoDrawn($p_->num_game_);
      if ((($row_['isMoveWhite_'] == 'Y') && ($row_['idWGamer_'] ==$id_user_)) ||
          (($row_['isMoveWhite_'] == 'N') && ($row_['idBGamer_'] ==$id_user_))){        $result_->o_status_game->may_move =true;
        $inVariants =array();
        CGames_::get_input_variant($p_->num_game_,$result_->o_status_game->in_variants_);
      }
      if ($row_['idWGamer_'] ==$id_user_) $result_->o_status_game->color_ ='w';
       elseif ($row_['idBGamer_'] ==$id_user_) $result_->o_status_game->color_ ='b';
    }
#Ходы
    $s ='select num_,WMoveCell1_,WMoveCell2_,WPiece_,w_isCheck_,w_isCheckMate_,'.
        '            BMoveCell1_,BMoveCell2_,BPiece_,b_isCheck_,b_isCheckMate_'.
        ' from TMoves_'.
        ' where (idGame_='.$p_->num_game_.') and (num_ >='.$p_->num_last_move.')';
    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
    $i =0;
    while ($row_ =mysql_fetch_array($cursor_)){
      $result_->o_status_game->moves_[$i] = new cl_move_;
      $result_->o_status_game->moves_[$i]->num_ =$row_['num_'];
      $result_->o_status_game->moves_[$i]->cell1_white_ =$row_['WMoveCell1_'];
      $result_->o_status_game->moves_[$i]->cell2_white_ =$row_['WMoveCell2_'];
      $result_->o_status_game->moves_[$i]->WPiece_ =is_null($row_['WPiece_']) ? '' : $row_['WPiece_'];
      $result_->o_status_game->moves_[$i]->w_isCheck_ =!is_null($row_['w_isCheck_']);
      $result_->o_status_game->moves_[$i]->w_isCheckMate_ =!is_null($row_['w_isCheckMate_']);
      if (!is_null($row_['BMoveCell1_'])){
        $result_->o_status_game->moves_[$i]->cell1_black_ =$row_['BMoveCell1_'];
        $result_->o_status_game->moves_[$i]->cell2_black_ =$row_['BMoveCell2_'];
        $result_->o_status_game->moves_[$i]->BPiece_ =is_null($row_['BPiece_']) ? '' : $row_['BPiece_'];
        $result_->o_status_game->moves_[$i]->b_isCheck_ =!is_null($row_['b_isCheck_']);
        $result_->o_status_game->moves_[$i]->b_isCheckMate_ =!is_null($row_['b_isCheckMate_']);
      }
      $i++;
    }#while

    mysql_free_result($cursor_); $cursor_ =false;
#Завершаю транзакцию и отправляю данные
    if (!const_::Commit_()) throw new Exception();
    $transact_ =false;
    echo($oJSON->encode($result_));
    const_::Disconnect_(); $connect_ =false;
  }#type_1

#Функция проверяет возможность хода, в случае если ход возможен, запрашивается статус партии
  function type_2($id_user_){
    global $oJSON;
    global $connect_;
    global $transact_;
    global $cursor_;
    global $p_;

#Получаю id игроков
    $w_id_ =0; $b_id_ =0;
    CGames_::getIDs($p_->num_game_,$w_id_,$b_id_);
#Восстанавливаю последнюю позицию
    $g =new CRuleGame();
    $g->id_=$p_->num_game_;
    $g->lastPosition();
#Проверка
    if ((($w_id_ ==$id_user_) && !$g->lastMoveIsWhite_) ||
        (($b_id_ ==$id_user_) && $g->lastMoveIsWhite_))
        $result_flag=$g->checkMove($p_->cell_1,$p_->cell_2);
     else
        $result_flag=false;
#Возвращаю результат
    $result_ =new cl_return;
    if ($result_flag)
        $result_->text_error_ ='move_access';
    else
        $result_->text_error_ ='move_not_access';
#Завершаю транзакцию и отправляю данные
    if (!const_::Commit_()) throw new Exception();
    $transact_ =false;
    echo($oJSON->encode($result_));
    const_::Disconnect_(); $connect_ =false;
  }#type_2

#Функция делает ход
  function type_3($id_user){
/*--------------------------------------------------------------------------
                          Псевдокод
  --------------------------------------------------------------------------
  w_id =id игрока белыми
  b_id =id игрока черными
  Проверка отпуска w_id
  Проверка отпуска b_id

  g = последняя позиция

  if время партии истекло
    завершение партии
  .

  g_e =партия завершена
  if (!g_e and (((w_id =id_user) and последний ход совершили черные)) or
                      ((b_id =id_user) and последний ход совершили белые))) and
                     id_user не в отпуске)
    if номер_хода_указан верно and ход_указан_корректно and ход_возможен
       совершить ход
    .
  .
  вызов type_1
--------------------------------------------------------------------------
 */
    global $p_;

#Получаю id игроков
    $w_id_ =0; $b_id_ =0;
    CGames_::getIDs($p_->num_game_,$w_id_,$b_id_);
    $class_ =CGames_::getClass($p_->num_game_);
    $may_otpusk_ =(($class_ != 'B') && CGames_::getMayOtpusk($p_->num_game_));
    if ($may_otpusk_){
      CUsers_::Check_otpusk($w_id_);
      CUsers_::Check_otpusk($b_id_);
    }
#Восстанавливаю последнюю позицию
    $g =new CRuleGame();
    $g->id_=$p_->num_game_;
    $g->lastPosition();
#Проверка - время истекло
     $g_e=CGames_::endGameIfClockZero($p_->num_game_);

     if (!$g_e && ((($w_id_ ==$id_user) && !$g->lastMoveIsWhite_) ||
                   (($b_id_ ==$id_user) && $g->lastMoveIsWhite_)) && (!$may_otpusk_ || !CUsers_::Status_Otpusk($id_user)))
         if (($p_->num_last_move ==count($g->table_moves)) && $g->checkMove($p_->cell_1,$p_->cell_2)){
#Определяю фигуру, в которую превращается пешка
              if ((($g->board_[$p_->cell_1{0}][$p_->cell_1{1}] == 'wp') && ($p_->cell_2{1}==8)) ||
                   (($g->board_[$p_->cell_1{0}][$p_->cell_1{1}] == 'bp') && ($p_->cell_2{1}==1))){
                 if (($p_->piece_ !='q') && ($p_->piece_ !='r') && ($p_->piece_ !='b') && ($p_->piece_ !='n'))
                    throw new Exception('Фигура, в которую должна превратиться пешка, указанна неверно.');
              }else $p_->piece_='';
#Совершаю ход
              $time_ =time();
              if (!$g->lastMoveIsWhite_){
                  if ($may_otpusk_ && CUsers_::Status_Otpusk($b_id_)) $time_2 =0; else $time_2 =$time_;
                  $s ='insert into TMoves_(num_,WMoveCell1_,WMoveCell2_,WPiece_,idGame_)'.
                          ' values('.$g->num_.',\''.$p_->cell_1.'\',\''.$p_->cell_2.'\','.(($p_->piece_ != '') ? "'{$p_->piece_}'" : 'null').','.$p_->num_game_.')';
                  $d ='update TGames_ set'.
                         ' clockWhite_ =case when (reglament_=1) or (reglament_=2) or (reglament_=7) or (reglament_=8) or (reglament_=9) or (reglament_=10) then clockWhite_-('.$time_.' - beginMove_)'.
                         '                   when reglament_=3 then 60*60*24*3'.
                         '                   when reglament_=4 then if((clockWhite_-('.$time_.' - beginMove_)+60*60*24)>60*60*24*10,60*60*24*10,clockWhite_-('.$time_.' - beginMove_)+60*60*24)'.
                         '                   when reglament_=5 then if((clockWhite_-('.$time_.' - beginMove_)+60*60*24)>60*60*24*15,60*60*24*15,clockWhite_-('.$time_.' - beginMove_)+60*60*24)'.
                         '                   when reglament_=6 then if((clockWhite_-('.$time_.' - beginMove_)+60*60*24)>60*60*24*5,60*60*24*5,clockWhite_-('.$time_.' - beginMove_)+60*60*24)'.
                         '                   when reglament_=11 then if((clockWhite_-('.$time_.' - beginMove_)+60*60)>60*60*24,60*60*24,clockWhite_-('.$time_.' - beginMove_)+60*60)'.
                         '              end,'.
                         ' beginMove_ ='.$time_2.','.
                         ' isMoveWhite_ =\'N\''.
                         ' where (id_='.$p_->num_game_.') and (isMoveWhite_=\'Y\') and (result_ is null)';
              }else{
                  if ($may_otpusk_ && CUsers_::Status_Otpusk($w_id_)) $time_2 =0; else $time_2 =$time_;
                  $s ='update TMoves_ set'.
                         ' BMoveCell1_ =\''.$p_->cell_1.'\','.
                         ' BMoveCell2_ =\''.$p_->cell_2.'\','.
                         ' BPiece_ ='.(($p_->piece_ != '') ? '\''.$p_->piece_.'\'' : 'null').
                         ' where (idGame_='.$p_->num_game_.') and (num_='.$g->num_.') and (BMoveCell1_ is null)';
                  $d ='update TGames_ set'.
                         ' clockBlack_ =case when (reglament_=1) or (reglament_=2) or (reglament_=7) or (reglament_=8) or (reglament_=9) or (reglament_=10) then clockBlack_-('.$time_.' - beginMove_)'.
                         '                   when reglament_=3 then 60*60*24*3'.
                         '                   when reglament_=4 then if((clockBlack_-('.$time_.' - beginMove_)+60*60*24)>60*60*24*10,60*60*24*10,clockBlack_-('.$time_.' - beginMove_)+60*60*24)'.
                         '                   when reglament_=5 then if((clockBlack_-('.$time_.' - beginMove_)+60*60*24)>60*60*24*15,60*60*24*15,clockBlack_-('.$time_.' - beginMove_)+60*60*24)'.
                         '                   when reglament_=6 then if((clockBlack_-('.$time_.' - beginMove_)+60*60*24)>60*60*24*5,60*60*24*5,clockBlack_-('.$time_.' - beginMove_)+60*60*24)'.
                         '                   when reglament_=11 then if((clockBlack_-('.$time_.' - beginMove_)+60*60)>60*60*24,60*60*24,clockBlack_-('.$time_.' - beginMove_)+60*60)'.
                         '              end,'.
                         ' beginMove_ ='.$time_2.','.
                         ' isMoveWhite_ =\'Y\''.
                         ' where (id_='.$p_->num_game_.') and (isMoveWhite_=\'N\') and (result_ is null)';
              }
              if (!mysql_query($s,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0) ||
                  !mysql_query($d,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0))
                 throw new Exception('При совершении хода произошла ошибка.');
#отклонение предложения ничьей
              CGames_::delDrawn($p_->num_game_,$g->lastMoveIsWhite_ ? 'w' : 'b');
#Запись и удаление вариантов
              CGames_::dell_variants($p_->num_game_);
#              if (isset($_SESSION['variants'][$this->id_]))
#                  Games::save_all_variants($this->id_,$this->isMoveWhite_);
#проверка завершения партии
              $is_check_='';
              $i_ =-1;
              if (!$g->lastMoveIsWhite_){
                  $g->move($p_->cell_1,$p_->cell_2,($p_->piece_ !='') ? 'w'.$p_->piece_ : '',true);
                  switch ($g->isEndGame('b')){
                       case 1 : $i_ =1; break;
                       case 2 : $i_ =2; break;
                  }#switch
              }else{
                  $g->move($p_->cell_1,$p_->cell_2,($p_->piece_ !='') ? 'b'.$p_->piece_ : '',true);
                  switch ($g->isEndGame('w')){
                       case 1 : $i_ =0; break;
                       case 2 : $i_ =2; break;
                  }#switch
              }
              $s='';
              if ($i_ == 1){
                  $s ='update TMoves_ set w_isCheckMate_=\'Y\' where (idGame_='.$p_->num_game_.') and (num_='.$g->num_.')';
                  $is_check_='#';
              }elseif ($i_ == 0){
                  $s ='update TMoves_ set b_isCheckMate_=\'Y\' where (idGame_='.$p_->num_game_.') and (num_='.$g->num_.')';
                  $is_check_='#';
              }elseif ($i_ != 2)
                  if (!$g->lastMoveIsWhite_){
                      if ($g->isCheckCell('b',$g->black_king_last_position)){
                          $s ='update TMoves_ set w_isCheck_=\'Y\' where (idGame_='.$p_->num_game_.') and (num_='.$g->num_.')';
                          $is_check_='+';
                      }
                  }else
                      if ($g->isCheckCell('w',$g->white_king_last_position)){
                          $s ='update TMoves_ set b_isCheck_=\'Y\' where (idGame_='.$p_->num_game_.') and (num_='.$g->num_.')';
                          $is_check_='+';
                      }
              if (($s !='') && !mysql_query($s,const_::$connect_))
                 throw new Exception('При совершении хода произошла ошибка.');

#Уведомление на почту о свершенном ходе
#--------------------------------------------------------------------------------------------------------------------------------
              $e_mail_ ='';
              if ($class_ !='B'){                $j_ =count($g->table_moves)-1;
                if (!$g->lastMoveIsWhite_){
                  if (CUsers_::ReadMoveToE_Mail($b_id_)){
                    $e_mail_ =CUsers_::ReadE_Mail($b_id_);
                    if ($e_mail_ !=''){
                      $message_to_email_ ='Партия №'.$p_->num_game_."\r\n".
                                          'Белые: '.CUsers_::ReadLogins_(array($w_id_))."\r\n".
                                          'Черные: '.CUsers_::ReadLogins_(array($b_id_))."\r\n".
                                          'Ваш противник совершил ход:'.
                                          $g->table_moves[$j_]['num'].'.';
                      switch ($g->table_moves[$j_]['wpiece']){
                        case 'q' : $message_to_email_ .='Ф'; break;
                        case 'k' : $message_to_email_ .='Кр'; break;
                        case 'r' : $message_to_email_ .='Л'; break;
                        case 'n' : $message_to_email_ .='К'; break;
                        case 'b' : $message_to_email_ .='С'; break;
                      }#switch
                      $message_to_email_ .=$g->table_moves[$j_]['wmove'];
                      switch ($g->table_moves[$j_]['w_to_piece']){
                        case 'q' : $message_to_email_ .='Ф'; break;
                        case 'k' : $message_to_email_ .='Кр'; break;
                        case 'r' : $message_to_email_ .='Л'; break;
                        case 'n' : $message_to_email_ .='К'; break;
                        case 'b' : $message_to_email_ .='С'; break;
                      }#switch
                      $message_to_email_ .=$is_check_;
                    }
                  }
                }else{
                  if (CUsers_::ReadMoveToE_Mail($w_id_)){
                    $e_mail_ =CUsers_::ReadE_Mail($w_id_);
                    if ($e_mail_ !=''){
                      $message_to_email_ ='Партия №'.$p_->num_game_."\r\n".
                                          'Белые: '.CUsers_::ReadLogins_(array($w_id_))."\r\n".
                                          'Черные: '.CUsers_::ReadLogins_(array($b_id_))."\r\n".
                                          'Ваш противник совершил ход:'.
                                          $g->table_moves[$j_]['num'].'. ... ';
                      switch ($g->table_moves[$j_]['bpiece']){
                        case 'q' : $message_to_email_ .='Ф'; break;
                        case 'k' : $message_to_email_ .='Кр'; break;
                        case 'r' : $message_to_email_ .='Л'; break;
                        case 'n' : $message_to_email_ .='К'; break;
                        case 'b' : $message_to_email_ .='С'; break;
                      }#switch
                      $message_to_email_ .=$g->table_moves[$j_]['bmove'];
                      switch ($g->table_moves[$j_]['b_to_piece']){
                        case 'q' : $message_to_email_ .='Ф'; break;
                        case 'k' : $message_to_email_ .='Кр'; break;
                        case 'r' : $message_to_email_ .='Л'; break;
                        case 'n' : $message_to_email_ .='К'; break;
                        case 'b' : $message_to_email_ .='С'; break;
                      }#switch
                      $message_to_email_ .=$is_check_;
                    }
                  }
                }
              }
              if ($e_mail_ !=''){
                $i_e_mail_ =count(const_::$e_mails_);
                const_::$e_mails_[$i_e_mail_]['e-mail'] =$e_mail_;
                const_::$e_mails_[$i_e_mail_]['message'] =$message_to_email_;
              }
#--------------------------------------------------------------------------------------------------------------------------------
              if ($i_ != -1) CGames_::EndGame($p_->num_game_,0,$i_);
         }else
            throw new Exception('Ход невозможен.');
     type_1($id_user);
  }#type_3
?>