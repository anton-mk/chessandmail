<?php
   require_once('const_.php');

   class CWork_{
        public static function Body_($type_){
            switch ($type_){
                case 0 : $result_ ='<SPAN id="text_doc">'.
                                   '  <H2 id="title_doc">�������������� ������ ����������� ��������� ����� � �����</H2>'.
                                   '  <P>'.
                                   '   ����� ��������� �������������� ������ ����������� ��������� ����� � �����.'.
                                   '   ���� �� �����������, ���������� �������� �������� ��� �� e-mail ��� ����������� � �������'.
                                   '   jpg �������� 68�68 ����� "�����" ����� �� "�����" ������ � ����� "������" ������ �� "������"'.
                                   '   ������. <a href="info_title_page.php?link_=work_&add=page1">������ �����...</a>'.
                                   '</SPAN>';
                         break;
                case 1 : $result_ ='<SPAN id="text_doc">'.
                                   '  <H2 id="title_doc">�������������� ������ ����������� ��������� ����� � �����</H2>'.
                                   '  <P>'.
                                   '   ����� ��������� �������������� ����� ����������� ��������� ����� � �����. ����'.
                                   '   �� �����������, ���������� �������� �������� ��� �� e-mail ��� ����������� � �������'.
                                   '   jpg �������� 68�68 ����� "�����" ����� �� "�����" ������ � ����� "������" ������'.
                                   '   �� "������" ������. � ������ ������� ����� ������� �� ������ �������� �� 6 �����������'.
                                   '   "�����" ����� � ���������� �����, 6 ����������� "������" ����� � ���������� �����,'.
                                   '   ��� ����������� ������ ������ "������" � "�����", 16 ����������� ��������� �����.'.
                                   '   <P>'.
                                   '   � ���������, ��������� �������� ���� ������� ��� ����� ��� ��� ���� �����������,'.
                                   '   �� ���������� �������������� ��������� ���������:<br>'.
                                   '   1. ������������ ����������� - ��������� ��� ����������� � ���������� �� �����������'.
                                   '   ����������� �� ������ ������, � ������ ������� ����� ������ ���� ��� ��������'.
                                   '   �������� ����� - ���������, �� ���������� ����� ����� ������ ����� ������'.
                                   '   ����������.<br>'.
                                   '   2. ����������� ������ ��������� ��������� �� ������ ��������� � ����������'.
                                   '   ��������� �����������, �� ��������� � ����� ������ � �� "������" �����.'.
                                   '   ��� ����� ������-���������� ������, �� ��� ������...'.
                                   '   <P>'.
                                   '   � �������� ������� ���������������� ������� 1 � 2 ���� �������� ����� �����,'.
                                   '   ������������� ����� "��������� �������":'.
                                   '  <DIV style="float: left">'.
                                   '      <img src="Info/Image/chess_planet_bpb.jpg" width="68" height="68" style="border: none" alt="����������� ��������� �����"   title="����������� ��������� �����"/>'.
                                   '      <img src="Info/Image/chess_planet_bnb.jpg" width="68" height="68" style="border: none" alt="����������� ���������� ����"   title="����������� ���������� ����"/>'.
                                   '      <img src="Info/Image/chess_planet_bbb.jpg" width="68" height="68" style="border: none" alt="����������� ���������� �����"  title="����������� ���������� �����"/>'.
                                   '      <img src="Info/Image/chess_planet_brb.jpg" width="68" height="68" style="border: none" alt="����������� ��������� �����"   title="����������� ��������� �����"/>'.
                                   '      <img src="Info/Image/chess_planet_bqb.jpg" width="68" height="68" style="border: none" alt="����������� ���������� �����"  title="����������� ���������� �����"/>'.
                                   '      <img src="Info/Image/chess_planet_bkb.jpg" width="68" height="68" style="border: none" alt="����������� ���������� ������" title="����������� ���������� ������"/>'.
                                   '  </DIV>'.
                                   '  <DIV style="clear:both">&nbsp;</DIV>'.
                                   '  <DIV style="float: left">'.
                                   '      <img src="Info/Image/chess_planet_wpw.jpg" width="68" height="68" style="border: none" alt="����������� ��������� �����"   title="����������� ��������� �����"/>'.
                                   '      <img src="Info/Image/chess_planet_wnw.jpg" width="68" height="68" style="border: none" alt="����������� ���������� ����"   title="����������� ���������� ����"/>'.
                                   '      <img src="Info/Image/chess_planet_wbw.jpg" width="68" height="68" style="border: none" alt="����������� ���������� �����"  title="����������� ���������� �����"/>'.
                                   '      <img src="Info/Image/chess_planet_wrw.jpg" width="68" height="68" style="border: none" alt="����������� ��������� �����"   title="����������� ��������� �����"/>'.
                                   '      <img src="Info/Image/chess_planet_wqw.jpg" width="68" height="68" style="border: none" alt="����������� ���������� �����"  title="����������� ���������� �����"/>'.
                                   '      <img src="Info/Image/chess_planet_wkw.jpg" width="68" height="68" style="border: none" alt="����������� ���������� ������" title="����������� ���������� ������"/>'.
                                   '  </DIV>'.
                                   '  <DIV style="clear:both">&nbsp;</DIV>'.
                                   '  <P>'.
                                   '  � �������� ������� ���������������� ������ 1 �� �� ���������������� ������ 2 ����'.
                                   '  �������� ������ ��������� ����� ������� ������������ �� ����� ������ � ���������'.
                                   '  ��������� ������:'.
                                   '  <DIV style="float: left">'.
                                   '      <img src="Image/bpb.jpg" width="68" height="68" style="border: none" alt="����������� ��������� �����"   title="����������� ��������� �����"/>'.
                                   '      <img src="Image/bnb.jpg" width="68" height="68" style="border: none" alt="����������� ���������� ����"   title="����������� ���������� ����"/>'.
                                   '      <img src="Image/bbb.jpg" width="68" height="68" style="border: none" alt="����������� ���������� �����"  title="����������� ���������� �����"/>'.
                                   '      <img src="Image/brb.jpg" width="68" height="68" style="border: none" alt="����������� ��������� �����"   title="����������� ��������� �����"/>'.
                                   '      <img src="Image/bqb.jpg" width="68" height="68" style="border: none" alt="����������� ���������� �����"  title="����������� ���������� �����"/>'.
                                   '      <img src="Image/bkb.jpg" width="68" height="68" style="border: none" alt="����������� ���������� ������" title="����������� ���������� ������"/>'.
                                   '  </DIV>'.
                                   '  <DIV style="clear:both">&nbsp;</DIV>'.
                                   '  <DIV style="float: left">'.
                                   '      <img src="Image/wpw.jpg" width="68" height="68" style="border: none" alt="����������� ��������� �����"   title="����������� ��������� �����"/>'.
                                   '      <img src="Image/wnw.jpg" width="68" height="68" style="border: none" alt="����������� ���������� ����"   title="����������� ���������� ����"/>'.
                                   '      <img src="Image/wbw.jpg" width="68" height="68" style="border: none" alt="����������� ���������� �����"  title="����������� ���������� �����"/>'.
                                   '      <img src="Image/wrw.jpg" width="68" height="68" style="border: none" alt="����������� ��������� �����"   title="����������� ��������� �����"/>'.
                                   '      <img src="Image/wqw.jpg" width="68" height="68" style="border: none" alt="����������� ���������� �����"  title="����������� ���������� �����"/>'.
                                   '      <img src="Image/wkw.jpg" width="68" height="68" style="border: none" alt="����������� ���������� ������" title="����������� ���������� ������"/>'.
                                   '  </DIV>'.
                                   '  <DIV style="clear:both">&nbsp;</DIV>'.
                                   '  <DIV style="text-align:right">'.
                                   '    ��� ������� � ������� �������� ����� ���������� ��� �� ������ anton-mk@yandex.ru<br>'.
                                   '    ����������� ����� ����������'.
                                   '  </DIV>'.
                                   '</SPAN>';
                         break;
            }#switch
            return $result_;

        }#Body_

        public static function MakePage(){
            if (isset($_GET['add']) && ($_GET['add'] =='page1')){
              $type_=1;
              CPage_::$title_ ='ChessAndMail. ��������� �������������� ����� ����������� ��������� ����� � �����.';
             }else{
              $type_=0;
              CPage_::$title_ ='ChessAndMail. ��������, ����������� ������.';
            }
            $body_ =CWork_::Body_($type_).
                    CPage_::get_metrika_yandex();
            CPage_::$header_ ='<DIV id="text_header_">'.
                              '  ����������� ������'.
                              '</DIV>';
            CPage_::MakeMenu_interesting(11);
            CPage_::$body_ =$body_;
            CPage_::MakePage();
        }#MakePage
   }#CWork_
?>