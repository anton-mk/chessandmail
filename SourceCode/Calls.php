<?php
   require_once('lib/mylib.php');
   require_once('const_.php');

   class CCalls_nonpersonal_A extends CPartOfQuery_{
       protected $page_personal;
       protected $page_nonpersonal_C;
       public $records_ =array();

       public function __construct($page_personal,$page_nonpersonal_C){
            $this->page_personal = $page_personal;
            $this->page_nonpersonal_C = $page_nonpersonal_C;
            parent::__construct(const_::$connect_);
       }#__construct

       protected function str_select_for_countPage(){
            $result_ ='select count(*) as count_'.
                      ' from TCallsToGame_ '.
                      ' where (id_gamerMakeCall_ <> '.$_SESSION[SESSION_ID_].') and (id_gamer_ is null) and (class_=\'A\')';
            return $result_;
       } #str_select_for_countPage

       protected function str_select_for_getRecords(){
            $result_ ='select A.id_,A.reglament_,A.gameIsRating_,A.timeMake_,adddate(A.timeMake_,interval A.callEnd_ day) as timeEnd_,B.login_,'.
                      '       A.gamerMakeCallIsWhite_,A.comment_'.
                      ' from TCallsToGame_ A, TGamers_ B'.
                      ' where (A.id_gamerMakeCall_ <> '.$_SESSION[SESSION_ID_].') and (A.id_gamerMakeCall_ = B.id_) and '.
                      '       (A.id_gamer_ is null) and (A.class_=\'A\')'.
                      ' order by A.id_';
            return $result_;
        }#str_select_for_countPage

       public function get_records($page_){
            global $reglaments_;
            try{
                if (!$this->getRecords(false,$page_,array('id_','reglament_','gameIsRating_','timeMake_',
                                                          'timeEnd_','login_','gamerMakeCallIsWhite_','comment_')))
                  throw new Exception();
                for($i=0; $i<count($this->listRecords); $i++){
                    $this->records_[$i]['id_'] =$this->listRecords[$i]['id_'];
                    $this->records_[$i]['reglament_']    = $reglaments_['reglament'.$this->listRecords[$i]['reglament_'].'_'];
                    $this->records_[$i]['gameIsRating_'] = $this->listRecords[$i]['gameIsRating_'] == 'Y' ? 'да' : 'нет';
                    $this->records_[$i]['timeMake_']     = convert_cyr_string($this->listRecords[$i]['timeMake_'],'d','w');
                    $this->records_[$i]['timeEnd_']      = convert_cyr_string($this->listRecords[$i]['timeEnd_'],'d','w');
                    $this->records_[$i]['login_']        = convert_cyr_string($this->listRecords[$i]['login_'],'d','w');
                    $this->records_[$i]['gamerMakeCallIsWhite_'] = $this->listRecords[$i]['gamerMakeCallIsWhite_'] == 'Y' ? 'чёрный' : 'белый';
                    $this->records_[$i]['comment_']      = convert_cyr_string($this->listRecords[$i]['comment_'],'d','w');
                } #for
            }catch(Exception $e){
                throw new Exception('При чтении информации о вызовах произошла ошибка.');
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

                if ($a > 1) $m ='<A href="'.CCalls_::get_link_off_line($this->page_personal,$a-1,$this->page_nonpersonal_C).'">'.htmlspecialchars('<<',ENT_QUOTES,CODE_PAGE).'</A>';
                for ($i=$a; $i <=$b; $i++){
                    if ($m != '') $m .='&nbsp;';
                    if ($this->page_ != $i) $m .='<A href="'.CCalls_::get_link_off_line($this->page_personal,$i,$this->page_nonpersonal_C).'">'.$i.'</A>'; else $m.=$i;
                }#for
                if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.CCalls_::get_link_off_line($this->page_personal,$b+1,$this->page_nonpersonal_C).'">'.htmlspecialchars('>>',ENT_QUOTES,CODE_PAGE).'</A>';
                $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            }
            $result_ .='<TR><TD>'."\n".
                       '<TABLE cellspacing="3">'."\n".
                       '     <COL span="9">'."\n".
                       '     <TR><TD class="table_head_1">№</TD>'."\n".
                                '<TD class="table_head_1">&nbsp;</TD>'.
                                '<TD class="table_head_1">От кого</TD>'.
                                '<TD class="table_head_1">Регламент</TD>'.
                                '<TD class="table_head_1">Ваш цвет</TD>'.
                                '<TD class="table_head_1">Рейтинговая</TD>'.
                                '<TD class="table_head_1">Время отправки вызова</TD>'.
                                '<TD class="table_head_1">Время окончания вызова</TD>'.
                                '<TD class="table_head_1">Комментарий</TD>'.
                       '     </TR>';
            for($i=0; $i < count($this->records_); $i++){
                $result_ .='<TR>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['id_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'."\n".
                           '          <A href="'.CCalls_::get_link_off_line($this->page_personal,$this->page_,$this->page_nonpersonal_C).'&question_accept_call='.$this->records_[$i]['id_'].'">принять</A>'."\n".
                           '    </TD>'."\n".
                           '    <TD class="table_body_1">'."\n".
                           '          <A href="MainPage.php?link_=about_gamer&login_='.urlencode($this->records_[$i]['login_']).'">'.htmlspecialchars($this->records_[$i]['login_'],ENT_QUOTES,CODE_PAGE).'</A>'."\n".
                           '    </TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['reglament_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['gamerMakeCallIsWhite_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['gameIsRating_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['timeMake_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['timeEnd_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.htmlspecialchars($this->records_[$i]['comment_'],ENT_QUOTES,CODE_PAGE).'</TD>'."\n".
                           '</TR>'."\n";
            } #for
            $result_ .='</TABLE>'."\n".
                       '</TD></TR>'."\n";
            if ($m != '')
              $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            $result_ .='</TABLE></SPAN>';
            return $result_;
       }#out_records
   }#CCalls_nonpersonal_A

   class CCalls_nonpersonal_C extends CPartOfQuery_{
       protected $page_personal;
       protected $page_nonpersonal_A;
       public $records_ =array();

       public function __construct($page_personal,$page_nonpersonal_A){
            $this->page_personal = $page_personal;
            $this->page_nonpersonal_A = $page_nonpersonal_A;
            parent::__construct(const_::$connect_);
       }#__construct

       protected function str_select_for_countPage(){
            $result_ ='select count(*) as count_'.
                      ' from TCallsToGame_ '.
                      ' where (id_gamerMakeCall_ <> '.$_SESSION[SESSION_ID_].') and (id_gamer_ is null) and (class_=\'C\')';
            return $result_;
       } #str_select_for_countPage

       protected function str_select_for_getRecords(){
            $result_ ='select A.id_,A.reglament_,A.gameIsRating_,A.timeMake_,adddate(A.timeMake_,interval A.callEnd_ day) as timeEnd_,B.login_,'.
                      '       A.gamerMakeCallIsWhite_,A.comment_'.
                      ' from TCallsToGame_ A, TGamers_ B'.
                      ' where (A.id_gamerMakeCall_ <> '.$_SESSION[SESSION_ID_].') and (A.id_gamerMakeCall_ = B.id_) and '.
                      '       (A.id_gamer_ is null) and (A.class_=\'C\')'.
                      ' order by A.id_';
            return $result_;
        }#str_select_for_countPage

       public function get_records($page_){
            global $reglaments_;
            try{
                if (!$this->getRecords(false,$page_,array('id_','reglament_','gameIsRating_','timeMake_',
                                                          'timeEnd_','login_','gamerMakeCallIsWhite_','comment_')))
                  throw new Exception();
                for($i=0; $i<count($this->listRecords); $i++){
                    $this->records_[$i]['id_'] =$this->listRecords[$i]['id_'];
                    $this->records_[$i]['reglament_']    = $reglaments_['reglament'.$this->listRecords[$i]['reglament_'].'_'];
                    $this->records_[$i]['gameIsRating_'] = $this->listRecords[$i]['gameIsRating_'] == 'Y' ? 'да' : 'нет';
                    $this->records_[$i]['timeMake_']     = convert_cyr_string($this->listRecords[$i]['timeMake_'],'d','w');
                    $this->records_[$i]['timeEnd_']      = convert_cyr_string($this->listRecords[$i]['timeEnd_'],'d','w');
                    $this->records_[$i]['login_']        = convert_cyr_string($this->listRecords[$i]['login_'],'d','w');
                    $this->records_[$i]['gamerMakeCallIsWhite_'] = $this->listRecords[$i]['gamerMakeCallIsWhite_'] == 'Y' ? 'чёрный' : 'белый';
                    $this->records_[$i]['comment_']      = convert_cyr_string($this->listRecords[$i]['comment_'],'d','w');
                } #for
            }catch(Exception $e){
                throw new Exception('При чтении информации о вызовах произошла ошибка.');
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

                if ($a > 1) $m ='<A href="'.CCalls_::get_link_off_line($this->page_personal,$this->page_nonpersonal_A,$a-1).'">'.htmlspecialchars('<<',ENT_QUOTES,CODE_PAGE).'</A>';
                for ($i=$a; $i <=$b; $i++){
                    if ($m != '') $m .='&nbsp;';
                    if ($this->page_ != $i) $m .='<A href="'.CCalls_::get_link_off_line($this->page_personal,$this->page_nonpersonal_A,$i).'">'.$i.'</A>'; else $m.=$i;
                }#for
                if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.CCalls_::get_link_off_line($this->page_personal,$this->page_nonpersonal_A,$b+1).'">'.htmlspecialchars('>>',ENT_QUOTES,CODE_PAGE).'</A>';
                $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            }
            $result_ .='<TR><TD>'."\n".
                       '<TABLE cellspacing="3">'."\n".
                       '     <COL span="9">'."\n".
                       '     <TR><TD class="table_head_1">№</TD>'."\n".
                                '<TD class="table_head_1">&nbsp;</TD>'.
                                '<TD class="table_head_1">От кого</TD>'.
                                '<TD class="table_head_1">Регламент</TD>'.
                                '<TD class="table_head_1">Ваш цвет</TD>'.
                                '<TD class="table_head_1">Рейтинговая</TD>'.
                                '<TD class="table_head_1">Время отправки вызова</TD>'.
                                '<TD class="table_head_1">Время окончания вызова</TD>'.
                                '<TD class="table_head_1">Комментарий</TD>'.
                       '     </TR>';
            for($i=0; $i < count($this->records_); $i++){
                $result_ .='<TR>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['id_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'."\n".
                           '          <A href="'.CCalls_::get_link_off_line($this->page_personal,$this->page_nonpersonal_A,$this->page_).'&question_accept_call='.$this->records_[$i]['id_'].'">принять</A>'."\n".
                           '    </TD>'."\n".
                           '    <TD class="table_body_1">'."\n".
                           '          <A href="MainPage.php?link_=about_gamer&login_='.urlencode($this->records_[$i]['login_']).'">'.htmlspecialchars($this->records_[$i]['login_'],ENT_QUOTES,CODE_PAGE).'</A>'."\n".
                           '    </TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['reglament_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['gamerMakeCallIsWhite_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['gameIsRating_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['timeMake_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['timeEnd_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.htmlspecialchars($this->records_[$i]['comment_'],ENT_QUOTES,CODE_PAGE).'</TD>'."\n".
                           '</TR>'."\n";
            } #for
            $result_ .='</TABLE>'."\n".
                       '</TD></TR>'."\n";
            if ($m != '')
              $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            $result_ .='</TABLE></SPAN>';
            return $result_;
       }#out_records
   }#CCalls_nonpersonal_C

   class CCalls_personal extends CPartOfQuery_{
       protected $page_nonpersonal_A;
       protected $page_nonpersonal_C;
       public $records_ =array();

       public function __construct($page_nonpersonal_A,$page_nonpersonal_C){
            $this->page_nonpersonal_A = $page_nonpersonal_A;
            $this->page_nonpersonal_C = $page_nonpersonal_C;
            parent::__construct(const_::$connect_);
       }#__construct

       protected function str_select_for_countPage(){
            $result_ ='select count(*) as count_'.
                      ' from TCallsToGame_ '.
                      ' where (id_gamer_ = '.$_SESSION[SESSION_ID_].') and ((class_=\'C\') or (class_=\'A\'))';
            return $result_;
       } #str_select_for_countPage

       protected function str_select_for_getRecords(){
            $result_ ='select A.id_,A.reglament_,A.gameIsRating_,A.timeMake_,adddate(A.timeMake_,interval A.callEnd_ day) as timeEnd_,B.login_,'.
                      '       A.gamerMakeCallIsWhite_,A.comment_,A.class_'.
                      ' from TCallsToGame_ A, TGamers_ B'.
                      ' where (A.id_gamerMakeCall_ = B.id_) and '.
                      '       (A.id_gamer_ = '.$_SESSION[SESSION_ID_].') and ((A.class_=\'C\') or (A.class_=\'A\'))'.
                      ' order by A.class_,A.id_';
            return $result_;
        }#str_select_for_countPage

       public function get_records($page_){
            global $reglaments_;
            try{
                if (!$this->getRecords(false,$page_,array('id_','reglament_','gameIsRating_','timeMake_',
                                                          'timeEnd_','login_','gamerMakeCallIsWhite_','comment_','class_')))
                  throw new Exception();
                for($i=0; $i<count($this->listRecords); $i++){
                    $this->records_[$i]['id_'] =$this->listRecords[$i]['id_'];
                    $this->records_[$i]['reglament_']    = $reglaments_['reglament'.$this->listRecords[$i]['reglament_'].'_'];
                    $this->records_[$i]['gameIsRating_'] = $this->listRecords[$i]['gameIsRating_'] == 'Y' ? 'да' : 'нет';
                    $this->records_[$i]['timeMake_']     = convert_cyr_string($this->listRecords[$i]['timeMake_'],'d','w');
                    $this->records_[$i]['timeEnd_']      = convert_cyr_string($this->listRecords[$i]['timeEnd_'],'d','w');
                    $this->records_[$i]['login_']        = convert_cyr_string($this->listRecords[$i]['login_'],'d','w');
                    $this->records_[$i]['gamerMakeCallIsWhite_'] = $this->listRecords[$i]['gamerMakeCallIsWhite_'] == 'Y' ? 'чёрный' : 'белый';
                    $this->records_[$i]['comment_']      = convert_cyr_string($this->listRecords[$i]['comment_'],'d','w');
                    $this->records_[$i]['class_']        = $this->listRecords[$i]['class_'];
                } #for
            }catch(Exception $e){
                throw new Exception('При чтении информации о вызовах произошла ошибка.');
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

                if ($a > 1) $m ='<A href="'.CCalls_::get_link_off_line($a-1,$this->page_nonpersonal_A,$this->page_nonpersonal_C).'">'.htmlspecialchars('<<',ENT_QUOTES,CODE_PAGE).'</A>';
                for ($i=$a; $i <=$b; $i++){
                    if ($m != '') $m .='&nbsp;';
                    if ($this->page_ != $i) $m .='<A href="'.CCalls_::get_link_off_line($i,$this->page_nonpersonal_A,$this->page_nonpersonal_C).'">'.$i.'</A>'; else $m.=$i;
                }#for
                if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.CCalls_::get_link_off_line($b+1,$this->page_nonpersonal_A,$this->page_nonpersonal_C).'">'.htmlspecialchars('>>',ENT_QUOTES,CODE_PAGE).'</A>';
                $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            }
            $result_ .='<TR><TD>'."\n".
                       '<TABLE cellspacing="3">'."\n".
                            '  <COL span="11">'.
                            '     <TR><TD class="table_head_1">№</TD>'.
                            '         <TD class="table_head_1">&nbsp;</TD>'.
                            '         <TD class="table_head_1">&nbsp;</TD>'.
                            '         <TD class="table_head_1">От кого</TD>'.
                            '         <TD class="table_head_1">Класс</TD>'.
                            '         <TD class="table_head_1">Регламент</TD>'.
                            '         <TD class="table_head_1">Ваш цвет</TD>'.
                            '         <TD class="table_head_1">Рейтинговая</TD>'.
                            '         <TD class="table_head_1">Время отправки вызова</TD>'.
                            '         <TD class="table_head_1">Время окончания вызова</TD>'.
                            '         <TD class="table_head_1">Комментарий</TD>'.
                            '     </TR>';
            for($i=0; $i < count($this->records_); $i++){
                $result_ .='<TR>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['id_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'."\n".
                           '          <A href="'.CCalls_::get_link_off_line($this->page_,$this->page_nonpersonal_A,$this->page_nonpersonal_C).'&question_accept_call='.$this->records_[$i]['id_'].'">принять</A>'."\n".
                           '    </TD>'."\n".
                           '    <TD class="table_body_1">'."\n".
                           '          <A href="'.CCalls_::get_link_off_line($this->page_,$this->page_nonpersonal_A,$this->page_nonpersonal_C).'&question_decline_call='.$this->records_[$i]['id_'].'">отклонить</A>'."\n".
                           '    </TD>'."\n".
                           '    <TD class="table_body_1">'."\n".
                           '          <A href="MainPage.php?link_=about_gamer&login_='.urlencode($this->records_[$i]['login_']).'">'.htmlspecialchars($this->records_[$i]['login_'],ENT_QUOTES,CODE_PAGE).'</A>'."\n".
                           '    </TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['class_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['reglament_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['gamerMakeCallIsWhite_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['gameIsRating_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['timeMake_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.$this->records_[$i]['timeEnd_'].'</TD>'."\n".
                           '    <TD class="table_body_1">'.htmlspecialchars($this->records_[$i]['comment_'],ENT_QUOTES,CODE_PAGE).'</TD>'."\n".
                           '</TR>'."\n";
            } #for
            $result_ .='</TABLE>'."\n".
                       '</TD></TR>'."\n";
            if ($m != '')
              $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            $result_ .='</TABLE></SPAN>';
            return $result_;
       }#out_records
   }#CCalls_personal

   class ECheckCallError extends Exception{}; #Неправильно заданы параметры вызова

   class CCalls_{
        public static function get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C){
            $href_='MainPage.php?link_=calls_off_line&personal='.$page_personal.'&nonpersonal_A='.$page_nonpersonal_A.'&nonpersonal_C='.$page_nonpersonal_C;
            return $href_;
        }#get_link_off_line

        protected static function outQuestionAcceptCall($id_,$header_,$page_personal,$page_nonpersonal_A,$page_nonpersonal_C){
            CPage_::$header_ =$header_;
            CPage_::QuestionPage('Подтвердите Ваше решение принять вызов.',
                                                  CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C),
                                                  CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C).'&accept_call='.$id_);
        }#outQuestionAcceptCall

        protected static function outQuestionDeclineCall($id_,$header_,$page_personal,$page_nonpersonal_A,$page_nonpersonal_C){
            CPage_::$header_ =$header_;
            CPage_::QuestionPage('Подтвердите Ваше решение отклонить вызов.',
                                                  CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C),
                                                  CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C).'&decline_call='.$id_);
        }#outQuestionDeclineCall

        protected static function outMessageAcceptCall($header_,$page_personal,$page_nonpersonal_A,$page_nonpersonal_C){
            CPage_::$header_ =$header_;
            CPage_::MessagePage('Вызов принят.',
                                                   CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C));
        }#outMessageAcceptCall

        protected static function outMessageDeclineCall($header_,$page_personal,$page_nonpersonal_A,$page_nonpersonal_C){
            CPage_::$header_ =$header_;
            CPage_::MessagePage('Вызов отклонен.',
                                                   CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C));
        }#outMessageDeclineCall

       protected static function outMessageDelCall($header_,$page_personal,$page_nonpersonal_A,$page_nonpersonal_C){
            CPage_::$header_ =$header_;
            CPage_::MessagePage('Вызов снят.',
                                CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C));
       }#outMessageDelCall

