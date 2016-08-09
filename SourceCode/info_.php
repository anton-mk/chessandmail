<?php
    require_once('const_.php');
    require_once('Users.php');
  	require_once('lib/mylib.php');

    class EGuestBookError extends Exception{}; #Ошибки при заполнении гостевой книги

    class message_info_ extends CPartOfQuery_{
		public $list_ =array();
        public $id_gamer_ =0;

        public static function get_link($page_){
            return 'MainPage.php?link_=Events&num_page='.$page_;
        }#get_link

		function __construct(){
			parent::__construct(const_::$connect_);
			$this->cRecordOnPage =7;
		} #__construct

		protected function str_select_for_countPage(){
			return 'select count(*) as count_ from TInfo_ where (id_gamer_ is null) or (id_gamer_='.$this->id_gamer_.')';
		} #str_select_for_countPage

		protected function str_select_for_getRecords(){
			return 'select id_,timeMake_,info_ from TInfo_ where (id_gamer_ is null) or (id_gamer_='.$this->id_gamer_.') order by timeMake_ desc, id_ desc';
		} #str_select_for_getRecords

		public function GetList($page_){
			try{
				if (!$this->getRecords(!const_::$isTransact_,$page_,array('id_','timeMake_','info_')))
					throw new Exception();
				for($i=0; $i<count($this->listRecords); $i++){
                                        $this->list_[$i]['id_'] = $this->listRecords[$i]['id_'];
					$this->list_[$i]['timeMake_'] = $this->listRecords[$i]['timeMake_'];
					$this->list_[$i]['info_']  = convert_cyr_string($this->listRecords[$i]['info_'],'d','w');
				} #for
			}catch(Exception $e){
				throw new Exception('При чтении информации о событиях произошла ошибка.');
			}
		}#GetList

		public function outList(){
			$m ='';
			$result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                                        'font-size: 12pt; color: black;'.
                                                        'text-decoration: none; font-weight: normal">'."\n";
			if ($this->cCountPages > 1){
				$a =$this->getFirstVisibleNum($this->page_);
				$b =$this->getLastVisibleNum($this->page_);
				if ($a > 1) $m ='<A href="'.message_info_::get_link($a-1).'">'.htmlspecialchars('<<').'</A>';
				for ($i=$a; $i <=$b; $i++){
					if ($m != '') $m .='&nbsp;';
					if ($this->page_ != $i) $m .='<A href="'.message_info_::get_link($i).'">'.$i.'</A>'; else $m.=$i;
				}
				if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.message_info_::get_link($b+1).'">'.htmlspecialchars('>>').'</A>';
				$result_ .='<DIV style="text-align: right">'.$m.'</DIV>'."\n";
			}
                        $id_last_read_info_ =null;
			for($i=0; $i < count($this->list_); $i++){
				$result_ .='<DIV style="text-align: left">'.htmlspecialchars($this->list_[$i]['timeMake_']).'</DIV>'."\n";
				$result_ .='<DIV style="text-align: left; padding-left: 30px">'.($this->list_[$i]['info_']).'</DIV><BR/>'."\n";
                                if (is_null($id_last_read_info_) || ($id_last_read_info_ < $this->list_[$i]['id_']))
                                    $id_last_read_info_ =$this->list_[$i]['id_'];
			} #for
                       $result_ .='</SPAN>'."\n";
                        if (!is_null($id_last_read_info_))
                            CUsers_::set_id_last_read_info(null, $id_last_read_info_);
                       return $result_;
		}#outList

		public function exists_records(){
			return (count($this->list_) > 0);
		}#exists_records()
    } #message_info_

	class message_GuestBook extends CPartOfQuery_{
		public $list_;

        public static function get_link($page_){
            return 'MainPage.php?link_=GuestBook&num_page='.$page_;
        }#get_link

		protected function str_select_for_countPage(){
			return 'select count(*) as count_ from TGuestBook_';
		} #str_select_for_countPage

		protected function str_select_for_getRecords(){
			return 'select id_,timeMake_,message_ from TGuestBook_ order by id_ desc';
		} #str_select_for_getRecords

		public function GetList($page_){
			$this->list_ =array();
			try{
				if (!$this->getRecords(!const_::$isTransact_,$page_,array('id_','timeMake_','message_')))
					throw new Exception();
				for($i=0; $i<count($this->listRecords); $i++){
                                        $this->list_[$i]['id_'] = $this->listRecords[$i]['id_'];
					$this->list_[$i]['timeMake_'] = $this->listRecords[$i]['timeMake_'];
					$this->list_[$i]['message_']  = convert_cyr_string($this->listRecords[$i]['message_'],'d','w');
				} #for
			}catch(Exception $e){
				throw new Exception('При чтении информации из гостевой книги произошла ошибка.');
			}
		}#GetList

		public function outList(){
			$m ='';
			$result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif; font-size: 12pt; font-style: normal; font-weight: normal; color : black">';
			if ($this->cCountPages > 1){
				$a =$this->getFirstVisibleNum($this->page_);
				$b =$this->getLastVisibleNum($this->page_);
				if ($a > 1) $m ='<A href="MainPage.php?link_=GuestBook&num_page='.($a-1).'">'.htmlspecialchars('<<').'</A>';
				for ($i=$a; $i <=$b; $i++){
					if ($m != '') $m .='&nbsp;';
					if ($this->page_ != $i) $m .='<A href="MainPage.php?link_=GuestBook&num_page='.$i.'">'.$i.'</A>'; else $m.=$i;
				}
				if ($b < $this->cCountPages) $m .='&nbsp;<A href="MainPage.php?link_=GuestBook&num_page='.($b+1).'">'.htmlspecialchars('>>').'</A>';
				$result_ .='<DIV style="text-align: right">'.$m.'</DIV><BR/>'."\n";
			}
                        $id_last_read_guest_book =null;
			for($i=0; $i < count($this->list_); $i++){
				$result_ .='<DIV style="text-align: left">'.htmlspecialchars($this->list_[$i]['timeMake_']).'</DIV>'."\n";
				$result_ .='<DIV style="text-align: left; padding-left: 30px">'.htmlspecialchars($this->list_[$i]['message_']).'</DIV><BR/>'."\n";
                                if (is_null($id_last_read_guest_book) || ($id_last_read_guest_book < $this->list_[$i]['id_']))
                                    $id_last_read_guest_book =$this->list_[$i]['id_'];
			} #for
			if ($m != '') $result_ .='<DIV style="text-align: right">'.$m.'</DIV><BR/>'."\n";
			$result_ .='</SPAN>';
                        if (!is_null($id_last_read_guest_book))
                            CUsers_::set_id_last_read_guest_book(null, $id_last_read_guest_book);
			return $result_;
		}#outList
	} #message_GuestBook

    class CInfoPage_{
        protected static function outQuestionAcceptCall($id_,$header_,$page_){
            CPage_::$header_ =$header_;
            CPage_::QuestionPage('Подтвердите Ваше решение принять вызов.',
                                  message_info_::get_link($page_),
                                  message_info_::get_link($page_).'&accept_call='.$id_);
        }#outQuestionAcceptCall

        protected static function outMessageAcceptCall($header_,$page_){
            CPage_::$header_ =$header_;
            CPage_::MessagePage('Вызов принят.',
                                message_info_::get_link($page_));
        }#outMessageAcceptCall

        protected static function outQuestionDeclineCall($id_,$header_,$page_){
            CPage_::$header_ =$header_;
            CPage_::QuestionPage('Подтвердите Ваше решение отклонить вызов.',
                                  message_info_::get_link($page_),
                                  message_info_::get_link($page_).'&decline_call='.$id_);
        }#outQuestionDeclineCall

        protected static function outMessageDeclineCall($header_,$page_){
            CPage_::$header_ =$header_;
            CPage_::MessagePage('Вызов отклонен.',
                                message_info_::get_link($page_));
        }#outMessageDeclineCall

	protected static function writeToGuestBook($message_){
            $connect_  =false;
            $transact_ =false;
            try{
                  if (!const_::$connect_)
                      if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                  if (!const_::$isTransact_)
                      if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                  $s_ ='insert into TGuestBook_(message_) values(\''.mysql_escape_string($message_).'\')';
                  $s_ =convert_cyr_string($s_,'w','d');
                  if (!mysql_query($s_,const_::$connect_)) throw new Exception();

                  if ($transact_)
                      if (const_::Commit_()) $transact_ =false; else throw new Exception();
                  if ($connect_)const_::Disconnect_();
             }catch (Exception $e){
                  if ($transact_) const_::Rollback_();
                  if ($connect_) const_::Disconnect_();
                        throw new Exception('При сохранении сообщения в гостевой книге произошла ошибка.');
             }
        }//WriteComment

#$type_ - тоже, что и в CInfoPage_::MakePage()
        public static function MakeMenuMainPage($type_){
            $i =CPage_::PositionMenu_('Информация') +1;

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=Events&num_page=1';
            CPage_::$menu_[$i]['image'] ='Image/label_events.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==1) ? 'Y' : 'N';

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=Contacts';
            CPage_::$menu_[$i]['image'] ='Image/label_personal_contacts.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==2) ? 'Y' : 'N';

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=GuestBook';
            CPage_::$menu_[$i]['image'] ='Image/label_guestbook.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==3) ? 'Y' : 'N';

            CPage_::MakeMenu_(CPage_::PositionMenu_('Информация'));
        }#MakeMenuMainPage

	protected static function GetCalls_personal(){
	    global $reglaments_;
	    $result_ =array();
	    $s ='select A.id_,A.reglament_,A.gameIsRating_,A.timeMake_,adddate(A.timeMake_,interval A.callEnd_ day) as timeEnd_,B.login_,'.
	           '       A.gamerMakeCallIsWhite_,A.comment_,A.class_'.
		   ' from TCallsToGame_ A, TGamers_ B'.
		   ' where (A.id_gamerMakeCall_ = B.id_) and (A.id_gamer_ = '.$_SESSION[SESSION_ID_].')';

	    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении информации о вызовах произошла ошибка');
	    while ($row_ =mysql_fetch_array($cursor_)){
                $result_[$row_['id_']]['reglament_']       =$reglaments_['reglament'.$row_['reglament_'].'_'];
                $result_[$row_['id_']]['gameIsRating_'] =$row_['gameIsRating_'] == 'Y' ? 'да' : 'нет';
                $result_[$row_['id_']]['timeMake_']       =$row_['timeMake_'];
                $result_[$row_['id_']]['timeEnd_']        =$row_['timeEnd_'];
                $result_[$row_['id_']]['login_']              = convert_cyr_string($row_['login_'],'d','w');
                $result_[$row_['id_']]['gamerMakeCallIsWhite_'] =$row_['gamerMakeCallIsWhite_'] == 'Y' ? 'чёрный' : 'белый';
                $result_[$row_['id_']]['comment_']        = convert_cyr_string($row_['comment_'],'d','w');
                $result_[$row_['id_']]['class_']              = $row_['class_'];
            }#while
			return $result_;
        }#GetCalls_personal

      public static function BodyCalls_personal($page_){
          $result_='';
          $l =CInfoPage_::GetCalls_personal();
          if (count($l) > 0){
              $result_ ='<BR>'.
                                '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                        'font-size: 16pt; color: white; text-align: center;'.
                                                        'text-decoration: none; font-weight: normal">'.
                                 'Приглашения сыграть'.
                                '</DIV><BR>'."\n".
              			        '<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                                        'font-size: 12pt; color: black;'.
                                                        'text-decoration: none; font-weight: normal">'."\n".
			    	            '<TABLE style="border: none; margin-left: auto; margin-right: auto" cellspacing="3">'.
                                '	<COL span="11">'.
                                '		<TR><TD class="table_head_1">№</TD>'.
                                '			<TD class="table_head_1">&nbsp;</TD>'.
                                '			<TD class="table_head_1">&nbsp;</TD>'.
                                '			<TD class="table_head_1">От кого</TD>'.
                                '			<TD class="table_head_1">Класс</TD>'.
                                '			<TD class="table_head_1">Регламент</TD>'.
                                '			<TD class="table_head_1">Ваш цвет</TD>'.
                                '			<TD class="table_head_1">Рейтинговая</TD>'.
                                '			<TD class="table_head_1">Время отправки вызова</TD>'.
                                '			<TD class="table_head_1">Время окончания вызова</TD>'.
                                '			<TD class="table_head_1">Комментарий</TD>'.
			    	            '		</TR>';
               foreach($l as $key_=>$value_){
                  $result_.='<TR><TD class="table_body_1">'.$key_.'</TD>'.
                            '    <TD class="table_body_1">'.
                            '          <A href="'.message_info_::get_link($page_).'&question_accept_call='.$key_.'">принять</A>'.
                            '    </TD>'.
                            '    <TD class="table_body_1">'.
                            '          <A href="'.message_info_::get_link($page_).'&question_decline_call='.$key_.'">отклонить</A>'.
                            '    </TD>'.
                            '    <TD class="table_body_1">'.
                            '          <A href="MainPage.php?link_=about_gamer&login_='.urlencode($value_['login_']).'">'.htmlspecialchars($value_['login_']).'</A>'.
                            '    </TD>'.
                            '    <TD class="table_body_1">'.$value_['class_'].'</TD>'.
                            '    <TD class="table_body_1">'.$value_['reglament_'].'</TD>'.
                            '    <TD class="table_body_1">'.$value_['gamerMakeCallIsWhite_'].'</TD>'.
                            '    <TD class="table_body_1">'.$value_['gameIsRating_'].'</TD>'.
                            '    <TD class="table_body_1">'.$value_['timeMake_'].'</TD>'.
                            '    <TD class="table_body_1">'.$value_['timeEnd_'].'</TD>'.
                            '    <TD class="table_body_1">'.htmlspecialchars($value_['comment_']).'</TD>'.
                            '</TR>';
               }#foreach
               $result_.='</TABLE>';
               $result_.='</SPAN>';
          }
          return $result_;
      }#BodyCalls_personal

        protected static function GetGames_my_move(){
			global $reglaments_;

			$curr_time =time();
			$result_ = array();
			$s ='select A.id_,B.login_ as wlogin_,C.login_ as blogin_,A.reglament_,'.
				    '           A.clockWhite_,A.clockBlack_,A.beginMove_,A.isMoveWhite_,A.gameIsRating_,D.id_tournament_,'.
				    '           E.class_,E.system_'.
				    ' from TGames_ A left join TGamesTournament_ D on (A.id_=D.id_game)'.
				    '                left join TTournaments_ E on (not D.id_tournament_ is null) and (D.id_tournament_ = E.id_),'.
				    '      TGamers_ B, TGamers_ C'.
				    ' where (A.result_ is null) and (A.idWGamer_ = B.id_) and (A.idBGamer_ = C.id_) and'.
				    '            (((B.id_ ='.$_SESSION[SESSION_ID_].') and (A.isMoveWhite_ =\'Y\')) or'.
				    '             ((C.id_ ='.$_SESSION[SESSION_ID_].') and (A.isMoveWhite_ =\'N\')))'.
				    ' order by A.id_';

			$cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении информации об партиях произошла ошибка');
			while ($row_ =mysql_fetch_array($cursor_)){
				$result_[$row_['id_']]['wlogin_']   = convert_cyr_string($row_['wlogin_'],'d','w');
				$result_[$row_['id_']]['blogin_']    = convert_cyr_string($row_['blogin_'],'d','w');
				$result_[$row_['id_']]['reglament_'] = $reglaments_['reglament'.$row_['reglament_'].'_'];
				if ($row_['isMoveWhite_'] == 'Y'){
					$result_[$row_['id_']]['clockBlack_'] = clockToStr($row_['clockBlack_']);
					$i_ =$row_['clockWhite_']; if ($row_['beginMove_'] !=0) $i_ -=($curr_time - $row_['beginMove_']);
					$result_[$row_['id_']]['clockWhite_'] = clockToStr($i_ > 0 ? $i_ : 0);
				}else{
					$i_ =$row_['clockBlack_']; if ($row_['beginMove_'] !=0) $i_ -=($curr_time - $row_['beginMove_']);
					$result_[$row_['id_']]['clockBlack_'] = clockToStr($i_ > 0 ? $i_ : 0);
					$result_[$row_['id_']]['clockWhite_'] = clockToStr($row_['clockWhite_']);
				}
				if ($row_['gameIsRating_'] == 'Y') $result_[$row_['id_']]['gameIsRating_'] = 'да';
					else $result_[$row_['id_']]['gameIsRating_'] = 'нет';
				if (!is_null($row_['id_tournament_']))
                        $result_[$row_['id_']]['id_tournament_'] =$row_['id_tournament_'];
                    else
                        $result_[$row_['id_']]['id_tournament_'] ='';
                $result_[$row_['id_']]['class_'] =$row_['class_'];
                $result_[$row_['id_']]['system_'] =$row_['system_'];
            }#while
			mysql_free_result($cursor_);

			return $result_;
		}#GetGames_my_move


      public static function BodyGames_my_move(){
          $result_='';
          $l =CInfoPage_::GetGames_my_move();
          if (count($l) > 0){
              $result_ ='<BR>'.
                                '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                        'font-size: 16pt; color: white; text-align: center;'.
                                                        'text-decoration: none; font-weight: normal">'.
                                 'Партии, ожидающие Ваш ход'.
                                '</DIV><BR>'."\n".
              			        '<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                                        'font-size: 12pt; color: black;'.
                                                        'text-decoration: none; font-weight: normal">'."\n".
			    	            '<TABLE style="border: none; margin-left: auto; margin-right: auto" cellspacing="3">'.
                                '	<COL span="7">'.
                                '		<TR><TD class="table_head_1">№</TD>'.
                                '			      <TD class="table_head_1">Кто с кем играет</TD>'.
                                '			<TD class="table_head_1">Турнир</TD>'.
			                    '			<TD class="table_head_1">Регламент</TD>'.
                                '			<TD class="table_head_1">Рейтинговая</TD>'.
			                    '			<TD class="table_head_1">Время на часах белых</TD>'.
				                '			<TD class="table_head_1">Время на часах чёрных</TD>'.
			    	            '		</TR>';
			   foreach($l as $key_=>$value_){
                  $result_.='<TR><TD class="table_body_1"><A href="MainPage.php?link_=game&id='.$key_.'">'.$key_.'</A></TD>'.
			    		            '          <TD class="table_body_1"><A href="MainPage.php?link_=about_gamer&login_='.urlencode($value_['wlogin_']).'">'.htmlspecialchars($value_['wlogin_']).'</A> - '.
			    					'                                   <A href="MainPage.php?link_=about_gamer&login_='.urlencode($value_['blogin_']).'">'.htmlspecialchars($value_['blogin_']).'</A>'.
			    			        '          </TD>';
                  if ($value_['id_tournament_'] != '')
                            if (is_null($value_['system_']))
                                 $result_ .='<TD class="table_body_1"><A href="MainPage.php?link_=Tournament&id_='.$value_['id_tournament_'].'">'.$value_['id_tournament_'].'</A> класс '.$value_['class_'].'</TD>';
                             else
                                 $result_ .='<TD class="table_body_1"><A href="MainPage.php?link_=swiss_Tournament&id_='.$value_['id_tournament_'].'">'.$value_['id_tournament_'].'</A> класс '.$value_['class_'].'</TD>';
			      		else
			      			$result_ .='<TD class="table_body_1">&nbsp;</TD>';
			      $result_ .='     <TD class="table_body_1"><A href="doc_.php?link_=reglaments_">'.$value_['reglament_'].'</A></TD>'.
			      		             '     <TD class="table_body_1">'.$value_['gameIsRating_'].'</TD>'.
						             '	   <TD class="table_body_1">'.$value_['clockWhite_'].'</TD>'.
						             '	   <TD class="table_body_1">'.$value_['clockBlack_'].'</TD>'.
						            '</TR>';
			   }#foreach
			   $result_.='</TABLE>';
			   $result_.='</SPAN>';
          }
          return $result_;
      }#BodyGames_my_move

      public static function BodyEvent($page){
 			$e =new message_info_(const_::$connect_);
            $e->id_gamer_ =$_SESSION[SESSION_ID_];
            $e->GetList($page);
            $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                        'font-size: 16pt; color: white; text-align: center;'.
                                                        'text-decoration: none; font-weight: normal">'.
                                 'События'.
                             '</DIV>';
            $result_ .=$e->outList();
            return $result_;
      }#BodyEvent

      public static function BodyGuestBook($page_,$error_,$message_){
			$m =new message_GuestBook(const_::$connect_);
			$m->GetList($page_);
            $dhtml_ =CUsers_::Read_dhtml_();

            $result_ = '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                   'font-size: 16pt; color: white; text-align: center;'.
                                   'text-decoration: none; font-weight: normal">'.
                         'Отзывы и предложения'.
                       '</DIV><BR>';
            $result_ .='<DIV style="font-family: Liberation Serif, Times, sans-serif; font-size: 12pt; font-style: normal; font-weight: normal; color : black">'."\n".
                       '  <FORM name="form_" action ="MainPage.php?link_=post_GuestBook" method="post">'."\n".
                       '     <TABLE style ="border: none; margin-left: auto; margin-right: auto" cellspacing="3" cellpadding="0">'."\n".
                       '       <COL span="1">'."\n";
            if ($dhtml_)
              $result_ .='     <TR><TD style="text-align:left">'."\n".
                                  'Максимальная длина сообщения 400 символов, осталось&nbsp;'."\n".
                                  '<INPUT type="text" name="chars_" size="4" value="'.(400-strlen($message_)).'" readonly>'."\n".
                                  '</TD>'."\n".
                              '</TR>'."\n";
            $result_ .='       <TR><TD style="text-align:left">'."\n".
                       '              <TEXTAREA rows="5" cols="80" name="message_" '.($dhtml_ ? 'onkeyup="CheckLengthMessage()"' : '').'>'.$message_.'</TEXTAREA>'."\n".
                       '           </TD>'."\n".
                       '       </TR>'."\n";
            if ($dhtml_)
                $result_ .='   <TR><TD style="text-align:left">'."\n".
                                  '  <INPUT type="button" value="подпись" onclick="SetSignature()">'."\n".
                              '</TD></TR>'."\n";
            $result_ .='       <TR><TD style="text-align:right">'."\n".
                       '             <TABLE style="margin-left:auto; margin-right:0; border: none" cellspacing="0" cellpadding="0" > <COL span="3">'."\n".
                                      '<TR>'."\n".
                                        '<TD style="padding:0px 5px 0px 0px;white-space:nowrap">Введите, пожалуйста, изображённый код</TD>'."\n".
                                        '<TD style="padding:0px 5px 0px 0px"><IMG src="GetCode.php"></TD>'."\n".
                                        '<TD style="padding:0px 0px 0px 0px"><INPUT type="text" name="code_" size="4"></TD>'."\n".
                                      '</TR>'."\n".
                                    '</TABLE>'."\n".
                                  '</TD>'."\n".
                              '</TR>'."\n".
                              '<TR><TD style="text-align:right">'."\n".
                                    '<INPUT type="submit" name="say" value="Отправить">'."\n".
                                  '</TD>'."\n".
                              '</TR>'."\n".
                       '     </TABLE>'."\n".
                       '  </FORM>'."\n".
                       '</DIV>'."\n";
            if ($error_ != '')
              $result_ .= '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                   'font-size: 12pt; color: white; text-align: center;'.
                                   'text-decoration: none; font-weight: normal">'.
                           $error_.
                         '</DIV>';
#            $result_ .='<HR/>';
            if ($dhtml_)
                $result_ .='<SCRIPT type="text/javascript">'."\n".
                           '   function CheckLengthMessage(){'."\n".
                           '     if (document.form_.message_.value.length > 400)'."\n".
                           '        document.form_.message_.value =document.form_.message_.value.substring(0,400)'."\n".
                           '     document.form_.chars_.value = 400 - document.form_.message_.value.length'."\n".
                           '   }'."\n".
                           '   function SetSignature(){'."\n".
                           '     document.form_.message_.value =document.form_.message_.value + " '.htmlspecialchars($_SESSION[SESSION_LOGIN_]).'."'."\n".
                           '     if (document.form_.message_.value.length > 400)'."\n".
                           '       document.form_.message_.value =document.form_.message_.value.substring(0,400)'."\n".
                           '     document.form_.chars_.value = 400 - document.form_.message_.value.length'."\n".
                           '   }'."\n".
                           '</SCRIPT>'."\n";
            $result_ .=$m->outList();
            return $result_;
      }#BodyGuestBook

      public static function BodyChat(){
          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                   'font-size: 16pt; color: white; text-align: center;'.
                                                   'text-decoration: none; font-weight: normal">'.
                                 'Общение'.
                             '</DIV>';
          if (!CUsers_::Read_dhtml_()){
              $result_ .='<BR>'.
                                '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                   'font-size: 12pt; color: black; text-align: center;'.
                                                   'text-decoration: none; font-weight: normal">'.
                                    'Для отображения этой страницы нужно установить флаг DHTML в разделе настройки.'.
                                 '</DIV>';

          }else{
              $result_ .='<SCRIPT type="text/javascript" src="scripts/json2.js"></SCRIPT>'."\n".
                                 '<SCRIPT type="text/javascript" src="scripts/hints_.js"></SCRIPT>'."\n".
                                 '<SCRIPT type="text/javascript" src="scripts/chat.js"></SCRIPT>'."\n".
                                 '<SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n".
                                 '<SCRIPT type="text/javascript">'."\n".
                                 '  window.onload =function(){chat_create_elements();}'."\n".
                                 '</SCRIPT>'."\n".
                                 '<BR><SPAN id="chat"></SPAN>'."\n";
          }
          return $result_;
      }#BodyChat

#$type_: 1 - события, 2 - общение, 3 - гостевая книга
      public static function MakePage($type_){
            unset($_SESSION[SESSION_LINK_ESC_INFO_SWISS_TOURNAMENT]);
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

                $header_ ='<DIV id="text_login_">'.
                          '  логин: '.$_SESSION[SESSION_LOGIN_].'<BR>'.
                          '  класс: A'.$classA_.'/B'.$classB_.'/C'.$classC_.
                          '  рейтинг: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'<BR>'.
                          '</DIV>'.
                          '<DIV id="text_header_">'.
                          '  Информация'.
                          '</DIV>';
                switch ($type_){
                    case 1 :
                        if (!isset($_GET['num_page']) || !ctype_digit($_GET['num_page']))
                                $p=1;
                            else $p =$_GET['num_page'];
                        if (isset($_GET['question_accept_call']) && ctype_digit($_GET['question_accept_call'])){
                            $id_call =$_GET['question_accept_call'];
                            CInfoPage_::outQuestionAcceptCall($id_call,$header_,$p);
                            if ($transact_)
                              if (const_::Commit_()) $transact_ =false;
                                else throw new Exception('При завершении транзакции произошла ошибка.');
                            if ($connect_)const_::Disconnect_();
                            return;
                        }
                        if (isset($_GET['question_decline_call']) && ctype_digit($_GET['question_decline_call'])){
                            $id_call =$_GET['question_decline_call'];
                            CInfoPage_::outQuestionDeclineCall($id_call,$header_,$p);
                            if ($transact_)
                              if (const_::Commit_()) $transact_ =false;
                                else throw new Exception('При завершении транзакции произошла ошибка.');
                            if ($connect_)const_::Disconnect_();
                            return;
                        }
                        if (isset($_GET['accept_call']) && ctype_digit($_GET['accept_call'])){
                            $id_call =$_GET['accept_call'];
                            CUsers_::accept_call($id_call);
                            CInfoPage_::outMessageAcceptCall($header_,$p);
                            if ($transact_)
                              if (const_::Commit_()) $transact_ =false;
                                else throw new Exception('При завершении транзакции произошла ошибка.');
                            if ($connect_)const_::Disconnect_();
                            return;
                        }
                        if (isset($_GET['decline_call']) && ctype_digit($_GET['decline_call'])){
                            $id_call =$_GET['decline_call'];
                            CUsers_::decline_call($id_call);
                            CInfoPage_::outMessageDeclineCall($header_,$p);
                            if ($transact_)
                              if (const_::Commit_()) $transact_ =false;
                                else throw new Exception('При завершении транзакции произошла ошибка.');
                            if ($connect_)const_::Disconnect_();
                            return;
                        }
                        $body_ =CInfoPage_::BodyEvent($p).CInfoPage_::BodyGames_my_move().CInfoPage_::BodyCalls_personal($p);
                        $link_esc_info_tournament=message_info_::get_link($p);
                        $link_esc_info_swiss_tournament=message_info_::get_link($p);
                        $link_esc_game=message_info_::get_link($p);
                        $link_esc_about_gamer =message_info_::get_link($p);
                        $link_esc_doc=message_info_::get_link($p);
                        break;
                    case 2:
                        $body_ =CInfoPage_::BodyChat();
                        $link_esc_doc='MainPage.php?link_=Contacts';
                        break;
                    case 3:
                        $err_ ='';
                        $p=1;
                        $m ='';
                        if (isset($_POST['message_'])) $m =trim($_POST['message_']);
                        try{
                           if ($_GET['link_']=='post_GuestBook'){
                             if ($m =='') throw new EGuestBookError('Текст сообщение не указан.');
                             if (strlen($m) > 400) $m =substr($m,0,400);
                             if (!isset($_POST['code_']) || !isset($_SESSION[SESSION_ANTI_SPAM]) || ($_POST['code_'] !=$_SESSION[SESSION_ANTI_SPAM]))
                               throw new EGuestBookError('Код введён неверно.');
                             CInfoPage_::writeToGuestBook($m);
                             $m='';
                          }else
                             if (isset($_GET['num_page']) && ctype_digit($_GET['num_page'])) $p =$_GET['num_page'];
                        }catch(Exception $e){
                           if ($e instanceof EGuestBookError)
                              $err_ =$e->getMessage();
                            else
                              throw new Exception($e->getMessage());
                        }
                        $body_ =CInfoPage_::BodyGuestBook($p,$err_,$m);
                        $link_esc_doc=message_GuestBook::get_link($p);
                        break;
                    default : $body_ ='';
                }#switch

				if ($transact_)
					if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
				if ($connect_)const_::Disconnect_();

                CPage_::$header_ =$header_;
                CInfoPage_::MakeMenuMainPage($type_);
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
    }#CInfoPage


?>
