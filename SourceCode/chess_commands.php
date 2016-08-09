<?php
    require_once('const_.php');
    require_once('Users.php');

    class CChessCommands_{
        protected static $add_file_style ='chess_commands.css';
#получает список комманд
        public static function get_commands(){
            $Result_ =array();
            $s ='select id_,name_,if(emblem_ is null,0,1) as emblem_,slogan_'.
                ' from  TChessCommands_'.
                ' order by name_';
            $cursor_ =mysql_query($s,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о командах произошла ошибка');
            while ($row_=mysql_fetch_array($cursor_)){
                $i =count($Result_);
                $Result_[$i]['id_'] =$row_['id_'];
                $Result_[$i]['emblem_'] =($row_['emblem_'] ==1);
                $Result_[$i]['name_'] =convert_cyr_string($row_['name_'],'d','w');
                $Result_[$i]['slogan_'] =convert_cyr_string(trim($row_['slogan_']),'d','w');
            }#while
            return $Result_;
        }#get_commands

#получаю эмблему
        public static function get_emblem_command($id_){
            $connect_  =false;
            $transact_ =false;
            $cursor_   =false;
            try{
#Получаю рисунок эмблемы из базы данных
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'select emblem_ from TChessCommands_ where id_='.$id_;
              $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
              if (!is_null($row_['emblem_'])) $e_ =$row_['emblem_']; else $e_ ='';
              mysql_free_result($cursor_); $cursor_ =false;
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
#Передаю рисунок
              header("Content-type: image/jpeg");
              echo($e_);
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
#Возможно в будущем в это место имеет смысл поставить код, передающий картинку "ошибка загрузки"
                throw new Exception('При получении рисунка эмблемы произошла ошибка.');
            }
        }#get_emblem_command

        public static function BodyListCommands(){
            $l_ =CChessCommands_::get_commands();

            $result_ ='<DIV class="text_header_section_">'.
                         'Список команд'.
                      '</DIV><BR><BR>'.
                      '<DIV class="list_commands">'.
                      '  <A class="new_command" href="MainPage.php?chess_command=make">создать команду</A>'.
                      '  <BR/><BR/>';
            if (count($l_) > 0){
              for($i=0; $i < count($l_); $i++){
                $result_ .='<DIV class="row_command">'.
                           '  <DIV class="embleme">'.
                           '    <A href="MainPage.php?chess_command=page_events&id='.$l_[$i]['id_'].'">'.
                           ($l_[$i]['emblem_'] ? '<IMG src="MainPage.php?emblem_command='.$l_[$i]['id_'].'"/>' : '<IMG class="no_embleme" src="Image/no_embleme.png"/>').                        
                           '    </A>'.
                           '  </DIV>'.
                           '  <DIV class="name_command">'.
                           '   <A href="MainPage.php?chess_command=page_events&id='.$l_[$i]['id_'].'">'.htmlspecialchars($l_[$i]['name_']).'</A>'.
                           '  </DIV>'.
                           '  <DIV class="slogan_command">'.htmlspecialchars($l_[$i]['slogan_']).'</DIV>'.
                           '</DIV>';
              }#for
            }
            $result_ .='</DIV>';
            return $result_;
        }#BodyListCommands

        public static function MakePage(){
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

                $body_ =CChessCommands_::BodyListCommands();

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
                if ($connect_)const_::Disconnect_();

                CPage_::$header_ ='<DIV id="text_login_">'.
                                  '  логин: '.$_SESSION[SESSION_LOGIN_].'<BR>'.
                                  '  класс: A'.$classA_.'/B'.$classB_.'/C'.$classC_.
                                  '  рейтинг: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'<BR>'.
                                  '</DIV>'   .
                                  '<DIV id="text_header_">'.
                                  '  Команды'.
                                  '</DIV>';
                CPage_::MakeMenu_(CPage_::PositionMenu_('Команды'));
                CPage_::$body_ =$body_;
                CPage_::$add_file_style =CChessCommands_::$add_file_style;
                CPage_::MakePage();
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
                CPage_::PageErr();
            }#try
        }#MakePage
    }#CChessCommands

?>