#$type_ - тоже, что и в MakePage()
        public static function MakeMenuMainPage($type_){
            $i =CPage_::PositionMenu_('Вызовы') +1;

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=calls_off_line';
            CPage_::$menu_[$i]['image'] ='Image/label_calls_mail.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==1) ? 'Y' : 'N';

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=calls_on_line';
            CPage_::$menu_[$i]['image'] ='Image/label_calls_on_line.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==2) ? 'Y' : 'N';

            CPage_::MakeMenu_(CPage_::PositionMenu_('Вызовы'));
        }#MakeMenuMainPage

//считываем из базы исходящие вызовы
        protected static function GetOut_(){
            global $reglaments_;
            $result_ =array();
            $s ='select A.id_,A.reglament_,A.gameIsRating_,A.timeMake_,adddate(A.timeMake_,interval A.callEnd_ day) as timeEnd_,B.login_,'.
                '       A.gamerMakeCallIsWhite_,A.comment_,A.class_'.
                ' from TCallsToGame_ A left join TGamers_ B on A.id_gamer_ = B.id_'.
                ' where (A.id_gamerMakeCall_ ='.$_SESSION[SESSION_ID_].') and (A.class_ <> \'B\')'.
                ' order by A.class_,A.id_';
            $cursor_=mysql_query($s,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о вызовах произошла ошибка.');
            while ($row_ =mysql_fetch_array($cursor_)){
                $result_[$row_['id_']]['reglament_']       = $reglaments_['reglament'.$row_['reglament_'].'_'];
                $result_[$row_['id_']]['gameIsRating_'] = $row_['gameIsRating_'] == 'Y' ? 'да' : 'нет';
                $result_[$row_['id_']]['timeMake_']        = convert_cyr_string($row_['timeMake_'],'d','w');
                $result_[$row_['id_']]['timeEnd_']          = convert_cyr_string($row_['timeEnd_'],'d','w');
                $result_[$row_['id_']]['login_']               = convert_cyr_string($row_['login_'],'d','w');
                $result_[$row_['id_']]['gamerMakeCallIsWhite_'] =$row_['gamerMakeCallIsWhite_'] == 'Y' ? 'белый' : 'черный';
                $result_[$row_['id_']]['comment_']        = convert_cyr_string($row_['comment_'],'d','w');
                $result_[$row_['id_']]['class_']              =$row_['class_'];
            }#while
            mysql_free_result($cursor_);
            return $result_;
        }#GetOut_

       protected static function BodyOnLine(){
          if (!CUsers_::Read_dhtml_($_SESSION[SESSION_LOGIN_])){
            $result_ ='<BR>'.
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                         'Для отображения данного раздела флаг dhtml в разделе "Настройка" должен быть установлен.'."\n".
                      '</DIV>'."\n";
          }else{
            $result_ ='<SCRIPT type="text/javascript" src="scripts/modal_form.js"></SCRIPT>'."\n".
                      '  <SCRIPT type="text/javascript">'."\n".
                      '     var o_control_mens_on_line = null;'."\n".
                      '     window.onload =function(){ '."\n".
                      '        o_modal_ = new cl_modal_();'."\n".
                      '     }'."\n".
                      '  </SCRIPT>'."\n".

                      '<TABLE style="border: none; width:100%" cellspacing="0">'.
                      '    <COL width="70%">'.
                      '    <COL width="30%">'.
                      '    <TR>'.
                      '       <TD>'.
                      '          <IFRAME width="100%" height="500px" frameborder="0" scrolling="auto" src="MainPage.php?link_=calls_on_line&sub_link_=calls"></IFRAME>'.
                      '       </TD>'.
                      '       <TD>'.
                      '          <IFRAME width="100%" height="500px" frameborder="0" scrolling="auto" src="MainPage.php?link_=calls_on_line&sub_link_=mens_on_line"></IFRAME>'.
                      '       </TD>'.
                      '    </TR>'.
                      '</TABLE>';
          }
          return $result_;
       }#BodyOnLine


       protected static function Body_mens_on_line(){
          $page_ ='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">'."\n".
                  '<HTML>'."\n".
                  '  <HEAD>'."\n".
                  '    <META http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251">'."\n".
                  '    <META name="author" content="Антон">'."\n".
                  '    <LINK href="styles/chess.css" rel="stylesheet" type="text/css">'."\n".
                  '  </HEAD>'."\n".
                  '  <BODY class="center_">'."\n".
                      '<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black; text-decoration: none; font-weight: normal">'."\n".
                        '<DIV style="text-align: center; color: white">'."\n".
                          'Зашли 2 минуты назад'."\n".
                        '</DIV>'."\n".
                        '<TEXTAREA rows="5" style="width:100%" name="enter_mens" id="enter_mens" readonly>'."\n".
                           'запрос информации...'."\n".
                        '</TEXTAREA>'."\n".
                        '<BR>'."\n".
                        '<DIV style="text-align: center; color: white">'.
                          'На сайте'."\n".
                          '<DIV id="labels_mens_on_line" style="text-align:right"></DIV>'.
                          '<HR>'.
                        '</DIV>'."\n".
                        '<SPAN name="list_mens" id="list_mens">'."\n".
                          '<DIV style="text-align: center; color: black">запрос информации...</DIV>'."\n".
                        '</SPAN>'."\n".
                      '</SPAN>'."\n".
                  '  </BODY>'."\n".
                  '  <SCRIPT type="text/javascript" src="scripts/json2.js"></SCRIPT>'."\n".
                  '  <SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n".
                  '  <SCRIPT type="text/javascript" src="scripts/calls_on_line.js"></SCRIPT>'."\n".
                  '  <SCRIPT type="text/javascript">'."\n".
                  '     var o_control_mens_on_line = null;'."\n".
                  '     window.onload =function(){ '."\n".
                  '        o_control_mens_on_line = new cl_control_mens_on_line();'."\n".
                  '        o_control_mens_on_line.read_info();'."\n".
                  '     }'."\n".
                  '  </SCRIPT>'."\n".
                  '</HTML>'."\n";
          echo($page_);
       }#Body_mens_on_line

       protected static function Body_table_calls(){
          $page_ ='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">'."\n".
                  '<HTML>'."\n".
                  '  <HEAD>'."\n".
                  '    <META http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251">'."\n".
                  '    <META name="author" content="Антон">'."\n".
                  '    <LINK href="styles/chess.css" rel="stylesheet" type="text/css">'."\n".
                  '  </HEAD>'."\n".
                  '  <BODY class="center_">'."\n".
                      '<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black; text-decoration: none; font-weight: normal">'."\n".

                        '<DIV style="text-align: center; color: white">'."\n".
                          'Исходящие вызовы'."\n".
                        '</DIV>'."\n".
                        '<SPAN name="out_calls" id="out_calls" style="font-size: 11pt">'."\n".
                           '<DIV style="text-align: center; color: black">запрос информации...</DIV>'."\n".
                        '</SPAN>'."\n".
                        '<BR>'."\n".
                        '<DIV style="white; text-align: center">'."\n".
                            '<A href="MainPage.php?link_=calls_on_line&sub_link_=form_call" target="_parent">'.
                                 'Послать вызов'.
                            '</A>'."\n".
                        '</DIV>'."\n".
                        '<BR>'."\n".
                        '<DIV style="text-align: center; color: white">'."\n".
                          'Персональные вызовы'."\n".
                        '</DIV>'."\n".
                        '<SPAN name="person_calls" id="person_calls" style="font-size: 11pt">'."\n".
                           '<DIV style="text-align: center; color: black">запрос информации...</DIV>'."\n".
                        '</SPAN>'."\n".
                        '<BR>'."\n".
                        '<DIV style="text-align: center; color: white">'.
                          'Общие вызовы'."\n".
                        '</DIV>'."\n".
                        '<DIV id="labels_calls_on_line" style="text-align:left" style="font-size: 11pt"></DIV>'.
                        '<SPAN name="total_calls" id="total_calls" style="font-size: 11pt">'."\n".
                          '<DIV style="text-align: center; color: black">запрос информации...</DIV>'."\n".
                        '</SPAN>'."\n".
                      '</SPAN>'."\n".
                  '  </BODY>'."\n".
                  '  <SCRIPT type="text/javascript" src="scripts/json2.js"></SCRIPT>'."\n".
                  '  <SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n".
                  '  <SCRIPT type="text/javascript" src="scripts/calls_on_line.js"></SCRIPT>'."\n".
                  '  <SCRIPT type="text/javascript">'."\n".
                  '     var o_control_calls_on_line = null;'."\n".
                  '     window.onload =function(){ '."\n".
                  '        o_control_calls_on_line = new cl_control_calls_on_line();'."\n".
                  '        o_control_calls_on_line.read_info();'."\n".
                  '     }'."\n".
                  '  </SCRIPT>'."\n".
                  '</HTML>'."\n";
          echo($page_);
       }#Body_table_calls

       protected static function BodyFormCall_on_line($params_,$error_){
          global $reglaments_;
          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'."\n".
                         'Форма отправки вызова (on-line)'."\n".
                    '</DIV><BR><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:left;'.
                                 'text-decoration: none; font-weight: normal">'."\n";

          if ($error_ != '')
              $result_ .='<DIV style="color: white; text-align:center">'.
                           htmlspecialchars($error_,ENT_QUOTES,CODE_PAGE).'<BR><BR>'.
                         '</DIV>'."\n";

          $result_ .='<SCRIPT type="text/javascript" src="scripts/json2.js"></SCRIPT>'."\n".
                     '<SCRIPT type="text/javascript" src="scripts/hints_.js"></SCRIPT>'."\n".
                     '<SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n".
                     '<SCRIPT type="text/javascript">'."\n".
                     '  var find_o_hints_;'.
                     '  window.onload =function(){'.
                     '       find_o_hints_ =new cl_hints_(document.getElementById("login_"),5,"ajax_tournaments_.php");'."\n".
                     '                           }'."\n".
                     '</SCRIPT>'."\n".
                     '<TABLE style="border: none; margin-left: auto; margin-right: auto">'."\n".
                     '  <COL span="2">'."\n".
                     '  <TR>'."\n".
                     '    <TD>предназначен (если не заполнено - общий вызов)</TD>'."\n".
                     '    <TD>'."\n".
                     '       <INPUT type="text" id="login_" name="login_" value="'.htmlspecialchars($params_['login_'],ENT_QUOTES,CODE_PAGE).'" autocomplete="off">'."\n".
                     '    </TD>'."\n".
                     '  </TR>'."\n".
                     '  <TR>'."\n".
                     '    <TD>регламент</TD>'."\n".
                     '    <TD>'."\n".
                     '      <SELECT name="reglament_">'."\n".
                     '         <OPTION '.(($params_['reglament_'] == '7') ? 'selected' : '').' value="7">'.$reglaments_['reglament7_'].'</OPTION>'."\n".
                     '         <OPTION '.(($params_['reglament_'] == '8') ? 'selected' : '').' value="8">'.$reglaments_['reglament8_'].'</OPTION>'."\n".
                     '         <OPTION '.(($params_['reglament_'] == '9') ? 'selected' : '').' value="9">'.$reglaments_['reglament9_'].'</OPTION>'."\n".
                     '         <OPTION '.(($params_['reglament_'] == '10') ? 'selected' : '').' value="10">'.$reglaments_['reglament10_'].'</OPTION>'."\n".
                     '      </SELECT>'."\n".
                     '    </TD>'."\n".
                     '  </TR>'."\n".
                     '  <TR>'."\n".
                     '    <TD>ваш цвет</TD>'."\n".
                     '    <TD>'."\n".
                     '      <SELECT name="color_">'."\n".
                     '         <OPTION '.(($params_['color_'] == 'white')  ? 'selected' : '').' value="white">белый</OPTION>'."\n".
                     '         <OPTION '.(($params_['color_'] == 'black')  ? 'selected' : '').' value="black">черный</OPTION>'."\n".
                     '         <OPTION '.(($params_['color_'] == 'random') ? 'selected' : '').' value="random">случайный</OPTION>'."\n".
                     '      </SELECT>'."\n".
                     '    </TD>'."\n".
                     '  <TR>'."\n".
                     '    <TD>вызов действует (в минутах)</TD>'."\n".
                     '    <TD>'."\n".
                     '       <INPUT type="text" id="do_call_" name="do_call_" value="'.$params_['do_call_'].'">'."\n".
                     '    </TD>'."\n".
                     '  </TR>'."\n".
                     '  <TR>'."\n".
                     '    <TD>партия рейтинговая</TD>'."\n".
                     '    <TD>'."\n".
                     '      <SELECT name="is_rating_">'."\n".
                     '         <OPTION '.(($params_['is_rating_'] == 'yes') ? 'selected' : '').' value="yes">да</OPTION>'."\n".
                     '         <OPTION '.(($params_['is_rating_'] == 'no')  ? 'selected' : '').' value="no">нет</OPTION>'."\n".
                     '      </SELECT>'."\n".
                     '    </TD>'."\n".
                     '  </TR>'."\n".
                     '    <TD>комментарий</TD>'."\n".
                     '    <TD>'."\n".
                     '       <INPUT type="text" id="comment_" name="comment_" maxlength="200" value="'.$params_['comment_'].'">'."\n".
                     '    </TD>'."\n".
                     '  </TR>'."\n".
                     '</TABLE>'."\n";

          $result_ .='</DIV>';
          return $result_;
       }#BodyFormCall_on_line

       protected static function FormOnLineCall($header_,$params_,$error_){
          CPage_::$header_ =$header_;
          CPage_::$action_form_ ='MainPage.php?link_=calls_on_line&make_call_on_line=yes';

          CPage_::$menu_[0]['link'] ='MainPage.php?link_=calls_on_line';
          CPage_::$menu_[0]['image'] ='Image/label_esc.png';
          CPage_::$menu_[0]['submit'] =false;
          CPage_::$menu_[0]['level'] =1;
          CPage_::$menu_[0]['active'] ='N';

          CPage_::$menu_[1]['link'] = '';
          CPage_::$menu_[1]['image'] ='Image/label_send_call.png';
          CPage_::$menu_[1]['submit'] =true;
          CPage_::$menu_[1]['level'] =1;
          CPage_::$menu_[1]['active'] ='N';

          CPage_::$body_ =CCalls_::BodyFormCall_on_line($params_,$error_);
          CPage_::MakePage();
       }#FormOnLineCall


       public static function BodyCallsMail($page_personal,$page_nonpersonal_A,$page_nonpersonal_C){
          $a =new CCalls_nonpersonal_A($page_personal,$page_nonpersonal_C);
          $a->get_records($page_nonpersonal_A);

          $c =new CCalls_nonpersonal_C($page_personal,$page_nonpersonal_A);
          $c->get_records($page_nonpersonal_C);

          $p =new CCalls_personal($page_nonpersonal_A,$page_nonpersonal_C);
          $p->get_records($page_personal);

          $out_ =CCalls_::GetOut_();

#Вывожу исходящие вызовы
          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'."\n".
                         'Вызовы,отправленные Вами (переписка)'."\n".
                    '</DIV><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:left;'.
                                 'text-decoration: none; font-weight: normal">'."\n";
          if (count($out_) ==0)
              $result_ .='<DIV style="text-align:center">Вызовов нет</DIV>';
          else{
	      $result_ .= '<TABLE style="border: none; margin-left: auto; margin-right: auto" cellspacing="3">'.
                                '    <COL span="10">'.
                                '       <TR><TD class="table_head_1">№</TD>'.
                                '           <TD class="table_head_1">&nbsp;</TD>'.
                                '           <TD class="table_head_1">Класс</TD>'.
                                '           <TD class="table_head_1">Кому отправлен</TD>'.
                                '           <TD class="table_head_1">Регламент</TD>'.
                                '           <TD class="table_head_1">Ваш цвет</TD>'.
                                '           <TD class="table_head_1">Рейтинговая</TD>'.
                                '           <TD class="table_head_1">Время отправки вызова</TD>'.
                                '           <TD class="table_head_1">Время окончания вызова</TD>'.
                                '           <TD class="table_head_1">Комментарий</TD>'.
                                '       </TR>';
               foreach($out_ as $key_=>$value_){
                  $result_.='<TR><TD class="table_body_1">'.$key_.'</TD>'.
                            '    <TD class="table_body_1">'.
                            '         <A href="'.CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C).'&stop_call='.$key_.'">отменить</A>'.
                            '    </TD>'.
                            '    <TD class="table_body_1">'.$value_['class_'].'</TD>'.
                            '    <TD class="table_body_1">';
                  if ($value_['login_'] == '')
                     $result_ .='&nbsp;';
                  else
                     $result_ .='<A href="MainPage.php?link_=about_gamer&login_='.urlencode($value_['login_']).'">'.htmlspecialchars($value_['login_'],ENT_QUOTES,CODE_PAGE).'</A>';
                  $result_ .='    </TD>'.
                             '    <TD class="table_body_1">'.$value_['reglament_'].'</TD>'.
                             '    <TD class="table_body_1">'.$value_['gamerMakeCallIsWhite_'].'</TD>'.
                             '    <TD class="table_body_1">'.$value_['gameIsRating_'].'</TD>'.
                             '    <TD class="table_body_1">'.$value_['timeMake_'].'</TD>'.
                             '    <TD class="table_body_1">'.$value_['timeEnd_'].'</TD>'.
                             '    <TD class="table_body_1">'.htmlspecialchars($value_['comment_'],ENT_QUOTES,CODE_PAGE).'</TD>'.
                             '</TR>';
               }#foreach
               $result_.='</TABLE>';
          }#if
          $result_.='</DIV>';

          $result_.='<BR><DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 14pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'."\n".
                            '<A href="'.
                                 CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C).'&form_call_off_line=yes">'.
                                 'Послать вызов'.
                            '</A>'."\n".
                        '</DIV>'."\n";

