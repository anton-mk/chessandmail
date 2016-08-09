<?php
  require_once('../const_.php');
  
  session_name(NAME_SESSION_);
  session_start();

  CPage_::$root_catalog ='../';
  $link_begin =(isset($_SESSION[SESSION_ID_]) ? CPage_::$root_catalog.'MainPage.php?link_=Events' : 'http://chessandmail.ru'); 
  
  CPage_::$header_ ='<h1>����� ���������� ���������</h1>';
  $body_='<div class="narration">'.
         ' <image src="Image/Morphy-final-American-chess-Congress.jpg"'.
         '        alt="��� ���������� �. ����� ������� ������ �� ������ �������� �� ��� ������������ - �. ��. �������."'.
         '        title="��� ���������� �. ����� ������� ������ �� ������ �������� �� ��� ������������ - �. ��. �������."/>'.
         '   � ������ �������� ������� ��������� <a href="Paul-Charles-Morphy.php">���</a> � ������������ ��������� ������ ��������. ������'.
         '   �������������: �������-�� ������������ �������, �������� ������, ��������� ������������� ��������'.
         '   �� ������.'.
         '<p>�������-�� ���� �������! ��������� ������� �������.'.
         '<p>��������, ����������, ���������� ������� ��������� ��������: ���� ����� ����� ����������� ������'.
         '   �������� �� ���� ������������ ��� ������ ������.'.
         '<p>��������� ������ ������. �������� ������ ������� ��� ��������, ������� �� ������� ������� ���������'.
         '   �����. ��� ������� ����, �� �� ������� �� ��� ��������. �� ����� ���� ������ ������ (����� ������,'.
         '   ������ � ���� ������� �� ���� �� ������) � ��������� ������� ����� � �������� ����������. ������'.
         '   ���� ������, ���-���� � ��� ������ ������� �� �������. ����� ������ ����� ��������, ������ ����'.
         '   ����� ������� � ������������� ������, ������� ��������� �� �����. ������ �������� ������ ��� �����'.
         '   ������� �����������, � ���� �� ��� ����� (� ����� ��� �� ����) �� ���������� �������� �������'.
         '   ���������. �������������� ��� �������� ����� � ���������. ������ ������ ������� � ���� ����� ������'.
         '   �����.'.
         '<p><a href="Paul-Charles-Morphy.php">����</a> ������� �������� ������ ������ ����� �������, ������������� ������. ������ ������ �������'.
         '   ���������� �����. ��� � � ��� ������� ����������� ���������, �� ����������� � ������� ������. �� ��'.
         '   ��� �������� ���� ���� �����������, ����� ���� ������ � � ���������� �������� �� ��������� ����'.
         '   ������ ������ �����. ��� ���� ������ ��������� �� ����� ���...'.
         '<p>�������� �������� �������� ����, ������� �� ������ ������� ���� ������������ � ������ ������� �����.'.
         '   ������� ��� ������� �������? ������ �� ������, �� ������ ���-�� �������������, � ��� ��������'.
         '   ���������� ��� ������ ���� ����� ��������� ����� �������. � ���, ������� ����������� � �������'.
         '   ������. ����� �� ����� ������� � ��������� � ��������� �������.'.
         '<p>���� ������� ��� �� ������. �� �� �������, �� ������� ������� ��������� ��� �������� ������, � �'.
         '   ���� ����� ���� ������� ������ ��������. ��������� ������ ���������� ������, ���� � ��������� �'.
         '   ������������� ����.'.
         '<p>� ��������� ������ (����� ���� ������������) ��� ����������� ����� � ������. �������� �����������'.
         '   ������ ������, ������ ����� ��������� ����� ����� ����� � ������ �����.'.
         '<p>��� ��� �������.'.
         '<p>������ ��� ��������� ����������. <a href="Paul-Charles-Morphy.php">���</a> ����� ����������. � ��� ������ ����� ���������� ��������'.
         '   ����������� ����, �� ����� ���������, �������������, ���������� ������ � �����. � ����� �� ������'.
         '   �� ����������� ��������� ����� �� ������ ������. ��� ����� � ��� ����� ������� ����� �������'.
         '   ����������, ��� ����� ���� ��������� ������� �� �������� ������� ����.'.
         '<p>�������� ��������� ������ � ���������, �� ������ �� ��� ���������������� ���, ����� �����������'.
         '   � ��������. ��� ������� ��� ��� ������ ������ � �������� ���� �� ������ 5:1 ��� ���� ������.'.
         '<p>���� ��� ���� ����������, ������� ������ ��������...'.
         ' <image src="Image/Morphy-first-prize.jpg"'.
         '        alt="������ ����, ������� ������� ��� ����� �� ������ �� ������������ ��������� 1857 ����."'.
         '        title="������ ����, ������� ������� ��� ����� �� ������ �� ������������ ��������� 1857 ����."/>'.
         '<p>�� �������� �����������, ������� ���� �������� ���������� �������, ��� ������� � ������� ��������'.
         '   ������ ������ �� ����� ���������� �������� � ���������� �����������. �� ������ ���� ����'.
         '   �������������, ���� � � ����� ���� ��� �������������.'.
         '<p>���-�������� � �������������� ������ ��������� � ���� ������� �������. ��� ��� ���������� ������ �'.
         '   ������ �������� ����������� ������, � ��� ����������� ����� ����� �� ��������������� �� �����������,'.
         '   � ���� ������. ������ �� ������ �������� ����, ��� �������� <a href="Paul-Charles-Morphy.php">����</a> ������������ ��������� ���� �������,'.
         '   ������ �����, ��������� � ��������� �������.'.
         '</div>'.
          
         '<div class="example-game">'.
         '  <h2>'.
         '    <span class="type-game">����������� ������</span><br/>'.
         '    ����������&nbsp;&nbsp;�����<br/>'.
         '    <span class="place-game">'.
         '      ���-���� 22 ������� 1857'.
         '    </span>'.
         '  </h2>';
          
  $rg =new CRuleGame();
  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">'.
          '  <div class="game">'.
          '    <div class="board">'.
                 CPage_::OutBoard($rg,0.5,'board10','b10-').
          '      <div class="operations">'.
          '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b10-begin">'.
          '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b10-prev">'.
          '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b10-next">'.
          '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b10-last">'.
          '      </div>'.
          '    </div>'.
          '  </div>'.
          '</div>'.

          '   1. <span id="b10-1">e2-e4</span>  <span id="b10-2">e7-e5</span>'.
          '   2. <span id="b10-3">�g1-f3</span> <span id="b10-4">�b8-c6</span>'.
          '   3. <span id="b10-5">d2-d4</span> <span id="b10-6">e5:d4</span>'.
          '   4. <span id="b10-7">�f1-c4</span> <span id="b10-8">�g8-f6</span>'.
          '   5. <span id="b10-9">e4-e5</span> ...'.
          '<p>���������������� 5. 0-0 �:e4 6. �e1 d5 7. �:d5 �:d5 8. �c3 �h5 (�f5) 9. �:�4.'.
          '<p>5. ... <span id="b10-10">d7-d5</span>'.
          '   6. <span id="b10-11">�c4-b5</span> <span id="b10-12">�f6-e4</span>'.
          '   7. <span id="b10-13">�f3:d4</span> <span id="b10-14">�c8-d7</span>'.
          '<p>�� ������� ���� ����� ����� ��� 7. ... �c5!, ������� � ����� ������� ����, ��������:'.
          '   7. ... �c5 8. �:c6 �:f2+ 9. ��f1 �h4! 10. �:d5! (10. �d4+ �� ����� ����� 10. ... c6 11. �:c6 �g3+'.
          '   12. ��:f2 �e4+ 13. ��e3 �f2+ 14. ��d3 bc 15. �:c6+ ��e7 16. �:d5! �f5) 10. ... �c5 11. g3 (� �����'.
          '   11. �d8+ �:d8 12. �:d8+ ��:d8 13. �d2 �:d2 14. �:d2 c6 15. �e2 �e6 ��������� ������ �����)'.
          '   11. ... �h3+ (��� ������� 11. ... �h3+) 12. ��e1 �f2+ 13. ��e2 �g4+ 14. ��d3 �c5+ 15. ��c3 0-0'.
          '   16. �e7+ ��h8 17. �d1 �e4+. �������, ��� ������������ ���� ��� ������� ������� ���� �������.'.
          '<p>8. <span id="b10-15">�d4:c6</span> <span id="b10-16">b7:c6</span>'.
          '   9. <span id="b10-17">�b5-d3</span> <span id="b10-18">�f8-c5</span>'.
          '   10. <span id="b10-19">�d3:e4</span> <span id="b10-20">�d8-h4</span>'.
          '<p>� ���� ������ ���� ��������� �������� ������� ������� ��������� ������� � ������� �������������� ������.'.
          '<p>11. <span id="b10-21">�d1-e2</span> <span id="b10-22">d5:e4</span>'.
          '   12. <span id="b10-23">�c1-e3</span> ...'.
          '<p>����� ��������� ����������. ������ �� ��� �������� � ������ ����������� ���������.'.
          '<p>12. ... <span id="b10-24">�d7-g4</span>'.
          '   13. <span id="b10-25">�e2-c4</span> <span id="b10-26">�c5:e3</span>'.
          '   14. <span id="b10-27">g2-g3</span> ...'.
          '<p>�� 14. �:c6+ ��������� 14. ... �d7 15. �:a8+ ��e7 16. g3 �:f2+ 17. ��:f2 e3+ 18. ��g1 (���� 18. ��e1,'.
          '   �� 18. ... �b4+ 19. c3 �:b2 20. �:h8 �h4) 18. ... e2'.
          '<p>14. ... <span id="b10-28">�h4-d8</span>'.
          '   15. <span id="b10-29">f2:e3</span> <span id="b10-30">�d8-d1+</span>'.
          '   16. <span id="b10-31">��e1-f2</span> <span id="b10-32">�d1-f3+</span>'.
          '   17. <span id="b10-33">��f2-g1</span> ...'.
          '<p>���� 17. ��e1, �� 17. ... �:e3+ 18. ��f1 �h3x.'.
          '<p>17. ... <span id="b10-34">�g4-h3</span>'.
          '   18. <span id="b10-35">�c4:c6+</span> <span id="b10-36">��e8-f8</span>'.
          '   19. <span id="b10-37">�c6:a8+</span> <span id="b10-38">��f8-e7</span>'.
          '<p>����� �������.'.
          
         '  <h2>'.
         '    <span class="type-game">������� ������</span><br/>'.
         '    �����&nbsp;&nbsp;����������<br/>'.
         '    <span class="place-game">'.
         '      ���-���� 23 ������� 1857'.
         '    </span>'.
         '  </h2>'.
          
         '   1. <span id="b11-1">e2-e4</span>   <span id="b11-2">e7-e5</span>'.
         '   2. <span id="b11-3">�f1-c4</span>  <span id="b11-4">�g8-f6</span>'.
         '   3. <span id="b11-5">�g1-f3</span>  <span id="b11-6">�f6:e4</span>'.
         '   4. <span id="b11-7">�b1-c3</span>  ...'.
         '<p>���� �������, ������������ �. ����������, �� ������ ��������, �� ���� ��� ������ �����������'.
         '   ������ � ������� �����'.
         '<p>4. ...<span id="b11-8">d7-d5</span>'.
         '<p>������ ���������� ���������� ����� � �������� ���������� ����. ��� ���� ����, ������, ����� ��������'.
         '   ��� 4. ... �c6! �� 5. �:e4 ������ �������� 5. ... d5, � �� 5. �:f7+ ������� ����� 5. ...��:f7'.
         '   6. �:e4 d5 � ������� �������. �����, ����� ���������� ���� � ��������� �����, ������ ���� ��'.
         '   ����������: 5. 0-0 �:c3 6. dc �e7 7. �e1 d6 8. �d4!'.
         '<p>5. <span id="b11-9">�c4:d5</span>  ...'.
         '<p>����������� �������� 5. �:d5 c6 6. �e3.';
          
  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">'.
          '  <div class="game">'.
          '    <div class="board">'.
                 CPage_::OutBoard($rg,0.5,'board11','b11-').
          '      <div class="operations">'.
          '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b11-begin">'.
          '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b11-prev">'.
          '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b11-next">'.
          '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b11-last">'.
          '      </div>'.
          '    </div>'.
          '  </div>'.
          '</div>'.
          
          '<p>5. ... <span id="b11-10">�e4-f6</span>'.
          '   6. <span id="b11-11">�d5-b3</span>  <span id="b11-12">�f8-d6</span>'.
          '   7. <span id="b11-13">d2-d3</span>   <span id="b11-14">0-0</span>'.
          '   8. <span id="b11-15">h2-h3</span>   <span id="b11-16">h7-h6</span>'.
          '   9. <span id="b11-17">�c1-e3</span>  <span id="b11-18">�b8-c6</span>'.
          '   10. <span id="b11-19">�d1-d2</span>  <span id="b11-20">�c6-a5</span>'.
          '   11. <span id="b11-21">g2-g4</span>   <span id="b11-22">�a5:b3</span>'.
          '   12. <span id="b11-23">a2:b3</span>   <span id="b11-24">�c8-d7</span>'.
          '   13. <span id="b11-25">�h1-g1</span>  <span id="b11-26">�f6-h7</span>'. 
          '   14. <span id="b11-27">�c3-e4</span>  <span id="b11-28">��g8-h8</span>'. 
          '   15. <span id="b11-29">g4-g5</span>   <span id="b11-30">h6-h5</span>'.
          '   16. <span id="b11-31">�f3-h4</span>  <span id="b11-32">g7-g6</span>'.
          '   17. <span id="b11-33">�d2-e2</span>  <span id="b11-34">�d7-c6</span>'.
          '<p>����� ���� �������� ����� �� ��������� c8-h3'.
          '<p>18. <span id="b11-35">f2-f4</span> ...'.
          '<p>����� � ������� �����������, � ������� ������ �� ����� �����������'.
          '<p>18. ... <span id="b11-36">e5:f4</span>'.
          '   19. <span id="b11-37">�e3-d4+</span>  <span id="b11-38">��h8-g8</span>'.
          '   20. <span id="b11-39">�h4-f5!</span>  <span id="b11-40">�f8-e8</span>'.
          '<p>������ �� ����� ����� ���� �� f5 ��-�� 21. �f6+, � ������ �������, �� ������ ���������������'.
          '   ���, ������� ���������� ������ � ����������� ��������. ����� ��������, ��� ��� ���������� ���'.
          '   ������ ��� ������. � ����� ������� ���� ���������� ����������� ����������, �������������� ����������'.
          '   ����� ���������� ���������� �� �����'.
          '<p>21. <span id="b11-41">�f5-h6+</span>  <span id="b11-42">��g8-f8</span>'.
          '   22. <span id="b11-43">0-0-0</span>   <span id="b11-44">�c6:e4</span>'.
          '<p>���� 22. ... �:g5, �� 23. �:f7! �:f7 24. �:g6'.
          '<p>23. <span id="b11-45">d3:e4</span>   <span id="b11-46">�d8-e7</span>'. 
          '   24. <span id="b11-47">e4-e5</span>  ...'.
          '<p>������ ����� ������. ����� ����������� ���� ������������ �� ������� �����������'.
          '<p>24. ... <span id="b11-48">�d6:e5</span>'.
          '<p>� ��� ����������� 24. ... �e6 25. �de1 �e7 26. h4 ����� ����������, ����� ����� �f�.'.
          '<p>25. <span id="b11-49">�d4:e5</span>  <span id="b11-50">�e7:e5</span>'.
          '   26. <span id="b11-51">�d1-d7</span>  <span id="b11-52">�e5-g7</span>'.
          '<p>������������� ���������� ���������! ������, �������, ��� ����� ��������: 26. ... �:g5! 27. �:g5 �f6'.
          '   28. �c4 (���� 28. �h5?, �� 28. ... �ad8) 28. ... �e1+! 29. ��d2 (����� ����, ����� ���� �� 29. �d1)'.
          '   29. ...�e7 30. ... �:e7 �:e7 31. �e5 �f6 � ����� 32. ... ��g7 ��� 32. ... �d8+.'.
          '<p>27. <span id="b11-53">�e2-c4</span>  <span id="b11-54">�e8-e7</span>'.
          '   28. <span id="b11-55">�d7:e7</span>  <span id="b11-56">��f8:e7</span>'.
          '   29. <span id="b11-57">�g1-e1+</span>.'.
          '<p>������ �������.'.
          
         '  <h2>'.
         '    <span class="type-game">����� ������� �����</span><br/>'.
         '    ��������&nbsp;&nbsp;�����<br/>'.
         '    <span class="place-game">'.
         '      ���-���� 8 ������ 1857'.
         '    </span>'.
         '  </h2>'.
  
         '   1. <span id="b12-1">e2-e4</span>   <span id="b12-2">e7-e5</span>'.
         '   2. <span id="b12-3">�g1-f3</span>  <span id="b12-4">�b8-c6</span>'.
         '   3. <span id="b12-5">�b1-c3</span>  <span id="b12-6">�g8-f6</span>'.
         '   4. <span id="b12-7">�f1-b5</span>  <span id="b12-8">�f8-c5</span>'.
         '<p>��� ������ ���� �� ����� �������� ������ ��������� ���� �����'.
         '<p>5. <span id="b12-9">0-0</span> ...'.
         '<p>����������� �������� 5. �:e5'.
         '<p>5. ... <span id="b12-10">0-0</span>'.
         '   6. <span id="b12-11">�f3:e5</span>  <span id="b12-12">�f8-e8</span>'.
         '   7. <span id="b12-13">�e5:c6</span> ...'.
         '<p>����� ����� �� �������� ������ ������, ����� 7. �f3! �:e4 8. d4 �:c3 9. bc �f8! 10. d5 �e5'.
         '   11. �:e5 �:e5 12. �f4.'.
         '<p> 7. ... <span id="b12-14">d7:c6</span>'.
         '   8. <span id="b12-15">�b5-c4</span>  <span id="b12-16">b7-b5</span>'.
         '<p>�������� 8. ... �:e4 9. �:e4 �:e4 10. �:f7+ ��:f7 11. �f3+.'.
         '<p>9. <span id="b12-17">�c4-e2</span>  <span id="b12-18">�f6:e4</span>'.
         '   10. <span id="b12-19">�c3:e4</span> ...'.
         '<p>�� 10. �f3 ������ ��������� ����: 10. ... �:f2 11. �:f2 �d4 12. �e4 (���� 12. �f1, �� 12. ... �:f2+'.
         '   � ����� 13. �e1x) 12. ... �:e4 13. �:e4 �:f2+ 14. ��h1 �g4 15. �f3 �e8.'.
         '<p>10. ... <span id="b12-20">�e8:e4</span>'.
         '   11. <span id="b12-21">�e2-f3</span> ...';

  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">'.
          '  <div class="game">'.
          '    <div class="board">'.
                 CPage_::OutBoard($rg,0.5,'board12','b12-').
          '      <div class="operations">'.
          '        <img src="Image/left_begin.png" alt="������� � ������ ������" title="������� � ������ ������" id="b12-begin">'.
          '        <img src="Image/left.png" alt="���������� ���" title="���������� ���" id="b12-prev">'.
          '        <img src="Image/right.png" alt="��������� ���" title="��������� ���" id="b12-next">'.
          '        <img src="Image/right_end.png" alt="������� � ����� ������" title="������� � ����� ������" id="b12-last">'.
          '      </div>'.
          '    </div>'.
          '  </div>'.
          '</div>'.
          '<p>����� 11. c3.'.
          '<p>11. ... <span id="b12-22">�e4-e6</span>'.
          '   12. <span id="b12-23">c2-c3?</span>  ...'.
          '<p>����� ������ ����������� � �� ��������� ������������� ������. ���� ��� ������������ ��������� ���������,'.
          '   � �� ������ ���� �. ��������� ������ ���� ������� ����� ������ ����. ����� 12. d3 ����� ����� ��'.
          '   �� ������� ���� ������ ������'.
          '<p>12. ... <span id="b12-24">�d8-d3</span>'.
          '<p>��� �� ������, ��� � ������. �������� ����� ����� �����������.'.
          '<p>13. <span id="b12-25">b2-b4</span>  <span id="b12-26">�c5-b6</span>'.
          '   14. <span id="b12-27">a2-a4</span>  <span id="b12-28">b5:a4</span>'.
          '   15. <span id="b12-29">�d1:a4</span> <span id="b12-30">�c8-d7</span>'.
          '<p>���������� 15. ... �b7, ����� �� ��������� ����� �� a6: 15. ... �b7 16. �a2 �ae8'.
          '   17. �d1! �a6. ��������� ����������� ���� ��� ����������� ������ ������.'.
          '<p>16. <span id="b12-31">�a1-a2?</span> ...'.
          '<p>�������� ������ - 16. �a6! ����������� ����.'.
          '<p>16. ... <span id="b12-32">�a8-e8</span>'.
          '   17. <span id="b12-33">�a4-a6</span> ...'.
          '<p>������ ������� ����� � ��� ���� (17. ... �:f1+). ��� � ������ ������������ �����, ������ �������'.
          '   ����������� ��� ����� ������� � �������� ������ �����.'.
          '<p>17. ... <span id="b12-34">�d3:f3</span>'.
          '   18. <span id="b12-35">g2:f3</span>   <span id="b12-36">�e6-g6+</span>'.
          '   19. <span id="b12-37">��g1-h1</span> <span id="b12-38">�d7-h3</span>'.
          '   20. <span id="b12-39">�f1-d1</span> ...'.
          '<p>���� 20. �d3, �� ������ ����� 20. ... f5 21. �c4+ ��f8! ��������� ������������. �� 20. �g1'.
          '   ��������� ��� � ��� ����.'.
          '<p>20. ... <span id="b12-40">�h3-g2+</span>'.
          '   21. <span id="b12-41">��h1-g1</span> <span id="b12-42">�g2:f3+</span>'.
          '   22. <span id="b12-43">��g1-f1</span> <span id="b12-44">�f3-g2+</span>'.
          '<p>����� ������ �������� ����������� �������� ��� � ������ ����, � ������: 22. ... �g2! 23. �d3!'.
          '   (���� 23. �:b6, �� 23. ... �:h2 � ����� 24. ... �h1x) 23. ... �:f2+ 24. ��g1 �g2+ 25. ��h1'.
          '   (25. ��f1 �g1x) ��� 23. �e2 �:e2 24. d4 �:h2 25. �:e2 �h1x'.
          '<p>23. <span id="b12-45">��f1-g1</span> <span id="b12-46">�g2-h3+</span>'.
          '<p>������ ���� � ������, ������� ������� � ������ �������, ����� ��� �������� �������� ������ �����'.
          '   � ������ ����: 23. �e4+ 24. ��f1 �f5! 25. �e2 �h3+ 26. ��e1 �g1x.'.
          '<p>24. <span id="b12-47">��g1-h1</span> <span id="b12-48">�b6:f2</span>'.
          '   25. <span id="b12-49">�a6-f1</span>  <span id="b12-50">�h3:f1</span>'.
          '   26. <span id="b12-51">�d1:f1</span>  <span id="b12-52">�e8-e2</span>'.
          '   27. <span id="b12-53">�a2-a1</span>  <span id="b12-54">�g6-h6</span>'.
          '   28. <span id="b12-55">d2-d4</span>   <span id="b12-56">�f2-e3</span>'.
          '<p>����� �������.'.
          
          '</div>'.
          
         '<div class="navigation">'.
         '  <a href="'.$link_begin.'">��������� ���� ChessAndMail</a>'.
         '</div>'.
         CPage_::get_metrika_yandex();
          
  
  CPage_::$title_ ='ChessAndMail: ����� ������� ������������� ���������� ���������';
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
