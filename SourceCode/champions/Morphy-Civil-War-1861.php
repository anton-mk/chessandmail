<?php

require_once('../const_.php');
  
  session_name(NAME_SESSION_);
  session_start();

  CPage_::$root_catalog ='../';
  $link_begin =(isset($_SESSION[SESSION_ID_]) ? CPage_::$root_catalog.'MainPage.php?link_=Events' : 'http://chessandmail.ru'); 
  
  CPage_::$header_ ='<h1>�����, ����������� ����� 1861 ���</h1>';
  $body_='<div class="narration">'.
         '    <image src="Image/Morphy-autograph-game.jpg" alt="�������� ����� �� ������ �.����� � �.��������" title="�������� ����� �� ������ �.����� � �.��������"/>'.
         '    � �������� ����������� ���� 1861 ���� ��� ����� �� ������, ����� ������ ������������ � ������� ������.'.
         '    �� ���������� ������ �����.'.
         '<p>�������, ���! �����! ����� �������� ���������� ����������� ����, ���� ������ ��������, ���������'.
         '   �����������!..'.
         '<p>��� ��������� ��� ��������� �������.'.
         '<p>�������� �� ������� ��������� ����!�� ������� ������.�� �� ������ �������� �������� �������� � ����'.
         '   ����, �� ������ �� �������� ���������� ���� ����� ����! � ��� ��������� � ����������� ������� ���,'.
         '   ���! � ��� ������� ������ ��?'.
         '<p>���������, �����,�� �������� ������� ���.�� ������� ���� ����������� � ���, ��� ����������.'.
         '<p>��� ��� ��� �����������?�� ������ ������.�� ������ � ������� � ���������, ��� ��������� ����� �����!...'.
         '<p>����� ����������� � ����� ������� ���� ���������, <a href="Paul-Charles-Morphy.php">��� �����</a> ������� ��������� ��������, ��� ��������'.
         '   �����������, ����� ���� ��� ���������� � �����, �� ��� ����������. ������ �� �� ������� � ����������'.
         '   ��������� ����������. ��������� (��� ���������� ���� �� ������) ������ ���� �������'.
         '   ������� ���� �����, ���, ��������� �� ������ �������� ������ ������� � �������, ����� ������������ �'.
         '   ��� �� ���������. ������ �������� �����, �������� ���������, ��� ������� ������ ������ �����������'.
         '   ������������� ������� ����� �� ����� �����...'.
         '<p>� ��� ������� ������ ��� ����� ��� � ������ � ����� �������, � ������ ���� �� ����-�����.'.
         '   �� ��� ������� ������� ������ ���������, ��� �������� ������� ������������, �����������, �����.'.
         '   �������, ���������� ������� �� �����, ��������� ������� ��� ��� � �������� ��� ������ �� ������'.
         '   ������. ��������� ����� ������������� ������ ������� � �����, ����������� �������� ������� �'.
         '   ������������ ��������� �� ������ ������. ������� ������� ���� ����������� ��� �������, ����� �'.
         '   ������� ��������, ���������� �������. ��������� ����� ������� ������� �����񻠖 �������� �����'.
         '   ����������� � ������������������ ��������,�� ������ ����������� � ������ �������������� ���������'.
         '   ��������� ������. ��� ������������ ���, ��� ���������� �������� �� �����������. ������� �������'.
         '   <a href="Paul-Charles-Morphy.php">���</a> ���������� � ��� ����� ���������� ������⠖ ������� � ������ �����. �� ����, ����� �����, ���'.
         '   ����������� ��������������� �������� ����� � ���������� ������� ����� ������ M����. ����� �����'.
         '   ����� ������� � ������� �����, ������� ��� ������ ����. ������ ��������� ���� ������� ����������'.
         '   ������ ����� ���������� �� ����� ������ ������� ����� � ���� �� �����, ����, �������� ���� ���, �'.
         '   �����, �������� ���� ���. ��������� ����� ���������� ������� � ������, �� ����, �� ��������������'.
         '   ����. ��� ����� ����� � ����� �������� ������ � ������ ����, �� ��������� ������������ ��'.
         '   �����������. ������� ������ �� ���� ���� ��������� ����� �� ���� � ������ ������������ ���� 1862'.
         '   ����. ���������������� ��������� �������頖 ����� ��������� ������ �������� ���� ������� � ���� �'.
         '   ���������� ����� ���������. ����� �������� ����� ������� ������ ������� ����� �� ����� ������.'.
         '   ����� ������ ��� ���� ��� ������ ������. �������� ������� � ��� ������� ������ � ����� ������...'.
         '<p><image src="Image/Morphy-cast-hands.jpg" alt="������ ���� ���� �����" title="������ ���� ���� �����"/>'.
         '   � ��������� ��������� ����� �� ����-���, ������� ��������� ���� � ���� ������ ������, ������'.
         '   ������� �������� � ������� ��������� ��������� ����. ������� �������������� ������� ��������� ����'.
         '   ��������� ��������� ���������. ��� �� ���� ��� �������� ������ ������� ������� � ����� ��� ������'.
         '   ������������ ������ ������ � �������� ��������� ���������. ������ <a href="Paul-Charles-Morphy.php">����� �����</a> ��� ��������'.
         '   �����������. �� ������� ������� � ������, �� ������� ��-��������, ��� ����������� �������. � ���'.
         '   ����� ����� ����-����������, ��������! ��� ������ �� �����. ��������� ���� ��� ������ ������������,'.
         '   �� ���� ������ ������� ��� �����. ��� ������� ��������� ������ ��� ��� �� ��������젖 ���� ����� �����.'.
         '   ���������� ������ ���������� ��������� ��������� �������� ���� ����������� ������ �����.'.
         '<p>��� ������ � ������ ����� � �� ���� ����� ��������� �� ����� ��������� ���������� ����. �����������'.
         '   �� ����� ������� ������ ������� �� ����� ������ ����, ������ � ���������� �������. �� ����� � ���'.
         '   ������ �� ������� �������� ������. ������� ������ �������� ������, ���������� ��������, ��� ����������'.
         '   ����������� ����, ���������� ������, �������� �� ������ ������.'.
         '<p>��� ��� �� �����?�� ������� ���.'.
         '<p>������ �������� ��������� ��� ������������� � ����� ������� �������.'.
         '<p>������ �������, ������ ���, ����� ������! ����� ���� ������ �� ���� � ������� ����� �����'.
         '   ������������ ��� � ��������� ���� �����-��������� �����, �������� �� ������. ����� ����� ������.'.
         '   ���� ������ ��� ����� ������������ ���������� ��� ������������.'.
         '<p>������ �������� ������� �����-����� ����������� ������ � ���������� ������ �������. �� ������ ��'.
         '   ���� ������, �� ��������� ����������� �����, ��� ������� ��������� �������.'.
         '   ������ ����� � ����� �� ������. �� �������� ��������� ������, �� ��� ������� ������� ������� ��'.
         '   ����� ����� �� ������, � ������� ������ ������� ��������� �������. ����� �������, ��������� �������'.
         '   ��� ����������� ���������� ���� ��� ����� ����������...'.
         '<p>� ����� � ������ ������ ��������� ������� ������� �� ����� �� �������, ��������� ������� �����'.
         '   ���� �����.'.
         '<p>������ �����, ����!�� ������ ������ ���.�� ������� ������ ����� ����������, �� ������� ���������� �������.'.
         '<p>��������, ����!�� ������� �����.�� �������� ����������� � ������, �� ��� ����� ����� �� ���� ���� �����!'.
         '<p>��� ��� �� ��������� �� ������� �� ������?'.
         '<p>�������� ��������! ������ � ��������� �� ��������������� �������� ������ � ��������� �� �����!'.
         '<p>��������, ����, �� ����� ���� �����. ��������� ����� ����� ����������� � �����, ��� ��� �������'.
         '   ������������. ������ ���� �������� ������������ ������� ���� �������� ����� � ������, ����� �����'.
         '   �� ��������� ���. ��� ��������� � �����, �������, ������, ��� ��� ���� �����, ����� ���� � ������'.
         '   �������...'.
         '<p>������� ����������� �� ������ ������ ������������ � ���� �������. ���� � ���� ���� � ������ �������'.
         '   � ����������� ��. ������ ������� � ���������� �������, ��� ������� � ������� � ������� ���� ������,'.
         '   ��� � ��������. ��� ���������� � ������ � ��������� ��������� ���� ���������� ����, � ������ �������'.
         '   ������ ������. ������� <a href="Paul-Charles-Morphy.php">���</a> ����� ����� � ���� ��� �� ������, ���������� �� ������� ��������, ��'.
         '   ����� ������� ���� �������. ��������� � ���� ��� �� ������ ������� ������ ���� �� ������ �������.'.
         '   � ��� ����� �������� � �������, ������ ������ ������� ���, ����� �� ����! �� ����� � ������� �� ����,'.
         '   �� ������ ������ �� ���, ��� �������� ������. ���, � ������ ��� �� �������� � ��� ����� ��'.
         '   ������������ � ���� ��� �� ������. ��� ���� � ������ ����� ������, �� ���������� �� �����'.
         '   ����������� �������� � ����� ������� �� ������� �� ���, ��� �������� ����. ����� ��� ������ ���, �'.
         '   ���� ��� ���������� ��������... ���� ��������� � �����, ����� ������ �����. ������ 1865 ����'.
         '   ������ �� ���� ������� ����� ���������� ����� �������� ������ ������. �������� ���� ����� �������,'.
         '   �������, ���������� � ��������, ������� ������ � ���� �������� ��� ������ �������� ���������'.
         '   ����������� ������. � ������ 1865 ���� ��� ����������� ����� ������ ������� ������ ����, ��������'.
         '   ������ ������� ����� �������� ������ ���� �����. ��� ��������� � �������. ����� ���������.'.
         '   ����� ������������. ����� 1865 ���� ��������� ����� ��������� �� ������ ����� ����� �����������'.
         '   ����������.'.
         '</div>'.
          
         '<div class="example-game">'.
         '  <h2>' .
         '    <span class="type-game">����������� ������</span><br/>' .
         '    �����&nbsp;-&nbsp;�� ������<br/>' .
         '    <span class="place-game">' .
         '      �����, ������ 1863 ���' .
         '    </span>' .
         '  </h2>'.
         '   1. <span id="b63-1">e2-e4</span> <span id="b63-2">e7-e5</span>'.
         '   2. <span id="b63-3">�g1-f3</span> <span id="b63-4">�b8-c6</span>'.
         '   3. <span id="b63-5">�f1-c4</span> <span id="b63-6">�f8-c5</span>'.
         '   4. <span id="b63-7">c2-c3</span> <span id="b63-8">�d8-e7</span>'.
         '<p>������ ���������� �� ��������� ����� 4. ... �f6 ������ ����� ������� (5. d4 ed 6. cd �b4+'.
         '   7. �c3! �:e4 8. 0-0 �:c3 9. d5!), ��� ��������� ��������, �� ����� ��������� ����������'.
         '<p>5. <span id="b63-9">d2-d4</span> <span id="b63-10">�c5-b6</span>'.
         '<p>����� (5. ... ed) �� � ���� ������. ������ ��������� �������� �����.'. 
         '<p>6. <span id="b63-11">0-0</span> <span id="b63-12">d7-d6</span>'.
         '   7. <span id="b63-13">h2-h3</span> ...'.
         '<p>�� �������� ������. ��-��������, ���������� 7. a4 a5 8. �a3 �g4 9. �c2 �f6 10. �e1 0-0 11. �e3!'. 
         '<p>7. ...  <span id="b63-14">�g8-f6</span>'.
         '   8. <span id="b63-15">�f1-e1</span> <span id="b63-16">h7-h6</span>'.
         '   9. <span id="b63-17">a2-a4</span> <span id="b63-18">a7-a5</span>'.
         '   10. <span id="b63-19">�b1-a3</span> <span id="b63-20">�c6-d8</span>'.
         '<p>����������� �������� 10. ... 0-0.'. 
         '<p>11. <span id="b63-21">�a3-c2</span> <span id="b63-22">�c8-e6</span>'.
         '   12. <span id="b63-23">�c2-e3</span> <span id="b63-24">�e6:c4</span>'.
         '   13. <span id="b63-25">�e3:c4</span> <span id="b63-26">�f6-d7</span>'.
         '   14. <span id="b63-27">�c4-e3</span> <span id="b63-28">g7-g6</span>';
  $rg = new CRuleGame();
  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">'.
         '  <div class="game">'.
         '    <div class="board">'.
         CPage_::OutBoard($rg, 0.5, 'board63', 'b63-') .
         '      <div class="operations">' .
         '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b63-begin">' .
         '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b63-prev">' .
         '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b63-next">' .
         '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b63-last">' .
         '      </div>' .
         '    </div>' .
         '  </div>' .
         '</div>' .
         '<p>����, ��������, 14. ... c6, �� 15. �f5 �f6 16. de de 17. �d6+'. 
         '<p>15. <span id="b63-29">�e3-d5</span> <span id="b63-30">�e7-e6</span>'.
         '<p>����� ����� ���� ����������� �������� ��� 15. ... �f8, �� ����� ������� �� ����� 16. �:b6'.
         '   � �������� �� ����������� ����������� ������������'. 
         '<p>16. <span id="b63-31">�c1:h6</span> <span id="b63-32">f7-f6</span>'.
         '<p>����� ������ ����� ��-�� 17. ... �g5!'. 
         '<p>17. <span id="b63-33">�h6-g7</span> ...'.
         '<p>����������� ���, ������� ������, ������� ������� ����� ��������� �������� - 17. �e3'.
         '   ���������� �������.'. 
         '<p>17. ...  <span id="b63-34">�h8-h5</span>'.
         '<p>�� 17. ... �g8 ��� 17. ... �h7 ��������� 18. �:f6 �:f6 19. �g5.'. 
         '<p>18. <span id="b63-35">g2-g4</span> ...'.
         '<p>������� ������ ������ ����������� �e6-f7.'. 
         '<p>18. ... <span id="b63-36">�h5:h3</span>'.
         '   19. <span id="b63-37">�d5:f6+</span> <span id="b63-38">�d7:f6</span>'.
         '   20. <span id="b63-39">�f3-g5</span> <span id="b63-40">�e6-d7</span>'.
         '<p>����������� �������� 20. ... �:g4 21. �:g4 �:g4 22. �:h3 ��f7 23. f3 ��:g7 24. fg ed'.
         '   25. ��g2 �e6, ����� ���� ������, �������� �� ������ ��������, ����� �� ������� ����� �� �����.'. 
         '<p>21. <span id="b63-41">�g7:f6</span> <span id="b63-42">�h3-h4</span>'.
         '   22. <span id="b63-43">f2-f3</span> <span id="b63-44">e5:d4</span>'.
         '   23. <span id="b63-45">c3:d4</span> <span id="b63-46">�h4-h6</span>'.
         '   24. <span id="b63-47">��g1-g2</span> <span id="b63-48">�d8-f7</span>'.
         '   25. <span id="b63-49">�e1-h1</span> <span id="b63-50">�f7:g5</span>'.
         '<p>��� 25. ... �:h1 ����� ����� 26. �:h1, �������� ������������ �����.'. 
         '<p>26. <span id="b63-51">�h1:h6</span> <span id="b63-52">�g5-h7</span>'.
         '   27. <span id="b63-53">�d1-h1</span> <span id="b63-54">�h7:f6</span>'.
         '   28. <span id="b63-55">�h6-h8+</span> <span id="b63-56">��e8-e7</span>'.
         '   29. <span id="b63-57">�h8:a8</span> <span id="b63-58">�b6:d4</span>'.
         '   30. <span id="b63-59">�h1-h6!</span> <span id="b63-60">�d7-c6</span>'.
         '   31. <span id="b63-61">�a1-c1</span> <span id="b63-62">�c6-b6</span>'.
         '<p>����� ��������� ����� ������.'. 
         '<p>32. <span id="b63-63">�c1:c7+</span> <span id="b63-64">��e7-e6</span>'.
         '   33. <span id="b63-65">�a8-e8+</span> <span id="b63-66">�f6:e8</span>'.
         '   34. <span id="b63-67">�h6:g6+</span> <span id="b63-68">��e6-e5</span>'.
         '   35. <span id="b63-69">�g6-f5x</span>'.

         '  <h2>' .
         '    <span class="type-game">����� ���� �����</span><br/>' .
         '    �� ������&nbsp;-&nbsp;�����<br/>' .
         '    <span class="place-game">' .
         '      �����, ������ 1863 ���' .
         '    </span>' .
         '  </h2>';
  $rg = new CRuleGame();
  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">'.
         '  <div class="game">'.
         '    <div class="board">'.
         CPage_::OutBoard($rg, 0.5, 'board64', 'b64-') .
         '      <div class="operations">' .
         '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b64-begin">' .
         '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b64-prev">' .
         '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b64-next">' .
         '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b64-last">' .
         '      </div>' .
         '    </div>' .
         '  </div>' .
         '</div>' .
         '   1. <span id="b64-1">e2-e4</span> <span id="b64-2">e7-e5</span>'.
         '   2. <span id="b64-3">�g1-f3</span> <span id="b64-4">�b8-c6</span>'.
         '   3. <span id="b64-5">�f1-c4</span> <span id="b64-6">�g8-f6</span>'.
         '   4. <span id="b64-7">�f3-g5</span> <span id="b64-8">d7-d5</span>'.
         '   5. <span id="b64-9">e4:d5</span> <span id="b64-10">�c6-a5</span>'.
         '   6. <span id="b64-11">d2-d3</span> <span id="b64-12">h7-h6</span>'.
         '<p>����� ������ ���� �� ����� 6. ... �:c4 7. dc �d6 � ����� 8. ... 0-0: ����� ���� ����� �� g5'.
         '   �����  � ���� ��� ������ ������ ����� ����.'. 
         '<p>7. <span id="b64-13">�g5-f3</span> <span id="b64-14">e5-e4</span>'.
         '   8. <span id="b64-15">�d1-e2</span> <span id="b64-16">�a5:c4</span>'.
         '   9. <span id="b64-17">d3:c4</span> ...'.
         '<p>����� ����������� ����� ��������� ��� 9. de. � ����� ����� ������� �����.'. 
         '<p>9. ...  <span id="b64-18">�f8-c5</span>'.
         '<p>����������� �������� 9. ... �e7 ��� 9. ... �d6.'. 
         '<p>10. <span id="b64-19">h2-h3</span> <span id="b64-20">0-0</span>'.
         '   11. <span id="b64-21">�f3-h2</span> <span id="b64-22">�f6-h7</span>'.
         '   12. <span id="b64-23">�b1-d2</span> ...'.
         '<p>������ ����� �������� ������� ����������. 12. �e3 ������ �������� ������.'. 
         '<p>13. ... <span id="b64-24">f7-f5</span>'.
         '   13. <span id="b64-25">�d2-b3</span> <span id="b64-26">�c5-d6</span>'.
         '   14. <span id="b64-27">0-0</span> <span id="b64-28">�d6:h2+</span>'.
         '   15. <span id="b64-29">��g1:h2</span> <span id="b64-30">f5-f4!</span>'.
         '<p>����������� ��������� � ������ �������.'. 
         '<p>16. <span id="b64-31">�e2:e4</span> <span id="b64-32">�h7-g5</span>'.
         '   17. <span id="b64-33">�e4-d4</span> ...'.
         '<p>��������� ����� ���� �� 17. �d3 � ������ �� 17. ... �f5 - 18. �d4.'. 
         '<p>17. ... <span id="b64-34">�g5-f3+</span>'.
         '   18. <span id="b64-35">g2:f3</span> <span id="b64-36">�d8-h4</span>'.
         '   19. <span id="b64-37">�f1-h1</span> <span id="b64-38">�c8:h3</span>'.
         '   20. <span id="b64-39">�c1-d2</span> <span id="b64-40">�f8-f6</span>'.
         '<p>� ������ ����������.'. 
          
         '</div>'.          
          
         '<div class="navigation">'.
         '  <a href="'.$link_begin.'">��������� ���� ChessAndMail</a>'.
         '</div>'.
         CPage_::get_metrika_yandex();
          
  
  CPage_::$title_ ='ChessAndMail: �����, ����������� ����� 1861 ���';
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