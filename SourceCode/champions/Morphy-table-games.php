<?php

 require_once('../const_.php');
  
  session_name(NAME_SESSION_);
  session_start();

  CPage_::$root_catalog ='../';
  $link_begin =(isset($_SESSION[SESSION_ID_]) ? CPage_::$root_catalog.'MainPage.php?link_=Events' : 'http://chessandmail.ru'); 
  
  CPage_::$header_ ='<h1>������ ���� �����</h1>';
  $body_='<div class="links-to-games">'.
         '  <table>'.
         '    <tr><th>���</th><th>�����</th><th>���������</th><th>������</th></tr>'.
         '    <tr><td>1849</td>'.
         '        <td>������ ������</td>'.
         '        <td>����� - �.����� (����)</td>'.
         '        <td><a href="Morphy-death-father.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1849</td>'.
         '        <td>����������� ������</td>'.
         '        <td>����� - �. �����</td>'.
         '        <td><a href="young-Morphy.php">������</a></td>'.
         '    </tr>'.
         '    <tr><td>1850</td>'.
         '        <td>������� ������</td>'.
         '        <td>����� - ���������</td>'.
         '        <td><a href="match-Morphy-Levental.php">������</a></td>'.
         '    </tr>'.
         '    <tr><td>1850</td>'.
         '        <td>������������ ������</td>'.
         '        <td>����� - ���������</td>'.
         '        <td><a href="match-Morphy-Levental.php">������</a></td>'.
         '    </tr>'.
         '    <tr><td>1855</td>'.
         '        <td>������ �����</td>'.
         '        <td>����� - ������<br/>(����� ��� ����� �� a1)</td>'.
         '        <td><a href="Morphy-college-St-Joseph.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1856</td>'.
         '        <td>������ ����</td>'.
         '        <td>����� - ����<br/>(����� ��� ����� �� a1 � ���� b1)</td>'.
         '        <td><a href="Morphy-University.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1857</td>'.
         '        <td>����������� ������</td>'.
         '        <td>������� - �����</td>'.
         '        <td><a href="Morphy-open-American-chess-Congress.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1857</td>'.
         '        <td>����������� ������</td>'.
         '        <td>���������� - �����</td>'.
         '        <td><a href="Morphy-final-American-chess-Congress.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1857</td>'.
         '        <td>������� ������</td>'.
         '        <td>����� - ����������</td>'.
         '        <td><a href="Morphy-final-American-chess-Congress.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1857</td>'.
         '        <td>����� ������� �����</td>'.
         '        <td>�������� - �����</td>'.
         '        <td><a href="Morphy-final-American-chess-Congress.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1857</td>'.
         '        <td>������ ������</td>'.
         '        <td>����� - ������</td>'.
         '        <td><a href="Morphy-after-American-chess-Congress.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>������ ��������</td>'.
         '        <td>����� - �����</td>'.
         '        <td><a href="Morphy-England-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>������ ��������</td>'.
         '        <td>����� - �����</td>'.
         '        <td><a href="Morphy-England-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>���������� ����������� ������</td>'.
         '        <td>����� - �����</td>'.
         '        <td><a href="Morphy-England-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>������ ��������</td>'.
         '        <td>���� - �����</td>'.
         '        <td><a href="Morphy-England-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>������ ��������</td>'.
         '        <td>��������+���� - �����+�����<br/>(�������� ��������������� ���� � ������)</td>'.
         '        <td><a href="Morphy-Levental-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>������ ��������</td>'.
         '        <td>��������� - �����<br/>(������ ������ �����)</td>'.
         '        <td><a href="Morphy-Levental-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>���������� ����������� ������</td>'.
         '        <td>����� - ���������<br/>(������ ������ �����)</td>'.
         '        <td><a href="Morphy-Levental-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>������� ������</td>'.
         '        <td>��������� - �����<br/>(������ ������ �����)</td>'.
         '        <td><a href="Morphy-Levental-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>����������� ������</td>'.
         '        <td>���� - �����<br/>(������ ��� ����� �� f7, ����� ������ �����)</td>'.
         '        <td><a href="Morphy-blindfold-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>������������ ������</td>'.
         '        <td>����� - ������<br/>(����� ������������� ���� ������� �� 8 ������)</td>'.
         '        <td><a href="Morphy-blindfold-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>������ ��������</td>'.
         '        <td>����� - �������<br/>(��������� ������ �����)</td>'.
         '        <td><a href="Morphy-Paris-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>����������� ������</td>'.
         '        <td>������� - �����<br/>(������� ������ �����)</td>'.
         '        <td><a href="Morphy-Paris-1858.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>������ ��������</td>'.
         '        <td>����� - ������ ���� �������������� � ���� �����<br/>(������� �����)</td>'.
         '        <td><a href="Morphy-Anderson.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>��������� ������</td>'.
         '        <td>����� - ��������<br/>(������ ������ �����)</td>'.
         '        <td><a href="Morphy-Anderson.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1859</td>'.
         '        <td>������������ ������</td>'.
         '        <td>����� - ����������<br/>(������ ������ �����)</td>'.
         '        <td><a href="Morphy-Multy-board-chess-play.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1859</td>'.
         '        <td>����� ���� �����</td>'.
         '        <td>�� ������ - �����<br/>(����� � ����� ����-�����)</td>'.
         '        <td><a href="Morphy-Multy-board-chess-play.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1859</td>'.
         '        <td>��������� ������</td>'.
         '        <td>��������� - �����<br/>(����� � ����� ����-�����)</td>'.
         '        <td><a href="Morphy-Multy-board-chess-play.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1859</td>'.
         '        <td>������ ��������</td>'.
         '        <td>����� - �������<br/>(����� ��� ���� �� b1)</td>'.
         '        <td><a href="Morphy-New-York-1859.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1859</td>'.
         '        <td>����������� ������</td>'.
         '        <td>����� - ������<br/>(����� ��� ���� �� b1)</td>'.
         '        <td><a href="Morphy-New-York-1859.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1863</td>'.
         '        <td>����������� ������</td>'.
         '        <td>����� - �� ������</td>'.
         '        <td><a href="Morphy-Civil-War-1861.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1863</td>'.
         '        <td>����� ���� �����</td>'.
         '        <td>�� ������ - �����</td>'.
         '        <td><a href="Morphy-Civil-War-1861.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1869</td>'.
         '        <td>������ ����</td>'.
         '        <td>����� - ������<br/>(����� ��� ���� �� b1)</td>'.
         '        <td><a href="Morphy-the-last-years-of-life.php">������</a></td>'.
         '    </tr>'. 
         '    <tr><td>1869</td>'.
         '        <td>����� �����</td>'.
         '        <td>����� - ������<br/>(����� ��� ���� �� b1)</td>'.
         '        <td><a href="Morphy-the-last-years-of-life.php">������</a></td>'.
         '    </tr>'. 
         '  </table>'. 
         '</div>'.
          
         '<div class="navigation">'.
         '  <a href="'.$link_begin.'">��������� ���� ChessAndMail</a>'.
         '</div>'.
         CPage_::get_metrika_yandex();
          
  
  CPage_::$title_ ='ChessAndMail: ������ ���� �����';
  CPage_::$add_file_style ='champions.css';
  CPage_::$body_ =$body_;
  CPage_::MakePage(); 
?>
