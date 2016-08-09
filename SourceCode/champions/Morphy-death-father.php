<?php
  require_once('../const_.php');
  require_once('../rule_game_.php');
  
  session_name(NAME_SESSION_);
  session_start();
  
  CPage_::$root_catalog ='../';
  
  $link_begin =(isset($_SESSION[SESSION_ID_]) ? CPage_::$root_catalog.'MainPage.php?link_=Events' : 'http://chessandmail.ru');
  
  CPage_::$header_ ='<h1>������ ���� ���� �����</h1>';
  
  $body_='<div class="narration">'.
         ' <image src="Image/cemetery-New-Orleans.png" alt="�������� ������ �������" title="�������� ������ �������">'.          
         '   ������ ��� �������� ��-�� ��������. �������. ������ �������� ��� � ����� �� ������������� �����.'.
         '<p>��� ��� ������������, ����� <a href="Paul-Charles-Morphy.php">���</a>,�� ������ �� ���������.�� ����� ������'.
         '   ������ � ����� � ��������'.
         '   ����. ��� ������ � �������� ��� ������ � ������ �������.'.
         '<p>��� �������� �����. ������ ������ ������, ���������� ������� ��������� � �����. ����� �� ������ �'.
         '   ����� �� ��������� ��������� ������������� �����. ��� ������ ������������ ����, ��� ���� � �����'.
         '   �������� � ����������� ���������� ������.'.
         '<p>����� ��������� ���������, ��� ������ � ��������, ������ � ��������. ���-�� �������� ����� �����'.
         '   �����, �� ����� ������������ � ������� ���� ����-�� ����� ������ �� ��� ������� ����� ��� ������,'.
         '   ��� ���� ��������� ����������. ���� ���� ��� ������, ��� �������� �������� �����. ������ ������'.
         '   ��������� ������� ��� � ������ ������� � ����� ������ �������� ������ ���.'.
         '<p>��� ���� ��� ������ � �������, ���,�� ������� � ������� ������ �����. �� �������� � ������ �������'.
         '   ����� ��� ������ ����������. � �������� ������ ��� ��������� ����� ����������, �������� ���������'.
         '   �������, �������� �����.'.
         '<p>20 ������ �� ������� ��������, � 22 ���� �� ���������������� �����, ��� � �� ������� � ����.'.
         '   ����� ��� ����� �������������� ������, <a href="Paul-Charles-Morphy.php">���</a> ���� � ���� �'.
         '   ������� � �� ������� ������ �� ����� �������.'.
         '<p>����� ����� �������� � ����� ������� 23 ������ 1856 ����, � ����� ��� �� ����� ����� �������.'.
         '   �������� ����� ��������� ���� �� ���������� ������ �� �������� ������� ��������, ��� �� ����'.
         '   ����-����� � ��-���-���.'.
         '   ��� ������� ���������� ����� ��� �������� �� �������� ����� ��������������, ������ � ����� �� ����'.
         '   �����. ��� �������� ������� ������ ��� ������� ������������ �����, ����� ������� ����� �����������,'.
         '   ���������� ��������, ��� ����������� �����������.'.
         '   ������� � ������� ���������� ������� ������� �� ����������, ������ ������� �������. ��� ���������'.
         '   � ��������� ���� ������������� ������ �����.'.
         '<p>� ����� ����� ��� ������, � ��� ��������� �� ����� �����...'.
         '<p>� ������ ������, 1857 ���� ������ ������� ������� � ���� ��� ���� �����. �� �������� �������� �������'.
         '   ������, ������ ��������, �������� ������������������ ���� � ��������� �������. ��� ��, �������������'.
         '   ������� � ���������� ���� ��������, ��� ����� �� ������������� ������������ ������ ��������. ��'.
         '   ������ � ���������� ������ ��������� ���� � � ����������� ������ ����� ���� ����������.'.
         '<p>������������������ ����� �� �������� �� ����, ��� ��������� �� ��� ����, ��� �� ������������ ����.'.
         '   ������ ��� ����� �������� ������ ���� ���� ���������� � ������ �������� � �������� ������������.'.
         '   ��� ������ ���� ����������, �� ��������� � ������� ����������� � ���������� �� ������ � ������� �'.
         '   ��������� ������������������. ���� ���������� ������� ��� �������.'.
         '<p>����� ��� ����� ��������� ������� ������ �����, ������ ������� ������ ������� ������� � ����������'.
         '   ��������� �������, ���������� ����� ������ �����, �������� ���������.'.
         '<p>���������� ��� ���� �������� � ������. ������� ����� ����蠖 ���������� ����� �������⠖ �������'.
         '   ������� ����� ��� ������ � �������� ������. ������ ������� �� �������� �����.'.
         '   ������� ��� �� ����-����� 89 ��������� ����� � ������������� ���� �������, � ��� ���� ����� � ���'.
         '   ����, ������� ��������. ����� � ��� ��������� ������ ����� �������� ������� � ����������� ������� �'.
         '   ������� ����.'.
         '<p>����� �������, ��� ��������� � ������������ ��� ����,�� ������� ������� ������� ������ �������.��'.
         '   �������� ������� ��������� � ���� ������ ����� �����!'.
         '</div>.'.
          
         '<div class="example-game">'.
          '  <h2>'.
          '    <span class="type-game">������ ������</span><br/>'.
          '    �����&nbsp;&nbsp;�. ����� (����)<br/>'.
          '    <span class="place-game">'.
          '      ����� ������, ����� 1849'.
          '    </span>'.
          '  </h2>';
          
  $rg =new CRuleGame();
  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">'.
          '  <div class="game">'.
          '    <div class="board">'.
                 CPage_::OutBoard($rg,0.5,'board1','b1-').
          '      <div class="operations">'.
          '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b1-begin">'.
          '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b1-prev">'.
          '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b1-next">'.
          '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b1-last">'.
          '      </div>'.
          '    </div>'.
          '  </div>'.
          '</div>'.
          '   1. <span id="b1-1">e2-e4</span>  <span id="b1-2">e7-e5</span>'.
          '   2. <span id="b1-3">�g1-f3</span> <span id="b1-4">�b8-c6</span>'.
          '   3. <span id="b1-5">�f1-c4</span> <span id="b1-6">�f8-c5</span>'.
          '   4. <span id="b1-7">b2-b4</span>  <span id="b1-8">�c5:b4</span>'.
          '   5. <span id="b1-9">c2-c3</span>  <span id="b1-10">�b4-c5</span>'.
          '   6. <span id="b1-11">d2-d4</span> <span id="b1-12">e5:d4</span>'.
          '   7. <span id="b1-13">c3:d4</span> <span id="b1-14">�c5-b6</span>'.
          '<p>7. ... �b4+ ������ �������������� �� �������� ����� ����� 8. �d2; ������� ����� � 8. ��f1;'.
          '   7. ... �b4+ 8. ��f1 d6 9. d5 � ����� 10. �a4 ��� 8. ... �e7 9. �b2 �:e4 10. �bd2 �:d2 11. �:d2 � �.�.'.
          '<p>8. <span id="b1-15">0-0</span> <span id="b1-16">�c6-a5</span>'.
          '   9. <span id="b1-17">�c4-d3</span> ...'.
          '<p>�� � ���� �� ���� ������ 9. �:f7+ ����� 9. ... ��:f7 10. �e5+ ��f8 11. �h5 �f6! 12. �g5 �e6 � �.�.'.
          '<p>9. ...  <span id="b1-18">�g8-e7</span>'.
          '   10. <span id="b1-19">�b1-c3</span>  <span id="b1-20">0-0</span>'.
          '   11. <span id="b1-21">�c1-a3</span>  ...'.
          '<p>����� 11. d5 d6 12. �e2 �g6 13. �b2, ����� ����� �������� ���������� ������� ������� ������'.
          '<p>11. ... <span id="b1-22">d7-d6</span>'.
          '   12. <span id="b1-23">e4-e5</span> <span id="b1-24">�c8-f5</span>'.
          '   13. <span id="b1-25">e5:d6</span> <span id="b1-26">c7:d6</span>'.
          '   14. <span id="b1-27">�c3-e4</span> <span id="b1-28">d6-d5</span>'.
          '<p>������� ����� 14. ... �:e4 � ����� f7-f5, �� � ����� � ������ ���� �� ������� ����. ����� ����'.
          '   � ������ ������� ������� ������ ���������� � ������������ �������� �����'.
          '<p>15. <span id="b1-29">�e4-f6+</span> <span id="b1-30">g7:f6</span>'.
          '   16. <span id="b1-31">�a3:e6</span> <span id="b1-32">�d8:e7</span>'.
          '   17. <span id="b1-33">�d3:f5</span> <span id="b1-34">�a5-c4</span>'.
          '<p>����������� �������� 17. ... �fe8 � �e7-f8'.
          '<p>18. <span id="b1-35">�f1-e1</span> <span id="b1-36">�e7-d6</span>'.
          '   19. <span id="b1-37">�f3-e5</span> ...'.
          '<p>����� 19.�h4 �f4 20. �h5 h6 21. �g6 � ��������� ��������. ������ � ������ ���������� ���'.
          '   �������������� � ������� ����� �������� �������'.
          '<p>19. ... <span id="b1-38">f6:e5</span>'.
          '   20. <span id="b1-39">�d1-g4+</span> ...'.
          '<p>�����, ��� ����� 20. �h5, ������ ���� ������ ����� ���������� ������� ���������'.
          '   (20. ... �e8, 20. ... ��g7).'.
          '<p>20. ... <span id="b1-40">��g8-h8</span>'.
          '   21. <span id="b1-41">�g4-h5</span> <span id="b1-42">��h8-g7</span>'.
          '   22. <span id="b1-43">�h5-g5+</span> ...'.
          '<p>����� ���� �� 22. �:h7+'.
          '<p>22. ... <span id="b1-44">��g7-h8</span>'.
          '   23. <span id="b1-45">�g5-h5</span> <span id="b1-46">h7-h6</span>'.
          '<p>������ ���������� �� ������ ����������� �����'.
          '<p>24. <span id="b1-47">�e1:e5</span> ...'.
          '<p>��������� ������ 24. de. ����� ���� ������������ �� ��, ��� ������ �������� ����� �������'.
          '<p>24. ... <span id="b1-48">�c4:e5</span>'.
          '<p>���� ����� ������������� ���� ������: ����� 24. ... �f6!, ������ ����� ����������� �� ����'.
          '   ������������ ������������'.
          '<p>25. <span id="b1-49">d4:e5</span> <span id="b1-50">�d6-c6</span>'.
          '   26. <span id="b1-51">e5-e6</span> <span id="b1-52">��h8-g7</span>'.
          '   27. <span id="b1-53">g2-g4</span> <span id="b1-54">�c6-c3</span>'.
          
          '<div class="chess-games">'.
          '  <div class="game">'.
          '    <div class="board">'.
                 CPage_::OutBoard($rg,0.5,'board1-2','b1-2-').
          '      <div class="operations">'.
          '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b1-2-begin">'.
          '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b1-2-prev">'.
          '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b1-2-next">'.
          '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b1-2-last">'.
          '      </div>'.
          '    </div>'.
          '  </div>'.
          '</div>'. 
          
          '<p>������ ���������� ��������� ����� �� f6, �� ��������� ��� �������� �����, ����������� �������'.
          '   ���������� � �������. ������� ���������������� ��� �a8-e8! ��� �� ������� ������, ��������:'.
          '   27. ... �ae8 28. �e1 (���� 28. g5, �� 28. ... �:e6) 28. ... �c3! 29. �f1 �f6.'.
          '<p>28. <span id="b1-55">g4-g5!</span> <span id="b1-56">�c3:a1+</span>'.
          '   29. <span id="b1-57">��g1-g2</span> <span id="b1-58">�a1-f6</span>'.
          '<p>29. ... ��g8 �������� ����������� ����� �� �� �� ���� ����������.'.
          '<p>30. <span id="b1-59">g5:f6+</span> <span id="b1-60">��g7:f6</span>'.
          '   31. <span id="b1-61">e6:f7</span> <span id="b1-62">�f8:f7</span>'.
          '   32. <span id="b1-63">�h5-g6+</span> <span id="b1-64">��f6-e7</span>'.
          '   33. <span id="b1-65">�g6-e6+</span> <span id="b1-66">��e7-f8</span>'.
          '   34. <span id="b1-67">�e6:h6+</span> <span id="b1-68">�f7-g7+</span>'.
          '   35. <span id="b1-69">�f5-g6</span> <span id="b1-70">��f8-g8!</span>'.
          '<p>���� 35. ... �d4, �� 36. �g5 � 37. h4.'.
          '<p>36. <span id="b1-71">h2-h4</span> <span id="b1-72">d5-d4</span>'.
          '<p>����� �� ����� ������ �a8-f8, ��������: 36. ... �f8 37. f4 �c7! 38. �g5 �:f4'.
          '   39. �:d5+ ��h8 40. h5 b6 � �.�.'.
          '<p>37. <span id="b1-73">h4-h5</span> <span id="b1-74">d4-d3</span>'.
          '   38. <span id="b1-75">�h6-g5</span> <span id="b1-76">�a8-d8</span>'.
          '   39. <span id="b1-77">h5-h6</span> <span id="b1-78">d3-d2</span>'.
          '   40. <span id="b1-79">�g5-f6</span> ...'.
          '<p>����� ������ ���������� 40. �h7+ ��f7 41. �:g7+ � �.�.'.
          '<p>40. ... <span id="b1-80">�g7-d7</span>'.
          '<p>�� 40. ... �:g6+ ��������� ��� � ���� �����: 41. �:g6+ ��f8 42. �g7+ ��e8 43. h7'.
          '   � �.�.; ���� 40. ... �dd7, �� ����� ��� �� ������� 5-�� ����: 41. hg �:g7 42. �e6+ ��h8'.
          '   43. �h3+ � 44. �c8+ ...'.
          '<p>41. <span id="b1-81">�g6-f5</span> <span id="b1-82">d2-d1�</span>'.
          '<p>������ ����������! ���� 41. ... �f7, �� 42. h7+, ��� � ������; �� 41. ... �f8 ����������'.
          '   42. �e6+ ��h7! 43. �:f8 ��g6! 44. �f5+ ��g5 45. f4+ ��h5 46. �g8 � �.�.'.
          '<p>42. <span id="b1-83">h6-h7+</span> ...'.
          '<p>�������� ������ �����!'.
          '<p>42. ... <span id="b1-84">�d7:h7</span>'.
          '   43. <span id="b1-85">�f5-e6+</span> <span id="b1-86">�h7-f7</span>'.
          '   44. <span id="b1-87">�e6:f7+</span> <span id="b1-88">��g8-h7</span>'.
          '<p>���� 44. ... ��f8, �� 45. �e6+ � 46. �f7x'.
          '<p>45. <span id="b1-89">�f6-g6+</span> <span id="b1-90">��h7-h8</span>'.
          '   46. <span id="b1-91">�g6-h6x</span>'.
          
          '</div>'.
          
          '<div class="navigation">'.
          '  <a href="'.$link_begin.'">��������� ���� ChessAndMail</a>'.
          '</div>'.
          CPage_::get_metrika_yandex();
          
  
  CPage_::$title_ ='ChessAndMail: ������ � �������� ���� ���� �����';
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