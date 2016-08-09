<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="text_doc">'.
                '   <H2 id="title_doc">��� ������������������ �� ����� ChessAndMail</H2>'.
                '   <DIV style="text-align:justify">'.
                '     <BR>'.
                '     <DIV style="float: right">'.
                '        <DIV><IMG src="doc/Image/begin_registration.png" style="float:none; border: 1px solid white; margin-bottom: 4px"></DIV>'.
                '        <DIV><IMG src="doc/Image/end_registration.png" style="float:none; border: 1px solid white"></DIV>'.
                '     </DIV>'.
                '     <P>����������� ����� ������, ����� ��������� ��� (�����), ��� ������� ��� ����� ������ ������'.
                '        ������ �����, � ������. � ����� � ������ ����� �������������� ����� ������� �������� � �����������'.
                '        ���������, � ����� �����, ����� ��������, ������, �������������,...'.
                '     <P>����� ������ ����������� ����� ������ �� ������� "�����������" ��� �� ������ �����.'.
                '        � ����������� ���� ������ ����������� ��� � ������. ����������� ������ ����� ����� ������� ��� ����,'.
                '        � ���� "������" � � ���� "������������� ������". �� ��� ������� � ��� ��� ���� ������ ���������'.
                '        ��������. ������� ���� ������ ����� ��� ����, �����, � ������������ �������, ��������� ��������'.
                '        ��� ������.'.
                '     <P>��� ���������� ����������� ����� ������ �� ������� "���������" ��� �� ������ �����.'.
                '        ��� � ������ ����� ���������� �� ������, ��� ���������� �� ��������. ���� �� ������� ���,'.
                '        ������� ��� ���-�� ������������ �� �����, �������� ��������������� ��������� � �����'.
                '        ���������� ������ ������.'.
                '        ���� ����������, �������� � ���� "������" � "������������� ������", ����������, ����� �����������'.
                '        ��������� � ����� ���������� ������ ������ ������.'.
                '     <P>����� �������������� ���������� �������� ����� � ������ �� �������� �� ����.'.
                '        � ������ "���������" ����� ������ �������������� ���������� � ����,'.
                '        �������� ���������� � ��������� ��������� ��� ���������� ����.'.
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