#Персональные вызовы
          $result_ .='<BR><DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                      'font-size: 16pt; color: white; text-align: center;'.
                                                      'text-decoration: none; font-weight: normal">'."\n".
                                         'Персональные вызовы (переписка)'."\n".
                           '</DIV><BR>'."\n";
          if (count($p->records_) ==0)
              $result_ .='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                  'font-size: 12pt; color: black; text-align:center;'.
                                                  'text-decoration: none; font-weight: normal">'."\n".
                              'Вызовов нет'."\n".
                         '</DIV>'."\n";
          else
              $result_ .=$p->out_records();

#Общие класса A
          $result_ .='<BR><DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                      'font-size: 16pt; color: white; text-align: center;'.
                                                      'text-decoration: none; font-weight: normal">'."\n".
                                         'Общие вызовы класса A'."\n".
                           '</DIV><BR>'."\n";
          if (count($a->records_) ==0)
              $result_ .='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                  'font-size: 12pt; color: black; text-align:center;'.
                                                  'text-decoration: none; font-weight: normal">'."\n".
                              'Вызовов нет'."\n".
                              '</DIV>'."\n";
          else
              $result_ .=$a->out_records();

#Общие класса C
          $result_ .='<BR><DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                      'font-size: 16pt; color: white; text-align: center;'.
                                                      'text-decoration: none; font-weight: normal">'."\n".
                                         'Общие вызовы класса C'."\n".
                           '</DIV><BR>'."\n";
          if (count($c->records_) ==0)
              $result_ .='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                  'font-size: 12pt; color: black; text-align:center;'.
                                                  'text-decoration: none; font-weight: normal">'."\n".
                              'Вызовов нет'."\n".
                         '</DIV>'."\n";
          else
              $result_ .=$c->out_records();

          return $result_;
       }#BodyCallsMail

       protected static function BodyOffLineFormCall($params_,$error_){
          global $reglaments_;
          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'."\n".
                         'Форма отправки вызова (переписка)'."\n".
                    '</DIV><BR><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:left;'.
                                 'text-decoration: none; font-weight: normal">'."\n";

          if ($error_ != '')
              $result_ .='<DIV style="color: white; text-align:center">'.
                           htmlspecialchars($error_,ENT_QUOTES,CODE_PAGE).'<BR><BR>'.
                         '</DIV>'."\n";

          if (!CUsers_::Read_dhtml_())
              $result_ .='<DIV>'."\n".
                          ' Если установить флаг DHTML в разделе "настройки", то поле "логин участника" '.
                          ' будет содержать выпадающий список, который появлятся при наборе первых букв.'.
                         '</DIV>'."\n";
            else
              $result_ .='<SCRIPT type="text/javascript" src="scripts/json2.js"></SCRIPT>'."\n".
                         '<SCRIPT type="text/javascript" src="scripts/hints_.js"></SCRIPT>'."\n".
                         '<SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n".
                         '<SCRIPT type="text/javascript">'."\n".
                         '  var find_o_hints_;'.
                         '  window.onload =function(){'.
                         '       find_o_hints_ =new cl_hints_(document.getElementById("login_"),5,"ajax_tournaments_.php");'."\n".
                         '                           }'."\n".
                         '</SCRIPT>'."\n";

          $result_ .=    '<TABLE style="border: none; margin-left: auto; margin-right: auto">'."\n".
                         '  <COL span="2">'."\n".
                         '  <TR>'."\n".
                         '    <TD>логин</TD>'."\n".
                         '    <TD>'."\n".
                         '       <INPUT type="text" id="login_" name="login_" value="'.htmlspecialchars($params_['login_'],ENT_QUOTES,CODE_PAGE).'" autocomplete="off">'."\n".
                         '    </TD>'."\n".
                         '  </TR>'."\n".
                         '  <TR>'."\n".
                         '    <TD>регламент</TD>'."\n".
                         '    <TD>'."\n".
                         '      <SELECT name="reglament_">'."\n".
                         '         <OPTGROUP label="класс A">'."\n".
                         '            <OPTION '.(($params_['reglament_'] == 'A1') ? 'selected' : '').' value="A1">'.$reglaments_['reglament1_'].'</OPTION>'."\n".
                         '            <OPTION '.(($params_['reglament_'] == 'A2') ? 'selected' : '').' value="A2">'.$reglaments_['reglament2_'].'</OPTION>'."\n".
                         '            <OPTION '.(($params_['reglament_'] == 'A3') ? 'selected' : '').' value="A3">'.$reglaments_['reglament3_'].'</OPTION>'."\n".
                         '            <OPTION '.(($params_['reglament_'] == 'A4') ? 'selected' : '').' value="A4">'.$reglaments_['reglament4_'].'</OPTION>'."\n".
                         '            <OPTION '.(($params_['reglament_'] == 'A5') ? 'selected' : '').' value="A5">'.$reglaments_['reglament5_'].'</OPTION>'."\n".
                         '            <OPTION '.(($params_['reglament_'] == 'A6') ? 'selected' : '').' value="A6">'.$reglaments_['reglament6_'].'</OPTION>'."\n".
                         '         </OPTGROUP>'."\n".
                         '         <OPTGROUP label="класс C">'."\n".
                         '            <OPTION '.(($params_['reglament_'] == 'C2') ? 'selected' : '').' value="C2">'.$reglaments_['reglament2_'].'</OPTION>'."\n".
                         '            <OPTION '.(($params_['reglament_'] == 'C4') ? 'selected' : '').' value="C4">'.$reglaments_['reglament4_'].'</OPTION>'."\n".
                         '            <OPTION '.(($params_['reglament_'] == 'C5') ? 'selected' : '').' value="C5">'.$reglaments_['reglament5_'].'</OPTION>'."\n".
                         '         </OPTGROUP>'."\n".
                         '      </SELECT>'."\n".
                         '    </TD>'."\n".
                         '  </TR>'."\n".
                         '  <TR>'."\n".
                         '    <TD>ваш цвет</TD>'."\n".
                         '    <TD>'."\n".
                         '      <SELECT name="color_">'."\n".
                         '         <OPTION '.(($params_['color_'] == 'white')  ? 'selected' : '').' value="white">белый</OPTION>'."\n".
                         '         <OPTION '.(($params_['color_'] == 'black')  ? 'selected' : '').' value="black">черный</OPTION>'."\n".
                         '         <OPTION '.(($params_['color_'] == 'random') ? 'selected' : '').' value="random">случайный</OPTION>'."\n".
                         '      </SELECT>'."\n".
                         '    </TD>'."\n".
                         '  <TR>'."\n".
                         '    <TD>вызов актуален (в днях)</TD>'."\n".
                         '    <TD>'."\n".
                         '       <INPUT type="text" id="do_call_" name="do_call_" value="'.$params_['do_call_'].'">'."\n".
                         '    </TD>'."\n".
                         '  </TR>'."\n".
                         '  <TR>'."\n".
                         '    <TD>партия рейтинговая</TD>'."\n".
                         '    <TD>'."\n".
                         '      <SELECT name="is_rating_">'."\n".
                         '         <OPTION '.(($params_['is_rating_'] == 'yes') ? 'selected' : '').' value="yes">да</OPTION>'."\n".
                         '         <OPTION '.(($params_['is_rating_'] == 'no')  ? 'selected' : '').' value="no">нет</OPTION>'."\n".
                         '      </SELECT>'."\n".
                         '    </TD>'."\n".
                         '  </TR>'."\n".
                         '    <TD>комментарий</TD>'."\n".
                         '    <TD>'."\n".
                         '       <INPUT type="text" id="comment_" name="comment_" maxlength="200" value="'.$params_['comment_'].'">'."\n".
                         '    </TD>'."\n".
                         '  </TR>'."\n".
                         '</TABLE>'."\n";
          $result_ .='</DIV>';
          return $result_;
       }#BodyOffLineFormCall

       protected static function FormOffLineCall($header_,$params_,$page_personal,$page_nonpersonal_A,$page_nonpersonal_C,$error_){
          CPage_::$header_ =$header_;
          CPage_::$action_form_ =CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C).'&make_call_off_line=yes';

          CPage_::$menu_[0]['link'] = CCalls_::get_link_off_line($page_personal,$page_nonpersonal_A,$page_nonpersonal_C);
          CPage_::$menu_[0]['image'] ='Image/label_esc.png';
          CPage_::$menu_[0]['submit'] =false;
          CPage_::$menu_[0]['level'] =1;
          CPage_::$menu_[0]['active'] ='N';

          CPage_::$menu_[1]['link'] = '';
          CPage_::$menu_[1]['image'] ='Image/label_send_call.png';
          CPage_::$menu_[1]['submit'] =true;
          CPage_::$menu_[1]['level'] =1;
          CPage_::$menu_[1]['active'] ='N';

          CPage_::$body_ =CCalls_::BodyOffLineFormCall($params_,$error_);
          CPage_::MakePage();
       }#FormOffLineCall

