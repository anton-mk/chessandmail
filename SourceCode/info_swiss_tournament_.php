<?php
    require_once('const_.php');
    require_once('Users.php');

    class CInfo_swiss_tournament_{
#Функция возвращает статус турнира:
#  1: турнир не начат, этап регистрации
#  2: время начала турнира подошло, но первый тур ещё не начат
#  3: идет тур
#  4: тур завершен (не последний), следующий ещё не начат
#  5: последний тур завершен, но турнир не закрыт
#  6: турнир закрыт
#  7: тур завершен (не последний), следующий не начат, не наступило время начала, жеребьевки не было
#  8: тур завершен (не последний), следующий не начат, не наступило время начала, жеребьевка была
#  9: тур создан, подошло время начала
        protected static function get_status_($id_tournament){
            $cursor_ =false;
            try{
#проверяю начат турнир или нет (статус 1)
                $s = 'select IF(NOW() < begin_,1,2) as start_,end_,cRounds_'.
                     '  from TTournaments_'.
                     '  where id_ ='.$id_tournament;
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('При чтении информации о турнире произошла ошибка.');
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception('Турнир не найден.');
                $count_rounds =$row_['cRounds_'];
                mysql_free_result($cursor_); $cursor_ =false;
                if ($row_['start_'] ==1) return 1;
#проверяю турнир закрыт или нет (статус 6)
                if (!is_null($row_['end_'])) return 6;
#проверяю начат первый раунд или нет (статус 2)
                $s ='select MAX(round_) as max_ from TRoundsSwiss_ where (id_tournament_='.$id_tournament.') and (not id_game_ is null)';
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('При чтении информации о турнире произошла ошибка.');
                $row_ =mysql_fetch_array($cursor_);
                mysql_free_result($cursor_); $cursor_ =false;
                if (!$row_ || is_null($row_['max_'])) return 2;
#проверяю статус идет тур (статус 3)
                $curr_round_ =$row_['max_'];
                $s ='select count(*) as count_ from TRoundsSwiss_ A, TGames_ B'.
                    ' where (A.id_tournament_='.$id_tournament.') and (A.round_='.$curr_round_.') and'.
                    '       (A.id_game_ = B.id_) and (B.result_ is null)';
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('При чтении информации о турнире произошла ошибка.');
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception('При чтении информации о турнире произошла ошибка.');
                mysql_free_result($cursor_); $cursor_ =false;
                if ($row_['count_'] > 0) return 3;
#(статусы 4 и 5)
                if ($curr_round_ < $count_rounds){
#получаю номер последнего турнира для которого была жеребьевка
                  $s ='select MAX(round_) as max_ from TRoundsSwiss_ where id_tournament_='.$id_tournament;
                  $cursor_=mysql_query($s,const_::$connect_);
                  if (!$cursor_) throw new Exception('При чтении информации о турнире произошла ошибка.');
                  $row_ =mysql_fetch_array($cursor_);
                  if (!$row_) throw new Exception('При чтении информации о турнире произошла ошибка.');
                  mysql_free_result($cursor_); $cursor_ =false;
                  $r_ =$row_['max_'];
#получаю информацию о начале тура
                  $s ='select IF(NOW() < begin_,1,2) as start_'.
                      ' from TStartRoundsTournament'.
                      ' where (id_tournament_='.$id_tournament.') and (round_ ='.($curr_round_+1).')';
                  $cursor_=mysql_query($s,const_::$connect_);
                  if (!$cursor_) throw new Exception('При чтении информации о турнире произошла ошибка.');
                  $row_ =mysql_fetch_array($cursor_);
                  mysql_free_result($cursor_); $cursor_ =false;
                  if (!$row_) return 4;
                    else{
                      if ($row_['start_'] == 1){
                        if ($r_ > $curr_round_) return 8; else return 7;
                      }else{
                        if ($r_ > $curr_round_) return 9; else return 4;
                      }
                    }
                }else return 5;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception($e->getMessage());
            }
        }#get_status_

        protected static function info_tournament($id_tournament){
            global $reglaments_;
            $cursor_ =false;
            try{
                $s ='select DATE_ADD(begin_, INTERVAL -3 HOUR) as begin_,cRounds_,reglament_,class_'.
                    ' from TTournaments_'.
                    ' where id_ ='.$id_tournament;
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('При чтении информации о турнире произошла ошибка.');
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception('Турнир не найден.');
                $result_ ='турнир по швейцарской системе'.'<br>'.
                          'начало '.$row_['begin_'].', время московское'.'<br>'.
                          'число туров '.$row_['cRounds_'].'<br>'.
                          'регламент '.$reglaments_['reglament'.$row_['reglament_'].'_'].'<br>'.
                          'класс '.$row_['class_'];
                mysql_free_result($cursor_);
                return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception($e->getMessage());
            }
        }

#возвращает: 1 - игрок может зарегистрироваться в турнире,
#            2 - игрок может отменить регистрацию,
#            3 - регистрация/отмена регистрации запрещены
        protected static function status_registration($id_){
            $cursor_ =false;
            try{
#проверка - этап регистрации не прошел
                $s = 'select IF(NOW() < begin_,1,2) as start_,class_'.
                     '  from TTournaments_'.
                     '  where id_ ='.$id_;
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception('Турнир не найден.');
                mysql_free_result($cursor_); $cursor_ =false;
                if ($row_['start_'] ==2) return 3;
                $class_ =$row_['class_'];
#Проверка, игрок уже зарегистрировался
                $s ='select count(*) as count_'.
                    ' from TMembersTournament_ A'.
                    ' where (A.id_tournament_ ='.$id_.') and (A.id_gamer_ ='.$_SESSION[SESSION_ID_].')';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                mysql_free_result($cursor_); $cursor_ =false;
                if ($row_ && ($row_['count_'] > 0)) return 2;
#Проверка класса турнира
                if ((strlen(trim($class_)) == 1) ||
                    (($class_{0} == 'A') && ($class_ ==('A'.CUsers_::ReadClass_($_SESSION[SESSION_ID_],1)))) ||
                    (($class_{0} == 'B') && ($class_ ==('A'.CUsers_::ReadClass_($_SESSION[SESSION_ID_],2)))) ||
                    (($class_{0} == 'C') && ($class_ ==('A'.CUsers_::ReadClass_($_SESSION[SESSION_ID_],3)))))
                  return 1;

                return 3;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($e->getMessage() == '')
                  throw new Exception('При чтении информации о турнире произошла ошибка.');
                 else
                  throw new Exception($e->getMessage());
            }
        }#status_registration

/*
     Турнирная таблица имеет следующий вид:
      -----------------------------------------------------------------------------------------
      |     Игрок       |      Тур 1       |      Тур 2       |      Тур 3       |Бухгольц|Место|
      |-|-------|-------|------------------|------------------|------------------|-------|-----|
      | |       |рейтинг|цвет    | Сумма   |цвет    | Сумма   |цвет    | Сумма   |       |     |
      |1|Игрок 1|       |--------|набранных|--------|набранных|--------|набранных|Бухглиц|Место|
      | |       |класс  |соперник| очков   |соперник| очков   |соперник| очков   |       |     |
      ------------------|------------------|------------------|------------------|-------|-----|
      | |       |рейтинг|цвет    | Сумма   |цвет    | Сумма   |цвет    | Сумма   |       |     |
      |2|Игрок 2|       |--------|набранных|--------|набранных|--------|набранных|Бухглиц|Место|
      | |       |класс  |соперник| очков   |соперник| очков   |соперник| очков   |       |     |
      ------------------|------------------|------------------|------------------|-------|-----|
      | |       |рейтинг|цвет    | Сумма   |цвет    | Сумма   |цвет    | Сумма   |       |     |
      |3|Игрок 3|       |--------|набранных|--------|набранных|--------|набранных|Бухглиц|Место|
      | |       |класс  |соперник| очков   |соперник| очков   |соперник| очков   |       |     |
      ------------------|------------------|------------------|------------------|-------|-----|
      | |       |рейтинг|цвет    | Сумма   |цвет    | Сумма   |цвет    | Сумма   |       |     |
      |4|Игрок 4|       |--------|набранных|--------|набранных|--------|набранных|Бухглиц|Место|
      | |       |класс  |соперник| очков   |соперник| очков   |соперник| очков   |       |     |
      ------------------|------------------|------------------|------------------|-------|-----|
      | |       |рейтинг|цвет    | Сумма   |цвет    | Сумма   |цвет    | Сумма   |       |     |
      |5|Игрок 5|       |--------|набранных|--------|набранных|--------|набранных|Бухглиц|Место|
      | |       |класс  |соперник| очков   |соперник| очков   |соперник| очков   |       |     |
      |-|-------|-------|------------------|------------------|------------------|-------|-----|

      массив:
      t[1..n][id_gamer] - id игрока
      t[1..n][login_gamer] - логин игрока
      t[1..n][rating] - рейтинг
      t[1..n][class] - класс
      t[1..n][rounds][1..r][color] - цвет
      t[1..n][rounds][1..r][opponent] - номер оппонента
      t[1..n][rounds][1..r][balls] - сумма набранных очков
      t[1..n][rounds][1..r][id_game] - id -партии
      t[1..n][rounds][1..r][id_opponent] - id -оппонента
      t[1..n][buchholz] - бухгольц
      t[1..n][place1] - место 1
      t[1..n][place2] - место 2
*/
        protected static function make_table_registration($id_tournament){
            $result_ =array();
            $cursor_ =false;
            try{
#читаю информацию о количестве раундов
                $s = 'select cRounds_ from TTournaments_ where id_ ='.$id_tournament;
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception();
                $rounds_ =$row_['cRounds_'];
                mysql_free_result($cursor_); $cursor_ =false;
#читаю список зарегистрировавшихся
                $s = 'select B.id_, B.login_,B.classA_,B.ratingA_,B.classB_,B.ratingB_,B.classC_,B.ratingC_'.
                     '  from TMembersTournament_ A,TGamers_ B'.
                     '  where (A.id_tournament_ ='.$id_tournament.') and (A.id_gamer_ = B.id_)'.
                     '  order by A.num_';
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception();
                $i=0;
                while ($row_ =mysql_fetch_array($cursor_)){
                    $result_[++$i]['id_gamer'] =$row_['id_'];
                    $result_[$i]['login_gamer'] =convert_cyr_string($row_['login_'],'d','w');
                    $result_[$i]['rating'] ='A'.$row_['ratingA_'].'/B'.$row_['ratingB_'].'/C'.$row_['ratingC_'];
                    $result_[$i]['class'] ='A'.$row_['classA_'].'/B'.$row_['classB_'].'/C'.$row_['classC_'];
                    $result_[$i]['buchholz'] ='';
                    $result_[$i]['place1'] ='';
                    $result_[$i]['place2'] ='';
                    for($j=1; $j <=$rounds_; $j++){
                       $result_[$i]['rounds'][$j]['color'] ='';
                       $result_[$i]['rounds'][$j]['opponent'] ='';
                       $result_[$i]['rounds'][$j]['balls'] ='';
                       $result_[$i]['rounds'][$j]['id_game'] ='';
                       $result_[$i]['rounds'][$j]['id_opponent'] ='';
                    }#for
                }#while
                mysql_free_result($cursor_); $cursor_ =false;
                return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При чтении информации о турнире произошла ошибка.');
            }
        }#make_table_registration

        protected static function add_gamer($id_){
            $cursor_ =false;
            try{
#Вычисляю значение num_ (номер участника турнира)
                $s ='select max(num_) as max_num_ from TMembersTournament_ where id_tournament_='.$id_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_ || is_null($row_['max_num_'])) $num_ =1; else $num_ =$row_['max_num_'] +1;
                mysql_free_result($cursor_); $cursor_ =false;
#Добавляю нового участника турнира
                $s ='insert into TMembersTournament_(num_,id_tournament_,id_gamer_)'.
                    ' values('.$num_.','.$id_.','.$_SESSION[SESSION_ID_].')';
                if (!mysql_query($s,const_::$connect_)) throw new Exception();
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При регистрации в турнире произошла ошибка.');
            }
        }#add_gamer

        protected static function del_gamer($id_){
            try{
                $s ='delete from TMembersTournament_ where'.
                    ' (id_tournament_ ='.$id_.') and'.
                    ' (id_gamer_ ='.$_SESSION[SESSION_ID_].')';
                if (!mysql_query($s,const_::$connect_)) throw new Exception();
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При отмены регистрации произошла ошибка.');
            }
        }#del_gamer

