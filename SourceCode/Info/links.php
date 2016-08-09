<?php

    require_once('const_.php');

    class CLinks extends CPage_{
              
        public function __construct() {
            CLinks::$header_ ='<div id="text_header_">Ссылки</div>';
        }
       
        protected function outPage($index, $body){
            switch ($index){
                case 2: CLinks::$title_ ='ChessAndMail. Шахматные школы.';              break;
                case 3: CLinks::$title_ ='ChessAndMail. Шахматные федерации.';          break;
                case 4: CLinks::$title_ ='ChessAndMail. Шахматные ассоциации.';         break;
                case 5: CLinks::$title_ ='ChessAndMail. Персональные шахматные сайты.'; break;
                case 6: CLinks::$title_ ='ChessAndMail. Шахматные игровые зоны.';       break;
                case 7: CLinks::$title_ ='ChessAndMail. Прочие шахматные сайты.';       break;
                
                default:
                    CLinks::$title_ ='ChessAndMail. Шахматные клубы.';
                    break;
            }//switch

            CLinks::MakeMenu_interesting($index);
            CLinks::$body_ =$body;
            CLinks::MakePage();
        }//outPage
       
        public function outClubs(){
            $body = 
<<<END
<span id="text_doc">
  <h2 id="title_doc">Шахматные клубы</h2>
  <p>
    <a href="http://club-gambit.kiev.ua" target="_blank">Шахматный клуб «Гамбит»</a><br>
    Украинский шахматный клуб, в котором могут заниматься любители шахмат любого возраста и уровня
    подготовки. На сайте можно посмотреть интересные партии, фотографии и достижения учеников клуба.
  <p>
    <a href="http://maestrochess.ru/" target="_blank">Шахматный центр "Маэстро"</a><br>
    Центр находится в г. Бердск Новосибирской области. Ежегодно проводит замечательный одноименный
    фестиваль, в котором принимает участие большое количество детей. На сайте можно прочитать
    последние новости, пообщаться в форуме, посмотреть фотографии, интервью.
  <p>
    <a href="http://www.juniorchess.ru" target="_blank">Алтайский детско-юношеский шахматный клуб</a><br>
    Алтайская краевая общественная организация "ДЕТСКО-ЮНОШЕСКИЙ ШАХМАТНЫЙ КЛУБ".
    На сайте можно посмотреть календарь соревнований, адреса клубов, почитать новости, есть разделы фотогаллерея,
    видеоканал. Можно потренироваться в решении задач, работает форум.
  <p>
    <a href="http://gshk-volgograd.ucoz.ru" target="_blank">Городской шахматный клуб Волгограда</a><br>
    Клуб расположен по адресу: г. Волгоград, ул. Советская, 28. На сайте можно найти много полезной
    информации, работает форум.
</span>
END;
            $this->outPage(1, $body);
        }//outClubs

        public function outSchools(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">Шахматные школы</h2>
  <p>
    <a href="http://chess86.ru" target="_blank">Шахматная школа "Кузьминки"</a><br>
    Детская шахматная школа. Обучает бесплатно детей от 5 лет. Находится по адресу:
    г. Москва, Волгоградский проспект, д. 109, к.6
</span>
END;
            $this->outPage(2, $body);
        }//outSchools

        public function outFederation(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">Шахматные федерации</h2>
  <p>
    <a href="http://www.obninskchess.ru/chess" target="_blank">Федерация шахмат г. Обнинска</a><br>
    Официальный сайт шахматной федерации Центрального Федерального Округа. Сайт насыщен интересной информацией, можно
    прочитать о шахматный фестивалях, турнирах, посмотреть фотографии, пообщаться в форуме, узнать
    последние новости из мира шахмат.
  <p>
    <a href="http://www.chess21.ru" target="_blank">Шахматы в Чувашии</a><br>
    На сайте есть основные шахматные новости республики Чувашия, информация о турнирах,
    статьи о самых достойных шахматистах Чувашии в рубрике «Знакомство», полный список
    тренеров-преподавателей по шахматам, работающих в Чебоксарах и республике, шахматный юмор
    и многое другое.
  <p>
    <a href="http://chess.poltava.ua" target="_blank">Шахматы в Полтаве</a><br>
    Сайт федерации шахмат города Полтавы. Можно найти последние новости, шахматные задачи,
    работает форум, гостевая книга.
  <p>
    <a href="http://rostovchess.ru" target="_blank">Шахматы в Ростовской области</a><br>
    На сайте можно посмотреть информацию о шахматной федерации Ростовской области, турнирах,
    посмотреть календарь соревнований, фотографии, выложены некоторые интервью и публикации.
</span>
END;
            $this->outPage(3,$body);
        }//outFederation

        public function outAssociation(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">Шахматные ассоциации</h2>
  <p>
    <a href="http://chess.org.by" target="_blank">Шахматная ассоциация инвалидов и ветеранов "Шанс"</a><br>
    Белорусское  общественное объединение "Шанс" занимается развитием и популяризацией шахмат в Беларусии.
  <p>
    <a href="http://www.mykrasnodarchess.ru" target="_blank">Коаснодарское отделение Российской Ассоциации Заочных Шахмат</a><br>
    Цель сайта создавать онлайновое сообщество шахматистов Краснодарского края, играющих в заочные шахматы.
    На сайте можно найти много разной информации, работает форум.
</span>
END;
            $this->outPage(4,$body);
        }//outAssociation

        public function outPersonal(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">Персональные сайты шахматистов</h2>
  <p>
    <a href="http://gonchar19511.narod.ru" target="_blank">Сайт Гончарова Игоря Владимировича</a><br>
    Гончаров Игорь Владимирович - международный мастер ИКЧФ (международная федерация игры в шахматы
    по переписке), кандидат технических наук, автор двух десятков научных публикаций. Около 20 лет
    занимается шахматами с детьми 5 - 16 лет. Его ученики добиваются успехов в шахматных
    соревнованиях самого различного уровня. Богатый жизненный и педагогический опыт убеждает его,
    что обучение шахматной игре, выполнение многочисленных шахматных заданий, участие в шахматных
    единоборствах - основные звенья наиболее эффективной системы воспитания творческой личности.
    <div style="float: right">
      <a href="http://kashlinskaya.ru" target="_blank">
        <img style="margin-left: 10px" src="http://kashlinskaya.ru/alina.jpg" border="0" title="Сайт гроссмейстера Алины Кашлинской" alt="Сайт гроссмейстера Алины Кашлинской">
      </a>
    </div>
    <div style="text-align:justify">
      <a href="http://kashlinskaya.ru" target="_blank">Сайт гроссмейстера Алины Кашлинской</a><br>
      Самая юнная в Европе международный гроссмейстер по шахматам.<br>
      Наивысший достигнутый международный шахматный рейтинг — 2358 (на 01.09.2010).
      Награждена двумя медалями (Чехова, «Одаренный ребенок»). Родилась 28 октября
      1993 года в городе Москва. Начала заниматься шахматами в возрасте 6 лет и 10
      месяцев. За год прошла путь от безразрядницы до 1 разряда. Дважды (в 8 и 9 лет)
      становилась серебряным призером Чемпионата Москвы в своей возрастной категории.
      В 2003 году стала Чемпионкой России до 10 лет, а затем дважды Вице-Чемпионкой
      Европы до 10 лет (по классическим и по быстрым шахматам). В 11 лет стала
      кандидатом в мастера спорта. В 12 лет получила первое международное звание «Мастер ФИДЕ».
      В 13 лет стала международным мастером по шахматам. В 15 лет официально присвоено звание «Международный гроссмейстер».<br>
      На сайте можно прочитать биографию Алины, посмотреть информацию о тренерах, фотографии.
      Выложены партии, некоторые с комментариями.
    </div><br/>
    <div style="text-align:justify">
      <a href="http://chessproblem.my-free-games.com" target="_blank">Bruno\'s Chess Problem of The Day</a><br>
      Like chess? New chess problems daily, theoretical opening discoveries, chess news, articles,
      free chess strategies or tactics, banks of games!<br>
      Сайт Брюно Беренгера (Франция), при содействии Букаева Юрия Вячеславовича - российского теоретика.
    </div>
</span>
END;
            $this->outPage(5, $body);
        }//outPersonal

        public function outGameZone(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">Игровые зоны</h2>
  <p>
    <a href="http://ncsgame.ru" target="_blank">Net Chess Server</a><br>
    Шахматный сервер, для игры необходимо установить приложение NetChess Client, которое
    можно скачать с этого же сервера. Также публикуются различные шахматные задачи.
</span>
END;
            $this->outPage(6, $body);
        }//Body_game_zone

        public function outOther(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">Шахматные сайты</h2>
  <p>
    <a href="http://vodoley.ufanet.ru/" target="_blank">Взгляд Водолея </a><br>
    На сайте можно найти биографии величайших шахматистов мира, цитаты известных маэстро, выражающие
    нетрадиционный взгляд на на игру. А также практические
    советы по игре, правила и историю игры по переписке в России, результаты командного
    первенства России по переписке, рейтинг - лист российских шахматистов-заочников, результаты
    заочных чемпионатов, приобрести книги на шахматные темы.
  <p>
    <a href="http://www.megachess.net" target="_blank">MegaChess</a><br>
    Шахматные новости, обзор текущих турниров, задачи, шахматная школа.
  <p>
    <a href="http://www.chesslibrary.ru" target="_blank">chesslibrary.ru - Шахматная библиотека</a><br>
    Можно найти большое количество разнообразной шахматной литературы
</span>
END;
            $this->outPage(7, $body);
        }//outOther

        public static function manager(){
            $page = new CLinks();
            if (isset($_GET['add']) && ($_GET['add'] =='schools')){
                $page->outSchools();
            }else if (isset($_GET['add']) && ($_GET['add'] =='federations')){
                $page->outFederation();
            }else if (isset($_GET['add']) && ($_GET['add'] =='associations')){
                $page->outAssociation();
            }else if (isset($_GET['add']) && ($_GET['add'] =='persons')){
                $page->outPersonal();
            }else if (isset($_GET['add']) && ($_GET['add'] =='game_zons')){
                $page->outGameZone();
            }else if (isset($_GET['add']) && ($_GET['add'] =='other')){
                $page->outOther();
            }else{
                if(!isset($_GET['add'])){
                    $page->outClubs();
                }else{
                    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");  
                } 
            }
        }#manager
   }#CLinks
