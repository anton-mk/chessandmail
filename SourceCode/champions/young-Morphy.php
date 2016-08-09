<?php

  require_once('../const_.php');
  require_once('../rule_game_.php');
  
  session_name(NAME_SESSION_);
  session_start();
  
  CPage_::$root_catalog ='../';
  
  $link_begin =(isset($_SESSION[SESSION_ID_]) ? CPage_::$root_catalog.'MainPage.php?link_=Events' : 'http://chessandmail.ru');

  CPage_::$header_ ='<h1>Детство Пола Морфи</h1>';
  $body_ ='<div class="narration">'.
          '  <image src="Image/Alonzo-Morphy.png" alt="Алонзо Морфи" class="no-border" title="Отец Пола Мофи - Алонзо Морфи.">'.
          '   - Сыграем Пол? -внезапно сказал дядя Эрнест. - Тащи сюда доску!<br/>'.
          '   Вскоре Пол вернулся со старинным шахматным ящиком. На маленьком столике в уютном темноватом'.
          '   углу началась игра. Ле Карпантье, сам хороший шахматист, придвинул себе кресло и уселся сбоку,'.
          '   не говоря ни слова.'.
          '<p>Вскоре Пол запел, тихо, почти беззвучно. Он всегда пел, когда играл в шахматы, ему казалось,'.
          '   что это необходимо.<br/>'.
          '   -Сю-ю-да...И сю-да... - пел он почти неслышно. - И опять... И во-от так... И ещё...<br/>'.
          '   -Замурлыкал, мальчик? - тихо спросил ле Карпантье.<br/>'.
          '   Пол кивнул. Молчаливая гармония игры захватила его и несла куда-то вдаль по певучим волнам звуков.'.
          '   Все фигуры, казалось, участвуют в симфонии, которой дирижирует он,'.
          '   <a href="Paul-Charles-Morphy.php">Пол Морфи</a>, знаменитый дирижер.'.
          '   Все должно звучать на этой доске, все фигуры без исключения....'.
          '<p>Вдруг он умолк и склонился над доской. Он даже вскочил со стула и стал на него коленками. Сейчас...'.
          '   Проверить! Ну конечно, надо бить ладьей коня... Ладьей - коня, потом ферзем - ладью, и вторая'.
          '   ладья дает шах и мат на первом ряду, благо учерного короля нет выхода... '.
          '<p>Раз! Пол побил ладьей коня.<br/>'.
          '   - А как уроки, Пол? - спросила внезапно миссис Тельсид.</br>'.
          '   Пол повернулся к ней. Дедушка ле Карпантье подмигнул Эрнесту и незаметно протянул к доске длинный'.
          '   палец.</br>'.
          '   - Папа же сказал: завтра утром! - обиженно сказал матери Пол и вернулся к доске. Не думая побил'.
          '   Пол ладью ферзем - всплеснул руками: крайняя королевская пешка Эрнеста успела почему-то'.
          '   продвинуться на один шаг - мата не получилось...'.
          '</div>'.
          '<div class="example-game">'.
          '   <h2>'.
          '     <span class="type-game">Итальянская партия</span><br/>'.
          '     Морфи&nbsp;&nbsp;Э.Морфи (первая партия, игранная Мофи не глядя на доску)<br/>'.
          '     <span class="place-game">Новый Орлеан, 22 июня 1849</span>'.
          '   </h2>';
  
  $rg =new CRuleGame();
  $rg->firstPosition();
  const_::set_params_image_board(false);
  $body_.='<div class="chess-games">'.
          '  <div class="game">'.
          '    <div class="board">'.
                 CPage_::OutBoard($rg,0.5,'board2','b2-').
          '      <div class="operations">'.
          '        <img src="Image/left_begin.png" alt="Перейти в начало партии" title="Перейти в начало партии" id="b2-begin">'.
          '        <img src="Image/left.png" alt="Предыдущий ход" title="Предыдущий ход" id="b2-prev">'.
          '        <img src="Image/right.png" alt="Следующий ход" title="Следующий ход" id="b2-next">'.
          '        <img src="Image/right_end.png" alt="Перейти в конец партии" title="Перейти в конец партии" id="b2-last">'.          
          '      </div>'.
          '    </div>'.
          '  </div>'.
          '</div>'.
          '   1. <span id="b2-1">e2-e4</span>  <span id="b2-2">e7-e5</span>'.
          '   2. <span id="b2-3">Кg1-f3</span> <span id="b2-4">Кb8-c6</span>'.
          '   3. <span id="b2-5">Сf1-c4</span> <span id="b2-6">Сf8-c5</span>'.
          '   4. <span id="b2-7">c2-c3</span>  <span id="b2-8">d7-d6</span>'.
          '   5. <span id="b2-9">0-0</span>    <span id="b2-10">Кg8-f6</span>'.
          '<p>Осторожнее 5... Cb6 или 5... Фe7. Теория рекомендует 5... Сg4 6. Фb3 С:f3 7. С:f7+ Крf8 '.
          '   8. С:g8 Л:g8 9.gf g5. Но вместо 8. С:g8 белые могут играть просто 8. gf, получая лучшую позицию.'.
          '<p>6. <span id="b2-11">d2-d4</span> <span id="b2-12">e5:d4</span>'.
          '   7. <span id="b2-13">c3:d4</span> <span id="b2-14">Сc5-b6</span>'.
          '   8. <span id="b2-15">h2-h3</span> ...'.
          '<p>Очень хорошо здесь 8. Кc3.'.
          '<p>8. ... <span id="b2-16">h7-h6</span>'.
          '   9. <span id="b2-17">Кb2-c3</span> <span id="b2-18">0-0</span>'.
          '  10. <span id="b2-19">Сc1-e3</span> ...'.
          '<p>Здесь белым следовало отступить слоном на b3.'.
          '<p>10. ... <span id="b2-20">Лf8-e8</span>'.
          '<p>Слабо 10. ... К:e4 11. К:e4 d5 вело к ровной игре.'.
          '<p>11. <span id="b2-21">d4-d5</span> <span id="b2-22">Сb6:e3</span>'.
          '<p>Ошибочный размен, после которого черные стоят хуже. Надо было играть 11. ... Кe7.'.
          '<p>12. <span id="b2-23">d5:c6</span>  <span id="b2-24">Сe3-b6</span>'.
          '   13. <span id="b2-25">e4-e5</span>  <span id="b2-26">d6:e5</span>'.
          '   14. <span id="b2-27">Фd1-b3</span> <span id="b2-28">Лe8-e7</span>'.
          '<p>Сыграно близоруко! Следовало играть 14. ... Ce6. Молодой маэстро немедленно использовал слабый ответ.'.
          '   Интересно, между прочим, что эта партия - первая, игранная Морфи не глядя на доску.'.
          '<p>15. <span id="b2-29">Сc4:f7+</span> <span id="b2-30">Лe7:f7</span>'.
          '   16. <span id="b2-31">Кf3:e5</span>  <span id="b2-32">Фd8-e8</span>'.
          '   17. <span id="b2-33">c6:b7</span>   <span id="b2-34">Сc8:b7</span>'.
          '   18. <span id="b2-35">Лa1-e1</span>  <span id="b2-36">Сb7-a6</span>'.
          '<p>Больше шансов на ничью давало 18. ... Кe4 19. К:f7 Ф:f7 20. Ф:f7+ Кр:f7 21. К:e4 Сa6 и т.д.'.
          '<p>19. <span id="b2-37">Кe5-g6</span> <span id="b2-38">Фe8-d8</span>'.
          '   20. <span id="b2-39">Лe1-e7</span>, и белые выиграли'.
          '<p>По словам одного очевидца, восторг присутствовавших при этой партии зрителей не поддавался никакому'.
          '   описанию.'.
          '</div>'.
          
          '<div class="navigation">'.
          '  <a href="'.$link_begin.'">Шахматный клуб ChessAndMail</a>'.
          '</div>'.
          CPage_::get_metrika_yandex();
  
  CPage_::$title_ ='ChessAndMail: Детство Пола Морфи';
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
