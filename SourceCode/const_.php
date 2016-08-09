<?php
    require_once('Users.php');
    require_once('rule_game_.php');

    define('HOST_','localhost',true);
#запуск на локальной машине
   define('USER_','root',true);
   define('PASSWORD_','1234567',true);
   define('BASE_','chessandmail',true);

    define('NAME_SESSION_','ChessAndMail',true);
    define('SESSION_LOGIN_','login_',true);
    define('SESSION_ID_','id_',true);
 #адрес для ссылок возврата
    define('SESSION_LINK_ESC_INFO_TOURNAMENT','link_esc_info_tournament',true);
    define('SESSION_LINK_ESC_GAME','link_esc_game',true);
    define('SESSION_LINK_ESC_VARIANT','link_esc_variant',true);
    define('SESSION_LINK_ESC_ABOUT_GAMER','link_esc_about_gamer',true);
    define('SESSION_LINK_ESC_DOC','link_esc_doc',true);
    define('SESSION_LINK_ESC_INFO_SWISS_TOURNAMENT','link_esc_info_swiss_tournament',true);
    define('SESSION_LINK_ESC_TASK','link_esc_task',true);

//переменная сесси, используется для предотвращении повторного выполнения операции при нажатии клавиши обновить
//0 - операция начата, 1 - операция закончена
    define('SESSION_STATE_OPERATION','STATE_OPERATION',true);
    define('SESSION_ANTI_SPAM','anti_spam',true);

    define('KEY_CRIPT_EMAIL','1234567890',true);

    define('TIME_CHECK_ON_SITE',60*3,true);

#каталоги изображений шахматных фигур и доски
    define('cat_img_figures_main','Image/',true);
    define('cat_img_figures_2','Image2/',true);
    define('cat_img_figures_3','Image3/',true);    

#минимальное количество зарегиситрировавшихся в турнире по швейцарской системе, чтобы он состоялся
    define('MIN_GAMERS_SWISS_TOURNAMENT',10,true);
    
#кодовая страница используется в htmlspecialchars
    define('CODE_PAGE','cp1251',true);

    $reglaments_['reglament1_'] = '7 дней на партию';
    $reglaments_['reglament2_'] = '30 дней на партию';
    $reglaments_['reglament3_'] = '3 дня на ход';
    $reglaments_['reglament4_'] = '10+1';
    $reglaments_['reglament5_'] = '15+1';
    $reglaments_['reglament6_'] = '5 + 1';
    $reglaments_['reglament7_'] = '30 минут';
    $reglaments_['reglament8_'] = '15 минут';
    $reglaments_['reglament9_'] = '10 минут';
    $reglaments_['reglament10_'] = '7 минут';
    $reglaments_['reglament11_'] = '1 день + 1 час';

    $reglamentsMail_ =array(1,2,3,4,5); #регламенты для игры по переписке

    $countMoveForChangeClass8_  =10; #Если одним из игроков был сделан countMoveForChangeClass8 ход партия учитывается при смене класса 8
    $countGamesForChangeClass8_ =5;  #Количество игр, которое нужно сыграть, чтобы изменился класс A8
    $countMoveForChangeRating_  =10; #Количество ходов, которое надо сделать в партии для изменения рейтинга

    $pathImage_ ='Image/';

    class const_{

        public static $catalog_image_fugure ='Image/';
        public static $size_image_cell =68;
        public static $size_image_left_board =19;
        public static $size_image_right_board =18;
        public static $size_image_top_bottom_board =20;
#массив для отправки писем
# $e_mails_[]['e-mail'] - адрес куда отправляется письмо
# $e_mails_[]['message'] - текс письма
        public static $e_mails_ =array();

        public static $connect_ =false;
	public static $isTransact_ =false;

        public static function send_mails(){
            for ($i=0; $i < count(const_::$e_mails_); $i++)
              CUsers_::SendToEMail(const_::$e_mails_[$i]['message'],const_::$e_mails_[$i]['e-mail']);
        }#send_mails()

        public static function set_params_image_board($exception_on_error=true){
          if (isset($_SESSION[SESSION_ID_])){
             $num_board_ =CUsers_::Read_view_board($exception_on_error);
             if (is_null($num_board_) || (($num_board_ != 2) && ($num_board_ != 3))) $num_board_ =0;
           }else $num_board_ =0;

          switch ($num_board_){
            case 0 : const_::$catalog_image_fugure =cat_img_figures_main;
                     const_::$size_image_cell =68;
                     const_::$size_image_left_board =19;
                     const_::$size_image_right_board =18;
                     const_::$size_image_top_bottom_board =20;
                     break;
            case 2 : const_::$catalog_image_fugure =cat_img_figures_2;
                     const_::$size_image_cell =68;
                     const_::$size_image_left_board =20;
                     const_::$size_image_right_board =20;
                     const_::$size_image_top_bottom_board =20;
                     break;
            case 3 : const_::$catalog_image_fugure =cat_img_figures_3;
                     const_::$size_image_cell =68;
                     const_::$size_image_left_board =19;
                     const_::$size_image_right_board =18;
                     const_::$size_image_top_bottom_board =20;
                     break;
          }#switch
        }#set_params_image_board

#Возвращает массив ['reg_login'] - количество зарегистрированных логинов
#                  ['games'] - играется партий
#                  ['end_games'] - сыграно партий с количеством ходов > 10 или поставлен мат
        public static function statistic_info(){
          $connect_  =false;
	  $transact_ =false;
	  $cursor_   =false;
	  try{
	    $result_ =array();
	    if (!const_::$connect_)
	      if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
            if (!const_::$isTransact_)
	      if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

#Количество зарегистрированных логинов
            $s ='select count(*) as count_ from TGamers_';
	    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
	    $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
	    $result_['reg_login'] = $row_['count_'];
	    mysql_free_result($cursor_); $cursor_ =false;
#Количество играющихся партий
	    $s ='select count(*) as count_ from TGames_ where result_ is null';
	    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
	    $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
	    $result_['games'] = $row_['count_'];
	    mysql_free_result($cursor_); $cursor_ =false;
#Количество завершенных партий, где сделано ходов > 10, либо поставлен мат
	    $s ='select count(*) as count_'.
	        ' from TGames_ A'.
		' where (not result_ is null) and '.
		'       exists(select * from TMoves_ where (idGame_ =A.id_) and ((num_ > 10) or (w_isCheckMate_=\'Y\') or (b_isCheckMate_=\'Y\')))';
	    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
	    $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
	    $result_['end_games'] = $row_['count_'];
	    mysql_free_result($cursor_); $cursor_ =false;

	    if ($transact_)
	      if (const_::Commit_()) $transact_ =false; else throw new Exception();
            if ($connect_) const_::Disconnect_();

	    return $result_;
	  }catch (Exception $e){
	    if ($cursor_) mysql_free_result($cursor_);
	    if ($transact_) const_::Rollback_();
	    if ($connect_) const_::Disconnect_();
	    throw new Exception('Не удалось установить связь с базой данных.<BR> Попробуйте зайти позже.');
	  }
	}#statistic_info


	public static function CalcRating($Ra,$Rb,$Sa,$class_){
	  $Ea =round(1/(1+pow(10,($Rb-$Ra)/400)),2);
	  if ($class_ == 8) $K=25;
	   elseif ($Ra >=2400) $K=10;
	   else $K=15;
	  $Ra_new =$Ra + round($K*($Sa-$Ea));
	  if ($Ra_new < 0) $Ra_new =0;
	  return $Ra_new;
        }

	public static function StartTransaction_(){
	  if (mysql_query('start transaction',const_::$connect_)){
	    const_::$isTransact_ =true;
	    $result_ = true;
	  }else
	    $result_ = false;
	  return $result_;
	}#StartTransaction()

	public static function Commit_(){
	  if (mysql_query('commit',const_::$connect_)){
	    const_::$isTransact_ =false;
	    $result_ =true;
	  }else
	    $result_ =false;
	  return $result_;
	}#Commit_

        public static function Rollback_(){
	  if (mysql_query('rollback',const_::$connect_)){
            const_::$isTransact_ =false;
            $result_ =true;
          }else
            $result_ =false;
            return $result_;
        }#Rollback_

	public static function SetConnect_(){
	  $c =mysql_connect(HOST_,USER_,PASSWORD_);
          if ($c) $result_ =const_::init_($c); else $result_ =false;
          if ($result_) const_::$connect_ =$c;
          const_::set_last_connect_();
          return $result_;
        }#connect

        public static function Disconnect_(){
          mysql_close(const_::$connect_);
          const_::$connect_ =false;
        }#disconnect

        protected static function init_($connect_){
          return mysql_select_db(BASE_,$connect_) && mysql_query('set names cp866',$connect_) &&
                 mysql_query('set autocommit=0',$connect_);
        }#init

        protected static function set_last_connect_(){
            if (isset($_SESSION[SESSION_ID_])){
                $s ='update TGamers_ set last_connect_ ='.time().' where id_='.$_SESSION[SESSION_ID_];
                mysql_query($s,const_::$connect_);
            }
        }#set_last_connect_
    } #const_


    function reglamentsMail_toStr(){
        global $reglamentsMail_;
        $result_ ='';
        for ($i=0; $i < count($reglamentsMail_); $i++){
          if ($result_ != '') $result_ .=',';
          $result_ .=$reglamentsMail_[$i];
        } #for
	return $result_;
    } #reglamentsMail_toStr

