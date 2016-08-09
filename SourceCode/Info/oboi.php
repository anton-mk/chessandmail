<?php
   require_once('const_.php');

   class COboi extends CPage_{
        public function out(){
            $body =
<<<END
<span>
  <div style="float: left">
    <div style="float:left">
      <img src="Info/Image/oboi/Chess_1_1280x1024.jpg" width="300" height="240" style="border:none; margin-right:10px" alt="Шахматы. Шахматная доска. Шахматные фигуры.">
    </div>
    Разрешение<br/>
    <a href="Info/Image/oboi/Chess_1_1600x1200.jpg">1600x1200</a><br/>
    <a href="Info/Image/oboi/Chess_1_1280x1024.jpg">1280x1024</a><br/>
    <a href="Info/Image/oboi/Chess_1_1280x800.jpg">1280x800</a><br/>
    <a href="Info/Image/oboi/Chess_1_1024x768.jpg">1024x768</a><br/>
  </div>
</span>
END;
            COboi::$header_ ='<div id="text_header_">Обои для рабочего стола</div>';
            COboi::MakeMenu_interesting(10);
            COboi::$title_ ='ChessAndMail. Обои для рабочего стола на шахматную тему.';
            COboi::$body_ =$body;            
            COboi::MakePage();
        }//out

        public static function manager(){
            $page = new COboi();
            $page->out();
        }//manager
   }//COboi