#функция возвращает кол-во зарегистрировавшихся в турнире
        protected static function get_count_logins_registration($id_){
            $cursor_ =false;
            try{
                $s ='select count(*) as count_ from TMembersTournament_ where id_tournament_='.$id_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                mysql_free_result($cursor_);
                return $row_['count_'];
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При чтении информации о турнире произошла ошибка.');
            }
        }#get_count_logins_registration

       protected static function set_count_gamers_($id_){
            $s ='update TTournaments_ set'.
                ' cGamers_ = (select count(*) from TMembersTournament_ where id_tournament_='.$id_.')'.
                ' where id_='.$id_;
            if (!mysql_query($s,const_::$connect_)) throw new Exception('При записи кол-во участников турнира произошла ошибка.');
       }#set_count_gamers_

#функция делает ррасылку сообщения всем участникам турнира
        protected static function send_message_($id_,$message_){
          $s ='insert into TInfo_(info_,id_gamer_)'.
              '  select \''.mysql_escape_string(convert_cyr_string($message_,'w','d')).'\',id_gamer_'.
              '   from TMembersTournament_ where id_tournament_ ='.$id_;
          if (!mysql_query($s,const_::$connect_)) throw new Exception('При отправки сообщения участникам турнира произошла ошибка');
        }#send_message_

        public static function del_tournament($id_){
          try{
            $s ='delete from TMembersTournament_ where id_tournament_='.$id_;
            if (!mysql_query($s,const_::$connect_)) throw new Exception('При удалении турнира произошла ошибка.');
            $s ='delete from TTournaments_ where id_='.$id_;
            if (!mysql_query($s,const_::$connect_)) throw new Exception('При удалении турнира произошла ошибка.');
          }catch(Exception $e){
              throw new Exception($e->getMessage());
          }
        }#del_tournament

       public static function del_tournaments_no_gamers(){
          $cursor_ =false;
          try{
            $s ='select id_ from TTournaments_ A'.
                ' where (NOW() > A.begin_) and (A.end_ is null) and (A.system_=1) and'.
                '       ('.MIN_GAMERS_SWISS_TOURNAMENT.' > (select count(*) from TMembersTournament_ where id_tournament_=A.id_))';
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении информации о турнирах произошла ошибка.');
            while ($row_ =mysql_fetch_array($cursor_)){
                $s ='Турнир №'.$row_['id_'].' не состоялся, по причине недостаточного кол-ва желающих принять в нем участие.';
                CInfo_swiss_tournament_::send_message_($row_['id_'],$s);
                CInfo_swiss_tournament_::del_tournament($row_['id_']);
            }#while
          }catch(Exception $e){
              if ($cursor_) mysql_free_result($cursor_);
              throw new Exception($e->getMessage());
          }
       }#del_tournaments_no_gamers

#Функция перемешивает массив
        protected static function mix_table($m){
/* Псевдокод:
          m - массив размерностью n (1..n), индекс порядковый номер в турнире, значение id игрока
           for (i=n; i > 1; i--)
             k = случайное число от 1..i   //если диапазон 1..i участник может остаться на месте, на котором он был
                                           //при регистрации, если 1..i-1 участник на месте остаться не может
             b =m[i]; m[i] =m[k]; m[k] =b
           .
*/
          $n =count($m);
          for($i=$n; $i >1; $i--){
            $k =rand(1,$i);
            $b =$m[$i]; $m[$i]=$m[$k]; $m[$k]=$b;
          }
          return $m;
        }#mix_table

