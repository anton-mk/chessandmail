<?php
    require_once('const_.php');
    require_once('Users.php');
    require_once('Games.php');
    require_once('help_.php');
    require_once('configuration_.php');
    require_once('info_.php');
    require_once('tournaments_.php');
    require_once('info_tournament_.php');
    require_once('Variants.php');
    require_once('mens_.php');
    require_once('AboutGamer.php');
    require_once('info_games.php');
    require_once('Calls.php');
    require_once('info_swiss_tournament_.php');
    require_once('chess_commands.php');
    require_once('pages_chess_command.php');

//инициализация сессии
    session_name(NAME_SESSION_);
    session_start();

    try{
      if (!isset($_SESSION[SESSION_LOGIN_]) || !isset($_SESSION[SESSION_ID_])) CPage_::FirstPage();
        else{
          if (isset($_GET['emblem_command']) && (ctype_digit($_GET['emblem_command']))){
            CChessCommands_::get_emblem_command($_GET['emblem_command']);
          }elseif (isset($_GET['chess_command'])){
            if (($_GET['chess_command'] =='make') || 
                (isset($_GET['id']) && ctype_digit($_GET['id']) && CPagesChessCommand_::check_id($_GET['id'])))  
              CPagesChessCommand_::MakePage();
             else
              CChessCommands_::MakePage();   
          }elseif (isset($_GET['link_'])){
            if ($_GET['link_']=='Events'){ #информация - события
              CInfoPage_::MakePage(1);
            }elseif ($_GET['link_']=='Contacts'){
  	      CInfoPage_::MakePage(2);
            }elseif (($_GET['link_']=='GuestBook') || ($_GET['link_']=='post_GuestBook')){
	      CInfoPage_::MakePage(3);
            }elseif ($_GET['link_']=='aboutYou'){
              CConfiguration_::MakePage(2);
            }elseif ($_GET['link_']=='password'){
              CConfiguration_::MakePage(1);
            }elseif ($_GET['link_']=='e_mail'){
              CConfiguration_::MakePage(3);
            }elseif ($_GET['link_']=='dhtml'){
              CConfiguration_::MakePage(4);
            }elseif ($_GET['link_']=='scale_board'){
              CConfiguration_::MakePage(5);
            }elseif ($_GET['link_']=='control_photo'){
              CConfiguration_::MakePage(6);
            }elseif ($_GET['link_']=='my_photo'){
              CConfiguration_::getPhoto($_SESSION[SESSION_ID_]);
            }elseif ($_GET['link_']=='question_del_photo'){
              CConfiguration_::getQuestionDelPhoto();
            }elseif (($_GET['link_']=='photo') && isset($_GET['id_']) && ctype_digit($_GET['id_'])){
              CConfiguration_::getPhoto($_GET['id_']);
            }elseif (($_GET['link_']=='control_otpusk') || ($_GET['link_']=='stop_otpusk') ||
                     ($_GET['link_']=='start_otpusk')){
              CConfiguration_::MakePage(7);
            }elseif ($_GET['link_']=='view_board'){
              CConfiguration_::MakePage(8);
            }elseif ($_GET['link_']=='you_active_tournaments'){
              CTournaments_::MakePage(1);
            }elseif ($_GET['link_']=='access_tournaments'){
              CTournaments_::MakePage(2);
            }elseif ($_GET['link_']=='active_tournaments'){
              CTournaments_::MakePage(3);
            }elseif ($_GET['link_']=='end_tournaments'){
              CTournaments_::MakePage(4);
            }elseif ($_GET['link_']=='Tournament'){
              CInfo_tournament_::MakePage();
            }elseif ($_GET['link_']=='swiss_Tournament'){
              CInfo_swiss_tournament_::MakePage();
            }elseif ($_GET['link_']=='game'){
              CGames_::MakePage();
            }elseif ($_GET['link_']=='make_variant'){
              CVariants_::MakePage();
            }elseif ($_GET['link_']=='variant'){
              CVariants_::MakePage();
            }elseif ($_GET['link_']=='save_move_variant'){
              CVariants_::MakePage();
            }elseif ($_GET['link_']=='dell_variant'){
              CVariants_::MakePage();
            }elseif ($_GET['link_']=='in_variant'){
              CVariants_::MakePage();
            }elseif ($_GET['link_']=='question_accept_variant'){
              CVariants_::MakePage();
            }elseif ($_GET['link_']=='find_mens'){
              CMens_::MakePage(2);
            }elseif ($_GET['link_']=='mens_on_site'){
              CMens_::MakePage(1);
            }elseif ($_GET['link_']=='about_gamer'){
              CAboutGamer_::MakePage();
            }elseif ($_GET['link_']=='you_active_games'){
              CInfo_Games_::MakePage(1);
            }elseif ($_GET['link_']=='you_end_games'){
              CInfo_Games_::MakePage(2);
            }elseif ($_GET['link_']=='calls_off_line'){
              CCalls_::MakePage(1);
            }elseif ($_GET['link_']=='calls_on_line'){
              CCalls_::MakePage(2);
            }elseif ($_GET['link_']=='chess_commands'){
              CChessCommands_::MakePage();
            }
          }
        }
    }catch(Exception $e){
      CPage_::PageErr();
    }

	function Info_Classes(){
		$connect_ =false;
		try{
			$c =new Connection_;
			if ($c->connect(true)) $connect_ =true; else throw new Exception('При соединении с базой произошла ошибка. Попробуйте зайти позже.');
			CMain_::FirstPartMainPage(CMain_::TitlePage('Классы и турниры'),true);
			help_::infoClasses();
			CMain_::SecondPartMainPage();
			$c->disconnect();
		}catch (Exception $e){
			if ($connect_) $c->disconnect();
			CMain_::PageError('Классы и турниры',$e->getMessage());
		}
	} #Info_Classes

	function Info_Rating(){
		$connect_ =false;
		try{
			$c =new Connection_;
			if ($c->connect(true)) $connect_ =true; else throw new Exception('При соединении с базой произошла ошибка. Попробуйте зайти позже.');
			CMain_::FirstPartMainPage(CMain_::TitlePage('Рейтинги'),true);
			help_::infoRating();
			CMain_::SecondPartMainPage();
			$c->disconnect();
		}catch (Exception $e){
			if ($connect_) $c->disconnect();
			CMain_::PageError('Рейтинги',$e->getMessage());
		} #Info_Rating
	}

#-------------------------------------------------------------------------------------------
# Запрос Ajax
#-------------------------------------------------------------------------------------------
	function get_ostatok_otpusk(){
        header('Content-Type: text/html; charset=windows-1251');
        header('Cache-Control: no-cache');
        $connect_=false;
    	$transact_ =false;
        try{
			ob_start(); #начинаю буферизацию

       		if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
			if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

            $id_gamer_  =CUsers_::Read_id_($_SESSION[SESSION_LOGIN_]);
            CUsers_::Check_otpusk($id_gamer_);
            CUsers_::html_control_otpusk($id_gamer_,true);

			if (const_::Commit_()) $transact_ =false; else throw new Exception();
			if ($connect_) const_::Disconnect_();
			ob_end_flush(); #завершаю буферизацию и передаю данные
        }catch (Exception $e){
			ob_end_clean(); #очищаю буфер и завершаю буферизацию
			if ($transact_) const_::Rollback_();
			if ($connect_) const_::Disconnect_();
            echo('error');
		}
    } #get_ostatoc_otpusk

?>
