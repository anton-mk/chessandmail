<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="text_doc">'.
                '   <H2 id="title_doc">��� ������ ������</H2>'.
                '   <DIV style="text-align:justify">'.
                '     <BR>'.
                '     <P>�� ����� ChessAndMail ��� ������ �������� � ��������� �������. ��� ���� �� ��������� �� ����� ���������������'.
                '        ����� � ��������� ����, ��� ���� on-line � ��������� �����. ��������� � �������� ������� �����'.
                '        �������� � ������� "����������". ������ �� ��������� ����� ��� � �������������� ������������� �������,'.
                '        ��� � ��� ����. ������ ��� ������������� ������������� ������� �������� �������� A ������,'.
                '        � �������������� C ������. On-line ������ �������� �������� B ������.'.
                '     <H3 id="title_2_doc">������� ���� � ������� �� ���������</H3>'.
                '     <DIV style="float: right">'.
                '        <DIV><IMG src="doc/Image/access_tournaments.png" style="float:none; border: 1px solid white"></DIV>'.
                '        <DIV><IMG src="doc/Image/tournament_.png" style="float:none; border: 1px solid white; margin-top: 4px"></DIV>'.
                '        <DIV><IMG src="doc/Image/table_tournament.png" style="float:none; border: 1px solid white; margin-top: 4px"></DIV>'.
                '        <DIV><IMG src="doc/Image/table_tournament_2.png" style="float:none; border: 1px solid white; margin-top: 4px"></DIV>'.
                '        <DIV><IMG src="doc/Image/games_.png" style="float:none; border: 1px solid white; margin-top: 4px"></DIV>'.
                '        <DIV><IMG src="doc/Image/table_games.png" style="float:none; border: 1px solid white; margin-top: 4px"></DIV>'.
                '     </DIV>'.
                '     <P>����� ������� ������ ������ ������ �� ����� � ������� �� ��������� - ������� ������� � �������.'.
                '        ������ ������ �� ����� �������� ��� �������, ��� �������, ���� ������. ����� ���������� ������'.
                '        ��������� �������� ����� ������ �� ������� ������� "�������" ��� �� ������ ����� � ��������.'.
                '        �������� ���� �������, ��� �������� "���������".'.
                '     <P>������ �������� ������������ � ���� ������, ������� ������������� �� �������. ������ �������'.
                '        �������� ����� �������. ����� �� ���� ����� ����� ������� ������� ������ ������� �������.'.
                '        ������ ������� ���������� �������, ������� ��� ��������� ������� � �������.'.
                '        ���� �� ������ ���� ������� ������� ������� ������ "������� �������" � �� ����������� ��������'.
                '        ����������� ���� �������.'.
                '     <P>����� ������������� ������� ������� �������, � ������� �����'.
                '        �������� ���� ��� (�����). ������� � ����� ������� ����� ����� �� ������, ����������� �� �����������'.
                '        ������ � ����� ������ � �������� � ������� ���������. ����� ������������� ��� ���� ������,'.
                '        ��� � ������ ������ �������, ������� �� ������ ����������� �� ����������� ������ � ������ ������'.
                '        ������ � �������� � ������� �������.'.
                '     <P>������� � ������� ����� �� ������ ����� ��������� �������, �� � ����� ������ "������".'.
                '        ��� ����� ����� ������� ������� ����� ������� ��� ������ ����� � ��������. �������� ����'.
                '        �������. ����� "��������" ���������� �������������� ��� ��������� ����. ������ ������ ������������'.
                '        � ���� �������. ������ ������� ������� ���������� ����� ������, ����� �� ������� ���������� �������'.
                '        ��������������� � ����� ������.'.

                '     <H3 id="title_2_doc" style="clear:both">������ �� ���� �� ���������</H3>'.
                '     <DIV style="float: right">'.
                '        <DIV><IMG src="doc/Image/calls_.png" style="float:none; border: 1px solid white"></DIV>'.
                '        <DIV><IMG src="doc/Image/table_calls.png" style="float:none; border: 1px solid white; margin-top: 4px"></DIV>'.
                '     </DIV>'.
                '     <P>��� ���� ������ ������ ���� - ������� ��� ��������� �����.'.
                '        ����� �� ������� ������� "������" ��� �� ������ ����� � ��������, �� �������� �� �������� �������.'.
                '        ���������� � ������� ������������ � ���� �������. ������ ������ "�������", ����������� �� ������ '.
                '        ������� �������, � �� ����������� �������� ���������� ���� �������, ������� �����.'.
                '        ���� ��� ����������� ������, �� ������ ���� ��� �������. ��� ����� ����� ������ �� ������'.
                '        "������� �����", � � ����������� ���� ������ ��������� ������� ���.'.
                '        ����� ��� ����� ����� ���-�� ������ ������ �������� �������������.'.
                '     <P>������ ���������� ����������� ������� ����� �������� � ������� "������".'.

                '     <H3 id="title_2_doc" style="clear:both">������ �� on-line ���� (���� ������ ��������� �������)</H3>'.
                '     <DIV style="float: right">'.
                '        <DIV><IMG src="doc/Image/begin_game_on_line.png" style="float:none; border: 1px solid white"></DIV>'.
                '     </DIV>'.
                '     <P>��� ���� � ������ on-line ��� ����� ����� ���������� ���� DHTML, ������� ��������� � �������'.
                '        "���������", ����� "dhtml".'.
                '     <P>������ ������ � ������ ��������� ������� ����� ������ �������� ��� ������ ����� B ������.'.
                '        �������� � �������� ����� ������ �������� � ������ "������" �������� "on-line".'.
                '        ������ ������������ � ���� �������, ������ � ������� ���������� ������ � �������� ������� �� ����'.
                '        �� ���������. ����� �������� ���� ������ ��� �������� ������ ������, �������� ����, �������������,'.
                '        ��� ������ ��������. ��� �������� � ������ ����� ������ �� ����� ���� ������, �����������'.
                '        � ������ ������� �������, ������������ ����.'.
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

      CPage_::$menu_[2]['link'] = 'doc_.php?link_=reglaments_';
      CPage_::$menu_[2]['image'] ='Image/forward_doc.png';
      CPage_::$menu_[2]['submit'] =false;
      CPage_::$menu_[2]['level'] =1;
      CPage_::$menu_[2]['active'] ='N';

      CPage_::$menu_[3]['link'] = 'doc_.php?link_=registration_';
      CPage_::$menu_[3]['image'] ='Image/back_doc.png';
      CPage_::$menu_[3]['submit'] =false;
      CPage_::$menu_[3]['level'] =1;
      CPage_::$menu_[3]['active'] ='N';
   }//Make_Menu_
?>