#Функция подготавливает информацию для начала первого тура
        protected static function first_round_($id_){
/*Псевдокод
            n - размерность массива
            i =1
            while(i < n)
              Игрок i будет играть с игроком i+1, i играет белым цветом
              i +=2
            .
            if i=n
              игрок n получает +1 балл
            .
*/
          $cursor_ =false;
          try{
#Подготавливаю турнирную таблицу
            $m=CInfo_swiss_tournament_::make_table_registration($id_);
            $m=CInfo_swiss_tournament_::mix_table($m);
            $n =count($m);
#Считываю информацию о первоначальном цвете
            $s ='select firstColorWhite_ from TTournaments_ where id_='.$id_;
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
            $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
            mysql_free_result($cursor_); $cursor_ =false;
            $first_color_white_ =($row_['firstColorWhite_'] =='Y');
#Заполняю информацию о первом туре
            $i =1;
            while($i < $n){
                $m[$i]['rounds'][1]['opponent']=$i+1;
                $m[$i]['rounds'][1]['color'] =($first_color_white_ ? 'Б' : 'Ч');
                $m[$i]['rounds'][1]['id_opponent'] =$m[$i+1]['id_gamer'];
                $m[$i+1]['rounds'][1]['opponent']=$i;
                $m[$i+1]['rounds'][1]['color'] =($first_color_white_ ? 'Ч' : 'Б');
                $m[$i+1]['rounds'][1]['id_opponent'] =$m[$i]['id_gamer'];
                $i +=2;
            }#while
            if ($i==$n){
                $m[$i]['rounds'][1]['balls'] =1;
            }
            return $m;
          }catch(Exception $e){
              if ($cursor_) mysql_free_result($cursor_);
              throw new Exception('При начале первого тура произошла ошибка');
          }
        }#first_round_

        protected static function begin_games_of_round(&$m,$id_,$round_){
          $cursor_ =false;
          try{
#считываю регламент и класс турнира
            $s ='select reglament_,class_ from TTournaments_ where id_='.$id_;
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
            $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
            mysql_free_result($cursor_); $cursor_ =false;
            $class_ =$row_['class_'];
            $reglament_ =$row_['reglament_'];
#начинаю партии
            for($i=1; $i <=count($m); $i++){
                if (($m[$i]['rounds'][$round_]['opponent'] !='') && ($m[$i]['rounds'][$round_]['id_game'] =='')){
                   if ($m[$i]['rounds'][$round_]['color'] == 'Б'){
                     $id_gamer_w =$m[$i]['id_gamer'];
                     $id_gamer_b =$m[$i]['rounds'][$round_]['id_opponent'];
                   }else{
                     $id_gamer_b =$m[$i]['id_gamer'];
                     $id_gamer_w =$m[$i]['rounds'][$round_]['id_opponent'];
                   }
                   $s ='insert into TGames_(idWGamer_,idBGamer_,reglament_,clockWhite_,clockBlack_,beginMove_,isMoveWhite_,gameIsRating_,class_,no_otpusk)'.
                        ' values('.$id_gamer_w.','.$id_gamer_b.','.$reglament_.','.GetBeginTime($reglament_).','.GetBeginTime($reglament_).','.
                                 time().',\'Y\',\'Y\','.(trim($class_) != '' ? '\''.$class_.'\'' : 'null').',\'Y\')';
                   if (!mysql_query($s,const_::$connect_)) throw new Exception();
                   $id_game_ =mysql_insert_id(const_::$connect_);
                   $m[$i]['rounds'][$round_]['id_game'] =$id_game_;
                   $m[$m[$i]['rounds'][$round_]['opponent']]['rounds'][$round_]['id_game'] =$id_game_;
                   $s ='insert into TGamesTournament_(id_tournament_,id_game)'.
                       ' values('.$id_.','.$id_game_.')';
                   if (!mysql_query($s,const_::$connect_)) throw new Exception();
                }
            }
            return $m;
          }catch(Exception $e){
              if ($cursor_) mysql_free_result($cursor_);
              throw new Exception('При начале партий раунда произошла ошибка');
          }
        }#begin_games_of_round

        protected static function save_info_round($m,$id_,$round_){
#удаляю информацию о раунде
          $s ='delete from TRoundsSwiss_ where (id_tournament_='.$id_.') and (round_='.$round_.')';
          if (!mysql_query($s,const_::$connect_)) throw new Exception('При сохранении информации о туре произошла ошибка');
#выполняю команду insert
          for($i=1; $i <=count($m); $i++){
            if ($m[$i]['rounds'][$round_]['color'] =='')
              $colorWhite_ ='null';
             else if ($m[$i]['rounds'][$round_]['color'] =='Б')
              $colorWhite_ ='\'Y\'';
             else
              $colorWhite_ ='\'N\'';
            $s ='insert into TRoundsSwiss_(id_tournament_,round_,id_gamer_,position_,balls_,id_game_,id_opponent_,colorWhite_)'.
                '  values('.$id_.','.$round_.','.$m[$i]['id_gamer'].','.$i.','.
                          ($m[$i]['rounds'][$round_]['balls'] !='' ? $m[$i]['rounds'][$round_]['balls'] : 'null').','.
                          ($m[$i]['rounds'][$round_]['id_game'] !='' ? $m[$i]['rounds'][$round_]['id_game'] : 'null').','.
                          ($m[$i]['rounds'][$round_]['id_opponent'] !='' ? $m[$i]['rounds'][$round_]['id_opponent'] : 'null').','.
                          $colorWhite_.')';
            if (!mysql_query($s,const_::$connect_)) throw new Exception('При сохранении информации о туре произошла ошибка');
          }#for
        }#save_info_round

        protected static function get_curr_round($id_){
          $s ='select MAX(round_) as max_ from TRoundsSwiss_ where id_tournament_='.$id_;
          $cursor_=mysql_query($s,const_::$connect_);
          if (!$cursor_) throw new Exception('При чтении информации о турнире произошла ошибка.');
          $row_ =mysql_fetch_array($cursor_);
          mysql_free_result($cursor_); $cursor_ =false;
          if (!$row_ || is_null($row_['max_'])) return 1; else return $row_['max_'];
        }#get_curr_round

        protected static function make_table($id_){
          $result_ =array();
          $cursor_ =false;
          try{
#читаю информацию о количестве раундов
              $s ='select cRounds_ from TTournaments_ where id_ ='.$id_;
              $cursor_=mysql_query($s,const_::$connect_);
              if (!$cursor_) throw new Exception();
              $row_ =mysql_fetch_array($cursor_);
              if (!$row_) throw new Exception();
              $rounds_ =$row_['cRounds_'];
              mysql_free_result($cursor_); $cursor_ =false;
#текущий раунд
              $curr_round =CInfo_swiss_tournament_::get_curr_round($id_);
#последняя сортировка турнирной таблицы
              $list_gamers_ =array();
              $s ='select id_gamer_,position_'.
                  '  from TRoundsSwiss_'.
                  '  where (id_tournament_ ='.$id_.') and'.
                  '        (round_ ='.$curr_round.')'.
                  '  order by position_';
              $cursor_=mysql_query($s,const_::$connect_);
              if (!$cursor_) throw new Exception();
              while ($row_ =mysql_fetch_array($cursor_))
                $list_gamers_[$row_['id_gamer_']] =$row_['position_'];
              mysql_free_result($cursor_); $cursor_ =false;
#читаю информацию о раундах
              $s ='select A.round_,A.id_gamer_,A.balls_,A.id_game_,A.id_opponent_,A.colorWhite_,'.
                  '       B.login_,B.ratingA_,B.ratingB_,B.ratingC_,B.classA_,B.classB_,B.classC_'.
                  '  from TRoundsSwiss_ A, TGamers_ B'.
                  '  where (A.id_tournament_ = '.$id_.') and (A.id_gamer_ =B.id_)'.
                  '  order by A.id_tournament_, A.id_gamer_';
              $cursor_=mysql_query($s,const_::$connect_);
              if (!$cursor_) throw new Exception();
              $prev_id_gamer_ =null;
              while ($row_ =mysql_fetch_array($cursor_)){
                if (is_null($prev_id_gamer_) || ($prev_id_gamer_ !=$row_['id_gamer_'])){
                   $i =$list_gamers_[$row_['id_gamer_']]; #позиция в турнирной таблицы
                   $prev_id_gamer_ =$row_['id_gamer_'];
                   $result_[$i]['id_gamer'] =$row_['id_gamer_'];
                   $result_[$i]['login_gamer'] =convert_cyr_string($row_['login_'],'d','w');
                   $result_[$i]['rating'] ='A'.$row_['ratingA_'].'/B'.$row_['ratingB_'].'/C'.$row_['ratingC_'];
                   $result_[$i]['class'] ='A'.$row_['classA_'].'/B'.$row_['classB_'].'/C'.$row_['classC_'];
                   $result_[$i]['buchholz'] ='';
                   $result_[$i]['place1'] ='';
                   $result_[$i]['place2'] ='';
                    for($j=1; $j <=$rounds_; $j++){
                       $result_[$i]['rounds'][$j]['color'] ='';
                       $result_[$i]['rounds'][$j]['opponent'] ='';
                       $result_[$i]['rounds'][$j]['balls'] ='';
                       $result_[$i]['rounds'][$j]['id_game'] ='';
                       $result_[$i]['rounds'][$j]['id_opponent'] ='';
                    }#for
                }
                if (!is_null($row_['colorWhite_']))
                  $result_[$i]['rounds'][$row_['round_']]['color'] =($row_['colorWhite_'] == 'Y' ? 'Б' : 'Ч');
                if (!is_null($row_['balls_']))
                  $result_[$i]['rounds'][$row_['round_']]['balls'] =$row_['balls_'];
                if (!is_null($row_['id_game_']))
                  $result_[$i]['rounds'][$row_['round_']]['id_game'] =$row_['id_game_'];
                if (!is_null($row_['id_opponent_']))
                  $result_[$i]['rounds'][$row_['round_']]['id_opponent'] =$row_['id_opponent_'];
              }#while
              mysql_free_result($cursor_); $cursor_ =false;
              return $result_;
          }catch(Exception $e){
              if ($cursor_) mysql_free_result($cursor_);
              throw new Exception('При чтении информации о турнире произошла ошибка');
          }
        }#make_table

        protected static function set_num_opponents(&$m){
#читаю последнюю сортировку турнирной таблицы
          $list_gamers_ =array();
          for($i =1; $i <=count($m); $i++)
            $list_gamers_[$m[$i]['id_gamer']] =$i;
          for($i =1; $i <=count($m); $i++)
            for($j=1; $j <=count($m[$i]['rounds']); $j++)
              if ($m[$i]['rounds'][$j]['id_opponent'] != '')
                $m[$i]['rounds'][$j]['opponent'] =$list_gamers_[$m[$i]['rounds'][$j]['id_opponent']];
        }#set_num_opponents

