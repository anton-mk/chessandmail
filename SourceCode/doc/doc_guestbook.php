<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="text_doc">'.
                '   <H2 id="title_doc">Отзывы и предложения</H2>'.
                '   <DIV style="text-align:justify">'.
                '        <IMG src="doc/Image/menu3_info.png" style="float:right; border: 1px solid white; margin-bottom: 4px">'.
                '        На закладке "отзывы" раздела "Информация" можно предложить добавить или изменить что-либо на '.
                '        сайте. Можно высказать свое мнение о предложении другого игрока. Вся информация, написанная здесь, '.
                '        будет видна всем игрокам шахматного сайта ChessAndMail.'.
                '        Если раздел "Информация" свернут и закладка не видна, нажатие на названии раздела или кнопке'.
                '        рядом раскроет его.'.
                '        <P>'.
                '        Максимальна длина сообщения, которое можно оставить, 400 символов. Если'.
                '        флаг "dhtml" в разделе "Настройка" установлен, то появится дополнительное поле в котором, при'.
                '        наборе сообщения, будет отображаться количество символов, ещё доступных для ввода. Если флаг'.
                '        "dhtml" не установлен, слишком длинное сообщение перед отправкой на сайт будет урезано до 400'.
                '        символов. По умолчанию флаг "dhtml" сброшен.'.
                '        <P>Чтобы отправить сообщение, нужно ввести антиспамовый код, отображенный чуть выше кнопки'.
                '           "отправить". Антиспамовый код введен для защиты информации на закладке от '.
                '           рекламных сообщений, генерируемых различными программами. Так как регистрация на сайте очень'.
                '           проста, рекламмные программы могут с легкостью пройти её.'.
                '        <IMG src="doc/Image/menu4_info.png" style="float:right; border: 1px solid white; margin-top: 4px; clear: right">'.
                '        <P>Закладка "отзывы" не запрашивает информацию в режиме on-line. Поэтому такое общение на этой'.
                '           закладке невозможно. Если при запросе какой-либо информации с сайта, будут найдены не '.
                '           прочитанные Вами отзывы, на кнопке закладки отобразится конверт. Если раздел "Информация"'.
                '           будет свернут, конверт отобразится на кнопке раздела.'.
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

      CPage_::$menu_[2]['link'] = 'doc_.php?link_=active_games';
      CPage_::$menu_[2]['image'] ='Image/forward_doc.png';
      CPage_::$menu_[2]['submit'] =false;
      CPage_::$menu_[2]['level'] =1;
      CPage_::$menu_[2]['active'] ='N';

      CPage_::$menu_[3]['link'] = 'doc_.php?link_=doc_messages';
      CPage_::$menu_[3]['image'] ='Image/back_doc.png';
      CPage_::$menu_[3]['submit'] =false;
      CPage_::$menu_[3]['level'] =1;
      CPage_::$menu_[3]['active'] ='N';
   }//Make_Menu_
?>
