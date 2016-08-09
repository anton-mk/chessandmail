<?php
    require_once('const_.php');
    require_once('Users.php');
    require_once('rule_game_.php');

    class EConfigurationError extends Exception{};

    class CConfiguration_{
#функции вызываемые из диспетчера MainPage (исключая MakePage функцию, она описана ниже)
#--------------------------------------------------------------------------------------------
        public static function getPhoto($id_){
            header("Content-type: image/jpeg");
            echo(CUsers_::ReadPhoto($id_));
        }#getPhoto

        public static function getQuestionDelPhoto(){
            CPage_::QuestionPage('Подтвердите удаление фотографии.',
                                 'MainPage.php?link_=control_photo',
                                 'MainPage.php?link_=control_photo&confirm_=yes');
        }
#--------------------------------------------------------------------------------------------

#$type_ - тоже, что и в CConfiguration_::MakePage()
        public static function MakeMenuMainPage($type_){
            $i =CPage_::PositionMenu_('Настройка') +1;

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=password';
            CPage_::$menu_[$i]['image'] ='Image/label_password.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==1) ? 'Y' : 'N';

   			CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=aboutYou';
			CPage_::$menu_[$i]['image'] ='Image/label_aboutYou.png';
			CPage_::$menu_[$i]['submit'] =false;
   			CPage_::$menu_[$i]['level'] =2;
   			CPage_::$menu_[$i++]['active'] =($type_ ==2) ? 'Y' : 'N';

   			CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=e_mail';
			CPage_::$menu_[$i]['image'] ='Image/label_e_mail.png';
			CPage_::$menu_[$i]['submit'] =false;
   			CPage_::$menu_[$i]['level'] =2;
   			CPage_::$menu_[$i++]['active'] =($type_ ==3) ? 'Y' : 'N';

   			CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=dhtml';
			CPage_::$menu_[$i]['image'] ='Image/label_dhtml.png';
			CPage_::$menu_[$i]['submit'] =false;
   			CPage_::$menu_[$i]['level'] =2;
   			CPage_::$menu_[$i++]['active'] =($type_ ==4) ? 'Y' : 'N';

   			CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=scale_board';
			CPage_::$menu_[$i]['image'] ='Image/label_board.png';
			CPage_::$menu_[$i]['submit'] =false;
   			CPage_::$menu_[$i]['level'] =2;
   			CPage_::$menu_[$i++]['active'] =($type_ ==5) ? 'Y' : 'N';

   			CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=view_board';
			CPage_::$menu_[$i]['image'] ='Image/label_view_board.png';
			CPage_::$menu_[$i]['submit'] =false;
   			CPage_::$menu_[$i]['level'] =2;
   			CPage_::$menu_[$i++]['active'] =($type_ ==8) ? 'Y' : 'N';

   			CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=control_photo';
			CPage_::$menu_[$i]['image'] ='Image/label_photo.png';
			CPage_::$menu_[$i]['submit'] =false;
   			CPage_::$menu_[$i]['level'] =2;
   			CPage_::$menu_[$i++]['active'] =($type_ ==6) ? 'Y' : 'N';

   			CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=control_otpusk';
			CPage_::$menu_[$i]['image'] ='Image/label_otpusk.png';
			CPage_::$menu_[$i]['submit'] =false;
   			CPage_::$menu_[$i]['level'] =2;
   			CPage_::$menu_[$i++]['active'] =($type_ ==7) ? 'Y' : 'N';

            CPage_::MakeMenu_(CPage_::PositionMenu_('Настройка'));
        }#MakeMenuMainPage

        public static function getHTMLcontrolOtpusk($count_otpusk,$in_otpusk){
          if ($in_otpusk)
             $result_ ='<FORM action="MainPage.php?link_=stop_otpusk" method="POST">'."\n".
                         'Осталось отпуска: '.clockToStr($count_otpusk).'<BR><BR>'."\n".
                         '<INPUT type="submit" value="прервать отпуск">'."\n".
                       '</FORM>';
           elseif ($count_otpusk > 0)
             $result_ ='<FORM action="MainPage.php?link_=start_otpusk" method="POST">'."\n".
                         'Осталось отпуска: '.clockToStr($count_otpusk).'<BR><BR>'."\n".
                         '<INPUT type="submit" value="взять отпуск">'."\n".
                       '</FORM>';
           else
             $result_ ='Осталось отпуска: '.clockToStr($count_otpusk)."\n";
          return $result_;
        }#getHTMLcontrolOtpusk

        public static function BodyControlOtpusk($count_otpusk,$in_otpusk,$activeDHTML){
          $result_ = '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                               'font-size: 16pt; color: white; text-align: center;'.
                               'text-decoration: none; font-weight: normal">'.
                         'Отпуск'.
                     '</DIV><BR>'."\n".
                     '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                               'font-size: 12pt; color: black; text-align:center;'.
                               'text-decoration: none; font-weight: normal">'."\n".
                        '<SPAN id="otpusk">'."\n".
                            CConfiguration_::getHTMLcontrolOtpusk($count_otpusk,$in_otpusk);
                        '</SPAN>'."\n".
                     '</DIV>'."\n";
          if ($in_otpusk && $activeDHTML)
            $result_ .='<SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n".
                       '<SCRIPT type="text/javascript" src="scripts/control_otpusk.js"></SCRIPT>'."\n";
          return $result_;
        }#BodyControlOtpusk

        public static function BodyControlPhoto($photo_,$error_){
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
#                          '     <A href="'. $photo_.'" style="border:none">фотография</A>'."\n".
                          '     <IMG src="'.(($photo_ =='') ? 'Image/no_photo.png' : $photo_).'" style="border:none">'."\n".
                          '  </TD>'."\n".
                          '</TR>'."\n".
                        '</TABLE>'."\n".
                   '  </TD></TR>';
          if ($photo_ != '')
              $result_ .='<TR><TD style="text-align: center">'."\n".
                           '<FORM action="MainPage.php?link_=question_del_photo" method="POST">'."\n".
                           '   <INPUT type="submit" value="Удалить">'."\n".
                           '</FORM>'."\n".
                         '</TD></TR>'."\n";
            else
              $result_ .='<TR><TD>&nbsp;</TD></TR>'."\n";
          $result_ .= '</TABLE>'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                               'font-size: 12pt; color: black;'.
                               'text-decoration: none; font-weight: normal">'."\n".
                      '<TABLE style="margin-left: auto; margin-right: auto">'."\n".
                      '  <COL span="1">'."\n".
                      '  <TR>'."\n".
                      '     <TD>'."\n".
                      '       <FORM action="MainPage.php?link_=control_photo" method="POST" enctype="multipart/form-data">'."\n".
                      '          <TABLE style="margin-left: auto; margin-right: auto">'."\n".
                      '            <COL span="3">'."\n".
                      '            <TR>'."\n".
                      '               <TD><LABEL for="fileMyPhoto_">файл</LABEL></TD>'."\n".
                      '               <TD><INPUT type="file" id="fileMyPhoto_" name="fileMyPhoto_"></TD>'."\n".
                      '               <TD><INPUT type="submit" value="Отправить"></TD>'."\n".
                      '            </TR>'."\n".
                      '          </TABLE>'."\n".
                      '       </FORM>'."\n".
                      '     </TD>'."\n".
                      '  </TR>'."\n".
                      '</TABLE>'."\n".
                   '</DIV>'."\n";
          if ($error_ != '')
            $result_ .= '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                    'font-size: 12pt; color: white; text-align: center;'.
                                    'text-decoration: none; font-weight: normal">'.
                           $error_.
                        '</DIV>';

          return $result_;
        }#BodyControlPhoto

        public static function BodyBoard($error_,$scale_board_){
          $result_='<FORM action="MainPage.php?link_=scale_board" method="POST">'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'Масштаб доски'.
                      '</DIV><BR>'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                         '<TABLE style="margin-left: auto; margin-right: auto">'."\n".
                            '<COL span="2">'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="scale_board_">масштаб доски</LABEL>&nbsp;</TD>'."\n".
                                '<TD><INPUT type="text" id="scale_board_" name="scale_board_" value="'.htmlspecialchars($scale_board_).'"></TD>'."\n".
                            '</TR>'."\n".
                         '</TABLE>'."\n".
                      '</DIV>'."\n".
                      '<BR>'."\n".
                      '<DIV style="text-align: center">'."\n".
                          '<INPUT type="submit" value="Отправить">'."\n".
                      '</DIV><BR>'."\n";

          if ($error_ != '')
            $result_ .= '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                    'font-size: 12pt; color: white; text-align: center;'.
                                    'text-decoration: none; font-weight: normal">'.
                           $error_.
                        '</DIV><BR>';

          $result_ .= '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black; text-align: justify;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                          'Этот параметр влияет на размер шахматной доски. Указывает во сколько раз нужно'."\n".
                          'растянуть размеры доски по вертикали и горизонтали при отображении в браузере.'."\n".
                          '1 - доска не будет изменена,  2 - доска будет увеличина вдвое, 0.5 - доска будет уменьшена вдвое.'."\n".
                          'Если у Вас разрешение монитора 1024х768 рекомендую установить данный параметр в 0.6.'."\n".
                      '</DIV>'."\n".
                   '</FORM>'."\n";
          return $result_;
        }#BodyBoard

        protected static function get_board($num_board,$scale_){
          if($num_board ==0){
#основной набор
            const_::$size_image_cell =68;
            const_::$size_image_left_board =19;
            const_::$size_image_right_board =18;
            const_::$size_image_top_bottom_board =20;
            const_::$catalog_image_fugure =cat_img_figures_main;
          }else if($num_board ==2){
#второй набор
            const_::$size_image_cell =68;
            const_::$size_image_left_board =20;
            const_::$size_image_right_board =20;
            const_::$size_image_top_bottom_board =20;
            const_::$catalog_image_fugure =cat_img_figures_2;
          }else{
            const_::$size_image_cell =68;
            const_::$size_image_left_board =19;
            const_::$size_image_right_board =18;
            const_::$size_image_top_bottom_board =20;
            const_::$catalog_image_fugure =cat_img_figures_3;
          }
#начальная позиция
          $g_ =new CRuleGame();
          $g_ ->firstPosition();
          return CPage_::OutBoard($g_,$scale_,'board-'.$num_board,$pref_='pref-b'.$num_board);
        }#get_board

        public static function BodyViewBoard($num_board){
          $scale_ =CUsers_::Read_scale_board();

          $result_='<FORM action="MainPage.php?link_=view_board" method="POST">'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'Вид доски'.
                      '</DIV><BR>'.
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'.
                         '<TABLE style="margin-left: auto; margin-right: auto">'.
                            '<COL span="1">'.
                            '<TR><TD>'.CConfiguration_::get_board(0,$scale_).'</TD></TR>'.
                            '<TR><TD style="text-align:center"><INPUT type="radio" id="view_board" name="view_board" value="board_main"'.(($num_board==0) ? 'checked' : '').'></TD></TR>'.
                            '<TR><TD>'.CConfiguration_::get_board(2,$scale_).'</TD></TR>'.
                            '<TR><TD style="text-align:center"><INPUT type="radio" id="view_board" name="view_board" value="board_2"'.(($num_board==2) ? 'checked' : '').'></TD></TR>'.
                            '<TR><TD>'.CConfiguration_::get_board(3,$scale_).'</TD></TR>'.
                            '<TR><TD style="text-align:center"><INPUT type="radio" id="view_board" name="view_board" value="board_3"'.(($num_board==3) ? 'checked' : '').'></TD></TR>'.
                         '</TABLE>'."\n".
                      '</DIV>'."\n".
                      '<BR>'."\n".
                      '<DIV style="text-align: center">'.
                          '<INPUT type="submit" value="Отправить">'.
                      '</DIV>'.
                   '</FORM>';
          return $result_;
        }#BodyViewBoard

        public static function BodyChangedBoard(){
          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'Масштаб доски'.
                   '</DIV><BR>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                               'font-size: 12pt; color: black; text-align: center;'.
                               'text-decoration: none; font-weight: normal">'."\n".
                        'Масштаб доски изменен.'.
                   '</DIV>';
          return $result_;
        }#BodyChangedBoard

        public static function BodyChangedViewBoard(){
          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'Вид доски'.
                   '</DIV><BR>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                               'font-size: 12pt; color: black; text-align: center;'.
                               'text-decoration: none; font-weight: normal">'."\n".
                        'Вид доски изменен.'.
                   '</DIV>';
          return $result_;
        }#BodyChangedBoard

        public static function BodyDHTML($dhtml_){
          $result_='<FORM action="MainPage.php?link_=dhtml" method="POST">'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'DHTML'.
                      '</DIV><BR>'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                         '<TABLE style="margin-left: auto; margin-right: auto">'."\n".
                            '<COL span="2">'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="active_dhtml_">включить DHTML</LABEL></TD>'."\n".
                                '<TD>'."\n".
                                  '<INPUT type="checkbox" id="active_dhtml_" name="active_dhtml_" '.($dhtml_ ? 'checked' : '').'>'."\n".
                                  '<INPUT type="hidden" id="hidden_dhtml_" name="hidden_dhtml_">'."\n".
                                '</TD>'."\n".
                            '</TR>'."\n".
                         '</TABLE>'."\n".
                      '</DIV>'."\n".
                      '<BR>'."\n".
                      '<DIV style="text-align: center">'."\n".
                          '<INPUT type="submit" value="Отправить">'."\n".
                      '</DIV><BR>'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black; text-align: justify;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                         'При установке этого флага некоторые страницы сайта будут содержать сценарии на языке JavaScript.'."\n".
                         'Эти сценарии будут выполняться на вашем компьютере во время просмотра таких страниц. Включение DHTML сделает'."\n".
                         'более удобной работу с сайтом. Например, в гостевой книге в поле сообщение длина вводимого текста'."\n".
                         'ограничена 200 символами. При включении DHTML вы будете видеть, сколько символов ещё можно ввести.'."\n".
                         'При выключенном DHTML в этом поле можно вводить текст неограниченной длины, но при сохранении он'."\n".
                         'будет урезан до 200 символов. Кроме того, если вы будете играть партии с регламентом меньше одного дня,'."\n".
                         'DHTML должен быть включен обязательно.<BR/>'."\n".
                         'Если вы установили флаг DHTML, проверьте, чтобы в вашем браузере было разрешено использование'."\n".
                         'клиентских сценариев.'."\n".
                      '</DIV>'."\n".
                   '</FORM>'."\n";
          return $result_;
        }#BodyDHTML

        public static function BodyChangedDHTML(){
          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'DHTML'.
                   '</DIV><BR>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                               'font-size: 12pt; color: black; text-align: center;'.
                               'text-decoration: none; font-weight: normal">'."\n".
                        'Информация изменена.'.
                   '</DIV>';
          return $result_;
        }#BodyChangedDHTML

        public static function BodyE_Mail($e_mail_,$move_to_e_mail_){
          $result_='<FORM action="MainPage.php?link_=e_mail" method="POST">'."\n".
                      '<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'text-decoration: none; font-weight: normal">'.
                      '  <DIV style="font-size: 16pt; color: white; text-align: center">'.
                      '     E-mail'.
                      '  </DIV><BR>'.
                      '  <DIV style="font-size: 12pt; color: black; text-align: justify">'."\n".
                      '    Почтовый ящик нужен для связи с Вами, например, если забудете пароль, на этот адрес будет выслан новый,'.
                      '    можно настроить отправку информации о сделанном противником ходе.'.
                      '    В базе адрес хранится в зашифрованном виде.'.
                      '  </DIV>'."\n".
                      '  <BR>'.
                      '  <DIV style="font-size: 12pt; color: black; white-space:nowrap; ; text-align: center">'.
                      '     <LABEL for="e_mail_">e-mail</LABEL>&nbsp;'.
                      '     <INPUT type="text" id="e_mail_" name="e_mail_" value="'.$e_mail_.'" maxlength="100">'.
                      '  </DIV>'.
                      '  <BR>'.
                      '  <DIV style="font-size: 12pt; color: black; white-space:nowrap; ; text-align: center">'.
                      '     <LABEL for="move_to_e_mail_">отправлять на e-mail нформацию о сделанном ходе</LABEL>&nbsp;'.
                      '     <INPUT type="checkbox" id="move_to_e_mail_" name="move_to_e_mail_" '.($move_to_e_mail_ ? 'checked' : '').'>'.
                      '  </DIV>'.
                      '  <BR>'.
                      '  <DIV style="text-align: center">'.
                      '    <INPUT type="submit" value="Отправить">'.
                      '  </DIV>'.
                      '</SPAN>'.
                   '</FORM>'."\n";
          return $result_;
        }#BodyE_Mail

        public static function BodyChangedE_Mail(){
          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'e-mail'.
                   '</DIV><BR>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                               'font-size: 12pt; color: black; text-align: center;'.
                               'text-decoration: none; font-weight: normal">'."\n".
                        'Параметры изменены.'.
                   '</DIV>';
          return $result_;
        }#BodyChangedE_Mail

        public static function BodyAboutYou($error_,$famil_,$ima_,$otchest_,$date_birth,$country_,$punkt_){
          $result_='<FORM action="MainPage.php?link_=aboutYou" method="POST">'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'О Вас'.
                      '</DIV><BR>'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                         '<TABLE style="margin-left: auto; margin-right: auto">'."\n".
                            '<COL span="2">'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="famil_">фамилия</LABEL></TD>'."\n".
                                '<TD><INPUT type="text" id="famil_" name="famil_" value="'.$famil_.'" maxlength="50"></TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="ima_">имя</LABEL></TD>'."\n".
                                '<TD><INPUT type="text" id="ima_" name="ima_" value="'.$ima_.'" maxlength="50"></TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="otchest_">отчество</LABEL></TD>'."\n".
                                '<TD><INPUT type="text" id="otchest_" name="otchest_" value="'.$otchest_.'" maxlength="50"></TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="date_birth">дата рождения (дд/мм/гггг)</LABEL></TD>'."\n".
                                '<TD><INPUT type="text" id="date_birth" name="date_birth" value="'.$date_birth.'"></TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD>&nbsp;</TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="country_">страна</LABEL></TD>'."\n".
                                '<TD><INPUT type="text" id="country_" name="country_" value="'.$country_.'" maxlength="50"></TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="punkt_">город (нас. пункт)</LABEL></TD>'."\n".
                                '<TD><INPUT type="text" id="punkt_" name="punkt_" value="'.$punkt_.'"  maxlength="50"></TD>'."\n".
                            '</TR>'."\n".
                         '</TABLE>'."\n".
                      '</DIV>'."\n".
                      '<BR>'."\n".
                      '<DIV style="text-align: center">'."\n".
                          '<INPUT type="submit" value="Отправить">'."\n".
                      '</DIV>'."\n".
                   '</FORM>'."\n";
          if ($error_ != '')
            $result_ .= '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                    'font-size: 12pt; color: white; text-align: center;'.
                                    'text-decoration: none; font-weight: normal">'.
                           $error_.
                        '</DIV>';
          return $result_;
        }#BodyAboutYou

        public static function BodyChangedAboutYou(){
          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'О Вас'.
                   '</DIV><BR>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                               'font-size: 12pt; color: black; text-align: center;'.
                               'text-decoration: none; font-weight: normal">'."\n".
                        'Информация изменена.'.
                   '</DIV>';
          return $result_;
        }#BodyChangedPassword

        public static function BodyPassword($error_){
          $result_='<FORM action="MainPage.php?link_=password" method="POST">'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'Смена пароля'.
                      '</DIV><BR>'."\n".
                      '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n".
                         '<TABLE style="margin-left: auto; margin-right: auto">'."\n".
                            '<COL span="2">'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="password_id">пароль</LABEL></TD>'."\n".
                                '<TD><INPUT type="password" id="password_id" name="password_" value=""  maxlength="35"></TD>'."\n".
                            '</TR>'."\n".
                            '<TR>'."\n".
                                '<TD><LABEL for="confirm_id">подтверждение пароля</LABEL></TD>'."\n".
                                '<TD><INPUT type="password" id="confirm_id" name="confirm_" value=""  maxlength="35"></TD>'."\n".
                            '</TR>'."\n".
                         '</TABLE>'."\n".
                      '</DIV>'."\n".
                      '<BR>'."\n".
                      '<DIV style="text-align: center">'."\n".
                          '<INPUT type="submit" value="Отправить">'."\n".
                      '</DIV>'."\n".
                   '</FORM>'."\n";
          if ($error_ != '')
            $result_ .= '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                    'font-size: 12pt; color: white; text-align: center;'.
                                    'text-decoration: none; font-weight: normal">'.
                           $error_.
                        '</DIV>';
          return $result_;
        }#BodyGames_my_move

        public static function BodyChangedPassword(){
          $result_='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 16pt; color: white; text-align: center;'.
                                  'text-decoration: none; font-weight: normal">'.
                        'Смена пароля'.
                   '</DIV><BR>'."\n".
                   '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                               'font-size: 12pt; color: black; text-align: center;'.
                               'text-decoration: none; font-weight: normal">'."\n".
                        'Пароль изменен.'.
                   '</DIV>';
          return $result_;
        }#BodyChangedPassword

#$type_: 1 - пароль, 2 - о Вас, 3 - e-mail, 4 - dhtml, 5 - доска, 6 - фотография, 7 -отпуск, 8 - вид доски
        public static function MakePage($type_){
#            date_default_timezone_set('Asia/Krasnoyarsk');
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
                        $password_='';
                        $confirm_ ='';
                        if (isset($_POST['password_'])) $password_ =trim($_POST['password_']);
                        if (isset($_POST['confirm_'])) $confirm_ =trim($_POST['confirm_']);
                        try{
                           if (isset($_POST['password_'])){
                             if ($password_ == '') throw new EConfigurationError('Пароль не указан');
                             if ($password_ != $confirm_) throw new EConfigurationError('Пароль не совпадает');
                             CUsers_::ChangePassword($password_);
                             $body_ =CConfiguration_::BodyChangedPassword();
                             $link_esc_doc ='MainPage.php?link_=password';
                           }else
                             $body_ =CConfiguration_::BodyPassword('');
                             $link_esc_doc ='MainPage.php?link_=password';
                        }catch(Exception $e){
                           if ($e instanceof EConfigurationError){
                              $body_ =CConfiguration_::BodyPassword($e->getMessage());
                              $link_esc_doc ='MainPage.php?link_=password';
                            }else
                              throw new Exception($e->getMessage());
                        }#catch
                        break;
                    case 2:
                        try{
                           if (isset($_POST['famil_'])){
                             $famil_ =trim($_POST['famil_']);
                             if (isset($_POST['ima_'])) $ima_ =trim($_POST['ima_']); else $ima_ ='';
                             if (isset($_POST['otchest_'])) $otchest_ =trim($_POST['otchest_']); else $otchest_ ='';
                             if (isset($_POST['country_'])) $country_ =trim($_POST['country_']); else $country_ ='';
                             if (isset($_POST['punkt_'])) $punkt_ =trim($_POST['punkt_']); else $punkt_ ='';
                             $date_birth ='';
                             if (isset($_POST['date_birth']) && (trim($_POST['date_birth']) !='')){
                               $day_=0; $month_ =0; $year_=0;
                               if (strToDate_(trim($_POST['date_birth']),$day_,$month_,$year_))
                                    $date_birth =''.$year_.'-'.$month_.'-'.$day_;
                                 else{
                                    $date_birth =$_POST['date_birth'];
                                    throw new EConfigurationError('Дата рождения указана неверно.');
                                 }
                             }
                             CUsers_::ChangeAboutYou($famil_,$ima_,$otchest_,$date_birth,$country_,$punkt_);
                             $body_ =CConfiguration_::BodyChangedAboutYou();
                             $link_esc_doc ='MainPage.php?link_=aboutYou';
                           }else{
                             $regInfo_ =CUsers_::ReadAboutYou($_SESSION[SESSION_ID_]);
                             $famil_     =$regInfo_['famil_'];
                             $ima_       =$regInfo_['ima_'];
                             $otchest_   =$regInfo_['otchest_'];
                             $date_birth =$regInfo_['date_birth'];
                             $country_   =$regInfo_['country_'];
                             $punkt_     =$regInfo_['punkt_'];
                             $body_ =CConfiguration_::BodyAboutYou('',$famil_,$ima_,$otchest_,$date_birth,$country_,$punkt_);
                             $link_esc_doc ='MainPage.php?link_=aboutYou';
                           }
                        }catch(Exception $e){
                           if ($e instanceof EConfigurationError){
                              $body_ =CConfiguration_::BodyAboutYou($e->getMessage(),$famil_,$ima_,$otchest_,$date_birth,$country_,$punkt_);
                              $link_esc_doc ='MainPage.php?link_=aboutYou';
                            }else
                              throw new Exception($e->getMessage());
                        }#catch
                        break;
                    case 3:
                        if (isset($_POST['e_mail_'])){
                          $e_mail_ =trim($_POST['e_mail_']);
                          $move_to_e_mail_ =isset($_POST['move_to_e_mail_']);
                          CUsers_::ChangeE_mail($e_mail_);
                          CUsers_::ChangeMoveToE_Mail($move_to_e_mail_);
                          $body_ =CConfiguration_::BodyChangedE_Mail();
                          $link_esc_doc ='MainPage.php?link_=e_mail';
                        }else{
                          $e_mail_ =CUsers_::ReadE_Mail($_SESSION[SESSION_ID_]);
                          $move_to_e_mail_ =CUsers_::ReadMoveToE_Mail($_SESSION[SESSION_ID_]);
                          $body_ =CConfiguration_::BodyE_Mail($e_mail_,$move_to_e_mail_);
                          $link_esc_doc ='MainPage.php?link_=e_mail';
                        }
                        break;
                    case 4:
                        if (isset($_POST['hidden_dhtml_'])){
                          $dhtml_ =isset($_POST['active_dhtml_']);
                          CUsers_::ChangeDHTML($dhtml_);
                          $body_ =CConfiguration_::BodyChangedDHTML();
                          $link_esc_doc ='MainPage.php?link_=dhtml';
                        }else{
                          $dhtml_ =CUsers_::Read_dhtml_($_SESSION[SESSION_LOGIN_]);
                          $body_ =CConfiguration_::BodyDHTML($dhtml_);
                          $link_esc_doc ='MainPage.php?link_=dhtml';
                        }
                        break;
                    case 5:
                        try{
                           if (isset($_POST['scale_board_'])){
                             $scale_board_ =$_POST['scale_board_'];
                             if (!preg_match('/^[0-9]+(\.[0-9]+)?$/',$scale_board_))
                                throw new EConfigurationError('Масштаб доски указан неверно.');
                             CUsers_::ChangeScale_board($scale_board_);
                             $body_ =CConfiguration_::BodyChangedBoard();
                             $link_esc_doc ='MainPage.php?link_=scale_board';
                           }else{
                             $scale_board_ =CUsers_::Read_scale_board();
                             $body_ =CConfiguration_::BodyBoard('',$scale_board_);
                             $link_esc_doc ='MainPage.php?link_=scale_board';
                           }
                        }catch(Exception $e){
                           if ($e instanceof EConfigurationError){
                              $body_ =CConfiguration_::BodyBoard($e->getMessage(),$scale_board_);
                            }else
                              throw new Exception($e->getMessage());
                        }#catch
                        break;
                    case 6:
                        try{
                           $s ='';
                           if (CUsers_::ExistsPhoto($_SESSION[SESSION_ID_]))
                              $s ='MainPage.php?link_=my_photo';
                           if (isset($_FILES['fileMyPhoto_'])){
                             if ($_FILES['fileMyPhoto_']['name'] =='')
                               throw new EConfigurationError('Файл не указан.');
                             if ($_FILES['fileMyPhoto_']['error'] !=0)
                               throw new EConfigurationError('При загрузке файла произошла ошибка.');
                             $photo_ =@getimagesize($_FILES['fileMyPhoto_']['tmp_name']);
                             if (!$photo_){
                               throw new EConfigurationError('Формат файла не удалось распознать.');
                             }
                             $im_1 =false;
                             switch ($photo_[2]){
                               case 1:
                                   $im_1 = @imagecreatefromgif($_FILES['fileMyPhoto_']['tmp_name']);
                                   break;
                               case 2:
                                   $im_1 = @imagecreatefromjpeg($_FILES['fileMyPhoto_']['tmp_name']);
                                   break;
                               case 3:
                                   $im_1 = @imagecreatefrompng($_FILES['fileMyPhoto_']['tmp_name']);
                                   break;
                               case 6:
                                   $im_1 = @imagecreatefromwbmp($_FILES['fileMyPhoto_']['tmp_name']);
                                   break;
                               case 15:
                                   $im_1 = @imagecreatefromwbmp($_FILES['fileMyPhoto_']['tmp_name']);
                                   break;
                               case 16:
                                   $im_1 = @imagecreatefromxbm($_FILES['fileMyPhoto_']['tmp_name']);
                                   break;
                             }#switch
                             if ($im_1){
                                $a=1;
                                $x =imagesx($im_1);
                                $y =imagesy($im_1);
                                if (($x > $y) && ($x > 200))
                                   $a =$x/200;
                                 else if (($y > $x) && ($y > 200))
                                   $a =$y/200;
                                if ($a !=1){
                                  $x_new = round($x/$a);
                                  $y_new = round($y/$a);
                                  $im_2 =imagecreatetruecolor($x_new, $y_new);
                                  imagecopyresampled ($im_2, $im_1, 0, 0, 0, 0, $x_new, $y_new, $x, $y);
                                  imagedestroy($im_1);
                                  $im_1=$im_2;
                                }
                                ob_start();
                                imagejpeg($im_1);
                                $photo_=ob_get_contents();
                                ob_end_clean();
                                imagedestroy($im_1);
                                if (strlen($photo_) > 20000)
                                  throw new EConfigurationError('Размер сохранянмых данных превышает ограничение. <BR>Попробуйте выбрать другой файл.');
                                CUsers_::ChangePhoto($photo_);
                                if ($s == '') $s ='MainPage.php?link_=my_photo';
                             }else
                               throw new EConfigurationError('Формат файла не удалось распознать.');
                           }else if (isset($_GET['confirm_'])){
                                CUsers_::ChangePhoto('');
                                $s ='';
                           }
                           $body_ =CConfiguration_::BodyControlPhoto($s,'');
                           $link_esc_doc ='MainPage.php?link_=control_photo';
                        }catch(Exception $e){
                           if ($e instanceof EConfigurationError){
                              $body_ =CConfiguration_::BodyControlPhoto($s,$e->getMessage());
                              $link_esc_doc ='MainPage.php?link_=control_photo';
                            }else
                              throw new Exception($e->getMessage());
                        }#catch
                        break;
                    case 7:
                        try{
                           CUsers_::Check_otpusk($_SESSION[SESSION_ID_]);
                           if ($_GET['link_']=='start_otpusk'){
                             $count_otpusk = CUsers_::Ostatok_Otpusk($_SESSION[SESSION_ID_]);
                             $in_otpusk =CUsers_::Status_Otpusk($_SESSION[SESSION_ID_]);
                             if (!$in_otpusk && ($count_otpusk >0))
                               CUsers_::Start_otpusk_($_SESSION[SESSION_ID_]);
                           }elseif ($_GET['link_']=='stop_otpusk'){
                             $count_otpusk = CUsers_::Ostatok_Otpusk($_SESSION[SESSION_ID_]);
                             $in_otpusk =CUsers_::Status_Otpusk($_SESSION[SESSION_ID_]);
                             if ($in_otpusk)
                               CUsers_::Stop_otpusk_($_SESSION[SESSION_ID_]);
                           }
                           $count_otpusk = CUsers_::Ostatok_Otpusk($_SESSION[SESSION_ID_]);
                           $in_otpusk =CUsers_::Status_Otpusk($_SESSION[SESSION_ID_]);
                           $activeDHTML =CUsers_::Read_dhtml_();
                           $body_ =CConfiguration_::BodyControlOtpusk($count_otpusk,$in_otpusk,$activeDHTML);
                           $link_esc_doc ='MainPage.php?link_=control_otpusk';
                        }catch(Exception $e){
                           throw new Exception($e->getMessage());
                        }#catch
                        break;
                    case 8:
                        if (isset($_POST['view_board']) && ($_POST['view_board'] =='board_main')){
                          CUsers_::ChangeView_board(0);
                          $body_ =CConfiguration_::BodyChangedViewBoard();
                        }else if (isset($_POST['view_board']) && ($_POST['view_board'] =='board_2')){
                          CUsers_::ChangeView_board(2);
                          $body_ =CConfiguration_::BodyChangedViewBoard();
                        }else if (isset($_POST['view_board']) && ($_POST['view_board'] =='board_3')){
                          CUsers_::ChangeView_board(3);
                          $body_ =CConfiguration_::BodyChangedViewBoard();
                        }else{
                          $num_board_ =CUsers_::Read_view_board();
                          if (is_null($num_board_) || (($num_board_ != 2) && ($num_board_ != 3))) $num_board_ =0;
                          $body_ =CConfiguration_::BodyViewBoard($num_board_);
                        }
                        break;
                    default : $body_ ='';
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
                                                 '  Настройка'.
                                                 '</DIV>';
                CConfiguration_::MakeMenuMainPage($type_);
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
    }#CConfiguration_
?>
