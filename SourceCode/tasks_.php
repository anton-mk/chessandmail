<?php
    require_once('const_.php');
    require_once('pageMakeTask.php');
    require_once('rule_game_.php');
    require_once('Users.php');
    require_once('lib/mylib.php');

#инициализация сессии
    session_name(NAME_SESSION_);
    session_start();

    class CListTasks extends CPartOfQuery_{
       public $records_ =array();
       public $type_=0;

       public function __construct($t_){
            $this->type_ =$t_;
            parent::__construct(const_::$connect_);
            $this->cRecordOnPage =4;
       }#__construct

/* $type_: 0 - мат в два хода, 1 - мат в три хода, 2 - мат в четыре хода, 3 - мат в пять ходов
           4 - как бы ва сыграли, 5 - ваши задачи, 7 - задачи на проверку */
       public static function get_link($type_,$page_ =0){
            $result_ ='tasks_.php?type_='.$type_;
            if ($page_ != 0)
              $result_ .='&page='.$page_;
            return $result_;
       }#get_link

       protected function str_select_for_countPage(){
            $result_ ='select count(*) as count_'.
                      ' from TTasks_'.
                      ' where ';
            switch ($this->type_){
                case 0 : $result_ .='(check_task_ =\'Y\') and (result_ =1)';
                         break;
                case 1 : $result_ .='(check_task_ =\'Y\') and (result_ =2)';
                         break;
                case 2 : $result_ .='(check_task_ =\'Y\') and (result_ =3)';
                         break;
                case 3 : $result_ .='(check_task_ =\'Y\') and (result_ =4)';
                         break;
                case 4 : $result_ .='(check_task_ =\'Y\') and ((result_ =5) or (result_ =6))';
                         break;
                case 5 : $result_ .='(id_gamer_maker_ ='.$_SESSION[SESSION_ID_].')';
                         break;
                case 7 : $result_ .='(check_task_ =\'N\')';
                         break;
            }#switch
            return $result_;
       } #str_select_for_countPage

       protected function str_select_for_getRecords(){
            $result_ ='select A.id_, A.result_, A.start_, A.answer_, A.check_task_, A.id_gamer_maker_, B.login_'.
                      ' from TTasks_ A, TGamers_ B'.
                      ' where (A.id_gamer_maker_=B.id_)';
            switch ($this->type_){
                case 0 : $result_ .=' and (check_task_ =\'Y\') and (result_ =1)';
                         break;
                case 1 : $result_ .=' and (check_task_ =\'Y\') and (result_ =2)';
                         break;
                case 2 : $result_ .=' and (check_task_ =\'Y\') and (result_ =3)';
                         break;
                case 3 : $result_ .=' and (check_task_ =\'Y\') and (result_ =4)';
                         break;
                case 4 : $result_ .=' and (check_task_ =\'Y\') and ((result_ =5) or (result_ =6))';
                         break;
                case 5 : $result_ .=' and (id_gamer_maker_ ='.$_SESSION[SESSION_ID_].')';
                         break;
                case 7 : $result_ .=' and (check_task_ =\'N\')';
                         break;
            }#switch
            $result_ .=' order by A.id_';
            return $result_;
        }#str_select_for_countPage

       public function get_records($page_){
            try{
                if (!$this->getRecords(false,$page_,array('id_','result_','start_','answer_','check_task_',
                                                          'id_gamer_maker_','login_')))
                    throw new Exception();
                for($i=0; $i<count($this->listRecords); $i++){
                    $this->records_[$i]['id_']             =$this->listRecords[$i]['id_'];
                    $this->records_[$i]['result_']         =$this->listRecords[$i]['result_'];
                    $this->records_[$i]['start_']          =$this->listRecords[$i]['start_'];
                    $this->records_[$i]['answer_']         =convert_cyr_string($this->listRecords[$i]['answer_'],'d','w');
                    $this->records_[$i]['check_task_']     =$this->listRecords[$i]['check_task_'];
                    $this->records_[$i]['id_gamer_maker_'] =$this->listRecords[$i]['id_gamer_maker_'];
                    $this->records_[$i]['login_']          =convert_cyr_string($this->listRecords[$i]['login_'],'d','w');
                } #for
            }catch(Exception $e){
#                throw new Exception(mysql_error());
                throw new Exception('При чтении информации произошла ошибка.');
            }
       }#get_records

       public function out_records(){
            $result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                   'font-size: 12pt; color: black;'.
                                   'text-decoration: none; font-weight: normal">'."\n".
                      '   <TABLE style ="border: none; margin-left: 10px; margin-right: 10px" cellspacing="0" cellpadding="0">'."\n".
                      '      <COL span="1">'."\n";
            $m ='';
            if ($this->cCountPages > 1){
                $a =$this->getFirstVisibleNum($this->page_);
                $b =$this->getLastVisibleNum($this->page_);
                if ($a > 1) $m ='<A href="'.CListTasks::get_link($this->type_,$a-1).'">'.htmlspecialchars('<<').'</A>';
                for ($i=$a; $i <=$b; $i++){
                    if ($m != '') $m .='&nbsp;';
                    if ($this->page_ != $i) $m .='<A href="'.CListTasks::get_link($this->type_,$i).'">'.$i.'</A>'; else $m.=$i;
                }#for
                if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.CListTasks::get_link($this->type_,$b+1).'">'.htmlspecialchars('>>').'</A>';
                $result_ .='<TR><TD style="border: none; text-align: left">'.$m.'</TD></TR>'."\n";
            }
            $result_ .='<TR><TD>'."\n".
                       '<TABLE cellspacing="3">'."\n".
                       '     <COL span="2">'.
                       '     <TR>'.
                       '        <TD style="text-align: center; vertical-align: middle">';
            if (count($this->records_) > 0){
              CTasks_::clear_board();
              CTasks_::read_position_task($this->records_[0]['id_']);
              CTasks_::$start_  =$this->records_[0]['start_'];
              CTasks_::$k_board_ =0.5;
              $result_ .='<A href="tasks_.php?add=view_task_&task_='.$this->records_[0]['id_'].'">'.
                            CTasks_::out_board_().
                         '</A>';
             }else $result_ .='&nbsp;';
            $result_ .='        </TD>';

            $result_ .='        <TD style="text-align: center; vertical-align: middle">';
            if (count($this->records_) > 1){
              CTasks_::clear_board();
              CTasks_::read_position_task($this->records_[1]['id_']);
              CTasks_::$start_  =$this->records_[1]['start_'];
              CTasks_::$k_board_ =0.5;
              $result_ .='<A href="tasks_.php?add=view_task_&task_='.$this->records_[1]['id_'].'">'.
                            CTasks_::out_board_().
                         '</A>';
             }else $result_ .='&nbsp;';
            $result_ .='        </TD>';
            $result_ .='     </TR>';

            $result_ .='     <TR>'.
                       '        <TD style="text-align: center; vertical-align: middle">';
            if (count($this->records_) > 2){
              CTasks_::clear_board();
              CTasks_::read_position_task($this->records_[2]['id_']);
              CTasks_::$start_  =$this->records_[2]['start_'];
              CTasks_::$k_board_ =0.5;
              $result_ .='<A href="tasks_.php?add=view_task_&task_='.$this->records_[2]['id_'].'">'.
                            CTasks_::out_board_().
                         '</A>';
             }else $result_ .='&nbsp;';
            $result_ .='        </TD>';

            $result_ .='        <TD style="text-align: center; vertical-align: middle">';
            if (count($this->records_) > 3){
              CTasks_::clear_board();
              CTasks_::read_position_task($this->records_[3]['id_']);
              CTasks_::$start_  =$this->records_[3]['start_'];
              CTasks_::$k_board_ =0.5;
              $result_ .='<A href="tasks_.php?add=view_task_&task_='.$this->records_[3]['id_'].'">'.
                            CTasks_::out_board_().
                         '</A>';
             }else $result_ .='&nbsp;';
            $result_ .='        </TD>';
            $result_ .='     </TR>';
            $result_ .='</TABLE>'."\n".
                       '</TD></TR>'."\n";
            $result_ .='</TABLE></SPAN>';
            return $result_;
       }#out_records
    }#CListTasks

    class CTasks_{
      public static $board_ =array();
      public static $start_   ='W'; #W - ход белых, B - ход черных
      public static $k_board_ =1; #масштаб доски
      public static $result_  ='';
      public static $answer_  ='';
      public static $check_task_ ='';

      public static function MakeMenuForViewTask($link_esc,$link_answer='',$link_next_='',$link_prev_=''){
        $i=0;
        CPage_::$menu_[$i]['link'] = $link_esc;
        CPage_::$menu_[$i]['image'] ='Image/label_esc.png';
        CPage_::$menu_[$i]['submit'] =false;
        CPage_::$menu_[$i]['level'] =1;
        CPage_::$menu_[$i++]['active'] ='N';

        if ($link_answer !=''){
          CPage_::$menu_[$i]['link'] = $link_answer;
          CPage_::$menu_[$i]['image'] ='Image/label_answer.png';
          CPage_::$menu_[$i]['submit'] =false;
          CPage_::$menu_[$i]['level'] =1;
          CPage_::$menu_[$i++]['active'] ='N';
        }

        if ($link_prev_ !=''){
          CPage_::$menu_[$i]['link'] = $link_prev_;
          CPage_::$menu_[$i]['image'] ='Image/label_prev.png';
          CPage_::$menu_[$i]['submit'] =false;
          CPage_::$menu_[$i]['level'] =1;
          CPage_::$menu_[$i++]['active'] ='N';
        }

        if ($link_next_ !=''){
          CPage_::$menu_[$i]['link'] = $link_next_;
          CPage_::$menu_[$i]['image'] ='Image/label_next.png';
          CPage_::$menu_[$i]['submit'] =false;
          CPage_::$menu_[$i]['level'] =1;
          CPage_::$menu_[$i++]['active'] ='N';
        }
      }#MakeMenuForViewTask

      public static function MakeMenuForViewAnswer($link_esc,$link_next_='',$link_prev_=''){
        $i=0;
        CPage_::$menu_[$i]['link'] = $link_esc;
        CPage_::$menu_[$i]['image'] ='Image/label_esc.png';
        CPage_::$menu_[$i]['submit'] =false;
        CPage_::$menu_[$i]['level'] =1;
        CPage_::$menu_[$i++]['active'] ='N';

        if ($link_prev_ !=''){
          CPage_::$menu_[$i]['link'] = $link_prev_;
          CPage_::$menu_[$i]['image'] ='Image/label_prev.png';
          CPage_::$menu_[$i]['submit'] =false;
          CPage_::$menu_[$i]['level'] =1;
          CPage_::$menu_[$i++]['active'] ='N';
        }

        if ($link_next_ !=''){
          CPage_::$menu_[$i]['link'] = $link_next_;
          CPage_::$menu_[$i]['image'] ='Image/label_next.png';
          CPage_::$menu_[$i]['submit'] =false;
          CPage_::$menu_[$i]['level'] =1;
          CPage_::$menu_[$i++]['active'] ='N';
        }
      }#MakeMenuForViewAnswer

#$type_: 0 - мат в два хода, 1 - мат в три хода, 2 - мат в четыре хода, 3 - мат в пять ходов
#        4 - как бы ва сыграли, 5 - ваши задачи, 6 - создать задачу, 7 - задачи на проверку
      public static function MakeMenuMainPage($type_){
        if (isset($_SESSION[SESSION_ID_]))
          $i =CPage_::PositionMenu_('Задачи') +1;
         else{
           $i =0;
           CPage_::$menu_[$i]['link'] = '';
           CPage_::$menu_[$i]['image'] ='Image/label_tasks.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =1;
           CPage_::$menu_[$i++]['active'] ='Y';
         }

        CPage_::$menu_[$i]['link'] =($type_ ==0) ? '' : CListTasks::get_link(0);
        CPage_::$menu_[$i]['image'] ='Image/label_checkmate_two_move.png';
        CPage_::$menu_[$i]['submit'] =false;
        CPage_::$menu_[$i]['level'] =2;
        CPage_::$menu_[$i++]['active'] =($type_ ==0) ? 'Y' : 'N';

        CPage_::$menu_[$i]['link'] = ($type_ ==1) ? '' : CListTasks::get_link(1);
        CPage_::$menu_[$i]['image'] ='Image/label_checkmate_three_move.png';
        CPage_::$menu_[$i]['submit'] =false;
        CPage_::$menu_[$i]['level'] =2;
        CPage_::$menu_[$i++]['active'] =($type_ ==1) ? 'Y' : 'N';

        CPage_::$menu_[$i]['link'] = ($type_ ==2) ? '' : CListTasks::get_link(2);
        CPage_::$menu_[$i]['image'] ='Image/label_checkmate_four_move.png';
        CPage_::$menu_[$i]['submit'] =false;
        CPage_::$menu_[$i]['level'] =2;
        CPage_::$menu_[$i++]['active'] =($type_ ==2) ? 'Y' : 'N';

        CPage_::$menu_[$i]['link'] = ($type_ ==3) ? '' : CListTasks::get_link(3);
        CPage_::$menu_[$i]['image'] ='Image/label_checkmate_five_move.png';
        CPage_::$menu_[$i]['submit'] =false;
        CPage_::$menu_[$i]['level'] =2;
        CPage_::$menu_[$i++]['active'] =($type_ ==3) ? 'Y' : 'N';

        CPage_::$menu_[$i]['link'] = ($type_ ==4) ? '' : CListTasks::get_link(4);
        CPage_::$menu_[$i]['image'] ='Image/label_best_variant.png';
        CPage_::$menu_[$i]['submit'] =false;
        CPage_::$menu_[$i]['level'] =2;
        CPage_::$menu_[$i++]['active'] =($type_ ==4) ? 'Y' : 'N';

        if (isset($_SESSION[SESSION_ID_]) && ($_SESSION[SESSION_ID_] ==1)){
          CPage_::$menu_[$i]['link'] = ($type_ ==5) ? '' : '';
          CPage_::$menu_[$i]['image'] ='Image/label_our_tasks.png';
          CPage_::$menu_[$i]['submit'] =false;
          CPage_::$menu_[$i]['level'] =2;
          CPage_::$menu_[$i++]['active'] =($type_ ==5) ? 'Y' : 'N';

          if ($_SESSION[SESSION_ID_] ==1){
            CPage_::$menu_[$i]['link'] = ($type_ ==6) ? '' : 'tasks_.php?add=make_';
            CPage_::$menu_[$i]['image'] ='Image/label_make_task.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==6) ? 'Y' : 'N';
          }

          if ($_SESSION[SESSION_ID_] ==1){
            CPage_::$menu_[$i]['link'] = ($type_ ==7) ? '' : '';
            CPage_::$menu_[$i]['image'] ='Image/label_check_tasks.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==7) ? 'Y' : 'N';
          }
        }
        if (!isset($_SESSION[SESSION_ID_])){
          CPage_::$menu_[$i]['link'] = 'index.php';
          CPage_::$menu_[$i]['image'] ='Image/label_esc.png';
          CPage_::$menu_[$i]['submit'] =false;
          CPage_::$menu_[$i]['level'] =1;
          CPage_::$menu_[$i++]['active'] ='N';
        }else CPage_::MakeMenu_(CPage_::PositionMenu_('Задачи'));
      }#MakeMenuMainPage

      public static function MakePage(){
        $connect_ =false;
        $transact_ =false;
        try{
            if (!const_::$connect_)
              if (const_::SetConnect_()) $connect_ =true; else throw new Exception('При соединении с базой данных произошла ошибка.');
            if (!const_::$isTransact_)
              if (const_::StartTransaction_()) $transact_ =true; else throw new Exception('При соединении с базой данных произошла ошибка.');

            $header_ ='';
            if (isset($_SESSION[SESSION_ID_])){
              $classA_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],1);
              $classB_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],2);
              $classC_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],3);
              $ratingA_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],1);
              $ratingB_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],2);
              $ratingC_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],3);

              $header_ ='<DIV id="text_login_">'.
                        '  логин: '.$_SESSION[SESSION_LOGIN_].'<BR>'.
                        '  класс: A'.$classA_.'/B'.$classB_.'/C'.$classC_.
                        '  рейтинг: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'<BR>'.
                        '</DIV>';

              const_::set_params_image_board();
            }

            $script_ ='';
            $link_esc_ ='';
            $link_answer_ ='';
            $link_next_ ='';
            $link_prev_ ='';
            $title_ ='';

            if (isset($_GET['add']) && ($_GET['add']=='make_')){
              if (!isset($_SESSION[SESSION_ID_])) throw new Exception('Для создания/редактирования задачи нужно войти на сайт.');
              if (isset($_POST['view_task_']))
                $type_ =8;
               else if (isset($_POST['view_answer_']))
                $type_ =9;
               else
                $type_ =6;
            }else if (isset($_GET['add']) && ($_GET['add']=='view_task_')){
              $type_ =8;
            }else if (isset($_GET['add']) && ($_GET['add']=='view_answer_')){
              $type_ =9;
            }else if (isset($_GET['type_']) && ($_GET['type_']=='0')){
              $type_ =0;
            }else if (isset($_GET['type_']) && ($_GET['type_']=='1')){
              $type_ =1;
            }else if (isset($_GET['type_']) && ($_GET['type_']=='2')){
              $type_ =2;
            }else if (isset($_GET['type_']) && ($_GET['type_']=='3')){
              $type_ =3;
            }else if (isset($_GET['type_']) && ($_GET['type_']=='4')){
              $type_ =4;
            }else
              $type_ =0;

            switch ($type_){
              case 0 : #мат в два хода
              case 1 : #мат в три хода
              case 2 : #мат в четыре хода
              case 3 : #мат в пять ходов
              case 4 : #как бы ва сыграли
                       if (!isset($_GET['page']) || !ctype_digit($_GET['page']))
                         $p=1;
                        else $p =$_GET['page'];
                       $l =new CListTasks($type_);
                       $l->get_records($p);
                       $body_ =$l->out_records();
                       switch ($type_){
                         case 0 : $title_ ='ChessAndMail. Задачи, мат в два хода'; break;
                         case 1 : $title_ ='ChessAndMail. Задачи, мат в три хода'; break;
                         case 2 : $title_ ='ChessAndMail. Задачи, мат в четыре хода'; break;
                         case 3 : $title_ ='ChessAndMail. Задачи, мат в пять ходов'; break;
                         case 4 : $title_ ='ChessAndMail. Задачи, найти лучшее продолжение'; break;
                       }#switch
                       $title_ .=($p > 1) ? ', страница '.$p.'.' : '.';
                       $_SESSION[SESSION_LINK_ESC_TASK]=CListTasks::get_link($type_,$p);
                       break;
#ваши задачи
              case 5 : $body_ ='';
                       break;
#создать задачу
#----------------------------------------------------------------------
              case 6 :
                       unset($_SESSION[SESSION_LINK_ESC_TASK]);
#Получаю номер задачи
                       if (isset($_GET['task_']) && !ctype_digit($_GET['task_']))
                         throw new Exception('Номер задачи указан неверно.');
                       CPageMakeTask_::$id_task_ ='';
                       if (isset($_GET['task_'])) CPageMakeTask_::$id_task_ =$_GET['task_'];
#обработка нажатия кнопки сохранить
                       if (isset($_POST['save_'])){
                         CPageMakeTask_::save_to_TTasks_(CPageMakeTask_::$id_task_);
                         CPageMakeTask_::save_to_TPositionTask_(CPageMakeTask_::$id_task_);
                       }
#расставляю сохраненную позицию
                       CPageMakeTask_::clear_board();
                       if (CPageMakeTask_::$id_task_ !==''){
                         CPageMakeTask_::read_position_task(CPageMakeTask_::$id_task_);
                         CPageMakeTask_::read_info_task(CPageMakeTask_::$id_task_);
                       }
#Создаю страницу
                       $body_ = CPageMakeTask_::Body_();
#Подключаю Java скрипт
                       $script_ ='<SCRIPT type="text/javascript" src="scripts/make_task.js"></SCRIPT>'."\n".
                                 '<SCRIPT type="text/javascript">'."\n".
                                 '  var task_ = null;'."\n".
                                 '  window.onload =function(){ '."\n".
                                 '     task_ = new cl_task("Image/","'.const_::$catalog_image_fugure.'");'."\n";
                       for($i_=8; $i_>=1; $i_--)
                         for($j_='A'; $j_<='H'; $j_=chr(ord($j_)+1))
                           if (CPageMakeTask_::$board_[$j_][$i_] !='')
                             $script_ .=' task_.board_[\''.$j_.'\']['.$i_.'] =\''.CPageMakeTask_::$board_[$j_][$i_].'\';';
                       $script_ .='  }'."\n".
                                  '</SCRIPT>'."\n";
                       if (CPageMakeTask_::$id_task_ ===''){
                         $header_ .='<DIV id="text_header_">'.
                                    '  Создание задачи'.
                                    '</DIV>';
                       }else{
                         $header_ .='<DIV id="text_header_">'.
                                    '  Редактирование задачи №'.CPageMakeTask_::$id_task_.
                                    '</DIV>';
                       }
                       break;

#задачи на проверку
#----------------------------------------------------------------------
              case 7 : $body_ ='';
                       break;

#Просмотр задачи
#----------------------------------------------------------------------
              case 8 :
                       if (($_GET['add']=='make_') && isset($_POST['view_task_'])){
#Если нажата кнопка "просмотр" со страницы создания задачи
                         if (isset($_GET['task_'])){
                           if (!ctype_digit($_GET['task_'])) throw new Exception('Номер задачи указан неверно.');
                           $id_task_ =$_GET['task_'];
                         }else $id_task_ ='';
                         CPageMakeTask_::save_to_TTasks_($id_task_);
                         CPageMakeTask_::save_to_TPositionTask_($id_task_);
                       }else{
#Получаю номер задачи
                         if (!isset($_GET['task_']) || !ctype_digit($_GET['task_']))
                           throw new Exception('Номер задачи не указан или указан неверно.');
                         $id_task_ =$_GET['task_'];
                       }
#расставляю фигуры
                       CTasks_::clear_board();
                       CTasks_::read_position_task($id_task_);
                       CTasks_::who_start($id_task_);
                       if (isset($_SESSION[SESSION_ID_]))
                         CTasks_::$k_board_ =CUsers_::Read_scale_board();
                       if (isset($_GET['add']) && ($_GET['add']=='make_')){
                         $link_esc_ ='tasks_.php?add=make_&task_='.$id_task_;
                       }else{
                         if (isset($_SESSION[SESSION_LINK_ESC_TASK]))
                           $link_esc_ =$_SESSION[SESSION_LINK_ESC_TASK];
                          else
                           $link_esc_ ='tasks_.php';
                         $link_answer_ ='tasks_.php?add=view_answer_&task_='.$id_task_;
#Ссылки следующая и предыдущая
                         $id_next_ =NULL;
                         $id_prev_ =NULL;
                         $t_ =NULL;
                         if (CTasks_::$check_task_ =='Y'){
                           if (!is_null(CTasks_::$result_))
                             switch (CTasks_::$result_){
                                case 1: $t_ =0; break;
                                case 2: $t_ =1; break;
                                case 3: $t_ =2; break;
                                case 4: $t_ =3; break;
                                case 5:
                                case 6:
                                        $t_ =4; break;
                             }#switch
                         }
                         if (!is_null($t_)){
                           CTasks_::get_next_last_id($t_,$id_task_,$id_next_,$id_prev_);
                           if (!is_null($id_next_))
                             $link_next_ ='tasks_.php?add=view_task_&task_='.$id_next_;
                           if (!is_null($id_prev_))
                             $link_prev_ ='tasks_.php?add=view_task_&task_='.$id_prev_;
                         }
                       }

                       $body_ =CTasks_::BodyTask_();

                       $header_ .='<DIV id="text_header_">'.
                                  '  Задача №'.$id_task_;
                       if (!is_null(CTasks_::$result_))
                         switch (CTasks_::$result_){
                            case 1: $header_ .=' мат в два хода';
                                    break;
                            case 2: $header_ .=' мат в три хода';
                                    break;
                            case 3: $header_ .=' мат в четыре хода';
                                    break;
                            case 4: $header_ .=' мат в пять хода';
                                    break;
                            case 5: $header_ .=' выигрыш';
                                    break;
                            case 6: $header_ .=' ничья';
                                    break;
                         }#switch
                       $header_ .='</DIV>';
                       $title_ ='ChessAndMail. Задача №'.$id_task_;
                       break;

#Просмотр решения
#----------------------------------------------------------------------
              case 9:
                       if (($_GET['add']=='make_') && isset($_POST['view_answer_'])){
#Если нажата кнопка "просмотр" со страницы создания задачи
                         if (isset($_GET['task_'])){
                           if (!ctype_digit($_GET['task_'])) throw new Exception('Номер задачи указан неверно.');
                           $id_task_ =$_GET['task_'];
                         }else $id_task_ ='';
                         CPageMakeTask_::save_to_TTasks_($id_task_);
                         CPageMakeTask_::save_to_TPositionTask_($id_task_);
                       }else{
#Получаю номер задачи
                         if (!isset($_GET['task_']) || !ctype_digit($_GET['task_']))
                           throw new Exception('Номер задачи не указан или указан неверно.');
                         $id_task_ =$_GET['task_'];
                       }
#расставляю фигуры
                       CTasks_::clear_board();
                       CTasks_::read_position_task($id_task_);
                       CTasks_::who_start($id_task_);
                       CTasks_::$k_board_ =0.5;
                       if (isset($_GET['add']) && ($_GET['add']=='make_')){
                         $link_esc_ ='tasks_.php?add=make_&task_='.$id_task_;
                       }else{
                         $link_esc_ ='tasks_.php?add=view_task_&task_='.$id_task_;
#Ссылки следующая и предыдущая
                         $id_next_ =NULL;
                         $id_prev_ =NULL;
                         $t_ =NULL;
                         if (CTasks_::$check_task_ =='Y'){
                           if (!is_null(CTasks_::$result_))
                             switch (CTasks_::$result_){
                                case 1: $t_ =0; break;
                                case 2: $t_ =1; break;
                                case 3: $t_ =2; break;
                                case 4: $t_ =3; break;
                                case 5:
                                case 6:
                                        $t_ =4; break;
                             }#switch
                         }
                         if (!is_null($t_)){
                           CTasks_::get_next_last_id($t_,$id_task_,$id_next_,$id_prev_);
                           if (!is_null($id_next_))
                             $link_next_ ='tasks_.php?add=view_task_&task_='.$id_next_;
                           if (!is_null($id_prev_))
                             $link_prev_ ='tasks_.php?add=view_task_&task_='.$id_prev_;
                         }
                       }
                       $body_ =CTasks_::BodyAnswer_();

                       $header_ .='<DIV id="text_header_">'.
                                  '  Задача №'.$id_task_;
                       if (!is_null(CTasks_::$result_))
                         switch (CTasks_::$result_){
                            case 1: $header_ .=' мат в два хода';
                                    break;
                            case 2: $header_ .=' мат в три хода';
                                    break;
                            case 3: $header_ .=' мат в четыре хода';
                                    break;
                            case 4: $header_ .=' мат в пять хода';
                                    break;
                            case 5: $header_ .=' выигрыш';
                                    break;
                            case 6: $header_ .=' ничья';
                                    break;
                         }#switch
                       $header_ .='</DIV>';
                       $title_ ='ChessAndMail. Решение задачи №'.$id_task_;
                       break;

              default : $body_ ='';
            }#switch

         if ($transact_)
            if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
         if ($connect_)const_::Disconnect_();

         CPage_::$header_ =$header_;
         CPage_::$title_ =$title_;
         if ($type_ ==8)
           CTasks_::MakeMenuForViewTask($link_esc_,$link_answer_,$link_next_,$link_prev_);
          else if ($type_ ==9)
           CTasks_::MakeMenuForViewAnswer($link_esc_,$link_next_,$link_prev_);
          else
           CPage_::MakeMenu_interesting($type_+20);
         CPage_::$body_ =$body_;
         CPage_::MakePage($script_);
        }catch(Exception $e){
           if ($transact_) const_::Rollback_();
           if ($connect_) const_::Disconnect_();
           CPage_::$text_error_ =$e->getMessage();
           CPage_::PageErr();
        }#try
      }#MakePage


      public static function out_board_(){
#Масштаб доски
            $l_=round(const_::$size_image_cell*CTasks_::$k_board_);              settype($l_,"integer"); $w_  ='width="'.$l_.'"'; $h_ ='height="'.$l_.'"';
            $l_=round(const_::$size_image_left_board*CTasks_::$k_board_);        settype($l_,"integer"); $w_l ='width="'.$l_.'"';
            $l_=round(const_::$size_image_right_board*CTasks_::$k_board_);       settype($l_,"integer"); $w_r ='width="'.$l_.'"';
            $l_=round(const_::$size_image_top_bottom_board*CTasks_::$k_board_);  settype($l_,"integer"); $h_t ='height="'.$l_.'"';

            $result_ ='<TABLE style ="border: none; margin-right:10px">'."\n".
                      '  <COL span="2">'."\n".
                      '  <TR><TD style="vertical-align: top">'."\n".
                      '        <TABLE style ="border: none" cellspacing="0" cellpadding="0">'."\n".
                      '        <COL span="10">'."\n";
//верхняя часть доски
            $result_ .='<TR><TD><IMG '.$w_l.' '.$h_t.' style="border:none; vertical-align: bottom" src="'.const_::$catalog_image_fugure.'board_tl.jpg"/></TD>'."\n";
            for($i_=1; $i_<9; $i_++)
                $result_ .='<TD><IMG '.$w_.' '.$h_t.' style="border:none; vertical-align: bottom" src="'.const_::$catalog_image_fugure.'board_top.jpg"/></TD>'."\n";
            $result_ .='<TD><IMG '.$w_r.' '.$h_t.' style="border:none; vertical-align: bottom" src="'.const_::$catalog_image_fugure.'board_tr.jpg"/></TD>'."\n".
                       '</TR>'."\n";
#доска
            for($i_=8; $i_>=1; $i_--){
                $result_ .='<TR>'."\n".
                           '<TD><IMG '.$w_l.' '.$h_.' style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.'board_'.$i_.'.jpg"/></TD>'."\n";
                for($j_='A'; $j_<='H'; $j_=chr(ord($j_)+1)){
                    $result_ .='<TD>';
                    $result_ .='<IMG id="'.$j_.$i_.'" '.$w_.' '.$h_.' onclick="task_.onClickCell(\''.$j_.$i_.'\');"';
                    $result_ .=' style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.
                               CTasks_::$board_[$j_][$i_].
                               CRuleGame::getColorBoard($j_.$i_).
                               '.jpg"/>';
                    $result_ .='</TD>';
                }//for $j_
                $result_ .='<TD><IMG  '.$w_r.' '.$h_.'  style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.'board_right.jpg"/></TD>'."\n".
                           '</TR>';
            }//for $i_
//нижняя часть доски
            $result_ .='<TR><TD><IMG  '.$w_l.' '.$h_t.' style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.'board_bl.jpg"/></TD>'."\n";
            for($i_='a'; $i_<='h'; $i_=chr(ord($i_)+1))
                $result_ .='<TD><IMG  '.$w_.' '.$h_t.' style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.'board_'.$i_.'.jpg"/></TD>'."\n";
            $result_ .='<TD><IMG  '.$w_r.' '.$h_t.' style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.'board_br.jpg"/></TD>'."\n".
                       '</TR>'."\n";

            $result_ .='</TABLE>'."\n".
                       '</TD>'."\n";

            if (CTasks_::$start_ =='W'){
              $result_ .=' <TD style="vertical-align: bottom">'.
                         '   <IMG style="border:none" src="Image/for_task_move_white.png"/>';
            }else{
              $result_ .=' <TD style="vertical-align: top">'.
                         '   <IMG style="border:none" src="Image/for_task_move_black.png"/>';
            }
            $result_ .='</TD>'.
                       '</TR>'.
                       '</TABLE>';
            return $result_;
      }#out_board_

      public static function BodyTask_(){
            $result_ =CTasks_::out_board_();
            return $result_;
      }#BodyTask_

      public static function BodyAnswer_(){
            $result_ ='<DIV style="float: left">'.
                        CTasks_::out_board_().
                      '</DIV>'.
                      '<SPAN id="text_task">'.
                        CTasks_::$answer_.
                      '</SPAN>';
            return $result_;
      }#BodyAnswer_

      public static function clear_board(){
        CTasks_::$board_['A'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
        CTasks_::$board_['B'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
        CTasks_::$board_['C'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
        CTasks_::$board_['D'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
        CTasks_::$board_['E'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
        CTasks_::$board_['F'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
        CTasks_::$board_['G'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
        CTasks_::$board_['H'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
      }#clear_board

      public static function read_position_task($id_task){
          $cursor_ =false;
          try{
            $s ='select colorPiece_,piece_,cell_ from TPositionTask_ where id_task_ ='.$id_task;
            $cursor_=mysql_query($s,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о задаче произошла ошибка.');
            while ($row_ =mysql_fetch_array($cursor_)){
              CTasks_::$board_[$row_['cell_']{0}][$row_['cell_']{1}] =$row_['colorPiece_'].$row_['piece_'];
            }#while
            mysql_free_result($cursor_); $cursor_ =false;
          }catch(Exception $e){
            if ($cursor_) mysql_free_result($cursor_);
            throw new Exception($e->getMessage());
          }
      }#read_position_task

      public static function who_start($id_task){
          $cursor_ =false;
          try{
            $s ='select result_,start_,answer_,check_task_ from TTasks_ where id_ ='.$id_task;
            $cursor_=mysql_query($s,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о задаче произошла ошибка.');
            $row_ =mysql_fetch_array($cursor_);
            if (!$row_) throw new Exception('Задача не найдена.');
            CTasks_::$start_  =$row_['start_'];
            CTasks_::$result_ =$row_['result_'];
            CTasks_::$answer_ =convert_cyr_string($row_['answer_'],'d','w');
            CTasks_::$check_task_ =$row_['check_task_'];

            mysql_free_result($cursor_); $cursor_ =false;
          }catch(Exception $e){
            if ($cursor_) mysql_free_result($cursor_);
            throw new Exception($e->getMessage());
          }
      }#who_start

      public static function get_next_last_id($type_,$id_,&$next_,&$prev_){
        $cursor_ =false;
          try{
#определяю id_ следующей задачи
            $s ='select id_'.
                ' from TTasks_'.
                ' where (id_ >'.$id_.') and (check_task_ =\'Y\')';
            $w ='';
            switch ($type_){
              case 0 : $w =' and (result_ =1)'; break;
              case 1 : $w =' and (result_ =2)'; break;
              case 2 : $w =' and (result_ =3)'; break;
              case 3 : $w =' and (result_ =4)'; break;
              case 4 : $w =' and ((result_ =5) or (result_ =6))'; break;
            }#switch
            $s .=$w.' order by id_';
            $cursor_=mysql_query($s,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о задаче произошла ошибка.');
            $row_ =mysql_fetch_array($cursor_);
            if ($row_) $next_ =$row_['id_']; else $next_ =NULL;
            mysql_free_result($cursor_); $cursor_ =false;

#определяю id_ предыдущей задачи
            $s ='select id_'.
                ' from TTasks_'.
                ' where (id_ <'.$id_.') and (check_task_ =\'Y\')';
            $s .=$w.' order by id_ desc';
            $cursor_=mysql_query($s,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о задаче произошла ошибка.');
            $row_ =mysql_fetch_array($cursor_);
            if ($row_) $prev_ =$row_['id_']; else $prev_ =NULL;
            mysql_free_result($cursor_); $cursor_ =false;

          }catch(Exception $e){
            if ($cursor_) mysql_free_result($cursor_);
            throw new Exception($e->getMessage());
          }
      }#get_next_last_id
    }#CTasks_

    CTasks_::MakePage();
?>
