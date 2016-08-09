<?php

    require_once('const_.php');

    class CLinks extends CPage_{
              
        public function __construct() {
            CLinks::$header_ ='<div id="text_header_">������</div>';
        }
       
        protected function outPage($index, $body){
            switch ($index){
                case 2: CLinks::$title_ ='ChessAndMail. ��������� �����.';              break;
                case 3: CLinks::$title_ ='ChessAndMail. ��������� ���������.';          break;
                case 4: CLinks::$title_ ='ChessAndMail. ��������� ����������.';         break;
                case 5: CLinks::$title_ ='ChessAndMail. ������������ ��������� �����.'; break;
                case 6: CLinks::$title_ ='ChessAndMail. ��������� ������� ����.';       break;
                case 7: CLinks::$title_ ='ChessAndMail. ������ ��������� �����.';       break;
                
                default:
                    CLinks::$title_ ='ChessAndMail. ��������� �����.';
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
  <h2 id="title_doc">��������� �����</h2>
  <p>
    <a href="http://club-gambit.kiev.ua" target="_blank">��������� ���� �������</a><br>
    ���������� ��������� ����, � ������� ����� ���������� �������� ������ ������ �������� � ������
    ����������. �� ����� ����� ���������� ���������� ������, ���������� � ���������� �������� �����.
  <p>
    <a href="http://maestrochess.ru/" target="_blank">��������� ����� "�������"</a><br>
    ����� ��������� � �. ������ ������������� �������. �������� �������� ������������� �����������
    ���������, � ������� ��������� ������� ������� ���������� �����. �� ����� ����� ���������
    ��������� �������, ���������� � ������, ���������� ����������, ��������.
  <p>
    <a href="http://www.juniorchess.ru" target="_blank">��������� ������-��������� ��������� ����</a><br>
    ��������� ������� ������������ ����������� "������-��������� ��������� ����".
    �� ����� ����� ���������� ��������� ������������, ������ ������, �������� �������, ���� ������� ������������,
    ����������. ����� ��������������� � ������� �����, �������� �����.
  <p>
    <a href="http://gshk-volgograd.ucoz.ru" target="_blank">��������� ��������� ���� ����������</a><br>
    ���� ���������� �� ������: �. ���������, ��. ���������, 28. �� ����� ����� ����� ����� ��������
    ����������, �������� �����.
</span>
END;
            $this->outPage(1, $body);
        }//outClubs

        public function outSchools(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">��������� �����</h2>
  <p>
    <a href="http://chess86.ru" target="_blank">��������� ����� "���������"</a><br>
    ������� ��������� �����. ������� ��������� ����� �� 5 ���. ��������� �� ������:
    �. ������, ������������� ��������, �. 109, �.6
</span>
END;
            $this->outPage(2, $body);
        }//outSchools

        public function outFederation(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">��������� ���������</h2>
  <p>
    <a href="http://www.obninskchess.ru/chess" target="_blank">��������� ������ �. ��������</a><br>
    ����������� ���� ��������� ��������� ������������ ������������ ������. ���� ������� ���������� �����������, �����
    ��������� � ��������� ����������, ��������, ���������� ����������, ���������� � ������, ������
    ��������� ������� �� ���� ������.
  <p>
    <a href="http://www.chess21.ru" target="_blank">������� � �������</a><br>
    �� ����� ���� �������� ��������� ������� ���������� �������, ���������� � ��������,
    ������ � ����� ��������� ����������� ������� � ������� �����������, ������ ������
    ��������-�������������� �� ��������, ���������� � ���������� � ����������, ��������� ����
    � ������ ������.
  <p>
    <a href="http://chess.poltava.ua" target="_blank">������� � �������</a><br>
    ���� ��������� ������ ������ �������. ����� ����� ��������� �������, ��������� ������,
    �������� �����, �������� �����.
  <p>
    <a href="http://rostovchess.ru" target="_blank">������� � ���������� �������</a><br>
    �� ����� ����� ���������� ���������� � ��������� ��������� ���������� �������, ��������,
    ���������� ��������� ������������, ����������, �������� ��������� �������� � ����������.
</span>
END;
            $this->outPage(3,$body);
        }//outFederation

        public function outAssociation(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">��������� ����������</h2>
  <p>
    <a href="http://chess.org.by" target="_blank">��������� ���������� ��������� � ��������� "����"</a><br>
    �����������  ������������ ����������� "����" ���������� ��������� � �������������� ������ � ���������.
  <p>
    <a href="http://www.mykrasnodarchess.ru" target="_blank">������������� ��������� ���������� ���������� ������� ������</a><br>
    ���� ����� ��������� ���������� ���������� ����������� �������������� ����, �������� � ������� �������.
    �� ����� ����� ����� ����� ������ ����������, �������� �����.
</span>
END;
            $this->outPage(4,$body);
        }//outAssociation

        public function outPersonal(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">������������ ����� �����������</h2>
  <p>
    <a href="http://gonchar19511.narod.ru" target="_blank">���� ��������� ����� �������������</a><br>
    �������� ����� ������������ - ������������� ������ ���� (������������� ��������� ���� � �������
    �� ���������), �������� ����������� ����, ����� ���� �������� ������� ����������. ����� 20 ���
    ���������� ��������� � ������ 5 - 16 ���. ��� ������� ���������� ������� � ���������
    ������������� ������ ���������� ������. ������� ��������� � �������������� ���� �������� ���,
    ��� �������� ��������� ����, ���������� �������������� ��������� �������, ������� � ���������
    ������������� - �������� ������ �������� ����������� ������� ���������� ���������� ��������.
    <div style="float: right">
      <a href="http://kashlinskaya.ru" target="_blank">
        <img style="margin-left: 10px" src="http://kashlinskaya.ru/alina.jpg" border="0" title="���� ������������� ����� ����������" alt="���� ������������� ����� ����������">
      </a>
    </div>
    <div style="text-align:justify">
      <a href="http://kashlinskaya.ru" target="_blank">���� ������������� ����� ����������</a><br>
      ����� ����� � ������ ������������� ������������ �� ��������.<br>
      ��������� ����������� ������������� ��������� ������� � 2358 (�� 01.09.2010).
      ���������� ����� �������� (������, ���������� �������). �������� 28 �������
      1993 ���� � ������ ������. ������ ���������� ��������� � �������� 6 ��� � 10
      �������. �� ��� ������ ���� �� ������������� �� 1 �������. ������ (� 8 � 9 ���)
      ����������� ���������� �������� ���������� ������ � ����� ���������� ���������.
      � 2003 ���� ����� ���������� ������ �� 10 ���, � ����� ������ ����-����������
      ������ �� 10 ��� (�� ������������ � �� ������� ��������). � 11 ��� �����
      ���������� � ������� ������. � 12 ��� �������� ������ ������������� ������ ������� ���Ż.
      � 13 ��� ����� ������������� �������� �� ��������. � 15 ��� ���������� ��������� ������ �������������� ������������.<br>
      �� ����� ����� ��������� ��������� �����, ���������� ���������� � ��������, ����������.
      �������� ������, ��������� � �������������.
    </div><br/>
    <div style="text-align:justify">
      <a href="http://chessproblem.my-free-games.com" target="_blank">Bruno\'s Chess Problem of The Day</a><br>
      Like chess? New chess problems daily, theoretical opening discoveries, chess news, articles,
      free chess strategies or tactics, banks of games!<br>
      ���� ����� ��������� (�������), ��� ���������� ������� ���� ������������� - ����������� ���������.
    </div>
</span>
END;
            $this->outPage(5, $body);
        }//outPersonal

        public function outGameZone(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">������� ����</h2>
  <p>
    <a href="http://ncsgame.ru" target="_blank">Net Chess Server</a><br>
    ��������� ������, ��� ���� ���������� ���������� ���������� NetChess Client, �������
    ����� ������� � ����� �� �������. ����� ����������� ��������� ��������� ������.
</span>
END;
            $this->outPage(6, $body);
        }//Body_game_zone

        public function outOther(){
            $body =
<<<END
<span id="text_doc">
  <h2 id="title_doc">��������� �����</h2>
  <p>
    <a href="http://vodoley.ufanet.ru/" target="_blank">������ ������� </a><br>
    �� ����� ����� ����� ��������� ���������� ����������� ����, ������ ��������� �������, ����������
    �������������� ������ �� �� ����. � ����� ������������
    ������ �� ����, ������� � ������� ���� �� ��������� � ������, ���������� ����������
    ���������� ������ �� ���������, ������� - ���� ���������� �����������-���������, ����������
    ������� �����������, ���������� ����� �� ��������� ����.
  <p>
    <a href="http://www.megachess.net" target="_blank">MegaChess</a><br>
    ��������� �������, ����� ������� ��������, ������, ��������� �����.
  <p>
    <a href="http://www.chesslibrary.ru" target="_blank">chesslibrary.ru - ��������� ����������</a><br>
    ����� ����� ������� ���������� ������������� ��������� ����������
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
