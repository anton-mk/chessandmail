<?php
  require_once('../const_.php');
  
  session_name(NAME_SESSION_);
  session_start();

  CPage_::$root_catalog ='../';
  $link_begin =(isset($_SESSION[SESSION_ID_]) ? CPage_::$root_catalog.'MainPage.php?link_=Events' : 'http://chessandmail.ru'); 
  
  CPage_::$header_ ='<h1>�����, ������ 1859 ���</h1>';
  $body_='<div class="narration">'.
         '   <image src="Image/WilliamLewis-GeorgeWalker-AlfredMongreden.jpg" alt="������ �����, ������ �����, ������� ����������" title="������ �����, ������ �����, ������� ����������"/>'.
         '   ��������� �� ������� ���� �� ������ � ������� ������� ������ ������'.
         '   ���������� ������, � ������� ���� �� ������� ������� � �������,�� ������ �.�����������.'.
         '   ���������� �� ����� ������� ������ �� ������, �� ��� ����� �������� ��������� �� �������� ���� ����.'.
         '   ���� ����� ��, ��������� � ������������� �������, ���������� ������� � �����. ���� ��������� �'.
         '   ��������� ������� �� ����, � �������� ������� �����������. �������������� ��� ���� ������ ���,'.
         '   ����-���� � ���� ���� �� ������, � ������� ��� ����� �����������.'.
         '<p>���������� �� ����� ������� ���� �������� ������������� � �����. �� ������ �������� ������ ������ �'.
         '   ������� �������� ������, �� ����� ��� �����, ��� ������. <a href="Paul-Charles-Morphy.php">���</a> �������� ����������� � ������� ���� ������'.
         '   ������, �� ���� ���� � ���������. �������������� ���� ��� ������, ��� ���������� ��� �������� � ����'.
         '   ������ ������ ������ ������, ���� � �� ����� ������� ����� �� ������ � ���� ��������.'.
         '<p>������������� � ��������, ����������� ����� � ������ ������ 1859 ����, ����� �� ���������� ���� ������'.
         '   � ������ ����������� �����������. ����� ������ � ���� ��� �� ������ ����������� ������� ������ ���'.
         '   ���� ��������, ������� ������������ � ���� �������. ����� ����� ������� �� ������� ������ ���� � �����!'.
         '   ���������� ���� ������� ������ ���� ����������� � ����� � ������. � �������� ���������� ���������'.
         '   ����������, �� ����� �� ��� �����������, ��� �������� �� ������������.'.
         '<p>����� 10 ������ ��� � ��� ������ � ������. � ���� ������ ���� ���� �� ������, �������� ��������� ����'.
         '   �� ������������������� ��������.'.
         '<p>��� �������� � ������, � ���������� ������ ��������� ��� �������������� ��������. �� ������� ���������,'.
         '   ����������� ���� ��������. ������ ������������, ��� ������� ������ � ������� �����, ����������,'.
         '   ����������� �������������. ������� � ����� �� ����������, ��������� �� ����� ���������� ����, ��������'.
         '   � ����� �����. ������, ������, ������ �������� �� ���� �������� <a href="Paul-Charles-Morphy.php">�����</a> ��� ��������?�, � ���������'.
         '   ��-�������: ������ �������� ������ ��� �� �����?� �� ���� ������ ������� ����, � ���� ��� 5 � 4 �� ��,'.
         '   ��� �������� ������ �� �����. �������� �����, ������� ��������� ������, ���� �������� ��������, � ��'.
         '   90 ��������� ��� ���� ������ ���������, ����������� �������� ���������. ����� � ��� ������� ��� � ���'.
         '   ��� ��������?��� ����� ������������ ���� �����. ���������� � �������������� ��������� ����� ��������'.
         '   ��������� �� �����������, ����� ��� ��� �������� ��������� � �������� ���� ���� ������� ����������.'.
         '   �� ��� �����, ��� � ������, �� � � ������� �������� ������� ������ ����, ���������, �� ������� ����.'.
         '   � ��� �� ������ ������ �� �������� ����. ����������� �� �� ��� ��������� ��������� ������; ���, ������'.
         '   ��, ������������ � ���� �� ���.'.
         '<p>��� ��� ���������, ��� ��������������� ���������! �� ������ ����� �� ������ � �������� �� ���� �������'.
         '   � ����������, ���������� ���������. ��������� �����. ������ �������� � ��������� ������ � �����. �'.
         '   ����������� ���� �������� ��������� � ������ ��� ��������� ��������...'.
         '<p>������ ��������� ��������� ������������� � ���� ���������� ���� ����������. ��� ������ ��� �������� �'.
         '   ������, ������� ������� �� ���� ������� ������ ���������� ���������������� ������. ��� ������� �����'.
         '   ����������, �� ����� �������, ��� ���-����� �� ����� ������ �� ����������, �������� �� �����������'.
         '   �������������. �� ����� ������ �� ����������, ������� � �������� ��� �������� ���� �����������. �����'.
         '   ��� �� ����, �� ��� ���-���� ���-���! ��� �������� �������� � ����� ��������. ������ �������� ��� ���'.
         '   ������, � ������� � �������. ���� �������, ���� ���� ������� �����������ࠖ ���� ������ ������, �������'.
         '   ��� ������� ��� �����-�� � ������ ��� ����� ������� � ������. ��� ����������� ����� �� �������� ������'.
         '   ������ �������. �� �� ����� ���� ������, �� ��������, �� ��� � ��������������� ������. ���� �������,'.
         '   ���������� ������� ����, �� ������� �������, �������� ����� ����� ������� �����-������ ������ � �������'.
         '   ���������: �� ���, <a href="Paul-Charles-Morphy.php">������ �����</a>?� ��� ������렖 � ��������� �������������� ����� ��������� �������.'.
         '   ��� ������ � ������ ��������, ��� ��������� ���������. �������� ����� ������ ��� �� ����� ����������'.
         '   ������ � ���������젖 ����������� ������. �� ����� ������� ���, ������ � ��������. ���� ������, ��'.
         '   ������� ��������� �������. �������� � ��������� �� ����, �� ������ ���� ���������������� ��������'.
         '   ������� ����������. ��� � ����� ����� � �������� �������� ��� ������. ����������, ��� ��� ��������� �'.
         '   ������������� ������ ���� � ������������ �������� ����. ���������� ���������� � ��������������'.
         '   ��������, ��� � ��������� ������� ������ �� ������ ��������, � ��� ������������ �������, �����'.
         '   ���������� ��������� ������������ � ���������� ���������� ��������, ������������ ���� ����������'.
         '<p>�����, �������� � ������� �����, ��� ��� � ����-������-����� ����� ����� ������� �� ������ ������.'.
         '   ������ ��� ��� �������, ��� � ������. ������ �����, �����, ��������, ������, ����������, ������, ���� �'.
         '   �����. ���� ������ ������� �������, ��� ��������� � ��� ����. �� �������� ����� �� ���� �����, �������'.
         '   � ����� � ������ � ������ ����� �� ����� ����������. ������ ������ ��� �������� �����, ������ ���������'.
         '   ������������ �������. ����� ������ ���� ������� ������ ���������� ���������� ���������, ����� �������'.
         '   ���� � �������. ������ ����� ��� ����� ���� ���� �����. ��� ������� � ����� ��������, �������� �������,'.
         '   �-��� �������, �������, ���������� � ������, ������ ����� � ������ ���� � �������, ��� ����� �������,'.
         '   �������� �������� ���� ������ ������ �������� ������ � ������. ����� ����� ������ � ����� ���� ���'.
         '   ������� ������������� ����. ���������� ������ �������� ���������� � ��������, ��� ������������ ���, ���,'.
         '   ��������, ���-��� ������ �� ������ � ����� ������� ������������� ������� ���������� ����.'.
         '<p>���������� ��������� ����������� � ����-���� ����� ��������� ���᠖ ����-����������. �� ��������� �'.
         '   ���� � �������� ���������� ��������������, � ���, ����������, �� ��� �������� ������ �������'.
         '   ����������. ������������ ���������, �� ����� ����� ����� � ������ �����, �� ������� ������������ ��'.
         '   �������� �������� ��������. ���� ����� ���������� ��������� � ������� ����� ���� �� ���� ������. ��'.
         '   ��� ��� ���� �� �����! � ������ ������ ���� ������ ��� ���������� ���������� �������, ������� �'.
         '   �������������� �������. ��� ��� ����� � ���� ������ ������������� ����: ���������, �� ������, �����,'.
         '   ���� � �����. ��� ������� � ����� � ������ ����� �� �������, ������ ����� � ���������� � ������� �'.
         '   �������� ������ ���� �������� ������ ������� ���������� ������. � ��� ������������ ������ �� ����'.
         '   ������� �� �����������, ������ ����� ������������.'.
         '<p>��� ��������� ������� ����������, �� �������� ��� ����� ������ ���������. �� ������� ��� ������, �'.
         '   ������� ������ ���� ����� ��� ������ ������ ����, ��������� �� � ������. ��������� ����� �����������'.
         '   � ��������� � ����� ��������� ������������ � ������� ��������� � �����������, � ��� ������ ����������'.
         '   ������ ���. �� ������� �� ������, � ���� ������ ������� ������ � ������������ � ����������� ���������'.
         '   �������, ���� ��� ������� �� ���� ��������� � �������������. ������ ��� ��� �����������, �����'.
         '   �������� ���������� � �������� ������� ���� ����� ����� � ����.'.
         '<p>����� ���� ����?�� ������� ���������� ���, ������ �� ������.'.
         '<p>����� �, ��렖 �������� ���������� ������.'.
         '<p>����, ����?�� ���������� ���. �� ����� ��� ����������� �����, ������� ����������� ��� � ���������'.
         '   ������������ ������. ��� ��� ���� ��������, ��� ��� ������� ������ ��������, �������� ��� ������'.
         '   ������� �� ���. �� ���� �������� ��� �����, � ���� ����������� ���������.'.
         '<p>�����-������ ��������� ����, ����?�� ������� ���, ���������. ��� ��� �� ���� ����� ��������������,'.
         '   ��� ��� �������� ��� �� �����? ����� ��� �������� � ��� �������!'.
         '<p>��� ���, ��� ������������!�� �������� ������� ��������.�� � ���� ��������� ���-����� ���� � ������,'.
         '   ���, � �� ��� ����� �� ������ � ���� ������ ������ ������� ������� ���� ��������� ��� � ��������� �'.
         '   ����� �������������.'.
         '<p>��� �� ��������� ���� � ������� ������, ����!�� ������ ��� ����������.�� ����� �� �����?'.
         '<p>��� ���� ����� �� �����������, ��� ������� �����������. ���� ��� ������, �� ����� ����� ������.'.
         '<p>��� ����������. ��� ������� ���������, ��� ������ � ���� ����� ���������, ��� �� ������ ���� ������ �'.
         '   �������� ���� ��������� �������� �� ������ �������? ���, ��� ��� ������, ������ �� ���! � �� ������ ��'.
         '   ���� ��������� �����������������:'.
         '<p>���� ����� ���������� ���, ����. ��� �������� ������� � ������� ��� ��� ��� ������ ���, �� ��� ���'.
         '   �����, ��� � ���� �� ������� ������. ��� ��� ����������, ����?'.
         '<p>��������, ���. ����� ����, ��� ����� ������?'.
         '<p>�����������. ����� � ���� ������ ����������. ����� ���, ����, ����������� ����. ��������� ���� �'.
         '   ������� ��, ��� � ��� �� ���� ������.'.
         '<p>���������, ���. ���������� ������!�� � �������� ����.'.
         '<p>� �������, �������� ������� ������ �� ��������� ����, � <a href="Paul-Charles-Morphy.php">���</a> ���������� ������� ����� �� ���������,'.
         '   ���������� �� ��������� ����� ��� ���, 30 ������ 1859 ����.'.
         '</div>'.
          
         '<div class="example-game">'.
         '  <h2>' .
         '    <span class="type-game">������������ ������</span><br/>' .
         '    �����&nbsp;-&nbsp;����������<br/>' .
         '    <span class="place-game">' .
         '      ������ ������,<br/>�����, 1859 ���'.
         '    </span>' .
         '  </h2>';
  
  $rg = new CRuleGame();
  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">' .
         '  <div class="game">' .
         '    <div class="board">' .
         CPage_::OutBoard($rg, 0.5, 'board52', 'b52-') .
         '      <div class="operations">' .
         '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b52-begin">' .
         '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b52-prev">' .
         '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b52-next">' .
         '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b52-last">' .
         '      </div>' .
         '    </div>' .
         '  </div>' .
         '</div>' .
  
         '   1. <span id="b52-1">e2-e4</span> <span id="b52-2">e7-e5</span>'.
         '   2. <span id="b52-3">�g1-f3</span> <span id="b52-4">d7-d5</span>'.
         '   3. <span id="b52-5">e4:d5</span> <span id="b52-6">e5-e4</span>'.
         '   4. <span id="b52-7">�d1-e2</span> <span id="b52-8">�d8-e7</span>'.
         '   5. <span id="b52-9">�f3-d4</span> <span id="b52-10">�e7-e5</span>'.
         '<p>����� 5. ... �f6.'. 
         '<p>6. <span id="b52-11">�d4-b5</span> <span id="b52-12">�f8-d6</span>'.
         '   7. <span id="b52-13">d2-d4</span> <span id="b52-14">�e5-e7</span>'.
         '   8. <span id="b52-15">c2-c4</span> <span id="b52-16">�d6-b4+</span>'.
         '   9. <span id="b52-17">�c1-d2</span> <span id="b52-18">�b4:d2+</span>'.
         '   10. <span id="b52-19">�b1:d2</span> <span id="b52-20">a7-a6</span>'.
         '   11. <span id="b52-21">�b5-c3</span> ...'.
         '<p>����� 11. d6, ��� �������� �����, ����� �������� �� ���� �������� ������������. ��������, 11. ... cd'.
         '   12. �:e4 ab 13. �:d6+ ��f8+! 14. �:c8 �c7, � ������ ������ �������� ������������. ��� ���������'.
         '   ����������� � ������ ��� ������� ������.'. 
         '<p>11. ...  <span id="b52-22">f7-f5</span>'.
         '   12. <span id="b52-23">0-0-0</span> <span id="b52-24">�g8-f6</span>'.
         '   13. <span id="b52-25">�d1-e1</span> <span id="b52-26">0-0</span>'.
         '   14. <span id="b52-27">f2-f3</span> <span id="b52-28">b7-b5</span>'.
         '   15. <span id="b52-29">f3:e4</span> <span id="b52-30">f5:e4</span>'.
         '   16. <span id="b52-31">�d2:e4</span> <span id="b52-32">b5:c4</span>'.
         '   17. <span id="b52-33">�e2:c4</span> <span id="b52-34">��g8-h8</span>'.
         '   18. <span id="b52-35">�f1-d3</span> <span id="b52-36">�c8-b7</span>'.
         '   19. <span id="b52-37">�e4:f6</span> <span id="b52-38">�e7:f6</span>'.
         '   20. <span id="b52-39">�h1-f1</span> <span id="b52-40">�f6-d8</span>'.
         '<p>����� ����� ����� �����������.'. 
         '<p>21. <span id="b52-41">�f1-f8</span> <span id="b52-42">�d8-f8</span>'.
         '   22. <span id="b52-43">�c4-b4</span>. � ����� ����������.'.
          
         '  <h2>' .
         '    <span class="type-game">����� ���� �����</span><br/>' .
         '    �� ������&nbsp;-&nbsp;�����<br/>' .
         '    <span class="place-game">' .
         '      ����� � ����� ����-�����,<br/>������, 26 ������ 1859 ����'.
         '    </span>' .
         '  </h2>';

  $rg = new CRuleGame();
  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">' .
         '  <div class="game">' .
         '    <div class="board">' .
         CPage_::OutBoard($rg, 0.5, 'board014', 'b014-') .
         '      <div class="operations">' .
         '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b014-begin">' .
         '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b014-prev">' .
         '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b014-next">' .
         '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b014-last">' .
         '      </div>' .
         '    </div>' .
         '  </div>' .
         '</div>' .
          
         '   1. <span id="b014-1">e4</span> <span id="b014-2">e5</span>'.
         '   2. <span id="b014-3">�f3</span> <span id="b014-4">�c6</span>'.
         '   3. <span id="b014-5">�c4</span> <span id="b014-6">�f6</span>'.
         '   4. <span id="b014-7">�g5</span> <span id="b014-8">d5</span>'.
         '   5. <span id="b014-9">ed</span> <span id="b014-10">�a5</span>'.
         '   6. <span id="b014-11">d3</span> <span id="b014-12">h6</span>'.
         '   7. <span id="b014-13">�f3</span> <span id="b014-14">e4</span>'.
         '   8. <span id="b014-15">�e2</span> <span id="b014-16">�:c4</span>'.
         '   9. <span id="b014-17">dc</span> <span id="b014-18">�c5</span>'.
         '   10. <span id="b014-19">h3</span> <span id="b014-20">0-0</span>'.
         '   11. <span id="b014-21">�h2</span> <span id="b014-22">�h7</span>'.
         '   12. <span id="b014-23">�c3</span> <span id="b014-24">f5</span>'.
         '   13. <span id="b014-25">�e3</span> <span id="b014-26">�b4</span>'.
         '   14. <span id="b014-27">�d2</span> <span id="b014-28">�d7</span>'.
         '   15. <span id="b014-29">g3</span> <span id="b014-30">�e7</span>'.
         '   16. <span id="b014-31">a3</span> <span id="b014-32">�d6</span>'.
         '   17. <span id="b014-33">�e2</span> <span id="b014-34">b5</span>'.
         '   18. <span id="b014-35">cb</span> <span id="b014-36">�:b5</span>'.
         '   19. <span id="b014-37">�d4</span> <span id="b014-38">�c4</span>'.
         '   20. <span id="b014-39">�e6</span> <span id="b014-40">�fe8</span>'.
         '   21. <span id="b014-41">�d4</span> <span id="b014-42">�a6</span>'.
         '   22. <span id="b014-43">c4</span> <span id="b014-44">c5</span>'.
         '   23. <span id="b014-45">�c3</span> <span id="b014-46">�c8</span>'.
         '   24. <span id="b014-47">�f4</span> <span id="b014-48">�b8</span>'.
         '   25. <span id="b014-49">�b1</span> <span id="b014-50">g5</span>'.
         '   26. <span id="b014-51">�e2</span> <span id="b014-52">�f8</span>'.
         '   27. <span id="b014-53">h4</span> <span id="b014-54">�g6</span>'.
         '   28. <span id="b014-55">hg</span> <span id="b014-56">hg</span>'.
         '   29. <span id="b014-57">�c1</span> <span id="b014-58">�e5</span>'.
         '   30. <span id="b014-59">�:g5</span> <span id="b014-60">�d3+</span>'.
         '   31. <span id="b014-61">��f1</span> <span id="b014-62">�g7!</span>'.
         '   32. <span id="b014-63">�d2</span> <span id="b014-64">�:b2</span>'.
         '   33. <span id="b014-65">�c2</span> <span id="b014-66">�a6</span>'.
         '   34. <span id="b014-67">�c1</span> <span id="b014-68">�:c4</span>'.
         '   35. <span id="b014-69">�a4</span> <span id="b014-70">�d2+</span>'.
         '   36. <span id="b014-71">��g2</span> <span id="b014-72">�:b1</span>'.
         '   37. <span id="b014-73">�:a6</span> <span id="b014-74">�b6</span>'.
         '   38. <span id="b014-75">�a4</span> <span id="b014-76">�eb8</span>'.
         '   39. <span id="b014-77">�f1</span> <span id="b014-78">�e5</span>'.
         '   40. <span id="b014-79">�e3</span> <span id="b014-80">f4</span>'.
         '   41. <span id="b014-81">�:f4</span> <span id="b014-82">�:f4</span>'.
         '   42. <span id="b014-83">�f5</span> <span id="b014-84">�f7</span>'.
         '   43. <span id="b014-85">�:f4</span> <span id="b014-86">�:f5</span>'.
         '   44. <span id="b014-87">�:b8</span> <span id="b014-88">�:b8</span>'.
         '   45. <span id="b014-89">�:a7</span> <span id="b014-90">�f8</span>'.
         '   46. <span id="b014-91">�:c5</span> <span id="b014-92">�f3+</span>'.
         '   47. <span id="b014-93">��g1</span> <span id="b014-94">�c3</span>'.
         '   48. <span id="b014-95">�h4</span> <span id="b014-96">�e2+</span>'.
         '   49. <span id="b014-97">��h2</span> <span id="b014-98">�:f2</span>'.
         '   50. <span id="b014-99">�:f2</span> <span id="b014-100">�:f2</span>'.
         '   51. <span id="b014-101">��h3</span> <span id="b014-102">�g1+</span>'.
         '   52. <span id="b014-103">��g4</span> <span id="b014-104">e3</span>'.
         '   53. <span id="b014-105">��h5</span> <span id="b014-106">e2</span>'.
         '   54. <span id="b014-107">�e4</span> <span id="b014-108">�f1</span> � ������ ����������.'. 
          
         '  <h2>' .
         '    <span class="type-game">��������� ������</span><br/>' .
         '    ���������&nbsp;-&nbsp;�����<br/>' .
         '    <span class="place-game">' .
         '      ����� � ����� ����-�����,<br/>������, 26 ������ 1859 ����'.
         '    </span>' .
         '  </h2>';

  $rg = new CRuleGame();
  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">' .
         '  <div class="game">' .
         '    <div class="board">' .
         CPage_::OutBoard($rg, 0.5, 'board016', 'b016-') .
         '      <div class="operations">' .
         '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b016-begin">' .
         '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b016-prev">' .
         '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b016-next">' .
         '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b016-last">' .
         '      </div>' .
         '    </div>' .
         '  </div>' .
         '</div>' .

         '   1. <span id="b016-1">e4</span> <span id="b016-2">e5</span>'.
         '   2. <span id="b016-3">�f3</span> <span id="b016-4">�c6</span>'.
         '   3. <span id="b016-5">�b5</span> <span id="b016-6">a6</span>'.
         '   4. <span id="b016-7">�a4</span> <span id="b016-8">�f6</span>'.
         '   5. <span id="b016-9">0-0</span> <span id="b016-10">�e7</span>'.
         '   6. <span id="b016-11">d4</span> <span id="b016-12">ed</span>'.
         '   7. <span id="b016-13">e5</span> <span id="b016-14">�e4</span>'.
         '   8. <span id="b016-15">C:c6</span> <span id="b016-16">dc</span>'.
         '   9. <span id="b016-17">�:d4</span> <span id="b016-18">�f5</span>'.
         '   10. <span id="b016-19">�c3</span> <span id="b016-20">�c5</span>'.
         '   11. <span id="b016-21">�:d8</span> <span id="b016-22">�:d8</span>'.
         '   12. <span id="b016-23">�h4</span> <span id="b016-24">�:c3</span>'.
         '   13. <span id="b016-25">�:f5</span> <span id="b016-26">�e2+</span>'.
         '   14. <span id="b016-27">��h1</span> <span id="b016-28">g6</span>'.
         '   15. <span id="b016-29">�g3</span> <span id="b016-30">�:g3+</span>'.
         '   16. <span id="b016-31">hg</span> <span id="b016-32">h6</span>'.
         '   17. <span id="b016-33">�b1</span> <span id="b016-34">��e7</span>'.
         '   18. <span id="b016-35">b4</span> <span id="b016-36">�d4</span>'.
         '   19. <span id="b016-37">f4</span> <span id="b016-38">��e6</span>'.
         '   20. <span id="b016-39">�b3</span> <span id="b016-40">h5</span>'.
         '   21. <span id="b016-41">�d3</span> <span id="b016-42">�b6</span>'.
         '   22. <span id="b016-43">�fd1</span> <span id="b016-44">�:d3</span>'.
         '   23. <span id="b016-45">�:d3</span> <span id="b016-46">��f5</span>'.
         '   24. <span id="b016-47">�b2</span> <span id="b016-48">�h7</span>'.
         '   25. <span id="b016-49">�d4</span> <span id="b016-50">h4</span>'.
         '   26. <span id="b016-51">�:b6</span> <span id="b016-52">hg+</span>'.
         '   27. <span id="b016-53">��g1</span> <span id="b016-54">cb</span>'.
         '   28. <span id="b016-55">�d7</span> <span id="b016-56">��e6</span>'.
         '   29. <span id="b016-57">�:b7</span> <span id="b016-58">�h4</span>'.
         '   30. <span id="b016-59">�:b6</span> <span id="b016-60">�:f4</span>'.
         '   31. <span id="b016-61">�:c6+</span> <span id="b016-62">��:e5</span>'.
         '   32. <span id="b016-63">�c5+</span> <span id="b016-64">��d6</span>'.
         '   33. <span id="b016-65">�g5!</span> <span id="b016-66">�:b4</span>'.
         '   34. <span id="b016-67">�:g3</span> <span id="b016-68">�a4</span>'.
         '   35. <span id="b016-69">a3</span> <span id="b016-70">�c4</span>'.
         '   36. <span id="b016-71">�d3+</span> <span id="b016-72">��e6</span>'.
         '   37. <span id="b016-73">�b3</span> <span id="b016-74">�:c2</span>'.
         '   38. <span id="b016-75">�b6+</span> <span id="b016-76">��f5</span>'.
         '   39. <span id="b016-77">�:a6</span> <span id="b016-78">g5</span>'.
         '   40. <span id="b016-79">�b6</span> <span id="b016-80">�a2</span>'.
         '   41. <span id="b016-81">�b3</span> <span id="b016-82">g4</span>'.
         '   42. <span id="b016-83">�b5+</span> <span id="b016-84">��f4</span>'.
         '   43. <span id="b016-85">�b3</span> <span id="b016-86">f5</span>'.
         '   44. <span id="b016-87">g3+</span> <span id="b016-88">��e4</span>'.
         '   45. <span id="b016-89">��f1</span> <span id="b016-90">��e5</span>'.
         '   46. <span id="b016-91">��g1</span> <span id="b016-92">f4</span>'.
         '   47. <span id="b016-93">�b4</span>. �����.'.
          
          
          '</div>'.          
          
         '<div class="navigation">'.
         '  <a href="'.$link_begin.'">��������� ���� ChessAndMail</a>'.
         '</div>'.
         CPage_::get_metrika_yandex();
          
  
  CPage_::$title_ ='ChessAndMail: �����, ������ 1859 ���';
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
