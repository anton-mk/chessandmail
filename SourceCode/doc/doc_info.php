<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="text_doc">'.
                '   <H2 id="title_doc">Что показывает закладка события</H2>'.
                '   <DIV style="text-align:justify">'.
                '        <IMG src="doc/Image/rasdel_info.png" style="float:right; border: 1px solid white; margin-bottom: 4px">'.
                '        <IMG src="doc/Image/table_games_2.png" style="float:right; border: 1px solid white; margin-bottom: 4px; clear: right">'.
                '        <IMG src="doc/Image/table_calls_2.png" style="float:right; border: 1px solid white; margin-bottom: 4px; clear: right">'.
                '        <IMG src="doc/Image/rasdel_info_2.png" style="float:right; border: 1px solid white; margin-bottom: 4px; clear: right">'.
                '        Закладка "события" находится в разделе "Информация". Если она не видна нужно нажать на надпись'.
                '        "Информация" или на кнопку рядом. На этой закладке показывается вся информация касающаяся Вас:'.
                '        завершение Ваших партий, изменение рейтинга и класса. Если Ваш соперник по незавершенной шахматной'.
                '        партии взял отпуск или вышел из отпуска, это тоже будет показано. Отображается и информация об изменениях на'.
                '        ChessAndMail, замеченных или исправленных ошибках. Кроме этого на закладке'.
                '        отображаются партии, ожидающие Ваш ход, и вызовы отправленные персонально Вам. Последняя информация'.
                '        представлена в виде таблиц. Первая колонка таблицы партий содержит ссылку на пратию,'.
                '        нажав на которую произойдет переход на страницу партии, где можно будет сделать ход. Вторая и третья'.
                '        колонка таблицы вызовов позволяет Вам принять или отклонить вызов.'.
                '       <P>Кнопка рядом с заклдакой "события" может содержать подсказку в виде конверта, информирующую о'.
                '             появлении событий, которые Вы еще не просматривали. Подсказка появляется только на неактивной'.
                '             закладке (закладка, которая  в данный момент не выбрана). Партии, ожидающих Ваш ход, и'.
                '             персональные вызовы не приведут к появлению подсказки рядом с закладкой "события".'.
                '             Подсказа появится рядом с соответствующими закладками в разделах "Парти" и "Вызовы". Если появились'.
                '             новые события, которые Вы ещё не просматривали, а раздел "Информация" свернут, подсказака в виде'.
                '             конверта появится на кнопке рядом с разделом.'.
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

      CPage_::$menu_[2]['link'] = 'doc_.php?link_=doc_messages';
      CPage_::$menu_[2]['image'] ='Image/forward_doc.png';
      CPage_::$menu_[2]['submit'] =false;
      CPage_::$menu_[2]['level'] =1;
      CPage_::$menu_[2]['active'] ='N';

      CPage_::$menu_[3]['link'] = 'doc_.php?link_=rating_and_class';
      CPage_::$menu_[3]['image'] ='Image/back_doc.png';
      CPage_::$menu_[3]['submit'] =false;
      CPage_::$menu_[3]['level'] =1;
      CPage_::$menu_[3]['active'] ='N';
   }//Make_Menu_
?>
