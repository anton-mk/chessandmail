<?php

  require_once('../const_.php');
  require_once('../rule_game_.php');
  
  session_name(NAME_SESSION_);
  session_start();
  
  CPage_::$root_catalog ='../';
  
  $link_begin =(isset($_SESSION[SESSION_ID_]) ? CPage_::$root_catalog.'MainPage.php?link_=Events' : 'http://chessandmail.ru');

  CPage_::$header_ ='<h1>������� ���� �����</h1>';
  $body_ ='<div class="narration">'.
          '  <image src="Image/Alonzo-Morphy.png" alt="������ �����" class="no-border" title="���� ���� ���� - ������ �����.">'.
          '   - ������� ���? -�������� ������ ���� ������. - ���� ���� �����!<br/>'.
          '   ������ ��� �������� �� ��������� ��������� ������. �� ��������� ������� � ������ ����������'.
          '   ���� �������� ����. �� ���������, ��� ������� ���������, ��������� ���� ������ � ������ �����,'.
          '   �� ������ �� �����.'.
          '<p>������ ��� �����, ����, ����� ���������. �� ������ ���, ����� ����� � �������, ��� ��������,'.
          '   ��� ��� ����������.<br/>'.
          '   -��-�-��...� ��-��... - ��� �� ����� ��������. - � �����... � ��-�� ���... � ���...<br/>'.
          '   -����������, �������? - ���� ������� �� ���������.<br/>'.
          '   ��� ������. ���������� �������� ���� ��������� ��� � ����� ����-�� ����� �� ������� ������ ������.'.
          '   ��� ������, ��������, ��������� � ��������, ������� ���������� ��,'.
          '   <a href="Paul-Charles-Morphy.php">��� �����</a>, ���������� �������.'.
          '   ��� ������ ������� �� ���� �����, ��� ������ ��� ����������....'.
          '<p>����� �� ����� � ��������� ��� ������. �� ���� ������� �� ����� � ���� �� ���� ���������. ������...'.
          '   ���������! �� �������, ���� ���� ������ ����... ������ - ����, ����� ������ - �����, � ������'.
          '   ����� ���� ��� � ��� �� ������ ����, ����� �������� ������ ��� ������... '.
          '<p>���! ��� ����� ������ ����.<br/>'.
          '   - � ��� �����, ���? - �������� �������� ������ �������.</br>'.
          '   ��� ���������� � ���. ������� �� ��������� ��������� ������� � ��������� �������� � ����� �������'.
          '   �����.</br>'.
          '   - ���� �� ������: ������ �����! - �������� ������ ������ ��� � �������� � �����. �� ����� �����'.
          '   ��� ����� ������ - ��������� ������: ������� ����������� ����� ������� ������ ������-��'.
          '   ������������ �� ���� ��� - ���� �� ����������...'.
          '</div>'.
          '<div class="example-game">'.
          '   <h2>'.
          '     <span class="type-game">����������� ������</span><br/>'.
          '     �����&nbsp;&nbsp;�.����� (������ ������, �������� ���� �� ����� �� �����)<br/>'.
          '     <span class="place-game">����� ������, 22 ���� 1849</span>'.
          '   </h2>';
  
  $rg =new CRuleGame();
  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">'.
          '  <div class="game">'.
          '    <div class="board">'.
                 CPage_::OutBoard($rg,0.5,'board2','b2-').
          '      <div class="operations">'.
          '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b2-begin">'.
          '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b2-prev">'.
          '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b2-next">'.
          '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b2-last">'.          
          '      </div>'.
          '    </div>'.
          '  </div>'.
          '</div>'.
          '   1. <span id="b2-1">e2-e4</span>  <span id="b2-2">e7-e5</span>'.
          '   2. <span id="b2-3">�g1-f3</span> <span id="b2-4">�b8-c6</span>'.
          '   3. <span id="b2-5">�f1-c4</span> <span id="b2-6">�f8-c5</span>'.
          '   4. <span id="b2-7">c2-c3</span>  <span id="b2-8">d7-d6</span>'.
          '   5. <span id="b2-9">0-0</span>    <span id="b2-10">�g8-f6</span>'.
          '<p>���������� 5... Cb6 ��� 5... �e7. ������ ����������� 5... �g4 6. �b3 �:f3 7. �:f7+ ��f8 '.
          '   8. �:g8 �:g8 9.gf g5. �� ������ 8. �:g8 ����� ����� ������ ������ 8. gf, ������� ������ �������.'.
          '<p>6. <span id="b2-11">d2-d4</span> <span id="b2-12">e5:d4</span>'.
          '   7. <span id="b2-13">c3:d4</span> <span id="b2-14">�c5-b6</span>'.
          '   8. <span id="b2-15">h2-h3</span> ...'.
          '<p>����� ������ ����� 8. �c3.'.
          '<p>8. ... <span id="b2-16">h7-h6</span>'.
          '   9. <span id="b2-17">�b2-c3</span> <span id="b2-18">0-0</span>'.
          '  10. <span id="b2-19">�c1-e3</span> ...'.
          '<p>����� ����� ��������� ��������� ������ �� b3.'.
          '<p>10. ... <span id="b2-20">�f8-e8</span>'.
          '<p>����� 10. ... �:e4 11. �:e4 d5 ���� � ������ ����.'.
          '<p>11. <span id="b2-21">d4-d5</span> <span id="b2-22">�b6:e3</span>'.
          '<p>��������� ������, ����� �������� ������ ����� ����. ���� ���� ������ 11. ... �e7.'.
          '<p>12. <span id="b2-23">d5:c6</span>  <span id="b2-24">�e3-b6</span>'.
          '   13. <span id="b2-25">e4-e5</span>  <span id="b2-26">d6:e5</span>'.
          '   14. <span id="b2-27">�d1-b3</span> <span id="b2-28">�e8-e7</span>'.
          '<p>������� ���������! ��������� ������ 14. ... Ce6. ������� ������� ���������� ����������� ������ �����.'.
          '   ���������, ����� ������, ��� ��� ������ - ������, �������� ����� �� ����� �� �����.'.
          '<p>15. <span id="b2-29">�c4:f7+</span> <span id="b2-30">�e7:f7</span>'.
          '   16. <span id="b2-31">�f3:e5</span>  <span id="b2-32">�d8-e8</span>'.
          '   17. <span id="b2-33">c6:b7</span>   <span id="b2-34">�c8:b7</span>'.
          '   18. <span id="b2-35">�a1-e1</span>  <span id="b2-36">�b7-a6</span>'.
          '<p>������ ������ �� ����� ������ 18. ... �e4 19. �:f7 �:f7 20. �:f7+ ��:f7 21. �:e4 �a6 � �.�.'.
          '<p>19. <span id="b2-37">�e5-g6</span> <span id="b2-38">�e8-d8</span>'.
          '   20. <span id="b2-39">�e1-e7</span>, � ����� ��������'.
          '<p>�� ������ ������ ��������, ������� ���������������� ��� ���� ������ �������� �� ���������� ��������'.
          '   ��������.'.
          '</div>'.
          
          '<div class="navigation">'.
          '  <a href="'.$link_begin.'">��������� ���� ChessAndMail</a>'.
          '</div>'.
          CPage_::get_metrika_yandex();
  
  CPage_::$title_ ='ChessAndMail: ������� ���� �����';
  CPage_::$add_file_style ='champions.css';
  CPage_::$body_ =$body_;
  CPage_::$use_jquery =true;
  $s='<script type="text/javascript" src="scripts/view_games.js"></script>'.
     '<script type="text/javascript" src="scripts/games-Paul-Morphy.js"></script>';
  if(isset($_SESSION[SESSION_ID_])){
    $s .='<script type="text/javascript">'.
         ' catalog_images =\''.CPage_::$root_catalog.const_::$catalog_image_fugure .'\';'.
         '</script>';   
  }else $s .='<script type="text/javascript">catalog_images =\'../Image/\';</script>';
  CPage_::MakePage($s); 
?>