//удаляю вызовы время действия которых истекло
       public static function DelCallsEndTime(){
          $s ='delete from TCallsToGame_ where (NOW() > adddate(timeMake_,interval callEnd_ day))';
          if (!mysql_query($s,const_::$connect_))
            throw new Exception('При удалении вызовов, время которых истекло, произошла ошибка');
       }#DelCallsEndTime


#$type_: 1 - off line, 2 - on line
        public static function MakePage($type_){
            $connect_ =false;
            $transact_ =false;
            try{
                if (isset($_GET['sub_link_']) && ($_GET['sub_link_']=='mens_on_line')){
                    CCalls_::Body_mens_on_line();
                    return;
                }else if (isset($_GET['sub_link_']) && ($_GET['sub_link_']=='calls')){
                    CCalls_::Body_table_calls();
                    return;
                }

                unset($_SESSION[SESSION_LINK_ESC_DOC]);
                $link_esc_doc='';

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

                $header_ ='<DIV id="text_login_">'.
                          '  логин: '.$_SESSION[SESSION_LOGIN_].'<BR>'.
                          '  класс: A'.$classA_.'/B'.$classB_.'/C'.$classC_.
                          '  рейтинг: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'<BR>'.
                          '</DIV>'   .
                          '<DIV id="text_header_">'.
                          '  Вызовы'.
                          '</DIV>';

                if (!isset($_GET['personal']) && !isset($_GET['nonpersonal_A']) && !isset($_GET['nonpersonal_C']))
                   CCalls_::DelCallsEndTime();

                if (!isset($_GET['personal']) || !ctype_digit($_GET['personal']))
                   $personal=1;
                 else $personal =$_GET['personal'];
                if (!isset($_GET['nonpersonal_A']) || !ctype_digit($_GET['nonpersonal_A']))
                   $nonpersonal_A=1;
                 else $nonpersonal_A =$_GET['nonpersonal_A'];
                if (!isset($_GET['nonpersonal_C']) || !ctype_digit($_GET['nonpersonal_C']))
                   $nonpersonal_C=1;
                 else $nonpersonal_C =$_GET['nonpersonal_C'];

                switch ($type_){
                    case 1 :
                        if (isset($_GET['question_accept_call']) && ctype_digit($_GET['question_accept_call'])){
                            $id_call =$_GET['question_accept_call'];
                            CCalls_::outQuestionAcceptCall($id_call,$header_,$personal,$nonpersonal_A,$nonpersonal_C);
                            if ($transact_)
                              if (const_::Commit_()) $transact_ =false;
                                else throw new Exception('При завершении транзакции произошла ошибка.');
                            if ($connect_)const_::Disconnect_();
                            return;
                        }
                        if (isset($_GET['question_decline_call']) && ctype_digit($_GET['question_decline_call'])){
                            $id_call =$_GET['question_decline_call'];
                            CCalls_::outQuestionDeclineCall($id_call,$header_,$personal,$nonpersonal_A,$nonpersonal_C);
                            if ($transact_)
                              if (const_::Commit_()) $transact_ =false;
                                else throw new Exception('При завершении транзакции произошла ошибка.');
                            if ($connect_)const_::Disconnect_();
                            return;
                        }
                        if (isset($_GET['accept_call']) && ctype_digit($_GET['accept_call'])){
                            $id_call =$_GET['accept_call'];
                            CUsers_::accept_call($id_call);
                            CCalls_::outMessageAcceptCall($header_,$personal,$nonpersonal_A,$nonpersonal_C);
                            if ($transact_)
                              if (const_::Commit_()) $transact_ =false;
                                else throw new Exception('При завершении транзакции произошла ошибка.');
                            if ($connect_)const_::Disconnect_();
                            return;
                        }
                        if (isset($_GET['decline_call']) && ctype_digit($_GET['decline_call'])){
                            $id_call =$_GET['decline_call'];
                            CUsers_::decline_call($id_call);
                            CCalls_::outMessageDeclineCall($header_,$personal,$nonpersonal_A,$nonpersonal_C);
                            if ($transact_)
                              if (const_::Commit_()) $transact_ =false;
                                else throw new Exception('При завершении транзакции произошла ошибка.');
                            if ($connect_)const_::Disconnect_();
                            return;
                        }
                        if (isset($_GET['stop_call']) && ctype_digit($_GET['stop_call'])){
                            $id_call =$_GET['stop_call'];
                            CUsers_::decline_call($id_call);
                            CCalls_::outMessageDelCall($header_,$personal,$nonpersonal_A,$nonpersonal_C);
                            if ($transact_)
                              if (const_::Commit_()) $transact_ =false;
                                else throw new Exception('При завершении транзакции произошла ошибка.');
                            if ($connect_)const_::Disconnect_();
                            return;
                        }
                        if (isset($_GET['form_call_off_line'])){
                            $params_['login_'] ='';
                            $params_['reglament_'] ='A1';
                            $params_['color_'] ='random';
                            $params_['do_call_'] =7;
                            $params_['is_rating_'] ='yes';
                            $params_['comment_'] ='';
                            CCalls_::FormOffLineCall($header_,$params_,$personal,$nonpersonal_A,$nonpersonal_C,'');
                            return;
                        }
                        if (isset($_GET['make_call_off_line'])){
                            try{
#Проверяю параметры вызова
                                $err_ ='';
                                if (isset($_POST['login_']) && (trim($_POST['login_']) !='')){
                                    $login_ =$_POST['login_'];
                                    $id_to =CUsers_::Read_id_($login_);
                                    if ($id_to == 0)
                                        $err_ ='Логин в базе не найден.';
                                }else $login_ ='';
                                if (!isset($_POST['reglament_']) ||
                                    (($_POST['reglament_'] !='A1') && ($_POST['reglament_'] !='A2') &&
                                     ($_POST['reglament_'] !='A3') && ($_POST['reglament_'] !='A4') &&
                                     ($_POST['reglament_'] !='A5') && ($_POST['reglament_'] !='A6') &&
                                     ($_POST['reglament_'] !='C2') && ($_POST['reglament_'] !='C4') &&
                                     ($_POST['reglament_'] !='C5'))
                                    ){
                                        $err_ .=' Регламент указан неверно.';
                                        $reglament_ ='A1';
                                }else $reglament_ =$_POST['reglament_'];
                                if (!isset($_POST['color_']) ||
                                    (($_POST['color_'] !='white') && ($_POST['color_'] !='black') &&
                                     ($_POST['color_'] !='random'))
                                    ){
                                        $err_ .=' Цвет указан неверно.';
                                        $color_ ='white';
                                }else $color_ =$_POST['color_'];
                                if (!isset($_POST['is_rating_']) ||
                                    (($_POST['is_rating_'] !='yes') && ($_POST['is_rating_'] !='no'))
                                    ){
                                        $err_ .=' Параметр "партия рейтинговая" указан неверно.';
                                        $is_rating_ ='yes';
                                }else $is_rating_ =$_POST['is_rating_'];
                                if (!isset($_POST['do_call_']) || !ctype_digit($_POST['do_call_']) ||
                                    ($_POST['do_call_'] =='0')){
                                        $err_ .=' Параметр "вызов актуален" указан неверно.';
                                        $do_call_ =$_POST['do_call_'];
                                }else $do_call_ =$_POST['do_call_'];
                                if (isset($_POST['comment_'])) $comment_ =$_POST['comment_']; else $comment_ ='';
                                if ($err_ !='') throw new ECheckCallError($err_);
#Отправка вызова
                                if ($color_ =='white') $gamerMakeCallIsWhite ='Y';
                                  elseif ($color_ =='black') $gamerMakeCallIsWhite ='N';
                                  elseif (rand(0,1) ==0) $gamerMakeCallIsWhite ='Y';
                                  else $gamerMakeCallIsWhite ='N';
                                $s ='insert into TCallsToGame_(reglament_,id_gamerMakeCall_,id_gamer_,gamerMakeCallIsWhite_,'.
                                                              'gameIsRating_,timeMake_,callEnd_,comment_,class_)'.
                                    ' values('.$reglament_{1}.','.$_SESSION[SESSION_ID_].','.
                                               ($login_ != '' ? $id_to : 'null').','.
                                               '\''.$gamerMakeCallIsWhite.'\','.
                                               '\''.($is_rating_ == 'yes' ? 'Y' : 'N').'\','.
                                               'NOW(),'.
                                               $do_call_.','.
                                               ($comment_ !== '' ? '\''.mysql_escape_string($comment_).'\'' : 'null').','.
                                               '\''.$reglament_{0}.'\')';
                                $s =convert_cyr_string($s,'w','d');
                                if (!mysql_query($s,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0))
                                    throw new Exception('При отправке вызова произошла лшибка');
                            }catch(ECheckCallError $e){
                                $params_['login_'] =$login_;
                                $params_['reglament_'] =$reglament_;
                                $params_['color_'] =$color_;
                                $params_['do_call_'] =$do_call_;
                                $params_['is_rating_'] =$is_rating_;
                                $params_['comment_'] =$comment_;
                                CCalls_::FormOffLineCall($header_,$params_,$personal,$nonpersonal_A,$nonpersonal_C,$e->getMessage());
                                if ($transact_) const_::Rollback_();
                                if ($connect_) const_::Disconnect_();
                                return;
                            }
                        }
                        $body_ =CCalls_::BodyCallsMail($personal,$nonpersonal_A,$nonpersonal_C);
                        $link_esc_doc ='MainPage.php?link_=calls_off_line';
                        break;
                    case 2 :
                        if (isset($_GET['sub_link_']) && ($_GET['sub_link_'] == 'form_call')){
                            $params_['login_'] ='';
                            $params_['reglament_'] ='10';
                            $params_['color_'] ='random';
                            $params_['do_call_'] =5;
                            $params_['is_rating_'] ='yes';
                            $params_['comment_'] ='';
                            CCalls_::FormOnLineCall($header_,$params_,'');
                            if ($transact_) const_::Commit_();
                            if ($connect_) const_::Disconnect_();
                            return;
                        }
                        if (isset($_GET['make_call_on_line'])){
                            try{
#Проверяю параметры вызова
                                $err_ ='';
                                if (isset($_POST['login_']) && (trim($_POST['login_']) !='')){
                                    $login_ =$_POST['login_'];
                                    $id_to =CUsers_::Read_id_($login_);
                                    if ($id_to == 0)
                                        $err_ ='Логин в базе не найден.';
                                    elseif ($id_to == $_SESSION[SESSION_ID_])
                                        $err_ ='Нельзя отправить вызов самому себе.';
                                }else $login_ ='';
                                if (!isset($_POST['reglament_']) ||
                                    (($_POST['reglament_'] !='7') && ($_POST['reglament_'] !='8') &&
                                     ($_POST['reglament_'] !='9') && ($_POST['reglament_'] !='10'))
                                    ){
                                        $err_ .=' Регламент указан неверно.';
                                        $reglament_ ='10';
                                }else $reglament_ =$_POST['reglament_'];
                                if (!isset($_POST['color_']) ||
                                    (($_POST['color_'] !='white') && ($_POST['color_'] !='black') &&
                                     ($_POST['color_'] !='random'))
                                    ){
                                        $err_ .=' Цвет указан неверно.';
                                        $color_ ='white';
                                }else $color_ =$_POST['color_'];
                                if (!isset($_POST['is_rating_']) ||
                                    (($_POST['is_rating_'] !='yes') && ($_POST['is_rating_'] !='no'))
                                    ){
                                        $err_ .=' Параметр "партия рейтинговая" указан неверно.';
                                        $is_rating_ ='yes';
                                }else $is_rating_ =$_POST['is_rating_'];
                                if (!isset($_POST['do_call_']) || !ctype_digit($_POST['do_call_']) ||
                                    ($_POST['do_call_'] =='0')){
                                        $err_ .=' Параметр "вызов действует" указан неверно.';
                                        $do_call_ =$_POST['do_call_'];
                                }else $do_call_ =$_POST['do_call_'];
                                if (isset($_POST['comment_'])) $comment_ =$_POST['comment_']; else $comment_ ='';
                                if ($err_ !='') throw new ECheckCallError($err_);
#Отправка вызова
                                if ($color_ =='white') $gamerMakeCallIsWhite ='Y';
                                  elseif ($color_ =='black') $gamerMakeCallIsWhite ='N';
                                  elseif (rand(0,1) ==0) $gamerMakeCallIsWhite ='Y';
                                  else $gamerMakeCallIsWhite ='N';
                                $s ='insert into TCallsToGame_(reglament_,id_gamerMakeCall_,id_gamer_,gamerMakeCallIsWhite_,'.
                                                              'gameIsRating_,timeMake_,callEnd_,comment_,class_)'.
                                    ' values('.$reglament_.','.$_SESSION[SESSION_ID_].','.
                                               ($login_ != '' ? $id_to : 'null').','.
                                               '\''.$gamerMakeCallIsWhite.'\','.
                                               '\''.($is_rating_ == 'yes' ? 'Y' : 'N').'\','.
                                               'NOW(),'.
                                               $do_call_.','.
                                               ($comment_ !== '' ? '\''.mysql_escape_string($comment_).'\'' : 'null').','.
                                               '\'B\')';
                                $s =convert_cyr_string($s,'w','d');
                                if (!mysql_query($s,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0))
                                    throw new Exception('При отправке вызова произошла лшибка');
                            }catch(ECheckCallError $e){
                                $params_['login_'] =$login_;
                                $params_['reglament_'] =$reglament_;
                                $params_['color_'] =$color_;
                                $params_['do_call_'] =$do_call_;
                                $params_['is_rating_'] =$is_rating_;
                                $params_['comment_'] =$comment_;
                                CCalls_::FormOnLineCall($header_,$params_,$e->getMessage());
                                if ($transact_) const_::Rollback_();
                                if ($connect_) const_::Disconnect_();
                                return;
                            }
                        }
                        $body_ =CCalls_::BodyOnLine();
                        $link_esc_doc ='MainPage.php?link_=calls_on_line';
                        break;
                    default :
                        $body_ ='';
                }#switch

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
                if ($connect_)const_::Disconnect_();

                CPage_::$header_ =$header_;
                CCalls_::MakeMenuMainPage($type_);
                CPage_::$body_ =$body_;
                CPage_::MakePage();
                if ($link_esc_doc !='')
                   $_SESSION[SESSION_LINK_ESC_DOC]=$link_esc_doc;
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
                CPage_::PageErr();
            }#try
        }#MakePage
	}#CCalls_
?>
