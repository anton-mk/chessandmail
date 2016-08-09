<?php
   require_once('const_.php');

   class CWork_{
        public static function Body_($type_){
            switch ($type_){
                case 0 : $result_ ='<SPAN id="text_doc">'.
                                   '  <H2 id="title_doc">Дополнительные наборы изображений шахматной доски и фигур</H2>'.
                                   '  <P>'.
                                   '   Сайту требуется дополнительные наборы изображений шахматных фигур и доски.'.
                                   '   Если Вы талантливый, креативный художник пришлите мне на e-mail два изображения в формате'.
                                   '   jpg размером 68х68 точек "белой" пешки на "белой" клетке и любой "черной" фигуры на "черной"'.
                                   '   клетке. <a href="info_title_page.php?link_=work_&add=page1">Читать далее...</a>'.
                                   '</SPAN>';
                         break;
                case 1 : $result_ ='<SPAN id="text_doc">'.
                                   '  <H2 id="title_doc">Дополнительные наборы изображений шахматной доски и фигур</H2>'.
                                   '  <P>'.
                                   '   Сайту требуется дополнительные набор изображений шахматных фигур и доски. Если'.
                                   '   Вы талантливый, креативный художник пришлите мне на e-mail два изображения в формате'.
                                   '   jpg размером 68х68 точек "белой" пешки на "белой" клетке и любой "черной" фигуры'.
                                   '   на "черной" клетке. В письме укажите сумму которую Вы хотите получить за 6 изображений'.
                                   '   "белых" фигур с прозрачным фоном, 6 изображений "черных" фигур с прозрачным фоном,'.
                                   '   два изображения пустых клеток "черной" и "белой", 16 изображений окантовки доски.'.
                                   '   <P>'.
                                   '   К сожалению, оценивать подходят ваши рисунки для сайта или нет буду субъективно,'.
                                   '   но постараюсь придерживаться следующих критериев:<br>'.
                                   '   1. Креативность изображений - насколько они оригинальны и отличаются от аналогичных'.
                                   '   изображений на других сайтах, в идеале хочется чтобы фигуры были как визитная'.
                                   '   карточка сайта - уникальны, но посетители могли легко понять какая фигура'.
                                   '   изображена.<br>'.
                                   '   2. Изображения должны выглядеть одинаково на разных мониторах с различными'.
                                   '   цветовыми настройками, не сливаться с фоном клетки и не "резать" глаза.'.
                                   '   Это очень трудно-выполнимая задача, на мой взгляд...'.
                                   '   <P>'.
                                   '   В качестве примера удовлетворяющего пунктам 1 и 2 хочу привести набор фигур,'.
                                   '   принадлежащий сайту "Шахматная планета":'.
                                   '  <DIV style="float: left">'.
                                   '      <img src="Info/Image/chess_planet_bpb.jpg" width="68" height="68" style="border: none" alt="изображение шахматной пешки"   title="изображение шахматной пешки"/>'.
                                   '      <img src="Info/Image/chess_planet_bnb.jpg" width="68" height="68" style="border: none" alt="изображение шахматного коня"   title="изображение шахматного коня"/>'.
                                   '      <img src="Info/Image/chess_planet_bbb.jpg" width="68" height="68" style="border: none" alt="изображение шахматного слона"  title="изображение шахматного слона"/>'.
                                   '      <img src="Info/Image/chess_planet_brb.jpg" width="68" height="68" style="border: none" alt="изображение шахматной ладьи"   title="изображение шахматной ладьи"/>'.
                                   '      <img src="Info/Image/chess_planet_bqb.jpg" width="68" height="68" style="border: none" alt="изображение шахматного ферзя"  title="изображение шахматного ферзя"/>'.
                                   '      <img src="Info/Image/chess_planet_bkb.jpg" width="68" height="68" style="border: none" alt="изображение шахматного короля" title="изображение шахматного короля"/>'.
                                   '  </DIV>'.
                                   '  <DIV style="clear:both">&nbsp;</DIV>'.
                                   '  <DIV style="float: left">'.
                                   '      <img src="Info/Image/chess_planet_wpw.jpg" width="68" height="68" style="border: none" alt="изображение шахматной пешки"   title="изображение шахматной пешки"/>'.
                                   '      <img src="Info/Image/chess_planet_wnw.jpg" width="68" height="68" style="border: none" alt="изображение шахматного коня"   title="изображение шахматного коня"/>'.
                                   '      <img src="Info/Image/chess_planet_wbw.jpg" width="68" height="68" style="border: none" alt="изображение шахматного слона"  title="изображение шахматного слона"/>'.
                                   '      <img src="Info/Image/chess_planet_wrw.jpg" width="68" height="68" style="border: none" alt="изображение шахматной ладьи"   title="изображение шахматной ладьи"/>'.
                                   '      <img src="Info/Image/chess_planet_wqw.jpg" width="68" height="68" style="border: none" alt="изображение шахматного ферзя"  title="изображение шахматного ферзя"/>'.
                                   '      <img src="Info/Image/chess_planet_wkw.jpg" width="68" height="68" style="border: none" alt="изображение шахматного короля" title="изображение шахматного короля"/>'.
                                   '  </DIV>'.
                                   '  <DIV style="clear:both">&nbsp;</DIV>'.
                                   '  <P>'.
                                   '  В качестве примера удовлетворяющему пункту 1 но не удовлетворяющему пункту 2 хочу'.
                                   '  привести пример шахматных фигур которые используются на сайте сейчас в качестсве'.
                                   '  основного набора:'.
                                   '  <DIV style="float: left">'.
                                   '      <img src="Image/bpb.jpg" width="68" height="68" style="border: none" alt="изображение шахматной пешки"   title="изображение шахматной пешки"/>'.
                                   '      <img src="Image/bnb.jpg" width="68" height="68" style="border: none" alt="изображение шахматного коня"   title="изображение шахматного коня"/>'.
                                   '      <img src="Image/bbb.jpg" width="68" height="68" style="border: none" alt="изображение шахматного слона"  title="изображение шахматного слона"/>'.
                                   '      <img src="Image/brb.jpg" width="68" height="68" style="border: none" alt="изображение шахматной ладьи"   title="изображение шахматной ладьи"/>'.
                                   '      <img src="Image/bqb.jpg" width="68" height="68" style="border: none" alt="изображение шахматного ферзя"  title="изображение шахматного ферзя"/>'.
                                   '      <img src="Image/bkb.jpg" width="68" height="68" style="border: none" alt="изображение шахматного короля" title="изображение шахматного короля"/>'.
                                   '  </DIV>'.
                                   '  <DIV style="clear:both">&nbsp;</DIV>'.
                                   '  <DIV style="float: left">'.
                                   '      <img src="Image/wpw.jpg" width="68" height="68" style="border: none" alt="изображение шахматной пешки"   title="изображение шахматной пешки"/>'.
                                   '      <img src="Image/wnw.jpg" width="68" height="68" style="border: none" alt="изображение шахматного коня"   title="изображение шахматного коня"/>'.
                                   '      <img src="Image/wbw.jpg" width="68" height="68" style="border: none" alt="изображение шахматного слона"  title="изображение шахматного слона"/>'.
                                   '      <img src="Image/wrw.jpg" width="68" height="68" style="border: none" alt="изображение шахматной ладьи"   title="изображение шахматной ладьи"/>'.
                                   '      <img src="Image/wqw.jpg" width="68" height="68" style="border: none" alt="изображение шахматного ферзя"  title="изображение шахматного ферзя"/>'.
                                   '      <img src="Image/wkw.jpg" width="68" height="68" style="border: none" alt="изображение шахматного короля" title="изображение шахматного короля"/>'.
                                   '  </DIV>'.
                                   '  <DIV style="clear:both">&nbsp;</DIV>'.
                                   '  <DIV style="text-align:right">'.
                                   '    Все вопросы и примеры рисунков прошу отправлять мне по адресу anton-mk@yandex.ru<br>'.
                                   '    Колосовский Антон Михайлович'.
                                   '  </DIV>'.
                                   '</SPAN>';
                         break;
            }#switch
            return $result_;

        }#Body_

        public static function MakePage(){
            if (isset($_GET['add']) && ($_GET['add'] =='page1')){
              $type_=1;
              CPage_::$title_ ='ChessAndMail. Требуется дополнительный набор изображений шахматных фигур и доски.';
             }else{
              $type_=0;
              CPage_::$title_ ='ChessAndMail. Вакансии, предложения работы.';
            }
            $body_ =CWork_::Body_($type_).
                    CPage_::get_metrika_yandex();
            CPage_::$header_ ='<DIV id="text_header_">'.
                              '  Предложения работы'.
                              '</DIV>';
            CPage_::MakeMenu_interesting(11);
            CPage_::$body_ =$body_;
            CPage_::MakePage();
        }#MakePage
   }#CWork_
?>