//Выбирает вариант слова из case1,case2_4,case5 в зависимости от числа n
    function OutCase($n,$case1,$case2_4,$case5){
        $i =$n % 100;
        if ($i > 20) $i =$i % 10;
        switch ($i){
          case 1: $Result =$case1; break;
          case 2:
          case 3:
          case 4: $Result =$case2_4; break;
          default: $Result =$case5;
        }
        return $Result;
    }

//Преобразует время на часах (в секундах) в строку количество дней часов:минут:секунд
    function clockToStr($clock_){
        $d_ = floor($clock_ / (60*60*24));
	$clock_ = $clock_ % (60*60*24);
	$h_ =floor($clock_ / (60*60));
	$clock_ =$clock_ % (60*60);
	$m_ =floor($clock_ / 60);
	$s_ = $clock_ % 60;

	$result_ =$d_.OutCase($d_,' день',' дня',' дней').sprintf(" %02u:%02u:%02u",$h_,$m_,$s_);
	return $result_;
    }

#Переводит дату в строку формата дд/мм/гггг
    function dateToStr_($day_,$month_,$year_){
        $result_ =(($day_ < 10) ? '0' : '').$day_.'/'.(($month_ < 10) ? '0' : '').$month_.'/'.$year_;
        return $result_;
    }#dateToStr_

#Разбирает строку даты формата дд/мм/гггг
    function strToDate_($str_,&$day_,&$month_,&$year_){
        $result_ =false;
        if (preg_match('/^\s*(\d{1,2})\s*\/\s*(\d{1,2})\s*\/\s*(\d{4})\s*$/',$str_,$l_))
            if (checkdate($l_[2],$l_[1],$l_[3])){
                $day_   =$l_[1];
                $month_ =$l_[2];
                $year_  =$l_[3];
                $result_ =true;
            }
        return $result_;
    }#strToDate_

