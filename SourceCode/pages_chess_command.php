<?php
    require_once('const_.php');
    require_once('Users.php');
    require_once('lib/mylib.php');

    class CPagesChessCommand_{
      protected static $link_post='';
      protected static $link_cancel ='';
      protected static $header_section ='';
      protected static $link_embleme ='Image/no_embleme.png';
        
      protected static $command_name='';
        
      public static function check_id($id_){
        $result_ =false;  
          
        $connect_  =false;
        $transact_ =false;
        $cursor_   =false;
        try{
          if (!const_::$connect_)
            if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
          if (!const_::$isTransact_)
            if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

          $s ='select name_ from TChessCommands_ where id_='.$id_;
          $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
          $row_ =mysql_fetch_array($cursor_);
          if ($row_){
            CPagesChessCommand_::$command_name =convert_cyr_string(trim($row_['name_']),'d','w');
            $result_ =true;
          }    

          if ($transact_)
            if (const_::Commit_()) $transact_ =false; else throw new Exception();
          if ($connect_)const_::Disconnect_();
          return $result_;
        }catch (Exception $e){
          if ($cursor_) mysql_free_result($cursor_);
          if ($transact_) const_::Rollback_();
          if ($connect_) const_::Disconnect_();
                throw new Exception('При запуске часов произошла ошибка.');
            }
            
        }#check_id

        protected static function menu_edit_info(){
            $i=0;
            if (CPagesChessCommand_::$link_post != ''){
              CPage_::$action_form_ =CPagesChessCommand_::$link_post;
              CPage_::$menu_[$i]['link'] ='';
              CPage_::$menu_[$i]['image'] ='Image/label_post.png';
              CPage_::$menu_[$i]['submit'] =true;
              CPage_::$menu_[$i]['level'] =1;
              CPage_::$menu_[$i]['name'] ='button_submit_';
              CPage_::$menu_[$i++]['active'] ='N';
            };
            CPage_::$menu_[$i]['link'] =CPagesChessCommand_::$link_cancel;
            CPage_::$menu_[$i]['image'] ='Image/label_cancel.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =1;
            CPage_::$menu_[$i++]['active'] ='N';
        }#menu_edit_info
        
        protected static function menu_info_command_add(){
            CPage_::$menu_[0]['link'] ='';
            CPage_::$menu_[0]['image'] ='Image/label_continue.png';
            CPage_::$menu_[0]['submit'] =false;
            CPage_::$menu_[0]['level'] =1;
            CPage_::$menu_[0]['active'] ='N';
        }#menu_info_command_add
        
#active_ - активная страница (1 -события, 2 -гостевая, 3 - форум, 4 - состав, 
#                             5 - командные турниры, 6 - внутри командные турниры,
#                             7 - вызовы
        protected static function menu_page($active_){
           $i =0; 
#Информация
           CPage_::$menu_[$i]['link'] ='';
           CPage_::$menu_[$i]['image'] ='Image/label_info.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =1;
           CPage_::$menu_[$i++]['active'] =(($active_ ==1) || ($active_ ==2) || ($active_ ==3)) ? 'Y' : 'N';
           if (CPage_::$menu_[$i-1]['active'] =='Y'){
#События               
             CPage_::$menu_[$i]['link'] = '';
             CPage_::$menu_[$i]['image'] ='Image/label_events.png';
             CPage_::$menu_[$i]['submit'] =false;
             CPage_::$menu_[$i]['level'] =2;
             CPage_::$menu_[$i]['lock'] =true;             
#             CPage_::$menu_[$i++]['active'] =($active_==1) ? 'Y' : 'N';
             CPage_::$menu_[$i++]['active'] ='N';
#Гостевая             
             CPage_::$menu_[$i]['link'] = '';
             CPage_::$menu_[$i]['image'] ='Image/label_guest_book.png';
             CPage_::$menu_[$i]['submit'] =false;
             CPage_::$menu_[$i]['level'] =2;
             CPage_::$menu_[$i]['lock'] =true;             
             CPage_::$menu_[$i++]['active'] =($active_==2) ? 'Y' : 'N';
#Форум             
             CPage_::$menu_[$i]['link'] = '';
             CPage_::$menu_[$i]['image'] ='Image/label_forum.png';
             CPage_::$menu_[$i]['submit'] =false;
             CPage_::$menu_[$i]['level'] =2;
             CPage_::$menu_[$i]['lock'] =true;
             CPage_::$menu_[$i++]['active'] =($active_==3) ? 'Y' : 'N';
           }
#Состав           
           CPage_::$menu_[$i]['link'] ='';
           CPage_::$menu_[$i]['image'] ='Image/label_team.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =1;
           CPage_::$menu_[$i]['lock'] =true;
           CPage_::$menu_[$i++]['active'] =($active_ ==4) ? 'Y' : 'N';
#Турниры
           CPage_::$menu_[$i]['link'] ='';
           CPage_::$menu_[$i]['image'] ='Image/label_tournaments.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =1;
           CPage_::$menu_[$i]['lock'] =true;
           CPage_::$menu_[$i++]['active'] =(($active_ ==5) || ($active_ ==6)) ? 'Y' : 'N';
           if (CPage_::$menu_[$i-1]['active'] =='Y'){
#Командные
             CPage_::$menu_[$i]['link'] = '';
             CPage_::$menu_[$i]['image'] ='Image/label_commands_tournaments.png';
             CPage_::$menu_[$i]['submit'] =false;
             CPage_::$menu_[$i]['level'] =2;
             CPage_::$menu_[$i++]['active'] =($active_==5) ? 'Y' : 'N';
#Внутренние
             CPage_::$menu_[$i]['link'] = '';
             CPage_::$menu_[$i]['image'] ='Image/label_inner_tournaments.png';
             CPage_::$menu_[$i]['submit'] =false;
             CPage_::$menu_[$i]['level'] =2;
             CPage_::$menu_[$i++]['active'] =($active_==6) ? 'Y' : 'N';
           }
#Вызовы
           CPage_::$menu_[$i]['link'] ='';
           CPage_::$menu_[$i]['image'] ='Image/label_calls.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =1;
           CPage_::$menu_[$i]['lock'] =true;
           CPage_::$menu_[$i++]['active'] =($active_ ==7) ? 'Y' : 'N';
#Начало
           CPage_::$menu_[$i]['link'] ='MainPage.php?link_=Events';
           CPage_::$menu_[$i]['image'] ='Image/label_begin.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =1;
           CPage_::$menu_[$i++]['active'] =($active_ ==7) ? 'Y' : 'N';
           
        }#menu_page

        protected static function BodyEditInfo($params_=array(),$err_=''){
            $dhtml_ =CUsers_::Read_dhtml_();
            $result_ = '<DIV class="text_header_section_">'.
                          CPagesChessCommand_::$header_section.
                       '</DIV><BR/><BR/>'.
                       '<DIV style="font-size:12pt; color:black">';
            if($err_ !='')
                $result_ .='<DIV style="color:white; margin-bottom:10px">'.$err_.'</DIV>';
            $result_ .='  <DIV style="margin-bottom:10px">'.
                       '     название<BR/>'.
                       '     <INPUT type="text" id="name_" name="name_" style="width:100%" value="'.(isset($params_['name_']) ? htmlspecialchars($params_['name_']) : '').'" maxlength="50">'.
                       '  </DIV>'.
                       '  <DIV style="margin-bottom:10px">'.
                       '     <DIV style="white-space:nowrap">'.
                       '       девиз';
                       if ($dhtml_)
                          $result_ .='&nbsp;максимальная длина 400 символов, осталось&nbsp;'.
                                     '<INPUT type="text" name="chars_" id ="chars_" size="4" value="'.(400-(isset($params_['slogan_']) ? strlen($params_['slogan_']) : 0)).'" readonly>';
            $result_ .='     </DIV>'.
                       '     <TEXTAREA rows="5" name="slogan_" id ="slogan_" style="width:100%"'.($dhtml_ ? ' onkeyup="CheckLengthMessage()"' : ''). '>'.
                                (isset($params_['slogan_']) ? htmlspecialchars($params_['slogan_']) : '').
                            '</TEXTAREA>'.
                       '  </DIV>'.
                       '  <DIV style="margin-bottom:10px">'.
                       '     эмблема<BR/>'.
                       '     <IMG src="'.CPagesChessCommand_::$link_embleme.'" style="border: 1px solid #fbffff; padding:4px"/><BR/>'.
                       '     <LABEL for="fileEmbleme_">Файл</LABEL>'.
                       '     <INPUT type="file" id="fileEmbleme_" name="fileEmbleme_" value="'.(isset($params_['fileEmbleme_']) ? htmlspecialchars($params_['fileEmbleme_']) : '').'">'.
                       '  </DIV>';
            if (CPagesChessCommand_::$link_embleme !='Image/no_embleme.png')
              $result_ .='  <DIV>'.
                         '    <LABEL for="delEmbleme_">удалить эмблему</LABEL>'.
                         '    <INPUT type="checkbox" id="delEmbleme_" name="delEmbleme_" '.(isset($params_['delEmbleme_']) ? 'checked' : '').'>'.
                         '  </DIV>';
            $result_ .='</DIV>';
            if ($dhtml_)
                $result_ .='<SCRIPT type="text/javascript">'."\n".
                           '   function CheckLengthMessage(){'."\n".
                           '     if (document.getElementById("slogan_").value.length > 400)'."\n".
                           '        document.getElementById("slogan_").value =document.getElementById("slogan_").value.substring(0,400)'."\n".
                           '     document.getElementById("chars_").value = 400 - document.getElementById("slogan_").value.length'."\n".
                           '   }'."\n".
                           '</SCRIPT>'."\n";

            return $result_;
        }#BodyEditInfo
        
        public static function BodyInfoCommandAdd(){
            $result_ = '<DIV style="font-size:12pt; color:black">'.
                       '  Команда создана.'.
                       '</DIV>';
            return $result_;
        }#BodyInfoCommandAdd

        protected static function check_member_other_command(){
           $s ='select id_command_ from TMembershipChessCommands_ where id_gamer_='.$_SESSION[SESSION_ID_];
           $cursor_=mysql_query($s,const_::$connect_);  if (!$cursor_) throw new Exception('При проверки на участие в других коммандах произошла ошибка.');
           $row_ =mysql_fetch_array($cursor_);
           mysql_free_result($cursor_);
           if($row_)
             throw new Exception('Вы не можете быть членом двух команд одновременно.');
        }#check_member_other_command
        
        protected static function check_command_name($name_){
           $name_ =trim($name_); if (strlen($name_) > 400) $name_ =substr($name_,0,400);
           $s ='select id_ from TChessCommands_ where name_=\''.mysql_real_escape_string($name_,const_::$connect_).'\'';
           $s =convert_cyr_string($s,'w','d');
           $cursor_=mysql_query($s,const_::$connect_);  if (!$cursor_) throw new Exception('При проверки информации о новой команде произошла ошибка.');
           $row_ =mysql_fetch_array($cursor_);
           mysql_free_result($cursor_);
           if($row_)
             throw new Exception('Команда с таким названием уже существует.');
            else
             return $name_;
        }#check_command_name
        
        protected static function check_file_embleme($file_){
          try{
            return mylib::load_and_convert_to_png($file_,100,20000);
          }catch(EmylibError $e){
            throw new Exception($e->getMessage());
          }
        }#check_file_embleme

#$type_: 1 - добавление команды
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

                if($_GET['chess_command'] =='make')
                  $type_=1; #добавление команды
                 else
                  $type_=2; #страница события

                switch ($type_){
#Добавление команды
#---------------------------------------------------------------------------------------------
                    case 1:
                        $info_command_is_add =false;
                        if (isset($_POST['name_'])){
                          $err_ ='';  
#проверяю уникальность названия команды
                          try{
                            $n =CPagesChessCommand_::check_command_name($_POST['name_']);
                          }catch(Exception $e){$err_=$e->getMessage();}
#проверяю на членство в двух командах одновременно
                          try{                          
                            CPagesChessCommand_::check_member_other_command();
                          }catch(Exception $e){if($err_ !='') $err_ .=' '; $err_ .=$e->getMessage();}
#проверяю эмблему                       
                          if (isset($_FILES['fileEmbleme_']) && ($_FILES['fileEmbleme_']['name'] !='')){
                            try{
                              $photo_ =CPagesChessCommand_::check_file_embleme($_FILES['fileEmbleme_']);
                            }catch(Exception $e){if($err_ !='') $err_ .=' '; $err_ .=$e->getMessage();}
                          }else
                            $photo_ =null;
                          
                          if($err_ != ''){
                            CPagesChessCommand_::$link_embleme ='Image/no_embleme.png';
                            $p_ =array();
                            $p_['name_'] =$_POST['name_'];
                            $p_['slogan_'] =$_POST['slogan_'];
                            if (isset($_FILES['fileEmbleme_']) && !isset($_POST['delEmbleme_']))
                              $p_['fileEmbleme_'] =$_FILES['fileEmbleme_']['name'];  
                            $body_ =CPagesChessCommand_::BodyEditInfo($p_,$err_);
                            $title_ ='Команды';
                          }else{
#добавляю команду
                            $s ='insert into TChessCommands_(name_,emblem_,slogan_)'.
                                ' values(\''.mysql_real_escape_string(convert_cyr_string($n,'w','d'),const_::$connect_).'\','.
                                         (is_null($photo_) ? 'null,' : '\''.mysql_real_escape_string($photo_,const_::$connect_).'\',').
                                         (trim($_POST['slogan_']) =='' ? 'null' : '\''.mysql_real_escape_string(convert_cyr_string(trim($_POST['slogan_']),'w','d'),const_::$connect_).'\'').
                                       ')';
                            if(!mysql_query($s,const_::$connect_)) throw new Exception('При создании команды произошла ошибка.');
#добавляю капитана
                            $s ='insert into TMembershipChessCommands_(id_command_,id_gamer_,isCaptain_)'.
                                ' values('.mysql_insert_id(const_::$connect_).','.$_SESSION[SESSION_ID_].',\'Y\')';    
                            if (!mysql_query($s,const_::$connect_)) throw new Exception('При создании команды произошла ошибка.');
                            if (!const_::Commit_()) throw new Exception('При создании команды произошла ошибка.');
                            if ($transact_) $transact_ =false; 
                            $body_ =CPagesChessCommand_::BodyInfoCommandAdd();
                            $title_ ='Команда '.$n;
                            $info_command_is_add =true;
                          }
                        }else{
                            CPagesChessCommand_::$link_embleme ='Image/no_embleme.png';
                            $body_ =CPagesChessCommand_::BodyEditInfo();
                            $title_ ='Команды';
                        }#if
                        if (!$info_command_is_add){
                          CPagesChessCommand_::$header_section ='Создать команду';
                          CPagesChessCommand_::$link_cancel ='MainPage.php?link_=chess_commands';
                          CPagesChessCommand_::$link_post ='MainPage.php?chess_command=make';
                        }  
                        break;
#---------------------------------------------------------------------------------------------                        
#События
#---------------------------------------------------------------------------------------------
                    case 2:
                        $body_ ='';
                        $title_ ='Команда '.htmlspecialchars(CPagesChessCommand_::$command_name);
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
                                  $title_.
                                  '</DIV>';
                switch ($type_){
                    case 1:
                        if ($info_command_is_add)
                          CPagesChessCommand_::menu_info_command_add();                            
                        else
                          CPagesChessCommand_::menu_edit_info();
                        break;
                    case 2: CPagesChessCommand_::menu_page(1); break;
                }#switch
                CPage_::$body_ =$body_;
                CPage_::MakePage();
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
                CPage_::PageErr();
            }#try

        }#MakePage
    }#CPagesChessCommand
?>
