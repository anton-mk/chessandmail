<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="content_doc">'.
                     '  <DIV style="font-size: 14pt; text-align: center">����������</DIV>'.
                     '  <UL>'.
                     '    <LI><A href="doc_.php?link_=registration_">��� ������������������ �� ChessAndMail</A></LI>'.
                     '    <LI><A href="doc_.php?link_=how_begin_game">��� ������ ������</A></LI>'.
                     '    <LI><A href="doc_.php?link_=reglaments_">����������</A></LI>'.
                     '    <LI>��������� ������</LI>'.
                     '    <UL>'.
                     '      <LI><A href="doc_.php?link_=how_make_move">��� ������� ���</A></LI>'.
                     '      <LI><A href="doc_.php?link_=variant_">�������� �����������</A></LI>'.
                     '      <LI><A href="doc_.php?link_=end_game">���������� ������</A></LI>'.
                     '      <LI><A href="doc_.php?link_=message_in_time_game">������� � ����������� �� ����� ������</A></LI>'.
                     '    </UL>'.
                     '    <LI><A href="doc_.php?link_=rating_and_class">������ � �������</A></LI>'.
                     '    <LI>������ ����������</LI>'.
                     '    <UL>'.
                     '      <LI><A href="doc_.php?link_=doc_info">��� ���������� �������� �������</A></LI>'.
                     '      <LI>�������</LI>'.
                     '      <UL>'.
                     '        <LI><A href="doc_.php?link_=doc_messages">������� � ������� ��������</A></LI>'.
                     '        <LI><A href="doc_.php?link_=doc_guestbook">������ � �����������</A></LI>'.
                     '      </UL>'.
                     '    </UL>'.
                     '    <LI>������</LI>'.
                     '    <UL>'.
                     '      <LI><A href="doc_.php?link_=active_games">���� �������������</A></LI>'.
                     '      <LI><A href="doc_.php?link_=end_games">����� �����������</A></LI>'.
                     '    </UL>'.
                     '    <LI><A href="doc_.php?link_=tournaments">�������</A></LI>'.
                     '    <LI><A href="doc_.php?link_=calls">������</A></LI>'.
                     '    <BR>'.
                     '    <LI>�������</A></LI>'.
                     '    <UL>'.
                     '      <LI>"������������" �������</LI>'.
                     '      <UL>'.
                     '        <LI><A href="doc_.php?link_=begin_position_classic_chess">������</A></LI>'.
                     '        <LI><A href="doc_.php?link_=move_chess">����</A></LI>'.
                     '        <LI><A href="doc_.php?link_=itog_chess">���� ����</A></LI>'.
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
