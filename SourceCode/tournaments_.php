<?php
	require_once('lib/mylib.php');
	require_once('const_.php');
	require_once('Users.php');
   	require_once('info_tournament_.php');
    require_once('info_swiss_tournament_.php');

   class ETournamentError extends Exception{};

   class CActiveTournaments extends CPartOfQuery_{
#params_['class_'] - класс, params_['login_'] - логин участника, params_['system_'] - система турнира
       protected $params_;
       public $records_ =array();

       public static function get_link($params_,$page_){
            $href_='MainPage.php?link_=active_tournaments';
            if ($params_['class_'] != '')
              $href_ .='&find_class_='.$params_['class_'];
            if ($params_['login_'] != '')
              $href_ .='&find_login_='.urlencode($params_['login_']);
            if ($params_['system_'] != '')
              $href_ .='&system_='.$params_['system_'];
            $href_ .='&page='.$page_;
            return $href_;
       }#get_link

       public function __construct($params_){
            $this->params_ =$params_;
            parent::__construct(const_::$connect_);
       }#__construct

       protected function str_select_for_countPage(){
            $result_ ='select count(*) as count_'.
                      ' from TTournaments_ A'.
                      ' where (A.end_ is null)';
            if ($this->params_['class_'] != '')
              $result_ .=' and (trim(A.class_) =\''.$this->params_['class_'].'\')';
            if ($this->params_['login_'] != '')
              $result_ .=' and exists (select * '.
                                      ' from TMembersTournament_ B, TGamers_ C'.
                                      ' where (B.id_tournament_ = A.id_) and (B.id_gamer_ = C.id_) and'.
                                      '       (C.login_ =\''.mysql_real_escape_string($this->params_['login_'],const_::$connect_).'\'))';
            if ($this->params_['system_'] == '0')
              $result_ .=' and (A.system_ is null)';
             else if ($this->params_['system_'] == '1')
              $result_ .=' and (A.system_ =1)';

            return $result_;
       } #str_select_for_countPage

       protected function str_select_for_getRecords(){
            $result_ ='select A.id_,A.cGamers_,A.reglament_,A.begin_,A.class_,count(Z.num_) as regGamers_,A.system_'.
                      ' from TTournaments_ A left join TMembersTournament_ Z on (A.id_ =Z.id_tournament_)'.
                      ' where (A.end_ is null)';
            if ($this->params_['class_'] != '')
              $result_ .=' and (A.class_ =\''.$this->params_['class_'].'\')';
            if ($this->params_['login_'] != '')
              $result_ .=' and exists (select * '.
                                      ' from TMembersTournament_ B, TGamers_ C'.
                                      ' where (B.id_tournament_ = A.id_) and (B.id_gamer_ = C.id_) and'.
                                      '       (C.login_ =\''.mysql_real_escape_string($this->params_['login_'],const_::$connect_).'\'))';
            if ($this->params_['system_'] == '0')
              $result_ .=' and (A.system_ is null)';
             else if ($this->params_['system_'] == '1')
              $result_ .=' and (A.system_ =1)';

            $result_ .=' group by A.id_,A.cGamers_,A.reglament_,A.begin_,A.class_'.
                       ' order by A.begin_';
            return $result_;
        }#str_select_for_countPage

       public function get_records($page_){
            global $reglaments_;
            try{
                if (!$this->getRecords(false,$page_,array('id_','cGamers_','reglament_','begin_','class_','regGamers_','system_')))
                    throw new Exception();
                for($i=0; $i<count($this->listRecords); $i++){
                    $this->records_[$i]['id_']        =$this->listRecords[$i]['id_'];
                    $this->records_[$i]['cGamers_']   =$this->listRecords[$i]['regGamers_'].'/'.(is_null($this->listRecords[$i]['cGamers_']) ? '-' : $this->listRecords[$i]['cGamers_']);
                    $this->records_[$i]['reglament_'] =$reglaments_['reglament'.$this->listRecords[$i]['reglament_'].'_'];
                    $this->records_[$i]['begin_']     =$this->listRecords[$i]['begin_'];
                    $this->records_[$i]['class_']     =$this->listRecords[$i]['class_'];
                    $this->records_[$i]['system_']    =$this->listRecords[$i]['system_'];
                } #for

            }catch(Exception $e){
                throw new Exception('При чтении информации о турнирах произошла ошибка.');
            }
       }#get_records

       public function out_records(){
            $result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                                        'font-size: 12pt; color: black;'.
                                                        'text-decoration: none; font-weight: normal">'."\n".
                      '   <TABLE style ="border: none; margin-left: auto; margin-right: auto" cellspacing="0" cellpadding="0">'."\n".
                      '      <COL span="1">'."\n";
            $m ='';
            if ($this->cCountPages > 1){
                $a =$this->getFirstVisibleNum($this->page_);
                $b =$this->getLastVisibleNum($this->page_);
                if ($a > 1) $m ='<A href="'.CActiveTournaments::get_link($this->params_,$a-1).'">'.htmlspecialchars('<<').'</A>';
                for ($i=$a; $i <=$b; $i++){
                    if ($m != '') $m .='&nbsp;';
                    if ($this->page_ != $i) $m .='<A href="'.CActiveTournaments::get_link($this->params_,$i).'">'.$i.'</A>'; else $m.=$i;
                }#for
                if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.CActiveTournaments::get_link($this->params_,$b+1).'">'.htmlspecialchars('>>').'</A>';
                $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            }
            $result_ .='<TR><TD>'."\n".
                       '<TABLE cellspacing="3">'."\n".
                       '     <COL span="6">'."\n".
                       '     <TR><TD class="table_head_1">№</TD>'."\n".
                       '         <TD class="table_head_1">класс</TD>'."\n".
                       '         <TD class="table_head_1">регламент</TD>'."\n".
                       '         <TD class="table_head_1">кол-во игроков</TD>'."\n".
                       '         <TD class="table_head_1">начало турнира</TD>'."\n".
                       '         <TD class="table_head_1">система</TD>'."\n".
                       '     </TR>';
            for($i=0; $i < count($this->records_); $i++){
                if (!is_null($this->records_[$i]['system_']) && ($this->records_[$i]['system_'] ==1)){
                    $link_tournament ='swiss_Tournament';
                    $system_tournament ='швейцарская';
                }else{
                    $link_tournament ='Tournament';
                    $system_tournament ='круговая';
                }
                $result_ .='<TR><TD class="table_body_1"><A href="MainPage.php?link_='.$link_tournament.'&id_='.$this->records_[$i]['id_'].'">'.$this->records_[$i]['id_'].'</A></TD>'."\n".
                           '    <TD class="table_body_1"><A>'.htmlspecialchars($this->records_[$i]['class_']).'</A></TD>'."\n".
                           '    <TD class="table_body_1"><A>'.htmlspecialchars($this->records_[$i]['reglament_']).'</A></TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['cGamers_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.htmlspecialchars(($this->records_[$i]['begin_'] !='') ? $this->records_[$i]['begin_'] : '&nbsp').'</TD>'."\n".
                           '    <TD class="table_body_1">'.htmlspecialchars($system_tournament).'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='</TABLE>'."\n".
                       '</TD></TR>'."\n";
            if ($m != '')
              $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            $result_ .='</TABLE></SPAN>';
            return $result_;
       }#out_records
   }#CActiveTournaments

	class CEndTournaments extends CPartOfQuery_{
#params_['class_'] - класс, params_['login_'] - логин участника,
#params_['place_'] - место, params_['reglament_'] - регламент
#params_['system_'] - система
        protected $params_;
        public $records_ =array();

        public static function get_link($params_,$page_){
            $href_='MainPage.php?link_=end_tournaments';
            if ($params_['class_'] != '')
               $href_ .='&find_class_='.$params_['class_'];
            if ($params_['login_'] != '')
               $href_ .='&find_login_='.urlencode($params_['login_']);
            if ($params_['place_'] != '')
              $href_ .='&find_place_='.$params_['place_'];
            if ($params_['reglament_'] != '')
              $href_ .='&find_reglament_='.$params_['reglament_'];
            if ($params_['system_'] != '')
              $href_ .='&system_='.$params_['system_'];
             $href_ .='&page='.$page_;
             return $href_;
        }#get_link

        protected function str_select_for_countPage(){
            $result_ ='select count(*) as count_'.
                      ' from TTournaments_ A'.
                      ' where (not A.end_ is null)';
            if ($this->params_['class_'] != '')
              $result_ .=' and (A.class_ =\''.$this->params_['class_'].'\')';
            if ($this->params_['reglament_'] != '')
              $result_ .=' and (A.reglament_ ='.$this->params_['reglament_'].')';
            if ($this->params_['login_'] != ''){
              $result_ .=' and exists (select * '.
                                      ' from TMembersTournament_ B, TGamers_ C'.
                                      ' where (B.id_tournament_ = A.id_) and (B.id_gamer_ = C.id_) and'.
                                      '       (C.login_ =\''.mysql_escape_string($this->params_['login_']).'\')';
              if ($this->params_['place_'] != '')
                $result_ .=' and (((not B.place1_ is null) and (B.place2_ is null) and '.
                           '       (B.place1_ ='.$this->params_['place_'].')) or'.
                           '      ((not B.place1_ is null) and (not B.place2_ is null) and '.
                           '       (B.place1_ <='.$this->params_['place_'].') and '.
                           '       (B.place2_ >='.$this->params_['place_'].')))';
              $result_ .=')';
            }
            if ($this->params_['system_'] == '0')
              $result_ .=' and (A.system_ is null)';
             else if ($this->params_['system_'] == '1')
              $result_ .=' and (A.system_ =1)';

            return $result_;
        } #str_select_for_countPage

        protected function str_select_for_getRecords(){
            $result_ ='select A.id_,A.reglament_,A.cGamers_,A.begin_,A.end_,A.class_,A.system_';
            if ($this->params_['login_'] != '')
              $result_ .=',B.place1_,B.place2_';
            $result_ .=' from TTournaments_ A';
            if ($this->params_['login_'] != '')
              $result_ .=', TMembersTournament_ B, TGamers_ C';
            $result_ .=' where (not A.end_ is null)';
            if ($this->params_['class_'] != '')
              $result_ .=' and (A.class_ =\''.$this->params_['class_'].'\')';
            if ($this->params_['reglament_'] != '')
              $result_ .=' and (A.reglament_ ='.$this->params_['reglament_'].')';
            if ($this->params_['login_'] != ''){
              $result_ .=' and (B.id_tournament_ = A.id_) and (B.id_gamer_ = C.id_) and'.
                         '     (C.login_ =\''.mysql_escape_string($this->params_['login_']).'\')';
              if ($this->params_['place_'] != '')
                $result_ .=' and (((not B.place1_ is null) and (B.place2_ is null) and '.
                           '       (B.place1_ ='.$this->params_['place_'].')) or'.
                           '      ((not B.place1_ is null) and (not B.place2_ is null) and '.
                           '       (B.place1_ <='.$this->params_['place_'].') and '.
                           '       (B.place2_ >='.$this->params_['place_'].')))';
            }
            if ($this->params_['system_'] == '0')
              $result_ .=' and (A.system_ is null)';
             else if ($this->params_['system_'] == '1')
              $result_ .=' and (A.system_ =1)';
            $result_ .=' order by A.end_ desc';
			return $result_;
		} #str_select_for_countPage

       public function __construct($params_){
            $this->params_ =$params_;
            parent::__construct(const_::$connect_);
       }#__construct

       public function get_records($page_){
            global $reglaments_;
            try{
                if ($this->params_['login_'] != ''){
                  if (!$this->getRecords(false,$page_,array('id_','reglament_','cGamers_','begin_','end_','class_','system_','place1_','place2_')))
                      throw new Exception();
                }else
                  if (!$this->getRecords(false,$page_,array('id_','reglament_','cGamers_','begin_','end_','class_','system_')))
                      throw new Exception();

                for($i=0; $i<count($this->listRecords); $i++){
                    $this->records_[$i]['id_']        =$this->listRecords[$i]['id_'];
                    $this->records_[$i]['reglament_'] =$reglaments_['reglament'.$this->listRecords[$i]['reglament_'].'_'];
                    $this->records_[$i]['cGamers_']   =$this->listRecords[$i]['cGamers_'];
                    $this->records_[$i]['begin_']     =$this->listRecords[$i]['begin_'];
                    $this->records_[$i]['end_']       =$this->listRecords[$i]['end_'];
                    $this->records_[$i]['class_']     =$this->listRecords[$i]['class_'];
                    if ($this->params_['login_'] != ''){
                      $this->records_[$i]['place1_']  =$this->listRecords[$i]['place1_'];
                      $this->records_[$i]['place2_']  =$this->listRecords[$i]['place2_'];
                    }
                    $this->records_[$i]['system_'] =$this->listRecords[$i]['system_'];
                } #for
            }catch(Exception $e){
                throw new Exception('При чтении информации о турнирах произошла ошибка.');
            }
       }#get_records

       public function out_records(){
            $href_='MainPage.php?link_=end_tournaments';
            if ($this->params_['class_'] != '')
              $href_ .='&find_class_='.$this->params_['class_'];
            if ($this->params_['login_'] != '')
              $href_ .='&find_login_='.urlencode($this->params_['login_']);
            if ($this->params_['place_'] != '')
              $href_ .='&find_place_='.$this->params_['place_'];
            if ($this->params_['reglament_'] != '')
              $href_ .='&find_reglament_='.$this->params_['reglament_'];

            $result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                                        'font-size: 12pt; color: black;'.
                                                        'text-decoration: none; font-weight: normal">'."\n".
                      '   <TABLE style ="border: none; margin-left: auto; margin-right: auto" cellspacing="0" cellpadding="0">'."\n".
                      '      <COL span="1">'."\n";
            $m ='';
            if ($this->cCountPages > 1){
                $a =$this->getFirstVisibleNum($this->page_);
                $b =$this->getLastVisibleNum($this->page_);
                if ($a > 1) $m ='<A href="'.CEndTournaments::get_link($this->params_,$a-1).'">'.htmlspecialchars('<<').'</A>';
                for ($i=$a; $i <=$b; $i++){
                    if ($m != '') $m .='&nbsp;';
                    if ($this->page_ != $i) $m .='<A href="'.CEndTournaments::get_link($this->params_,$i).'">'.$i.'</A>'; else $m.=$i;
                }#for
                if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.CEndTournaments::get_link($this->params_,$b+1).'">'.htmlspecialchars('>>').'</A>';
                $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            }
            $result_ .='<TR><TD>'."\n".
                       '<TABLE class="table_calls_" cellspacing="3">'."\n".
                       '     <COL span="'.(($this->params_['login_'] != '') ? '8' : '7').'">'."\n".
                       '     <TR><TD class="table_head_1">№</TD>'."\n".
                       '         <TD class="table_head_1">класс</TD>'."\n".
                       '         <TD class="table_head_1">регламент</TD>'."\n".
                       '         <TD class="table_head_1">кол-во игроков</TD>'."\n".
                       '         <TD class="table_head_1">турнир начат</TD>'."\n".
                       '         <TD class="table_head_1">турнир закончен</TD>'."\n";
            if ($this->params_['login_'] != '')
              $result_ .=' <TD class="table_head_1">место</TD>'."\n";
            $result_ .='<TD class="table_head_1">система</TD>'."\n";
            $result_ .='     </TR>';

            for($i=0; $i < count($this->records_); $i++){
                if (!is_null($this->records_[$i]['system_']) && ($this->records_[$i]['system_'] ==1)){
                    $link_tournament ='swiss_Tournament';
                    $system_tournament ='швейцарская';
                }else{
                    $link_tournament ='Tournament';
                    $system_tournament ='круговая';
                }
                $result_ .='<TR><TD class="table_body_1"><A href="MainPage.php?link_='.$link_tournament.'&id_='.$this->records_[$i]['id_'].'">'.$this->records_[$i]['id_'].'</A></TD>'."\n".
                           '    <TD class="table_body_1"><A>'.htmlspecialchars($this->records_[$i]['class_']).'</A></TD>'."\n".
                           '    <TD class="table_body_1"><A>'.htmlspecialchars($this->records_[$i]['reglament_']).'</A></TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['cGamers_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.htmlspecialchars(($this->records_[$i]['begin_'] !='') ? $this->records_[$i]['begin_'] : '&nbsp').'</TD>'."\n".
                           '    <TD class="table_body_1">'.htmlspecialchars(($this->records_[$i]['end_'] !='') ? $this->records_[$i]['end_'] : '&nbsp').'</TD>'."\n";
                if ($this->params_['login_'] != ''){
                  $s =$this->records_[$i]['place1_'].(($this->records_[$i]['place2_'] !='') ? '-'.$this->records_[$i]['place2_'] : '');
                  $result_ .=' <TD class="table_body_1">'.htmlspecialchars(($s !='') ? $s : '&nbsp').'</TD>'."\n";
                }
                $result_ .='<TD class="table_body_1">'.htmlspecialchars($system_tournament).'</TD>'."\n";
                $result_ .=' </TR>';
            } #for
            $result_ .='</TABLE>'."\n".
                       '</TD></TR>'."\n";
            if ($m != '')
              $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            $result_ .='</TABLE></SPAN>';
            return $result_;
       }#out_records
    } #CEndTournaments

    class CTournaments_{
       public static function BodyEndTournaments($params_,$page_,$error_){
          global $reglaments_;
          if ($error_ == ''){
            $c =new CEndTournaments($params_);
            $c->get_records($page_);
          }

          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'."\n".
                         'Завершенные турниры'."\n".
                    '</DIV><BR><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:left;'.
                                 'text-decoration: none; font-weight: normal">'."\n".
                         '<SPAN style="color: white">'.
                           'Параметры поиска'.(($error_ != '') ? ' ('.htmlspecialchars($error_).')' : '').'<BR>'.
                         '</SPAN>'."\n";

          if (!CUsers_::Read_dhtml_())
              $result_ .='<DIV>'."\n".
                          ' Если установить флаг DHTML в разделе "настройки", то поле "логин участника" '.
                          ' будет содержать выпадающий список, который появлятся при нажатии клавиш.'.
                         '</DIV>'."\n";
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
          $result_ .=    '<FORM action="MainPage.php?link_=end_tournaments&page_='.$page_.'" method="POST">'."\n".
                         '  <TABLE>'."\n".
                         '    <COL span="2">'."\n".
                         '    <TR>'."\n".
                         '      <TD>логин участника</TD>'."\n".
                         '      <TD>'."\n".
                         '         <INPUT type="text" id="find_login_" name="find_login_" value="'.htmlspecialchars($params_['login_']).'" autocomplete="off">'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>класс</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_class_">'."\n".
                         '           <OPTION '.(($params_['class_'] == '') ? 'selected' : '').' value="all">все</OPTION>'."\n".
                         '           <OPTGROUP label="класс A">'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A')  ? 'selected' : '').' value="A">A</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A8') ? 'selected' : '').' value="A8">A8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A7') ? 'selected' : '').' value="A7">A7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A6') ? 'selected' : '').' value="A6">A6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A5') ? 'selected' : '').' value="A5">A5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A4') ? 'selected' : '').' value="A4">A4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A3') ? 'selected' : '').' value="A3">A3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A1') ? 'selected' : '').' value="A2">A2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A1') ? 'selected' : '').' value="A1">A1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="класс B">'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B')  ? 'selected' : '').' value="B">B</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B8') ? 'selected' : '').' value="B8">B8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B7') ? 'selected' : '').' value="B7">B7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B6') ? 'selected' : '').' value="B6">B6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B5') ? 'selected' : '').' value="B5">B5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B4') ? 'selected' : '').' value="B4">B4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B3') ? 'selected' : '').' value="B3">B3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B2') ? 'selected' : '').' value="B2">B2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B1') ? 'selected' : '').' value="B1">B1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="класс C">'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C')  ? 'selected' : '').' value="C">C</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C8') ? 'selected' : '').' value="C8">C8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C7') ? 'selected' : '').' value="C7">C7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C6') ? 'selected' : '').' value="C6">C6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C5') ? 'selected' : '').' value="C5">C5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C4') ? 'selected' : '').' value="C4">C4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C3') ? 'selected' : '').' value="C3">C3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C2') ? 'selected' : '').' value="C2">C2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C1') ? 'selected' : '').' value="C1">C1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>система</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="system_">'."\n".
                         '           <OPTION '.(($params_['system_'] == '') ? 'selected' : '').' value="">все</OPTION>'."\n".
                         '           <OPTION '.(($params_['system_'] == '0')  ? 'selected' : '').' value="0">круговая</OPTION>'."\n".
                         '           <OPTION '.(($params_['system_'] == '1')  ? 'selected' : '').' value="1">швейцарская</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>регламент</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_reglament_">'."\n".
                         '           <OPTION '.(($params_['reglament_'] == '') ? 'selected' : '').' value="0">все</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament_'] == 1)  ? 'selected' : '').' value="1">'.$reglaments_['reglament1_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament_'] == 2)  ? 'selected' : '').' value="2">'.$reglaments_['reglament2_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament_'] == 3)  ? 'selected' : '').' value="3">'.$reglaments_['reglament3_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament_'] == 4)  ? 'selected' : '').' value="4">'.$reglaments_['reglament4_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament_'] == 5)  ? 'selected' : '').' value="5">'.$reglaments_['reglament5_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament_'] == 6)  ? 'selected' : '').' value="6">'.$reglaments_['reglament6_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament_'] == 7)  ? 'selected' : '').' value="7">'.$reglaments_['reglament7_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament_'] == 8)  ? 'selected' : '').' value="8">'.$reglaments_['reglament8_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament_'] == 9)  ? 'selected' : '').' value="9">'.$reglaments_['reglament9_'].'</OPTION>'."\n".
                         '           <OPTION '.(($params_['reglament_'] == 10) ? 'selected' : '').' value="10">'.$reglaments_['reglament10_'].'</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>место</TD>'."\n".
                         '      <TD>'."\n".
                         '         <INPUT type="text" id="find_place_" name="find_place_" value="'.htmlspecialchars($params_['place_']).'">'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR><TD colspan="2">'."\n".
                         '          <INPUT type="submit" value="Поиск">'."\n".
                         '        </TD>'."\n".
                         '    </TR>'."\n".
                         '  </TABLE>'."\n".
                         '</FORM>';
          if (($error_ =='') && (count($c->records_) > 0))
               $result_ .=$c->out_records();
            else
               $result_ .='<DIV style="text-align: center">турниры не найдены</DIV>';
          $result_ .='</DIV>';
          return $result_;
       }#BodyEndTournaments

       public static function BodyNonEndTournaments($params_,$page_){
          $c =new CActiveTournaments($params_);
          $c->get_records($page_);

          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'."\n".
                         'Активные турниры'."\n".
                    '</DIV><BR><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:left;'.
                                 'text-decoration: none; font-weight: normal">'."\n"."\n".
                         '<SPAN style="color: white">'.
                           'Параметры поиска<BR>'.
                         '</SPAN>'."\n";

          if (!CUsers_::Read_dhtml_())
              $result_ .='<DIV>'."\n".
                          ' Если установить флаг DHTML в разделе "настройки", то поле "логин участника" '.
                          ' будет содержать выпадающий список, который появлятся при нажатии клавиш.'.
                         '</DIV>'."\n";
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

          $result_ .=    '<FORM action="MainPage.php?link_=active_tournaments&page_='.$page_.'" method="POST">'."\n".
                         '  <TABLE>'."\n".
                         '    <COL span="2">'."\n".
                         '    <TR>'."\n".
                         '      <TD>класс</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_class_">'."\n".
                         '           <OPTION '.(($params_['class_'] == '') ? 'selected' : '').' value="all">все</OPTION>'."\n".
                         '           <OPTGROUP label="класс A">'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A')  ? 'selected' : '').' value="A">A</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A8') ? 'selected' : '').' value="A8">A8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A7') ? 'selected' : '').' value="A7">A7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A6') ? 'selected' : '').' value="A6">A6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A5') ? 'selected' : '').' value="A5">A5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A4') ? 'selected' : '').' value="A4">A4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A3') ? 'selected' : '').' value="A3">A3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A2') ? 'selected' : '').' value="A2">A2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A1') ? 'selected' : '').' value="A1">A1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="класс B">'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B')  ? 'selected' : '').' value="B">B</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B8') ? 'selected' : '').' value="B8">B8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B7') ? 'selected' : '').' value="B7">B7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B6') ? 'selected' : '').' value="B6">B6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B5') ? 'selected' : '').' value="B5">B5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B4') ? 'selected' : '').' value="B4">B4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B3') ? 'selected' : '').' value="B3">B3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B2') ? 'selected' : '').' value="B2">B2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B1') ? 'selected' : '').' value="B1">B1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="класс C">'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C')  ? 'selected' : '').' value="C">C</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C8') ? 'selected' : '').' value="C8">C8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C7') ? 'selected' : '').' value="C7">C7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C6') ? 'selected' : '').' value="C6">C6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C5') ? 'selected' : '').' value="C5">C5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C4') ? 'selected' : '').' value="C4">C4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C3') ? 'selected' : '').' value="C3">C3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C2') ? 'selected' : '').' value="C2">C2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C1') ? 'selected' : '').' value="C1">C1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>система</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="system_">'."\n".
                         '           <OPTION '.(($params_['system_'] == '') ? 'selected' : '').' value="">все</OPTION>'."\n".
                         '           <OPTION '.(($params_['system_'] == '0') ? 'selected' : '').' value="0">круговая</OPTION>'."\n".
                         '           <OPTION '.(($params_['system_'] == '1') ? 'selected' : '').' value="1">швейцарская</OPTION>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>логин участника</TD>'."\n".
                         '      <TD>'."\n".
                         '         <INPUT type="text" id="find_login_" name="find_login_" value="'.htmlspecialchars($params_['login_']).'" autocomplete="off">'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR><TD colspan="2">'."\n".
                         '          <INPUT type="submit" value="Поиск">'."\n".
                         '        </TD>'."\n".
                         '    </TR>'."\n".
                         '  </TABLE>'."\n".
                         '</FORM>';
          if (count($c->records_) > 0)
               $result_ .=$c->out_records();
            else
               $result_ .='<DIV style="text-align: center">турниры не найдены</DIV>';
          $result_ .='</DIV>';
          return $result_;
       }#BodyNonEndTournaments

       public static function get_access_tournaments($class_){
            global $reglaments_;
            $list_ =array();
            $s ='select A.id_,A.cGamers_,A.reglament_,A.class_,count(B.num_) as regGamers_,A.system_,A.begin_'.
                ' from TTournaments_ A left join TMembersTournament_ B on (A.id_ =B.id_tournament_)'.
                ' where (((length(trim(A.class_))=1) and (trim(A.class_)=\''.$class_{0}.'\')) or '.
                '        ((length(trim(A.class_))=2) and (A.class_=\''.$class_.'\'))) and '.
                '       not exists (select * from TMembersTournament_ B1'.
                '                     where (B1.id_tournament_ = A.id_) and '.
                '                           (B1.id_gamer_ = '.$_SESSION[SESSION_ID_].'))'.
                ' group by A.id_,A.cGamers_,A.reglament_,A.class_,A.system_,A.begin_'.
                ' having ((A.system_ is null) and (count(B.num_) < A.cGamers_)) or '.
                '        ((not A.system_ is null) and (NOW() < A.begin_))'.
                ' order by A.begin_';
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении информации о турнирах произошла ошибка.');
            $a=0;
            while ($row_ =mysql_fetch_array($cursor_)){
               $list_[$a]['id'] = $row_['id_'];
               $list_[$a]['class'] =$row_['class_'];
               $list_[$a]['reglament'] =$reglaments_['reglament'.$row_['reglament_'].'_'];
               $list_[$a]['count_gamers'] =$row_['regGamers_'].'/'.(is_null($row_['cGamers_']) ? '-' : $row_['cGamers_']);
               $list_[$a]['begin'] =$row_['begin_'];
               $list_[$a]['code_system'] =$row_['system_'];
               if (is_null($row_['system_']))
                 $list_[$a]['system'] ='круговая';
                else if ($row_['system_'] ==1)
                 $list_[$a]['system'] ='швейцарская';
                else
                 $list_[$a]['system'] ='';
               $a++;
            } #while
            mysql_free_result($cursor_);
            return $list_;
       }#get_access_tournaments

       public static function BodyAccessTournaments($class_A,$class_C,$class_B){
          $list_A =CTournaments_::get_access_tournaments($class_A);
          $list_C =CTournaments_::get_access_tournaments($class_C);
          $list_B =CTournaments_::get_access_tournaments($class_B);
          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'.
                         'Турниры, в которых Вы можете принять участие'.
                    '</DIV><BR><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:center;'.
                                 'text-decoration: none; font-weight: normal">'."\n".
                         '<SPAN style="color: white">'.
                           'Класс A<BR>'.
                         '</SPAN>';
          if (count($list_A) > 0){
            $result_ .='<TABLE style="border: none; margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                       '    <COL span="6">'."\n".
                       '    <TR><TD class="table_head_1">№</TD>'."\n".
                       '        <TD class="table_head_1">класс</TD>'."\n".
                       '        <TD class="table_head_1">регламент</TD>'."\n".
                       '        <TD class="table_head_1">кол-во игроков</TD>'."\n".
                       '        <TD class="table_head_1">начало турнира</TD>'."\n".
                       '        <TD class="table_head_1">система</TD>'."\n".
                       '    </TR>';
            for($i=0; $i < count($list_A); $i++){
                if (!is_null($list_A[$i]['code_system']) && ($list_A[$i]['code_system']==1))
                  $link_tournament ='swiss_Tournament';
                 else
                  $link_tournament ='Tournament';
                $result_ .='<TR><TD class="table_body_1" style="text-align:left"><A href="MainPage.php?link_='.$link_tournament.'&id_='.$list_A[$i]['id'].'">'.$list_A[$i]['id'].'</A></TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_A[$i]['class']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_A[$i]['reglament']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_A[$i]['count_gamers']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_A[$i]['begin']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_A[$i]['system']).'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='</TABLE>';
          }else $result_ .='турниров нет<BR>';

          $result_ .='<BR>'.
                     '<SPAN style="color: white">'.
                       'Класс B<BR>'.
                     '</SPAN>';
          if (count($list_B) > 0){
            $result_ .='<TABLE style="border: none; margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                       '    <COL span="6">'."\n".
                       '    <TR><TD class="table_head_1">№</TD>'."\n".
                       '        <TD class="table_head_1">класс</TD>'."\n".
                       '        <TD class="table_head_1">регламент</TD>'."\n".
                       '        <TD class="table_head_1">кол-во игроков</TD>'."\n".
                       '        <TD class="table_head_1">начало турнира</TD>'."\n".
                       '        <TD class="table_head_1">система</TD>'."\n".
                       '    </TR>';
            for($i=0; $i < count($list_B); $i++){
                if (!is_null($list_B[$i]['code_system']) && ($list_B[$i]['code_system']==1))
                  $link_tournament ='swiss_Tournament';
                 else
                  $link_tournament ='Tournament';
                $result_ .='<TR><TD class="table_body_1" style="text-align:left"><A href="MainPage.php?link_='.$link_tournament.'&id_='.$list_B[$i]['id'].'">'.$list_B[$i]['id'].'</A></TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_B[$i]['class']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_B[$i]['reglament']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_B[$i]['count_gamers']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_B[$i]['begin']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_B[$i]['system']).'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='</TABLE>';
          }else $result_ .='турниров нет<BR>';

          $result_ .='<BR>'.
                     '<SPAN style="color: white">'.
                       'Класс C<BR>'.
                     '</SPAN>';
          if (count($list_C) > 0){
            $result_ .='<TABLE style="border: none; margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                       '    <COL span="6">'."\n".
                       '    <TR><TD class="table_head_1">№</TD>'."\n".
                       '        <TD class="table_head_1">класс</TD>'."\n".
                       '        <TD class="table_head_1">регламент</TD>'."\n".
                       '        <TD class="table_head_1">кол-во игроков</TD>'."\n".
                       '        <TD class="table_head_1">начало турнира</TD>'."\n".
                       '        <TD class="table_head_1">система</TD>'."\n".
                       '    </TR>';
            $result_ .='  <SPAN style="text-align:left">';
            for($i=0; $i < count($list_C); $i++){
                if (!is_null($list_C[$i]['code_system']) && ($list_C[$i]['code_system']==1))
                  $link_tournament ='swiss_Tournament';
                 else
                  $link_tournament ='Tournament';
                $result_ .='<TR><TD class="table_body_1" style="text-align:left"><A href="MainPage.php?link_='.$link_tournament.'&id_='.$list_C[$i]['id'].'">'.$list_C[$i]['id'].'</A></TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_C[$i]['class']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_C[$i]['reglament']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_C[$i]['count_gamers']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_C[$i]['begin']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_C[$i]['system']).'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='  </SPAN>';
            $result_ .='</TABLE>';
          }else $result_ .='турниров нет';

          $result_ .='</DIV>';
          return $result_;
       }#BodyAccessTournaments

        public static function get_active_tournaments_A(){
            global $reglaments_;
            $list_active_A =array();
            $s ='select C.id_,C.reglament_,C.cGamers_,C.begin_,C.class_,count(D.num_) as count_,C.system_'.
                '  from TGamers_ A, TMembersTournament_ B,'.
                '       TTournaments_ C left join TMembersTournament_ D on C.id_ = D.id_tournament_'.
                '  where (A.id_ ='.$_SESSION[SESSION_ID_].') and (A.id_ = B.id_gamer_) and'.
                '        (B.id_tournament_ = C.id_) and (C.end_ is null) and (C.class_ like \'A%\')'.
                '  group by C.id_,C.reglament_,C.cGamers_,C.begin_,C.class_,C.system_'.
                '  order by C.begin_';
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении информации о турнирах произошла ошибка.');
            $a=0;
            while ($row_ =mysql_fetch_array($cursor_)){
                $list_active_A[$a]['id'] = $row_['id_'];
                $list_active_A[$a]['class'] =$row_['class_'];
                $list_active_A[$a]['reglament'] =$reglaments_['reglament'.$row_['reglament_'].'_'];
                $list_active_A[$a]['count_gamers'] =$row_['count_'].'/'.(is_null($row_['cGamers_']) ? '-' : $row_['cGamers_']);
                $list_active_A[$a]['date_begin']   =$row_['begin_'];
                $list_active_A[$a]['code_system']  =$row_['system_'];
                if (is_null($row_['system_']))
                  $list_active_A[$a]['system'] ='круговая';
                 else if ($row_['system_'] ==1)
                  $list_active_A[$a]['system'] ='швейцарская';
                 else
                  $list_active_A[$a]['system'] ='';
               $a++;
            } #while
            mysql_free_result($cursor_);
            return $list_active_A;
        }#get_active_tournaments_A

        public static function get_active_tournaments_B(){
            global $reglaments_;
            $list_active_B =array();
            $s ='select C.id_,C.reglament_,C.cGamers_,C.begin_,C.class_,count(D.num_) as count_,C.system_'.
                '  from TGamers_ A, TMembersTournament_ B,'.
                '       TTournaments_ C left join TMembersTournament_ D on C.id_ = D.id_tournament_'.
                '  where (A.id_ ='.$_SESSION[SESSION_ID_].') and (A.id_ = B.id_gamer_) and'.
                '        (B.id_tournament_ = C.id_) and (C.end_ is null) and (C.class_ like \'B%\')'.
                '  group by C.id_,C.reglament_,C.cGamers_,C.begin_,C.class_,C.system_'.
                '  order by C.begin_';
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении информации о турнирах произошла ошибка.');
            $a=0;
            while ($row_ =mysql_fetch_array($cursor_)){
                $list_active_B[$a]['id'] = $row_['id_'];
                $list_active_B[$a]['class'] =$row_['class_'];
                $list_active_B[$a]['reglament'] =$reglaments_['reglament'.$row_['reglament_'].'_'];
                $list_active_B[$a]['count_gamers'] =$row_['count_'].'/'.(is_null($row_['cGamers_']) ? '-' : $row_['cGamers_']);
                $list_active_B[$a]['date_begin']   =$row_['begin_'];
                $list_active_B[$a]['code_system']  =$row_['system_'];
                if (is_null($row_['system_']))
                  $list_active_B[$a]['system'] ='круговая';
                 else if ($row_['system_'] ==1)
                  $list_active_B[$a]['system'] ='швейцарская';
                 else
                  $list_active_B[$a]['system'] ='';
               $a++;
            } #while
            mysql_free_result($cursor_);
            return $list_active_B;
        }#get_active_tournaments_B

        public static function get_active_tournaments_C(){
            global $reglaments_;
            $list_active_C =array();
            $s ='select C.id_,C.reglament_,C.cGamers_,C.begin_,C.class_,count(D.num_) as count_,C.system_'.
                '  from TGamers_ A, TMembersTournament_ B,'.
                '       TTournaments_ C left join TMembersTournament_ D on C.id_ = D.id_tournament_'.
                '  where (A.id_ ='.$_SESSION[SESSION_ID_].') and (A.id_ = B.id_gamer_) and'.
                '        (B.id_tournament_ = C.id_) and (C.end_ is null) and (C.class_ like \'C%\')'.
                '  group by C.id_,C.reglament_,C.cGamers_,C.begin_,C.class_,C.system_'.
                '  order by C.begin_';
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении информации о турнирах произошла ошибка.');
            $a=0;
            while ($row_ =mysql_fetch_array($cursor_)){
                $list_active_C[$a]['id'] = $row_['id_'];
                $list_active_C[$a]['class'] =$row_['class_'];
                $list_active_C[$a]['reglament'] =$reglaments_['reglament'.$row_['reglament_'].'_'];
                $list_active_C[$a]['count_gamers'] =$row_['count_'].'/'.(is_null($row_['cGamers_']) ? '-' : $row_['cGamers_']);
                $list_active_C[$a]['date_begin']   =$row_['begin_'];
                $list_active_C[$a]['code_system']  =$row_['system_'];
                if (is_null($row_['system_']))
                  $list_active_C[$a]['system'] ='круговая';
                 else if ($row_['system_'] ==1)
                  $list_active_C[$a]['system'] ='швейцарская';
                 else
                  $list_active_C[$a]['system'] ='';
               $a++;
            } #while
            mysql_free_result($cursor_);
            return $list_active_C;
        }#get_active_tournaments_C

      public static function BodyActiveTournaments(){
          $result_='';
          $list_A =CTournaments_::get_active_tournaments_A();
          $list_B =CTournaments_::get_active_tournaments_B();
          $list_C =CTournaments_::get_active_tournaments_C();

          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'.
                         'Активные турниры, в которых Вы принимаете участие'.
                    '</DIV><BR><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:center;'.
                                 'text-decoration: none; font-weight: normal">'."\n".
                         '<SPAN style="color: white">'.
                           'Класс A<BR>'.
                         '</SPAN>';
          if (count($list_A) > 0){
            $result_ .='<TABLE style="border: none; margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                       '    <COL span="6">'."\n".
                       '    <TR><TD class="table_head_1">№</TD>'."\n".
                       '        <TD class="table_head_1">класс</TD>'."\n".
                       '        <TD class="table_head_1">регламент</TD>'."\n".
                       '        <TD class="table_head_1">кол-во игроков</TD>'."\n".
                       '        <TD class="table_head_1">турнир начат</TD>'."\n".
                       '        <TD class="table_head_1">система</TD>'."\n".
                       '    </TR>';
            for($i=0; $i < count($list_A); $i++){
                if (!is_null($list_A[$i]['code_system']) && ($list_A[$i]['code_system']==1))
                  $link_tournament ='swiss_Tournament';
                 else
                  $link_tournament ='Tournament';
                $result_ .='<TR><TD class="table_body_1" style="text-align:left"><A href="MainPage.php?link_='.$link_tournament.'&id_='.$list_A[$i]['id'].'">'.$list_A[$i]['id'].'</A></TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_A[$i]['class']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_A[$i]['reglament']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_A[$i]['count_gamers']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_A[$i]['date_begin']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_A[$i]['system']).'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='</TABLE>';
          }else $result_ .='турниров нет<BR>';

          $result_ .='<BR>'.
                     '<SPAN style="color: white">'.
                       'Класс B<BR>'.
                     '</SPAN>';
          if (count($list_B) > 0){
            $result_ .='<TABLE style="border: none; margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                       '    <COL span="6">'."\n".
                       '    <TR><TD class="table_head_1">№</TD>'."\n".
                       '        <TD class="table_head_1">класс</TD>'."\n".
                       '        <TD class="table_head_1">регламент</TD>'."\n".
                       '        <TD class="table_head_1">кол-во игроков</TD>'."\n".
                       '        <TD class="table_head_1">турнир начат</TD>'."\n".
                       '        <TD class="table_head_1">система</TD>'."\n".
                       '    </TR>';
            $result_ .='  <SPAN style="text-align:left">';
            for($i=0; $i < count($list_B); $i++){
                if (!is_null($list_B[$i]['code_system']) && ($list_B[$i]['code_system']==1))
                  $link_tournament ='swiss_Tournament';
                 else
                  $link_tournament ='Tournament';
                $result_ .='<TR><TD class="table_body_1" style="text-align:left"><A href="MainPage.php?link_='.$link_tournament.'&id_='.$list_B[$i]['id'].'">'.$list_B[$i]['id'].'</A></TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_B[$i]['class']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_B[$i]['reglament']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_B[$i]['count_gamers']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_B[$i]['date_begin']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_B[$i]['system']).'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='  </SPAN>';
            $result_ .='</TABLE>';
          }else $result_ .='турниров нет';

          $result_ .='<BR>'.
                     '<SPAN style="color: white">'.
                       'Класс C<BR>'.
                     '</SPAN>';
          if (count($list_C) > 0){
            $result_ .='<TABLE style="border: none; margin-left: auto; margin-right: auto" cellspacing="3">'."\n".
                       '    <COL span="6">'."\n".
                       '    <TR><TD class="table_head_1">№</TD>'."\n".
                       '        <TD class="table_head_1">класс</TD>'."\n".
                       '        <TD class="table_head_1">регламент</TD>'."\n".
                       '        <TD class="table_head_1">кол-во игроков</TD>'."\n".
                       '        <TD class="table_head_1">турнир начат</TD>'."\n".
                       '        <TD class="table_head_1">система</TD>'."\n".
                       '    </TR>';
            $result_ .='  <SPAN style="text-align:left">';
            for($i=0; $i < count($list_C); $i++){
                if (!is_null($list_C[$i]['code_system']) && ($list_C[$i]['code_system']==1))
                  $link_tournament ='swiss_Tournament';
                 else
                  $link_tournament ='Tournament';
                $result_ .='<TR><TD class="table_body_1" style="text-align:left"><A href="MainPage.php?link_='.$link_tournament.'&id_='.$list_C[$i]['id'].'">'.$list_C[$i]['id'].'</A></TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_C[$i]['class']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_C[$i]['reglament']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_C[$i]['count_gamers']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_C[$i]['date_begin']).'</TD>'."\n".
                           '    <TD class="table_body_1" style="text-align:left">'.htmlspecialchars($list_C[$i]['system']).'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='  </SPAN>';
            $result_ .='</TABLE>';
          }else $result_ .='турниров нет';

          $result_ .='</DIV>';
          return $result_;
      }#BodyActiveTournaments

#Если незаполненный турнир с заданными параметрами не существует он создается (система круговая)
		protected static function createTournament($reglament_,$cGamers_,$class_){
#Проверяю существует ли турнир с данными параметрами
            $s ='select count(*) as count_'.
                ' from TTournaments_ A'.
                ' where (A.reglament_='.$reglament_.') and (A.cGamers_='.$cGamers_.') and'.
                '       (A.class_ =\''.$class_.'\') and (A.system_ is null) and'.
                '       (A.cGamers_ > (select count(*) from TMembersTournament_ where id_tournament_ = A.id_))';
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении информации о турнирах произошла ошибка.');
            $row_ =mysql_fetch_array($cursor_);
            if ($row_ && ($row_['count_'] == 0)){
#Если турнира нет создаю его
                $s ='insert into TTournaments_(cGamers_,reglament_,begin_,firstColorWhite_,class_)'.
                    ' values('.$cGamers_.','.$reglament_.',NOW(),'.(rand(0,1) ? '\'Y\'' : '\'N\'').','.
                              (($class_ == '') ? 'null' : '\''.$class_.'\'').')';
                if (!mysql_query($s,const_::$connect_)) throw new Exception('При чтении информации о турнирах произошла ошибка.');
            }
            mysql_free_result($cursor_);
        }#createTournament

#Если турнира с заланными параметрами не существует он создается (система швейцарская)
        protected static function createSwissTournament($reglament_,$cRounds_,$class_){
#Проверяю существует ли турнир с данными параметрами
            $s ='select count(*) as count_'.
                ' from TTournaments_ A'.
                ' where (A.reglament_='.$reglament_.') and (A.cRounds_='.$cRounds_.') and'.
                '       (A.class_ =\''.$class_.'\') and (A.system_ =1) and (NOW() < A.begin_)';
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении информации о турнирах произошла ошибка.');
            $row_ =mysql_fetch_array($cursor_);
            if ($row_ && ($row_['count_'] == 0)){
#Если турнира нет создаю его
                $s ='insert into TTournaments_(reglament_,begin_,firstColorWhite_,class_,cRounds_,system_)'.
                    ' values('.$reglament_.',DATE_ADD(NOW(), INTERVAL 14 DAY),'.(rand(0,1) ? '\'Y\'' : '\'N\'').','.
                             '\''.$class_.'\','.$cRounds_.',1)';
                if (!mysql_query($s,const_::$connect_))
#                  throw new Exception(mysql_error());
                  throw new Exception('При чтении информации о турнирах произошла ошибка.');
            }
            mysql_free_result($cursor_);
        }#createSwissTournament

#Создаёт новые турниры, если незаполненных турниров с указанными параметрами нет
        public static function newTournaments(){#Круговая система
#--------------------------------------------------------
#Турниры класса A8
            CTournaments_::createTournament(4,7,'A8');
#Турниры класса A7
            CTournaments_::createTournament(1,5,'A7');
            CTournaments_::createTournament(2,5,'A7');
            CTournaments_::createTournament(3,5,'A7');
            CTournaments_::createTournament(4,5,'A7');
            CTournaments_::createTournament(5,5,'A7');
            CTournaments_::createTournament(6,5,'A7');
            CTournaments_::createTournament(1,7,'A7');
            CTournaments_::createTournament(2,7,'A7');
            CTournaments_::createTournament(3,7,'A7');
            CTournaments_::createTournament(4,7,'A7');
            CTournaments_::createTournament(5,7,'A7');
            CTournaments_::createTournament(6,7,'A7');
#Турниры класса A6
            CTournaments_::createTournament(1,5,'A6');
            CTournaments_::createTournament(2,5,'A6');
            CTournaments_::createTournament(3,5,'A6');
            CTournaments_::createTournament(4,5,'A6');
            CTournaments_::createTournament(5,5,'A6');
            CTournaments_::createTournament(6,5,'A6');
            CTournaments_::createTournament(1,7,'A6');
            CTournaments_::createTournament(2,7,'A6');
            CTournaments_::createTournament(3,7,'A6');
            CTournaments_::createTournament(4,7,'A6');
            CTournaments_::createTournament(5,7,'A6');
            CTournaments_::createTournament(6,7,'A6');
#Турниры класса A5
            CTournaments_::createTournament(1,5,'A5');
            CTournaments_::createTournament(2,5,'A5');
            CTournaments_::createTournament(3,5,'A5');
            CTournaments_::createTournament(4,5,'A5');
            CTournaments_::createTournament(5,5,'A5');
            CTournaments_::createTournament(6,5,'A5');
            CTournaments_::createTournament(1,7,'A5');
            CTournaments_::createTournament(2,7,'A5');
            CTournaments_::createTournament(3,7,'A5');
            CTournaments_::createTournament(4,7,'A5');
            CTournaments_::createTournament(5,7,'A5');
            CTournaments_::createTournament(6,7,'A5');
#Турниры класса A4
            CTournaments_::createTournament(1,5,'A4');
            CTournaments_::createTournament(2,5,'A4');
            CTournaments_::createTournament(3,5,'A4');
            CTournaments_::createTournament(4,5,'A4');
            CTournaments_::createTournament(5,5,'A4');
            CTournaments_::createTournament(6,5,'A4');
            CTournaments_::createTournament(1,7,'A4');
            CTournaments_::createTournament(2,7,'A4');
            CTournaments_::createTournament(3,7,'A4');
            CTournaments_::createTournament(4,7,'A4');
            CTournaments_::createTournament(5,7,'A4');
            CTournaments_::createTournament(6,7,'A4');
#Турниры класса A3
            CTournaments_::createTournament(1,5,'A3');
            CTournaments_::createTournament(2,5,'A3');
            CTournaments_::createTournament(3,5,'A3');
            CTournaments_::createTournament(4,5,'A3');
            CTournaments_::createTournament(5,5,'A3');
            CTournaments_::createTournament(6,5,'A3');
            CTournaments_::createTournament(1,7,'A3');
            CTournaments_::createTournament(2,7,'A3');
            CTournaments_::createTournament(3,7,'A3');
            CTournaments_::createTournament(4,7,'A3');
            CTournaments_::createTournament(5,7,'A3');
            CTournaments_::createTournament(6,7,'A3');
#Турниры класса A2
            CTournaments_::createTournament(1,5,'A2');
            CTournaments_::createTournament(2,5,'A2');
            CTournaments_::createTournament(3,5,'A2');
            CTournaments_::createTournament(4,5,'A2');
            CTournaments_::createTournament(5,5,'A2');
            CTournaments_::createTournament(6,5,'A2');
            CTournaments_::createTournament(1,7,'A2');
            CTournaments_::createTournament(2,7,'A2');
            CTournaments_::createTournament(3,7,'A2');
            CTournaments_::createTournament(4,7,'A2');
            CTournaments_::createTournament(5,7,'A2');
            CTournaments_::createTournament(6,7,'A2');
#Турниры класса A1
            CTournaments_::createTournament(1,5,'A1');
            CTournaments_::createTournament(2,5,'A1');
            CTournaments_::createTournament(3,5,'A1');
            CTournaments_::createTournament(4,5,'A1');
            CTournaments_::createTournament(5,5,'A1');
            CTournaments_::createTournament(6,5,'A1');
            CTournaments_::createTournament(1,7,'A1');
            CTournaments_::createTournament(2,7,'A1');
            CTournaments_::createTournament(3,7,'A1');
            CTournaments_::createTournament(4,7,'A1');
            CTournaments_::createTournament(5,7,'A1');
            CTournaments_::createTournament(6,7,'A1');

#-----------------------------------------------------------------
#Турниры класса C8
            CTournaments_::createTournament(4,7,'C8');
#Турниры класса C7
            CTournaments_::createTournament(1,5,'C7');
            CTournaments_::createTournament(2,5,'C7');
            CTournaments_::createTournament(3,5,'C7');
            CTournaments_::createTournament(4,5,'C7');
            CTournaments_::createTournament(5,5,'C7');
            CTournaments_::createTournament(6,5,'C7');
            CTournaments_::createTournament(1,7,'C7');
            CTournaments_::createTournament(2,7,'C7');
            CTournaments_::createTournament(3,7,'C7');
            CTournaments_::createTournament(4,7,'C7');
            CTournaments_::createTournament(5,7,'C7');
            CTournaments_::createTournament(6,7,'C7');
#Турниры класса C6
            CTournaments_::createTournament(1,5,'C6');
            CTournaments_::createTournament(2,5,'C6');
            CTournaments_::createTournament(3,5,'C6');
            CTournaments_::createTournament(4,5,'C6');
            CTournaments_::createTournament(5,5,'C6');
            CTournaments_::createTournament(6,5,'C6');
            CTournaments_::createTournament(1,7,'C6');
            CTournaments_::createTournament(2,7,'C6');
            CTournaments_::createTournament(3,7,'C6');
            CTournaments_::createTournament(4,7,'C6');
            CTournaments_::createTournament(5,7,'C6');
            CTournaments_::createTournament(6,7,'C6');
#Турниры класса C5
            CTournaments_::createTournament(1,5,'C5');
            CTournaments_::createTournament(2,5,'C5');
            CTournaments_::createTournament(3,5,'C5');
            CTournaments_::createTournament(4,5,'C5');
            CTournaments_::createTournament(5,5,'C5');
            CTournaments_::createTournament(6,5,'C5');
            CTournaments_::createTournament(1,7,'C5');
            CTournaments_::createTournament(2,7,'C5');
            CTournaments_::createTournament(3,7,'C5');
            CTournaments_::createTournament(4,7,'C5');
            CTournaments_::createTournament(5,7,'C5');
            CTournaments_::createTournament(6,7,'C5');
#Турниры класса C4
            CTournaments_::createTournament(1,5,'C4');
            CTournaments_::createTournament(2,5,'C4');
            CTournaments_::createTournament(3,5,'C4');
            CTournaments_::createTournament(4,5,'C4');
            CTournaments_::createTournament(5,5,'C4');
            CTournaments_::createTournament(6,5,'C4');
            CTournaments_::createTournament(1,7,'C4');
            CTournaments_::createTournament(2,7,'C4');
            CTournaments_::createTournament(3,7,'C4');
            CTournaments_::createTournament(4,7,'C4');
            CTournaments_::createTournament(5,7,'C4');
            CTournaments_::createTournament(6,7,'C4');
#Турниры класса C3
            CTournaments_::createTournament(1,5,'C3');
            CTournaments_::createTournament(2,5,'C3');
            CTournaments_::createTournament(3,5,'C3');
            CTournaments_::createTournament(4,5,'C3');
            CTournaments_::createTournament(5,5,'C3');
            CTournaments_::createTournament(6,5,'C3');
            CTournaments_::createTournament(1,7,'C3');
            CTournaments_::createTournament(2,7,'C3');
            CTournaments_::createTournament(3,7,'C3');
            CTournaments_::createTournament(4,7,'C3');
            CTournaments_::createTournament(5,7,'C3');
            CTournaments_::createTournament(6,7,'C3');
#Турниры класса C2
            CTournaments_::createTournament(1,5,'C2');
            CTournaments_::createTournament(2,5,'C2');
            CTournaments_::createTournament(3,5,'C2');
            CTournaments_::createTournament(4,5,'C2');
            CTournaments_::createTournament(5,5,'C2');
            CTournaments_::createTournament(6,5,'C2');
            CTournaments_::createTournament(1,7,'C2');
            CTournaments_::createTournament(2,7,'C2');
            CTournaments_::createTournament(3,7,'C2');
            CTournaments_::createTournament(4,7,'C2');
            CTournaments_::createTournament(5,7,'C2');
            CTournaments_::createTournament(6,7,'C2');
#Турниры класса C1
            CTournaments_::createTournament(1,5,'C1');
            CTournaments_::createTournament(2,5,'C1');
            CTournaments_::createTournament(3,5,'C1');
            CTournaments_::createTournament(4,5,'C1');
            CTournaments_::createTournament(5,5,'C1');
            CTournaments_::createTournament(6,5,'C1');
            CTournaments_::createTournament(1,7,'C1');
            CTournaments_::createTournament(2,7,'C1');
            CTournaments_::createTournament(3,7,'C1');
            CTournaments_::createTournament(4,7,'C1');
            CTournaments_::createTournament(5,7,'C1');
            CTournaments_::createTournament(6,7,'C1');
#--------------------------------------------------------
#Швейцарская система
#--------------------------------------------------------
#Турниры класса A
            CTournaments_::createSwissTournament(1,7,'A');
#Турниры класса C
            CTournaments_::createSwissTournament(1,7,'C');
#Турниры класса B
#            CTournaments_::createSwissTournament(9,7,'B');
        }#newTournaments

# Провереяет завершение турнира, если турнир завершён закрывает его (проставляет дату закрытие турнира)
        public static function CloseTournament($id_game){
            $cursor_ =false;
            try{
                $s ='select A.id_ from TTournaments_ A, TGamesTournament_ B'.
                    '  where (A.id_=B.id_tournament_) and (B.id_game ='.$id_game.') and'.
                    '        (A.system_ is null) and'.
                    '        (A.cGamers_=(select max(num_) from TMembersTournament_ where id_tournament_=A.id_)) and'.
                    '        not exists (select * from TGamesTournament_ B1, TGames_ B2'.
                    '                     where (B1.id_tournament_=A.id_) and (B1.id_game=B2.id_) and (B2.result_ is null))';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                if($row_){
                    $id_ =$row_['id_'];
                    $s ='update TTournaments_ set end_ =now() where id_='.$id_;
                    if(!mysql_query($s,const_::$connect_)) throw new Exception();
                    CTournaments_::SetPlaces($id_);
                }
                mysql_free_result($cursor_); $cursor_ =false;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При проверки завершения турнира произошла ошибка.');
            }
        }#CloseTournament

# Проставляет в таблицу TMembersTournament_ места, занятые в турнире
        public static function SetPlaces($id_tournament){
            try{
                CInfo_tournament_::GetInfo($id_tournament);
                $qGamers=count(CInfo_tournament_::$table_); #определяю максимальное количество игроков в турнире (количество строк)
                for($i=0; $i < $qGamers; $i++){
                    $s ='update TMembersTournament_ set'.
                           ' place1_ ='.CInfo_tournament_::$table_[$i][$qGamers+2][1].','.
                           ' place2_ ='.((CInfo_tournament_::$table_[$i][$qGamers+2][2] !=0) ? CInfo_tournament_::$table_[$i][$qGamers+2][1] + CInfo_tournament_::$table_[$i][$qGamers+2][2] : 'null').
                           ' where (id_tournament_ ='.$id_tournament.') and'.
                           '       (id_gamer_ =(select id_ from TGamers_ where login_=\''.mysql_escape_string(CInfo_tournament_::$table_[$i][0]).'\'))';
                    $s =convert_cyr_string($s,'w','d');
                    if (!mysql_query($s,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0))  throw new Exception();
#Изменение класса игрока, если он занял первое место
                    if ((CInfo_tournament_::$table_[$i][$qGamers+2][1] == 1) && (CInfo_tournament_::$table_[$i][$qGamers+2][2] ==0)){
                        $class_tour = CTournaments_::GetClassTournament($id_tournament);
                        if ($class_tour !=''){
                             $id_gamer_ =CUsers_::Read_id_(CInfo_tournament_::$table_[$i][0]);
                             if ($id_gamer_==0) throw new Exception();
                             CTournaments_::FirstPlaceTournament($class_tour,$id_gamer_);
                        }

#Изменение класса игрока, если он занял последнее место
                    }elseif ((CInfo_tournament_::$table_[$i][$qGamers+2][1] == $qGamers) && (CInfo_tournament_::$table_[$i][$qGamers+2][2] ==0)){
                        $class_tour = CTournaments_::GetClassTournament($id_tournament);
                        if ($class_tour !=''){
                            $id_gamer_ =CUsers_::Read_id_(CInfo_tournament_::$table_[$i][0]);
                            if ($id_gamer_==0) throw new Exception();
                            CTournaments_::LastPlaceTournament($class_tour,$id_gamer_);
                        }
                    }
                }#for
            }catch(Exception $e){
                throw new Exception('При сохранении информации об месте в турнире произошла ошибка.');
            }
        }#SetPlaces

# Возвращает класс турнира
        public static function GetClassTournament($id_tournament){
            $cursor_ =false;
            try{
                $s ='select class_ from TTournaments_ where id_='.$id_tournament;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $result_ = trim($row_['class_']);
                mysql_free_result($cursor_); $cursor_ =false;

                return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При получении информации об классе турнира произошла ошибка.');
            }
        } #GetClassTournament

# Изменяет класс игрока, если он занял первое место в турнире
        public static function FirstPlaceTournament($class_, $id_){
            try{
                if ((strlen($class_) ==2) && ctype_digit($class_{1}) && ($class_{1} !=1) && ($class_{1} !=8)){
                    switch($class_{0}){
                        case 'A' : $A_B_C=1; break;
                        case 'B' : $A_B_C=2; break;
                        case 'C' : $A_B_C=3; break;
                        default : throw new Exception();
                    }#switch
                    $class_user = CUsers_::ReadClass_($id_,$A_B_C);
                    if (($class_{1} -1) < $class_user)
                       CUsers_::SetClass_($id_,$A_B_C,$class_{1}-1);
                }
            }catch(Exception $e){
                throw new Exception('При изменении класса игрока произошла ошибка.');
            }
        } #FirstPlaceTournament

# Изменяет класс игрока, если он занял последнее место в турнире
        public static function LastPlaceTournament($class_, $id_){
            try{
                if ((strlen($class_) ==2) && ctype_digit($class_{1}) && ($class_{1} < 7)){
                    switch($class_{0}){
                        case 'A' : $A_B_C=1; break;
                        case 'B' : $A_B_C=2; break;
                        case 'C' : $A_B_C=3; break;
                        default : throw new Exception();
                    }#switch
                    $class_user = CUsers_::ReadClass_($id_,$A_B_C);

                    if (($class_{1} +1) > $class_user)
                       CUsers_::SetClass_($id_,$A_B_C,$class_{1}+1);
                }
            }catch(Exception $e){
                throw new Exception('При изменении класса игрока произошла ошибка.');
            }
        }#LastPlaceTournament

#$type_ - тоже, что и в CTournaments_::MakePage()
        public static function MakeMenuMainPage($type_){
            $i =CPage_::PositionMenu_('Турниры') +1;

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=you_active_tournaments';
            CPage_::$menu_[$i]['image'] ='Image/label_you_active_tournaments.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==1) ? 'Y' : 'N';

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=access_tournaments';
            CPage_::$menu_[$i]['image'] ='Image/label_access_tournaments.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==2) ? 'Y' : 'N';

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=active_tournaments&self_login=yes';
            CPage_::$menu_[$i]['image'] ='Image/label_active_tournaments.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==3) ? 'Y' : 'N';

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=end_tournaments&self_login=yes';
            CPage_::$menu_[$i]['image'] ='Image/label_end_tournaments.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==4) ? 'Y' : 'N';

            CPage_::MakeMenu_(CPage_::PositionMenu_('Турниры'));
        }#MakeMenuMainPage

#$type_: 1 - активные с вашим участием, 2 - доступные, 3 - активные, 4 - завершенные
        public static function MakePage($type_){
            unset($_SESSION[SESSION_LINK_ESC_INFO_TOURNAMENT]);
            $link_esc_info_tournament='';
            unset($_SESSION[SESSION_LINK_ESC_DOC]);
            $link_esc_doc='';
            unset($_SESSION[SESSION_LINK_ESC_INFO_SWISS_TOURNAMENT]);
            $link_esc_info_swiss_tournament='';

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
                        $body_ =CTournaments_::BodyActiveTournaments();
                        $link_esc_info_tournament='MainPage.php?link_=you_active_tournaments';
                        $link_esc_info_swiss_tournament='MainPage.php?link_=you_active_tournaments';
                        $link_esc_doc='MainPage.php?link_=you_active_tournaments';
                        break;
                    case 2 :
                        CInfo_swiss_tournament_::del_tournaments_no_gamers();
                        CTournaments_::newTournaments();
                        $body_ =CTournaments_::BodyAccessTournaments('A'.$classA_,'C'.$classC_,'B'.$classB_);
                        $link_esc_info_tournament='MainPage.php?link_=access_tournaments';
                        $link_esc_info_swiss_tournament='MainPage.php?link_=access_tournaments';
                        $link_esc_doc='MainPage.php?link_=access_tournaments';
                        break;
                    case 3 :
                        CInfo_swiss_tournament_::del_tournaments_no_gamers();
                        if (isset($_GET['self_login']))
                          $params_['login_'] =$_SESSION[SESSION_LOGIN_];
                         elseif (isset($_POST['find_login_']) && (trim($_POST['find_login_']) != ''))
                          $params_['login_'] =$_POST['find_login_'];
                         elseif (isset($_GET['find_login_']) && (trim($_GET['find_login_']) != ''))
                          $params_['login_'] =$_GET['find_login_'];
                         else
                          $params_['login_'] ='';
                        if (isset($_POST['find_class_']) && ($_POST['find_class_'] != ''))
                           $params_['class_'] =$_POST['find_class_'];
                         elseif (isset($_GET['find_class_']) && ($_GET['find_class_'] != ''))
                          $params_['class_'] =$_GET['find_class_'];
                         else
                          $params_['class_'] ='';
                        if (isset($_POST['system_']) && ($_POST['system_'] != ''))
                           $params_['system_'] =$_POST['system_'];
                         elseif (isset($_GET['system_']) && ($_GET['system_'] != ''))
                          $params_['system_'] =$_GET['system_'];
                         else
                          $params_['system_'] ='';
                        if (($params_['class_'] != '') &&
                            ($params_['class_'] != 'A') && ($params_['class_'] != 'B') && ($params_['class_'] != 'C') &&
                            ((($params_['class_'] < 'A1') || ($params_['class_'] > 'A8')) &&
                             (($params_['class_'] < 'B1') || ($params_['class_'] > 'B8')) &&
                             (($params_['class_'] < 'C1') || ($params_['class_'] > 'C8'))))
                          $params_['class_'] ='';
                        if (!isset($_GET['page']) || !ctype_digit($_GET['page']))
                                $p=1;
                            else $p =$_GET['page'];
                        $body_ =CTournaments_::BodyNonEndTournaments($params_,$p);
                        $link_esc_info_tournament=CActiveTournaments::get_link($params_,$p);
                        $link_esc_info_swiss_tournament=CActiveTournaments::get_link($params_,$p);
                        $link_esc_doc=CActiveTournaments::get_link($params_,$p);
                        break;
                    case 4 :
                        try{
                          if (isset($_GET['self_login']))
                            $params_['login_'] =$_SESSION[SESSION_LOGIN_];
                           elseif (isset($_POST['find_login_']) && (trim($_POST['find_login_']) != ''))
                            $params_['login_'] =$_POST['find_login_'];
                           elseif (isset($_GET['find_login_']) && (trim($_GET['find_login_']) != ''))
                            $params_['login_'] =$_GET['find_login_'];
                           else
                            $params_['login_'] ='';

                           if (isset($_POST['find_class_']) && ($_POST['find_class_'] != ''))
                              $params_['class_'] =$_POST['find_class_'];
                            elseif (isset($_GET['find_class_']) && ($_GET['find_class_'] != ''))
                             $params_['class_'] =$_GET['find_class_'];
                            else
                             $params_['class_'] ='';

                           if (isset($_POST['system_']) && ($_POST['system_'] != ''))
                             $params_['system_'] =$_POST['system_'];
                           elseif (isset($_GET['system_']) && ($_GET['system_'] != ''))
                             $params_['system_'] =$_GET['system_'];
                           else
                             $params_['system_'] ='';

                           if (($params_['class_'] != '') &&
                               ($params_['class_'] != 'A') && ($params_['class_'] != 'B') && ($params_['class_'] != 'C') &&
                               ((($params_['class_'] < 'A1') || ($params_['class_'] > 'A8')) &&
                                (($params_['class_'] < 'B1') || ($params_['class_'] > 'B8')) &&
                                (($params_['class_'] < 'C1') || ($params_['class_'] > 'C8'))))
                             $params_['class_'] ='';

                           if (isset($_GET['find_reglament_']) && ctype_digit($_GET['find_reglament_']))
                              $params_['reglament_'] =$_GET['find_reglament_'];
                            elseif (isset($_POST['find_reglament_']) && ctype_digit($_POST['find_reglament_']))
                              $params_['reglament_'] =$_POST['find_reglament_'];
                            else
                              $params_['reglament_'] ='';
                            if (($params_['reglament_'] !=='') &&
                                (($params_['reglament_'] < 1) || ($params_['reglament_'] > 10)))
                              $params_['reglament_'] ='';

                           if (isset($_GET['find_place_'])){
                             $params_['place_'] = trim($_GET['find_place_']);
                             if (($params_['place_'] !='') && !ctype_digit($params_['place_']))
                                throw new ETournamentError('место указано неверно');
                           }elseif (isset($_POST['find_place_'])){
                             $params_['place_'] = trim($_POST['find_place_']);
                             if (($params_['place_'] !='') && !ctype_digit($params_['place_']))
                                throw new ETournamentError('место указано неверно');
                           }else
                             $params_['place_'] ='';

                           if (!isset($_GET['page']) || !ctype_digit($_GET['page']))
                                $p=1;
                            else $p =$_GET['page'];

                           $body_ =CTournaments_::BodyEndTournaments($params_,$p,'');
                           $link_esc_info_tournament=CEndTournaments::get_link($params_,$p);
                           $link_esc_info_swiss_tournament=CEndTournaments::get_link($params_,$p);
                           $link_esc_doc=CEndTournaments::get_link($params_,$p);
                        }catch(Exception $e){
                           if ($e instanceof ETournamentError){
                              $body_ =CTournaments_::BodyEndTournaments($params_,1,$e->getMessage());
                            }else
                              throw new Exception($e->getMessage());
                        }#catch
                        break;
                    default :
                        $body_ ='';
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
                                                 '  Турниры'.
                                                 '</DIV>';
                CTournaments_::MakeMenuMainPage($type_);
                CPage_::$body_ =$body_;
                CPage_::MakePage();
                if ($link_esc_info_tournament !='')
                   $_SESSION[SESSION_LINK_ESC_INFO_TOURNAMENT]=$link_esc_info_tournament;
                if($link_esc_info_swiss_tournament !='')
                   $_SESSION[SESSION_LINK_ESC_INFO_SWISS_TOURNAMENT]=$link_esc_info_swiss_tournament;
                if ($link_esc_doc !='')
                   $_SESSION[SESSION_LINK_ESC_DOC]=$link_esc_doc;
            }catch(Exception $e){
				if ($transact_) const_::Rollback_();
				if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
				CPage_::PageErr();
            }#try
        }#MakePage
	} #CTournamenst_
?>