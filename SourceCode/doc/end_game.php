<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="text_doc">'.
                '   <H2 id="title_doc">«авершение партии в шахматы</H2>'.
                '   <P>ѕарти€ считаетс€ завершенной если один из соперников поставил мат, поставил пат, сдал'.
                '      партию, соперники согласились на ничью, у одного из соперников закончилось врем€. ≈сли у одного из'.
                '      соперников закончилось врем€, а на доске остались только два корол€, будет зафиксированна'.
                '      ничь€.'.
                '   <DIV>'.
                '     <IMG src="doc/Image/menu_end_game.png" style="float:right; border: 1px solid white; margin-top: 4px">'.
                '     <IMG src="doc/Image/label_end_game.png" style="float:right; clear:right; border: 1px solid white; margin-top: 8px">'.
                '     <IMG src="doc/Image/menu1_end_game.png" style="float:right; clear:right; border: 1px solid white; margin-top: 8px">'.
                '     <H3 id="title_2_doc">ѕредложение ничьей</H3>'.
                '     <P>Ќа сайте ChessAndMail ничью можно предложить как во врем€ своего хода, так и во врем€ хода'.
                '        соперника. ƒл€ этого нужно нажать в меню на пункт "ѕредложить ничью" и на по€вившейс€ странице'.
                '        подтверить отправку предложени€.'.
                '     <H3 id="title_2_doc">ѕрин€тие ничьей</H3>'.
                '     <P>»нформаци€ о предложении на ничью по€вл€етс€ вверху доски в виде надписи "белые предложили ничью"'.
                '        или "черные предложили ничью". » в виде пункта меню "ѕрин€ть ничью". ƒл€ прин€ти€ ничьей нужно'.
                '        нажать на этот пункт и на по€вившейс€ странице подтвердить решение.'.
                '     <H3 id="title_2_doc">—дача партии</H3>'.
                '     <P>—дать партию можно как во врем€ своего хода, так и во врем€ хода противника. ƒл€ сдачи партии нужно'.
                '        нажать в меню на пункт "—дать партию" и на по€вившейс€ странице подтвердить решение сдать партию.'.
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

      CPage_::$menu_[2]['link'] = 'doc_.php?link_=message_in_time_game';
      CPage_::$menu_[2]['image'] ='Image/forward_doc.png';
      CPage_::$menu_[2]['submit'] =false;
      CPage_::$menu_[2]['level'] =1;
      CPage_::$menu_[2]['active'] ='N';

      CPage_::$menu_[3]['link'] = 'doc_.php?link_=variant_';
      CPage_::$menu_[3]['image'] ='Image/back_doc.png';
      CPage_::$menu_[3]['submit'] =false;
      CPage_::$menu_[3]['level'] =1;
      CPage_::$menu_[3]['active'] ='N';
   }//Make_Menu_
?>
