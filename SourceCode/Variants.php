<?php
    require_once('const_.php');
    require_once('rule_game_.php');
    require_once('Games.php');
    require_once('Users.php');

    class CVariants_{
        public static $active_variant;               # активный вариант
        protected static $current_move_=0;              # текущий ход в варианте (последний, отображенный на доске ход)
        protected static $current_move_is_white_=true;  # текущий ход в варианте сделали белые
# Индекс type_ - тип:
#      1- ход назад, 2 - ход вперед, 3 - принять вариант, 4 - сохранить ход, 5 - удалить вариант
#      6 - выбрать б. ферзя,  7 -выбрать б. ладью, 8 - выбрать б. слона, 9 - выбрать б. коня
#      10 - выбрать ч. ферзя, 11 -выбрать ч. ладью, 12 - выбрать ч. слона, 13 - выбрать ч. коня
#Индекс link_ - адрес операции
        protected static $operations_ =array();

        protected static function outQuestionAcceptVariant($id_game,$id_variant,$header_){
            CPage_::$header_ =$header_;
            CPage_::QuestionPage('Подтвердите Ваше решение принять вариант.',
                                 'MainPage.php?link_=in_variant&id='.$id_game.'&id_variant='.$id_variant,
                                 'MainPage.php?link_=game&id='.$id_game.'&type_=7&id_variant='.$id_variant);
        }#outQuestionAcceptVariant

        protected static function outQuestionDellVariant($id_game,$num_variant,$header_){
            CPage_::$header_ =$header_;
            CPage_::QuestionPage('Подтвердите Ваше решение удалить вариант.',
                                 'MainPage.php?link_=variant&id='.$id_game.'&num_variant='.$num_variant,
                                 'MainPage.php?link_=game&id='.$id_game.'&type_=6&num_variant='.$num_variant);
        }#outQuestionDellVariant

        protected static function make_link_esc_for_error($id_){
            CPage_::$menu_[0]['link'] = 'MainPage.php?link_=game&id='.$id_;
            CPage_::$menu_[0]['image'] ='Image/label_esc.png';
            CPage_::$menu_[0]['submit'] =false;
            CPage_::$menu_[0]['level'] =1;
            CPage_::$menu_[0]['active'] ='N';
        }#make_link_esc_for_error

        protected static function make_link_esc_for_message($id_,$num_variant_){
            CPage_::$menu_[0]['link'] = 'MainPage.php?link_=variant&id='.$id_.'&num_variant='.($num_variant_+1);
            CPage_::$menu_[0]['image'] ='Image/label_esc.png';
            CPage_::$menu_[0]['submit'] =false;
            CPage_::$menu_[0]['level'] =1;
            CPage_::$menu_[0]['active'] ='N';
        }#make_link_esc_for_message

        protected static function MakeMenuMainPage(){
            $i =0;
            if (isset($_SESSION[SESSION_LINK_ESC_VARIANT])){
                CPage_::$menu_[$i]['link'] = $_SESSION[SESSION_LINK_ESC_VARIANT];
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

            for($a=0; $a < count(CVariants_::$operations_); $a++){
                switch (CVariants_::$operations_[$a]['type_']){
                    case 1:
                       CPage_::$menu_[$i]['image'] ='Image/label_move_back.png';
                       break;
                    case 2:
                       CPage_::$menu_[$i]['image'] ='Image/label_move_forward.png';
                       break;
                    case 3:
                       CPage_::$menu_[$i]['image'] ='Image/label_ok_variant.png';
                       break;
                    case 4:
                       CPage_::$menu_[$i]['image'] ='Image/label_save_move.png';
                       break;
                    case 5:
                       CPage_::$menu_[$i]['image'] ='Image/label_del_variant.png';
                       break;
                    case 6:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_wq.png';
                       break;
                    case 7:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_wr.png';
                       break;
                    case 8:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_wb.png';
                       break;
                    case 9:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_wn.png';
                       break;
                    case 10:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_bq.png';
                       break;
                    case 11:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_br.png';
                       break;
                    case 12:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_bb.png';
                       break;
                    case 13:
                       CPage_::$menu_[$i]['image'] ='Image/Choose_bn.png';
                       break;
                }#switch
                CPage_::$menu_[$i]['link'] = CVariants_::$operations_[$a]['link_'];
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
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
                if (!isset($_GET['id']) || !ctype_digit($_GET['id']))
                    throw new Exception('Номер партии указан неверно.');
                $id_game=$_GET['id'];

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
                         '<DIV id="text_header_" style="white-space:nowrap">'.
                         '  Вариант продолжения партии №'.$id_game.
                         '</DIV>';

#Получение информации - партия завершена
                $s ='select result_ from TGames_ where (id_='.$id_game.')';
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('При чтении информации о партии произошла ошибка.');
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception('При чтении информации о партии произошла ошибка.');
                if (!is_null($row_['result_'])){
                    CVariants_::make_link_esc_for_error($id_game);
                    throw new Exception('Партия завершена.');
                }
                mysql_free_result($cursor_); $cursor_=false;

#Получаю id игроков
                $w_id_ =0; $b_id_ =0;
                CGames_::getIDs($id_game,$w_id_,$b_id_);

#Восстанавливаю последнюю позицию
                $g =new CRuleGame();
                $g->id_=$id_game;
                $g->lastPosition();

                if (!(($w_id_ ==$_SESSION[SESSION_ID_]) && !$g->lastMoveIsWhite_) &&
                    !(($b_id_ ==$_SESSION[SESSION_ID_]) && $g->lastMoveIsWhite_)){
                        CVariants_::make_link_esc_for_error($id_game);
                        throw new Exception('Варианты продолжения доступны только соперникам, играющим партию, во время их хода.');
                }

                $cell1_ =''; $cell2_ ='';
                $make_move=true;
#Удаление варианта
#--------------------------------------------------------------------------------------------------------------------
                if ($_GET['link_']=='question_accept_variant'){
                    if (!isset($_GET['id_variant']) || !ctype_digit($_GET['id_variant']))
                      throw new Exception('Вариант указан неверно.');
                    CVariants_::outQuestionAcceptVariant($id_game,$_GET['id_variant'],$header_);
                    return;
#Входящий вариант
#--------------------------------------------------------------------------------------------------------------------
                }else if ($_GET['link_']=='in_variant'){
                    if (!isset($_GET['id_variant']) || !ctype_digit($_GET['id_variant']))
                      throw new Exception('Номер варианта указан неверно.');
                    $v=$_GET['id_variant'];
                    CVariants_::$active_variant =array();
                    CVariants_::get_moves_in_variant($v);
                    if (isset($_GET['num_']) && ctype_digit($_GET['id_variant'])){
                        $num_ =$_GET['num_']; $i =$num_-CVariants_::$active_variant[0]['num_'];
                        if (($num_ <1) || !isset(CVariants_::$active_variant[$i]) ||
                            ($num_ != CVariants_::$active_variant[$i]['num_']))
                          throw new Exception('Номер хода указан неверно.');
                        if (!isset($_GET['color']) || (($_GET['color'] !='black') && ($_GET['color'] !='white')) ||
                            !isset(CVariants_::$active_variant[$i][($_GET['color'] =='black' ? 'BMoveCell1_' : 'WMoveCell1_')]))
                          throw new Exception('Цвет указан неверно.');

                        CVariants_::$current_move_ =$num_;
                        CVariants_::$current_move_is_white_=($_GET['color'] =='white');
                    }else{
                        CVariants_::$current_move_ =CVariants_::$active_variant[0]['num_'];
                        CVariants_::$current_move_is_white_ = isset(CVariants_::$active_variant[0]['WMoveCell1_']);
                    }
                    CVariants_::VariantPosition($g,CVariants_::$active_variant,CVariants_::$current_move_,CVariants_::$current_move_is_white_);
                    $make_move =false;
#Удаление варианта
#--------------------------------------------------------------------------------------------------------------------
                }else if ($_GET['link_']=='dell_variant'){
                    if (!isset($_GET['num_variant']) || !ctype_digit($_GET['num_variant']) ||
                        !isset($_SESSION['variants']) || !isset($_SESSION['variants'][$id_game]) ||
                        ($_GET['num_variant'] < 1) || ($_GET['num_variant'] > count($_SESSION['variants'][$id_game])))
                      throw new Exception('Номер варианта указан неверно.');
                    $v =$_GET['num_variant'];
                    CVariants_::outQuestionDellVariant($id_game,$v,$header_);
                    return;
#Создание варианта
#--------------------------------------------------------------------------------------------------------------------
                }else if ($_GET['link_']=='make_variant'){
                    if (!isset($_SESSION['variants'])) $_SESSION['variants'] =array();
                    if (!isset($_SESSION['variants'][$id_game])) $_SESSION['variants'][$id_game]=array();
                    $v =-1;
                    for ($i =0; $i < count($_SESSION['variants'][$id_game]); $i++)
                      if (count($_SESSION['variants'][$id_game][$i])==0){
                        $v =$i;
                        break;
                      }
                    if ($v ==-1){
                      $v =count($_SESSION['variants'][$id_game]);
                      $_SESSION['variants'][$id_game][$v]=array();
                    }
                    CVariants_::$active_variant =$_SESSION['variants'][$id_game][$v];
#Исходяший вариант
#--------------------------------------------------------------------------------------------------------------------
                }else if ($_GET['link_']=='variant'){
                    if (!isset($_GET['num_variant']) || !ctype_digit($_GET['num_variant']) ||
                        !isset($_SESSION['variants']) || !isset($_SESSION['variants'][$id_game]) ||
                        ($_GET['num_variant'] < 1) || ($_GET['num_variant'] > count($_SESSION['variants'][$id_game])))
                      throw new Exception('Номер варианта указан неверно.');
                    $v =$_GET['num_variant']-1;
                    CVariants_::$active_variant =$_SESSION['variants'][$id_game][$v];
                    if (count(CVariants_::$active_variant) > 0){
                        CVariants_::VariantPosition($g,CVariants_::$active_variant);
                        $i =count(CVariants_::$active_variant)-1;
                        CVariants_::$current_move_ =CVariants_::$active_variant[$i]['num_'];
                        CVariants_::$current_move_is_white_ = !isset(CVariants_::$active_variant[$i]['BMoveCell1_']);
                    }
                    if (isset($_GET['sel_1']) && $g->checkFirstClick($_GET['sel_1'])){
                      $cell1_ =$_GET['sel_1'];
                      if (isset($_GET['sel_2']) && $g->checkMove($cell1_,$_GET['sel_2']))
                         $cell2_ =$_GET['sel_2'];
                    }
                    if (isset($_GET['click_cell'])){
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
#Сохранение хода
#--------------------------------------------------------------------------------------------------------------------
                }else if ($_GET['link_']=='save_move_variant'){
#Проверяю правильность указания варианта
                    if (!isset($_GET['num_variant']) || !ctype_digit($_GET['num_variant']) ||
                        !isset($_SESSION['variants']) || !isset($_SESSION['variants'][$id_game]) ||
                        ($_GET['num_variant'] < 1) || ($_GET['num_variant'] > count($_SESSION['variants'][$id_game])))
                      throw new Exception('Номер варианта указан неверно.');
                    $v =$_GET['num_variant']-1;
#Получаю последнюю позицию
                    if (count($_SESSION['variants'][$id_game][$v]) > 0)
                        CVariants_::VariantPosition($g,$_SESSION['variants'][$id_game][$v]);
#Проверяю правильность хода
                    if (isset($_GET['sel_1']) && $g->checkFirstClick($_GET['sel_1'])){
                      $cell1_ =$_GET['sel_1'];
                      if (isset($_GET['sel_2']) && $g->checkMove($cell1_,$_GET['sel_2']))
                         $cell2_ =$_GET['sel_2'];
                    }
                    if (($cell1_ == '') || ($cell2_ == ''))
                      throw new Exception('Ход указан неверно.');
#Определяю фигуру, в которую превращается пешка
                    $piece_ ='';
                    if ((($g->board_[$cell1_{0}][$cell1_{1}] == 'wp') && ($cell2_{1}==8)) ||
                        (($g->board_[$cell1_{0}][$cell1_{1}] == 'bp') && ($cell2_{1}==1)))
                      if (isset($_GET['piece']) && (($_GET['piece'] =='q') || ($_GET['piece'] =='r') ||
                          ($_GET['piece'] =='b') || ($_GET['piece'] =='n')))
                            $piece_ =$_GET['piece'];
                          else
                            throw new Exception('Фигура, в которую должна превратиться пешка, указанна неверно.');
#Проверяю правильность первого хода
                    if (count($_SESSION['variants'][$id_game][$v]) == 0)
                      for ($i=0; $i < count($_SESSION['variants'][$id_game]); $i++)
                         if (($i != $v) && (count($_SESSION['variants'][$id_game][$i]) > 0)){
                             if ($w_id_ ==$_SESSION[SESSION_ID_]){
                                if (($_SESSION['variants'][$id_game][$i][0]['WMoveCell1_'] != $cell1_) ||
                                    ($_SESSION['variants'][$id_game][$i][0]['WMoveCell2_'] != $cell2_) ||
                                    ($_SESSION['variants'][$id_game][$i][0]['WPiece_'] != $piece_)){
                                 CVariants_::make_link_esc_for_message($id_game,$v);
                                 throw new Exception('Все варианты должны начинаться с одного хода');
                                }
                             }else{
                                if (($_SESSION['variants'][$id_game][$i][0]['BMoveCell1_'] != $cell1_) ||
                                    ($_SESSION['variants'][$id_game][$i][0]['BMoveCell2_'] != $cell2_) ||
                                    ($_SESSION['variants'][$id_game][$i][0]['BPiece_'] != $piece_)){
                                 CVariants_::make_link_esc_for_message($id_game,$v);
                                 throw new Exception('Все варианты должны начинаться с одного хода');
                                }
                             }
                         }
#Совершаю ход
                    if (!$g->lastMoveIsWhite_){
                      $g->table_moves[]['num'] =$g->num_;
                      $j =count($g->table_moves)-1;
                      $g->table_moves[$j]['w_isCheck']='';
                      $g->move($cell1_,$cell2_,($piece_ !='') ? 'w'.$piece_ : '',true);
                      if ($g->isEndGame('b') != 0){
                         CVariants_::make_link_esc_for_message($id_game,$v);
                         throw new Exception('Вариант не должен завершать партию.');
                      }
                      $i =count($_SESSION['variants'][$id_game][$v]);
                      $_SESSION['variants'][$id_game][$v][$i]['num_']=$g->num_;
                      $_SESSION['variants'][$id_game][$v][$i]['WMoveCell1_'] =$cell1_;
                      $_SESSION['variants'][$id_game][$v][$i]['WMoveCell2_'] =$cell2_;
                      $_SESSION['variants'][$id_game][$v][$i]['WPiece_']     =$piece_;
                      $_SESSION['variants'][$id_game][$v][$i]['w_isCheck_'] ='';
                    }else{
                      $j =count($g->table_moves)-1;
                      $g->table_moves[$j]['b_isCheck']='';
                      $g->move($cell1_,$cell2_,($piece_ !='') ? 'b'.$piece_ : '',true);
                      if ($g->isEndGame('w')){
                        CVariants_::make_link_esc_for_message($id_game,$v);
                        throw new Exception('Вариант не должен завершать партию.');
                      }
                      $i =count($_SESSION['variants'][$id_game][$v]);
                      if ($i > 0) $i--; else $_SESSION['variants'][$id_game][$v][$i]['num_']=$g->num_;
                      $_SESSION['variants'][$id_game][$v][$i]['BMoveCell1_'] =$cell1_;
                      $_SESSION['variants'][$id_game][$v][$i]['BMoveCell2_'] =$cell2_;
                      $_SESSION['variants'][$id_game][$v][$i]['BPiece_']     =$piece_;
                      $_SESSION['variants'][$id_game][$v][$i]['b_isCheck_'] ='';
                    }
                    $j =count($g->table_moves)-1;
                    if (!$g->lastMoveIsWhite_){
                      if ($g->isCheckCell('b',$g->black_king_last_position)){
                        $g->table_moves[$j]['w_isCheck'] ='+';
                         $_SESSION['variants'][$id_game][$v][$i]['w_isCheck_'] ='Y';
                      }
                    }else
                      if ($g->isCheckCell('w',$g->white_king_last_position)){
                        $g->table_moves[$j]['b_isCheck'] ='+';
                        $_SESSION['variants'][$id_game][$v][$i]['b_isCheck_'] ='Y';
                      }
                    $g->num_++;
                    $g->lastMoveIsWhite_ =!$g->lastMoveIsWhite_;
                    $cell1_ =''; $cell2_ =''; $piece_='';
                    CVariants_::$active_variant =$_SESSION['variants'][$id_game][$v];
                    $i =count(CVariants_::$active_variant)-1;
                    CVariants_::$current_move_ =CVariants_::$active_variant[$i]['num_'];
                    CVariants_::$current_move_is_white_ = !isset(CVariants_::$active_variant[$i]['BMoveCell1_']);
                }
#Формирование списка операции
#-------------------------------------------------------------------------------------------------------------
                $n =-1;
                if (($cell1_ != '') && ($cell2_ != '')){
                    if (($g->board_[$cell1_{0}][$cell1_{1}] == 'wp') && ($cell2_{1}==8)){
                        CVariants_::$operations_[++$n]['type_'] =6;
                        CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=save_move_variant&id='.$id_game.
                                                               '&sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=q'.
                                                               '&num_variant='.($v+1);
                        CVariants_::$operations_[++$n]['type_'] =7;
                        CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=save_move_variant&id='.$id_game.
                                                               '&sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=r'.
                                                               '&num_variant='.($v+1);
                        CVariants_::$operations_[++$n]['type_'] =8;
                        CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=save_move_variant&id='.$id_game.
                                                               '&sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=b'.
                                                               '&num_variant='.($v+1);
                        CVariants_::$operations_[++$n]['type_'] =9;
                        CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=save_move_variant&id='.$id_game.
                                                               '&sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=n'.
                                                               '&num_variant='.($v+1);
                    }else if (($g->board_[$cell1_{0}][$cell1_{1}] == 'bp') && ($cell2_{1}==1)){
                        CVariants_::$operations_[++$n]['type_'] =10;
                        CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=save_move_variant&id='.$id_game.
                                                               '&sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=q'.
                                                               '&num_variant='.($v+1);
                        CVariants_::$operations_[++$n]['type_'] =11;
                        CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=save_move_variant&id='.$id_game.
                                                               '&sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=r'.
                                                               '&num_variant='.($v+1);
                        CVariants_::$operations_[++$n]['type_'] =12;
                        CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=save_move_variant&id='.$id_game.
                                                               '&sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=b'.
                                                               '&num_variant='.($v+1);
                        CVariants_::$operations_[++$n]['type_'] =13;
                        CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=save_move_variant&id='.$id_game.
                                                               '&sel_1='.$cell1_.'&sel_2='.$cell2_.'&piece=n'.
                                                               '&num_variant='.($v+1);
                    }else{
                        CVariants_::$operations_[++$n]['type_'] =4;
                        CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=save_move_variant&id='.$id_game.
                                                               '&sel_1='.$cell1_.'&sel_2='.$cell2_.
                                                               '&num_variant='.($v+1);
                    }
                }
                if ($make_move){
#Удаление варианта
                    CVariants_::$operations_[++$n]['type_'] =5;
                    CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=dell_variant&id='.$id_game.
                                                           '&num_variant='.($v+1);
                }else{
                    if ((count(CVariants_::$active_variant) > 0) &&
                        ((CVariants_::$current_move_ > CVariants_::$active_variant[0]['num_']) ||
                         ((CVariants_::$current_move_ = CVariants_::$active_variant[0]['num_']) &&
                            isset(CVariants_::$active_variant[0]['WMoveCell1_']) &&
                            !CVariants_::$current_move_is_white_))){
                      CVariants_::$operations_[++$n]['type_'] =1;
                      CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=in_variant&id='.$id_game.
                                                             '&id_variant='.$v.
                                                             (CVariants_::$current_move_is_white_
                                                               ? '&num_='.(CVariants_::$current_move_-1).'&color=black'
                                                               : '&num_='.CVariants_::$current_move_.'&color=white');
                    }
                    $i =count(CVariants_::$active_variant);
                    if (($i > 0) &&
                        ((CVariants_::$current_move_ < CVariants_::$active_variant[$i-1]['num_']) ||
                         ((CVariants_::$current_move_ = CVariants_::$active_variant[$i-1]['num_']) &&
                          CVariants_::$current_move_is_white_ &&
                          isset(CVariants_::$active_variant[0]['BMoveCell1_'])))){
                      CVariants_::$operations_[++$n]['type_'] =2;
                      CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=in_variant&id='.$id_game.
                                                             '&id_variant='.$v.
                                                             (CVariants_::$current_move_is_white_
                                                               ? '&num_='.CVariants_::$current_move_.'&color=black'
                                                               : '&num_='.(CVariants_::$current_move_+1).'&color=white');
                    }
                    CVariants_::$operations_[++$n]['type_'] =3;
                    CVariants_::$operations_[$n]['link_'] ='MainPage.php?link_=question_accept_variant&id='.$id_game.
                                                           '&id_variant='.$v;
                }

                $body_ =CVariants_::BodyBoard($g,$v,$make_move,$cell1_,$cell2_);

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
                if ($connect_)const_::Disconnect_();

                CPage_::$header_ =$header_;
                CVariants_::MakeMenuMainPage();
                CPage_::$body_ =$body_;
                CPage_::MakePage();

            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
                CPage_::PageErr();
            }#try
        }#MakePage

#В игре $g делает $last_move ходов варианта $v
        protected static function VariantPosition($g,$v,$last_move=0,$is_white_last_move=true){
#Проверяю очередь хода
            if ((!$g->lastMoveIsWhite_ && !isset($v[0]['WMoveCell1_'])) ||
                ($g->lastMoveIsWhite_ && isset($v[0]['WMoveCell1_'])))
                throw new Exception('В варианте несоблюдена очередность хода');
#			Games::$first_move_variant =$this->num_;
#			Games::$is_white_first_move_variant=!$this->lastMoveIsWhite_;

            $not_move_black_ =false; $b=null;
            for ($i=0; $i < count($v); $i++){
                if (($v[$i]['num_'] !=$g->num_) || $not_move_black_)
                    throw new Exception('В варианте несоблюдена нумерация ходов');
                if (!$g->lastMoveIsWhite_){
                    $wcell1_ = $v[$i]['WMoveCell1_'];
                    $wcell2_ = $v[$i]['WMoveCell2_'];
                    $wpiece_ = (($v[$i]['WPiece_'] != '') ? 'w'.$v[$i]['WPiece_'] : '');
                    $g->table_moves[]['num'] =$g->num_; $j =count($g->table_moves)-1;
                    $g->move($wcell1_,$wcell2_,$wpiece_,true);
                    if ($v[$i]['w_isCheck_'] == 'Y')
                        $g->table_moves[$j]['w_isCheck']='+';
                      else
                        $g->table_moves[$j]['w_isCheck']='';
                    $g->lastMoveIsWhite_ =true;

                    if (($last_move == $g->num_) && $is_white_last_move)
                      $b =$g->board_;
                }
                if (isset($v[$i]['BMoveCell1_'])){
                    $bcell1_ = $v[$i]['BMoveCell1_'];
                    $bcell2_ = $v[$i]['BMoveCell2_'];
                    $bpiece_ = (($v[$i]['BPiece_'] != '') ? 'b'.$bpiece_ : '');
                    $g->move($bcell1_,$bcell2_,$bpiece_ ,true);
                    $j =count($g->table_moves)-1;
                    if ($v[$i]['b_isCheck_'] == 'Y')
                        $g->table_moves[$j]['b_isCheck']='+';
                      else
                        $g->table_moves[$j]['b_isCheck']='';
                    $g->lastMoveIsWhite_ =false;

                    if (($last_move == $g->num_) && !$is_white_last_move)
                      $b =$g->board_;

                    $g->num_++;
                }else $not_move_black_ =true;
            }#for
            if (!is_null($b)) $g->board_ =$b;
        }#VariantPosition

       public static function get_moves_in_variant($id_variant){
          $s ='select A.num_,A.WMoveCell1_,A.WMoveCell2_,A.WPiece_,A.w_isCheck_,A.BMoveCell1_,A.BMoveCell2_,A.BPiece_,A.b_isCheck_'.
                 '  from TVariants_ B, TMovesVariant_ A'.
                 '  where (B.id_='.$id_variant.') and (A.idVariant_=B.id_)';
          $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При получении информации о варианте произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
                $i =count(CVariants_::$active_variant);
                CVariants_::$active_variant[$i]['num_'] =$row_['num_'];
                if (!is_null($row_['WMoveCell1_'])){
                    CVariants_::$active_variant[$i]['WMoveCell1_'] =$row_['WMoveCell1_'];
                    CVariants_::$active_variant[$i]['WMoveCell2_'] =$row_['WMoveCell2_'];
                    CVariants_::$active_variant[$i]['WPiece_']     =$row_['WPiece_'];
                    CVariants_::$active_variant[$i]['w_isCheck_']  =$row_['w_isCheck_'];
                }
                if (!is_null($row_['BMoveCell1_'])){
                    CVariants_::$active_variant[$i]['BMoveCell1_'] =$row_['BMoveCell1_'];
                    CVariants_::$active_variant[$i]['BMoveCell2_'] =$row_['BMoveCell2_'];
                    CVariants_::$active_variant[$i]['BPiece_']     =$row_['BPiece_'];
                    CVariants_::$active_variant[$i]['b_isCheck_']  =$row_['b_isCheck_'];
                }
          } #while
          mysql_free_result($cursor_);

          if (count(CVariants_::$active_variant) ==0) throw new Exception('Вариант не найден.');
       }#get_moves_in_variant


        protected static function BodyBoard(CRuleGame $game_,$num_variant,$mayClick_=false,$cell1_='',$cell2_=''){
            $login_white_ =''; $login_black_ ='';
            CGames_::getLogins($game_->id_,$login_white_,$login_black_);
            $otpusk_white =''; $otpusk_black ='';
            CGames_::getInfoOtpusk($game_->id_,$otpusk_white,$otpusk_black);
            $clock_white_ =''; $clock_black_ =''; $result_game_ ='';
            CGames_::getClocks($game_->id_,$clock_white_,$clock_black_,$result_game_);
#isRotate=true - черные внизу, false черные вверху
            $isRotate =CGames_::getInfoRotate($game_->id_);

            $k_ =CUsers_::Read_scale_board();
            $l_=round(68*$k_);  settype($l_,"integer"); $w_  ='width="'.$l_.'"'; $h_ ='height="'.$l_.'"';
            $l_=round(19*$k_);  settype($l_,"integer"); $w_l ='width="'.$l_.'"';
            $l_=round(18*$k_);  settype($l_,"integer"); $w_r ='width="'.$l_.'"';
            $l_=round(20*$k_);  settype($l_,"integer"); $h_t  ='height="'.$l_.'"';

            $s_ ='MainPage.php?link_=variant&id='.$game_->id_.'&num_variant='.($num_variant+1);

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

            if ($isRotate){
                $result_ .='<TR><TD colspan="10">'.
                           '  <DIV>'.
                           '    <A href="AboutGamer.php?login='.urlencode($login_white_).'">'.htmlspecialchars($login_white_).'</A>'.
                           '  </DIV>'.
                           '</TD></TR>'.
                           '<TR><TD colspan="10">'."\n".
                           '       <DIV><SPAN id="clock_white">'.htmlspecialchars($clock_white_).'</SPAN>';
                if ($otpusk_white !=='')
                    $result_ .='&nbsp;отпуск, осталось: '.htmlspecialchars($otpusk_white);
                $result_ .="\n".
                           '       </DIV>'."\n".
                           '</TD></TR>'."\n";
            }else{
                $result_ .='<TR><TD colspan="10">'.
                           '  <DIV>'.
                           '    <A href="AboutGamer.php?login='.urlencode($login_black_).'">'.htmlspecialchars($login_black_).'</A></TD></TR>'.
                           '  </DIV>'.
                           '<TR><TD colspan="10" style="text-align: center">'."\n".
                           '       <DIV><SPAN id="clock_black">'.htmlspecialchars($clock_black_).'</SPAN>';
                if ($otpusk_black !=='')
                    $result_ .='&nbsp;отпуск, осталось: '.htmlspecialchars($otpusk_black);
                $result_ .="\n".
                           '       </DIV>'."\n".
                           '</TD></TR>'."\n";
            }
//верхняя часть доски
            $result_ .='<TR><TD><IMG '.$w_l.' '.$h_t.' style="border:none; vertical-align: bottom" src="Image/board_tl.jpg"/></TD>'."\n";
            for($i_=1; $i_<9; $i_++)
                $result_ .='<TD><IMG '.$w_.' '.$h_t.' style="border:none; vertical-align: bottom" src="Image/board_top.jpg"/></TD>'."\n";
            $result_ .='<TD><IMG '.$w_r.' '.$h_t.' style="border:none; vertical-align: bottom" src="Image/board_tr.jpg"/></TD>'."\n".
                       '</TR>'."\n";
#доска
            for($i_=($isRotate ? 1 : 8); $isRotate ? $i_<=8 : $i_>=1; ($isRotate ? $i_++ : $i_--)){
                $result_ .='<TR>'."\n".
                           '<TD><IMG '.$w_l.' '.$h_.' style="border:none; vertical-align: top" src="Image/board_'.$i_.'.jpg"/></TD>'."\n";
                for($j_=($isRotate ? 'H' : 'A'); $isRotate ? $j_>='A' : $j_<='H'; $j_=($isRotate ? chr(ord($j_)-1) : chr(ord($j_)+1))){
                    $result_ .='<TD>';
                    if ($mayClick_)
                       $result_.='<A href="'.$s_.'&click_cell='.$j_.$i_.'">';
                    $result_ .='<IMG id="'.$j_.$i_.'" '.$w_.' '.$h_;
                    $result_ .=' style="border:none; vertical-align: top; cursor:pointer" src="Image/'.$game_->board_[$j_][$i_].$game_->getColorBoard($j_.$i_).
                               ((($cell1_ == ($j_.$i_)) || ($cell2_ == ($j_.$i_))) ? 's' : '').'.jpg"/>';
                    if ($mayClick_) $result_ .='</A>';
                    $result_ .='</TD>';
                }//for $j_
                $result_ .='<TD><IMG  '.$w_r.' '.$h_.'  style="border:none; vertical-align: top" src="Image/board_right.jpg"/></TD>'."\n".
                           '</TR>';
            }//for $i_
//нижняя часть доски
            $result_ .='<TR><TD><IMG  '.$w_l.' '.$h_t.' style="border:none; vertical-align: top" src="Image/board_bl.jpg"/></TD>'."\n";
            for($i_=($isRotate ? 'h' : 'a'); $isRotate ? $i_>='a' : $i_<='h'; $i_ =($isRotate ? chr(ord($i_)-1) : chr(ord($i_)+1)))
                $result_ .='<TD><IMG  '.$w_.' '.$h_t.' style="border:none; vertical-align: top" src="Image/board_'.$i_.'.jpg"/></TD>'."\n";
            $result_ .='<TD><IMG  '.$w_r.' '.$h_t.' style="border:none; vertical-align: top" src="Image/board_br.jpg"/></TD>'."\n".
                       '</TR>'."\n";

            if ($isRotate){
                $result_ .='<TR><TD colspan="10">'."\n".
                           '  <DIV><SPAN id="clock_black">'.htmlspecialchars($clock_black_).'</SPAN>';
                if ($otpusk_black !=='')
                    $result_ .='&nbsp;отпуск, осталось: '.htmlspecialchars($otpusk_black);
                $result_ .="\n".
                           '       </DIV>'."\n".
                           '</TD></TR>'."\n".
                           '<TR><TD colspan="10"><A href="AboutGamer.php?login='.urlencode($login_black_).'">'.htmlspecialchars($login_black_).'</A></TD></TR>';
            }else{
                $result_ .='<TR><TD colspan="10">'.
                           '       <DIV><SPAN id="clock_white">'.htmlspecialchars($clock_white_).'</SPAN>';
                if ($otpusk_white !=='')
                    $result_ .='&nbsp;отпуск, осталось: '.htmlspecialchars($otpusk_white);
                $result_ .="\n".
                           '       </DIV>'."\n".
                           '</TD></TR>'."\n".
                           '<TR><TD colspan="10"><A href="AboutGamer.php?login='.urlencode($login_white_).'">'.htmlspecialchars($login_white_).'</A></TD></TR>';
            }

            $result_ .='</TABLE>'."\n".
                            '</DIV>'."\n".
                            '</TD>'."\n";

            $result_ .='<TD style="vertical-align: top">'."\n".
                       '  <DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                       '            font-size: 12pt; font-style: normal; font-weight: normal;'.
                       '            color : black">'.
                             CVariants_::Table_moves_($game_).
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
            $s3_v ='"border-top: 1px solid black; border-left: none; border-right: 1px solid black; border-bottom: none; white-space:nowrap; color:white; font-style:italic"';
            $s4_v ='"border-top: 1px solid black; border-left: none; border-right: 2px solid black; border-bottom: none; white-space:nowrap; color:white; font-style:italic"';
            $s5_v ='"border-top: 1px solid black; border-left: none; border-right: none; border-bottom: none; white-space:nowrap; color:white; font-style:italic"';
            $s3_c ='"border-top: 1px solid black; border-left: none; border-right: 1px solid black; border-bottom: none; white-space:nowrap; color:white; font-weight: bold"';
            $s4_c ='"border-top: 1px solid black; border-left: none; border-right: 2px solid black; border-bottom: none; white-space:nowrap; color:white; font-weight: bold"';
            $s5_c ='"border-top: 1px solid black; border-left: none; border-right: none; border-bottom: none; white-space:nowrap; color:white; font-weight: bold"';

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
                        $f_m_v     =(count(CVariants_::$active_variant) > 0) ? CVariants_::$active_variant[0]['num_'] : -1;  # номер первого хода варианта
                        $c_m_v     =CVariants_::$current_move_;           # номер текущего хода варианта
                        $t_m_n     =$game_->table_moves[$i_]['num'];      # номер хода строки $i_ таблицы ходов
                        $i_w_c_m_v =CVariants_::$current_move_is_white_;  # текущий ход  варианта сделали белые
                        $i_w_f_m_v  =(count(CVariants_::$active_variant) > 0) && isset(CVariants_::$active_variant[0]['WMoveCell1_']); # первый ход варианта сделали белые
                        if (($f_m_v ==-1) or ($f_m_v > $t_m_n)){
                            $s0=$s3_; $s3 =$s3_; $s4 =$s4_; $s5 =$s5_;
                        }else if ($c_m_v ==$t_m_n){
                            if ($i_w_c_m_v){$s0=$s3_v; $s3 =$s3_c; $s4 =$s4_v; $s5 =$s5_v;}
                                else{
                                    if (($f_m_v ==$t_m_n) && !$i_w_f_m_v){
                                        $s0 =$s3_; $s3 =$s3_;
                                    }else{
                                        $s0 =$s3_v; $s3 =$s3_v;
                                    }
                                    $s4 =$s4_c; $s5 =$s5_c;
                                }
                        }else if ($f_m_v ==$t_m_n){
                            if ($i_w_f_m_v){$s0=$s3_v; $s3 =$s3_v;}else{$s0=$s3_; $s3 =$s3_;}
                            $s4 =$s4_v; $s5 =$s5_v;
                        }else{
                            $s0=$s3_v; $s3 =$s3_v; $s4 =$s4_v; $s5 =$s5_v;
                        }

                        $result_ .='<TD style='.$s0.'>'.$game_->table_moves[$i_]['num'].'</TD>'."\n";
                        $s_ ='';
                        if ($game_->table_moves[$i_]['wpiece'] !='') $s_='<IMG style="border:none" src="Image/fw'.$game_->table_moves[$i_]['wpiece'].($s3 !=$s3_ ? '_v' : '').'.png"/>';
                        $s_ .=$game_->table_moves[$i_]['wmove'];
                        if ($game_->table_moves[$i_]['w_to_piece'] !='') $s_.='<IMG style="border:none" src="Image/fw'.$game_->table_moves[$i_]['w_to_piece'].($s3 !=$s3_ ? '_v' : '').'.png"/>';
                        if ($game_->table_moves[$i_]['w_isCheck'] !='') $s_.=$game_->table_moves[$i_]['w_isCheck'];
                        $result_ .='<TD style='.$s3.' id ="move_white_'.($r_+($row_count*($c_-1))).'">'.$s_.'</TD>'."\n";
                        if (isset($game_->table_moves[$i_]['bmove'])){
                            $s_ ='';
                            if ($game_->table_moves[$i_]['bpiece'] !='') $s_='<IMG style="border:none" src="Image/fw'.$game_->table_moves[$i_]['bpiece'].($s4 !=$s4_ ? '_v' : '').'.png"/>';
                            $s_ .=$game_->table_moves[$i_]['bmove'];
                            if ($game_->table_moves[$i_]['b_to_piece'] !='') $s_.='<IMG style="border:none" src="Image/fw'.$game_->table_moves[$i_]['b_to_piece'].($s4 !=$s4_ ? '_v' : '').'.png"/>';
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

    }#CVariants_
?>
