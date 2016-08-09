<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="text_doc">'.
                '   <H2 id="title_doc">Как зарегистрироваться на сайте ChessAndMail</H2>'.
                '   <DIV style="text-align:justify">'.
                '     <BR>'.
                '     <DIV style="float: right">'.
                '        <DIV><IMG src="doc/Image/begin_registration.png" style="float:none; border: 1px solid white; margin-bottom: 4px"></DIV>'.
                '        <DIV><IMG src="doc/Image/end_registration.png" style="float:none; border: 1px solid white"></DIV>'.
                '     </DIV>'.
                '     <P>Регистрация очень проста, нужно придумать имя (логин), под котором Вас будут видеть другие'.
                '        игроки сайта, и пароль. В имени и пароле могут присутствовать любые символы русского и английского'.
                '        алфавитов, а также цифры, знаки препиная, скобки, подчеркивание,...'.
                '     <P>Чтобы начать регистрацию нужно нажать на надпись "Регистрация" или на кнопке рядом.'.
                '        В появившимся окне ввести придуманное имя и пароль. Придуманный пароль нужно будет указать два раза,'.
                '        в поле "пароль" и в поле "подтверждение пароля". То что введено в эти два поля должно полностью'.
                '        совпасть. Двойной ввод пароля нужен для того, чтобы, в определенной степени, исключить опечатку'.
                '        при наборе.'.
                '     <P>Для завершения регистрации нужно нажать на надпись "Отправить" или на кнопку рядом.'.
                '        Имя и пароль будут отправлены на сервер, где произойдет их проверка. Если Вы выбрали имя,'.
                '        которое уже кем-то используется на сайте, появится соответствующее сообщение и будет'.
                '        предложено ввести другое.'.
                '        Если информация, введеная в поля "пароль" и "подтверждение пароля", отличается, также отобразится'.
                '        сообщение и будет предложено ввести пароль заново.'.
                '     <P>После положительного завершения проверки имени и пароля Вы попадете на сайт.'.
                '        В разеле "Настройка" можно ввести дополнительную информацию о себе,'.
                '        добавить фотографию и выполнить настройки для комфортной игры.'.
                '   </DIV>'.
                '</SPAN>';
      return $result_;
   }//Body_

   function Make_Menu_(){
      if (isset($_SESSION[SESSION_LINK_ESC_DOC]))
         CPage_::$menu_[0]['link'] = $_SESSION[SESSION_LINK_ESC_DOC];
      else
         CPage_::$menu_[0]['link'] = 'index.php';
      CPage_::$menu_[0]['image'] ='Image/label_esc.png';
      CPage_::$menu_[0]['submit'] =false;
      CPage_::$menu_[0]['level'] =1;
      CPage_::$menu_[0]['active'] ='N';

      CPage_::$menu_[1]['link'] = 'doc_.php';
      CPage_::$menu_[1]['image'] ='Image/content_doc.png';
      CPage_::$menu_[1]['submit'] =false;
      CPage_::$menu_[1]['level'] =1;
      CPage_::$menu_[1]['active'] ='N';

      CPage_::$menu_[2]['link'] = 'doc_.php?link_=how_begin_game';
      CPage_::$menu_[2]['image'] ='Image/forward_doc.png';
      CPage_::$menu_[2]['submit'] =false;
      CPage_::$menu_[2]['level'] =1;
      CPage_::$menu_[2]['active'] ='N';
   }//Make_Menu_

?>
