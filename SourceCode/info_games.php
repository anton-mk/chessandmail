<?php
    require_once('const_.php');
    require_once('Users.php');
    require_once('lib/mylib.php');

    class CListEndGames_info_games extends CPartOfQuery_{
/*     params_['login'] - логин,
       params_['reglament'] - регламент,
       params_['class'] - класс,
*/
       protected $params_;
       protected $id_;
       public $records_ =array();

       public static function empty_params(){
           $result_['login_1'] ='';
           $result_['color_1'] ='';
           $result_['result_1'] ='';
           $result_['login_2'] ='';
           $result_['color_2'] ='';
           $result_['result_2'] ='';
           $result_['reglament'] ='';
           $result_['class'] ='';
           return $result_;
       }#empty_params

       public static function get_link($params_,$page_){
            $href_='MainPage.php?link_=you_end_games';

            if ($params_['login_1'] != '')
              $href_ .='&find_login_1='.urlencode($params_['login_1']);
            if ($params_['color_1'] != '')
              $href_ .='&find_color_1='.$params_['color_1'];
            if ($params_['result_1'] != '')
              $href_ .='&find_result_1='.$params_['result_1'];
            if ($params_['login_2'] != '')
              $href_ .='&find_login_2='.urlencode($params_['login_2']);
            if ($params_['color_2'] != '')
              $href_ .='&find_color_2='.$params_['color_2'];
            if ($params_['result_2'] != '')
              $href_ .='&find_result_2='.$params_['result_2'];

            if ($params_['reglament'] != '')
              $href_ .='&find_reglament='.$params_['reglament'];
            if ($params_['class'] != '')
              $href_ .='&find_class='.$params_['class'];

            $href_ .='&page='.$page_;
            return $href_;
       }#get_link

       public function __construct($params_){
            $this->params_ =$params_;
            parent::__construct(const_::$connect_);
       }#__construct

       protected function get_where(&$where_){
            $where_='';
            if ($this->params_['class'] != '')
              $where_ ='(A.class_ =\''.$this->params_['class'].'\')';
            if ($this->params_['login_1'] != ''){
              if ($where_ !='') $where_ .=' and ';
              if ($this->params_['color_1'] == 'white'){
                  $where_ .='(B.login_=\''.mysql_escape_string($this->params_['login_1']).'\')';
                  if ($this->params_['result_1'] == 'victory')
                      $where_ .=' and (A.result_=1)';
                   else if ($this->params_['result_1'] == 'defeat')
                      $where_ .=' and (A.result_=0)';
                   else if ($this->params_['result_1'] == 'drawn')
                      $where_ .=' and (A.result_=2)';
              }else if ($this->params_['color_1'] == 'black'){
                  $where_ .='(C.login_=\''.mysql_escape_string($this->params_['login_1']).'\')';
                  if ($this->params_['result_1'] == 'victory')
                      $where_ .=' and (A.result_=0)';
                   else if ($this->params_['result_1'] == 'defeat')
                      $where_ .=' and (A.result_=1)';
                   else if ($this->params_['result_1'] == 'drawn')
                      $where_ .=' and (A.result_=2)';
              }else{
                  if ($this->params_['result_1'] == 'victory')
                      $where_ .='(((B.login_=\''.mysql_escape_string($this->params_['login_1']).'\') and (A.result_ =1)) or '.
                                       '  ((C.login_=\''.mysql_escape_string($this->params_['login_1']).'\') and (A.result_ =0)))';
                  else if ($this->params_['result_1'] == 'defeat')
                      $where_ .='(((B.login_=\''.mysql_escape_string($this->params_['login_1']).'\') and (A.result_ =0)) or '.
                                       '  ((C.login_=\''.mysql_escape_string($this->params_['login_1']).'\') and (A.result_ =1)))';
                  else if ($this->params_['result_1'] == 'drawn')
                      $where_ .='((B.login_=\''.mysql_escape_string($this->params_['login_1']).'\') or '.
                                       '  (C.login_=\''.mysql_escape_string($this->params_['login_1']).'\')) and (A.result_ =2)';
                  else
                      $where_ .='((B.login_=\''.mysql_escape_string($this->params_['login_1']).'\') or '.
                                ' (C.login_=\''.mysql_escape_string($this->params_['login_1']).'\'))';

              }
            }
            if ($this->params_['login_2'] != ''){
              if ($where_ !='') $where_ .=' and ';
              if ($this->params_['color_2'] == 'white'){
                  $where_ .='(B.login_=\''.mysql_escape_string($this->params_['login_2']).'\')';
                  if ($this->params_['result_2'] == 'victory')
                      $where_ .=' and (A.result_=1)';
                   else if ($this->params_['result_2'] == 'defeat')
                      $where_ .=' and (A.result_=0)';
                   else if ($this->params_['result_2'] == 'drawn')
                      $where_ .=' and (A.result_=2)';
              }else if ($this->params_['color_2'] == 'black'){
                  $where_ .='(C.login_=\''.mysql_escape_string($this->params_['login_2']).'\')';
                  if ($this->params_['result_2'] == 'victory')
                      $where_ .=' and (A.result_=0)';
                   else if ($this->params_['result_2'] == 'defeat')
                      $where_ .=' and (A.result_=1)';
                   else if ($this->params_['result_2'] == 'drawn')
                      $where_ .=' and (A.result_=2)';
              }else{
                  if ($this->params_['result_2'] == 'victory')
                      $where_ .='(((B.login_=\''.mysql_escape_string($this->params_['login_2']).'\') and (A.result_ =1)) or '.
                                       '  ((C.login_=\''.mysql_escape_string($this->params_['login_2']).'\') and (A.result_ =0)))';
                  else if ($this->params_['result_2'] == 'defeat')
                      $where_ .='(((B.login_=\''.mysql_escape_string($this->params_['login_2']).'\') and (A.result_ =0)) or '.
                                       '  ((C.login_=\''.mysql_escape_string($this->params_['login_2']).'\') and (A.result_ =1)))';
                  else if ($this->params_['result_2'] == 'drawn')
                      $where_ .='((B.login_=\''.mysql_escape_string($this->params_['login_2']).'\') or '.
                                       '  (C.login_=\''.mysql_escape_string($this->params_['login_2']).'\')) and (A.result_ =2)';
                  else
                      $where_ .='((B.login_=\''.mysql_escape_string($this->params_['login_2']).'\') or '.
                                '  (C.login_=\''.mysql_escape_string($this->params_['login_2']).'\'))';
              }
            }
            if ($this->params_['reglament'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='(A.reglament_ ='.$this->params_['reglament'].')';
            }
       }#get_where

       protected function str_select_for_countPage(){
            $result_ ='select count(*) as count_'.
                      ' from TGames_ A, TGamers_ B, TGamers_ C'.
                      ' where (not A.result_ is null) and (A.idWGamer_ = B.id_) and (A.idBGamer_ = C.id_)';
            $where_ ='';
            $this->get_where($where_);
            if ($where_ !='') $result_ .=' and '.$where_;
            return $result_;
       } #str_select_for_countPage

       protected function str_select_for_getRecords(){
            $result_ ='select A.id_,B.login_ as wlogin_,C.login_ as blogin_,A.reglament_,A.result_,'.
                      '       A.clockWhite_,A.clockBlack_,A.gameIsRating_,D.id_tournament_,'.
                      '       A.class_ as class_game, E.class_ as class_tournament,F.num_,E.system_'.
                      ' from TGames_ A left join TGamesTournament_ D on (A.id_=D.id_game)'.
                      '                left join TTournaments_ E on (D.id_tournament_ = E.id_)'.
                      '                left join TMoves_ F on (A.id_ =F.idGame_) and '.
                      '                                       not exists (select * from TMoves_ where (A.id_ = idGame_) and (num_ > F.num_)),'.
                      '      TGamers_ B, TGamers_ C'.
                      ' where (not A.result_ is null) and (A.idWGamer_ = B.id_) and (A.idBGamer_ = C.id_)';
            $where_ ='';
            $this->get_where($where_);
            if ($where_ !='') $result_ .=' and '.$where_;
            $result_ .=' order by A.class_,E.class_,A.id_';
            return $result_;
        }#str_select_for_getRecords

       public function get_records($page_){
            global $reglaments_;
            try{
                if (!$this->getRecords(false,$page_,array('id_','wlogin_','blogin_','reglament_',
                                                          'result_','clockWhite_','clockBlack_',
                                                          'gameIsRating_','id_tournament_',
                                                          'class_game','class_tournament','num_','system_')))
                    throw new Exception();

                for($i=0; $i<count($this->listRecords); $i++){
                  $this->records_[$i]['num'] ='<A href="MainPage.php?link_=game&id='.$this->listRecords[$i]['id_'].'">'.$this->listRecords[$i]['id_'].'</A>';
                  $w_ =convert_cyr_string($this->listRecords[$i]['wlogin_'],'d','w');
                  $b_ =convert_cyr_string($this->listRecords[$i]['blogin_'],'d','w');
                  $this->records_[$i]['white-black'] ='<A href="MainPage.php?link_=about_gamer&login_='.urlencode($w_).'">'.
                                                         htmlspecialchars($w_).
                                                      '</A>'.
                                                      ' - '.
                                                      '<A href="MainPage.php?link_=about_gamer&login_='.urlencode($b_).'">'.
                                                         htmlspecialchars($b_).
                                                      '</A>';
                  if (is_null($this->listRecords[$i]['id_tournament_']))
                    $this->records_[$i]['tournament'] ='&nbsp;';
                  else{
                    if (is_null($this->listRecords[$i]['system_']))
                      $this->records_[$i]['tournament'] ='<A href="MainPage.php?link_=Tournament&id_='.$this->listRecords[$i]['id_tournament_'].'">';
                     else
                      $this->records_[$i]['tournament'] ='<A href="MainPage.php?link_=swiss_Tournament&id_='.$this->listRecords[$i]['id_tournament_'].'">';
                    $this->records_[$i]['tournament'] .=    $this->listRecords[$i]['id_tournament_'].
                                                         '</A>';
                  }
                  $this->records_[$i]['reglament']= $reglaments_['reglament'.$this->listRecords[$i]['reglament_'].'_'];
                  if ($this->listRecords[$i]['gameIsRating_'] == 'Y')
                    $this->records_[$i]['isRating'] ='да';
                  else
                    $this->records_[$i]['isRating'] ='нет';
                  $this->records_[$i]['clockWhite_'] = clockToStr($this->listRecords[$i]['clockWhite_']);
                  $this->records_[$i]['clockBlack_'] = clockToStr($this->listRecords[$i]['clockBlack_']);
                  $this->records_[$i]['class_game_'] =(!is_null($this->listRecords[$i]['class_game']) ? $this->listRecords[$i]['class_game'] : '&nbsp;');
                  $this->records_[$i]['class_tournament'] =(!is_null($this->listRecords[$i]['class_tournament']) ? $this->listRecords[$i]['class_tournament'] : '&nbsp;');
                  $this->records_[$i]['num_moves'] =(!is_null($this->listRecords[$i]['num_']) ? $this->listRecords[$i]['num_'] : '&nbsp;');
                  if ($this->listRecords[$i]['result_'] == 0) $this->records_[$i]['result_'] = '0:1';
                    elseif ($this->listRecords[$i]['result_'] == 1) $this->records_[$i]['result_'] = '1:0';
                    else $this->records_[$i]['result_'] = '1/2:1/2';
                } #for
            }catch(Exception $e){
                throw new Exception('При чтении информации о завершенных партиях произошла ошибка.');
            }
       }#get_records

       public function out_records(){
            $result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                   'font-size: 12pt; color: black;'.
                                   'text-decoration: none; font-weight: normal">'."\n".
                      '   <TABLE style ="border: none; margin-left: auto; margin-right: auto" cellspacing="0" cellpadding="0">'."\n".
                      '      <COL span="11">'."\n";
            $m ='';
            if ($this->cCountPages > 1){
                $a =$this->getFirstVisibleNum($this->page_);
                $b =$this->getLastVisibleNum($this->page_);
                if ($a > 1) $m ='<A href="'.CListEndGames_info_games::get_link($this->params_,$a-1).'">'.htmlspecialchars('<<').'</A>';
                for ($i=$a; $i <=$b; $i++){
                    if ($m != '') $m .='&nbsp;';
                    if ($this->page_ != $i) $m .='<A href="'.CListEndGames_info_games::get_link($this->params_,$i).'">'.$i.'</A>'; else $m.=$i;
                }#for
                if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.CListEndGames_info_games::get_link($this->params_,$b+1).'">'.htmlspecialchars('>>').'</A>';
                $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            }
            $result_ .='<TR><TD>'."\n".
                       '<TABLE cellspacing="3">'."\n".
                       '     <COL span="8">'."\n".
                       '     <TR><TD class="table_head_1">№</TD>'."\n".
                       '         <TD class="table_head_1">результат</TD>'."\n".
                       '         <TD class="table_head_1">класс</TD>'."\n".
                       '         <TD class="table_head_1">белые-черные</TD>'."\n".
                       '         <TD class="table_head_1">турнир</TD>'."\n".
                       '         <TD class="table_head_1">класс турнира</TD>'."\n".
                       '         <TD class="table_head_1">регламент</TD>'."\n".
                       '         <TD class="table_head_1">рейтинговая</TD>'."\n".
                       '         <TD class="table_head_1">часы белых</TD>'."\n".
                       '         <TD class="table_head_1">часы черных</TD>'."\n".
                       '         <TD class="table_head_1">сделано ходов</TD>'."\n".
                       '     </TR>';
            for($i=0; $i < count($this->records_); $i++){
                $result_ .='<TR>'.
                              '<TD class="table_body_1">'.$this->records_[$i]['num'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['result_'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['class_game_'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['white-black'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['tournament'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['class_tournament'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['reglament'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['isRating'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['clockWhite_'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['clockBlack_'].'</TD>'."\n".
                              '<TD class="table_body_1">'.$this->records_[$i]['num_moves'].'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='</TABLE>'."\n".
                       '</TD></TR>'."\n";
            if ($m != '')
              $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            $result_ .='</TABLE></SPAN>';
            return $result_;
       }#out_records
    }#CListEndGames_info_games

    class CInfo_Games_{
#$type_ - тоже, что и в MakePage()
        protected static function MakeMenuMainPage($type_){            $i =CPage_::PositionMenu_('Партии') +1;

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=you_active_games';
            CPage_::$menu_[$i]['image'] ='Image/label_active_games.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==1) ? 'Y' : 'N';

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=you_end_games&default=yes';
            CPage_::$menu_[$i]['image'] ='Image/label_end_tournaments.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==2) ? 'Y' : 'N';

            CPage_::MakeMenu_(CPage_::PositionMenu_('Партии'));
        }#MakeMenuMainPage

        protected static function get_active_ym(){
            global $reglaments_;
            $result_ =array();
            $s_ ='select A.id_,B.login_ as wlogin_,C.login_ as blogin_,A.reglament_,'.
                 '       A.clockWhite_,A.clockBlack_,A.beginMove_,A.isMoveWhite_,A.gameIsRating_,D.id_tournament_,'.
                 '       A.class_ as class_game, E.class_ as class_tournament,F.num_,E.system_'.
                 ' from TGames_ A left join TGamesTournament_ D on (A.id_=D.id_game)'.
                 '                left join TTournaments_ E on (D.id_tournament_ = E.id_)'.
                 '                left join TMoves_ F on (A.id_ =F.idGame_) and '.
                 '                                       not exists (select * from TMoves_ where (A.id_ = idGame_) and (num_ > F.num_)),'.
                 '      TGamers_ B, TGamers_ C'.
                 ' where (A.result_ is null) and (A.idWGamer_ = B.id_) and (A.idBGamer_ = C.id_) and'.
                 '       (((A.idWGamer_ ='.$_SESSION[SESSION_ID_].') and (A.isMoveWhite_ =\'Y\')) or'.
                 '        ((A.idBGamer_ ='.$_SESSION[SESSION_ID_].') and (A.isMoveWhite_ =\'N\')))'.
                 ' order by A.class_,E.class_,A.id_';
            $cursor_=mysql_query($s_,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о партиях, ожидающих Ваш ход, произошла ошибка.');
            while ($row_ =mysql_fetch_array($cursor_)){
                $i=count($result_);
                $result_[$i]['num'] ='<A href="MainPage.php?link_=game&id='.$row_['id_'].'">'.$row_['id_'].'</A>';
                $w_ =convert_cyr_string($row_['wlogin_'],'d','w');
                $b_ =convert_cyr_string($row_['blogin_'],'d','w');
                $result_[$i]['white-black'] ='<A href="MainPage.php?link_=about_gamer&login_='.urlencode($w_).'">'.
                                                 htmlspecialchars($w_).
                                             '</A>'.
                                             ' - '.
                                             '<A href="MainPage.php?link_=about_gamer&login_='.urlencode($b_).'">'.
                                                 htmlspecialchars($b_).
                                             '</A>';
                if (is_null($row_['id_tournament_']))
                    $result_[$i]['tournament'] ='&nbsp;';
                 else
                    if (is_null($row_['system_']))
                      $result_[$i]['tournament'] ='<A href="MainPage.php?link_=Tournament&id_='.$row_['id_tournament_'].'">';
                     else
                      $result_[$i]['tournament'] ='<A href="MainPage.php?link_=swiss_Tournament&id_='.$row_['id_tournament_'].'">';
                    $result_[$i]['tournament'] .=   $row_['id_tournament_'].
                                                  '</A>';
                $result_[$i]['reglament']= $reglaments_['reglament'.$row_['reglament_'].'_'];
                if ($row_['gameIsRating_'] == 'Y')
                    $result_[$i]['isRating'] ='да';
                 else
                    $result_[$i]['isRating'] ='нет';
                $curr_time =time();
                if ($row_['isMoveWhite_'] == 'Y'){
                    $result_[$i]['clockBlack_'] = clockToStr($row_['clockBlack_']);
                    $a =$row_['clockWhite_']; if ($row_['beginMove_'] !=0) $a -=($curr_time - $row_['beginMove_']);
                    $result_[$i]['clockWhite_'] = clockToStr($a > 0 ? $a : 0);
                }else{
                    $a =$row_['clockBlack_']; if ($row_['beginMove_'] !=0) $a -=($curr_time - $row_['beginMove_']);
                    $result_[$i]['clockBlack_'] = clockToStr($a > 0 ? $a : 0);
                    $result_[$i]['clockWhite_'] = clockToStr($row_['clockWhite_']);
                }
                $result_[$i]['class_game_'] =(!is_null($row_['class_game']) ? $row_['class_game'] : '&nbsp;');
                $result_[$i]['class_tournament'] =(!is_null($row_['class_tournament']) ? $row_['class_tournament'] : '&nbsp;');
                $result_[$i]['num_moves'] =(!is_null($row_['num_']) ? $row_['num_'] : '&nbsp;');
            }#while

            mysql_free_result($cursor_);
            return $result_;
        }#get_active_ym

        protected static function get_active_am(){
            global $reglaments_;
            $result_ =array();
            $s_ ='select A.id_,B.login_ as wlogin_,C.login_ as blogin_,A.reglament_,'.
                 '       A.clockWhite_,A.clockBlack_,A.beginMove_,A.isMoveWhite_,A.gameIsRating_,D.id_tournament_,'.
                 '       A.class_ as class_game, E.class_ as class_tournament,F.num_,E.system_'.
                 ' from TGames_ A left join TGamesTournament_ D on (A.id_=D.id_game)'.
                 '                left join TTournaments_ E on (D.id_tournament_ = E.id_)'.
                 '                left join TMoves_ F on (A.id_ =F.idGame_) and '.
                 '                                       not exists (select * from TMoves_ where (A.id_ = idGame_) and (num_ > F.num_)),'.
                 '      TGamers_ B, TGamers_ C'.
                 ' where (A.result_ is null) and (A.idWGamer_ = B.id_) and (A.idBGamer_ = C.id_) and'.
                 '       (((A.idWGamer_ ='.$_SESSION[SESSION_ID_].') and (A.isMoveWhite_ =\'N\')) or'.
                 '        ((A.idBGamer_ ='.$_SESSION[SESSION_ID_].') and (A.isMoveWhite_ =\'Y\')))'.
                 ' order by A.class_,E.class_,A.id_';
            $cursor_=mysql_query($s_,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о партиях, ожидающих Ваш ход, произошла ошибка.');
            while ($row_ =mysql_fetch_array($cursor_)){
                $i=count($result_);
                $result_[$i]['num'] ='<A href="MainPage.php?link_=game&id='.$row_['id_'].'">'.$row_['id_'].'</A>';
                $w_ =convert_cyr_string($row_['wlogin_'],'d','w');
                $b_ =convert_cyr_string($row_['blogin_'],'d','w');
                $result_[$i]['white-black'] ='<A href="MainPage.php?link_=about_gamer&login_='.urlencode($w_).'">'.
                                                 htmlspecialchars($w_).
                                             '</A>'.
                                             ' - '.
                                             '<A href="MainPage.php?link_=about_gamer&login_='.urlencode($b_).'">'.
                                                 htmlspecialchars($b_).
                                             '</A>';
                if (is_null($row_['id_tournament_']))
                    $result_[$i]['tournament'] ='&nbsp;';
                 else
                    if (is_null($row_['system_']))
                      $result_[$i]['tournament'] ='<A href="MainPage.php?link_=Tournament&id_='.$row_['id_tournament_'].'">';
                     else
                      $result_[$i]['tournament'] ='<A href="MainPage.php?link_=swiss_Tournament&id_='.$row_['id_tournament_'].'">';
                    $result_[$i]['tournament'] .=  $row_['id_tournament_'].
                                                  '</A>';
                $result_[$i]['reglament']= $reglaments_['reglament'.$row_['reglament_'].'_'];
                if ($row_['gameIsRating_'] == 'Y')
                    $result_[$i]['isRating'] ='да';
                 else
                    $result_[$i]['isRating'] ='нет';
                $curr_time =time();
                if ($row_['isMoveWhite_'] == 'Y'){
                    $result_[$i]['clockBlack_'] = clockToStr($row_['clockBlack_']);
                    $a =$row_['clockWhite_']; if ($row_['beginMove_'] !=0) $a -=($curr_time - $row_['beginMove_']);
                    $result_[$i]['clockWhite_'] = clockToStr($a > 0 ? $a : 0);
                }else{
                    $a =$row_['clockBlack_']; if ($row_['beginMove_'] !=0) $a -=($curr_time - $row_['beginMove_']);
                    $result_[$i]['clockBlack_'] = clockToStr($a > 0 ? $a : 0);
                    $result_[$i]['clockWhite_'] = clockToStr($row_['clockWhite_']);
                }
                $result_[$i]['class_game_'] =(!is_null($row_['class_game']) ? $row_['class_game'] : '&nbsp;');
                $result_[$i]['class_tournament'] =(!is_null($row_['class_tournament']) ? $row_['class_tournament'] : '&nbsp;');
                $result_[$i]['num_moves'] =(!is_null($row_['num_']) ? $row_['num_'] : '&nbsp;');
            }#while

            mysql_free_result($cursor_);
            return $result_;
        }#get_active_am

        protected static function BodyActive(){
          $table_1 =CInfo_Games_::get_active_ym();
          $table_2 =CInfo_Games_::get_active_am();

          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                   '  Партии, ожидающие Ваш ход'.
                   '</DIV>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n";
          if (count($table_1) == 0)
              $result_ .='<BR/><DIV style="text-align:center">Партий нет</DIV>';
          else{
              $result_ .='  <TABLE style="margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                         '     <COL span="10">'."\n".
                         '     <TR><TD class="table_head_1">№</TD>'."\n".
                         '         <TD class="table_head_1">класс</TD>'."\n".
                         '         <TD class="table_head_1">белые-черные</TD>'."\n".
                         '         <TD class="table_head_1">турнир</TD>'."\n".
                         '         <TD class="table_head_1">класс турнира</TD>'."\n".
                         '         <TD class="table_head_1">регламент</TD>'."\n".
                         '         <TD class="table_head_1">рейтинговая</TD>'."\n".
                         '         <TD class="table_head_1">часы белых</TD>'."\n".
                         '         <TD class="table_head_1">часы черных</TD>'."\n".
                         '         <TD class="table_head_1">сделано ходов</TD>'."\n".
                         '     </TR>';
              for ($i=0; $i < count($table_1); $i++){
                 $result_ .='     <TR><TD class="table_body_1">'.$table_1[$i]['num'].'</TD>'."\n".
                            '         <TD class="table_body_1">'.$table_1[$i]['class_game_'].'</TD>'."\n".
                            '         <TD class="table_body_1">'.$table_1[$i]['white-black'].'</TD>'."\n".
                            '         <TD class="table_body_1">'.$table_1[$i]['tournament'].'</TD>'."\n".
                            '         <TD class="table_body_1">'.$table_1[$i]['class_tournament'].'</TD>'."\n".
                            '         <TD class="table_body_1">'.$table_1[$i]['reglament'].'</TD>'."\n".
                            '         <TD class="table_body_1">'.$table_1[$i]['isRating'].'</TD>'."\n".
                            '         <TD class="table_body_1">'.$table_1[$i]['clockWhite_'].'</TD>'."\n".
                            '         <TD class="table_body_1">'.$table_1[$i]['clockBlack_'].'</TD>'."\n".
                            '         <TD class="table_body_1">'.$table_1[$i]['num_moves'].'</TD>'."\n".
                            '     </TR>'."\n";
              }
              $result_ .='  </TABLE>'."\n";
          }
          $result_ .='</DIV><BR>'."\n".
                     '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 16pt; color: white; text-align: center;'.
                                 'text-decoration: none; font-weight: normal">'.
                     '  Партии, ожидающие ход соперника'.
                     '</DIV>'."\n".
                     '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black;'.
                                 'text-decoration: none; font-weight: normal">'."\n";
          if (count($table_2) == 0)
             $result_ .='<DIV style="text-align: center">Партий нет</DIV>';
          else{
             $result_ .='  <TABLE style="margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                        '     <COL span="10">'."\n".
                        '     <TR><TD class="table_head_1">№</TD>'."\n".
                        '         <TD class="table_head_1">класс</TD>'."\n".
                        '         <TD class="table_head_1">белые-черные</TD>'."\n".
                        '         <TD class="table_head_1">турнир</TD>'."\n".
                        '         <TD class="table_head_1">класс турнира</TD>'."\n".
                        '         <TD class="table_head_1">регламент</TD>'."\n".
                        '         <TD class="table_head_1">рейтинговая</TD>'."\n".
                        '         <TD class="table_head_1">часы белых</TD>'."\n".
                        '         <TD class="table_head_1">часы черных</TD>'."\n".
                        '         <TD class="table_head_1">сделано ходов</TD>'."\n".
                        '     </TR>';
             for ($i=0; $i < count($table_2); $i++){
                $result_ .='     <TR><TD class="table_body_1">'.$table_2[$i]['num'].'</TD>'."\n".
                           '         <TD class="table_body_1">'.$table_2[$i]['class_game_'].'</TD>'."\n".
                           '         <TD class="table_body_1">'.$table_2[$i]['white-black'].'</TD>'."\n".
                           '         <TD class="table_body_1">'.$table_2[$i]['tournament'].'</TD>'."\n".
                           '         <TD class="table_body_1">'.$table_2[$i]['class_tournament'].'</TD>'."\n".
                           '         <TD class="table_body_1">'.$table_2[$i]['reglament'].'</TD>'."\n".
                           '         <TD class="table_body_1">'.$table_2[$i]['isRating'].'</TD>'."\n".
                           '         <TD class="table_body_1">'.$table_2[$i]['clockWhite_'].'</TD>'."\n".
                           '         <TD class="table_body_1">'.$table_2[$i]['clockBlack_'].'</TD>'."\n".
                           '         <TD class="table_body_1">'.$table_2[$i]['num_moves'].'</TD>'."\n".
                           '     </TR>'."\n";
             }
             $result_ .='  </TABLE>'."\n";
          }
          $result_ .='</DIV>'."\n";
          return $result_;
        }#BodyActive

        protected static function BodyEnd($params_,$page_){
          global $reglaments_;
          $c =new CListEndGames_info_games($params_);
          $c->get_records($page_);

          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'."\n".
                         'Завершенные партии'."\n".
                    '</DIV><BR><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:left;'.
                                 'text-decoration: none; font-weight: normal">'."\n".
                         '<SPAN style="color: white">'.
                           'Параметры поиска'.'<BR>'.
                         '</SPAN>'."\n".

                         '<FORM action="MainPage.php?link_=you_end_games&page_='.$page_.'" method="POST">'."\n".
                         '  <TABLE>'."\n".
                         '    <COL span="6">'."\n".
                         '    <TR>'."\n".
                         '      <TD>логин 1</TD>'."\n".
                         '      <TD>'."\n".
                         '        <INPUT type="text" id="find_login_1" name="find_login_1" value="'.htmlspecialchars($params_['login_1']).'" autocomplete="off">'."\n".
                         '      </TD>'."\n".
                         '      <TD>цвет</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_color_1">'."\n".
                         '           <OPTION '.(($params_['color_1'] == '') ? 'selected' : '').' value="">не учитывать</OPTION>'."\n".
                         '           <OPTION '.(($params_['color_1'] == 'white') ? 'selected' : '').' value="white">белые</OPTION>'."\n".
                         '           <OPTION '.(($params_['color_1'] == 'black') ? 'selected' : '').' value="black">черные</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '      <TD>результат</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_result_1">'."\n".
                         '           <OPTION '.(($params_['result_1'] == '') ? 'selected' : '').' value="">не учитывать</OPTION>'."\n".
                         '           <OPTION '.(($params_['result_1'] == 'victory') ? 'selected' : '').' value="victory">только выйгранные</OPTION>'."\n".
                         '           <OPTION '.(($params_['result_1'] == 'defeat') ? 'selected' : '').' value="defeat">только проигранные</OPTION>'."\n".
                         '           <OPTION '.(($params_['result_1'] == 'drawn') ? 'selected' : '').' value="drawn">сыгранные вничью</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>логин 2</TD>'."\n".
                         '      <TD>'."\n".
                         '         <INPUT type="text" id="find_login_2" name="find_login_2" value="'.htmlspecialchars($params_['login_2']).'" autocomplete="off">'."\n".
                         '      </TD>'."\n".
                         '      <TD>цвет</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_color_2">'."\n".
                         '           <OPTION '.(($params_['color_2'] == '') ? 'selected' : '').' value="">не учитывать</OPTION>'."\n".
                         '           <OPTION '.(($params_['color_2'] == 'white') ? 'selected' : '').' value="white">белые</OPTION>'."\n".
                         '           <OPTION '.(($params_['color_2'] == 'black') ? 'selected' : '').' value="black">черные</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '      <TD>результат</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_result_2">'."\n".
                         '           <OPTION '.(($params_['result_2'] == '') ? 'selected' : '').' value="">не учитывать</OPTION>'."\n".
                         '           <OPTION '.(($params_['result_2'] == 'victory') ? 'selected' : '').' value="victory">только выйгранные</OPTION>'."\n".
                         '           <OPTION '.(($params_['result_2'] == 'defeat') ? 'selected' : '').' value="defeat">только проигранные</OPTION>'."\n".
                         '           <OPTION '.(($params_['result_2'] == 'drawn') ? 'selected' : '').' value="drawn">сыгранные вничью</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD colspan="6">регламент&nbsp;'."\n".
                         '        <SELECT name="find_reglament">'."\n".
                         '           <OPTION '.(($params_['reglament'] == '')   ? 'selected' : '').' value="">любой</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 1)   ? 'selected' : '').'  value="1">'.  $reglaments_['reglament1_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 2)   ? 'selected' : '').'  value="2">'.  $reglaments_['reglament2_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 3)   ? 'selected' : '').'  value="3">'.  $reglaments_['reglament3_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 4)   ? 'selected' : '').'  value="4">'.  $reglaments_['reglament4_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 5)   ? 'selected' : '').'  value="5">'.  $reglaments_['reglament5_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 6)   ? 'selected' : '').'  value="6">'.  $reglaments_['reglament6_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 7)   ? 'selected' : '').'  value="7">'.  $reglaments_['reglament7_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 8)   ? 'selected' : '').'  value="8">'.  $reglaments_['reglament8_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 9)   ? 'selected' : '').'  value="9">'.  $reglaments_['reglament9_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament'] == 10) ? 'selected' : '').'   value="10">'.$reglaments_['reglament10_'].'</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD colspan="6">класс&nbsp;'."\n".
                         '        <SELECT name="find_class">'."\n".
                         '           <OPTION '.(($params_['class'] == '') ? 'selected' : '').' value="">любой</OPTION>'."\n".
                         '           <OPTION '.(($params_['class'] == 'A')? 'selected' : '').' value="A">A</OPTION>'."\n".
                         '           <OPTION '.(($params_['class'] == 'B')? 'selected' : '').' value="B">B</OPTION>'."\n".
                         '           <OPTION '.(($params_['class'] == 'C')? 'selected' : '').' value="C">C</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n";

          if (!CUsers_::Read_dhtml_())
               $result_ .='<TR>'."\n".
                          '   <TD colspan="5">'."\n".
                          '     Если установить флаг DHTML в разделе "настройки", то поля "логин" '.
                          '     будут содержать выпадающий список, который появлятся при нажатии клавиш.'.
                          '   </TD>'."\n".
                          '</TR>'."\n";
           else
               $result_ .='<SCRIPT type="text/javascript" src="scripts/json2.js"></SCRIPT>'."\n".
                          '<SCRIPT type="text/javascript" src="scripts/hints_.js"></SCRIPT>'."\n".
                          '<SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n".
                          '<SCRIPT type="text/javascript">'."\n".
                          '  var find_o_hints_1;'.
                          '  var find_o_hints_2;'.
                          '  window.onload =function(){'.
                          '       find_o_hints_1 =new cl_hints_(document.getElementById("find_login_1"),5,"ajax_tournaments_.php");'."\n".
                          '       find_o_hints_2 =new cl_hints_(document.getElementById("find_login_2"),5,"ajax_tournaments_.php");'."\n".
                          '                           }'."\n".
                          '</SCRIPT>'."\n";
          $result_ .='    <TR><TD colspan="5">'."\n".
                     '          <INPUT type="submit" value="Поиск">'."\n".
                     '        </TD>'."\n".
                     '    </TR>'."\n".
                     '  </TABLE>'."\n".
                     '</FORM>';

          if (count($c->records_) > 0)
               $result_ .=$c->out_records();
            else
               $result_ .='<DIV style="text-align: center">партии не найдены</DIV>';
          $result_ .='</DIV>';

          return $result_;
        }#BodyEnd

        public static function MakePage($type_){            unset($_SESSION[SESSION_LINK_ESC_INFO_SWISS_TOURNAMENT]);
            $link_esc_info_swiss_tournament='';
            unset($_SESSION[SESSION_LINK_ESC_INFO_TOURNAMENT]);
            $link_esc_info_tournament='';
            unset($_SESSION[SESSION_LINK_ESC_GAME]);
            $link_esc_game='';
            unset($_SESSION[SESSION_LINK_ESC_ABOUT_GAMER]);
            $link_esc_about_gamer='';
            unset($_SESSION[SESSION_LINK_ESC_DOC]);
            $link_esc_doc='';

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

                switch ($type_){
                    case 1 :
                        $body_ =CInfo_Games_::BodyActive();
                        $link_esc_info_tournament='MainPage.php?link_=you_active_games';
                        $link_esc_info_swiss_tournament='MainPage.php?link_=you_active_games';
                        $link_esc_game='MainPage.php?link_=you_active_games';
                        $link_esc_about_gamer='MainPage.php?link_=you_active_games';
                        $link_esc_doc='MainPage.php?link_=you_active_games';
                        break;
                    case 2 :
                        if (isset($_GET['default'])){
                            $params_ =CListEndGames_info_games::empty_params();
                            $params_['login_1'] =$_SESSION[SESSION_LOGIN_];
                            $p=1;
                        }else{
                          if (!isset($_GET['page']) || !ctype_digit($_GET['page']))
                              $p=1;
                           else $p =$_GET['page'];

                          $params_['class'] ='';
                          if (isset($_REQUEST['find_class'])) $params_['class'] =$_REQUEST['find_class'];
                          $params_['reglament'] ='';
                          if (isset($_REQUEST['find_reglament'])) $params_['reglament'] =$_REQUEST['find_reglament'];
                          $params_['login_1'] ='';
                          if (isset($_REQUEST['find_login_1'])) $params_['login_1'] =$_REQUEST['find_login_1'];
                          $params_['color_1'] ='';
                          if (isset($_REQUEST['find_color_1'])) $params_['color_1'] =$_REQUEST['find_color_1'];
                          $params_['result_1'] ='';
                          if (isset($_REQUEST['find_result_1'])) $params_['result_1'] =$_REQUEST['find_result_1'];
                          $params_['login_2'] ='';
                          if (isset($_REQUEST['find_login_2'])) $params_['login_2'] =$_REQUEST['find_login_2'];
                          $params_['color_2'] ='';
                          if (isset($_REQUEST['find_color_2'])) $params_['color_2'] =$_REQUEST['find_color_2'];
                          $params_['result_2'] ='';
                          if (isset($_REQUEST['find_result_2'])) $params_['result_2'] =$_REQUEST['find_result_2'];
                        }

                        $body_ =CInfo_Games_::BodyEnd($params_,$p);
                        $link_esc_info_tournament=CListEndGames_info_games::get_link($params_,$p);
                        $link_esc_info_swiss_tournament=CListEndGames_info_games::get_link($params_,$p);
                        $link_esc_game=CListEndGames_info_games::get_link($params_,$p);
                        $link_esc_about_gamer=CListEndGames_info_games::get_link($params_,$p);
                        $link_esc_doc=CListEndGames_info_games::get_link($params_,$p);
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
                                  '  Партии'.
                                  '</DIV>';
                CInfo_Games_::MakeMenuMainPage($type_);
                CPage_::$body_ =$body_;
                CPage_::MakePage();
                if ($link_esc_info_tournament !='')
                   $_SESSION[SESSION_LINK_ESC_INFO_TOURNAMENT]=$link_esc_info_tournament;
                if($link_esc_info_swiss_tournament !='')
                   $_SESSION[SESSION_LINK_ESC_INFO_SWISS_TOURNAMENT]=$link_esc_info_swiss_tournament;
                if ($link_esc_game !='')
                   $_SESSION[SESSION_LINK_ESC_GAME]=$link_esc_game;
                if ($link_esc_about_gamer !='')
                   $_SESSION[SESSION_LINK_ESC_ABOUT_GAMER]=$link_esc_about_gamer;
                if ($link_esc_doc !='')
                   $_SESSION[SESSION_LINK_ESC_DOC]=$link_esc_doc;
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
                CPage_::PageErr();
            }#try
       }#MakePage
    }#CInfo_Games_

?>
