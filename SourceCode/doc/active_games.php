<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="text_doc">'.
                '   <H2 id="title_doc">¬аши незавершенные партии в шахматы</H2>'.
                '   <DIV style="text-align:justify">'.
                '        <IMG src="doc/Image/menu2_games.png" style="float:right; border: 1px solid white; margin-bottom: 4px">'.
                '        ѕартии, которые ¬ы играете, можно посмотреть в разделе "ѕартии", выбрав закладку'.
                '        "активные". »нформаци€ на этой странице представленна в виде двух таблиц, перва€ содержит'.
                '        список партии, ожидающие ¬аш ход. ¬тора€ список партии, ожидающие ход соперника. ѕерва€ колонка таблиц'.
                '        показывает номера партии, нажав на которые, можно перейти на их страницу.'.
                '        <IMG src="doc/Image/table_games.png" style="float:right; border: 1px solid white; margin-bottom: 4px; clear: right">'.
                '        ¬ таблицах показываютс€ как партии по переписке, так и партии on-line. Ќе'.
                '        знаю насколько полезно отображение здесь партии on-line, но отображение этих партии сделал...'.
                '        <P>'.
                '        <IMG src="doc/Image/menu_games.png" style="float:right; border: 1px solid white; margin-bottom: 4px; clear: right">'.
                '        ≈сли есть партии, ожидающие ¬аш ход, и вы не находитесь на закладке "активные", кнопка, р€дом с'.
                '        надписью закладки, будт содержать подсказку в виде конверта. ≈сли раздел "ѕартии" свернут,'.
                '        подсказка по€витс€ на кнопке раздела.'.
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

      CPage_::$menu_[2]['link'] = 'doc_.php?link_=end_games';
      CPage_::$menu_[2]['image'] ='Image/forward_doc.png';
      CPage_::$menu_[2]['submit'] =false;
      CPage_::$menu_[2]['level'] =1;
      CPage_::$menu_[2]['active'] ='N';

      CPage_::$menu_[3]['link'] = 'doc_.php?link_=doc_guestbook';
      CPage_::$menu_[3]['image'] ='Image/back_doc.png';
      CPage_::$menu_[3]['submit'] =false;
      CPage_::$menu_[3]['level'] =1;
      CPage_::$menu_[3]['active'] ='N';
   }//Make_Menu_
?>
