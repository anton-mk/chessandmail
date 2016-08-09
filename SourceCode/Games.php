<?php
    require_once('const_.php');
    require_once('Users.php');
    require_once('rule_game_.php');
    require_once('tournaments_.php');
    require_once('Variants.php');

	class CGames_{
		var $games_ym_;			//партии ожидающие ваш ход (games your move)
		var $games_am_;			//партии ожидающие ход соперника (games adversary move)
//Информация, полученная из таблицы TGames_
		var $login_white_;		//логин, играющего белыми
		var $login_black_;		//логин, играющего черными
		var $clock_white_;		//часы белых
		var $clock_black_;		//часы чёрных
		var $isMoveWhite_;		//ход белых
		var $result_;			//результат, если партия незаконченна $result_==''

#Часы отпуска белых
		public $clock_otpusk_white =false;
		public $clock_otpusk_black =false;
#Доступные операции.
# Индекс type_ - тип:
#      1- повернуть доску, 2 - сделать ход, 3 - предложить ничью, 4 - принять ничью,
#      5 - сдать партию, 6 -создать вариант, 7 - входящий вариант, 8 - исходящий вариант
#      9 - выбрать б. ферзя,  10 -выбрать б. ладью, 11 - выбрать б. слона, 12 - выбрать б. коня
#      13 - выбрать ч. ферзя, 14 -выбрать ч. ладью, 15 - выбрать ч. слона, 16 - выбрать ч. коня
#Индекс link_ - адрес операции
        protected static $operations_ =array();

        protected static function make_link_esc_for_error($id_){
            CPage_::$menu_[0]['link'] = 'MainPage.php?link_=game&id='.$id_;
            CPage_::$menu_[0]['image'] ='Image/label_esc.png';
            CPage_::$menu_[0]['submit'] =false;
            CPage_::$menu_[0]['level'] =1;
            CPage_::$menu_[0]['active'] ='N';
        }#make_link_esc_for_error

        protected static function outQuestionDrawn($id_,$header_){
            CPage_::$header_ =$header_;
            CPage_::QuestionPage('Подтвердите Ваше решение предложить ничью.',
                                 'MainPage.php?link_=game&id='.$id_,
                                 'MainPage.php?link_=game&id='.$id_.'&type_=3');
        }#outQuestionDrawn

        protected static function outQuestionAcceptDrawn($id_,$header_){
            CPage_::$header_ =$header_;
            CPage_::QuestionPage('Подтвердите Ваше решение принять предложение ничьей.',
                                 'MainPage.php?link_=game&id='.$id_,
                                 'MainPage.php?link_=game&id='.$id_.'&type_=4');
        }#outQuestionDrawn

        protected static function outQuestionYield($id_,$header_){
            CPage_::$header_ =$header_;
            CPage_::QuestionPage('Подтвердите Ваше решение сдать партию.',
                                 'MainPage.php?link_=game&id='.$id_,
                                 'MainPage.php?link_=game&id='.$id_.'&type_=5');
        }#outQuestionDrawn

        protected static function MakeCommentsGame($id_){
            $result_ ='';
            $result_='<TABLE id="table_comments_game" cellspasing="4">'."\n".
                     '    <TR>'."\n".
                     '        <TD>'."\n".
                     '            <IFRAME style="border: none; width:175px; height:'.(CUsers_::$last_value_read_dhtml_ ? '370px' : '350px').'" frameborder="0" marginwidth="0" src="CommentsGamePage.php?id='.$id_.'"></IFRAME>'."\n".
                     '        </TD>'."\n".
                     '    </TR>'."\n".
                     '</TABLE>'."\n";
            return $result_;
        }#MakeTableStat

        public static function MakeMenuMainPage(){
            $i =0;
            if (isset($_SESSION[SESSION_LINK_ESC_GAME])){
                CPage_::$menu_[$i]['link'] = $_SESSION[SESSION_LINK_ESC_GAME];
                CPage_::$menu_[$i]['image'] ='Image/label_esc.png';
                CPage_::$menu_[$i]['submit'] =false;
                CPage_::$menu_[$i]['level'] =1;
                CPage_::$menu_[$i++]['active'] ='N';
            }

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=Events';
            CPage_::$menu_[$i]['image'] ='Image/label_begin.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =1;
            CPage_::$menu_[$i++]['active'] ='N';

            for($a=0; $a < count(CGames_::$operations_); $a++){
                switch (CGames_::$operations_[$a]['type_']){
                    case 1:
                       CPage_::$menu_[$i]['image'] ='Image/label_rotate.png';
                       break;
                    case 2:
                       CPage_::$menu_[$i]['image'] ='Image/label_move.png';
                       break;
                    case 3:
                       CPage_::$menu_[$i]['image'] ='Image/label_drawn.png';
                       break;
                    case 4:
                       CPage_::$menu_[$i]['image'] ='Image/label_accept_drawn.png';
                       break;
                    case 5:
                       CPage_::$menu_[$i]['image'] ='Image/label_yield.png';
                       break;
                    case 6:
                       CPage_::$menu_[$i]['image'] ='Image/label_make_variant.png';
                       break;
                    case 7:
                       CPage_::$menu_[$i]['image'] ='Image/label_in_variant.png';
                       break;
                    case 8:
                       CPage_::$menu_[$i]['image'] ='Image/label_out_variant.png';
                       break;
                    case 9:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_wq.png';
                       break;
                    case 10:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_wr.png';
                       break;
                    case 11:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_wb.png';
                       break;
                    case 12:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_wn.png';
                       break;
                    case 13:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_bq.png';
                       break;
                    case 14:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_br.png';
                       break;
                    case 15:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_bb.png';
                       break;
                    case 16:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_bn.png';
                       break;
                }#switch
			   CPage_::$menu_[$i]['link'] = CGames_::$operations_[$a]['link_'];
			   CPage_::$menu_[$i]['submit'] =false;
   			   CPage_::$menu_[$i]['level'] =1;
   			   CPage_::$menu_[$i++]['active'] ='N';
            }#for

			CPage_::$menu_[$i]['link'] = 'index.php';
			CPage_::$menu_[$i]['image'] ='Image/label_exit.png';
			CPage_::$menu_[$i]['submit'] =false;
   			CPage_::$menu_[$i]['level'] =1;
   			CPage_::$menu_[$i]['active'] ='N';
        }#MakeMenuMainPage

        public static function MakePage(){
/*--------------------------------------------------------------------------------------
                          Псевдокод
--------------------------------------------------------------------------------------
            Инициализация переменных connect_,transact_,cursor_
            try
               id_game = номер партии
               установка связи с базой и начало транзакции
               получение информации об игроке
               header_ = заголовок окна
               w_id = id игрока белыми
               b_id = id игрока черными
               проверка отпуска w_id и b_id
               g = последняя позиция
               if время истекло
                 завершение партии
               .
               result_ =партия завершена
               cell1_ =''; cell2_ =''; piece_='';
               if (!result_ and (((w_id =_SESSION[SESSION_ID_]) and последний ход совершили черные)) or
                                 ((b_id =_SESSION[SESSION_ID_]) and последний ход совершили белые))) and
                   question_drawn не указан and question_accept_drawn не указан and question_yield не указан)
                 определение выбранных клеток
               .

               if question_yield указан
                 if анализ question_yield
                   CPage_::$header_=header_
                   формирование страницы подтверждения
                   return
                 .
               else if question_accept_drawn указан
                 if анализ question_accept_drawn
                   CPage_::$header_=header_
                   формирование страницы подтверждения
                   return
                 .
               else if question_drawn указан
                 if анализ question_drawn
                   CPage_::$header_=header_
                   формирование страницы подтверждения
                   return
                 .
               else if type_ указан
                 switch type_
                   1 : if (w_id =_SESSION[SESSION_ID_]) or (b_id =_SESSION[SESSION_ID_])
                         повернуть доску
                       .
                       cell1_ =''; cell2_ =''; piece_='';
                   2 : анализ вариантов продолжения
                       if (cell1_ <> '') and (cell2_ <> '')
                         определение превращения пешки
                         совершение хода
                       .
                       cell1_ =''; cell2_ =''; piece_='';
                   3 : if партия не завершена
                         offerDrawn =информация о предложении ничьей
                         color_='';
                         if (w_id_ ==$_SESSION[SESSION_ID_])
                             color_='w';
                           else if (b_id_ ==$_SESSION[SESSION_ID_])
                             color_='b';
                         .
                         if (color_ != '') and (offerDrawn =='')
                           Добавить запись в таблицу TOffersDrawn_
                         .
                       .
                   4 : if партия не завершена
                         offerDrawn =информация о предложении ничьей
                         if (((w_id_ ==$_SESSION[SESSION_ID_]) && (offerDrawn=='b')) ||
                             ((b_id_ ==$_SESSION[SESSION_ID_]) && (offerDrawn=='w')))
                           Завершить партию
                           result_ =true;
                         .
                       .
                   5 : if партия не завершена
                         if (w_id_ ==$_SESSION[SESSION_ID_])
                           завершить партию
                           return_ =true
                          else if (b_id_ ==$_SESSION[SESSION_ID_])
                           завершить партию
                           return_ =true
                         .
                       .
                   6 : if партия не завершена
                         if вариант указан верно
                           if (ход белых и (w_id_ ==$_SESSION[SESSION_ID_])) or
                              (ход черных и (b_id_ ==$_SESSION[SESSION_ID_])
                                удалить вариант
                           .
                         .
                       .
                   7 : if партия не завершена
                         if (ход белых и (w_id_ ==$_SESSION[SESSION_ID_])) or
                            (ход черных и (b_id_ ==$_SESSION[SESSION_ID_])
                           if если в отпуске
                              исключение
                           .
                           if номер варианта указан неверно
                              исключение
                           .
                           принять вариант
                           считать состояние партии
                         .
                       .
                   .
               else if click_cell указана
                   if !result_ and (((w_id =_SESSION[SESSION_ID_]) and последний ход совершили черные)) or
                                    ((b_id =_SESSION[SESSION_ID_]) and последний ход совершили белые)))
                     анализ click_cell
                   .
               .
               формирование списка доступных операций
               body = доска и таблица ходов
               завершение транзакции и разрыв связи с базой
               формирование страницы
            catch
               анализ переменных connect_,transact_,cursor_
               формирование страницы ошибки
            .
--------------------------------------------------------------------------------------
*/

            unset($_SESSION[SESSION_LINK_ESC_ABOUT_GAMER]);

            const_::$e_mails_ =array();

            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
                $header_ ='';
                $ok_id_game =false;
                if (!isset($_GET['id']) || !ctype_digit($_GET['id']))
                    throw new Exception('Номер партии указан неверно.');
                $id_game=$_GET['id'];
                $ok_id_game =true;

                $_SESSION[SESSION_LINK_ESC_VARIANT]='MainPage.php?link_=game&id='.$id_game;

                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception('При соединении с базой данных произошла ошибка.');
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception('При соединении с базой данных произошла ошибка.');

                $classA_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],1);
                $classB_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],2);
                $classC_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],3);
                $ratingA_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],1);
                $ratingB_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],2);
                $ratingC_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],3);
                CUsers_ ::Read_dhtml_();
                $header_='<DIV id="text_login_">'.
                         '  логин: '.$_SESSION[SESSION_LOGIN_].'<BR>'.
                         '  класс: A'.$classA_.'/B'.$classB_.'/C'.$classC_.
                         '  рейтинг: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'<BR>'.
                         '</DIV>'   .
                         '<DIV id="text_header_">'.
                         '  Партия №'.$id_game.
                         '</DIV>';
