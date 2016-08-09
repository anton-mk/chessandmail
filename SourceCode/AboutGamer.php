<?php
    require_once('const_.php');
    require_once('Users.php');
    require_once('lib/mylib.php');

    class CListEndTournaments extends CPartOfQuery_{
/*     params_['reglament'] - регламент,
       params_['class'] - класс,
*/
       protected $params_;
       protected $id_;
       public $records_ =array();

       public static function empty_params(){
           $result_['reglament'] ='';
           $result_['class'] ='';
           return $result_;
       }#empty_params

       public static function get_link($params_,$page_,$login_){
            $href_='MainPage.php?link_=about_gamer&type_=7&login_='.urlencode($login_);

            if ($params_['reglament'] != '')
              $href_ .='&reglament_='.$params_['reglament'];
            if ($params_['class'] != '')
              $href_ .='&class_='.$params_['class'];

            $href_ .='&page='.$page_;
            return $href_;
       }#get_link

       public function __construct($params_,$id_){
            $this->params_ =$params_;
            $this->id_ =$id_;
            parent::__construct(const_::$connect_);
       }#__construct

       protected function get_where(&$where_){
            $where_='';
            if ($this->params_['class'] != '')
              $where_ ='(A.class_ =\''.$this->params_['class'].'\')';
            if ($this->params_['reglament'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='(A.reglament_ ='.$this->params_['reglament'].')';
            }
       }#get_where

       protected function str_select_for_countPage(){
            $result_ ='select count(*) as count_'.
                      ' from TTournaments_ A'.
                      ' where (not A.end_ is null)'.
                            ' and exists (select * '.
                                         ' from TMembersTournament_ B'.
                                         ' where (B.id_tournament_ = A.id_) and (B.id_gamer_ ='.$this->id_.'))';
            $where_ ='';
            $this->get_where($where_);
            if ($where_ !='') $result_ .=' and '.$where_;
            return $result_;
       } #str_select_for_countPage

       protected function str_select_for_getRecords(){            $result_ ='select A.id_,A.reglament_,A.cGamers_,A.begin_,A.end_,A.class_,B.place1_,B.place2_,A.system_'.
                      ' from TTournaments_ A, TMembersTournament_ B'.
                      ' where (not A.end_ is null) and (B.id_tournament_ = A.id_) and (B.id_gamer_ ='.$this->id_.')';
            $where_ ='';
            $this->get_where($where_);
            if ($where_ !='') $result_ .=' and '.$where_;
            $result_ .=' order by A.id_ desc';
            return $result_;
       }#str_select_for_getRecords

       public function get_records($page_){
            global $reglaments_;
            try{
                if (!$this->getRecords(false,$page_,array('id_','reglament_','cGamers_','begin_','end_','class_','place1_','place2_','system_')))
                    throw new Exception();
                for($i=0; $i<count($this->listRecords); $i++){                    if (is_null($this->listRecords[$i]['system_']))
                      $this->records_[$i]['id_'] ='<A href="MainPage.php?link_=Tournament&id_='.$this->listRecords[$i]['id_'].'">'.$this->listRecords[$i]['id_'].'</A>';
                     else
                      $this->records_[$i]['id_'] ='<A href="MainPage.php?link_=swiss_Tournament&id_='.$this->listRecords[$i]['id_'].'">'.$this->listRecords[$i]['id_'].'</A>';
                    $this->records_[$i]['reglament_'] =$reglaments_['reglament'.$this->listRecords[$i]['reglament_'].'_'];
                    $this->records_[$i]['cGamers_']   =$this->listRecords[$i]['cGamers_'];
                    $this->records_[$i]['begin_']     =convert_cyr_string($this->listRecords[$i]['begin_'],'d','w');
                    $this->records_[$i]['end_']       =convert_cyr_string($this->listRecords[$i]['end_'],'d','w');
                    $this->records_[$i]['class_']     =$this->listRecords[$i]['class_'];
                    $this->records_[$i]['place1_']    =$this->listRecords[$i]['place1_'];
                    $this->records_[$i]['place2_']    =$this->listRecords[$i]['place2_'];
                } #for
            }catch(Exception $e){
                throw new Exception('При чтении информации о завершенных турнирах произошла ошибка.');
            }
       }#get_records

       public function out_records($login_){
            $result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                   'font-size: 12pt; color: black;'.
                                   'text-decoration: none; font-weight: normal">'."\n".
                      '   <TABLE style ="border: none; margin-left: auto; margin-right: auto" cellspacing="0" cellpadding="0">'."\n".
                      '      <COL span="1">'."\n";
            $m ='';
            if ($this->cCountPages > 1){
                $a =$this->getFirstVisibleNum($this->page_);
                $b =$this->getLastVisibleNum($this->page_);
                if ($a > 1) $m ='<A href="'.CListEndTournaments::get_link($this->params_,$a-1,$login_).'">'.htmlspecialchars('<<').'</A>';
                for ($i=$a; $i <=$b; $i++){
                    if ($m != '') $m .='&nbsp;';
                    if ($this->page_ != $i) $m .='<A href="'.CListEndTournaments::get_link($this->params_,$i,$login_).'">'.$i.'</A>'; else $m.=$i;
                }#for
                if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.CListEndTournaments::get_link($this->params_,$b+1,$login_).'">'.htmlspecialchars('>>').'</A>';
                $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            }
            $result_ .='<TR><TD>'."\n".
                       '<TABLE cellspacing="3">'."\n".
                       '     <COL span="8">'."\n".
                       '     <TR><TD class="table_head_1">№</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">класс</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">регламент</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">кол-во игроков</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">турнир начат</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">турнир закончен</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">место</TD>'."\n".
                       '     </TR>';
            for($i=0; $i < count($this->records_); $i++){
                $result_ .='<TR>'.
                              '<TD class="table_body_1">'.$this->records_[$i]['id_'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['class_'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['reglament_'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['cGamers_'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['begin_'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['end_'].'</TD>'."\n";
                $s =$this->records_[$i]['place1_'].(($this->records_[$i]['place2_'] !='') ? '-'.$this->records_[$i]['place2_'] : '');
                $result_ .=   '<TD class="table_body_1">'.$s.'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='</TABLE>'."\n".
                       '</TD></TR>'."\n";
            if ($m != '')
              $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            $result_ .='</TABLE></SPAN>';
            return $result_;
       }#out_records
    }#CListEndTournaments

    class CListEndGames extends CPartOfQuery_{
/*     params_['login'] - логин,
       params_['reglament'] - регламент,
       params_['class'] - класс,
*/
       protected $params_;
       protected $id_;
       public $records_ =array();

       public static function empty_params(){
           $result_['login'] ='';
           $result_['reglament'] ='';
           $result_['class'] ='';
           return $result_;
       }#empty_params

       public static function get_link($params_,$page_,$login_){
            $href_='MainPage.php?link_=about_gamer&type_=5&login_='.urlencode($login_);

            if ($params_['login'] != '')
              $href_ .='&login_rival_='.urlencode($params_['login']);
            if ($params_['reglament'] != '')
              $href_ .='&reglament_='.$params_['reglament'];
            if ($params_['class'] != '')
              $href_ .='&class_='.$params_['class'];

            $href_ .='&page='.$page_;
            return $href_;
       }#get_link

       public function __construct($params_,$id_){
            $this->params_ =$params_;
            $this->id_ =$id_;
            parent::__construct(const_::$connect_);
       }#__construct

       protected function get_where(&$where_){
            $where_='';
            if ($this->params_['class'] != '')
              $where_ ='(A.class_ =\''.$this->params_['class'].'\')';
            if ($this->params_['login'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='((B.login_ =\''.$this->params_['login'].'\') or '.
                        ' (C.login_ =\''.$this->params_['login'].'\'))';
            }
            if ($this->params_['reglament'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='(A.reglament_ ='.$this->params_['reglament'].')';
            }
       }#get_where

       protected function str_select_for_countPage(){
            $result_ ='select count(*) as count_'.
                      ' from TGames_ A, TGamers_ B, TGamers_ C'.
                      ' where (not A.result_ is null) and (A.idWGamer_ = B.id_) and (A.idBGamer_ = C.id_) and'.
                      '       ((A.idWGamer_ ='.$this->id_.') or (A.idBGamer_ ='.$this->id_.'))';
            $where_ ='';
            $this->get_where($where_);
            if ($where_ !='') $result_ .=' and '.$where_;
            return $result_;
       } #str_select_for_countPage

       protected function str_select_for_getRecords(){
            $result_ ='select A.id_, A.reglament_, A.clockWhite_,A.clockBlack_, A.result_,'.
                      '       B.login_ as whiteLogin, C.login_ as blackLogin, D.num_,A.class_'.
                      ' from TGames_ A left join TMoves_ D on (A.id_ =D.idGame_) and '.
                      '                                       not exists (select * from TMoves_ where (A.id_ = idGame_) and (num_ > D.num_)),'.
                      '      TGamers_ B, TGamers_ C'.
                      ' where (not A.result_ is null) and (A.idWGamer_ = B.id_) and (A.idBGamer_ = C.id_) and'.
                      '       ((A.idWGamer_ ='.$this->id_.') or (A.idBGamer_ ='.$this->id_.'))';
            $where_ ='';
            $this->get_where($where_);
            if ($where_ !='') $result_ .=' and '.$where_;
            $result_ .=' order by A.id_ desc';
            return $result_;
        }#str_select_for_getRecords

       public function get_records($page_){
            global $reglaments_;
            try{
                if (!$this->getRecords(false,$page_,array('id_','reglament_','clockWhite_','clockBlack_','result_','whiteLogin','blackLogin','num_','class_')))
                    throw new Exception();
                for($i=0; $i<count($this->listRecords); $i++){
                    $this->records_[$i]['id_'] ='<A href="MainPage.php?link_=game&id='.$this->listRecords[$i]['id_'].'">'.$this->listRecords[$i]['id_'].'</A>';
                    $w_ =convert_cyr_string($this->listRecords[$i]['whiteLogin'],'d','w');
                    $b_ =convert_cyr_string($this->listRecords[$i]['blackLogin'],'d','w');
                    $this->records_[$i]['white-black']     ='<A href="MainPage.php?link_=about_gamer&login_='.urlencode($w_).'">'.
                                                                                 htmlspecialchars($w_).
                                                                             '</A>'.
                                                                             ' - '.
                                                                             '<A href="MainPage.php?link_=about_gamer&login_='.urlencode($b_).'">'.
                                                                                 htmlspecialchars($b_).
                                                                             '</A>';
					if ($this->listRecords[$i]['result_'] == 0) $this->records_[$i]['result'] = '0:1';
                      elseif ($this->listRecords[$i]['result_'] == 1) $this->records_[$i]['result'] = '1:0';
					  else $this->records_[$i]['result'] = '1/2:1/2';
    			    if ($this->listRecords[$i]['num_'] !='') $this->records_[$i]['num_move'] =$this->listRecords[$i]['num_'];
					  else $this->records_[$i]['num_move'] =0;
					$this->records_[$i]['reglament']= $reglaments_['reglament'.$this->listRecords[$i]['reglament_'].'_'];
					$this->records_[$i]['clock_black'] = clockToStr($this->listRecords[$i]['clockBlack_']);
					$this->records_[$i]['clock_white'] = clockToStr($this->listRecords[$i]['clockWhite_']);
                    if (is_null($this->listRecords[$i]['class_']) || ($this->listRecords[$i]['class_'] ==''))
                        $this->records_[$i]['class']  ='&nbsp;';
                     else
                         $this->records_[$i]['class'] =$this->listRecords[$i]['class_'];
                } #for
            }catch(Exception $e){
                throw new Exception('При чтении информации о завершенных партиях произошла ошибка.');
            }
       }#get_records

       public function out_records($login_){
            $result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                   'font-size: 12pt; color: black;'.
                                   'text-decoration: none; font-weight: normal">'."\n".
                      '   <TABLE style ="border: none; margin-left: auto; margin-right: auto" cellspacing="0" cellpadding="0">'."\n".
                      '      <COL span="1">'."\n";
            $m ='';
            if ($this->cCountPages > 1){
                $a =$this->getFirstVisibleNum($this->page_);
                $b =$this->getLastVisibleNum($this->page_);
                if ($a > 1) $m ='<A href="'.CListEndGames::get_link($this->params_,$a-1,$login_).'">'.htmlspecialchars('<<').'</A>';
                for ($i=$a; $i <=$b; $i++){
                    if ($m != '') $m .='&nbsp;';
                    if ($this->page_ != $i) $m .='<A href="'.CListEndGames::get_link($this->params_,$i,$login_).'">'.$i.'</A>'; else $m.=$i;
                }#for
                if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.CListEndGames::get_link($this->params_,$b+1,$login_).'">'.htmlspecialchars('>>').'</A>';
                $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            }
            $result_ .='<TR><TD>'."\n".
                       '<TABLE cellspacing="3">'."\n".
                       '     <COL span="8">'."\n".
                       '     <TR><TD class="table_head_1">№</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">белые-черные</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">результат</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">сделано ходов</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">регламент</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">класс</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">часы белых</TD>'."\n".
                       '         <TD class="table_head_1" style="white-space:nowrap">часы черных</TD>'."\n".
                       '     </TR>';
            for($i=0; $i < count($this->records_); $i++){
                $result_ .='<TR>'.
                              '<TD class="table_body_1">'.$this->records_[$i]['id_'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['white-black'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['result'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['num_move'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['reglament'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['class'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['clock_white'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['clock_black'].'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='</TABLE>'."\n".
                       '</TD></TR>'."\n";
            if ($m != '')
              $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            $result_ .='</TABLE></SPAN>';
            return $result_;
       }#out_records
    }#CListEndGames

	class CAboutGamer_{
        protected static function MakeMenuMainPage($type_,$login_){
            $i =0;
            if (isset($_SESSION[SESSION_LINK_ESC_ABOUT_GAMER])){
                CPage_::$menu_[$i]['link'] = $_SESSION[SESSION_LINK_ESC_ABOUT_GAMER];
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

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=about_gamer&login_='.urlencode($login_);
            CPage_::$menu_[$i]['image'] ='Image/label_info.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =1;
            CPage_::$menu_[$i++]['active'] =(($type_ ==1) || ($type_ ==2) || ($type_ ==3)) ? 'Y' : 'N';

            if (($type_ ==1) || ($type_ ==2) || ($type_ ==3)){
              CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=about_gamer&login_='.urlencode($login_);
              CPage_::$menu_[$i]['image'] ='Image/label_general_info.png';
              CPage_::$menu_[$i]['submit'] =false;
              CPage_::$menu_[$i]['level'] =2;
              CPage_::$menu_[$i++]['active'] =($type_ ==1) ? 'Y' : 'N';

              CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=about_gamer&type_=2&login_='.urlencode($login_);
              CPage_::$menu_[$i]['image'] ='Image/label_statistic_info.png';
              CPage_::$menu_[$i]['submit'] =false;
              CPage_::$menu_[$i]['level'] =2;
              CPage_::$menu_[$i++]['active'] =($type_ ==2) ? 'Y' : 'N';

              CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=about_gamer&type_=3&login_='.urlencode($login_);
              CPage_::$menu_[$i]['image'] ='Image/label_photo.png';
              CPage_::$menu_[$i]['submit'] =false;
              CPage_::$menu_[$i]['level'] =2;
              CPage_::$menu_[$i++]['active'] =($type_ ==3) ? 'Y' : 'N';
            }

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=about_gamer&type_=4&login_='.urlencode($login_);
            CPage_::$menu_[$i]['image'] ='Image/label_games_for_about_user.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =1;
            CPage_::$menu_[$i++]['active'] =(($type_ ==4) || ($type_ ==5)) ? 'Y' : 'N';

            if (($type_ ==4) || ($type_ ==5)){
              CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=about_gamer&type_=4&login_='.urlencode($login_);
              CPage_::$menu_[$i]['image'] ='Image/label_active_tournaments.png';
              CPage_::$menu_[$i]['submit'] =false;
              CPage_::$menu_[$i]['level'] =2;
              CPage_::$menu_[$i++]['active'] =($type_ ==4) ? 'Y' : 'N';

              CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=about_gamer&type_=5&login_='.urlencode($login_);
              CPage_::$menu_[$i]['image'] ='Image/label_end_tournaments.png';
              CPage_::$menu_[$i]['submit'] =false;
              CPage_::$menu_[$i]['level'] =2;
              CPage_::$menu_[$i++]['active'] =($type_ ==5) ? 'Y' : 'N';
            }

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=about_gamer&type_=6&login_='.urlencode($login_);
            CPage_::$menu_[$i]['image'] ='Image/label_tournaments.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =1;
            CPage_::$menu_[$i++]['active'] =(($type_ ==6) || ($type_ ==7)) ? 'Y' : 'N';

            if (($type_ ==6) || ($type_ ==7)){
              CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=about_gamer&type_=6&login_='.urlencode($login_);
              CPage_::$menu_[$i]['image'] ='Image/label_active_tournaments.png';
              CPage_::$menu_[$i]['submit'] =false;
              CPage_::$menu_[$i]['level'] =2;
              CPage_::$menu_[$i++]['active'] =($type_ ==6) ? 'Y' : 'N';

              CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=about_gamer&type_=7&login_='.urlencode($login_);
              CPage_::$menu_[$i]['image'] ='Image/label_end_tournaments.png';
              CPage_::$menu_[$i]['submit'] =false;
              CPage_::$menu_[$i]['level'] =2;
              CPage_::$menu_[$i++]['active'] =($type_ ==7) ? 'Y' : 'N';
            }

            CPage_::$menu_[$i]['link'] = 'index.php';
            CPage_::$menu_[$i]['image'] ='Image/label_exit.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =1;
            CPage_::$menu_[$i++]['active'] ='N';
        }#MakeMenuMainPage

        protected static function BodyGeneralInfo($id_){
          $i_ =CUsers_::ReadAboutYou($id_);
          $status_otpusk =CUsers_::Status_Otpusk($id_);
          $ostatoc_otpusk =clockToStr(CUsers_::Ostatok_Otpusk($id_));

          $classA_ =CUsers_::ReadClass_($id_,1);
          $classB_ =CUsers_::ReadClass_($id_,2);
          $classC_ =CUsers_::ReadClass_($id_,3);
          $ratingA_ =CUsers_::ReadRating_($id_,1);
          $ratingB_ =CUsers_::ReadRating_($id_,2);
          $ratingC_ =CUsers_::ReadRating_($id_,3);

          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'Общая информация'.
                      '</DIV><BR>'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                         '<TABLE style="margin-left: auto; margin-right: auto">'."\n".
                            '<COL span="2">'."\n".
                            '<TR>'."\n".
                                '<TD colspan="2" style="text-align: center">класс: A'.$classA_.'/B'.$classB_.'/C'.$classC_.'</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD colspan="2" style="text-align: center">рейтинг: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'</TD>'."\n".
                            '</TR>'."\n".
                            '<TR><TD>&nbsp;</TD></TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="famil_">фамилия</LABEL></TD>'."\n".
                                '<TD>'.$i_['famil_'].'</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="ima_">имя</LABEL></TD>'."\n".
                                '<TD>'.$i_['ima_'].'</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="otchest_">отчество</LABEL></TD>'."\n".
                                '<TD>'.$i_['otchest_'].'</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="date_birth">дата рождения</LABEL></TD>'."\n".
                                '<TD>'.$i_['date_birth'].'</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD>&nbsp;</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="country_">страна</LABEL></TD>'."\n".
                                '<TD>'.$i_['country_'].'</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="punkt_">город (нас. пункт)</LABEL></TD>'."\n".
                                '<TD>'.$i_['punkt_'].'</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD>&nbsp;</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="punkt_">последняя активность</LABEL></TD>'."\n".
                                '<TD>'.$i_['last_connect_'].'</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="punkt_">отпуск</LABEL></TD>'."\n".
                                '<TD>'.($status_otpusk ? 'в отпуске' : 'не в отпуске').' (осталось отпуска: '.$ostatoc_otpusk.')</TD>'."\n".
                            '</TR>'."\n".
                         '</TABLE>'."\n".
                      '</DIV>'."\n";
          return $result_;
        }#BodyGeneralInfo

        protected static function BodyPhoto($id_){
          $photo_ ='';
          if (CUsers_::ExistsPhoto($id_))
            $photo_ ='MainPage.php?link_=photo&id_='.$id_;

          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                               'font-size: 16pt; color: white; text-align: center;'.
                               'text-decoration: none; font-weight: normal">'.
                     'Фотография'.
                   '</DIV><BR>'."\n".
                   '<TABLE style="margin-left: auto; margin-right: auto">'."\n".
                   '  <TR><TD>'."\n".
                        '<TABLE style="border: 1px solid #fbffff" cellspasing="4">'."\n".
                          '<COL span="1">'."\n".
                          '<TR>'."\n".
                          '  <TD>'."\n".
                          '     <IMG src="'.(($photo_ =='') ? 'Image/no_photo.png' : $photo_).'" style="border:none">'."\n".
                          '  </TD>'."\n".
                          '</TR>'."\n".
                        '</TABLE>'."\n".
                   '  </TD></TR>'.
                   '</TABLE>'."\n";
          return $result_;
        }#BodyPhoto

        protected static function get_static_info_games($id_){
#общее количество партии
          $result_['end_games']['A'] = '&nbsp;';
          $result_['end_games']['B'] = '&nbsp;';
          $result_['end_games']['C'] = '&nbsp;';
          $s_ ='select count(*) as count_, class_'.
               ' from TGames_ '.
               ' where (not result_ is null) and ((idWGamer_ ='.$id_.') or (idBGamer_ ='.$id_.'))'.
               ' group by class_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о завершенных партиях произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
             if ($row_['class_'] =='A')
               $result_['end_games']['A'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
             if ($row_['class_'] =='B')
               $result_['end_games']['B'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
             if ($row_['class_'] =='C')
               $result_['end_games']['C'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
          }#while
          mysql_free_result($cursor_);
#выйграно
          $result_['victorty_games']['A'] = '&nbsp;';
          $result_['victorty_games']['B'] = '&nbsp;';
          $result_['victorty_games']['C'] = '&nbsp;';
          $s_ ='select count(*) as count_, class_'.
               ' from TGames_'.
               ' where ((idWGamer_ ='.$id_.') and (result_ =1)) or '.
                      '((idBGamer_ ='.$id_.') and (result_ =0))'.
               ' group by class_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о выйгранных партиях произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
             if ($row_['class_'] =='A')
               $result_['victorty_games']['A'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
             if ($row_['class_'] =='B')
               $result_['victorty_games']['B'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
             if ($row_['class_'] =='C')
               $result_['victorty_games']['C'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
          }#while
          mysql_free_result($cursor_);
#количество партий, сыгранных вничью
          $result_['drawn_games']['A'] = '&nbsp;';
          $result_['drawn_games']['B'] = '&nbsp;';
          $result_['drawn_games']['C'] = '&nbsp;';
          $s_ ='select count(*) as count_, class_'.
               ' from TGames_'.
               ' where (result_=2) and ((idWGamer_ ='.$id_.') or (idBGamer_ ='.$id_.'))'.
               ' group by class_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о партиях, сыгранных вничью, произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
             if ($row_['class_'] =='A')
               $result_['drawn_games']['A'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
             if ($row_['class_'] =='B')
               $result_['drawn_games']['B'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
             if ($row_['class_'] =='C')
               $result_['drawn_games']['C'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
          }#while
          mysql_free_result($cursor_);
#проиграно
          $result_['loss_games']['A'] = '&nbsp;';
          $result_['loss_games']['B'] = '&nbsp;';
          $result_['loss_games']['C'] = '&nbsp;';
          $s_ ='select count(*) as count_, class_'.
               ' from TGames_'.
               ' where ((idWGamer_ ='.$id_.') and (result_ =0)) or '.
                      '((idBGamer_ ='.$id_.') and (result_ =1))'.
               ' group by class_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о проигранных партиях произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
             if ($row_['class_'] =='A')
               $result_['loss_games']['A'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
             if ($row_['class_'] =='B')
               $result_['loss_games']['B'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
             if ($row_['class_'] =='C')
               $result_['loss_games']['C'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
          }#while
          mysql_free_result($cursor_);
#проиграно по времени
          $result_['time_loss_games']['A'] = '&nbsp;';
          $result_['time_loss_games']['B'] = '&nbsp;';
          $result_['time_loss_games']['C'] = '&nbsp;';
          $s_ ='select count(*) as count_, class_'.
               ' from TGames_'.
               ' where ((idWGamer_ ='.$id_.') and (result_ =0) and (clockWhite_=0)) or '.
                      '((idBGamer_ ='.$id_.') and (result_ =1) and (clockBlack_=0))'.
               ' group by class_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о партиях, проигранных по времени, произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
             if ($row_['class_'] =='A')
               $result_['time_loss_games']['A'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
             if ($row_['class_'] =='B')
               $result_['time_loss_games']['B'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
             if ($row_['class_'] =='C')
               $result_['time_loss_games']['C'] = ($row_['count_']==0 ? '&nbsp;' : $row_['count_']);
          }#while
          mysql_free_result($cursor_);

          return $result_;
        }#get_static_info_games

        protected static function get_personal_result($id_){
#общее количество партии
          $result_['A']['self'] =0; $result_['A']['target_id'] =0;
          $result_['B']['self'] =0; $result_['B']['target_id'] =0;
          $result_['C']['self'] =0; $result_['C']['target_id'] =0;
          $s_ ='select class_, result_, idWGamer_, idBGamer_'.
               ' from TGames_ '.
               ' where (not result_ is null) and '.
               '       ((idWGamer_ ='.$id_.') or (idWGamer_ ='.$_SESSION[SESSION_ID_].')) and '.
               '       ((idBGamer_ ='.$id_.') or (idBGamer_ ='.$_SESSION[SESSION_ID_].'))';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о личном счете произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
             if ($row_['result_'] ==0){
               if ($row_['idWGamer_'] == $id_){
                 if ($row_['class_'] == 'A') $result_['A']['self'] +=1;
                  else if ($row_['class_'] == 'B') $result_['B']['self'] +=1;
                  else if ($row_['class_'] == 'C') $result_['C']['self'] +=1;
               }else{
                 if ($row_['class_'] == 'A') $result_['A']['target_id']+=1;
                  else if ($row_['class_'] == 'B') $result_['B']['target_id'] +=1;
                  else if ($row_['class_'] == 'C') $result_['C']['target_id'] +=1;
               }
             }else if ($row_['result_'] ==1){
               if ($row_['idWGamer_'] == $id_){
                 if ($row_['class_'] == 'A') $result_['A']['target_id'] +=1;
                  else if ($row_['class_'] == 'B') $result_['B']['target_id'] +=1;
                  else if ($row_['class_'] == 'C') $result_['C']['target_id'] +=1;
               }else{
                 if ($row_['class_'] == 'A') $result_['A']['self']+=1;
                  else if ($row_['class_'] == 'B') $result_['B']['self'] +=1;
                  else if ($row_['class_'] == 'C') $result_['C']['self'] +=1;
               }
             }else if ($row_['result_'] ==2){
                 if ($row_['class_'] == 'A'){$result_['A']['self'] +=0.5; $result_['A']['target_id'] +=0.5;}
                  else if ($row_['class_'] == 'B') {$result_['B']['self'] +=0.5; $result_['B']['target_id'] +=0.5;}
                  else if ($row_['class_'] == 'C') {$result_['C']['self'] +=0.5; $result_['C']['target_id'] +=0.5;}
             }
          }#while
          mysql_free_result($cursor_);

          return $result_;
        }#get_personal_result

        protected static function get_info_tournaments($id_){
#законченные
          $result_['end']['A'] =0;
          $result_['end']['B'] =0;
          $result_['end']['C'] =0;
          $s_ ='select count(*) as count_, A.class_'.
               ' from TTournaments_ A, TMembersTournament_ B'.
               ' where (not A.end_ is null) and (B.id_tournament_=A.id_) and (B.id_gamer_='.$id_.')'.
               ' group by A.class_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о законченных турнирах произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
            if (!is_null($row_['class_']) && ($row_['class_'] !=''))
              if ($row_['class_']{0} == 'A') $result_['end']['A'] +=$row_['count_'];
                else if ($row_['class_']{0} == 'B') $result_['end']['B'] +=$row_['count_'];
                else if ($row_['class_']{0} == 'C') $result_['end']['C'] +=$row_['count_'];
          }#while
#первое место
          $result_['first']['A'] =0;
          $result_['first']['B'] =0;
          $result_['first']['C'] =0;
          $s_ ='select count(*) as count_, A.class_'.
               ' from TTournaments_ A, TMembersTournament_ B'.
               ' where (not A.end_ is null) and (B.id_tournament_=A.id_) and (B.id_gamer_='.$id_.') and (B.place1_ =1)'.
               ' group by A.class_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о законченных турнирах произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
            if (!is_null($row_['class_']) && ($row_['class_'] !=''))
              if ($row_['class_']{0} == 'A') $result_['first']['A'] +=$row_['count_'];
                else if ($row_['class_']{0} == 'B') $result_['first']['B'] +=$row_['count_'];
                else if ($row_['class_']{0} == 'C') $result_['first']['C'] +=$row_['count_'];
          }#while
#второе место
          $result_['second']['A'] =0;
          $result_['second']['B'] =0;
          $result_['second']['C'] =0;
          $s_ ='select count(*) as count_, A.class_'.
               ' from TTournaments_ A, TMembersTournament_ B'.
               ' where (not A.end_ is null) and (B.id_tournament_=A.id_) and (B.id_gamer_='.$id_.') and (B.place1_ =2)'.
               ' group by A.class_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о законченных турнирах произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
            if (!is_null($row_['class_']) && ($row_['class_'] !=''))
              if ($row_['class_']{0} == 'A') $result_['second']['A'] +=$row_['count_'];
                else if ($row_['class_']{0} == 'B') $result_['second']['B'] +=$row_['count_'];
                else if ($row_['class_']{0} == 'C') $result_['second']['C'] +=$row_['count_'];
          }#while
#третье место
          $result_['third']['A'] =0;
          $result_['third']['B'] =0;
          $result_['third']['C'] =0;
          $s_ ='select count(*) as count_, A.class_'.
               ' from TTournaments_ A, TMembersTournament_ B'.
               ' where (not A.end_ is null) and (B.id_tournament_=A.id_) and (B.id_gamer_='.$id_.') and (B.place1_ =3)'.
               ' group by A.class_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о законченных турнирах произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
            if (!is_null($row_['class_']) && ($row_['class_'] !=''))
              if ($row_['class_']{0} == 'A') $result_['third']['A'] +=$row_['count_'];
                else if ($row_['class_']{0} == 'B') $result_['third']['B'] +=$row_['count_'];
                else if ($row_['class_']{0} == 'C') $result_['third']['C'] +=$row_['count_'];
          }#while
#последнее место
          $result_['last']['A'] =0;
          $result_['last']['B'] =0;
          $result_['last']['C'] =0;
          $s_ ='select count(*) as count_, A.class_'.
               ' from TTournaments_ A, TMembersTournament_ B'.
               ' where (not A.end_ is null) and (B.id_tournament_=A.id_) and (B.id_gamer_='.$id_.') and '.
               '       (B.place1_ = A.cGamers_) and (B.place2_ is null)'.
               ' group by A.class_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о законченных турнирах произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
            if (!is_null($row_['class_']) && ($row_['class_'] !=''))
              if ($row_['class_']{0} == 'A') $result_['last']['A'] +=$row_['count_'];
                else if ($row_['class_']{0} == 'B') $result_['last']['B'] +=$row_['count_'];
                else if ($row_['class_']{0} == 'C') $result_['last']['C'] +=$row_['count_'];
          }#while

          return $result_;
        }#get_info_tournaments

        protected static function BodyStatistic($id_){
          $table_1 =CAboutGamer_::get_static_info_games($id_);
          $table_2 =CAboutGamer_::get_personal_result($id_);
          $table_3 =CAboutGamer_::get_info_tournaments($id_);
          $login_  =CUsers_::ReadLogins_(array($id_));

          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                   '  Завершенные партии'.
                   '</DIV>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                   '  <TABLE style="margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                   '     <COL span="4">'."\n".
                   '     <TR><TD class="table_head_1">&nbsp;</TD>'."\n".
                   '         <TD class="table_head_1">класс A</TD>'."\n".
                   '         <TD class="table_head_1">класс B</TD>'."\n".
                   '         <TD class="table_head_1">класс C</TD>'."\n".
                   '     </TR>'.
                   '     <TR><TD class="table_body_1">сыграно</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['end_games']['A'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['end_games']['B'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['end_games']['C'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '     <TR><TD class="table_body_1">выиграно</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['victorty_games']['A'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['victorty_games']['B'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['victorty_games']['C'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '     <TR><TD class="table_body_1">ничья</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['drawn_games']['A'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['drawn_games']['B'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['drawn_games']['C'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '     <TR><TD class="table_body_1">проиграно</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['loss_games']['A'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['loss_games']['B'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['loss_games']['C'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '     <TR><TD class="table_body_1">из них по времени</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['time_loss_games']['A'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['time_loss_games']['B'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_1['time_loss_games']['C'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '  </TABLE>'."\n".
                   '</DIV><BR>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                   '  Личный счет'.
                   '</DIV>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                   '  <TABLE style="margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                   '     <COL span="3">'."\n".
                   '     <TR><TD class="table_head_1">&nbsp;</TD>'."\n".
                   '         <TD class="table_head_1">'.$login_.'</TD>'."\n".
                   '         <TD class="table_head_1">'.$_SESSION[SESSION_LOGIN_].'</TD>'."\n".
                   '     </TR>'.
                   '     <TR><TD class="table_body_1">класс A</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_2['A']['target_id'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_2['A']['self'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '     <TR><TD class="table_body_1">класс B</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_2['B']['target_id'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_2['B']['self'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '     <TR><TD class="table_body_1">класс C</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_2['C']['target_id'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_2['C']['self'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '  </TABLE>'."\n".
                   '</DIV><BR>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                   '  Завершенные турниры'.
                   '</DIV>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                   '  <TABLE style="margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                   '     <COL span="4">'."\n".
                   '     <TR><TD class="table_head_1">&nbsp;</TD>'."\n".
                   '         <TD class="table_head_1">класс A</TD>'."\n".
                   '         <TD class="table_head_1">класс B</TD>'."\n".
                   '         <TD class="table_head_1">класс C</TD>'."\n".
                   '     </TR>'.
                   '     <TR><TD class="table_body_1">закончено</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['end']['A'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['end']['B'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['end']['C'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '     <TR><TD class="table_body_1">первое место</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['first']['A'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['first']['B'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['first']['C'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '     <TR><TD class="table_body_1">второе место</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['second']['A'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['second']['B'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['second']['C'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '     <TR><TD class="table_body_1">третье место</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['third']['A'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['third']['B'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['third']['C'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '     <TR><TD class="table_body_1">последнее место</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['last']['A'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['last']['B'].'</TD>'."\n".
                   '         <TD class="table_body_1">'.$table_3['last']['C'].'</TD>'."\n".
                   '     </TR>'."\n".
                   '  </TABLE>'."\n".
                   '</DIV>'."\n";
          return $result_;
        }#BodyStatistic

        protected static function get_list_active_games($id_){
          global $reglaments_;
          $result_ =array();
          $curr_time=time();

          $s_ ='select A.id_, A.reglament_, A.clockWhite_, A.clockBlack_, A.beginMove_, A.isMoveWhite_,'.
               '       A.class_, B.login_ as whiteLogin, C.login_ as blackLogin, D.num_,'.
               '       F.id_ as id_tournament, F.class_ as class_tournament,F.system_'.
               ' from TGames_ A left join TMoves_ D on (A.id_ =D.idGame_) and '.
               '                                       not exists (select * from TMoves_ where (A.id_ = idGame_) and (num_ > D.num_))'.
               '                left join TGamesTournament_ E on E.id_game = A.id_'.
               '                left join TTournaments_ F on F.id_ = E.id_tournament_,'.
               '      TGamers_ B, TGamers_ C'.
               ' where (A.result_ is null) and (A.idWGamer_ = B.id_) and (A.idBGamer_ = C.id_) and'.
               '       ((A.idWGamer_ ='.$id_.') or (A.idBGamer_ ='.$id_.'))'.
               ' order by A.id_';
          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о незавершенных партиях произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
            $i =count($result_);
            $result_[$i]['num'] ='<A href="MainPage.php?link_=game&id='.$row_['id_'].'">'.$row_['id_'].'</A>';
            $w_ =convert_cyr_string($row_['whiteLogin'],'d','w');
            $b_ =convert_cyr_string($row_['blackLogin'],'d','w');
            $result_[$i]['white-black'] ='<A href="MainPage.php?link_=about_gamer&login_='.urlencode($w_).'">'.
                                               htmlspecialchars($w_).
                                         '</A>'.
                                         ' - '.
                                         '<A href="MainPage.php?link_=about_gamer&login_='.urlencode($b_).'">'.
                                               htmlspecialchars($b_).
                                         '</A>';
            if (is_null($row_['num_']) || ($row_['num_'] =='')) $result_[$i]['num_moves'] ='&nbsp;';
              else $result_[$i]['num_moves'] =$row_['num_'];
            $result_[$i]['reglament'] =$reglaments_['reglament'.$row_['reglament_'].'_'];
            if ($row_['isMoveWhite_'] == 'Y'){
              $result_[$i]['clockBlack_'] = clockToStr($row_['clockBlack_']);
              $t =$row_['clockWhite_']; if ($row_['beginMove_'] !=0) $t -=($curr_time - $row_['beginMove_']);
              $result_[$i]['clockWhite_'] = clockToStr($t > 0 ? $t : 0);
            }else{
              $t =$row_['clockBlack_']; if ($row_['beginMove_'] !=0) $t -=($curr_time - $row_['beginMove_']);
              $result_[$i]['clockBlack_'] = clockToStr($t > 0 ? $t : 0);
              $result_[$i]['clockWhite_'] = clockToStr($row_['clockWhite_']);
            }
            if (is_null($row_['id_tournament']) || ($row_['id_tournament'] ==''))
              $result_[$i]['tournament'] = '&nbsp;';
             else{
              $result_[$i]['tournament'] ='номер: ';
              if (is_null($row_['system_']))
                $result_[$i]['tournament'] .='<A href="MainPage.php?link_=Tournament&id_='.$row_['id_tournament'].'">';
              else
                $result_[$i]['tournament'] .='<A href="MainPage.php?link_=swiss_Tournament&id_='.$row_['id_tournament'].'">';
              $result_[$i]['tournament'] .=$row_['id_tournament'].
                                           '</A>'.
                                           ' класс: '.
                                           $row_['class_tournament'];
             }
          }#while
          mysql_free_result($cursor_);

          return $result_;
        }#get_list_active_games

        protected static function Body_Non_End_Game($id_){
          $table_ =CAboutGamer_::get_list_active_games($id_);

          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                   '  Незавершенные партии'.
                   '</DIV><BR>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                   '  <TABLE style="margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                   '     <COL span="7">'."\n".
                   '     <TR><TD class="table_head_1">№</TD>'."\n".
                   '         <TD class="table_head_1">турнир</TD>'."\n".
                   '         <TD class="table_head_1">белые-черные</TD>'."\n".
                   '         <TD class="table_head_1">сделано ходов</TD>'."\n".
                   '         <TD class="table_head_1">регламент</TD>'."\n".
                   '         <TD class="table_head_1">часы белых</TD>'."\n".
                   '         <TD class="table_head_1">часы черных</TD>'."\n".
                   '     </TR>';
          for ($i =0; $i < count($table_); $i++){
            $result_ .='     <TR><TD class="table_body_1">'.$table_[$i]['num'].'</TD>'."\n".
                       '         <TD class="table_body_1">'.$table_[$i]['tournament'].'</TD>'."\n".
                       '         <TD class="table_body_1">'.$table_[$i]['white-black'].'</TD>'."\n".
                       '         <TD class="table_body_1">'.$table_[$i]['num_moves'].'</TD>'."\n".
                       '         <TD class="table_body_1">'.$table_[$i]['reglament'].'</TD>'."\n".
                       '         <TD class="table_body_1">'.$table_[$i]['clockWhite_'].'</TD>'."\n".
                       '         <TD class="table_body_1">'.$table_[$i]['clockBlack_'].'</TD>'."\n".
                       '     </TR>'."\n";
          }#for
          $result_ .='  </TABLE>'."\n".
                     '</DIV>'."\n";

          return $result_;
        }#Body_Non_End_Game

        protected static function Body_End_Game($params_,$page_,$id_,$login_){
            global $reglaments_;

            $c =new CListEndGames($params_,$id_);
            $c->get_records($page_);


          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'."\n".
                         'Завершенные партии'."\n".
                    '</DIV><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:left;'.
                                 'text-decoration: none; font-weight: normal">'."\n".
                         '<SPAN style="color: white">'.
                           'Параметры поиска<BR>'.
                         '</SPAN>'."\n";
          $result_ .=    '<FORM action="'.CListEndGames::get_link(CListEndGames::empty_params(), $page_, $login_).'" method="POST">'."\n".
                         '  <TABLE>'."\n".
                         '    <COL span="2">'."\n".
                         '    <TR>'."\n".
                         '      <TD>логин соперника</TD>'."\n".
                         '      <TD>'."\n".
                         '         <INPUT type="text" id="find_login_" name="find_login_" value="'.htmlspecialchars($params_['login']).'" autocomplete="off">'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>регламент</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_reglament">'."\n".
                         '           <OPTION '.(($params_['reglament'] == '')   ? 'selected' : '').' value="">любой</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 1)   ? 'selected' : '').' value="1">'.  $reglaments_['reglament1_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 2)   ? 'selected' : '').' value="2">'.  $reglaments_['reglament2_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 3)   ? 'selected' : '').' value="3">'.  $reglaments_['reglament3_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 4)   ? 'selected' : '').' value="4">'.  $reglaments_['reglament4_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 5)   ? 'selected' : '').' value="5">'.  $reglaments_['reglament5_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 6)   ? 'selected' : '').' value="6">'.  $reglaments_['reglament6_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 7)   ? 'selected' : '').' value="7">'.  $reglaments_['reglament7_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 8)   ? 'selected' : '').' value="8">'.  $reglaments_['reglament8_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 9)   ? 'selected' : '').' value="9">'.  $reglaments_['reglament9_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 10) ? 'selected' : '').' value="10">'.$reglaments_['reglament10_'].'</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>класс</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_class_">'."\n".
                         '           <OPTION '.(($params_['class'] == '') ? 'selected' : '').' value="">любой</OPTION>'."\n".
                         '           <OPTION '.(($params_['class'] == 'A') ? 'selected' : '').' value="A">A</OPTION>'."\n".
                         '           <OPTION '.(($params_['class'] == 'B') ? 'selected' : '').' value="B">B</OPTION>'."\n".
                         '           <OPTION '.(($params_['class'] == 'C') ? 'selected' : '').' value="C">C</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n";

          if (!CUsers_::Read_dhtml_())
               $result_ .='<TR>'."\n".
                          '   <TD colspan="2">'."\n".
                          '     Если установить флаг DHTML в разделе "настройки", то поле "логин участника" '.
                          '     будет содержать выпадающий список, который появлятся при нажатии клавиш.'.
                          '   </TD>'."\n".
                          '</TR>'."\n";
           else
               $result_ .='<SCRIPT type="text/javascript" src="scripts/json2.js"></SCRIPT>'."\n".
                          '<SCRIPT type="text/javascript" src="scripts/hints_.js"></SCRIPT>'."\n".
                          '<SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n".
                          '<SCRIPT type="text/javascript">'."\n".
                          '  var find_o_hints_;'.
                          '  window.onload =function(){'.
                          '       find_o_hints_ =new cl_hints_(document.getElementById("find_login_"),5,"ajax_tournaments_.php");'."\n".
                          '                           }'."\n".
                          '</SCRIPT>'."\n";
          $result_ .='    <TR><TD colspan="2">'."\n".
                     '          <INPUT type="submit" value="Поиск">'."\n".
                     '        </TD>'."\n".
                     '    </TR>'."\n".
                     '  </TABLE>'."\n".
                     '</FORM>';

          if (count($c->records_) > 0)
               $result_ .=$c->out_records($login_);
            else
               $result_ .='<DIV style="text-align: center">партии не найдены</DIV>';
          $result_ .='</DIV>';

          return $result_;
        }#Body_End_Game

        protected static function get_list_active_tournaments($id_){
          global $reglaments_;
          $result_ =array();

          $s_ ='select A.id_,A.cGamers_,A.reglament_,A.begin_,A.class_,count(Z.num_) as regGamers_,A.system_'.
               ' from TTournaments_ A left join TMembersTournament_ Z on (A.id_ =Z.id_tournament_)'.
               ' where (A.end_ is null) and'.
               '        exists (select * '.
                              ' from TMembersTournament_ B'.
                              ' where (B.id_tournament_ = A.id_) and (B.id_gamer_ ='.$id_.'))'.
               ' group by A.id_,A.cGamers_,A.reglament_,A.begin_,A.class_'.
               ' order by A.begin_';

          $cursor_=mysql_query($s_,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о незавершенных турнирах произошла ошибка.');
          while ($row_ =mysql_fetch_array($cursor_)){
            $i =count($result_);
            if (is_null($row_['system_']))
                $result_[$i]['num'] ='<A href="MainPage.php?link_=Tournament&id_='.$row_['id_'].'">'.$row_['id_'].'</A>';
             else
                $result_[$i]['num'] ='<A href="MainPage.php?link_=swiss_Tournament&id_='.$row_['id_'].'">'.$row_['id_'].'</A>';
            $result_[$i]['class']     =$row_['class_'];
            $result_[$i]['reglament'] =htmlspecialchars($reglaments_['reglament'.$row_['reglament_'].'_']);
            $result_[$i]['cGamers']   =$row_['cGamers_'];
            if (is_null($row_['begin_']) || ($row_['begin_'] ==''))
               $result_[$i]['begin_'] ='';
             else
               $result_[$i]['begin_'] =htmlspecialchars($row_['begin_']);
          }#while
          mysql_free_result($cursor_);

          return $result_;
        }#get_list_active_tournaments

        protected static function Body_Active_Tournaments($id_){
          $table_ =CAboutGamer_::get_list_active_tournaments($id_);

          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                   '  Незавершенные турниры'.
                   '</DIV><BR>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                   '  <TABLE style="margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                   '     <COL span="7">'."\n".
                   '     <TR><TD class="table_head_1">№</TD>'."\n".
                   '         <TD class="table_head_1">класс</TD>'."\n".
                   '         <TD class="table_head_1">регламент</TD>'."\n".
                   '         <TD class="table_head_1">кол-во игроков</TD>'."\n".
                   '         <TD class="table_head_1">турнир начат</TD>'."\n".
                   '     </TR>';
          for ($i =0; $i < count($table_); $i++){
            $result_ .='     <TR><TD class="table_body_1">'.$table_[$i]['num'].'</TD>'."\n".
                       '         <TD class="table_body_1">'.$table_[$i]['class'].'</TD>'."\n".
                       '         <TD class="table_body_1">'.$table_[$i]['reglament'].'</TD>'."\n".
                       '         <TD class="table_body_1">'.$table_[$i]['cGamers'].'</TD>'."\n".
                       '         <TD class="table_body_1">'.$table_[$i]['begin_'].'</TD>'."\n".
                       '     </TR>'."\n";
          }#for
          $result_ .='  </TABLE>'."\n".
                     '</DIV>'."\n";

          return $result_;
        }#Body_Active_Tournaments

        protected static function Body_End_Tournaments($params_,$page_,$id_,$login_){
            global $reglaments_;

            $c =new CListEndTournaments($params_,$id_);
            $c->get_records($page_);


          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'."\n".
                         'Завершенные партии'."\n".
                    '</DIV><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:left;'.
                                 'text-decoration: none; font-weight: normal">'."\n".
                         '<SPAN style="color: white">'.
                           'Параметры поиска<BR>'.
                         '</SPAN>'."\n";
          $result_ .=    '<FORM action="'.CListEndTournaments::get_link(CListEndTournaments::empty_params(), $page_, $login_).'" method="POST">'."\n".
                         '  <TABLE>'."\n".
                         '    <COL span="2">'."\n".
                         '    <TR>'."\n".
                         '      <TD>регламент</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_reglament">'."\n".
                         '           <OPTION '.(($params_['reglament'] == '')   ? 'selected' : '').' value="">любой</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 1)   ? 'selected' : '').' value="1">'.  $reglaments_['reglament1_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 2)   ? 'selected' : '').' value="2">'.  $reglaments_['reglament2_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 3)   ? 'selected' : '').' value="3">'.  $reglaments_['reglament3_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 4)   ? 'selected' : '').' value="4">'.  $reglaments_['reglament4_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 5)   ? 'selected' : '').' value="5">'.  $reglaments_['reglament5_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 6)   ? 'selected' : '').' value="6">'.  $reglaments_['reglament6_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 7)   ? 'selected' : '').' value="7">'.  $reglaments_['reglament7_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 8)   ? 'selected' : '').' value="8">'.  $reglaments_['reglament8_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 9)   ? 'selected' : '').' value="9">'.  $reglaments_['reglament9_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 10) ? 'selected' : '').' value="10">'.$reglaments_['reglament10_'].'</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>класс</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_class_">'."\n".
                         '           <OPTION '.(($params_['class'] == '') ? 'selected' : '').' value="">все</OPTION>'."\n".
                         '           <OPTGROUP label="класс A">'."\n".
                         '              <OPTION '.(($params_['class'] == 'A8') ? 'selected' : '').' value="A8">A8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'A7') ? 'selected' : '').' value="A7">A7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'A6') ? 'selected' : '').' value="A6">A6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'A5') ? 'selected' : '').' value="A5">A5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'A4') ? 'selected' : '').' value="A4">A4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'A3') ? 'selected' : '').' value="A3">A3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'A1') ? 'selected' : '').' value="A2">A2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'A1') ? 'selected' : '').' value="A1">A1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="класс B">'."\n".
                         '              <OPTION '.(($params_['class'] == 'B8') ? 'selected' : '').' value="B8">B8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'B7') ? 'selected' : '').' value="B7">B7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'B6') ? 'selected' : '').' value="B6">B6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'B5') ? 'selected' : '').' value="B5">B5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'B4') ? 'selected' : '').' value="B4">B4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'B3') ? 'selected' : '').' value="B3">B3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'B2') ? 'selected' : '').' value="B2">B2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'B1') ? 'selected' : '').' value="B1">B1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="класс C">'."\n".
                         '              <OPTION '.(($params_['class'] == 'C8') ? 'selected' : '').' value="C8">C8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'C7') ? 'selected' : '').' value="C7">C7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'C6') ? 'selected' : '').' value="C6">C6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'C5') ? 'selected' : '').' value="C5">C5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'C4') ? 'selected' : '').' value="C4">C4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'C3') ? 'selected' : '').' value="C3">C3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'C2') ? 'selected' : '').' value="C2">C2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class'] == 'C1') ? 'selected' : '').' value="C1">C1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR><TD colspan="2">'."\n".
                         '          <INPUT type="submit" value="Поиск">'."\n".
                         '        </TD>'."\n".
                         '    </TR>'."\n".
                         '  </TABLE>'."\n".
                         '</FORM>';

          if (count($c->records_) > 0)
               $result_ .=$c->out_records($login_);
            else
               $result_ .='<DIV style="text-align: center">турниры не найдены</DIV>';
          $result_ .='</DIV>';

          return $result_;
        }#Body_End_Tournaments

#$type_: 1 - общая информация, 2 - статистика, 3 - фотография, 4 - активные партии,
#        5 - завершенные партии, 6 - активные турниры, 7 - завершенные турниры
        public static function MakePage(){
            unset($_SESSION[SESSION_LINK_ESC_INFO_TOURNAMENT]);
            $link_esc_info_tournament='';
            unset($_SESSION[SESSION_LINK_ESC_GAME]);
            $link_esc_game='';

            $connect_ =false;
            $transact_ =false;
            try{
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

                if (!isset($_GET['login_'])) throw new Exception('Логин не указан.');
                $login_ = $_GET['login_'];
                $id_ =CUsers_::Read_id_($login_);

                if (!isset($_GET['type_']) || !ctype_digit($_GET['type_'])) $type_ =1;
                  else $type_ =$_GET['type_'];

                switch ($type_){
                    case 1 :
                        $body_ =CAboutGamer_::BodyGeneralInfo($id_);
                        break;
                    case 2 :
                        $body_ =CAboutGamer_::BodyStatistic($id_);
                        break;
                    case 3 :
                        $body_ =CAboutGamer_::BodyPhoto($id_);
                        break;
                    case 4 :
                        $body_ =CAboutGamer_::Body_Non_End_Game($id_);
                        $link_esc_info_tournament='MainPage.php?link_=about_gamer&type_=4&login_='.urlencode($login_);
                        $link_esc_game='MainPage.php?link_=about_gamer&type_=4&login_='.urlencode($login_);
                        break;
                    case 5 :
                        if (!isset($_GET['page']) || !ctype_digit($_GET['page']))
                            $p=1;
                         else $p =$_GET['page'];

                        $params_['class'] ='';
                        if (isset($_REQUEST['find_class_']))  $params_['class'] =$_REQUEST['find_class_'];
                        $params_['reglament'] ='';
                        if (isset($_REQUEST['find_reglament']))  $params_['reglament'] =$_REQUEST['find_reglament'];
                        $params_['login'] ='';
                        if (isset($_REQUEST['find_login_']))  $params_['login'] =$_REQUEST['find_login_'];
                        $body_ =CAboutGamer_::Body_End_Game($params_,$p,$id_,$login_);
                        $link_esc_info_tournament=CListEndGames::get_link($params_, $p, $login_);
                        $link_esc_game=CListEndGames::get_link($params_, $p, $login_);
                        break;
                    case 6 :
                        $body_ =CAboutGamer_::Body_Active_Tournaments($id_);
                        $link_esc_info_tournament='MainPage.php?link_=about_gamer&type_=6&login_='.urlencode($login_);
                        break;
                    case 7 :
                        if (!isset($_GET['page']) || !ctype_digit($_GET['page']))
                            $p=1;
                         else $p =$_GET['page'];

                        $params_['class'] ='';
                        if (isset($_REQUEST['find_class_']))  $params_['class'] =$_REQUEST['find_class_'];
                        $params_['reglament'] ='';
                        if (isset($_REQUEST['find_reglament']))  $params_['reglament'] =$_REQUEST['find_reglament'];
                        $body_ =CAboutGamer_::Body_End_Tournaments($params_,$p,$id_,$login_);
                        $link_esc_info_tournament=CListEndTournaments::get_link($params_, $p, $login_);
                        break;
                }#switch

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
                if ($connect_)const_::Disconnect_();

                CPage_::$header_ ='<DIV id="text_login_">'.
                                  '  логин: '.$_SESSION[SESSION_LOGIN_].'<BR>'.
                                  '  класс: A'.$classA_.'/B'.$classB_.'/C'.$classC_.
                                  '  рейтинг: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'<BR>'.
                                  '</DIV>'   .
                                  '<DIV id="text_header_">'.
                                  '  Информация об '.htmlspecialchars($_GET['login_']).
                                  '</DIV>';
                CAboutGamer_::MakeMenuMainPage($type_,$login_);
                CPage_::$body_ =$body_;
                CPage_::MakePage();

                if ($link_esc_info_tournament !='')
                   $_SESSION[SESSION_LINK_ESC_INFO_TOURNAMENT]=$link_esc_info_tournament;
                if ($link_esc_game !='')
                   $_SESSION[SESSION_LINK_ESC_GAME]=$link_esc_game;
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
                CPage_::PageErr();
            }#try
        }#MakePage
    }#CAboutGamer_
?>