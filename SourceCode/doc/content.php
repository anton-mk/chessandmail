<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="content_doc">'.
                     '  <DIV style="font-size: 14pt; text-align: center">Содержание</DIV>'.
                     '  <UL>'.
                     '    <LI><A href="doc_.php?link_=registration_">Как зарегистрироваться на ChessAndMail</A></LI>'.
                     '    <LI><A href="doc_.php?link_=how_begin_game">Как начать играть</A></LI>'.
                     '    <LI><A href="doc_.php?link_=reglaments_">Регламенты</A></LI>'.
                     '    <LI>Шахматная партия</LI>'.
                     '    <UL>'.
                     '      <LI><A href="doc_.php?link_=how_make_move">Как сделать ход</A></LI>'.
                     '      <LI><A href="doc_.php?link_=variant_">Варианты продолжения</A></LI>'.
                     '      <LI><A href="doc_.php?link_=end_game">Завершение партии</A></LI>'.
                     '      <LI><A href="doc_.php?link_=message_in_time_game">Общение с противником во время партии</A></LI>'.
                     '    </UL>'.
                     '    <LI><A href="doc_.php?link_=rating_and_class">Классы и рейтинг</A></LI>'.
                     '    <LI>Раздел информация</LI>'.
                     '    <UL>'.
                     '      <LI><A href="doc_.php?link_=doc_info">Что показывает закладка события</A></LI>'.
                     '      <LI>Общение</LI>'.
                     '      <UL>'.
                     '        <LI><A href="doc_.php?link_=doc_messages">Общение с другими игроками</A></LI>'.
                     '        <LI><A href="doc_.php?link_=doc_guestbook">Отзывы и предложения</A></LI>'.
                     '      </UL>'.
                     '    </UL>'.
                     '    <LI>Партии</LI>'.
                     '    <UL>'.
                     '      <LI><A href="doc_.php?link_=active_games">Ваши незавершенные</A></LI>'.
                     '      <LI><A href="doc_.php?link_=end_games">Поиск завершенных</A></LI>'.
                     '    </UL>'.
                     '    <LI><A href="doc_.php?link_=tournaments">Турниры</A></LI>'.
                     '    <LI><A href="doc_.php?link_=calls">Вызовы</A></LI>'.
                     '    <BR>'.
                     '    <LI>Правила</A></LI>'.
                     '    <UL>'.
                     '      <LI>"Классические" шахматы</LI>'.
                     '      <UL>'.
                     '        <LI><A href="doc_.php?link_=begin_position_classic_chess">Начало</A></LI>'.
                     '        <LI><A href="doc_.php?link_=move_chess">Ходы</A></LI>'.
                     '        <LI><A href="doc_.php?link_=itog_chess">Цель игры</A></LI>'.
                     '      </UL>'.
                     '    </UL>'.
                     '  </UL>'.
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

      CPage_::$menu_[1]['link'] = 'doc_.php?link_=rule_classic_chess';
      CPage_::$menu_[1]['image'] ='Image/forward_doc.png';
      CPage_::$menu_[1]['submit'] =false;
      CPage_::$menu_[1]['level'] =1;
      CPage_::$menu_[1]['active'] ='N';
   }//Make_Menu_
?>
