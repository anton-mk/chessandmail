<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="text_doc">'.
                '   <H2 id="title_doc">���������� ������ � �������</H2>'.
                '   <P>������ ��������� ����������� ���� ���� �� ���������� �������� ���, �������� ���, ����'.
                '      ������, ��������� ����������� �� �����, � ������ �� ���������� ����������� �����. ���� � ������ ��'.
                '      ���������� ����������� �����, � �� ����� �������� ������ ��� ������, ����� ��������������'.
                '      �����.'.
                '   <DIV>'.
                '     <IMG src="doc/Image/menu_end_game.png" style="float:right; border: 1px solid white; margin-top: 4px">'.
                '     <IMG src="doc/Image/label_end_game.png" style="float:right; clear:right; border: 1px solid white; margin-top: 8px">'.
                '     <IMG src="doc/Image/menu1_end_game.png" style="float:right; clear:right; border: 1px solid white; margin-top: 8px">'.
                '     <H3 id="title_2_doc">����������� ������</H3>'.
                '     <P>�� ����� ChessAndMail ����� ����� ���������� ��� �� ����� ������ ����, ��� � �� ����� ����'.
                '        ���������. ��� ����� ����� ������ � ���� �� ����� "���������� �����" � �� ����������� ��������'.
                '        ���������� �������� �����������.'.
                '     <H3 id="title_2_doc">�������� ������</H3>'.
                '     <P>���������� � ����������� �� ����� ���������� ������ ����� � ���� ������� "����� ���������� �����"'.
                '        ��� "������ ���������� �����". � � ���� ������ ���� "������� �����". ��� �������� ������ �����'.
                '        ������ �� ���� ����� � �� ����������� �������� ����������� �������.'.
                '     <H3 id="title_2_doc">����� ������</H3>'.
                '     <P>����� ������ ����� ��� �� ����� ������ ����, ��� � �� ����� ���� ����������. ��� ����� ������ �����'.
                '        ������ � ���� �� ����� "����� ������" � �� ����������� �������� ����������� ������� ����� ������.'.
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