//Вычисляет начальное время на часах в зависимости от регламента
    function GetBeginTime($reglament_){
        switch($reglament_){
            case 1:   $result_ =7* 24*60*60;  break;
            case 2:   $result_ =30* 24*60*60; break;;
            case 3:   $result_ =3* 24*60*60;  break;
            case 4:   $result_ =10* 24*60*60; break;
            case 5:   $result_ =15* 24*60*60; break;
            case 6:   $result_ =5* 24*60*60;  break;
            case 7:   $result_ =30*60;  break;
            case 8:   $result_ =15*60;  break;
            case 9:   $result_ =10*60;  break;
            case 10:  $result_ =7*60;  break;
            case 11:  $result_ =60*60*24;  break;
            default: $result_ =0;
        }
        return $result_;
    }#GetBeginTime

    class CPage_{
        public static $header_ ='';
        public static $menu_ = array();
        public static $sub_menu_ = array();
        public static $table_stat_ ='';
        public static $body_='';
        public static $action_form_ ='';
        public static $text_error_ ='';
        public static $comments_game_ ='';
        public static $title_ ='';
        public static $add_file_style='';
        public static $root_catalog=''; #Путь до корневого каталога
        public static $use_jquery =false;
        public static $boxInfo_ =''; #Информационное сообщение в виде модального окна, которое выводится при
                                     #входе на сайт.
        
        public static function get_metrika_yandex(){
          return 
            '<!-- Yandex.Metrika counter -->'.
            '<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>'.
            '<script type="text/javascript">'.
            'try { var yaCounter247887 = new Ya.Metrika({id:247887});'.
            '} catch(e) { }'.
            '</script>'.
            '<noscript><div><img src="//mc.yandex.ru/watch/247887" style="position:absolute; left:-9999px;" alt="" /></div></noscript>'.
            '<!-- /Yandex.Metrika counter -->';
        }#get_metrika_yandex


        public static function PositionMenu_($item_){
            $result_ =-1;
            if ($item_ =='Информация')      $result_=1;
              else if ($item_ =='Партии')    $result_=2;
              else if ($item_ =='Турниры')   $result_=3;
              else if ($item_ =='Вызовы')    $result_=4;
              else if ($item_ =='Настройка') $result_=5;
              else if ($item_ =='Игроки')    $result_=6;
#              else if ($item_ =='Задачи')    $result_=7;
              else if ($item_ =='Команды')   $result_=8;
              else if ($item_ =='Описание')  $result_=9;
              else if ($item_ =='Выход')     $result_=10;
            $result_ -=1;
            return $result_;
        }#PositionMenu_

       public static function MakeMenu_($active_item_){
         $i =0;
#Информация
         CPage_::$menu_[$i]['link'] =($active_item_ ==CPage_::PositionMenu_('Информация')) ? '' : 'MainPage.php?link_=Events';
         CPage_::$menu_[$i]['image'] ='Image/label_info.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =($active_item_ ==CPage_::PositionMenu_('Информация')) ? 'Y' : 'N';
         while (isset(CPage_::$menu_[$i])) $i++;
#Партии
         CPage_::$menu_[$i]['link'] =($active_item_ ==CPage_::PositionMenu_('Партии')) ? '' : 'MainPage.php?link_=you_active_games';
         CPage_::$menu_[$i]['image'] ='Image/label_games.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =($active_item_ ==CPage_::PositionMenu_('Партии')) ? 'Y' : 'N';
          while (isset(CPage_::$menu_[$i])) $i++;
#Турниры
         CPage_::$menu_[$i]['link'] =($active_item_ ==CPage_::PositionMenu_('Турниры')) ? '' : 'MainPage.php?link_=you_active_tournaments';
         CPage_::$menu_[$i]['image'] ='Image/label_tournaments.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =($active_item_ ==CPage_::PositionMenu_('Турниры')) ? 'Y' : 'N';
         while (isset(CPage_::$menu_[$i])) $i++;
#Вызовы
         CPage_::$menu_[$i]['link'] =($active_item_ ==CPage_::PositionMenu_('Вызовы')) ? '' : 'MainPage.php?link_=calls_off_line';
         CPage_::$menu_[$i]['image'] ='Image/label_calls.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =($active_item_ ==CPage_::PositionMenu_('Вызовы')) ? 'Y' : 'N';
         while (isset(CPage_::$menu_[$i])) $i++;
#Настройка
         CPage_::$menu_[$i]['link'] =($active_item_ ==CPage_::PositionMenu_('Настройка')) ? '' : 'MainPage.php?link_=aboutYou';
         CPage_::$menu_[$i]['image'] ='Image/label_configuration.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =($active_item_ ==CPage_::PositionMenu_('Настройка')) ? 'Y' : 'N';
         while (isset(CPage_::$menu_[$i])) $i++;
#Игорки
         CPage_::$menu_[$i]['link'] =($active_item_ ==CPage_::PositionMenu_('Игроки')) ? '' : 'MainPage.php?link_=mens_on_site';
         CPage_::$menu_[$i]['image'] ='Image/label_mens.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =($active_item_ ==CPage_::PositionMenu_('Игроки')) ? 'Y' : 'N';
         while (isset(CPage_::$menu_[$i])) $i++;
#Команды
         CPage_::$menu_[$i]['link'] =($active_item_ ==CPage_::PositionMenu_('Команды')) ? '' : 'MainPage.php?link_=chess_commands';
         CPage_::$menu_[$i]['image'] ='Image/label_commands.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =($active_item_ ==CPage_::PositionMenu_('Команды')) ? 'Y' : 'N';
#         while (isset(CPage_::$menu_[$i])) $i++;
#Задачи
#         CPage_::$menu_[$i]['link'] =($active_item_ ==CPage_::PositionMenu_('Задачи')) ? '' : 'tasks_.php';
#         CPage_::$menu_[$i]['image'] ='Image/label_tasks.png';
#         CPage_::$menu_[$i]['submit'] =false;
#         CPage_::$menu_[$i]['level'] =1;
#         CPage_::$menu_[$i++]['active'] =($active_item_ ==CPage_::PositionMenu_('Задачи')) ? 'Y' : 'N';
#         while (isset(CPage_::$menu_[$i])) $i++;

#Интересно...
         CPage_::$menu_[$i]['link'] = 'info_title_page.php?link_=links_';
         CPage_::$menu_[$i]['image'] ='Image/it-is-interesting.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] ='N';

#Описание
         CPage_::$menu_[$i]['link'] =($active_item_ ==CPage_::PositionMenu_('Описание')) ? '' : 'doc_.php';
         CPage_::$menu_[$i]['image'] ='Image/label_docs.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =($active_item_ ==CPage_::PositionMenu_('Описание')) ? 'Y' : 'N';
         while (isset(CPage_::$menu_[$i])) $i++;
#Выход
         CPage_::$menu_[$i]['link'] =($active_item_ ==CPage_::PositionMenu_('Выход')) ? '' : 'index.php';
         CPage_::$menu_[$i]['image'] ='Image/label_exit.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] ='N';;
       }#MakeMenu_

#page: 1-клубы, 2 - школы, 3 - федерации, 4 - ассоциации, 5 - персональные, 6 - игровые зоны, 7 - прочее       
#      10 - обои, 11 - работа
#      20 - мат в два хода, 21 - мат в три хода, 22 - мат в четыре хода, 23 - мат в пять ходов
#      24 - как бы ва сыграли, 25 - ваши задачи, 26 - создать задачу, 27 - задачи на проверку
       
       public static function MakeMenu_interesting($page_){
         $i=0;
         
         CPage_::$menu_[$i]['link'] = 'champions/champions-of-chess.php';
         CPage_::$menu_[$i]['image'] ='Image/champions.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] ='N';
         
         CPage_::$menu_[$i]['link'] = ((($page_ >=20) && ($page_ <=27)) ? '' : 'tasks_.php');
         CPage_::$menu_[$i]['image'] ='Image/label_tasks.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =((($page_ >=20) && ($page_ <=27)) ? 'Y' : 'N');
         
         if (($page_ >=20) && ($page_ <=27)){
           CPage_::$menu_[$i]['link'] =($page_ ==20) ? '' : 'tasks_.php?type_=0';
           CPage_::$menu_[$i]['image'] ='Image/label_checkmate_two_move.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==20) ? 'Y' : 'N';

           CPage_::$menu_[$i]['link'] = ($page_ ==21) ? '' : 'tasks_.php?type_=1';
           CPage_::$menu_[$i]['image'] ='Image/label_checkmate_three_move.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==21) ? 'Y' : 'N';

           CPage_::$menu_[$i]['link'] = ($page_ ==22) ? '' : 'tasks_.php?type_=2';
           CPage_::$menu_[$i]['image'] ='Image/label_checkmate_four_move.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==22) ? 'Y' : 'N';

           CPage_::$menu_[$i]['link'] = ($page_ ==23) ? '' : 'tasks_.php?type_=3';
           CPage_::$menu_[$i]['image'] ='Image/label_checkmate_five_move.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==23) ? 'Y' : 'N';

           CPage_::$menu_[$i]['link'] = ($page_ ==24) ? '' : 'tasks_.php?type_=4';
           CPage_::$menu_[$i]['image'] ='Image/label_best_variant.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==24) ? 'Y' : 'N';

           if (isset($_SESSION[SESSION_ID_]) && ($_SESSION[SESSION_ID_] ==1)){
             CPage_::$menu_[$i]['link'] = ($page_ ==25) ? '' : '';
             CPage_::$menu_[$i]['image'] ='Image/label_our_tasks.png';
             CPage_::$menu_[$i]['submit'] =false;
             CPage_::$menu_[$i]['level'] =2;
             CPage_::$menu_[$i++]['active'] =($page_ ==25) ? 'Y' : 'N';

             if ($_SESSION[SESSION_ID_] ==1){
               CPage_::$menu_[$i]['link'] = ($page_ ==26) ? '' : 'tasks_.php?add=make_';
               CPage_::$menu_[$i]['image'] ='Image/label_make_task.png';
               CPage_::$menu_[$i]['submit'] =false;
               CPage_::$menu_[$i]['level'] =2;
               CPage_::$menu_[$i++]['active'] =($page_ ==26) ? 'Y' : 'N';
             }

             if ($_SESSION[SESSION_ID_] ==1){
               CPage_::$menu_[$i]['link'] = ($page_ ==27) ? '' : '';
               CPage_::$menu_[$i]['image'] ='Image/label_check_tasks.png';
               CPage_::$menu_[$i]['submit'] =false;
               CPage_::$menu_[$i]['level'] =2;
               CPage_::$menu_[$i++]['active'] =($page_ ==27) ? 'Y' : 'N';
             }
           }
         }
         CPage_::$menu_[$i]['link'] = ((($page_ >=1) && ($page_ <=7)) ? '' : 'info_title_page.php?link_=links_');
         CPage_::$menu_[$i]['image'] ='Info/Image/label_links.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =((($page_ >=1) && ($page_ <=7)) ? 'Y' : 'N');
          
         if(($page_ >=1) && ($page_ <=7)){
           CPage_::$menu_[$i]['link'] = ($page_ ==1) ? '' : 'info_title_page.php?link_=links_';
           CPage_::$menu_[$i]['image'] ='Info/Image/label_clubs.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==1) ? 'Y' : 'N';

           CPage_::$menu_[$i]['link'] = ($page_ ==2) ? '' : 'info_title_page.php?link_=links_&add=schools';
           CPage_::$menu_[$i]['image'] ='Info/Image/label_schools.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==2) ? 'Y' : 'N';

           CPage_::$menu_[$i]['link'] = ($page_ ==3) ? '' : 'info_title_page.php?link_=links_&add=federations';
           CPage_::$menu_[$i]['image'] ='Info/Image/label_federations.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==3) ? 'Y' : 'N';

           CPage_::$menu_[$i]['link'] = ($page_ ==4) ? '' : 'info_title_page.php?link_=links_&add=associations';
           CPage_::$menu_[$i]['image'] ='Info/Image/label_associations.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==4) ? 'Y' : 'N';

           CPage_::$menu_[$i]['link'] = ($page_ ==5) ? '' : 'info_title_page.php?link_=links_&add=persons';
           CPage_::$menu_[$i]['image'] ='Info/Image/label_persons.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==5) ? 'Y' : 'N';

           CPage_::$menu_[$i]['link'] = ($page_ ==6) ? '' : 'info_title_page.php?link_=links_&add=game_zons';
           CPage_::$menu_[$i]['image'] ='Info/Image/label_game_zons.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==6) ? 'Y' : 'N';

           CPage_::$menu_[$i]['link'] = ($page_ ==7) ? '' : 'info_title_page.php?link_=links_&add=other';
           CPage_::$menu_[$i]['image'] ='Info/Image/label_other.png';
           CPage_::$menu_[$i]['submit'] =false;
           CPage_::$menu_[$i]['level'] =2;
           CPage_::$menu_[$i++]['active'] =($page_ ==7) ? 'Y' : 'N';
         }
           
         CPage_::$menu_[$i]['link'] =($page_ ==10 ? '' : 'info_title_page.php?link_=oboi');
         CPage_::$menu_[$i]['image'] ='Image/label_chess_images.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =($page_ ==10 ? 'Y' : 'N');
           
         CPage_::$menu_[$i]['link'] = ($page_ ==11 ? '' : 'info_title_page.php?link_=work_');
         CPage_::$menu_[$i]['image'] ='Info/Image/label_work.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] =($page_ ==11 ? 'Y' : 'N');
           
         CPage_::$menu_[$i]['link'] =(isset($_SESSION[SESSION_ID_]) ? 'MainPage.php?link_=Events' : 'http://chessandmail.ru');
         CPage_::$menu_[$i]['image'] ='Image/label_begin.png';
         CPage_::$menu_[$i]['submit'] =false;
         CPage_::$menu_[$i]['level'] =1;
         CPage_::$menu_[$i++]['active'] ='N';
       }#MakeMenu_interesting

       public static function BodyError(){
         CPage_::$body_ ='<TABLE>'."\n".
	                 '	<TR>'."\n".
			 '		<TD>'."\n".
			 '			<IMG src="Image/image_stop.png" width="144" height="268" style="border:none">'."\n".
			 '		</TD>'."\n".
			 '		<TD width="100%" style="text-align:center; vertical-align: middle">'."\n".
			 '			<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
			                                    'font-size: 14pt; color: white;'.
							    'text-decoration: none; font-weight: normal;'.
							    'text-align: center">'."\n".
					           CPage_::$text_error_."\n".
                         '			</SPAN>'."\n".
                         '		</TD>'."\n".
                         '	</TR>'."\n".
                         '</TABLE>';
		}#BodyError

        public static function BodyQuestion($question_){
           CPage_::$body_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                       'font-size: 14pt; color: white;'.
                                       'text-decoration: none; font-weight: normal;'.
                                       'text-align: center">'."\n".
                                $question_."\n".
                           '</DIV>';
        }#BodyQuestion

        public static function BodyMessage($message_){
           CPage_::$body_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                       'font-size: 14pt; color: white;'.
                                       'text-decoration: none; font-weight: normal;'.
                                       'text-align: center">'."\n".
                                $message_."\n".
                           '</DIV>';
        }#BodyMessage

        public static function Aforizm_(){
            CPage_::$header_ ='  <SPAN id="text_aforizm_">'.
                              '     Шахматы, так же, как любовь, требуют партнера.'.
                              '     <BR>'.
                              '     (Стефан Цвейг)'.
                              '  </SPAN>';
        }#Aforizm_

        public static function BodyWelcome($login_){
            CPage_::$body_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                        'font-size: 16pt; color: white; text-align: center;'.
                                                        'text-decoration: none; font-weight: normal">'.
                                              'Добро пожаловать'.
                                         '</DIV><BR>'."\n".
                                         '<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                                                   'font-size: 14pt; color: white;'.
                                                                   'text-decoration: none; font-weight: normal">'."\n";
            if (CPage_::$text_error_ !='')
            	CPage_::$body_ .='<DIV style="text-align:center">'."\n".
            					 	CPage_::$text_error_."\n".
            					 '</DIV>'."\n";
            CPage_::$body_ .='    <TABLE style="margin-left: auto; margin-right: auto">'."\n".
                             '      <COL span="2">'."\n".
                             '      <TR>'."\n".
                             '         	<TD><LABEL  for="login_id">имя</LABEL></TD>'."\n".
                             '     		<TD><INPUT type="text" id="login_id" name="login_" value="'.$login_.'"></TD>'."\n".
                             '      </TR>'."\n".
                             '      <TR>'."\n".
                             '     		<TD><LABEL for="password_id">пароль</LABEL></TD>'."\n".
                             '     		<TD><INPUT type="password" id="password_id" name="password_"></TD>'."\n".
                             '     	</TR>'."\n".
                             '     </TABLE><BR>'."\n".
                             '</SPAN>'."\n".
                             '<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                          'font-size: 12pt; color: black;'.
                                          'text-decoration: none;	font-weight: normal;'.
                                          'text-align: justify; text-indent: 30px">'."\n".
                             '     <DIV>'."\n".
                             '       Рад приветствовать Вас на странице шахматного Интернет клуба ChessAndMail. Стать членом клуба может каждый желающий,'.
                             '       не зависимо от того умеете Вы играть в шахматы или нет. Если не умеете, то тут Вы сможете научиться, в разделе "Описание"'.
                             '       можно прочитать основные правила игры: как расставлять фигуры, как они ходят. Клуб поможет Вам с'.
                             '       практикой – подберет подходящего партнера. Несмотря на то, что клуб русскоговорящий, география членов ChessAndMail'.
                             '       очень широка. С Вами за доской может оказаться человек, живущий в другой стране, и даже на другой стороне земного шара.'.
                             '     <P>'.
                             '       Если Вы уже умеете играть и желаете совершенствоваться далее в этой старинной игре, клуб тоже поможет Вам в этом. Здесь Вы сможете'.
                             '       играть в удобном для Вас режиме. На данный момент, можно играть партии трех классов A, B, C. Класс A это партии, играющиеся по'.
                             '       переписке без использования компьютерного анализа. Класс C это то же шахматы по переписке, но уже с использованием компьютера. Для партий этих'.
                             '       классов доступны регламенты 7 и 30 дней на партию, 1 день + 1 час, 5 дней + 1 день, 10+1, 15+1 и 3 дня на ход. Класс B это партии,'.
                             '       играющиеся в режиме онлайн, для них доступны контроли времени 7, 10, 15 и 30 минут на партию. В клубе регулярно проводятся различные'.
                             '       турниры по круговой и швейцарской системам. А турнирная система подберет соперника вашего уровня. Каждому члену клуба присваивается'.
                             '       класс от A8 до A1, от B8 до B1 и от C8 до C1, отражающий силу игры. В одном турнире могут принять участие только игроки'.
                             '       одного класса. Раздел “Информация” содержит ссылки на другие ресурсы шахматной тематики, где Вы сможете найти много дополнительного материала.'.
                             '     <P>'.
                             '       Если Вы профессионал, имеет разряд, звание, клуб тоже будет Вам интересен. На сайте клуба можно найти интересные шахматные задачи.'.
                             '       Как пример хочу привести двухходовую задачу, размещенную под номером 3.'.
                             '       Её придумал в XIX веке американец Самюэль Лойд. Казалось бы, мат в два хода, что проще, но не все популярные шахматные движки'.
                             '       находят решение. Играя партию, Вы можете вести с Вашим оппонентом переписку на любом языке, которая будет видна'.
                             '       только вам. В клубе можно не только общаться за партией, можно оставлять персональные сообщения для любого члена ChessAndMail,'.
                             '       можно приватно общаться и в режиме онлайн. Записи, оставленные в гостевой книге видны всем членам клуба, причем сайт автоматически оповестит'.
                             '       каждого о Вашем сообщении. При желании Вы можете оставить в гостевой книге и любое рекламное сообщение, так или иначе относящееся к шахматам.'.
                             '     <P>'.
                             '       Чтобы стать членом клуба Вам нужно зарегистрироваться, но регистрация очень проста, достаточно придумать имя, под которым Вас будут'.
                             '       видеть другие и пароль. После регистрации, если пожелаете, можно указать дополнительную информацию о себе и разместить фотографию.'.
                             '       Для игры в шахматы ставить дополнительное программное обеспечение не нужно. Сайт клуба прекрасно работает на всех web браузерах во всех'.
                             '       операционных системах. Если у Вас операционная система семейства Windows, можно использовать браузер Microsoft Internet Explorer начиная'.
                             '       от 7 версии, Google Chrome, FireFox, Opera ставить никаких дополнительных надстроек тоже не нужно. Если у Вас операционная система'.
                             '       семейства Linux можно использовать Google Chrome, FireFox, Konqueror, Epiphany. Вы можете посещать сайт используя сотовый телефонов, например Nokia C5.'.
                             '       Если у Вас более или менее современный браузер, на сайте ChessAndMail Вы получите удовольствие от этой старинной игры, которая победила время.'.
                             '     </DIV><BR>'."\n".
                             '     <DIV style="text-align:right; text-indent: 0px">'."\n".
                             '         Я буду рад Вашим предложениям и пожеланиям, которые Вы можете оставить в гостевой книге, напрямую написать мне через этот сайт или отправить по электронной почте'."\n".
                             '         <SPAN style="white-space:nowrap">'."\n".
                             '             на адрес anton-mk@yandex.ru'."\n".
                             '         </SPAN>'."\n".
                             '         <BR>'."\n".
                             '         Колосовский Антон Михайлович'."\n".
                             '     </DIV>'."\n".
                             '</SPAN><BR>'."\n".
#реклама google
#                             CPage_::MakeBottomAdvertisGoogle().
#счётчик Rambler
                             '<!-- begin of Top100 code -->'."\n".
                             '<script id="top100Counter" type="text/javascript" src="http://cnt.rambler.ru/top100.jcn?1801381">'."\n".
                             '</script>'."\n".
                             '<noscript>'."\n".
                             '     <a href="http://top100.rambler.ru/home?id=1801381" target="_blank">'."\n".
                             '         <img src="http://cnt.rambler.ru/top100.cnt?1801381" alt="Rambler\'s Top100" width="81" height="63" border="0" />'."\n".
                             '     </a>'."\n".
                             '</noscript>'."\n".
                             '<!-- end of Top100 code -->'."\n".
#регистрация на mail.ru
                             '&nbsp;'."\n".
                             '<a href="http://list.mail.ru" target="_blank">'."\n".
                             '     <img src="http://list.mail.ru/i/88x31_13.gif" width="88" height="31" border="0" alt="Каталог@Mail.ru - каталог ресурсов интернет">'."\n".
                             '</a>'."\n".
#счетчик LiveInternet
                             '<!--LiveInternet counter--><script type="text/javascript"><!--'."\n".
                             '      document.write("<a href=\'http://www.liveinternet.ru/click\' "+'."\n".
                             '                     "target=_blank><img src=\'//counter.yadro.ru/hit?t53.6;r"+'."\n".
                             '                     escape(document.referrer)+((typeof(screen)=="undefined")?"":'."\n".
                             '                     ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?'."\n".
                             '                     screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+'."\n".
                             '                     ";"+Math.random()+'."\n".
                             '                     "\' alt=\'\' title=\'LiveInternet: показано число просмотров и"+'."\n".
                             '                     " посетителей за 24 часа\' "+'."\n".
                             '                     "border=\'0\' width=\'88\' height=\'31\'><\/a>")'."\n".
                             '//--></script><!--/LiveInternet-->'."\n".
#счетчик HotLog
                             '<!-- HotLog -->'."\n".
                             '<script type="text/javascript" language="javascript">'."\n".
                             ' hotlog_js="1.0"; hotlog_r=""+Math.random()+"&s=2075609&im=101&r="+'."\n".
                             '                           escape(document.referrer)+"&pg="+escape(window.location.href);'."\n".
                             ' document.cookie="hotlog=1; path=/"; hotlog_r+="&c="+(document.cookie?"Y":"N");'."\n".
                             '</script>'."\n".
                             '<script type="text/javascript" language="javascript1.1">'."\n".
                             ' hotlog_js="1.1"; hotlog_r+="&j="+(navigator.javaEnabled()?"Y":"N");'."\n".
                             '</script>'."\n".
                             '<script type="text/javascript" language="javascript1.2">'."\n".
                             '  hotlog_js="1.2"; hotlog_r+="&wh="+screen.width+"x"+screen.height+"&px="+'."\n".
                             '  (((navigator.appName.substring(0,3)=="Mic"))?screen.colorDepth:screen.pixelDepth);'."\n".
                             '</script>'."\n".
                             '<script type="text/javascript" language="javascript1.3">'."\n".
                             '  hotlog_js="1.3";'."\n".
                             '</script>'."\n".
                             '<script type="text/javascript" language="javascript">'."\n".
                             '  hotlog_r+="&js="+hotlog_js;'."\n".
                             '  document.write(\'<a href="http://click.hotlog.ru/?2075609" target="_top"><img \'+'."\n".
                             '                 \'src="http://hit34.hotlog.ru/cgi-bin/hotlog/count?\'+'."\n".
                             '                 hotlog_r+\'" border="0" width="88" height="31" alt="HotLog"><\/a>\');'."\n".
                             '</script>'."\n".
                             '<noscript>'."\n".
                             '<a href="http://click.hotlog.ru/?2075609" target="_top"><img'."\n".
                             '  src="http://hit34.hotlog.ru/cgi-bin/hotlog/count?s=2075609&im=101" border="0"'."\n".
                             '  width="88" height="31" alt="HotLog"></a>'."\n".
                             '</noscript>'."\n".
                             '<!-- /HotLog -->'."\n".
#счетчик TopSport
                             '<!--Start TopSport-->'."\n".
                             '<a href=http://www.topsport.ru target=_blank>'."\n".
                             '<img src=http://www.topsport.ru/ts/counter.asp?id=28967 border=0></a>'."\n".
                             '<!--End TopSport-->'."\n".
#Счетчик Yandex                    
                             CPage_::get_metrika_yandex();
        }#BodyWelcome

        public static function MakeTableStat($logins_,$active_game_,$end_game_){
          $result_='<SPAN class="text_table_stat">'."\n".
                   '	<TABLE id="statistic" cellspasing="4">'."\n".
                   '		<TR>'."\n".
                   '			<TD>'."\n".
                   '				<TABLE cellspacing="0" cellpadding="0">'."\n".
                   '					<COL span="2">'."\n".
                   '					<TR><TD colspan="2" style="text-align:center">Статистика</TD></TR>'."\n".
                   '					<TR><TD style="text-align:left">зарегистрировано:</TD><TD style="text-align:right">'.$logins_.'</TD></TR>'."\n".
                   '					<TR><TD style="text-align:left">играется партий:</TD><TD style="text-align:right">'.$active_game_.'</TD></TR>'."\n".
                   '					<TR><TD style="text-align:left">сыграно партий:</TD><TD style="text-align:right">'.$end_game_.'</TD></TR>'."\n".
                   '				</TABLE>'."\n".
                   '			</TD>'."\n".
                   '		</TR>'."\n".
                   '	</TABLE>'."\n".
                   '</SPAN>'."\n";
                   return $result_;
        }#MakeTableStat

       protected static function MakeLeftAdvertisingGoogle(){
            $result_='  <BR>'."\n".
                     '  <TABLE id="left_advertising_google">'."\n".
                     '      <TR>'."\n".
                     '          <TD>'."\n".
                     '              <script type="text/javascript"><!--'."\n".
                     '                  google_ad_client = "pub-5124825078350206";'."\n".
                     '                  /* 180x90, создано 10.06.10 */'."\n".
                     '                  google_ad_slot = "1883535706";'."\n".
                     '                  google_ad_width = 180;'."\n".
                     '                  google_ad_height = 90;'."\n".
                     '                //-->'."\n".
                     '              </script>'."\n".
                     '              <script type="text/javascript"'."\n".
                     '                      src="http://pagead2.googlesyndication.com/pagead/show_ads.js">'."\n".
                     '              </script>'."\n".
                     '          </TD>'."\n".
                     '      </TR>'."\n".
                     '  </TABLE>'."\n";
            return $result_;
       }#MakeLeftAdvertisingGoogle

       protected static function MakeLeftVladBanner(){
            $result_=' '."\n".
                     '  <TABLE id="left_Vlad_Banner">'."\n".
                     '      <TR>'."\n".
                     '          <TD>'."\n".
                     '              <IMG src="Image/greetings/new_year_2015.jpg" width="181" height="272" style="border:none">'."\n".
                     '          </TD>'."\n".
                     '      </TR>'."\n".
                     '  </TABLE>'."\n";
            return $result_;
       }#MakeLeftVladBanner

       protected static function MakeBottomAdvertisGoogle(){
            $result_='  <TABLE id="bottom_advertising_google">'."\n".
                     '      <TR>'."\n".
                     '          <TD>'."\n".
                     '              <script type="text/javascript"><!--'."\n".
                     '                  google_ad_client = "pub-5124825078350206";'."\n".
                     '                  /* 468x60, создано 10.06.10 */'."\n".
                     '                  google_ad_slot = "9046783481";'."\n".
                     '                  google_ad_width = 468;'."\n".
                     '                  google_ad_height = 60;'."\n".
                     '                //-->'."\n".
                     '              </script>'."\n".
                     '              <script type="text/javascript"'."\n".
                     '                      src="http://pagead2.googlesyndication.com/pagead/show_ads.js">'."\n".
                     '              </script>'."\n".
                     '          </TD>'."\n".
                     '      </TR>'."\n".
                     '  </TABLE>'."\n".
                     '  <BR>'."\n";
            return $result_;
       }#MakeBottomAdvertisGoogle()

       public static function MakePage($head_script_=''){
#Вычисляю рабочую область
         $row_span =count(CPage_::$menu_) * 2 +1;
         $col_span  = 4;

         echo('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">'."\n");
         echo('<HTML>'."\n");
         echo('  <HEAD>'."\n");
         echo('    <META http-equiv="Content-type" content="text/html; charset=WINDOWS-1251">');
         echo('    <META name="author" content="Колосовский Антон Михайлович">'."\n");
         echo('    <META name="keywords" lang="ru" content="шахматы, шахматы играть, шахматы онлайн, шахматные игры, бесплатные шахматы, шахматная доска, шахматы переписка">'."\n");
         echo('    <META name="copyright" content="Copyright (c) 2009 by ChessAndMail.ru">'."\n");
         echo('    <META name="distribution" content="global">'."\n");
         echo('    <META name="rating" content="general">'."\n");
#Тег указывает браузерам, что страницу кэшировать не надо
         echo('    <META http-equiv="pragma" content="no-cache">'."\n");
#Тег указывает поисковым роботам, что страница находится в разработке и её желательно нужно переиндексировать при каждом посещении
         echo('    <META name="document-state" content="dynamic">'."\n");

         $meta_ ='<META name="description" lang="ru" '.
                 'content="Шахматный клуб ChessAndMail. В клубе можно играть по '.
                          'переписке и on-line. При желании можете принять участие в регулярно проводимых '.
                          'турнирах. Вашим соперником за доской может оказаться '.
                          'человек, живущий на другом континенте земного шара. С которым вы можете спокойно '.
                          'пообщаться за партией и после."'.
                 '>';
         echo($meta_."\n");

#Подтверждение прав на сайт для google
         echo('<meta name="google-site-verification" content="mmf6I34bAIaol17FEsz0S8fUfv1KnEZ5onb-KLkcnAA" />');
#Подтверждение прав на сайт для yandex
         echo('<meta name="yandex-verification" content="5e97fc5c1a32f458" />');

         echo('<LINK href="'.CPage_::$root_catalog.'styles/chess.css" rel="stylesheet" type="text/css">'."\n");
         if (CPage_::$add_file_style)
           echo('<LINK href="styles/'.CPage_::$add_file_style.'" rel="stylesheet" type="text/css">'."\n");

#title страниницы
         if (CPage_::$title_ =='')
           echo('<TITLE>Шахматы. СhessAndMail. Игра по переписке и on-line. </TITLE>'."\n");
         else
           echo('<TITLE>'.CPage_::$title_.'</TITLE>'."\n");

         if(CPage_::$use_jquery || isset($_SESSION[SESSION_ID_]))
           echo('<script type="text/javascript" src="'.CPage_::$root_catalog.'lib/jquery/jquery-1.7.2.js"></script>'."\n");
#Вывод информационного сообщения в виде модального окна при входе не сайт
         if(CPage_::$boxInfo_ != ''){
           echo('<link rel="stylesheet" href="lib/fancyBox/source/jquery.fancybox.css" type="text/css" media="screen" />');
           echo('<script type="text/javascript" src="lib/fancyBox/source/jquery.fancybox.js"></script>');
         }
#Поключение JavaScripts          
         if(isset($_SESSION[SESSION_ID_])){
           echo('<script type="text/javascript" src="'.CPage_::$root_catalog.'scripts/script_lib.js"></script>'."\n");                        
           try{
            $last_id_infoLogins =CUsers_::Read_last_id_infoLogins();   
           }catch(Exception $e){
            $last_id_infoLogins =false;   
           }
           if ($last_id_infoLogins !==false){
             echo('<script type="text/javascript" src="'.CPage_::$root_catalog.'scripts/info_logins.js"></script>'."\n");
           }
         }#if  
         if((isset($_SESSION[SESSION_ID_]) && ($last_id_infoLogins !==false)) || (CPage_::$boxInfo_ !='')){
             echo('<script type="text/javascript">');
             if(isset($_SESSION[SESSION_ID_]) && ($last_id_infoLogins !==false))
               echo('  var o_info_logins =new cl_info_logins();');
             echo('  $(function(){');
             if(isset($_SESSION[SESSION_ID_]) && ($last_id_infoLogins !==false)){
               echo('     o_info_logins.last_id='.$last_id_infoLogins.';');
               echo('     o_info_logins.start_();');
             }  
             if(CPage_::$boxInfo_ !=''){
               echo('$.fancybox({href:\''.CPage_::$boxInfo_.'\'});');
             }
             echo('});');
             echo('</script>');               
         }

         if ($head_script_ !='') echo($head_script_);         
         echo('</HEAD>'."\n");

         echo('<BODY>'."\n");
         if (CPage_::$action_form_ != '')
           echo('<FORM action="'.CPage_::$action_form_.'" method="post" enctype="multipart/form-data">'."\n");
         echo('<TABLE width="1020" border="0" align="center" cellpadding="0" cellspacing="0">'."\n");
         echo('  <COL width="182">'."\n");
         echo('  <COL width="13">'."\n");
         echo('  <COL width="11">'."\n");
         echo('  <COL width="31">'."\n");
         echo('  <COL width="207">'."\n");
         echo('  <COL width="246">'."\n");
         echo('  <COL width="285">'."\n");
#            echo('          <COL width="*">'."\n");
         echo('  <COL width="45">'."\n");
#Большинству браузеров данная строка нужна для корректного отображения страницы
         if (strpos($_SERVER['HTTP_USER_AGENT'],'Konqueror') ===false){
           echo('<TR>'."\n");
           echo('  <TD ></TD>'."\n");
           echo('  <TD></TD>'."\n");
           echo('  <TD></TD>'."\n");
           echo('  <TD></TD>'."\n");
           echo('  <TD></TD>'."\n");
           echo('  <TD></TD>'."\n");
           echo('  <TD></TD>'."\n");
           echo('  <TD></TD>'."\n");
           echo('</TR>'."\n");
         }
#Заголовок страницы
         echo('<TR>'."\n");
         echo('  <TD height="94" colspan="2"><IMG src="'.CPage_::$root_catalog.'Image/left_top.png" width="195" height="94" style="border:none; vertical-align: bottom"></TD>'."\n");
         echo('  <TD height="94" colspan="3" class="center_"><IMG src="'.CPage_::$root_catalog.'Image/left_title.jpg" width="249" height="94" style="border:none; vertical-align: bottom"></TD>'."\n");
         echo('  <TD height="94" colspan="2" class="center_" style="text-align:right" valign="bottom">'.((CPage_::$header_ != '') ? CPage_::$header_ : '&nbsp;' ).'</TD>'."\n");
         echo('  <TD height="94"><IMG src="'.CPage_::$root_catalog.'Image/right_top.png" width="45" height="94" style="border:none; vertical-align: bottom"></TD>'."\n");
         echo('</TR>'."\n");
#Верхняя линия
         echo('<TR>'."\n");
         echo('  <TD height="7" colspan="2" valign="top" class="left_"><IMG src="'.CPage_::$root_catalog.'Image/top_left_line_body.png" width="195" height="7" style="border:none; vertical-align: top"></TD>'."\n");
         echo('  <TD height="7" colspan="5" valign="top" class="top_center_">&nbsp;</TD>'."\n");
         echo('  <TD height="7" valign="top" class="right_"><IMG src="'.CPage_::$root_catalog.'Image/top_right_line_body.png" width="45" height="7" style="border:none; vertical-align: top"></TD>'."\n");
         echo('</TR>'."\n");
#Меню
         echo('<TR>'."\n");
         echo('  <TD colspan="3" class="left_206" valign="top">'."\n");
         echo('    <TABLE border="0" cellpadding="0" cellspacing="0" id="operations_">'."\n");
         echo('       <COL width="182">'."\n");
         echo('       <COL width="13">'."\n");
         echo('       <COL width="11">'."\n");
         for($i=0; $i < count(CPage_::$menu_); $i++){
           echo('<TR>'."\n");
           echo('<TD height="25" valign="top" class="left_">'."\n");
           if (CPage_::$menu_[$i]['submit'])
             echo('<INPUT type="image" src="'.CPage_::$menu_[$i]['image'].'" '.
                  'style="border:none; vertical-align:top"'.
                  (isset(CPage_::$menu_[$i]['name']) ? ' name="'.CPage_::$menu_[$i]['name'].'"' : '').'>'."\n");
           else{
             if ((CPage_::$menu_[$i]['active']=='N') && (!isset(CPage_::$menu_[$i]['lock'])))
               echo('<A href="'.CPage_::$menu_[$i]['link'].'">'."\n");
               echo('<IMG src="'.CPage_::$menu_[$i]['image'].'" width="181" height="23" border="0" style="border:none; vertical-align: top">'."\n");
               if ((CPage_::$menu_[$i]['active']=='N') && (!isset(CPage_::$menu_[$i]['lock'])))
                 echo('</A>'."\n");
           }
           echo('</TD>'."\n");
           echo('<TD colspan="2" valign="top">'."\n");
           if (CPage_::$menu_[$i]['level']==1)
             if (CPage_::$menu_[$i]['active']=='N'){
               if (CPage_::$menu_[$i]['submit'])
                 echo('<INPUT type="image" src="Image/button.jpg" style="border:none; vertical-align: top">'."\n");
               else{
                 if (isset(CPage_::$menu_[$i]['lock']))
                   $src_button='Image/button_lock.jpg';  
                 else{
                   $src_button='Image/button.jpg';
                   if (CPage_::$menu_[$i]['image'] =='Image/label_info.png'){
                     if (CUsers_::exists_non_read_personal_message() ||
                         CUsers_::exists_non_read_guest_book() ||
                         CUsers_::exists_non_read_info())
                       $src_button ='Image/button_email.jpg';
                   }else if (CPage_::$menu_[$i]['image'] =='Image/label_calls.png'){
                     if (CUsers_::exists_calls_class_A_C() || CUsers_::exists_calls_class_B())
                       $src_button ='Image/button_email.jpg';
                   }else if (CPage_::$menu_[$i]['image'] =='Image/label_games.png'){
                     if (CUsers_::exists_games_wait_move())
                       $src_button ='Image/button_email.jpg';
                   }
                 }  
                 if (!isset(CPage_::$menu_[$i]['lock'])) 
                   echo('<A href="'.CPage_::$menu_[$i]['link'].'">'."\n");                      
                 echo('<IMG src="'.$src_button.'" name="button_'.($i+1).'" width="24" height="25" border="0" style="border:none; vertical-align: top">'."\n");
                 if (!isset(CPage_::$menu_[$i]['lock']))
                   echo('</A>'."\n");
               }
             }else
               echo('<IMG src="Image/button_2.jpg" name="button_'.($i+1).'" width="24" height="25" border="0" style="border:none; vertical-align: top">'."\n");
           else
             if (CPage_::$menu_[$i]['active']=='N'){
               if (isset(CPage_::$menu_[$i]['lock']))
                 $src_button='Image/button_3_lock.png';  
               else{
                 echo('<A href="'.CPage_::$menu_[$i]['link'].'">'."\n");
                 $src_button='Image/button_3.png';
                 if (CPage_::$menu_[$i]['image'] =='Image/label_events.png'){
                   if (CUsers_::exists_non_read_info())
                     $src_button='Image/button_3_email.png';
                 }else if (CPage_::$menu_[$i]['image'] =='Image/label_personal_contacts.png'){
                   if (CUsers_::exists_non_read_personal_message())
                     $src_button='Image/button_3_email.png';
                 }else if (CPage_::$menu_[$i]['image'] =='Image/label_guestbook.png'){
                   if (CUsers_::exists_non_read_guest_book())
                     $src_button='Image/button_3_email.png';
                 }else if (CPage_::$menu_[$i]['image'] =='Image/label_calls_mail.png'){
                   if (CUsers_::exists_calls_class_A_C())
                     $src_button='Image/button_3_email.png';
                 }else if (CPage_::$menu_[$i]['image'] =='Image/label_calls_on_line.png'){
                   if (CUsers_::exists_calls_class_B())
                     $src_button='Image/button_3_email.png';
                 }else if (CPage_::$menu_[$i]['image'] =='Image/label_active_games.png'){
                   if (CUsers_::exists_games_wait_move())
                     $src_button='Image/button_3_email.png';
                 }
               }  
               echo('<IMG src="'.$src_button.'" name="button_'.($i+1).'" width="24" height="25" border="0" style="border:none; vertical-align: top">'."\n");
               if (!isset(CPage_::$menu_[$i]['lock']))
                 echo('</A>'."\n");
             }else
               echo('<IMG src="Image/button_4.png" name="button_'.($i+1).'" width="24" height="25" border="0" style="border:none; vertical-align: top">'."\n");
           echo('</TD>'."\n");
           echo('</TR>'."\n");
           if (($i ==(count(CPage_::$menu_)-1)) || (CPage_::$menu_[$i+1]['level']==1)){
             echo('<TR>'."\n");
             echo('  <TD height="15" colspan="3">&nbsp;</TD>'."\n");
             echo('</TR>'."\n");
           }
         }#for
         echo('</TABLE>'."\n");
#Доп. информация на левой панели
         echo(' <TABLE border="0" cellpadding="0" cellspacing="0">'."\n");
         echo('    <COL width="195">'."\n");
         echo('    <COL width="11">'."\n");
         echo('    <TR><TD>'."\n");
         if (CPage_::$table_stat_ !='')
           echo(CPage_::$table_stat_);
          elseif (CPage_::$comments_game_ !='')
           echo(CPage_::$comments_game_);
          else echo('&nbsp;');
         echo('    </TD><TD></TD></TR>'."\n");
         echo(' </TABLE>'."\n");
         echo('</TD>'."\n");
#Основная информация (тело)
         echo('<TD colspan="'.$col_span.'" class="center_" valign="top" style="padding-left:10px; padding-right:10px">'."\n");
         echo(CPage_::$body_);
         echo('</TD>'."\n");

         echo('<TD  valign="top" class="right_">&nbsp;</TD>'."\n");
         echo('</TR>'."\n");
#Нижняя линия
         echo('<TR>'."\n");
         echo('  <TD height="7" colspan="2" valign="top" class="left_">'."\n");
         echo('     <IMG src="'.CPage_::$root_catalog.'Image/bottom_left_line_body.png" width="195" height="7" style="border:none; vertical-align: top">'."\n");
         echo('  </TD>'."\n");
         echo('  <TD height="7" colspan="5" valign="top" class="bottom_center_"></TD>'."\n");
         echo('  <TD height="7" valign="top" class="right_">'."\n");
         echo('    <IMG src="'.CPage_::$root_catalog.'Image/bottom_right_line_body.png" width="45" height="7" style="border:none; vertical-align: top">'."\n");
         echo('  </TD>'."\n");
         echo('</TR>'."\n");
#Низ страницы
         echo('<TR>'."\n");
         echo('  <TD height="62" colspan="2">'."\n");
         echo('    <IMG src="'.CPage_::$root_catalog.'Image/left_bottom.png" width="195" height="62" style="border:none; vertical-align: top">'."\n");
         echo('  </TD>'."\n");
         echo('  <TD height="62" colspan="5" class="text_copyright">'."\n");
         echo('    2009 Chess And Mail. All rights reserved.<br>Web Template provided by International Design Studios Ltd.'."\n");
         echo('  </TD>'."\n");
         echo('  <TD height="62" width="45">'."\n");
         echo('    <IMG src="'.CPage_::$root_catalog.'Image/right_bottom.png" width="45" height="62" style="border:none; vertical-align: top">'."\n");
         echo('  </TD>'."\n");
         echo('</TR>'."\n");

         echo('</TABLE>'."\n");
         if (CPage_::$action_form_ != '')
           echo('</FORM>'."\n");
         echo('</BODY>'."\n");
         echo('</HTML>'."\n");
       }#MakePage

       public static function FirstPage(){
         $stat_ =const_::statistic_info();
         CPage_::$table_stat_ = CPage_::MakeTableStat($stat_['reg_login'],$stat_['games'],$stat_['end_games']).'<BR>'.
#        CPage_::MakeLeftAdvertisingGoogle().
#         CPage_::MakeLeftVladBanner();

         CPage_::Aforizm_();
         CPage_::$action_form_ ='index.php';

         CPage_::$menu_[0]['link'] = 'info_title_page.php?link_=links_';
         CPage_::$menu_[0]['image'] ='Image/it-is-interesting.png';
         CPage_::$menu_[0]['submit'] =false;
         CPage_::$menu_[0]['level'] =1;
         CPage_::$menu_[0]['active'] ='N';
         
         CPage_::$menu_[1]['link'] = 'doc_.php';
         CPage_::$menu_[1]['image'] ='Image/label_docs.png';
         CPage_::$menu_[1]['submit'] =false;
         CPage_::$menu_[1]['level'] =1;
         CPage_::$menu_[1]['active'] ='N';
         
         
#         CPage_::$menu_[1]['link'] = 'tasks_.php';
#         CPage_::$menu_[1]['image'] ='Image/label_tasks.png';
#         CPage_::$menu_[1]['submit'] =false;
#         CPage_::$menu_[1]['level'] =1;
#         CPage_::$menu_[1]['active'] ='N';
#         CPage_::$menu_[2]['link'] = 'info_title_page.php';
#         CPage_::$menu_[2]['image'] ='Image/label_info.png';
#         CPage_::$menu_[2]['submit'] =false;
#         CPage_::$menu_[2]['level'] =1;
#         CPage_::$menu_[2]['active'] ='N';
         CPage_::$menu_[2]['link'] = 'RegUser.php';
         CPage_::$menu_[2]['image'] ='Image/label_registration.png';
         CPage_::$menu_[2]['submit'] =false;
         CPage_::$menu_[2]['level'] =1;
         CPage_::$menu_[2]['active'] ='N';
         CPage_::$menu_[3]['link'] = '';
         CPage_::$menu_[3]['image'] ='Image/label_enter.png';
         CPage_::$menu_[3]['submit'] =true;
         CPage_::$menu_[3]['level'] =1;
         CPage_::$menu_[3]['active'] ='N';

         $login_ ='';
         if (isset($_POST['login_'])) $login_ = $_POST['login_'];
         CPage_::BodyWelcome($login_);
         CPage_::$title_ ='Шахматный Интернет клуб ChessAndMail.';
         CPage_::MakePage('');
       }#FirstPage

       public static function QuestionPage($question_,$link_cancel_,$link_confirm_){
         CPage_::$menu_[0]['link'] = $link_confirm_;
         CPage_::$menu_[0]['image'] ='Image/label_confirm.png';
         CPage_::$menu_[0]['submit'] =false;
         CPage_::$menu_[0]['level'] =1;
         CPage_::$menu_[0]['active'] ='N';
         CPage_::$menu_[1]['link'] = $link_cancel_;
         CPage_::$menu_[1]['image'] ='Image/label_cancel.png';
         CPage_::$menu_[1]['submit'] =false;
         CPage_::$menu_[1]['level'] =1;
         CPage_::$menu_[1]['active'] ='N';

         CPage_::BodyQuestion($question_);
         CPage_::MakePage();
       }#QusetionPage

       public static function MessagePage($message_,$link_esc_){
         CPage_::$menu_[0]['link'] = $link_esc_;
         CPage_::$menu_[0]['image'] ='Image/label_esc.png';
         CPage_::$menu_[0]['submit'] =false;
         CPage_::$menu_[0]['level'] =1;
         CPage_::$menu_[0]['active'] ='N';

         CPage_::BodyMessage($message_);
         CPage_::MakePage();
       }#MessagePage

       public static function PageErr(){
         CPage_::BodyError();
         CPage_::MakePage();
       }#PageErr

//Функция выводит форму запроса (текст и две кнопки подтвердить,отменить)
       public static function Question_($strQuestion_,$actionForm_){
         echo('<SPAN class="normal_">');
         echo('	<FORM action="'.$actionForm_.'" method="POST">');
         echo('		<TABLE style ="border: none; margin-left: auto; margin-right: auto" cellspacing="0" cellpadding="10">');
         echo('			<COL span="2"/>');
         echo('			<TBODY>');
         echo('				<TR><TD style="text-align: center" colspan="2">'.$strQuestion_.'</TD></TR>');
         echo('				<TR><TD style="text-align: right"><INPUT type="submit" name="ButtonOK" value="подтвердить"></TD>');
         echo('					<TD style="text-align: left"><INPUT type="submit" name="ButtonCancel" value="отменить"></TD>');
         echo('				</TR>');
         echo('			</TBODY>');
         echo('		</TABLE>');
         echo('	</FORM>');
         echo('</SPAN>');
       }#Question_
       
#rg - объект CRule_game_, $k - масшта, $pref_ - префикс id ячеек
       public static function OutBoard($rg,$k,$id_board,$pref_=''){
#Масштаб доски
         $l_=round(const_::$size_image_cell*$k);             settype($l_,"integer"); $w_  ='width="'.$l_.'"'; $h_ ='height="'.$l_.'"';
         $l_=round(const_::$size_image_left_board*$k);       settype($l_,"integer"); $w_l ='width="'.$l_.'"';
         $l_=round(const_::$size_image_right_board*$k);      settype($l_,"integer"); $w_r ='width="'.$l_.'"';
         $l_=round(const_::$size_image_top_bottom_board*$k); settype($l_,"integer"); $h_t ='height="'.$l_.'"';

         $c =CPage_::$root_catalog.const_::$catalog_image_fugure;
         $r ='<TABLE class="chessboard" cellspacing="0" cellpadding="0" '.($id_board !='' ? 'id="'.$id_board.'"' : '').'>'.
             '   <COL span="10">';
//верхняя часть доски
         $r .='<TR class="top_board"><TD><IMG '.$w_l.' '.$h_t.' src="'.$c.'board_tl.jpg"/></TD>';
         for($i_=1; $i_<9; $i_++)
           $r .='<TD><IMG '.$w_.' '.$h_t.' src="'.$c.'board_top.jpg"/></TD>';
         $r .='<TD><IMG '.$w_r.' '.$h_t.' src="'.$c.'board_tr.jpg"/></TD>'.
              '</TR>';
#доска
         for($i_=8; $i_>=1; $i_--){
           $r .='<TR>'.
                '  <TD><IMG '.$w_l.' '.$h_.' src="'.$c.'board_'.$i_.'.jpg"/></TD>'."\n";
           for($j_='A'; $j_<='H'; $j_=chr(ord($j_)+1)){
             $r .='<TD><IMG id="'.$pref_.$j_.$i_.'" '.$w_.' '.$h_.
                       ' src="'.$c.$rg->board_[$j_][$i_].$rg->getColorBoard($j_.$i_).'.jpg"/>'.
                  '</TD>';
           }//for $j_
           $r .='  <TD><IMG  '.$w_r.' '.$h_.' src="'.$c.'board_right.jpg"/></TD>'.
                '</TR>';
         }//for $i_
//нижняя часть доски
         $r .='<TR><TD><IMG  '.$w_l.' '.$h_t.' src="'.$c.'board_bl.jpg"/></TD>';
         for($i_='a'; $i_<='h'; $i_=chr(ord($i_)+1))
           $r .='<TD><IMG  '.$w_.' '.$h_t.' src="'.$c.'board_'.$i_.'.jpg"/></TD>';
         $r .='<TD><IMG  '.$w_r.' '.$h_t.' src="'.$c.'board_br.jpg"/></TD>'.
              '</TR>';

         $r .='</TABLE>';
         return $r;
       }#OutBoard
    } #CPage_
?>