#записывает баллы в туры, для завершившихся партий
        protected static function set_result_end_games($id_){
          $cursor_ =false;
          try{
            $s ='select A.result_,A.idWGamer_,A.idBGamer_,B.id_tournament_,B.round_,B.id_gamer_,C.balls_'.
                '  from TGames_ A, TRoundsSwiss_ B'.
                '    left join TRoundsSwiss_ C on (B.id_tournament_ = C.id_tournament_) and'.
                '                                 (B.id_gamer_ = C.id_gamer_) and (C.round_ =B.round_-1)'.
                '  where (B.id_tournament_ ='.$id_.') and (A.id_ = B.id_game_) and (not A.result_ is null) and'.
                '        (B.balls_ is null)';
            $cursor_=mysql_query($s,const_::$connect_);
            if (!$cursor_) throw new Exception();
            while ($row_ =mysql_fetch_array($cursor_)){
              if (is_null($row_['balls_'])) $balls_=0; else $balls_=$row_['balls_'];
              switch ($row_['result_']){
                case 0: if ($row_['idBGamer_'] == $row_['id_gamer_']) $balls_ +=1;
                        break;
                case 1: if ($row_['idWGamer_'] == $row_['id_gamer_']) $balls_ +=1;
                        break;
                case 2: $balls_ +=0.5;
                        break;
              }#switch
              $s ='update TRoundsSwiss_ set'.
                  '  balls_='.$balls_.
                  ' where (id_tournament_ ='.$id_.') and (id_gamer_='.$row_['id_gamer_'].') and (round_='.$row_['round_'].')';
              if (!mysql_query($s,const_::$connect_)) throw new Exception();
            }#while
            mysql_free_result($cursor_); $cursor_ =false;
          }catch(Exception $e){
              if ($cursor_) mysql_free_result($cursor_);
              throw new Exception('При чтении информации о турнире произошла ошибка');
          }
        }#set_result_end_games

#функция рассчитывает коэффициент бухгольца
        protected static function calc_buchholz(&$m){
            $c_rounds =count($m[1]['rounds']);
            for($i=1; $i <=count($m); $i++){
                $b =0;
                for($j=1; $j <=$c_rounds; $j++){
                    if ($m[$i]['rounds'][$j]['opponent'] != ''){
                      $a =0; $n=$m[$i]['rounds'][$j]['opponent'];
                      for($k=$c_rounds; $k >=1; $k--){
                          if ($m[$n]['rounds'][$k]['balls'] !=''){
                              $a =$m[$n]['rounds'][$k]['balls'];
                              break;
                          }
                      }#for
                      $b +=$a;
                    }
                }#for
                $m[$i]['buchholz'] =$b;
            }#for
        }#calc_buchholz

#Функция проверяет, что игроки не играли: true - не играли, false - играли
        protected static function no_meet($m,$index_gamer_1,$index_gamer_2){
            $result_ =true;
            $id_gamer_2 =$m[$index_gamer_2]['id_gamer'];
            for($i =1; $i <=count($m[$index_gamer_1]['rounds']); $i++)
              if (($m[$index_gamer_1]['rounds'][$i]['id_opponent'] !='') &&
                  ($m[$index_gamer_1]['rounds'][$i]['id_opponent'] ==$id_gamer_2)){
                $result_ =false;
                break;
              }
            return $result_;
        }#no_meet

#Функция определяеет желаемы цвет для игрока
#   +1 - желаемый цвет белый (последняя партия уже сыгранна белым цветом)
#   +2 - очень желаемый цвет белый (последние две партии сыгранны белым цветом)
#   -1 - желаемый цвет черный
#   -2 - очень желаемый цвет черный
#   0 - игрок в турнире ещё не играл (в первом туре не было соперника), цвет любой
        protected static function wish_color($m,$index_gamer){
          $result_=0;
          for($i =1; $i <= count($m[$index_gamer]['rounds']); $i++){
            $s =$m[$index_gamer]['rounds'][$i]['color'];
            if ($s !='')
              if ($s =='Б')
                if (($result_ == -1) || ($result_ == -2)) $result_ =-2; else $result_ =-1;
              else
                if (($result_ == 1) || ($result_ == 2)) $result_ =2; else $result_ =1;
          }#for
          return $result_;
        }#wish_color

/*Функция коэффициент_соответствия партии между игроками $index_gamer_1,$index_gamer_2
     возвращает: в параметре ['разница баллов'] - разницу в набранных очках
                 в параметре ['состояние'] - 0 - играть не могут
                                             1 - один из игроков будет играть третий раз одним и тем же цветом
                                             2 - один из игроков будет играть второй раз одним и тем же цветом
                                             3 - по цвету подходят*/
        protected static function k_equivalent($m,$round_,$index_gamer_1,$index_gamer_2){
            $result_['разница баллов'] =NULL;
            $result_['состояние']      =NULL;
#проверяю встречались игроки ранее
            if (!CInfo_swiss_tournament_::no_meet($m,$index_gamer_1,$index_gamer_2)){
              $result_['состояние'] =0;
              return $result_;
            }
#определяю разницу баллов
            $balls_gamer_1 =$m[$index_gamer_1]['rounds'][$round_-1]['balls'];
            $balls_gamer_2 =$m[$index_gamer_2]['rounds'][$round_-1]['balls'];
            $result_['разница баллов'] =abs($balls_gamer_1 - $balls_gamer_2);
#определяю желаемый цвет фигур
            $wish_color_gamer_1 =CInfo_swiss_tournament_::wish_color($m,$index_gamer_1);
            $wish_color_gamer_2 =CInfo_swiss_tournament_::wish_color($m,$index_gamer_2);
#определяю коэффициент соответствия
#таблица соответствия
#(не последний раунд):              (последний раунд):
#    +1|+2|-1|-2|0|                    +1|+2|-1|-2|0|
#   --------------                     --------------
# +1| 2| 2| 3| 3|3|                 +1| 2| 2| 3| 3|3|
#   --------------                     --------------
# +2| 2| 0| 3| 3|3|                 +2| 2| 1| 3| 3|3|
#   --------------                     --------------
# -1| 3| 3| 2| 2|3|                 -1| 3| 3| 2| 2|3|
#   --------------                     --------------
# -2| 3| 3| 2| 0|3|                 -2| 3| 3| 2| 1|3|
#   --------------                     --------------
#  0| 3| 3| 3| 3|3|                  0| 3| 3| 3| 3|3|
#   --------------                     --------------
#   +1 - желаемый цвет белый (последняя партия уже сыгранна белым цветом)
#   +2 - очень желаемый цвет белый (последние две партии сыгранны белым цветом)
#   -1 - желаемый цвет черный
#   -2 - очень желаемый цвет черный
#   0 - игрок в турнире ещё не играл (в первом туре не было соперника), цвет любой
            $t_k =array();
            if($round_ < count($m[1]['rounds'])){
              $t_k[1]  =array(1=>2,2=>2,-1=>3,-2=>3,0=>3);
              $t_k[2]  =array(1=>2,2=>0,-1=>3,-2=>3,0=>3);
              $t_k[-1] =array(1=>3,2=>3,-1=>2,-2=>2,0=>3);
              $t_k[-2] =array(1=>3,2=>3,-1=>2,-2=>0,0=>3);
              $t_k[0]  =array(1=>3,2=>3,-1=>3,-2=>3,0=>3);
            }else{
              $t_k[1]  =array(1=>2,2=>2,-1=>3,-2=>3,0=>3);
              $t_k[2]  =array(1=>2,2=>1,-1=>3,-2=>3,0=>3);
              $t_k[-1] =array(1=>3,2=>3,-1=>2,-2=>2,0=>3);
              $t_k[-2] =array(1=>3,2=>3,-1=>2,-2=>1,0=>3);
              $t_k[0]  =array(1=>3,2=>3,-1=>3,-2=>3,0=>3);
            }
            $result_['состояние']=$t_k[$wish_color_gamer_1][$wish_color_gamer_2];
            return $result_;
        }#k_equivalent

