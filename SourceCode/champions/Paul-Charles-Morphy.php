<?php
  require_once('../const_.php');
  
  session_name(NAME_SESSION_);
  session_start();

  CPage_::$root_catalog ='../';
  $link_begin =(isset($_SESSION[SESSION_ID_]) ? CPage_::$root_catalog.'MainPage.php?link_=Events' : 'http://chessandmail.ru');  
  
  CPage_::$header_ ='<h1>��� ������ �����</h1>';
  $body_ ='<div class="biography">'.
          ' <image src="Image/paul-charles-morphy.png" alt="��� ������ �����" title="��� ������ �����">'.
          ' <blockquote>'.
          '   ����� ���� �� ������� ����� ���� �����. � ���� ����� �� ������� ���������� �� ������ ��������������'.
          '   ��������, �� � ����������� ���������, ���������������� ����, ����� ����������...'.
          ' </blockquote>'.
          ' <div class="signature">'.
          '   ������� �������'.
          ' </div>'.
          ' <div class="introduction">'.
          '   �������������� ����� � ����������� ���������� ����� �� �����  ������� ������������  ���� ����� ������������'.
          '   ������������ � ����� 1980 �. ������ � ����������� �� ����� ����������� ������������� ���� ������'.
          '   ���������� ������ ����� �����.'.
          ' </div>'.
          ' <div class="links_">'.
          '   <div><a href="books/narrative-of-Morphy.fb2">����� ������� ������������ �������� � ����� � ������� fb2</a></div>'.
          '   <div><a href="Morphy-table-games.php">������ ���� �����, ������� ����� ����� �� �����</a></div>'.          
          ' </div>'.
          ' <p><a href="young-Morphy.php">'.
          '      ��� ������ ����� ������� 22 ���� 1837 ���� � ������� � ����� �������, � ����� ����� ������ �����.'.
          '    </a>'.
          ' <p>� 1850 ���� � 12 ��� ��� ������� � ����� ����� ������'.
          '    <a href="match-Morphy-Levental.php">���� � ��������� ����������� ���� ������� �������� ����������</a>.'.
          '    � ���� ������� ��� ������� ������, ��������� ����������� � �����.'.
          ' <p>������� <a href="Morphy-college-St-Joseph.php">������� ���-����� � ������� 1854 ����</a>'.
          '    � ���������� ���������. �������� ��� ������� ��� ���� ����� ���������, ����� ����������'.
          '    ��� ������, ����������� � ������ � ������� �����������.'.
          ' <p>� 1856 ���� �������� ���� ��� ��������'.
          '    <a href="Morphy-University.php">������������ ���������� ������������ ������������</a>,'.
          '    ������ ���������� ���� �� ��� ����. ��� ���� ��������� ������ �������� �������� � ������.'.
          '    � ������� ���������, ��� ���� ������ �����, ������, ����� ����� ������������ � �������� ��������'.
          '    �� ���� ���������� ����������� ������.'.
          ' <p><a href="Morphy-death-father.php">22 ������ 1856 ���� ���� ���� ���� ������� �����.</a>'.
          '    �������� ����� ��������� ���� �� ���������� ������ �� �������� ������� �������� � ����� �������.'.
          ' <p>6 ������� 1857 ���� � ������ ���-���� ��������'.
          '    <a href="Morphy-open-American-chess-Congress.php">'.
          '      ������ ��������������� ������������ ��������� �������, � ������� ��� ����� ������� ����������� ������� �������'.
          '    </a>.'.
          '    �������� ��������� �������� ����� �� �������������. ����� ��� ����������� ����� �� ��������������.'.
          '    ��������� ���� ��������� �� ����, ������ ������ � ������ ���� ������ ���� �� ���� ���������� ������.'.
          '    ����������� ������� �� �������, � ���������� ������� � ��������� ����, ��� ���� ��� �� ��� �� ��������.'.
          '    ����� ����������� ������� ���� �� ���� ���������� ������. ����� �������� ������ ���� ������,'.
          '    ������� � ������ ����� �������� (+3-0=0), �� 2-� - ���� (+3-0=0), � 3-� - ����������� (+3-0=1).'.          
          '    <a href="Morphy-final-American-chess-Congress.php">'.
          '      �������������� ���� ��� �������� ������ � ����������. ����� ������� ���� ���� �� ������ (+5-1=2).'.
          '    </a>'.
          ' <p>�� ��������� ������� ������� ������� ������� ��� �� ��������� ����� � ���-�����.'.
          '    <a href="Morphy-after-American-chess-Congress.php">��������� � �����'.
          '      �����, �� �������, ��� ����� ������ ������ ������������� ���������� ����� � ��� ������.'.
          '    </a>'.
          '    ��� ����� �� ���� �� ���� ���������� ������ ������ ���������� ����� ���-�������� ���������� ����� �. ������,'.
          '    �� ��� ����� ����� ������ �� ��������� �� ���������� ����, ��� �� ���� ������������. ����� ����,'.
          '    ����� ����� � ������������ ���������� ������ �����. �� ���� ���� ������������ ��������� �����������'.
          '    ������������� ���� ���� ��������� ���������, ��� ��������� �������������� ��� �������.<br/>'.
          '    � ���� ������ ����� ������ � ���-����� 100 ������ �� ������ (+87-5=8). � ����� ��� �����������'.
          '    ���� ����� ������� ������, ��� �. ��������, �. ������, ��. �������, �. ����������. ����� ����, ��'.
          '    ������ 161 ������ �� ���� ������ (+107-36=18). ����� ��� ����������� ��� ��� ������� �������, � ���'.
          '    ����� � ������.'.
          ' <p>���������� � ����� ������ ��� ������ � ������ ������� ���������� ����� ������ �������� �����������'.  
          '    ����������� ���������� ����������� ������� ��������� ������� � ���� �������. ��� �������'.
          '    �� �������������: ��� ������� ���������� ����������� ���� ���������. ������ ��� ����� ������� �����������'.
          '    ����������� � �������� ����� �� ��������� ������ ����������� ���������� �����, ������� ���� ��������'.
          '    �������� � ���� - ���� 1858 ���� � ������ ����������. 26 ��� 1858 ����, ��� ������ �� ������ �������'.
          '    �� �������� �������. 8 ���� �� ������ � ���-����, ������ 9 ���� �� ������������������ ������� ��������'.
          '    ��������� � ������ ����. 21 ���� 1858 ���� �� ������ � ������, ��� ����� � �������� ������ �� ������.'.
          '    <a href="Morphy-England-1858.php">'.
          '      � �������� ������ ����������� ���������� ����� �� ������ ��� ������ � ������� ����������� �������� �'.
          '      ������� ���� �������������.'.
          '    </a>'.
          '    ������, ���� � �������� ���������� �� ���������.'.
          ' <p>19 ���� 1858 ���� �������'.
          '    <a href="Morphy-Levental-1858.php">'.
          '      ���� ����� � ���������� ������� �������� ����������'.
          '    </a>,'.
          '    ������� � ��� �����'.
          '    �������� � ������. �� ����� ����� ����� � ���������� � 1850 ���� � ����� ��������. ������ ����������'.
          '    ��������� ��� �� ���� ����� ���. ��� � ������ ��� ����� �������. ��������� �����: +10-4=5. ����� �����'.
          '    ����� � ����� ��������� ������ ������� � ������� �������������� ���������� ������, � ������� �� ������ ������� � ��������� � '.
          '    �������� ������� ������.'.
          ' <p><a href="Morphy-blindfold-1858.php">'.
          '    ����� �� ����� � ������� �������������� ���������� ������, ������� �������� � ����������, �� ����� ��� ��������� �� ���������'.
          '    ���������� ������� ����� ������������� ���� � ������ �� ������ ������.'.
          '    </a>'.
          '    �������, ����� � ���������� ������� ���� �����, �� ��������� 5 ������ ������ ���������� ���������� ��'.
          '    ����� �������� �������. ��� ����� ����� ������� ���� ������ 8 ������� �����������'.
          '    � �������� ����� �� ������ +6-1=1. �� ���� ��� ����� �� ���� ���� ������ �� �����.'.
          '    ����� ���������� ������� � ������� �� ���������� ��� ������ ���� �� ����'.
          '    ����� � ���� ������ � ����� �� ���������� ����������� ������, ����������� ������, �������'.
          '    ����� ��� ����������� �������. ���� ���������� �� ������ +5-0=2 � ������ �����.'.
          '    ��� ���������� ���������� ������ ���� ���������, �������� ����������� ������� ����'.
          '    � �����.'.
          ' <p><a href="Morphy-Paris-1858.php">'.
          '      ������ 1858 ���� ��� ������ ����� ��������� � �����, ��� ����� ����� ������������ �����,'.
          '      ��� ����� ��������� ������ ��������� ������� ������ �������.'.          
          '    </a>'.
          '    ������� �������� ��� ������������ �������: ����� ������ ��� ������� ��� ������ ������ ��������, �'.
          '    ����� �� ����� ��� �� ����������, � ���� ������ ���� ����������� � ������� ����������� ���� ����'.
          '    (��� ����� �� �����) � ����������� ��������. ����� ������ ��� ������� � ���� ���������.'.
          '    ��� ����� +5-2=1 � ������ ����� �������, ���������� �� ������ �����������, ���� ����.'.
          ' <p>��� �� ���������� ����� � ���������, ��� ����� +4-2=0 ��� ���� ����� ������������� ���� ������� 8'.
          '    ���������� ����������� �������: ����, �������, ���������, ������, ������, �����, �����, ������.'.
          '    ����� 10 ������� ���� ����� ���������� �� ������ +6-0=2 � ������ �����.'.
          ' <p>�� �������� ���� ����� � ��������� (������� �������� �� ��������� ���������) ���'.
          '    ������� ������� �� ��������� � ����� ����������� ���������� �������� ������� ��������� � ���������'.
          '    ��� ������� ����.'.
          '    <a href="Morphy-Anderson.php">'.
          '      �������� ������ �����, � � ������� 1858 ���� � ������ ���� ���������'.
          '    </a>,'.
          '    ������� ���������� ������� ����� �� ������ +7-2=2. ����� �������� � �������� �������� � �����'.
          '    ������������ ������� ��� ����� ������ ������, ������� ���������� ������ ���� ���������.'.
          '    ���� ����-���� ������ ��� � ��������� ���� � ���������� ������� ����� �� ������ 5:1.'.
          '    ������� ������ ��� ���������� ����������, ��� ����� ������ ��������� ���� ��������� ���� �'.
          '    ������������� ����������� ���������� ����� ��������� ������������, ������� ���������� ��� �����'.
          '    ������ � �����. ���� ��������� � ��������� ������� �� ����, � �������� ����������� � ����������'.
          '    ������� ����� �� ������ +7-0=1.'.
          ' <p>10 ������ 1859 ���� ��� ����� ������� � ������. �� �������� �� ������ ��� ���������������� ������'.
          '    ������ ���������. ��������� � ����� ����� �����, ��������� �� ���������� ����� ��������� ����. ���'.
          '    ������ ����� � ����� ��������. ����� �������� �����, ��� ��� � ����� ����-������ ����� �������������'.
          '    ���� ������� 8 ���������� ����������� ������: ������, ������, ���������, �������, �����������, �������,'.
          '    �����, ��������. ����� ������ 7 �����, ����� ������� � ����� � ��������, � ���������� ������ ������.'.
          '    ����������� ������������ ������ ������ ��� ��������� �����. ������� � ��� ����������� ���������'.
          '    ���������� ���������� ��������� � ��������. ����� ������ 5 ����� � ���������� �� ������ +6-0=2 �'.
          '    ������ �����. ������� ����� � ���� ��������� ��������� � �������� ���������� ��������� �� � ����-����'.
          '    ����� ��������� ���� - ����-����������.'.
          '    <a href="Morphy-Multy-board-chess-play.php">'.
          '      �� �������� ����� � ������ � ������� ����� ���� ����� ����������� ���� ��������� ���� ����������'.
          '      ����������� �������.'.
          '    </a>'.
          '    � ������ ������� ������� ���������, ������, �����, ���� � �����. ��� ������� � ����� � �������, ������'.
          '    ����� � ���������� � ������� � �������� ������. 30 ������ 1859 ���� �� �������� ��������� ��� �����'.
          '    ������ � �������.'.
          ' <p>����� �������� �� ������ 10 ��� 1859 �. � ��� �������� ������������� ������� ����������� � �����������.'.
          '    ������������ ��������� ����� � �������� ��������� ���������� ��� � ����, ��� ��� ��, ��� ���� �����'.
          '    �������, �� ��� ������������� ���� �������� ������ � �������� ���.'.
          '    <a href="Morphy-New-York-1859.php">'.
          '      ������ 1858 �. � ���-����� ��� ��������� ������� ��� ���������� ������������� ������� �����'.
          '    </a>, � 25 ��� 1859 �. � ����� ���� ���-�������� ������������ ���� ������� � ���� �������������'.
          '    ����������� ��������� ����� � �������� � ������������ �������� ��� ������ ����� � ������'.
          '    ������������� ����������, � ����������� ��������� �������������� �����, ��������� � ��������������.'.
          '    � ��� ���-������� ������ ����������� ��� ������� ����, �� ���������� ������� ����� ���� ��������'.
          '    ���������� ���������. ����� ���������� � � ������ �������, �� �� ��� �� ����� ������ ���������'.
          '    ������ � ����������� ������ ������ � ����� ������ ���� ��� �����. � ��������� ����������� �������'.
          '    �� ��������� �����������. ���������� � ����� ������, ����� ������ ��� �� ����� 1860 ����, ���������'.
          '    ������������ �������. ������ �� ����� ������� � ���-����, �� ��� ����� �������� �� ��������� �������,'.
          '    ������ ������� ����� ��������� ������.'.
          ' <p><a href="Morphy-Civil-War-1861.php">'.
          '      ����������  � 1861 ���� ����������� ����� ����� ������� � ���� �������� ��� ������ � ������� 1862 ����'.
          '      � �������</a>,'.
          '    ��� �� ������ ��������������� ����� � ������ ��� ������ ��������� ������. � ������� �����'.
          '    ������ ��������� ������ � ��������� ���� ������-����� ��������, �������� ������� ������ �������'.
          '    - �����, � ����� ������ ������� ������� �������� ������. �� ������ ����� ����� �� �������. � ������'.
          '    �� ����� ����� �����, ������� ������ �� ����� ������ ���� �� ��������, ���������� ��������� ��������'.
          '    ������� ������. �� ��������� ������ �� �������������. � ������� 1864 ���� �� �������� � ����� ������.'.
          '    � ����� �������, ����� ����� ������ �� ���� ������ � ���-��������, �� ����� ������ ������ �� ���� ������'.
          '    �� ����� ������ ������� �. ��������, �� ��������� ������ ��� �� ��������� ��������������.'.
          ' <p>��� ������� �� ����� ���������� ���������� ������� � ����� �������������. �� ��� ��� - � 1867 ����'.
          '    ����� � ������, ����� ���������� � ����������, �� ������� ��� ��� �����������. �� ����������� ��'.
          '    ������ �� ��������� ������� � ����� ����� � �������� ���� ������ �� ��� ������ ��� ������: ���'.
          '    ��������, ��� ��� ����� ��������. ����� 1869 ���� �� �� ����� ������ �� ����� ������.'.
          '    <a href="Morphy-the-last-years-of-life.php">'.
          '      ��� ����� ��������� 10 ���� 1884 ���� �� 48-� ���� �����.'.
          '    </a>'.
          '</div>'.
          
          '<div class="navigation">'.
          '  <a href="'.$link_begin.'">��������� ���� ChessAndMail</a>'.
          '</div>'.
          CPage_::get_metrika_yandex();
          

  CPage_::$title_ ='ChessAndMail: ��� ������ �����';
  CPage_::$add_file_style ='champions.css';
  CPage_::$body_ =$body_;
  CPage_::MakePage(); 
?>
