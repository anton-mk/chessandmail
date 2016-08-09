<?php
   require_once('const_.php');

   function Body_(){
      $result_ ='<SPAN id="text_doc">'.
                '   <H2 id="title_doc">������ � �������</H2>'.
                '   <DIV style="text-align:justify">'.
                '     <H3 id="title_2_doc">������ ���� � �������</H3>'.
                '     <P>������� ������, ������������������� �� ChessAndMail, ������������� ��� ������.'.
                '        ������ �������� ������� ����. '.
                '        ����� A �������� ������� ���� �� ��������� ��� ������������� ������������� �������.'.
                '        ����� B �������� ������� ���� � ������ on-line.'.
                '        ����� C �������� ������� ���� �� ��������� c �������������� ������������� �������.'.
                '        �����, ���������� ����� ������ ������� ���� �� ���������, ��� A8 � C8. � ������ on-line B8.'.
                '        A1, B1, C1 - ������, ���������� ����� ������� ������� ����.'.
                '        ����� ���� ���������� ��� ������ A2-A7, B2-B7 � C2-C7.'.
                '        ��� ������ �����, ��� ���� ������� ����.'.
                '     <P>������� ����� ������������������� ������ ������������� ����� A8/B8/C8.'.
                '        ����� �������� ����� 8 ����� ������� ���� ������, ������������������ ����� 10 �����,'.
                '        ��������� �������� �� �����. ��� ����������� ��������� ������ ���������� ������� � ��������.'.
                '     <P>������� ����� �������������� �� ������ A1-A8, B1-B8 � C1-C8.'.
                '        ������ �������� ������ ������� ��� ������. ��������, ���� � ������ ����� A8, ��� ����� ��������'.
                '        ������ ������� ������ A8. ���� �� ������ ������ ����� � �������, �� � ��� �� �������� ���, �� ���'.
                '        ������������� ����� � ������ �� 1 ������, ��� ����� �������. ��������, ���� ����� ����� ������'.
                '        ����� � ������� ������ A7, �� �� ��������� ������� ��� ����� �������� ����� A6. ���� ����� �����'.
                '        ������ ����� � �������� ������ 1 ��� 8, ����� ������ �� ���������. ���� ����� ����� ���������'.
                '        ����� � �������, �� � ��� �� �������� ���, �� ��� ����� ����� ����� ������� �� ����� � ������'.
                '        �� ������� ������ ��� ����� �������. ��������, ���� ����� ����� ��������� ����� � ������� A6, ���'.
                '        ����� �������� ����� A7. ���� ����� ����� ��������� ����� � ������� ������ 7 ��� 8, ���������'.
                '        ������ �� ���������. ����� 7, �� ����� ���� ������� �� ��� ����� ���������������.'.
                '     <P>��������� ������� ����� ����� �������� ��������� ��� ���������� ������ ������. ��� �������'.
                '        ���� ���������� ��� ����. �� ������ �������� ����� ������� ������ � ������� �� ������'.
                '        ������. ��� ��������, ��������, � ����� ������: ��� ����������� �������� ��� �������� ����� 8, ��'.
                '        ��������� ������� � ������� ������ 8, �� ����� ������� ����� ���������� �� 7, ���������'.
                '        ������� � ������� ������ 7, � ������� �� �������� ������ �����, ����� ���������� �� 6, � ������'.
                '        ������ 8 ��� �� ����������. � ����� ��������� ����� ����� ���� ������� ��� ������� ����� �� '.
                '        ��������� ��������, ��������, ����� ������ 5, �� ������ � �������� ������ 5 � 6. � ������� ������ 6 ��'.
                '        �������� ��������� �����, ����� ����� ������� �� 7. ����� ����� �� �������� ������ ����� � �������'.
                '        ������ 5, ����� ������ ����� ������� � 7 �� 4. �������� � ����� �������� ����� ����� �����'.
                '        ������ ����� � �������, � ��� ����� �� ���������, ��������, ���� ����� ������ 5 ������ ������ �����'.
                '        � ������� ������ 6.'.
                '     <H3 id="title_2_doc">�������</H3>'.
                '     <P>��� ��������� ������ ���� � ���������� � ������� �������������� �������.'.
		'           ������� A �������� ������� ���� � ������� �� ��������� ��� ������������� ������������� �������.'.
                '           ������� B �������� ������� ���� on-line.'.
                '           ������� C ������� ���� �� ��������� � �������������� ������������� �������.'.
		'           ������� ������, ����� ����������� �� �����, ������������� ������� A900/B900/C900. ��� ����������'.
                '           ����������� ������,	� ������� ������� �� ����� 10 �����, ������� ���������������. � ��������'.
                '           ������ ������ �������� ������������.'.
                '     <P>������� ��������������� �� �������: Ra_��� = Ra + K*(Sa-Ea)'.
		'           <TABLE>'.
		'			<COL span="4">'.
		'			<TR><TD style="vertical-align:top">���</TD><TD style="vertical-align:top">Ra_���</TD><TD style="vertical-align:top">-</TD>'.
		'                                <TD style="vertical-align:top">'.
		'					������� ������ ����� ���������� ������, ��������� �������� ��������� �� ������'.
                '                                       ����� �� �������������� ��������,'.
		'				</TD>'.
		'                       </TR>'.
		'                       <TR><TD style="vertical-align:top">&nbsp;</TD><TD style="vertical-align:top">Ra</TD><TD style="vertical-align:top">-</TD>'.
                '                                <TD style="vertical-align:top">������� ������ �� ���������� ������,</TD>'.
                '                       </TR>'.
		'			<TR><TD style="vertical-align:top">&nbsp;</TD><TD style="vertical-align:top">K</TD><TD style="vertical-align:top">-</TD>'.
		'				<TD style="vertical-align:top">'.
		'					����������� ������ 25 ���� ����� ������ ����� ���������� ������ 8,'.
                '                                       10 ���� Ra >= 2400, 15 ���� Ra < 2400'.
		'				</TD>'.
		'			</TR>'.
		'			<TR><TD style="vertical-align:top">&nbsp;</TD><TD style="vertical-align:top">Sa</TD><TD style="vertical-align:top">-</TD>'.
		'				 <TD style="vertical-align:top">'.
		'					1 ���� ����� ������� ������, 0.5 ���� ������ ������, 0 ���� ��������'.
		'				</TD>'.
		'			</TR>'.
		'			<TR><TD style="vertical-align:top">&nbsp;</TD><TD style="vertical-align:top">Ea</TD><TD style="vertical-align:top">-</TD>'.
		'				 <TD style="vertical-align:top">'.
		'					��������� ��������� ������, ���������� �� ����� �� �������������� ��������.<BR/>'.
		'					Ea ������� ������ �� �������� ������� �� ���������� ������.<BR/>'.
		'					���� ������� ������, ��� �������� ����������� Ea, ���������� ��� Ra, � ������� ���'.
                '                                       ��������� ��� Rb, �� Ea=1/(1+10^((Rb-Ra)/400))'.
		'                                 </TD>'.
		'                        </TR>'.
		'	    </TABLE>'.
		'     <P>������ ������� 1<BR>'.
		'	    ������ ������ A ������ ������ B ����������� ��������� ������ A.'.
		'           ������ A � B �� ���������� ������ ����� �������� 900. ����� ����� ������� ����� ����������'.
                '           ������ 7. ����� ���������� ������ ������� ������ A ��������� ��������� �������:<BR/>'.
		'           Ea=1 /(1+10^((900-900)/400))=0.5<BR/>'.
		'           Ra_���=900 + 15*(1-0,5)=900+7.5, ����� ���������� ��������� �������� Ra_��� = 908<BR/>'.
		'           ������� ������ B ��������� ��������� �������:<BR/>'.
		'           Ea =0.5<BR/>'.
		'           Ra_��� =900 +15*(0-0.5)=900-7.5, ����� ���������� ��������� �������� Ra_��� = 892'.
                '     <P>������ ������� 2<BR>'.
		'           ������ ������ A ������ ������ B ����������� ��������� ������ A.'.
		'           ������ A ����� ������� 2000 � ����� 3, ����� B ����� ������� 900 � ����� 7.'.
		'           ����� ���������� ������ ������� ������ A ��������� ��������� �������:<BR/>'.
		'           Ea=1 /(1+10^((900-2000)/400))=0.998..., ����� ���������� Ea=1<BR/>'.
		'           Ra_���=2000 + 15*(1-1)=2000, ������� ������ A ����� ���������� ������ �� ���������<BR/>'.
		'           ������� ������ B ��������� ��������� �������:<BR/>'.
		'           Ea=1 /(1+10^((2000-900)/400))=0.001..., ����� ���������� �������� Ea=0<BR/>'.
		'           Ra_��� =900 +15*(0-0)=900, ������� ������ B ����� ���������� ������ �� ���������'.
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

      CPage_::$menu_[2]['link'] = 'doc_.php?link_=doc_info';
      CPage_::$menu_[2]['image'] ='Image/forward_doc.png';
      CPage_::$menu_[2]['submit'] =false;
      CPage_::$menu_[2]['level'] =1;
      CPage_::$menu_[2]['active'] ='N';

      CPage_::$menu_[3]['link'] = 'doc_.php?link_=message_in_time_game';
      CPage_::$menu_[3]['image'] ='Image/back_doc.png';
      CPage_::$menu_[3]['submit'] =false;
      CPage_::$menu_[3]['level'] =1;
      CPage_::$menu_[3]['active'] ='N';
   }//Make_Menu_
?>