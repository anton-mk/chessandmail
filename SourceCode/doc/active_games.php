<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="text_doc">'.
                '   <H2 id="title_doc">���� ������������� ������ � �������</H2>'.
                '   <DIV style="text-align:justify">'.
                '        <IMG src="doc/Image/menu2_games.png" style="float:right; border: 1px solid white; margin-bottom: 4px">'.
                '        ������, ������� �� �������, ����� ���������� � ������� "������", ������ ��������'.
                '        "��������". ���������� �� ���� �������� ������������� � ���� ���� ������, ������ ��������'.
                '        ������ ������, ��������� ��� ���. ������ ������ ������, ��������� ��� ���������. ������ ������� ������'.
                '        ���������� ������ ������, ����� �� �������, ����� ������� �� �� ��������.'.
                '        <IMG src="doc/Image/table_games.png" style="float:right; border: 1px solid white; margin-bottom: 4px; clear: right">'.
                '        � �������� ������������ ��� ������ �� ���������, ��� � ������ on-line. ��'.
                '        ���� ��������� ������� ����������� ����� ������ on-line, �� ����������� ���� ������ ������...'.
                '        <P>'.
                '        <IMG src="doc/Image/menu_games.png" style="float:right; border: 1px solid white; margin-bottom: 4px; clear: right">'.
                '        ���� ���� ������, ��������� ��� ���, � �� �� ���������� �� �������� "��������", ������, ����� �'.
                '        �������� ��������, ���� ��������� ��������� � ���� ��������. ���� ������ "������" �������,'.
                '        ��������� �������� �� ������ �������.'.
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