#Функция сравнивает набранные очки и бухгольц
#0 - очки и бухгольц равны
#1 - результат первого игрока > указанного
#2 - результат первого игрока < указанного
        protected static function comparison_balls($m,$index_gamer_1,$round_,$balls_,$buchholz_){
            if ($m[$index_gamer_1]['rounds'][$round_]['balls'] > $balls_)
              return 1;
             else if ($m[$index_gamer_1]['rounds'][$round_]['balls'] < $balls_)
              return 2;
             else if ($m[$index_gamer_1]['buchholz'] > $buchholz_)
              return 1;
             else if ($m[$index_gamer_1]['buchholz'] < $buchholz_)
              return 2;
             else
              return 0;
        }#comparison_balls

        protected static function quick_sort_table_(&$m,$iLo,$iHi,$round_){
#Вспомогательная функция для sort_table
            $Lo =$iLo;
            $Hi =$iHi;
            $Mid_ =floor(($Lo+$Hi)/2);
            $balls_mid =$m[$Mid_]['rounds'][$round_]['balls'];
            $buchholz_mid =$m[$Mid_]['buchholz'];
            do{
               while (CInfo_swiss_tournament_::comparison_balls($m,$Lo,$round_,$balls_mid,$buchholz_mid)==1) $Lo++;
               while (CInfo_swiss_tournament_::comparison_balls($m,$Hi,$round_,$balls_mid,$buchholz_mid)==2) $Hi--;
               if ($Lo <=$Hi){
                $t =$m[$Lo];
                $m[$Lo] =$m[$Hi];
                $m[$Hi] =$t;
                $Lo++;
                $Hi--;
               }
            }while($Lo <=$Hi);
            if ($Hi > $iLo) CInfo_swiss_tournament_::quick_sort_table_($m,$iLo,$Hi,$round_);
            if ($Lo < $iHi) CInfo_swiss_tournament_::quick_sort_table_($m,$Lo,$iHi,$round_);
        }#quick_sort_table_

        protected static function sort_table_(&$m){
          for($i=count($m[1]['rounds']); $i >=1; $i--)
            if ($m[1]['rounds'][$i]['balls'] != ''){
              $round_ =$i;
              break;
            }
#Используется быстрая сортировка, алгоритм взять и демонстрационного кода Delphi 7
          CInfo_swiss_tournament_::quick_sort_table_($m,1,count($m),$round_);
        }#sort_table_

/*
 массив вариантов имеет вид
         1|2|3|4|..|n|         1|2|3|4|..|n| , где кол-во игроков в туре (четное число)
        --------------        --------------
       1|X|X|X|X| X|X|       1|X|X|X|X| X|X|
       2| |X|X|X| X|X|       2| |X|X|X| X|X|
       3| | |X|X| X|X|       3|-|-|X|X| X|X|
       4| | | |X| X|X|       4| | |||X| X|X|
      ..| | | | | X|X|      ..|-|-|+|-|-X|X|
       n| | | | |  |X|       n| | ||| | ||X|
                                   |    |
                                   |    |
                                 путь3 путь..
*/
        protected static function make_table_variants($m,$round_){
            $result_ =array();
            $n =count($m); if (($n % 2) != 0) $n--;
            for($i=1,$j=1; $i <=count($m); $i++)
              if ($m[$i]['rounds'][$round_]['balls'] == '')
                $result_[$j++]['position'] =$i;

            for($i=2; $i <=$n; $i++)
              for($j =1; $j <$i; $j++)
                $result_[$i][$j] =CInfo_swiss_tournament_::k_equivalent($m,$round_,
                                                                        $result_[$i]['position'],
                                                                        $result_[$j]['position']);
            return $result_;
        }#make_table_variants

        protected static function get_path($t,&$path_,&$count_paths_){
/* Возвращает первый путь в котором есть варианты в параметр $path_ и в параметре $count_paths_ общее кол-во путей,
   в параметре $t принимает массив вариантов, предполагаю, что турнирная таблица отсортирована в порядке занимаемых мест*/
            $path_ =0; $count_paths_ =0; $n =count($t);
            for($i=1; $i <=$n; $i++){
                $a =0;
                for($j=1; $j <$i; $j++)
                  if ($t[$i][$j]['состояние'] >  0)
                    $a++;
                for($j=$i+1; $j <=$n; $j++)
                  if($t[$j][$i]['состояние'] >  0)
                    $a++;
                if ($a >0){
                  $count_paths_++;
                  if ($path_ ==0) $path_ =$i;
                }
            }
        }#get_path

