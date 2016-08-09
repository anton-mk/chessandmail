<?php

 require_once('../const_.php');
  
  session_name(NAME_SESSION_);
  session_start();

  CPage_::$root_catalog ='../';
  $link_begin =(isset($_SESSION[SESSION_ID_]) ? CPage_::$root_catalog.'MainPage.php?link_=Events' : 'http://chessandmail.ru'); 
  
  CPage_::$header_ ='<h1>Партии Пола Морфи</h1>';
  $body_='<div class="links-to-games">'.
         '  <table>'.
         '    <tr><th>Год</th><th>Дебют</th><th>Соперники</th><th>Ссылка</th></tr>'.
         '    <tr><td>1849</td>'.
         '        <td>Гамбит Эванса</td>'.
         '        <td>Морфи - А.Морфи (отец)</td>'.
         '        <td><a href="Morphy-death-father.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1849</td>'.
         '        <td>Итальянская партия</td>'.
         '        <td>Морфи - Э. Морфи</td>'.
         '        <td><a href="young-Morphy.php">сслыка</a></td>'.
         '    </tr>'.
         '    <tr><td>1850</td>'.
         '        <td>Русская партия</td>'.
         '        <td>Морфи - Левенталь</td>'.
         '        <td><a href="match-Morphy-Levental.php">сслыка</a></td>'.
         '    </tr>'.
         '    <tr><td>1850</td>'.
         '        <td>Сицилианская партия</td>'.
         '        <td>Морфи - Левенталь</td>'.
         '        <td><a href="match-Morphy-Levental.php">сслыка</a></td>'.
         '    </tr>'.
         '    <tr><td>1855</td>'.
         '        <td>Гамбит слона</td>'.
         '        <td>Морфи - Мориан<br/>(Белые без ладьи на a1)</td>'.
         '        <td><a href="Morphy-college-St-Joseph.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1856</td>'.
         '        <td>Гамбит Коня</td>'.
         '        <td>Морфи - Найт<br/>(Белые без ладьи на a1 и коня b1)</td>'.
         '        <td><a href="Morphy-University.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1857</td>'.
         '        <td>Итальянская партия</td>'.
         '        <td>Томпсон - Морфи</td>'.
         '        <td><a href="Morphy-open-American-chess-Congress.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1857</td>'.
         '        <td>Шотландский гамбит</td>'.
         '        <td>Лихтенгейн - Морфи</td>'.
         '        <td><a href="Morphy-final-American-chess-Congress.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1857</td>'.
         '        <td>Русская партия</td>'.
         '        <td>Морфи - Лихтенгейн</td>'.
         '        <td><a href="Morphy-final-American-chess-Congress.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1857</td>'.
         '        <td>Дебют четырех коней</td>'.
         '        <td>Паульсен - Морфи</td>'.
         '        <td><a href="Morphy-final-American-chess-Congress.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1857</td>'.
         '        <td>Гамбит Эванса</td>'.
         '        <td>Морфи - Стэнли</td>'.
         '        <td><a href="Morphy-after-American-chess-Congress.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Защита Филидора</td>'.
         '        <td>Бернс - Морфи</td>'.
         '        <td><a href="Morphy-England-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Защита Филидора</td>'.
         '        <td>Бернс - Морфи</td>'.
         '        <td><a href="Morphy-England-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Отказанный королевский гамбит</td>'.
         '        <td>Морфи - Боден</td>'.
         '        <td><a href="Morphy-England-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Защита Филидора</td>'.
         '        <td>Берд - Морфи</td>'.
         '        <td><a href="Morphy-England-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Защита Филидора</td>'.
         '        <td>Стаунтон+Оуэн - Морфи+Бернс<br/>(партнеры консультируются друг с другом)</td>'.
         '        <td><a href="Morphy-Levental-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Защита Филидора</td>'.
         '        <td>Левенталь - Морфи<br/>(первая партия матча)</td>'.
         '        <td><a href="Morphy-Levental-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Отказанный королевский гамбит</td>'.
         '        <td>Морфи - Левенталь<br/>(вторая партия матча)</td>'.
         '        <td><a href="Morphy-Levental-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Русская партия</td>'.
         '        <td>Левенталь - Морфи<br/>(третья партия матча)</td>'.
         '        <td><a href="Morphy-Levental-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Французская партия</td>'.
         '        <td>Оуэн - Морфи<br/>(черные без пешки на f7, пятая партия матча)</td>'.
         '        <td><a href="Morphy-blindfold-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Сицилианская партия</td>'.
         '        <td>Морфи - Эверен<br/>(сеанс одновременной игры вслепую на 8 досках)</td>'.
         '        <td><a href="Morphy-blindfold-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Защита Филидора</td>'.
         '        <td>Морфи - Гаррвиц<br/>(четвертая партия матча)</td>'.
         '        <td><a href="Morphy-Paris-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Голландская партия</td>'.
         '        <td>Гаррвиц - Морфи<br/>(седьмая партия матча)</td>'.
         '        <td><a href="Morphy-Paris-1858.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Защита Филидора</td>'.
         '        <td>Морфи - Герцог Карл Брауншвейгский и граф Изуар<br/>(оперный театр)</td>'.
         '        <td><a href="Morphy-Anderson.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1858</td>'.
         '        <td>Испанская партия</td>'.
         '        <td>Морфи - Андерсен<br/>(третья партия матча)</td>'.
         '        <td><a href="Morphy-Anderson.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1859</td>'.
         '        <td>Неправильное начало</td>'.
         '        <td>Морфи - Монгредьен<br/>(шестая партия матча)</td>'.
         '        <td><a href="Morphy-Multy-board-chess-play.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1859</td>'.
         '        <td>Дебют двух коней</td>'.
         '        <td>Де Ривьер - Морфи<br/>(Сеанс в клубе Сент-Джемс)</td>'.
         '        <td><a href="Morphy-Multy-board-chess-play.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1859</td>'.
         '        <td>Испанская партия</td>'.
         '        <td>Левенталь - Морфи<br/>(Сеанс в клубе Сент-Джемс)</td>'.
         '        <td><a href="Morphy-Multy-board-chess-play.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1859</td>'.
         '        <td>Защита Филидора</td>'.
         '        <td>Морфи - Джельен<br/>(белые без коня на b1)</td>'.
         '        <td><a href="Morphy-New-York-1859.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1859</td>'.
         '        <td>Шотландский гамбит</td>'.
         '        <td>Морфи - Перрэн<br/>(белые без коня на b1)</td>'.
         '        <td><a href="Morphy-New-York-1859.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1863</td>'.
         '        <td>Итальянская партия</td>'.
         '        <td>Морфи - де Ривьер</td>'.
         '        <td><a href="Morphy-Civil-War-1861.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1863</td>'.
         '        <td>Дебют двух коней</td>'.
         '        <td>де Ривьер - Морфи</td>'.
         '        <td><a href="Morphy-Civil-War-1861.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1869</td>'.
         '        <td>Гамбит коня</td>'.
         '        <td>Морфи - Мориан<br/>(белые без коня на b1)</td>'.
         '        <td><a href="Morphy-the-last-years-of-life.php">сслыка</a></td>'.
         '    </tr>'. 
         '    <tr><td>1869</td>'.
         '        <td>Дебют Берда</td>'.
         '        <td>Морфи - Мориан<br/>(белые без коня на b1)</td>'.
         '        <td><a href="Morphy-the-last-years-of-life.php">сслыка</a></td>'.
         '    </tr>'. 
         '  </table>'. 
         '</div>'.
          
         '<div class="navigation">'.
         '  <a href="'.$link_begin.'">Шахматный клуб ChessAndMail</a>'.
         '</div>'.
         CPage_::get_metrika_yandex();
          
  
  CPage_::$title_ ='ChessAndMail: Партии Пола Морфи';
  CPage_::$add_file_style ='champions.css';
  CPage_::$body_ =$body_;
  CPage_::MakePage(); 
?>