#Получаю класс партии и возможность взять отпуск
                $class_ =CGames_::getClass($id_game);
                $mayOtpusk_ =($class_ !='B') && CGames_::getMayOtpusk($id_game);
#Получаю id игроков
                $w_id_ =0; $b_id_ =0;
                CGames_::getIDs($id_game,$w_id_,$b_id_);
                if ($mayOtpusk_){
                  CUsers_::Check_otpusk($w_id_);
                  CUsers_::Check_otpusk($b_id_);
                }
#Восстанавливаю последнюю позицию
                $g =new CRuleGame();
                $g->id_=$id_game;
                $g->lastPosition();
#Проверка - время истекло
                CGames_::endGameIfClockZero($id_game);
#Получение информации - партия завершена
                $s ='select result_ from TGames_ where (id_='.$id_game.')';
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('При чтении информации о партии произошла ошибка.');
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception('При чтении информации о партии произошла ошибка.');
                $result_ =!is_null($row_['result_']);
                mysql_free_result($cursor_); $cursor_=false;

                $cell1_ =''; $cell2_ =''; $piece_=''; $exist_variants =false;
                if (!$result_ && ((($w_id_ ==$_SESSION[SESSION_ID_]) && !$g->lastMoveIsWhite_) ||
                                  (($b_id_ ==$_SESSION[SESSION_ID_]) && $g->lastMoveIsWhite_)) &&
                    !isset($_GET['question_drawn']) && !isset($_GET['question_accept_drawn']) &&
                    !isset($_GET['question_yield'])){
                   if (isset($_SESSION['variants']) && isset($_SESSION['variants'][$id_game]) &&
                       (count($_SESSION['variants'][$id_game]) >0)){
                        $exist_variants =true;
                    }else{
#Определяю выбранные ячейки
                         if (isset($_GET['sel_1']) && $g->checkFirstClick($_GET['sel_1'])){
                           $cell1_ =$_GET['sel_1'];
                           if (isset($_GET['sel_2']) && $g->checkMove($cell1_,$_GET['sel_2']))
                             $cell2_ =$_GET['sel_2'];
                         }
                   }
                }

                if (isset($_GET['question_yield'])){
                  if (!$result_ && (($w_id_ ==$_SESSION[SESSION_ID_]) || ($b_id_ ==$_SESSION[SESSION_ID_]))){
                    CGames_::outQuestionYield($id_game,$header_);
                    return;
                  }
                }else if (isset($_GET['question_accept_drawn'])){
                  $offerDrawn =CGames_::getInfoDrawn($id_game);
                  if (!$result_ && ((($w_id_ ==$_SESSION[SESSION_ID_]) && ($offerDrawn=='b')) ||
                                    (($b_id_ ==$_SESSION[SESSION_ID_]) && ($offerDrawn=='w')))){
                    CGames_::outQuestionAcceptDrawn($id_game,$header_);
                    return;
                  }
                }else if (isset($_GET['question_drawn'])){
                  if (!$result_ && (($w_id_ ==$_SESSION[SESSION_ID_]) || ($b_id_ ==$_SESSION[SESSION_ID_]))){
                    $offerDrawn =CGames_::getInfoDrawn($id_game);
                    if ($offerDrawn ==''){
                        CGames_::outQuestionDrawn($id_game,$header_);
                        return;
                    }
                  }
                }else if (isset($_GET['type_']) && ctype_digit($_GET['type_'])){
                  switch ($_GET['type_']){
                      case 1 : #Вращение доски
#----------------------------------------------------------------------------------------------------------
                         if (($w_id_ ==$_SESSION[SESSION_ID_]) || ($b_id_ ==$_SESSION[SESSION_ID_])){
                             $s ='select A.rotateBoard_W,A.rotateBoard_B'.
                                 ' from TGames_ A'.
                                 ' where (A.id_='.$id_game.')';
                             $cursor_=mysql_query($s,const_::$connect_);
                             if (!$cursor_) throw new Exception('При чтении информации о партии произошла ошибка.');
                             $row_ =mysql_fetch_array($cursor_);
                             if (!$row_) throw new Exception('При чтении информации о партии произошла ошибка.');
                             if ($w_id_ ==$_SESSION[SESSION_ID_])
                                  $s ='update TGames_ set rotateBoard_W=\''.(($row_['rotateBoard_W'] =='Y') ? 'N' : 'Y').'\' where id_='.$id_game;
                               else
                                  $s ='update TGames_ set rotateBoard_B=\''.(($row_['rotateBoard_B'] =='Y') ? 'N' : 'Y').'\' where id_='.$id_game;
                             if (!mysql_query($s,const_::$connect_)) throw new Exception('При повороте доски произошла ошибка.');
                             mysql_free_result($cursor_); $cursor_ =false;
                         }
                         $cell1_ =''; $cell2_ =''; $piece_='';
                         break;
                      case 2: # совершение хода
#----------------------------------------------------------------------------------------------------------
#Анализ вариантов продолжения
                         if ($exist_variants){
#Удаление вариантов
                           CGames_::dell_variants($id_game);

                           if ((count($_SESSION['variants'][$id_game][0]) > 0) &&
                               (!$g->lastMoveIsWhite_ && !isset($_SESSION['variants'][$id_game][0][0]['WMoveCell1_'])) ||
                               ($g->lastMoveIsWhite_ && isset($_SESSION['variants'][$id_game][0][0]['WMoveCell1_'])))
                                throw new Exception('Вариант должен начинаться Вашим ходом.');
#Возможно ситуация когда у пользователя одна партия с вариантами открыта в двух браузерах, после того как он сделает ход
# в одном браузере, варианты продолжения в другом остануться. Проверка ниже предотвращает выполнение хода в этом случае
                           if ($g->num_ != $_SESSION['variants'][$id_game][0][0]['num_'])
                                throw new Exception('В варианте не соблюдена очередность хода. '.
                                                    'Возможно, это связанно с тем, что Вы уже сделали ход. '.
                                                    'Попробуйте выйти и зайти заново на сайт.');
                           if (!$g->lastMoveIsWhite_){
                             $cell1_ =$_SESSION['variants'][$id_game][0][0]['WMoveCell1_'];
                             $cell2_ =$_SESSION['variants'][$id_game][0][0]['WMoveCell2_'];
                             $piece_ =$_SESSION['variants'][$id_game][0][0]['WPiece_'];
                           }else{
                             $cell1_ =$_SESSION['variants'][$id_game][0][0]['BMoveCell1_'];
                             $cell2_ =$_SESSION['variants'][$id_game][0][0]['BMoveCell2_'];
                             $piece_ =$_SESSION['variants'][$id_game][0][0]['BPiece_'];
                           }
                           CGames_::save_variants($g,$id_game);
                         }

                         if (($cell1_ != '') && ($cell2_ !='')){
                           if ($mayOtpusk_ && CUsers_::Status_Otpusk($_SESSION[SESSION_ID_]))
                             throw new Exception('Вы не можете сделать ход, находясь в отпуске.');
#Определяю фигуру, в которую превращается пешка
                           if ((($g->board_[$cell1_{0}][$cell1_{1}] == 'wp') && ($cell2_{1}==8)) ||
                               (($g->board_[$cell1_{0}][$cell1_{1}] == 'bp') && ($cell2_{1}==1)))
                             if (isset($_GET['piece']) && (($_GET['piece'] =='q') || ($_GET['piece'] =='r') ||
                                                           ($_GET['piece'] =='b') || ($_GET['piece'] =='n')))
                               $piece_ =$_GET['piece'];
                             else
                               throw new Exception('Фигура, в которую должна превратиться пешка, указанна неверно.');
#Совершаю ход
                           $time_ =time();
                           if (!$g->lastMoveIsWhite_){
                             if ($mayOtpusk_){
                               CUsers_::Check_otpusk($b_id_);
                               if (CUsers_::Status_Otpusk($b_id_)) $time_2 =0; else $time_2 =$time_;
                             }else $time_2 =$time_;
                             $s ='insert into TMoves_(num_,WMoveCell1_,WMoveCell2_,WPiece_,idGame_)'.
                                 ' values('.$g->num_.',\''.$cell1_.'\',\''.$cell2_.'\','.(($piece_ != '') ? "'{$piece_}'" : 'null').','.$id_game.')';
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
                                 ' where (id_='.$id_game.') and (result_ is null)';
                           }else{
                             if ($mayOtpusk_){
                               CUsers_::Check_otpusk($w_id_);
                               if (CUsers_::Status_Otpusk($w_id_)) $time_2 =0; else $time_2 =$time_;
                             }else $time_2 =$time_;
                             $s ='update TMoves_ set'.
                                 ' BMoveCell1_ =\''.$cell1_.'\','.
                                 ' BMoveCell2_ =\''.$cell2_.'\','.
                                 ' BPiece_ ='.(($piece_ != '') ? '\''.$piece_.'\'' : 'null').
                                 ' where (idGame_='.$id_game.') and (num_='.$g->num_.') and (BMoveCell1_ is null)';
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
                                 ' where (id_='.$id_game.') and (isMoveWhite_=\'N\') and (result_ is null)';
                           }
                           if (!mysql_query($s,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0) ||
                               !mysql_query($d,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0))
                             throw new Exception('При совершении хода произошла ошибка.');
#отклонение предложения ничьей
                           CGames_::delDrawn($id_game,$g->lastMoveIsWhite_ ? 'w' : 'b');
#Удаление вариантов
                           if (!$exist_variants)
                             CGames_::dell_variants($id_game);
#проверка завершения партии
                           $i_ =-1;
                           if (!$g->lastMoveIsWhite_){
                             $g->table_moves[]['num'] =$g->num_;
                             $j_ =count($g->table_moves)-1;
                             $g->table_moves[$j_]['w_isCheck']='';
#                             $g->table_moves[$j_]['b_isCheck']='';
                             $g->move($cell1_,$cell2_,($piece_ !='') ? 'w'.$piece_ : '',true);
#определяю e-mail и заголовок письма
                             switch ($g->isEndGame('b')){
                               case 1 : $i_ =1; break;
                               case 2 : $i_ =2; break;
                             }#switch
                           }else{
                             $g->move($cell1_,$cell2_,($piece_ !='') ? 'b'.$piece_ : '',true);
                             switch ($g->isEndGame('w')){
                               case 1 : $i_ =0; break;
                               case 2 : $i_ =2; break;
                             }#switch
                           }
                           $s='';
                           $j_ =count($g->table_moves)-1;
                           if ($i_ == 1){
                             $s ='update TMoves_ set w_isCheckMate_=\'Y\' where (idGame_='.$id_game.') and (num_='.$g->num_.')';
                             $g->table_moves[$j_]['w_isCheck'] ='#';
                           }elseif ($i_ == 0){
                             $s ='update TMoves_ set b_isCheckMate_=\'Y\' where (idGame_='.$id_game.') and (num_='.$g->num_.')';
                             $g->table_moves[$j_]['b_isCheck'] ='#';
                           }elseif ($i_ != 2)
                             if (!$g->lastMoveIsWhite_){
                               if ($g->isCheckCell('b',$g->black_king_last_position)){
                                 $s ='update TMoves_ set w_isCheck_=\'Y\' where (idGame_='.$id_game.') and (num_='.$g->num_.')';
                                 $g->table_moves[$j_]['w_isCheck'] ='+';
                               }
                             }else
                               if ($g->isCheckCell('w',$g->white_king_last_position)){
                                 $s ='update TMoves_ set b_isCheck_=\'Y\' where (idGame_='.$id_game.') and (num_='.$g->num_.')';
                                 $g->table_moves[$j_]['b_isCheck'] ='+';
                               }
                           if (($s !='') && !mysql_query($s,const_::$connect_))
                             throw new Exception('При совершении хода произошла ошибка.');
#Уведомление на почту о свершенном ходе
#--------------------------------------------------------------------------------------------------------------------------------
                             $e_mail_ ='';
                             if ($class_ !='B'){
                               if (!$g->lastMoveIsWhite_){
                                 if (CUsers_::ReadMoveToE_Mail($b_id_)){
                                   $e_mail_ =CUsers_::ReadE_Mail($b_id_);
                                   if ($e_mail_ !=''){
                                     $message_to_email_ ='Партия №'.$id_game."\r\n".
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
                                     $message_to_email_ .=$g->table_moves[$j_]['w_isCheck'];
                                   }
                                 }
                               }else{
                                 if (CUsers_::ReadMoveToE_Mail($w_id_)){
                                   $e_mail_ =CUsers_::ReadE_Mail($w_id_);
                                   if ($e_mail_ !=''){
                                     $message_to_email_ ='Партия №'.$id_game."\r\n".
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
                                     $message_to_email_ .=$g->table_moves[$j_]['b_isCheck'];
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
                           if ($i_ != -1) CGames_::EndGame($id_game,0,$i_);

                           $g->num_++;
                           $g->lastMoveIsWhite_ =!$g->lastMoveIsWhite_;
                         }
#Удаляю исходящие варианты
                         if ($exist_variants){
                           unset($_SESSION['variants'][$id_game]);
                           $exist_variants =false;
                         }

                         $cell1_ =''; $cell2_ =''; $piece_='';
                         break;
                      case 3: #предложение ничьей
#----------------------------------------------------------------------------------------------------------
                         if (!$result_){
                             $offerDrawn =CGames_::getInfoDrawn($id_game);
                             $color_='';
                             if ($w_id_ ==$_SESSION[SESSION_ID_])
                               $color_='w';
                              else if ($b_id_ ==$_SESSION[SESSION_ID_])
                               $color_='b';
                             if (($color_ != '') && ($offerDrawn =='')){
                                 if ($mayOtpusk_ && CUsers_::Status_Otpusk($_SESSION[SESSION_ID_]))
                                     throw new Exception('Вы не можете предложить ничью, находясь в отпуске.');
                                 $s ='insert into TOffersDrawn_(id_game_,who_offer_)'.
                                     ' values('.$id_game.',\''.$color_.'\')';
                                 if (!mysql_query($s,const_::$connect_))
                                     throw new Exception('При сохранении предложения о ничьей произошла ошибка.');
                             }
                         }
                         break;
                      case 4: #принятие ничьей
#----------------------------------------------------------------------------------------------------------
                         if (!$result_){
                             $offerDrawn =CGames_::getInfoDrawn($id_game);
                             if ((($w_id_ ==$_SESSION[SESSION_ID_]) && ($offerDrawn=='b')) ||
                                 (($b_id_ ==$_SESSION[SESSION_ID_]) && ($offerDrawn=='w'))){
                                 if ($mayOtpusk_ && CUsers_::Status_Otpusk($_SESSION[SESSION_ID_]))
                                     throw new Exception('Вы не можете принять ничью, находясь в отпуске.');
                                 CGames_::EndGame($id_game,0,2);
                                 $result_ =true;
                             }
                         }
                         break;
                      case 5: #сдать партию
#----------------------------------------------------------------------------------------------------------
                         if (!$result_){
                            if ($mayOtpusk_ && CUsers_::Status_Otpusk($_SESSION[SESSION_ID_]))
                                throw new Exception('Вы не можете сдать партию, находясь в отпуске.');
                            if ($w_id_ ==$_SESSION[SESSION_ID_]){
                                 CGames_::EndGame($id_game,0,0);
                                 $result_ =true;
                            }else if ($b_id_ ==$_SESSION[SESSION_ID_]){
                                 CGames_::EndGame($id_game,0,1);
                                 $result_ =true;
                            }
                         }
                         break;
                      case 6 : #удаление варианта продолжения
#----------------------------------------------------------------------------------------------------------
                         if (!$result_){
                            if (!isset($_GET['num_variant']) || !ctype_digit($_GET['num_variant']) ||
                                !isset($_SESSION['variants']) || !isset($_SESSION['variants'][$id_game]) ||
                                ($_GET['num_variant'] < 1) || ($_GET['num_variant'] > count($_SESSION['variants'][$id_game])))
                              throw new Exception('Номер варианта указан неверно.');
                            if ((($w_id_ ==$_SESSION[SESSION_ID_]) && !$g->lastMoveIsWhite_) ||
                                (($b_id_ ==$_SESSION[SESSION_ID_]) && $g->lastMoveIsWhite_)){
                              $v=$_GET['num_variant'] -1;
                              array_splice($_SESSION['variants'][$id_game],$v,1);
                              if (count($_SESSION['variants'][$id_game]) ==0){
                                 unset($_SESSION['variants'][$id_game]);
                                 if (count($_SESSION['variants']) ==0) unset($_SESSION['variants']);
                                 $exist_variants =false;
                              }
                            }else
                              throw new Exception('Удалить вариант продолжения может только тот, кто его создал.');
                         }
                         break;
                      case 7 : #принятие варианта
#----------------------------------------------------------------------------------------------------------
                         if (!$result_){
                             if ((($w_id_ ==$_SESSION[SESSION_ID_]) && !$g->lastMoveIsWhite_) ||
                                 (($b_id_ ==$_SESSION[SESSION_ID_]) && $g->lastMoveIsWhite_)){
                                 if ($mayOtpusk_ && CUsers_::Status_Otpusk($_SESSION[SESSION_ID_]))
                                     throw new Exception('Вы не можете принять вариант, находясь в отпуске.');
                                 if (!isset($_GET['id_variant']) || !ctype_digit($_GET['id_variant']))
                                     throw new Exception('Вариант указан неверно.');
                                 CGames_::accept_variant($id_game,$_GET['id_variant']);
                                 $g =new CRuleGame();
                                 $g->id_=$id_game;
                                 $g->lastPosition();
                                 $cell1_ =''; $cell2_ =''; $piece_='';
#Запись и удаление вариантов
                                 CGames_::dell_variants($id_game);
#Удаляю исходящие варианты
                                 if ($exist_variants){
                                   unset($_SESSION['variants'][$id_game]);
                                   $exist_variants =false;
                                 }
                             }
                         }
                         break;
                  }#switch
                }else{
# -------------------------------------------------------
                  if (isset($_GET['click_cell'])){
                    if (!$result_ && ((($w_id_ ==$_SESSION[SESSION_ID_]) && !$g->lastMoveIsWhite_) ||
                                      (($b_id_ ==$_SESSION[SESSION_ID_]) && $g->lastMoveIsWhite_))){
                       if ($cell1_ == ''){
                           if ($g->checkFirstClick($_GET['click_cell']))
                               $cell1_ =$_GET['click_cell'];
                       }elseif (($cell1_ != '') && ($cell2_ == '')){
                           if ($_GET['click_cell'] == $cell1_)
                               $cell1_ ='';
                           elseif ($g->checkMove($cell1_,$_GET['click_cell']))
                               $cell2_ =$_GET['click_cell'];
                       }else{
                           if ($_GET['click_cell'] == $cell2_)
                               $cell2_ ='';
                           elseif ($g->checkFirstClick($_GET['click_cell'])){
                               $cell1_ =$_GET['click_cell'];
                               $cell2_ ='';
                           }else{
                               $cell1_ =''; $cell2_ ='';
                           }
                       }
                    }
                  }
                }
#Формирование списка операции
#-------------------------------------------------------------------------------------------------------------
                $n =-1;
                if (($cell1_ != '') && ($cell2_ != '')){
                    if (($g->board_[$cell1_{0}][$cell1_{1}] == 'wp') && ($cell2_{1}==8)){
                        CGames_::$operations_[++$n]['type_'] =9;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=2&'.
                                                            'sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=q';
                        CGames_::$operations_[++$n]['type_'] =10;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=2&'.
                                                            'sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=r';
                        CGames_::$operations_[++$n]['type_'] =11;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=2&'.
                                                            'sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=b';
                        CGames_::$operations_[++$n]['type_'] =12;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=2&'.
                                                            'sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=n';
                    }else if (($g->board_[$cell1_{0}][$cell1_{1}] == 'bp') && ($cell2_{1}==1)){
                        CGames_::$operations_[++$n]['type_'] =13;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=2&'.
                                                            'sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=q';
                        CGames_::$operations_[++$n]['type_'] =14;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=2&'.
                                                            'sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=r';
                        CGames_::$operations_[++$n]['type_'] =15;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=2&'.
                                                            'sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=b';
                        CGames_::$operations_[++$n]['type_'] =16;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=2&'.
                                                            'sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=n';
                    }else{
                        CGames_::$operations_[++$n]['type_'] =2;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=2&'.
                                                            'sel_1='.$cell1_.'&sel_2='.$cell2_;
                    }
                }

                if ($exist_variants){
                    CGames_::$operations_[++$n]['type_'] =2;
                    CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=2';
                }

                $ajax_flag_drawn =false;
                $ajax_flag_accept_drwan =false;
                if (($w_id_ ==$_SESSION[SESSION_ID_]) || ($b_id_ ==$_SESSION[SESSION_ID_])){
                    CGames_::$operations_[++$n]['type_'] =1;
                    CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&type_=1';

                    if (!$result_){
                        $offerDrawn =CGames_::getInfoDrawn($id_game);
                        if ($offerDrawn ==''){
                            CGames_::$operations_[++$n]['type_'] =3;
                            CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&question_drawn=yes';
                            $ajax_flag_drawn =true;
                        }else if ((($offerDrawn =='w') && ($b_id_ ==$_SESSION[SESSION_ID_])) ||
                                  (($offerDrawn =='b') && ($w_id_ ==$_SESSION[SESSION_ID_]))){
                            CGames_::$operations_[++$n]['type_'] =4;
                            CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&question_accept_drawn=yes';
                            $ajax_flag_accept_drwan =true;
                        }
                    }
                }

                if (!$result_ && (($w_id_ ==$_SESSION[SESSION_ID_]) || ($b_id_ ==$_SESSION[SESSION_ID_]))){
                    CGames_::$operations_[++$n]['type_'] =5;
                    CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=game&id='.$id_game.'&question_yield=yes';
                }
#Варианты продолжения
                $inVariants =array();
                if (!$result_ && ((($w_id_ ==$_SESSION[SESSION_ID_]) && !$g->lastMoveIsWhite_) ||
                                  (($b_id_ ==$_SESSION[SESSION_ID_]) && $g->lastMoveIsWhite_))){
                    CGames_::get_input_variant($id_game,$inVariants);
                    for ($i=0; $i < count($inVariants); $i++){
                        CGames_::$operations_[++$n]['type_'] =7;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=in_variant&id='.$id_game.'&id_variant='.$inVariants[$i];
                    }#for
                }
                if (!$result_ && (($w_id_ ==$_SESSION[SESSION_ID_]) || ($b_id_ ==$_SESSION[SESSION_ID_]))){
                    CGames_::$operations_[++$n]['type_'] =6;
                    CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=make_variant&id='.$id_game;
                }
                if (isset($_SESSION['variants']) && isset($_SESSION['variants'][$id_game]))
                    for($i=0; $i < count($_SESSION['variants'][$id_game]); $i++){
                        CGames_::$operations_[++$n]['type_'] =8;
                        CGames_::$operations_[$n]['link_'] ='MainPage.php?link_=variant&id='.$id_game.'&num_variant='.($i+1);
                    }#for
#-------------------------------------------------------------------------------------------------------------

#Комментарий
#-------------------------------------------------------------------------------------------------------------
                if (($w_id_ ==$_SESSION[SESSION_ID_]) || ($b_id_ ==$_SESSION[SESSION_ID_])){
                    CPage_::$comments_game_ =CGames_::MakeCommentsGame($id_game);
                }
#-------------------------------------------------------------------------------------------------------------
                $body_ =CGames_::BodyBoard($g,$exist_variants,$cell1_,$cell2_,count($inVariants)>0);

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
                const_::send_mails();
                if ($connect_)const_::Disconnect_();

                CPage_::$header_ =$header_;
                CGames_::MakeMenuMainPage();
                CPage_::$body_ =$body_;
#Подключение скрипта AJAX
#-------------------------------------------------------------------------------------------------------------
                $script_ajax_ ='';
                if (!$result_ && CUsers_::$last_value_read_dhtml_){
                    $script_ajax_ .='<SCRIPT type="text/javascript" src="scripts/json2.js"></SCRIPT>'."\n".
                                    '<SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n";
                    if ($class_ !='B')
                      $script_ajax_ .='<SCRIPT type="text/javascript" src="scripts/game_.js"></SCRIPT>'."\n";
                     else
                      $script_ajax_ .='<SCRIPT type="text/javascript" src="scripts/game_online.js"></SCRIPT>'."\n".
                                      '<SCRIPT type="text/javascript" src="scripts/modal_form.js"></SCRIPT>';
                    $script_ajax_ .='<SCRIPT type="text/javascript">'."\n".
                                    '  var o_game_ = null;'."\n".
                                    '  window.onload =function(){ '."\n".
                                    '     o_game_ = new cl_control_game_("Image/",'.$id_game.',"'.const_::$catalog_image_fugure.'",'.const_::$size_image_cell.');'."\n";
#последняя позиция
                    for ($a='A'; $a <='H'; $a =chr(ord($a{0})+1))
                       for ($b=1; $b <=8; $b++)
                          if ($g->board_[$a][$b] != '')
                            $script_ajax_ .=' o_game_.board_[\''.$a.'\']['.$b.'] =\''.$g->board_[$a][$b].'\';'."\n";
#таблица ходов
                    for ($a=0; $a < count($g->table_moves); $a++)
                       $script_ajax_ .=' o_game_.table_moves_['.$a.'] =new Array('.
                                       '"'.(isset($g->table_moves[$a]['wpiece']) ? $g->table_moves[$a]['wpiece'] : '').'",'.
                                       '"'.(isset($g->table_moves[$a]['wmove']) ? $g->table_moves[$a]['wmove'] : '').'",'.
                                       '"'.(isset($g->table_moves[$a]['w_to_piece']) ? $g->table_moves[$a]['w_to_piece'] : '').'",'.
                                       '"'.(isset($g->table_moves[$a]['w_isCheck']) ? $g->table_moves[$a]['w_isCheck'] : '').'",'.
                                       '"'.(isset($g->table_moves[$a]['bpiece']) ? $g->table_moves[$a]['bpiece'] : '').'",'.
                                       '"'.(isset($g->table_moves[$a]['bmove']) ? $g->table_moves[$a]['bmove'] : '').'",'.
                                       '"'.(isset($g->table_moves[$a]['b_to_piece']) ? $g->table_moves[$a]['b_to_piece'] : '').'",'.
                                       '"'.(isset($g->table_moves[$a]['b_isCheck']) ? $g->table_moves[$a]['b_isCheck'] : '').'"'.
                                       ');'."\n";
                    if ($w_id_ ==$_SESSION[SESSION_ID_])
                        $script_ajax_ .='   o_game_.color_=\'w\';'."\n";
                      elseif ($b_id_ ==$_SESSION[SESSION_ID_])
                        $script_ajax_ .='   o_game_.color_=\'b\';'."\n";
                    $script_ajax_ .='   o_game_.init();'."\n";

                    if (count($inVariants) > 0){
                      for ($a=0; $a < count($inVariants); $a++)
                         $script_ajax_ .='o_game_.in_variants['.$a.'] ='.$inVariants[$a].";\n";
                         $script_ajax_ .='o_game_.row_in_variant = o_game_.c_ins_link_in_variant;'."\n";
                    }
                    if ($ajax_flag_drawn){
                      $script_ajax_ .='o_game_.row_operation_drawn = o_game_.c_ins_link_drawn;'."\n";
                      $script_ajax_ .='o_game_.row_in_variant +=2;'."\n";
                    }
                    if ($ajax_flag_accept_drwan){
                      $script_ajax_ .='o_game_.row_operation_accept_drawn = o_game_.c_ins_link_accept_drawn;'."\n";
                      $script_ajax_ .='o_game_.row_in_variant +=2;'."\n";
                    }

                    $script_ajax_ .='  }'."\n".
                                    '</SCRIPT>'."\n";
                }
#-------------------------------------------------------------------------------------------------------------
                CPage_::MakePage($script_ajax_);

                $_SESSION[SESSION_LINK_ESC_ABOUT_GAMER]='MainPage.php?link_=game&id='.$id_game;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
                if ($ok_id_game)
                  CGames_::make_link_esc_for_error($id_game);
                if ($header_ !='')
                  CPage_::$header_ =$header_;
                CPage_::PageErr();
            }#try
        }#MakePage

       protected static function save_variants(CRuleGame $g,$id_game){
         for($i =0; $i <count($_SESSION['variants'][$id_game]); $i++){
            $j =count($_SESSION['variants'][$id_game][$i]);
            if ($j < 2) throw new Exception('Вариант должен содержать более одного хода');
	        if ((!$g->lastMoveIsWhite_ && isset($_SESSION['variants'][$id_game][$i][$j-1]['BMoveCell1_'])) ||
                ($g->lastMoveIsWhite_ && !isset($_SESSION['variants'][$id_game][$i][$j-1]['BMoveCell1_'])))
              throw new Exception('Вариант должен заканчиваться Вашим ходом');
#Добавляю данные в таблицу TVariants
            $s ='insert into TVariants_(idGame_) values('.$id_game.')';
            if (!mysql_query($s,const_::$connect_)) throw new Exception();
            $id_variant =mysql_insert_id(const_::$connect_);
#Добавляю ходы варианта
#Первый ход варианта не записывается, если первый ход должны сделать черные 0 ход варианта не записывается,
# если первый ход должны сделать белые, 0 ход белых не записывается.
            for ($j=($g->lastMoveIsWhite_ ? 1 : 0); $j < count($_SESSION['variants'][$id_game][$i]); $j++){
              $v =$_SESSION['variants'][$id_game][$i][$j];
              $s ='insert into TMovesVariant_(num_,idVariant_';
              $s_v ='values('.$v['num_'].','.$id_variant;
              if (($j > 0) && isset($v['WMoveCell1_'])){
                  $s .=',WMoveCell1_,WMoveCell2_';
                  $s_v .=',\''.$v['WMoveCell1_'].'\',\''.$v['WMoveCell2_'].'\'';
                  if (isset($v['WPiece_']) && ($v['WPiece_'] != '')){
                      $s .=',WPiece_';
                      $s_v .=',\''.$v['WPiece_'].'\'';
                  }
                  if (isset($r_['w_isCheck_']) &&  ($v['w_isCheck_']  != '')){
                      $s .=',w_isCheck_';
                      $s_v .=',\''.$v['w_isCheck_'].'\'';
                  }
              }
              if (isset($v['BMoveCell1_'])){
                  $s .=',BMoveCell1_,BMoveCell2_';
                  $s_v .=',\''.$v['BMoveCell1_'].'\',\''.$v['BMoveCell2_'].'\'';
                  if (isset($v['BPiece_']) && ($v['BPiece_']  != '')){
                      $s .=',BPiece_';
                      $s_v .=',\''.$v['BPiece_'].'\'';
                  }
                  if (isset($v['b_isCheck_']) && ($v['b_isCheck_'] != '')){
                      $s .=',b_isCheck_';
                      $s_v .=',\''.$v['b_isCheck_'].'\'';
                  }
              }
              $s .=')';
              $s_v .=')';
              if (!mysql_query($s.' '.$s_v,const_::$connect_)) throw new Exception('При сохранении варианта произошла ошибка.');
            }#for j
         }#for i
       }#save_variants

        public static function get_input_variant($id_game,&$in_variants){
           $s ='select A.id_ from TVariants_ A where (A.idGame_='.$id_game.') order by A.id_';
           $cursor_=mysql_query($s,const_::$connect_);
           if (!$cursor_) throw new Exception('При чтении информации о вариантах продолжения произошла ошибка.');
           while ($row_ =mysql_fetch_array($cursor_))
              $in_variants[] = $row_['id_'];
           mysql_free_result($cursor_);
        }#get_input_variant

        public static function endGameIfClockZero($id_){
            $cursor_ =false;
            try{
                $time_ =time();
                $s ='select isMoveWhite_,result_,beginMove_,'.
                    '       if(isMoveWhite_=\'Y\',clockWhite_,clockBlack_)-'.$time_.' + beginMove_ as clock_'.
                    ' from TGames_ where id_='.$id_;
                $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении информации о партии произошла ошибка.');
                $row_=mysql_fetch_array($cursor_); if (!$row_) throw new Exception('При чтении информации о партии произошла ошибка.');
                $result_=!is_null($row_['result_']);
                if (is_null($row_['result_']) && ($row_['beginMove_'] !=0)){
                  if ($row_['clock_'] <= 0){
                    if ($row_['isMoveWhite_'] == 'Y')
                        CGames_::EndGame($id_,1,0);
                     else
                        CGames_::EndGame($id_,2,1);
                    $result_ =true;
                  }
                }
                mysql_free_result($cursor_);
                return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception($e->getMessage());
            }
        }#endGameIfClockZero


        public static function getInfoDrawn($id_){
            $result_ ='';
            $s ='select who_offer_ from TOffersDrawn_ where (id_game_ ='.$id_.') order by id_';
            $cursor_ =mysql_query($s,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о партии произошла ошибка.');
            $row_ =mysql_fetch_array($cursor_);
            if ($row_) $result_ =$row_['who_offer_'];
            mysql_free_result($cursor_);
            return $result_;
        }#getInfoDrawn

        public static function getInfoRotate($id_){
            $cursor_ =false;
            try{
               $s ='select  A.rotateBoard_W,A.rotateBoard_B,A.idWGamer_,A.idBGamer_'.
                   ' from TGames_ A'.
                   ' where (A.id_='.$id_.')';
               $cursor_ =mysql_query($s,const_::$connect_);
               if (!$cursor_) throw new Exception();
               $row_ =mysql_fetch_array($cursor_);
               if (!$row_) throw new Exception();
               if ($_SESSION[SESSION_ID_] ==$row_['idWGamer_'])
                   $result_ =($row_['rotateBoard_W']=='Y');
                 elseif ($_SESSION[SESSION_ID_] ==$row_['idBGamer_'])
                   $result_ =($row_['rotateBoard_B']=='Y');
                 else
                   $result_ ='';
               mysql_free_result($cursor_);
               return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При чтении информации о партии произошла ошибка.');
            }#try
        }#getInfoRotate

       public static function getLogins($id_,&$wlogin_,&$blogin_){
            $cursor_ =false;
            try{
               $s ='select B.login_ as wlogin_, C.login_ as blogin_'.
                   ' from TGames_ A, TGamers_ B,TGamers_ C'.
                   ' where (A.id_='.$id_.') and (A.idWGamer_ = B.id_) and (A.idBGamer_ = C.id_)';
               $cursor_ =mysql_query($s,const_::$connect_);
               if (!$cursor_) throw new Exception();
               $row_ =mysql_fetch_array($cursor_);
               if (!$row_) throw new Exception();
               $wlogin_ =convert_cyr_string($row_['wlogin_'],'d','w');
               $blogin_ =convert_cyr_string($row_['blogin_'],'d','w');
               mysql_free_result($cursor_);
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При чтении информации о партии произошла ошибка.');
            }#try
       }#getLogins

       public static function getIDs($id_,&$w_id_,&$b_id_){
            $cursor_ =false;
            try{
               $s ='select A.idWGamer_,A.idBGamer_'.
                   ' from TGames_ A'.
                   ' where (A.id_='.$id_.')';
               $cursor_ =mysql_query($s,const_::$connect_);
               if (!$cursor_) throw new Exception();
               $row_ =mysql_fetch_array($cursor_);
               if (!$row_) throw new Exception();
               $w_id_ =$row_['idWGamer_'];
               $b_id_ =$row_['idBGamer_'];
               mysql_free_result($cursor_);
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При чтении информации о партии произошла ошибка.');
            }#try
       }#getIDs

       public static function getClass($id_){
            $cursor_ =false;
            $result_ ='';
            try{
               $s ='select A.class_'.
                   ' from TGames_ A'.
                   ' where (A.id_='.$id_.')';
               $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
               $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
               $result_ =$row_['class_'];
               mysql_free_result($cursor_);
               return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При чтении класса партии произошла ошибка.');
            }#try
       }

       public static function getMayOtpusk($id_){
            $cursor_ =false;
            $result_ ='';
            try{
               $s ='select A.no_otpusk'.
                   ' from TGames_ A'.
                   ' where (A.id_='.$id_.')';
               $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
               $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
               $result_ =is_null($row_['no_otpusk']);
               mysql_free_result($cursor_);
               return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При чтении информации о партии произошла ошибка.');
            }#try
       }

       public static function getClocks($id_,&$wclock_,&$bclock_,&$result_){
            $cursor_ =false;
            try{
               $curr_time=time();
               $s ='select A.result_,A.clockWhite_,A.clockBlack_,A.beginMove_,A.isMoveWhite_'.
                   ' from TGames_ A'.
                   ' where (A.id_='.$id_.')';
               $cursor_ =mysql_query($s,const_::$connect_);
               if (!$cursor_) throw new Exception();
               $row_ =mysql_fetch_array($cursor_);
               if (!$row_) throw new Exception();
               $wclock_ = $row_['clockWhite_'];
               $bclock_ = $row_['clockBlack_'];
               if (is_null($row_['result_']))
                 if (($row_['isMoveWhite_'] == 'Y') && ($row_['beginMove_'] <> 0))
                     $wclock_ =$wclock_ - ($curr_time - $row_['beginMove_']);
                  elseif (($row_['isMoveWhite_'] == 'N') && ($row_['beginMove_'] <> 0))
                     $bclock_ =$bclock_ - ($curr_time - $row_['beginMove_']);
               $result_ = $row_['result_'];
               mysql_free_result($cursor_);
               $wclock_ = clockToStr(($wclock_ > 0) ? $wclock_ : 0);
               $bclock_ = clockToStr(($bclock_ > 0) ? $bclock_ : 0);
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При чтении информации о партии произошла ошибка.');
            }#try
       }#getClocks

       public static function getInfoOtpusk($id_,&$wotpusk_,&$botpusk_){
            $cursor_ =false;
            try{
               $s ='select A.result_,A.idWGamer_,A.idBGamer_'.
                    ' from TGames_ A'.
                    ' where (A.id_='.$id_.')';
               $cursor_ =mysql_query($s,const_::$connect_);
               if (!$cursor_) throw new Exception();
               $row_ =mysql_fetch_array($cursor_);
               if (!$row_) throw new Exception();
               if (is_null($row_['result_'])){
                   CUsers_::Check_otpusk($row_['idWGamer_']);
                   CUsers_::Check_otpusk($row_['idBGamer_']);
                   if (CUsers_::Status_Otpusk($row_['idWGamer_'])){
                     $wotpusk_ =CUsers_::Ostatok_Otpusk($row_['idWGamer_']);
                     $wotpusk_ =clockToStr(($wotpusk_ > 0) ? $wotpusk_ : 0);
                   }
                   if (CUsers_::Status_Otpusk($row_['idBGamer_'])){
                     $botpusk_ =CUsers_::Ostatok_Otpusk($row_['idBGamer_']);
                     $botpusk_ =clockToStr(($botpusk_ > 0) ? $botpusk_ : 0);
                   }
               }else{
                   $wotpusk_ ='';
                   $botpusk_ ='';
               }
               mysql_free_result($cursor_);
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При чтении информации о партии произошла ошибка.');
            }#try
       }#getInfoOtpusk

#Удаляет предложение о ничьей
        public static function delDrawn($id_, $color_=''){
            $s ='delete from TOffersDrawn_ where (id_game_ ='.$id_.')';
            if ($color_ !='') $s .=' and (who_offer_=\''.$color_.'\')';
            if (!mysql_query($s,const_::$connect_)) throw new Exception('При удалении предложения ничьей произошла ошибка');
        } #DelDrawn

        public static function dell_variants($id_){
            $s ='delete from TVariants_ where idGame_= '.$id_;
            if (!mysql_query($s,const_::$connect_)) throw new Exception();
        }#dell_variants

        protected static function accept_variant($id_game_,$id_variant){
#Первый ход варианта
            CVariants_::$active_variant =array();
            CVariants_::get_moves_in_variant($id_variant);
            $v =CVariants_::$active_variant;
            $j=0;
            if (!isset($v[0]['WMoveCell1_'])){
              $s ='update TMoves_ set'.
                  '  BMoveCell1_ ='.(isset($v[0]['BMoveCell1_']) ? '\''.$v[0]['BMoveCell1_'].'\'' : 'null').','.
                  '  BMoveCell2_ ='.(isset($v[0]['BMoveCell2_']) ? '\''.$v[0]['BMoveCell2_'].'\'' : 'null').','.
                  '  BPiece_ ='.(isset($v[0]['BPiece_']) ? '\''.$v[0]['BPiece_'].'\'' : 'null').','.
                  '  b_isCheck_ ='.(isset($v[0]['b_isCheck_']) ? '\''.$v[0]['b_isCheck_'].'\'' : 'null').
                  ' where (idGame_='.$id_game_.') and (num_='.$v[0]['num_'].') and (BMoveCell1_ is null)';
              if (!mysql_query($s,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0))
                throw new Exception('При принятии варианта возникла ошибка.');
              $j=1;
            }

            for($i=$j; $i < count($v); $i++){
              $s='insert into TMoves_(num_,idGame_,WMoveCell1_,WMoveCell2_,WPiece_,w_isCheck_,BMoveCell1_,BMoveCell2_,BPiece_,b_isCheck_)'.
                 ' values('.$v[$i]['num_'].','.$id_game_.','.
                 (isset($v[$i]['WMoveCell1_']) ? '\''.$v[$i]['WMoveCell1_'].'\'' : 'null').','.
                 (isset($v[$i]['WMoveCell2_']) ? '\''.$v[$i]['WMoveCell2_'].'\'' : 'null').','.
                 (isset($v[$i]['WPiece_']) ? '\''.$v[$i]['WPiece_'].'\'' : 'null').','.
                 (isset($v[$i]['w_isCheck_']) ? '\''.$v[$i]['w_isCheck_'].'\'' : 'null').','.
                 (isset($v[$i]['BMoveCell1_']) ? '\''.$v[$i]['BMoveCell1_'].'\'' : 'null').','.
                 (isset($v[$i]['BMoveCell2_']) ? '\''.$v[$i]['BMoveCell2_'].'\'' : 'null').','.
                 (isset($v[$i]['BPiece_']) ? '\''.$v[$i]['BPiece_'].'\'' : 'null').','.
                 (isset($v[$i]['b_isCheck_']) ? '\''.$v[$i]['b_isCheck_'].'\'' : 'null').')';
              if (!mysql_query($s,const_::$connect_)) throw new Exception('При принятии варианта возникла ошибка.');
            }#for
        }#accept_variant

#$clockZero_: 0 - не устанавливать в ноль часы, 1 - часы белых в ноль, 2 - часы чёрных в ноль
#$result_: 0 - 0-1, 1 - 1-0, 2 - 1/2-1/2
       public static function EndGame($id_,$clockZero_,$result_){
            $cursor_ =false;
            try{
#Событие о завершении партии
#------------------------------------------------------------------------------------------------------------------------------------------------
                $s ='select A.idWGamer_,A.idBGamer_,A.class_,B.login_ as w_login_,C.login_ as b_login_,D.w_isCheckMate_,D.b_isCheckMate_'.
                        ' from TGames_ A left join TMoves_ D on (D.idGame_ = A.id_) and'.
                        '                                       not exists (select * from TMoves_ where (idGame_ =A.id_) and (num_ > D.num_)),'.
                        '          TGamers_ B, TGamers_ C'.
                        ' where (A.idWGamer_ =B.id_) and (A.idBGamer_=C.id_) and (A.id_='.$id_.')';

                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $l_w =trim(convert_cyr_string($row_['w_login_'],'d','w'));
                $l_b =trim(convert_cyr_string($row_['b_login_'],'d','w'));
                $w_isCheckMate =$row_['w_isCheckMate_'];
                $b_isCheckMate =$row_['b_isCheckMate_'];
                $idWGamer_ =$row_['idWGamer_'];
                $idBGamer_ =$row_['idBGamer_'];
                $class_ =$row_['class_'];
                mysql_free_result($cursor_); $cursor_ =false;

                $s ='Партия номер <A href="MainPage.php?link_=game&id='.$id_.'">'.$id_.'</A> '.$l_w.' - '.$l_b;
                if ($result_ ==2)
                     $s .=' завершилась в ничью.';
                else{
                    $s .=' завершилась с результатом';
                    if ($result_ ==0){
                       $s .=' 0 : 1';
                       if ($clockZero_==1) $s .=' (белые просрочили время).';
                         elseif (!is_null($b_isCheckMate)) $s .=' (чёрные поставили мат).';
                         else $s .=' (белые сдали партию).';
                    }else{
                       $s .=' 1 : 0';
                       if ($clockZero_==2) $s .=' (чёрные просрочили время).';
                         elseif (!is_null($w_isCheckMate)) $s .=' (белые поставили мат).';
                         else $s .=' (чёрные сдали партию).';
                    }
                }
                CUsers_::writeEvents($s,$idWGamer_);
                CUsers_::writeEvents($s,$idBGamer_);
#------------------------------------------------------------------------------------------------------------------------------------------------
                $s ='update TGames_ set ';
                if ($clockZero_ == 1) $s .='clockWhite_ = 0,';
                   elseif ($clockZero_ == 2) $s .='clockBlack_ = 0,';
                $s .=' result_ ='.$result_.' where (id_='.$id_.')';
                if (!mysql_query($s,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0)) throw new Exception();
                CGames_::delDrawn($id_);
#Удаляю варианты
                CGames_::dell_variants($id_);
#Изменение класса 8
                switch ($class_){
                    case 'A' : $A_B_C =1; break;
                    case 'B' : $A_B_C =2; break;
                    case 'C' : $A_B_C =3; break;
                    default :
                        throw new Exception();
                }#switch
                CUsers_::checkChangeClass8($idWGamer_, $A_B_C);
                CUsers_::checkChangeClass8($idBGamer_, $A_B_C);
#Закрытие турнира
                CTournaments_::CloseTournament($id_);
#Изменение рейтинга
                CUsers_::СhangeRating($id_);
#Отправка уведомлений на почтовый ящик.
                if ($class_ !='B'){
                  for($j=1; $j < 3; $j++){
                    if ($j==1) $k =$idWGamer_; else $k =$idBGamer_;
                    if (CUsers_::ReadMoveToE_Mail($k)){
                      $email_ =CUsers_::ReadE_Mail($k);
                      if ($email_ != ''){
                        $message_ ='Партия №'.$id_.' завершилась ';
                        switch ($result_){
                            case 0 : $message_ .='с результатом 0:1.'; break;
                            case 1 : $message_ .='с результатом 1:0.'; break;
                            case 2 : $message_ .='в ничью.'; break;
                        }#switch
                        $i =count(const_::$e_mails_);
                        const_::$e_mails_[$i]['e-mail'] =$email_;
                        const_::$e_mails_[$i]['message'] =$message_;
                      }
                    }
                  }#for
                }
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception($e->getMessage());
#                throw new Exception('При завершении партии произошла ошибка.');
            }
       }#EndGame

        protected static function BodyBoard(CRuleGame $game_,$exist_variants_=false,$cell1_='',$cell2_='',$inVariants=false){
            const_::set_params_image_board();
            $login_white_ =''; $login_black_ ='';
            CGames_::getLogins($game_->id_,$login_white_,$login_black_);
            $class_ =CGames_::getClass($game_->id_);
            $may_otpusk =(($class_ != 'B') && CGames_::getMayOtpusk($game_->id_));
            $otpusk_white =''; $otpusk_black ='';
            if ($may_otpusk)
              CGames_::getInfoOtpusk($game_->id_,$otpusk_white,$otpusk_black);
            $clock_white_ =''; $clock_black_ =''; $result_game_ ='';
            CGames_::getClocks($game_->id_,$clock_white_,$clock_black_,$result_game_);
            $offerDrawn =CGames_::getInfoDrawn($game_->id_);
#isRotate=true - черные внизу, false черные вверху
            $isRotate =CGames_::getInfoRotate($game_->id_);

            $k_ =CUsers_::Read_scale_board();
            $l_=round(const_::$size_image_cell*$k_); settype($l_,"integer"); $w_  ='width="'.$l_.'"'; $h_ ='height="'.$l_.'"';
            $l_=round(const_::$size_image_left_board*$k_); settype($l_,"integer"); $w_l ='width="'.$l_.'"';
            $l_=round(const_::$size_image_right_board*$k_); settype($l_,"integer"); $w_r ='width="'.$l_.'"';
            $l_=round(const_::$size_image_top_bottom_board*$k_); settype($l_,"integer"); $h_t  ='height="'.$l_.'"';
            $im_ =const_::$catalog_image_fugure;

            $s_ ='MainPage.php?link_=game&id='.$game_->id_;

            if ($cell1_ !=''){
                $s_ .='&sel_1='.$cell1_;
                if ($cell2_ !='') $s_ .='&sel_2='.$cell2_;
            }
            $result_ ='<TABLE style ="border: none">'."\n".
                      '  <COL span="2">'."\n".
                      '  <TR><TD style="padding:0px 30px 0px 30px; vertical-align:top">'."\n".
                      '  <DIV style="font-family: Liberation Serif, Times, sans-serif; '.
                      '            font-size: 12pt; font-style: normal; font-weight: normal; '.
                      '            color : black; text-align: center">';

            $result_ .='<TABLE style ="border: none; margin-left: auto; margin-right: auto" cellspacing="0" cellpadding="0">'."\n".
                       '  <COL span="10">'."\n";

            $s_drawn ='<SPAN id="label_drawn">';
            if ($inVariants)
              $s_drawn .='<DIV style="font-size: 14pt; color:white">'.
                                  'присланы варианты продолжения'.
                              '</DIV>';
            if (($offerDrawn !='') && is_null($result_game_)){
#                if (!$inVariants) $s_drawn .='<BR/>';
                $s_drawn .='<DIV style="font-size: 14pt; color:white">'.
                                  (($offerDrawn =='w') ? 'белые предложили ничью' : 'чёрные предложили ничью').
                                '</DIV>';
            }
            $s_drawn .='</SPAN>'; 
            if ($isRotate){
                $result_ .='<TR><TD colspan="10">'.$s_drawn.
                           '  <DIV>'.
                           '    <A href="MainPage.php?link_=about_gamer&login_='.urlencode($login_white_).'">'.htmlspecialchars($login_white_,ENT_QUOTES,CODE_PAGE).'</A>'.
                           '  </DIV>'.
                           '</TD></TR>'.
                           '<TR><TD colspan="10">'."\n".
                           '       <DIV><SPAN id="clock_white">'.htmlspecialchars($clock_white_,ENT_QUOTES,CODE_PAGE).'</SPAN>';
                if ($otpusk_white !=='')
                    $result_ .='&nbsp;отпуск, осталось: '.htmlspecialchars($otpusk_white,ENT_QUOTES,CODE_PAGE);
                $result_ .="\n".
                           '       </DIV>'."\n".
                           '</TD></TR>'."\n";
            }else{
                $result_ .='<TR><TD colspan="10">'.$s_drawn.
                           '  <DIV>'.
                           '    <A href="MainPage.php?link_=about_gamer&login_='.urlencode($login_black_).'">'.htmlspecialchars($login_black_,ENT_QUOTES,CODE_PAGE).'</A></TD></TR>'.
                           '  </DIV>'.
                           '<TR><TD colspan="10" style="text-align: center">'."\n".
                           '       <DIV><SPAN id="clock_black">'.htmlspecialchars($clock_black_,ENT_QUOTES,CODE_PAGE).'</SPAN>';
                if ($otpusk_black !=='')
                    $result_ .='&nbsp;отпуск, осталось: '.htmlspecialchars($otpusk_black,ENT_QUOTES,CODE_PAGE);
                $result_ .="\n".
                           '       </DIV>'."\n".
                           '</TD></TR>'."\n";
            }
//верхняя часть доски
            $result_ .='<TR><TD><IMG '.$w_l.' '.$h_t.' style="border:none; vertical-align: bottom" src="'.$im_.'board_tl.jpg"/></TD>'."\n";
            for($i_=1; $i_<9; $i_++)
                $result_ .='<TD><IMG '.$w_.' '.$h_t.' style="border:none; vertical-align: bottom" src="'.$im_.'board_top.jpg"/></TD>'."\n";
            $result_ .='<TD><IMG '.$w_r.' '.$h_t.' style="border:none; vertical-align: bottom" src="'.$im_.'board_tr.jpg"/></TD>'."\n".
                       '</TR>'."\n";
#доска
            for($i_=($isRotate ? 1 : 8); $isRotate ? $i_<=8 : $i_>=1; ($isRotate ? $i_++ : $i_--)){
                $result_ .='<TR>'."\n".
                           '<TD><IMG '.$w_l.' '.$h_.' style="border:none; vertical-align: top" src="'.$im_.'board_'.$i_.'.jpg"/></TD>'."\n";
                for($j_=($isRotate ? 'H' : 'A'); $isRotate ? $j_>='A' : $j_<='H'; $j_=($isRotate ? chr(ord($j_)-1) : chr(ord($j_)+1))){
                    $result_ .='<TD>';
                    if (!$exist_variants_ && !CUsers_::$last_value_read_dhtml_)
                       $result_.='<A href="'.$s_.'&click_cell='.$j_.$i_.'">';
                    $result_ .='<IMG id="'.$j_.$i_.'" '.$w_.' '.$h_;
                    if (!$exist_variants_ && CUsers_::$last_value_read_dhtml_ && is_null($result_game_))
                        $result_ .=' onclick="o_game_.onClickCell(\''.$j_.$i_.'\');"';
                    $result_ .=' style="border:none; vertical-align: top; cursor:pointer" src="'.$im_.$game_->board_[$j_][$i_].$game_->getColorBoard($j_.$i_).
                               ((($cell1_ == ($j_.$i_)) || ($cell2_ == ($j_.$i_))) ? 's' : '').'.jpg"/>';
                    if (!$exist_variants_ && !CUsers_::$last_value_read_dhtml_) $result_ .='</A>';
                    $result_ .='</TD>';
                }//for $j_
                $result_ .='<TD><IMG  '.$w_r.' '.$h_.'  style="border:none; vertical-align: top" src="'.$im_.'board_right.jpg"/></TD>'."\n".
                           '</TR>';
            }//for $i_
//нижняя часть доски
            $result_ .='<TR><TD><IMG  '.$w_l.' '.$h_t.' style="border:none; vertical-align: top" src="'.$im_.'board_bl.jpg"/></TD>'."\n";
            for($i_=($isRotate ? 'h' : 'a'); $isRotate ? $i_>='a' : $i_<='h'; $i_ =($isRotate ? chr(ord($i_)-1) : chr(ord($i_)+1)))
                $result_ .='<TD><IMG  '.$w_.' '.$h_t.' style="border:none; vertical-align: top" src="'.$im_.'board_'.$i_.'.jpg"/></TD>'."\n";
            $result_ .='<TD><IMG  '.$w_r.' '.$h_t.' style="border:none; vertical-align: top" src="'.$im_.'board_br.jpg"/></TD>'."\n".
                       '</TR>'."\n";

            if ($isRotate){
                $result_ .='<TR><TD colspan="10">'."\n".
                           '  <DIV><SPAN id="clock_black">'.htmlspecialchars($clock_black_,ENT_QUOTES,CODE_PAGE).'</SPAN>';
                if ($otpusk_black !=='')
                    $result_ .='&nbsp;отпуск, осталось: '.htmlspecialchars($otpusk_black,ENT_QUOTES,CODE_PAGE);
                $result_ .="\n".
                           '       </DIV>'."\n".
                           '</TD></TR>'."\n".
                           '<TR><TD colspan="10"><A href="MainPage.php?link_=about_gamer&login_='.urlencode($login_black_).'">'.htmlspecialchars($login_black_,ENT_QUOTES,CODE_PAGE).'</A></TD></TR>';
            }else{
                $result_ .='<TR><TD colspan="10">'.
                           '       <DIV><SPAN id="clock_white">'.htmlspecialchars($clock_white_,ENT_QUOTES,CODE_PAGE).'</SPAN>';
                if ($otpusk_white !=='')
                    $result_ .='&nbsp;отпуск, осталось: '.htmlspecialchars($otpusk_white,ENT_QUOTES,CODE_PAGE);
                $result_ .="\n".
                           '       </DIV>'."\n".
                           '</TD></TR>'."\n".
                           '<TR><TD colspan="10"><A href="MainPage.php?link_=about_gamer&login_='.urlencode($login_white_).'">'.htmlspecialchars($login_white_,ENT_QUOTES,CODE_PAGE).'</A></TD></TR>';
            }

            $result_ .='<TR><TD colspan="10"><BR/><DIV class="you_move_" id="info_result_or_move">'."\n";
            if (!is_null($result_game_)){
                $s_ ='Результат: ';
                switch ($result_game_){
                    case 0 : $s_ .='0:1'; break;
                    case 1 : $s_ .='1:0'; break;
                    case 2 : $s_ .='1/2:1/2'; break;
                }
                $result_ .=htmlspecialchars($s_,ENT_QUOTES,CODE_PAGE)."\n";
/*            }elseif ($mayClick_){
                $s_ ='Ход: '.$game_->num_;
                if ($game_->lastMoveIsWhite_) $s_ .='... ';
                if ($cell1_ !=''){
                    $s_ .=$cell1_;
                    if ($cell2_ !='') $s_ .=' - '.$cell2_;
                }
                $result_ .=htmlspecialchars($s_)."\n";*/
            }
            $result_ .='</DIV></TD></TR>'."\n".
                       '</TABLE>'."\n".
                       '</DIV>'."\n".
                       '</TD>'."\n";

            $result_ .='<TD style="vertical-align: top">'."\n".
                       '  <DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                       '            font-size: 12pt; font-style: normal; font-weight: normal;'.
                       '            color : black">'.
                             CGames_::Table_moves_($game_).
                       '  </DIV>'."\n".
                       '</TD></TR>'."\n".
                       '</TABLE>'."\n";
            return $result_;
        } #BodyBoard

        protected static function Table_moves_(CRuleGame $game_){
            $result_ ='<SPAN id="table_moves">'."\n".
                            '<TABLE style ="border: 1px solid black" cellspacing="0" cellpadding="3">'."\n";
//определяю кол-во колонок
            if (count($game_->table_moves) > 20) $col_count =2; else $col_count =1;
//определяю кол-во строк
            if (count($game_->table_moves) <= 20) $row_count = count($game_->table_moves);
                elseif (count($game_->table_moves) <= 40) $row_count = 20;
                else{
                    $row_count =floor(count($game_->table_moves) /2);
                    if ((count($game_->table_moves) % 2) !=0) $row_count++;
                }

            $s1_ ='"border-top: none; border-left: none; border-right: 1px solid black; border-bottom: none"';
            $s2_ ='"border-top: none; border-left: none; border-right: 2px solid black; border-bottom: none"';
            $s3_ ='"border-top: 1px solid black; border-left: none; border-right: 1px solid black; border-bottom: none; white-space:nowrap"';
            $s4_ ='"border-top: 1px solid black; border-left: none; border-right: 2px solid black; border-bottom: none; white-space:nowrap"';
            $s5_ ='"border-top: 1px solid black; border-left: none; border-right: none; border-bottom: none; white-space:nowrap"';
            $s3_v ='"border-top: 1px solid black; border-left: none; border-right: 1px solid black; border-bottom: none; white-space:nowrap; color:blue; font-style:italic"';
            $s4_v ='"border-top: 1px solid black; border-left: none; border-right: 2px solid black; border-bottom: none; white-space:nowrap; color:blue; font-style:italic"';
            $s5_v ='"border-top: 1px solid black; border-left: none; border-right: none; border-bottom: none; white-space:nowrap; color:blue; font-style:italic"';
            $s3_c ='"border-top: 1px solid black; border-left: none; border-right: 1px solid black; border-bottom: none; white-space:nowrap; color:blue; font-style:italic;font-weight: bold"';
            $s4_c ='"border-top: 1px solid black; border-left: none; border-right: 2px solid black; border-bottom: none; white-space:nowrap; color:blue; font-style:italic;font-weight: bold"';
            $s5_c ='"border-top: 1px solid black; border-left: none; border-right: none; border-bottom: none; white-space:nowrap; color:blue; font-style:italic;font-weight: bold"';

            if ($col_count ==1){
                    $result_ .='<COL span="3">'."\n".
                                     '<TR><TD style='.$s1_.'>ход</TD><TD style='.$s1_.'>белые</TD><TD style="border:none">черные</TD></TR>'."\n";
                }else{
                    $result_ .='<COL span="6">'."\n".
                                     '<TR><TD style='.$s1_.'>ход</TD><TD style='.$s1_.'>белые</TD><TD style='.$s2_.'>черные</TD>'."\n".
                                     '    <TD style='.$s1_.'>ход</TD><TD style='.$s1_.'>белые</TD><TD style="border:none">черные</TD></TR>'."\n";
            }
            for ($r_=1; $r_<=$row_count; $r_++){
                $result_ .='<TR>'."\n";
                for($c_=1; $c_ <=$col_count; $c_++){
//определяю индекс
                    $i_ =($r_ -1) + $row_count * ($c_-1);
//вывожу ход
                    if (isset($game_->table_moves[$i_])){
#определяю принадлежит ли ход варианту
#                        $f_m_v        =Games::$first_move_variant;
#                        $c_m_v        =Games::$curr_move_variant;
#                        $t_m_n        =$Games_->table_moves[$i_]['num'];
#                        $i_w_c_m_v =Games::$is_white_curr_move_variant;
#                        $i_w_f_m_v  =Games::$is_white_first_move_variant;
#                        if (($f_m_v ==-1) or ($f_m_v > $t_m_n)){
                            $s3 =$s3_; $s4 =$s4_; $s5 =$s5_;
#                        }else if ($c_m_v ==$t_m_n){
#                            if ($i_w_c_m_v){$s3 =$s3_c; $s4 =$s4_v; $s5 =$s5_v;}
#                                else{
#                                    if (($f_m_v ==$t_m_n) && !$i_w_f_m_v)
#                                        $s3 =$s3_;
#                                    else
#                                        $s3 =$s3_v;
#                                    $s4 =$s4_c; $s5 =$s5_c;
#                                }
#                       }else if ($f_m_v ==$t_m_n){
#                           if ($i_w_f_m_v) $s3 =$s3_v; else $s3 =$s3_;
#                           $s4 =$s4_v; $s5 =$s5_v;
#                       }else{
#                           $s3 =$s3_v; $s4 =$s4_v; $s5 =$s5_v;
#                       }

                        $result_ .='<TD style='.$s3.'>'.$game_->table_moves[$i_]['num'].'</TD>'."\n";
                        $s_ ='';
                        if ($game_->table_moves[$i_]['wpiece'] !='') $s_='<IMG style="border:none" src="Image/fw'.$game_->table_moves[$i_]['wpiece'].'.png"/>';
                        $s_ .=$game_->table_moves[$i_]['wmove'];
                        if ($game_->table_moves[$i_]['w_to_piece'] !='') $s_.='<IMG style="border:none" src="Image/fw'.$game_->table_moves[$i_]['w_to_piece'].'.png"/>';
                        if ($game_->table_moves[$i_]['w_isCheck'] !='') $s_.=$game_->table_moves[$i_]['w_isCheck'];
                        $result_ .='<TD style='.$s3.' id ="move_white_'.($r_+($row_count*($c_-1))).'">'.$s_.'</TD>'."\n";
                        if (isset($game_->table_moves[$i_]['bmove'])){
                            $s_ ='';
                            if ($game_->table_moves[$i_]['bpiece'] !='') $s_='<IMG style="border:none" src="Image/fw'.$game_->table_moves[$i_]['bpiece'].'.png"/>';
                            $s_ .=$game_->table_moves[$i_]['bmove'];
                            if ($game_->table_moves[$i_]['b_to_piece'] !='') $s_.='<IMG style="border:none" src="Image/fw'.$game_->table_moves[$i_]['b_to_piece'].'.png"/>';
                            if ($game_->table_moves[$i_]['b_isCheck'] !='') $s_.=$game_->table_moves[$i_]['b_isCheck'];
                        }else $s_='&nbsp';
                        $result_ .='<TD style='.((($col_count==2) && ($c_==1)) ? $s4 : $s5).
                                         '    id ="move_black_'.($r_+($row_count*($c_-1))).'">'.$s_.'</TD>'."\n";
                    }else
                        $result_ .='<TD style='.$s3_.'>'.($r_+($row_count*($c_-1))).'</TD>'.
                                         '<TD style='.$s3_.' id ="move_white_'.($r_+($row_count*($c_-1))).'">&nbsp</TD>'.
                                         '<TD style='.((($col_count==2) && ($c_==1)) ? $s4_ : $s5_).
                                         '    id ="move_black_'.($r_+($row_count*($c_-1))).'">&nbsp</TD>'."\n";
                }//for $c_
                $result_ .='</TR>'."\n";
            }//for $r_
            $result_ .='</TABLE>'."\n".
                       '</SPAN>'."\n";
            return $result_;
        } #Table_moves_
	} //CGames_
?>