#Функция сравнивает варианты
#0 - равны
#1 - первый предпочтительнее 2
#2 - второй предпочтительнее 1
        protected static function comparison_variant($v1,$v2){
            if ($v1['разница баллов'] == $v2['разница баллов']){
                if ($v1['состояние'] == $v2['состояние']) return 0;
                 else if ($v1['состояние'] > $v2['состояние']) return 1;
                 else return 2;
            }else if ($v1['разница баллов'] < $v2['разница баллов']) return 1;
             else return 2;
        }#comparison_variant

        protected static function quick_array_variants(&$v,$iLo,$iHi){
#Вспомогательная функция для sort_table
            $Lo =$iLo;
            $Hi =$iHi;
            $Mid_ =floor(($Lo+$Hi)/2);
            do{
               while (CInfo_swiss_tournament_::comparison_variant($v[$Lo][1],$v[$Mid_][1])==1) $Lo++;
               while (CInfo_swiss_tournament_::comparison_variant($v[$Hi][1],$v[$Mid_][1])==2) $Hi--;
               if ($Lo <=$Hi){
                $t =$v[$Lo];
                $v[$Lo] =$v[$Hi];
                $v[$Hi] =$t;
                $Lo++;
                $Hi--;
               }
            }while($Lo <=$Hi);
            if ($Hi > $iLo) CInfo_swiss_tournament_::quick_array_variants($v,$iLo,$Hi);
            if ($Lo < $iHi) CInfo_swiss_tournament_::quick_array_variants($v,$Lo,$iHi);
        }#quick_sort_table_

        protected static function make_array_variants_of_path($t,$path_){
#Возвращает массив вариантов, для пути $path_, отсортированный в порядке приоритета использования
#1 - наиболее подходящий вариант для $path_, последний - наименее подходящий
            $result_ =array(); $k=1; $n =count($t);
            for($i=1; $i <$path_; $i++)
              if ($t[$path_][$i]['состояние'] > 0){
                $result_[$k][1] =$t[$path_][$i];
                $result_[$k++][2] =$i;
              }
            for($i=$path_+1; $i <=$n; $i++)
              if ($t[$i][$path_]['состояние'] > 0){
                $result_[$k][1] =$t[$i][$path_];
                $result_[$k++][2] =$i;
              }
            CInfo_swiss_tournament_::quick_array_variants($result_,1,count($result_));
            return $result_;
        }#make_array_variants_of_path

       protected static function mark_path(&$t,$path_,$marker_){
            $n =count($t);
            for($i=1; $i <$path_; $i++)
               $t[$path_][$i]['состояние'] +=$marker_;
            for($i=$path_+1; $i <=$n; $i++)
               $t[$i][$path_]['состояние'] +=$marker_;
       }#mark_path

       protected static function get_game_($g_,$t,&$m,$round_){
           $n =count($t);
           $path_ =0; $count_paths_ =0;
           CInfo_swiss_tournament_::get_path($t,$path_,$count_paths_);
           if ($count_paths_ <> ($n-($g_-1)*2))
             return false;
           $v =CInfo_swiss_tournament_::make_array_variants_of_path($t,$path_); $p_v=1;
           while($p_v <= count($v)){
             CInfo_swiss_tournament_::mark_path($t,$path_,-100);
             CInfo_swiss_tournament_::mark_path($t,$v[$p_v][2],-100);
             $i_1 =$t[$path_]['position'];
             $i_2 =$t[$v[$p_v][2]]['position'];
             $m[$i_1]['rounds'][$round_]['opponent']=$i_2;
             $m[$i_2]['rounds'][$round_]['opponent']=$i_1;
             $m[$i_1]['rounds'][$round_]['id_opponent']=$m[$i_2]['id_gamer'];
             $m[$i_2]['rounds'][$round_]['id_opponent']=$m[$i_1]['id_gamer'];
             if (($g_ ==($n/2)) || CInfo_swiss_tournament_::get_game_($g_+1,$t,$m,$round_))
               return true;
#Снимаю метки
             $m[$i_1]['rounds'][$round_]['opponent']='';
             $m[$i_2]['rounds'][$round_]['opponent']='';
             $m[$i_1]['rounds'][$round_]['id_opponent']='';
             $m[$i_2]['rounds'][$round_]['id_opponent']='';
             CInfo_swiss_tournament_::mark_path($t,$path_,100);
             CInfo_swiss_tournament_::mark_path($t,$v[$p_v][2],100);

             $p_v++;
           }#while
       }#get_game_

        protected static function set_colors(&$m,$round_){
/*цвет которым будет играть i игрок определяется из таблицы
   +1 - желаемый цвет белый (последняя партия уже сыгранна белым цветом)
   +2 - очень желаемый цвет белый (последние две партии сыгранны белым цветом)
   -1 - желаемый цвет черный
   -2 - очень желаемый цвет черный
    0 - игрок в турнире ещё не играл (в первом туре не было соперника), цвет любой
                                 +1|+2|-1|-2|0|       желаемый цвет j-го игрока
                                --------------
                             +1| Б| Ч| Б| Б|Б|
                                --------------
                             +2| Б| Б| Б| Б|Б|
 желаемый цвет i-го игрока      --------------
                             -1| Ч| Ч| Ч| Б|Ч|
                                --------------
                             -2| Ч| Ч| Ч| Ч|Ч|
                                --------------
                              0| Ч| Ч| Б| Б|Б|
                                --------------
*/
             $t_color =array();
             $t_color[1]  =array(1=>'Б',2=>'Ч',-1=>'Б',-2=>'Б',0=>'Б');
             $t_color[2]  =array(1=>'Б',2=>'Б',-1=>'Б',-2=>'Б',0=>'Б');
             $t_color[-1] =array(1=>'Ч',2=>'Ч',-1=>'Ч',-2=>'Б',0=>'Ч');
             $t_color[-2] =array(1=>'Ч',2=>'Ч',-1=>'Ч',-2=>'Ч',0=>'Ч');
             $t_color[0]  =array(1=>'Ч',2=>'Ч',-1=>'Б',-2=>'Б',0=>'Б');

             for ($i=1; $i <=count($m); $i++)
                if (($m[$i]['rounds'][$round_]['opponent'] !='') && ($m[$i]['rounds'][$round_]['color'] ==='')){
                  $j =$m[$i]['rounds'][$round_]['opponent'];
                  $wish_color_i = CInfo_swiss_tournament_::wish_color($m,$i);
                  $wish_color_j = CInfo_swiss_tournament_::wish_color($m,$j);
                  $m[$i]['rounds'][$round_]['color'] =$t_color[$wish_color_i][$wish_color_j];
                  if ($m[$i]['rounds'][$round_]['color'] =='Б')
                    $m[$j]['rounds'][$round_]['color'] = 'Ч';
                   else
                     $m[$j]['rounds'][$round_]['color'] = 'Б';
                }
        }#set_colors

#Функция подготавливает информацию для начала тура
        protected static function next_round(&$m){
#определяю раунд
            for ($i =count($m[1]['rounds'])-1; $i>=1; $i--)
              if ($m[1]['rounds'][$i]['balls'] != ''){
                $round_ =$i+1;
                break;
              }
#определяю игрока, который получит +1 балл
            if ((count($m) %2) !=0){
              for ($i=count($m); $i >=1; $i--){
                $f=true;
                for($j=1; $j < $round_; $j++)
                  if ($m[$i]['rounds'][$j]['balls'] ==='')
                    break;
                   else if ($m[$i]['rounds'][$j]['id_opponent'] ==''){
                      $f =false;
                      break;
                   }
                  if ($f){
                     $m[$i]['rounds'][$round_]['balls'] =$m[$i]['rounds'][$round_-1]['balls'] +1;
                      break;
                   }
              }#for
            }
#жеребьевка
            $t =CInfo_swiss_tournament_::make_table_variants($m,$round_);
            if (!CInfo_swiss_tournament_::get_game_(1,$t,$m,$round_))
              throw new Exception('При жеребьвке след. тура произошла ошибка.');
#определяю цвета
            CInfo_swiss_tournament_::set_colors($m,$round_);
        }#next_round

        protected static function set_places(&$m){
            $last_round_ =count($m[1]['rounds']);
            $i=1;
            while($i <= count($m)){
                $j =$i;
                for($k=$i+1; $k <=count($m); $k++)
                  if (($m[$i]['rounds'][$last_round_]['balls'] == $m[$k]['rounds'][$last_round_]['balls']) &&
                      ($m[$i]['buchholz'] == $m[$k]['buchholz']))
                    $j =$k;
                   else break;
                $m[$i]['place1'] =$i;
                if ($j != $i){
                  $m[$i]['place2'] =$j;
                  for($k=$i+1; $k <=$j; $k++){
                    $m[$k]['place1'] =$i;
                    $m[$k]['place2'] =$j;
                  }
                }
                $i =$j+1;
            }#while
        }#set_places

        protected static function end_tournament_($id_,$m){
            for($i=1; $i <=count($m); $i++){
                $s='update TMembersTournament_ set'.
                   ' place1_ ='.$m[$i]['place1'].','.
                   ' place2_='.(($m[$i]['place2'] !='') ? $m[$i]['place2'] : 'null').
                   ' where (id_tournament_='.$id_.') and (id_gamer_='.$m[$i]['id_gamer'].')';
                if (!mysql_query($s,const_::$connect_)) throw new Exception('При закрытии турнира произошла ошибка');
            }#for
            $s ='update TTournaments_ set end_=NOW() where id_='.$id_;
            if (!mysql_query($s,const_::$connect_)) throw new Exception('При закрытии турнира произошла ошибка');
            CInfo_swiss_tournament_::FirstPlaceTournament($id_,$m);
            CInfo_swiss_tournament_::LastPlaceTournament($id_,$m);
        }#end_tournament_

