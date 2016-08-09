<?php
  require_once('const_.php');

#������������� ������
  session_name(NAME_SESSION_);
  session_start();

  $header_ ='<DIV id="text_header_">'.
            '  ��������'.
            '</DIV>';

  if (isset($_GET['link_']) && ($_GET['link_']=='begin_position_classic_chess')){
     require_once('doc/begin_position_classic_chess.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������, ������ ������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='move_chess')){
     require_once('doc/move_chess.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. ������� ���������� ����� � ��������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='itog_chess')){
     require_once('doc/itog_chess.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. ���� ���� � �������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='registration_')){
     require_once('doc/registration_.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� ��������� ����������� �� �����.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='how_begin_game')){
     require_once('doc/how_begin_game.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� - ��� ������ ������ �� �����.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='reglaments_')){
     require_once('doc/reglaments_.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� ������������ �� ����� �����������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='how_make_move')){
     require_once('doc/how_make_move.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� - ��� ��������� ���.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='variant_')){
     require_once('doc/variant_.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� ��������� �����������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='end_game')){
     require_once('doc/end_game.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� - ��� ��������� ������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='message_in_time_game')){
     require_once('doc/message_in_time_game.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� - ������� �� ����� ������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='rating_and_class')){
     require_once('doc/rating_and_class.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� ������� � ���������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='doc_info')){
     require_once('doc/doc_info.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� �������� "�������".';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='doc_messages')){
     require_once('doc/doc_messages.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� - ��� �������� � ������� �������� �� ������� ������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='doc_guestbook')){
     require_once('doc/doc_guestbook.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� - ��� �������� ����� ��� �����������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='active_games')){
     require_once('doc/active_games.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� ������ ����� ������������� ������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='end_games')){
     require_once('doc/end_games.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� ��������� ������ ����������� ��������� ������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='tournaments')){
     require_once('doc/tournaments.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� ��������.';
     Make_Menu_();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='calls')){
     require_once('doc/calls.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. �������� - ��� ������� ��� ������� ����� �� ���� � �������.';
     Make_Menu_();
  }else{
     require_once('doc/content.php');
     $body_ =Body_();
     CPage_::$title_ ='ChessAndMail. ���������� ��������.';
     Make_Menu_();
  }

  CPage_::$header_ =$header_;
  CPage_::$body_ =Body_();
  CPage_::MakePage();
?>