# Изменяет класс игрока, если он занял первое место в турнире
        protected static function FirstPlaceTournament($id_,$m){
            $cursor_ =false;
            try{
                if ($m[1]['place2'] ==''){
#считываю класс турнира
                  $s ='select class_ from TTournaments_ where id_='.$id_;
                  $cursor_=mysql_query($s,const_::$connect_);
                  if (!$cursor_) throw new Exception();
                  $row_ =mysql_fetch_array($cursor_);
                  if (!$row_) throw new Exception();
                  $class_ =$row_['class_'];
                  mysql_free_result($cursor_); $cursor_ =false;

                  if ((strlen(trim($class_)) ==2) && ($class_{1} !=1) && ($class_{1} !=8)){
                    switch($class_{0}){
                        case 'A' : $A_B_C=1; break;
                        case 'B' : $A_B_C=2; break;
                        case 'C' : $A_B_C=3; break;
                        default : throw new Exception();
                    }#switch
                    $class_user = CUsers_::ReadClass_($m[1]['id_gamer'],$A_B_C);
                    if (($class_{1} -1) < $class_user)
                       CUsers_::SetClass_($m[1]['id_gamer'],$A_B_C,$class_{1}-1);
                  }
                }
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При изменении класса игрока произошла ошибка.');
            }
        } #FirstPlaceTournament

# Изменяет класс игрока, если он занял последнее место в турнире
        public static function LastPlaceTournament($id_,$m){
            $cursor_ =false;
            try{
                if ($m[count($m)]['place2'] ==''){
#считываю класс турнира
                  $s ='select class_ from TTournaments_ where id_='.$id_;
                  $cursor_=mysql_query($s,const_::$connect_);
                  if (!$cursor_) throw new Exception();
                  $row_ =mysql_fetch_array($cursor_);
                  if (!$row_) throw new Exception();
                  $class_ =$row_['class_'];
                  mysql_free_result($cursor_); $cursor_ =false;

                  if ((strlen(trim($class_)) ==2) && ($class_{1} < 7)){
                    switch($class_{0}){
                        case 'A' : $A_B_C=1; break;
                        case 'B' : $A_B_C=2; break;
                        case 'C' : $A_B_C=3; break;
                        default : throw new Exception();
                    }#switch
                    $class_user = CUsers_::ReadClass_($m[count($m)]['id_gamer'],$A_B_C);
                    if (($class_{1} +1) > $class_user)
                       CUsers_::SetClass_($m[count($m)]['id_gamer'],$A_B_C,$class_{1}+1);
                  }
                }
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('При изменении класса игрока произошла ошибка.');
            }
        }#LastPlaceTournament

        protected static function out_table($table_){
#читаю информацию о кол-во туров
            $rounds_ =count($table_[1]['rounds']);
#определяю будет ли выводится тройка лидеров выделенным цветом
            for($i =$rounds_; $i >=1; $i--){
              $last_round =$i;
              for($j=1; $j <=count($table_); $j++)
                if($table_[$j]['rounds'][$i]['balls'] === ''){
                  $last_round =0;
                  break;
                }
              if ($last_round !=0) break;
            }
            $flag_lider =(($last_round >=3) &&
                          ($table_[3]['rounds'][$last_round]['balls'] !=$table_[4]['rounds'][$last_round]['balls']));

#строю таблицу
            $result_  ='<TABLE style ="border: none" cellspacing="3" cellpadding="0">'.
                       '   <COL span="'.($rounds_*2+1).'">'.
                       '   <TR>'.
                       '        <TD colspan="3" class ="table_head_1" style="white-space:nowrap">&nbsp;</TD>';
                                for($i=1; $i<=$rounds_; $i++)
                                  $result_ .='<TD colspan="2" class ="table_head_1" style="white-space:nowrap">Тур '.$i.'</TD>';
            $result_ .='        <TD class ="table_head_1" style="white-space:nowrap">Бухгольц</TD>'.
                       '        <TD class ="table_head_1" style="white-space:nowrap">Место</TD>'.
                       '  </TR>';
                       for($i=1; $i <=count($table_); $i++){
                          $result_ .='<TR>'.
                                     '   <TD rowspan="2" class="table_body_1">'.$i.'</TD>'.
                                     '   <TD rowspan="2" class="table_body_1">'.
                                     '      <A href="MainPage.php?link_=about_gamer&login_='.urlencode($table_[$i]['login_gamer']).'">'.
                                                htmlspecialchars($table_[$i]['login_gamer']).
                                     '      </A>'.
                                     '    </TD>'.
                                     '    <TD class="table_body_1">'.htmlspecialchars($table_[$i]['rating']).'</TD>';
                                          for($j=1; $j <=$rounds_; $j++){
                                             $s =$table_[$i]['rounds'][$j]['color'];
                                             $result_ .='<TD  class="table_body_1">'.($s !='' ? $s : '&nbsp;').'</TD>';
                                             $style_ =(($flag_lider && ($i < 4)) ? ' style="font-weight:bold"' : '');
                                             $s =$table_[$i]['rounds'][$j]['balls'];
                                             $result_ .='<TD rowspan="2" class="table_body_1" '.$style_.'>';
                                             if ($table_[$i]['rounds'][$j]['id_game'] !='')
                                               $result_ .='<A href="MainPage.php?link_=game&id='.$table_[$i]['rounds'][$j]['id_game'].'">'.
                                                              ($s==='' ? '&nbsp;' : $s).
                                                          '</A>';
                                              else
                                               $result_ .=($s==='' ? '&nbsp;' : $s);
                                             $result_ .='</TD>';
                                          }
                          $style_ ='';
                          if (($table_[$i]['place1'] !== '') && ($table_[$i]['place1'] < 4))
                            $style_ =' style="color:white"';
                          $s =$table_[$i]['buchholz'];
                          $result_ .='    <TD rowspan="2" class="table_body_1"'.$style_.'>'.($s !=='' ? $s : '&nbsp;').'</TD>';
                          $s =$table_[$i]['place1'].($table_[$i]['place2'] !='' ? '-'.$table_[$i]['place2'] : '');
                          $result_ .='    <TD rowspan="2" class="table_body_1"'.$style_.'>'.($s !='' ? $s : '&nbsp;').'</TD>';
                          $result_ .='</TR>'.
                                     '<TR>'.
                                     '    <TD class="table_body_1">'.htmlspecialchars($table_[$i]['class']).'</TD>';
                                          for($j=1; $j <=$rounds_; $j++){
                                             $s =$table_[$i]['rounds'][$j]['opponent'];
                                             $result_ .='<TD class="table_body_1">'.($s !='' ? $s : '&nbsp;').'</TD>';
                                          }
                          $result_ .='</TR>';
                       }
            $result_ .='</TABLE>';
            return $result_;
        }#out_table

        protected static function BodyRegistration($id_tournament){
            $info_ =CInfo_swiss_tournament_::info_tournament($id_tournament);
            $status_reg =CInfo_swiss_tournament_::status_registration($id_tournament);
            $t_ =CInfo_swiss_tournament_::make_table_registration($id_tournament);
            if (count($t_))
              $table_ =CInfo_swiss_tournament_::out_table($t_);
             else
              $table_ ='';

            $result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                   'text-decoration: none; font-weight: normal">'.
                        '<DIV style="font-size: 12pt; text-align: left; color: white">'.
                            $info_.
                        '</DIV><BR>';
            if ($status_reg == 1)
              $result_ .='<DIV style="font-size: 12pt; text-align: left">'.
                            '<A href="MainPage.php?link_=swiss_Tournament&id_='.$id_tournament.'&add_=question">'.
                            '  зарегистрироваться'.
                            '</A>'.
                         '</DIV><BR>';
             else if ($status_reg == 2)
              $result_ .='<DIV style="font-size: 12pt; text-align: left">'.
                            '<A href="MainPage.php?link_=swiss_Tournament&id_='.$id_tournament.'&del_=question">'.
                            '  отменить регистрацию'.
                            '</A>'.
                         '</DIV><BR>';
            $result_ .=$table_.'</SPAN>';
            return $result_;
        }#BodyRegistration

        protected static function BodyTournament_($m){
            $table_ =CInfo_swiss_tournament_::out_table($m);

            $result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                   'text-decoration: none; font-weight: normal">'.
                      $table_.
                      '</SPAN>';
            return $result_;
        }#BodyTournament_

        public static function outQuestionAdd($id_){
            CPage_::QuestionPage('Подтвердите Ваше решение принять участие в турнире.',
                                 'MainPage.php?link_=swiss_Tournament&id_='.$id_,
                                 'MainPage.php?link_=swiss_Tournament&id_='.$id_.'&add_=self');
        }

        public static function outQuestionDel($id_){
            CPage_::QuestionPage('Подтвердите Ваше решение отменить регистрацию в турнире.',
                                 'MainPage.php?link_=swiss_Tournament&id_='.$id_,
                                 'MainPage.php?link_=swiss_Tournament&id_='.$id_.'&del_=self');
        }

        public static function MakeMenuMainPage(){
            $i =0;
            if (isset($_SESSION[SESSION_LINK_ESC_INFO_SWISS_TOURNAMENT])){
                CPage_::$menu_[$i]['link'] = $_SESSION[SESSION_LINK_ESC_INFO_SWISS_TOURNAMENT];
                CPage_::$menu_[$i]['image'] ='Image/label_esc.png';
                CPage_::$menu_[$i]['submit'] =false;
                CPage_::$menu_[$i]['level'] =1;
                CPage_::$menu_[$i++]['active'] ='N';
            }

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=Events';
            CPage_::$menu_[$i]['image'] ='Image/label_begin.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =1;
            CPage_::$menu_[$i++]['active'] ='N';

            CPage_::$menu_[$i]['link'] = 'index.php';
            CPage_::$menu_[$i]['image'] ='Image/label_exit.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =1;
            CPage_::$menu_[$i]['active'] ='N';
        }#MakeMenuMainPage

        public static function MakePage(){
            $connect_ =false;
            $transact_ =false;
            try{
                unset($_SESSION[SESSION_LINK_ESC_GAME]);
                $link_esc_game='';

                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception('При соединении с базой данных произошла ошибка.');
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception('При соединении с базой данных произошла ошибка.');

                $classA_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],1);
                $classB_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],2);
                $classC_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],3);
                $ratingA_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],1);
                $ratingB_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],2);
                $ratingC_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],3);

                if (!isset($_GET['id_'])) throw new Exception('Номер турнира не указан.');
                if (!ctype_digit($_GET['id_'])) throw new Exception('Номер турнира указан неверно.');
                $id_ =$_GET['id_'];

                $header_ ='<DIV id="text_login_">'.
                          '  логин: '.$_SESSION[SESSION_LOGIN_].'<BR>'.
                          '  класс: A'.$classA_.'/B'.$classB_.'/C'.$classC_.
                          '  рейтинг: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'<BR>'.
                          '</DIV>'   .
                          '<DIV id="text_header_">'.
                          '  Турнир №'.$id_.
                          '</DIV>';

                $status_ =CInfo_swiss_tournament_::get_status_($id_);
                switch ($status_){
                    case 1: #этап регистрации
                            if (isset($_GET['add_']) && ($_GET['add_'] =='question')){
                              CPage_::$header_ =$header_;
                              CInfo_swiss_tournament_::outQuestionAdd($id_);
                              if ($transact_) const_::Commit_();
                              if ($connect_)const_::Disconnect_();
                              return;
                            }
                            if (isset($_GET['del_']) && ($_GET['del_'] =='question')){
                              CPage_::$header_ =$header_;
                              CInfo_swiss_tournament_::outQuestionDel($id_);
                              if ($transact_) const_::Commit_();
                              if ($connect_)const_::Disconnect_();
                              return;
                            }
                            if (isset($_GET['add_']) && ($_GET['add_'] =='self')){
                                if (CInfo_swiss_tournament_::status_registration($id_) ==1)
                                    CInfo_swiss_tournament_::add_gamer($id_);
                            }
                            if (isset($_GET['del_']) && ($_GET['del_'] =='self')){
                                if (CInfo_swiss_tournament_::status_registration($id_) ==2)
                                    CInfo_swiss_tournament_::del_gamer($id_);
                            }
                            $body_ =CInfo_swiss_tournament_::BodyRegistration($id_);
                            break;
                    case 2: #время регистрации закончилось, первый тур ещё не начат
                            if (CInfo_swiss_tournament_::get_count_logins_registration($id_) < MIN_GAMERS_SWISS_TOURNAMENT){
                              $s ='Турнир №'.$id_.' не состоялся, по причине недостаточного кол-ва желающих принять в нем участие.';
                              CInfo_swiss_tournament_::send_message_($id_,$s);
                              CInfo_swiss_tournament_::del_tournament($id_);
                              $body_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                 ' text-decoration: none; font-weight: normal;'.
                                                 ' text-align:center">'.
                                      ' Турнир №'.$id_.' не состоялся по причине недостаточного количества желающих принять участие.'.
                                      '</DIV>';
                            }else{
                                CInfo_swiss_tournament_::set_count_gamers_($id_);
                                $m=CInfo_swiss_tournament_::first_round_($id_);
                                CInfo_swiss_tournament_::begin_games_of_round($m,$id_,1);
                                CInfo_swiss_tournament_::save_info_round($m,$id_,1);
                                $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            }
                            break;
                    case 3: #идет тур
                            CInfo_swiss_tournament_::set_result_end_games($id_);
                            $m=CInfo_swiss_tournament_::make_table($id_);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            break;
                    case 4: #тур завершен (не последний), следующий ещё не начат
                            CInfo_swiss_tournament_::set_result_end_games($id_);
                            $m=CInfo_swiss_tournament_::make_table($id_);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            CInfo_swiss_tournament_::sort_table_($m);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            for($i=count($m[1]['rounds']); $i >=1; $i--)
                              if ($m[1]['rounds'][$i]['balls'] !=''){
                               $round_=$i;
                               break;
                              }
                            CInfo_swiss_tournament_::next_round($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            CInfo_swiss_tournament_::begin_games_of_round($m,$id_,$round_+1);
                            CInfo_swiss_tournament_::save_info_round($m,$id_,$round_+1);
                            $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            break;
                    case 5: #последний тур завершен, но турнир не закрыт
                            CInfo_swiss_tournament_::set_result_end_games($id_);
                            $m=CInfo_swiss_tournament_::make_table($id_);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            CInfo_swiss_tournament_::sort_table_($m);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            $round_ =count($m[1]['rounds']);
                            CInfo_swiss_tournament_::save_info_round($m,$id_,$round_);
                            CInfo_swiss_tournament_::set_places($m);
                            CInfo_swiss_tournament_::end_tournament_($id_,$m);
                            $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            break;
                    case 6: #турнир закрыт
                            $m=CInfo_swiss_tournament_::make_table($id_);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            CInfo_swiss_tournament_::set_places($m);
                            $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            break;
                    case 7: #тур завершен (не последний), следующий не начат, не наступило время начала, жеребьевки не было
                            CInfo_swiss_tournament_::set_result_end_games($id_);
                            $m=CInfo_swiss_tournament_::make_table($id_);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            CInfo_swiss_tournament_::sort_table_($m);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            for($i=count($m[1]['rounds']); $i >=1; $i--)
                              if ($m[1]['rounds'][$i]['balls'] !=''){
                               $round_=$i;
                               break;
                              }
                            CInfo_swiss_tournament_::next_round($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            CInfo_swiss_tournament_::save_info_round($m,$id_,$round_+1);
                            $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            break;
                    case 8: #тур завершен (не последний), следующий не начат, не наступило время начала, жеребьевка была
                            $m=CInfo_swiss_tournament_::make_table($id_);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            break;
                    case 9: #тур создан, подошло время начала
                            $m=CInfo_swiss_tournament_::make_table($id_);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            for($i=count($m[1]['rounds']); $i >=1; $i--)
                              if ($m[1]['rounds'][$i]['balls'] !=''){
                               $round_=$i;
                               break;
                              }
                            CInfo_swiss_tournament_::begin_games_of_round($m,$id_,$round_+1);
                            CInfo_swiss_tournament_::save_info_round($m,$id_,$round_+1);
                            $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            break;
                }#switch

                $link_esc_game ='MainPage.php?link_=swiss_Tournament&id_='.$id_;

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
                if ($connect_)const_::Disconnect_();

                CPage_::$header_ =$header_;

                CInfo_swiss_tournament_::MakeMenuMainPage();
                CPage_::$body_ =$body_;
                CPage_::MakePage();

                if ($link_esc_game !='')
                    $_SESSION[SESSION_LINK_ESC_GAME]=$link_esc_game;
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
                CPage_::PageErr();
            }#try
        }#MakePage
    }#CInfo_swiss_tournament_
?>
