<?php
    require_once('const_.php');
    require_once('Users.php');

    class CInfo_swiss_tournament_{
#������� ���������� ������ �������:
#  1: ������ �� �����, ���� �����������
#  2: ����� ������ ������� �������, �� ������ ��� ��� �� �����
#  3: ���� ���
#  4: ��� �������� (�� ���������), ��������� ��� �� �����
#  5: ��������� ��� ��������, �� ������ �� ������
#  6: ������ ������
#  7: ��� �������� (�� ���������), ��������� �� �����, �� ��������� ����� ������, ���������� �� ����
#  8: ��� �������� (�� ���������), ��������� �� �����, �� ��������� ����� ������, ���������� ����
#  9: ��� ������, ������� ����� ������
        protected static function get_status_($id_tournament){
            $cursor_ =false;
            try{
#�������� ����� ������ ��� ��� (������ 1)
                $s = 'select IF(NOW() < begin_,1,2) as start_,end_,cRounds_'.
                     '  from TTournaments_'.
                     '  where id_ ='.$id_tournament;
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('��� ������ ���������� � ������� ��������� ������.');
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception('������ �� ������.');
                $count_rounds =$row_['cRounds_'];
                mysql_free_result($cursor_); $cursor_ =false;
                if ($row_['start_'] ==1) return 1;
#�������� ������ ������ ��� ��� (������ 6)
                if (!is_null($row_['end_'])) return 6;
#�������� ����� ������ ����� ��� ��� (������ 2)
                $s ='select MAX(round_) as max_ from TRoundsSwiss_ where (id_tournament_='.$id_tournament.') and (not id_game_ is null)';
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('��� ������ ���������� � ������� ��������� ������.');
                $row_ =mysql_fetch_array($cursor_);
                mysql_free_result($cursor_); $cursor_ =false;
                if (!$row_ || is_null($row_['max_'])) return 2;
#�������� ������ ���� ��� (������ 3)
                $curr_round_ =$row_['max_'];
                $s ='select count(*) as count_ from TRoundsSwiss_ A, TGames_ B'.
                    ' where (A.id_tournament_='.$id_tournament.') and (A.round_='.$curr_round_.') and'.
                    '       (A.id_game_ = B.id_) and (B.result_ is null)';
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('��� ������ ���������� � ������� ��������� ������.');
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception('��� ������ ���������� � ������� ��������� ������.');
                mysql_free_result($cursor_); $cursor_ =false;
                if ($row_['count_'] > 0) return 3;
#(������� 4 � 5)
                if ($curr_round_ < $count_rounds){
#������� ����� ���������� ������� ��� �������� ���� ����������
                  $s ='select MAX(round_) as max_ from TRoundsSwiss_ where id_tournament_='.$id_tournament;
                  $cursor_=mysql_query($s,const_::$connect_);
                  if (!$cursor_) throw new Exception('��� ������ ���������� � ������� ��������� ������.');
                  $row_ =mysql_fetch_array($cursor_);
                  if (!$row_) throw new Exception('��� ������ ���������� � ������� ��������� ������.');
                  mysql_free_result($cursor_); $cursor_ =false;
                  $r_ =$row_['max_'];
#������� ���������� � ������ ����
                  $s ='select IF(NOW() < begin_,1,2) as start_'.
                      ' from TStartRoundsTournament'.
                      ' where (id_tournament_='.$id_tournament.') and (round_ ='.($curr_round_+1).')';
                  $cursor_=mysql_query($s,const_::$connect_);
                  if (!$cursor_) throw new Exception('��� ������ ���������� � ������� ��������� ������.');
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
                if (!$cursor_) throw new Exception('��� ������ ���������� � ������� ��������� ������.');
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception('������ �� ������.');
                $result_ ='������ �� ����������� �������'.'<br>'.
                          '������ '.$row_['begin_'].', ����� ����������'.'<br>'.
                          '����� ����� '.$row_['cRounds_'].'<br>'.
                          '��������� '.$reglaments_['reglament'.$row_['reglament_'].'_'].'<br>'.
                          '����� '.$row_['class_'];
                mysql_free_result($cursor_);
                return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception($e->getMessage());
            }
        }

#����������: 1 - ����� ����� ������������������ � �������,
#            2 - ����� ����� �������� �����������,
#            3 - �����������/������ ����������� ���������
        protected static function status_registration($id_){
            $cursor_ =false;
            try{
#�������� - ���� ����������� �� ������
                $s = 'select IF(NOW() < begin_,1,2) as start_,class_'.
                     '  from TTournaments_'.
                     '  where id_ ='.$id_;
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception('������ �� ������.');
                mysql_free_result($cursor_); $cursor_ =false;
                if ($row_['start_'] ==2) return 3;
                $class_ =$row_['class_'];
#��������, ����� ��� �����������������
                $s ='select count(*) as count_'.
                    ' from TMembersTournament_ A'.
                    ' where (A.id_tournament_ ='.$id_.') and (A.id_gamer_ ='.$_SESSION[SESSION_ID_].')';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                mysql_free_result($cursor_); $cursor_ =false;
                if ($row_ && ($row_['count_'] > 0)) return 2;
#�������� ������ �������
                if ((strlen(trim($class_)) == 1) ||
                    (($class_{0} == 'A') && ($class_ ==('A'.CUsers_::ReadClass_($_SESSION[SESSION_ID_],1)))) ||
                    (($class_{0} == 'B') && ($class_ ==('A'.CUsers_::ReadClass_($_SESSION[SESSION_ID_],2)))) ||
                    (($class_{0} == 'C') && ($class_ ==('A'.CUsers_::ReadClass_($_SESSION[SESSION_ID_],3)))))
                  return 1;

                return 3;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($e->getMessage() == '')
                  throw new Exception('��� ������ ���������� � ������� ��������� ������.');
                 else
                  throw new Exception($e->getMessage());
            }
        }#status_registration

/*
     ��������� ������� ����� ��������� ���:
      -----------------------------------------------------------------------------------------
      |     �����       |      ��� 1       |      ��� 2       |      ��� 3       |��������|�����|
      |-|-------|-------|------------------|------------------|------------------|-------|-----|
      | |       |�������|����    | �����   |����    | �����   |����    | �����   |       |     |
      |1|����� 1|       |--------|���������|--------|���������|--------|���������|�������|�����|
      | |       |�����  |��������| �����   |��������| �����   |��������| �����   |       |     |
      ------------------|------------------|------------------|------------------|-------|-----|
      | |       |�������|����    | �����   |����    | �����   |����    | �����   |       |     |
      |2|����� 2|       |--------|���������|--------|���������|--------|���������|�������|�����|
      | |       |�����  |��������| �����   |��������| �����   |��������| �����   |       |     |
      ------------------|------------------|------------------|------------------|-------|-----|
      | |       |�������|����    | �����   |����    | �����   |����    | �����   |       |     |
      |3|����� 3|       |--------|���������|--------|���������|--------|���������|�������|�����|
      | |       |�����  |��������| �����   |��������| �����   |��������| �����   |       |     |
      ------------------|------------------|------------------|------------------|-------|-----|
      | |       |�������|����    | �����   |����    | �����   |����    | �����   |       |     |
      |4|����� 4|       |--------|���������|--------|���������|--------|���������|�������|�����|
      | |       |�����  |��������| �����   |��������| �����   |��������| �����   |       |     |
      ------------------|------------------|------------------|------------------|-------|-----|
      | |       |�������|����    | �����   |����    | �����   |����    | �����   |       |     |
      |5|����� 5|       |--------|���������|--------|���������|--------|���������|�������|�����|
      | |       |�����  |��������| �����   |��������| �����   |��������| �����   |       |     |
      |-|-------|-------|------------------|------------------|------------------|-------|-----|

      ������:
      t[1..n][id_gamer] - id ������
      t[1..n][login_gamer] - ����� ������
      t[1..n][rating] - �������
      t[1..n][class] - �����
      t[1..n][rounds][1..r][color] - ����
      t[1..n][rounds][1..r][opponent] - ����� ���������
      t[1..n][rounds][1..r][balls] - ����� ��������� �����
      t[1..n][rounds][1..r][id_game] - id -������
      t[1..n][rounds][1..r][id_opponent] - id -���������
      t[1..n][buchholz] - ��������
      t[1..n][place1] - ����� 1
      t[1..n][place2] - ����� 2
*/
        protected static function make_table_registration($id_tournament){
            $result_ =array();
            $cursor_ =false;
            try{
#����� ���������� � ���������� �������
                $s = 'select cRounds_ from TTournaments_ where id_ ='.$id_tournament;
                $cursor_=mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_) throw new Exception();
                $rounds_ =$row_['cRounds_'];
                mysql_free_result($cursor_); $cursor_ =false;
#����� ������ ��������������������
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
                throw new Exception('��� ������ ���������� � ������� ��������� ������.');
            }
        }#make_table_registration

        protected static function add_gamer($id_){
            $cursor_ =false;
            try{
#�������� �������� num_ (����� ��������� �������)
                $s ='select max(num_) as max_num_ from TMembersTournament_ where id_tournament_='.$id_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                if (!$row_ || is_null($row_['max_num_'])) $num_ =1; else $num_ =$row_['max_num_'] +1;
                mysql_free_result($cursor_); $cursor_ =false;
#�������� ������ ��������� �������
                $s ='insert into TMembersTournament_(num_,id_tournament_,id_gamer_)'.
                    ' values('.$num_.','.$id_.','.$_SESSION[SESSION_ID_].')';
                if (!mysql_query($s,const_::$connect_)) throw new Exception();
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('��� ����������� � ������� ��������� ������.');
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
                throw new Exception('��� ������ ����������� ��������� ������.');
            }
        }#del_gamer

#������� ���������� ���-�� �������������������� � �������
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
                throw new Exception('��� ������ ���������� � ������� ��������� ������.');
            }
        }#get_count_logins_registration

       protected static function set_count_gamers_($id_){
            $s ='update TTournaments_ set'.
                ' cGamers_ = (select count(*) from TMembersTournament_ where id_tournament_='.$id_.')'.
                ' where id_='.$id_;
            if (!mysql_query($s,const_::$connect_)) throw new Exception('��� ������ ���-�� ���������� ������� ��������� ������.');
       }#set_count_gamers_

#������� ������ �������� ��������� ���� ���������� �������
        protected static function send_message_($id_,$message_){
          $s ='insert into TInfo_(info_,id_gamer_)'.
              '  select \''.mysql_escape_string(convert_cyr_string($message_,'w','d')).'\',id_gamer_'.
              '   from TMembersTournament_ where id_tournament_ ='.$id_;
          if (!mysql_query($s,const_::$connect_)) throw new Exception('��� �������� ��������� ���������� ������� ��������� ������');
        }#send_message_

        public static function del_tournament($id_){
          try{
            $s ='delete from TMembersTournament_ where id_tournament_='.$id_;
            if (!mysql_query($s,const_::$connect_)) throw new Exception('��� �������� ������� ��������� ������.');
            $s ='delete from TTournaments_ where id_='.$id_;
            if (!mysql_query($s,const_::$connect_)) throw new Exception('��� �������� ������� ��������� ������.');
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
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('��� ������ ���������� � �������� ��������� ������.');
            while ($row_ =mysql_fetch_array($cursor_)){
                $s ='������ �'.$row_['id_'].' �� ���������, �� ������� �������������� ���-�� �������� ������� � ��� �������.';
                CInfo_swiss_tournament_::send_message_($row_['id_'],$s);
                CInfo_swiss_tournament_::del_tournament($row_['id_']);
            }#while
          }catch(Exception $e){
              if ($cursor_) mysql_free_result($cursor_);
              throw new Exception($e->getMessage());
          }
       }#del_tournaments_no_gamers

#������� ������������ ������
        protected static function mix_table($m){
/* ���������:
          m - ������ ������������ n (1..n), ������ ���������� ����� � �������, �������� id ������
           for (i=n; i > 1; i--)
             k = ��������� ����� �� 1..i   //���� �������� 1..i �������� ����� �������� �� �����, �� ������� �� ���
                                           //��� �����������, ���� 1..i-1 �������� �� ����� �������� �� �����
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

#������� �������������� ���������� ��� ������ ������� ����
        protected static function first_round_($id_){
/*���������
            n - ����������� �������
            i =1
            while(i < n)
              ����� i ����� ������ � ������� i+1, i ������ ����� ������
              i +=2
            .
            if i=n
              ����� n �������� +1 ����
            .
*/
          $cursor_ =false;
          try{
#������������� ��������� �������
            $m=CInfo_swiss_tournament_::make_table_registration($id_);
            $m=CInfo_swiss_tournament_::mix_table($m);
            $n =count($m);
#�������� ���������� � �������������� �����
            $s ='select firstColorWhite_ from TTournaments_ where id_='.$id_;
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
            $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
            mysql_free_result($cursor_); $cursor_ =false;
            $first_color_white_ =($row_['firstColorWhite_'] =='Y');
#�������� ���������� � ������ ����
            $i =1;
            while($i < $n){
                $m[$i]['rounds'][1]['opponent']=$i+1;
                $m[$i]['rounds'][1]['color'] =($first_color_white_ ? '�' : '�');
                $m[$i]['rounds'][1]['id_opponent'] =$m[$i+1]['id_gamer'];
                $m[$i+1]['rounds'][1]['opponent']=$i;
                $m[$i+1]['rounds'][1]['color'] =($first_color_white_ ? '�' : '�');
                $m[$i+1]['rounds'][1]['id_opponent'] =$m[$i]['id_gamer'];
                $i +=2;
            }#while
            if ($i==$n){
                $m[$i]['rounds'][1]['balls'] =1;
            }
            return $m;
          }catch(Exception $e){
              if ($cursor_) mysql_free_result($cursor_);
              throw new Exception('��� ������ ������� ���� ��������� ������');
          }
        }#first_round_

        protected static function begin_games_of_round(&$m,$id_,$round_){
          $cursor_ =false;
          try{
#�������� ��������� � ����� �������
            $s ='select reglament_,class_ from TTournaments_ where id_='.$id_;
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
            $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
            mysql_free_result($cursor_); $cursor_ =false;
            $class_ =$row_['class_'];
            $reglament_ =$row_['reglament_'];
#������� ������
            for($i=1; $i <=count($m); $i++){
                if (($m[$i]['rounds'][$round_]['opponent'] !='') && ($m[$i]['rounds'][$round_]['id_game'] =='')){
                   if ($m[$i]['rounds'][$round_]['color'] == '�'){
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
              throw new Exception('��� ������ ������ ������ ��������� ������');
          }
        }#begin_games_of_round

        protected static function save_info_round($m,$id_,$round_){
#������ ���������� � ������
          $s ='delete from TRoundsSwiss_ where (id_tournament_='.$id_.') and (round_='.$round_.')';
          if (!mysql_query($s,const_::$connect_)) throw new Exception('��� ���������� ���������� � ���� ��������� ������');
#�������� ������� insert
          for($i=1; $i <=count($m); $i++){
            if ($m[$i]['rounds'][$round_]['color'] =='')
              $colorWhite_ ='null';
             else if ($m[$i]['rounds'][$round_]['color'] =='�')
              $colorWhite_ ='\'Y\'';
             else
              $colorWhite_ ='\'N\'';
            $s ='insert into TRoundsSwiss_(id_tournament_,round_,id_gamer_,position_,balls_,id_game_,id_opponent_,colorWhite_)'.
                '  values('.$id_.','.$round_.','.$m[$i]['id_gamer'].','.$i.','.
                          ($m[$i]['rounds'][$round_]['balls'] !='' ? $m[$i]['rounds'][$round_]['balls'] : 'null').','.
                          ($m[$i]['rounds'][$round_]['id_game'] !='' ? $m[$i]['rounds'][$round_]['id_game'] : 'null').','.
                          ($m[$i]['rounds'][$round_]['id_opponent'] !='' ? $m[$i]['rounds'][$round_]['id_opponent'] : 'null').','.
                          $colorWhite_.')';
            if (!mysql_query($s,const_::$connect_)) throw new Exception('��� ���������� ���������� � ���� ��������� ������');
          }#for
        }#save_info_round

        protected static function get_curr_round($id_){
          $s ='select MAX(round_) as max_ from TRoundsSwiss_ where id_tournament_='.$id_;
          $cursor_=mysql_query($s,const_::$connect_);
          if (!$cursor_) throw new Exception('��� ������ ���������� � ������� ��������� ������.');
          $row_ =mysql_fetch_array($cursor_);
          mysql_free_result($cursor_); $cursor_ =false;
          if (!$row_ || is_null($row_['max_'])) return 1; else return $row_['max_'];
        }#get_curr_round

        protected static function make_table($id_){
          $result_ =array();
          $cursor_ =false;
          try{
#����� ���������� � ���������� �������
              $s ='select cRounds_ from TTournaments_ where id_ ='.$id_;
              $cursor_=mysql_query($s,const_::$connect_);
              if (!$cursor_) throw new Exception();
              $row_ =mysql_fetch_array($cursor_);
              if (!$row_) throw new Exception();
              $rounds_ =$row_['cRounds_'];
              mysql_free_result($cursor_); $cursor_ =false;
#������� �����
              $curr_round =CInfo_swiss_tournament_::get_curr_round($id_);
#��������� ���������� ��������� �������
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
#����� ���������� � �������
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
                   $i =$list_gamers_[$row_['id_gamer_']]; #������� � ��������� �������
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
                  $result_[$i]['rounds'][$row_['round_']]['color'] =($row_['colorWhite_'] == 'Y' ? '�' : '�');
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
              throw new Exception('��� ������ ���������� � ������� ��������� ������');
          }
        }#make_table

        protected static function set_num_opponents(&$m){
#����� ��������� ���������� ��������� �������
          $list_gamers_ =array();
          for($i =1; $i <=count($m); $i++)
            $list_gamers_[$m[$i]['id_gamer']] =$i;
          for($i =1; $i <=count($m); $i++)
            for($j=1; $j <=count($m[$i]['rounds']); $j++)
              if ($m[$i]['rounds'][$j]['id_opponent'] != '')
                $m[$i]['rounds'][$j]['opponent'] =$list_gamers_[$m[$i]['rounds'][$j]['id_opponent']];
        }#set_num_opponents

#���������� ����� � ����, ��� ������������� ������
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
              throw new Exception('��� ������ ���������� � ������� ��������� ������');
          }
        }#set_result_end_games

#������� ������������ ����������� ���������
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

#������� ���������, ��� ������ �� ������: true - �� ������, false - ������
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

#������� ����������� ������� ���� ��� ������
#   +1 - �������� ���� ����� (��������� ������ ��� �������� ����� ������)
#   +2 - ����� �������� ���� ����� (��������� ��� ������ �������� ����� ������)
#   -1 - �������� ���� ������
#   -2 - ����� �������� ���� ������
#   0 - ����� � ������� ��� �� ����� (� ������ ���� �� ���� ���������), ���� �����
        protected static function wish_color($m,$index_gamer){
          $result_=0;
          for($i =1; $i <= count($m[$index_gamer]['rounds']); $i++){
            $s =$m[$index_gamer]['rounds'][$i]['color'];
            if ($s !='')
              if ($s =='�')
                if (($result_ == -1) || ($result_ == -2)) $result_ =-2; else $result_ =-1;
              else
                if (($result_ == 1) || ($result_ == 2)) $result_ =2; else $result_ =1;
          }#for
          return $result_;
        }#wish_color

/*������� �����������_������������ ������ ����� �������� $index_gamer_1,$index_gamer_2
     ����������: � ��������� ['������� ������'] - ������� � ��������� �����
                 � ��������� ['���������'] - 0 - ������ �� �����
                                             1 - ���� �� ������� ����� ������ ������ ��� ����� � ��� �� ������
                                             2 - ���� �� ������� ����� ������ ������ ��� ����� � ��� �� ������
                                             3 - �� ����� ��������*/
        protected static function k_equivalent($m,$round_,$index_gamer_1,$index_gamer_2){
            $result_['������� ������'] =NULL;
            $result_['���������']      =NULL;
#�������� ����������� ������ �����
            if (!CInfo_swiss_tournament_::no_meet($m,$index_gamer_1,$index_gamer_2)){
              $result_['���������'] =0;
              return $result_;
            }
#��������� ������� ������
            $balls_gamer_1 =$m[$index_gamer_1]['rounds'][$round_-1]['balls'];
            $balls_gamer_2 =$m[$index_gamer_2]['rounds'][$round_-1]['balls'];
            $result_['������� ������'] =abs($balls_gamer_1 - $balls_gamer_2);
#��������� �������� ���� �����
            $wish_color_gamer_1 =CInfo_swiss_tournament_::wish_color($m,$index_gamer_1);
            $wish_color_gamer_2 =CInfo_swiss_tournament_::wish_color($m,$index_gamer_2);
#��������� ����������� ������������
#������� ������������
#(�� ��������� �����):              (��������� �����):
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
#   +1 - �������� ���� ����� (��������� ������ ��� �������� ����� ������)
#   +2 - ����� �������� ���� ����� (��������� ��� ������ �������� ����� ������)
#   -1 - �������� ���� ������
#   -2 - ����� �������� ���� ������
#   0 - ����� � ������� ��� �� ����� (� ������ ���� �� ���� ���������), ���� �����
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
            $result_['���������']=$t_k[$wish_color_gamer_1][$wish_color_gamer_2];
            return $result_;
        }#k_equivalent

#������� ���������� ��������� ���� � ��������
#0 - ���� � �������� �����
#1 - ��������� ������� ������ > ����������
#2 - ��������� ������� ������ < ����������
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
#��������������� ������� ��� sort_table
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
#������������ ������� ����������, �������� ����� � ����������������� ���� Delphi 7
          CInfo_swiss_tournament_::quick_sort_table_($m,1,count($m),$round_);
        }#sort_table_

/*
 ������ ��������� ����� ���
         1|2|3|4|..|n|         1|2|3|4|..|n| , ��� ���-�� ������� � ���� (������ �����)
        --------------        --------------
       1|X|X|X|X| X|X|       1|X|X|X|X| X|X|
       2| |X|X|X| X|X|       2| |X|X|X| X|X|
       3| | |X|X| X|X|       3|-|-|X|X| X|X|
       4| | | |X| X|X|       4| | |||X| X|X|
      ..| | | | | X|X|      ..|-|-|+|-|-X|X|
       n| | | | |  |X|       n| | ||| | ||X|
                                   |    |
                                   |    |
                                 ����3 ����..
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
/* ���������� ������ ���� � ������� ���� �������� � �������� $path_ � � ��������� $count_paths_ ����� ���-�� �����,
   � ��������� $t ��������� ������ ���������, �����������, ��� ��������� ������� ������������� � ������� ���������� ����*/
            $path_ =0; $count_paths_ =0; $n =count($t);
            for($i=1; $i <=$n; $i++){
                $a =0;
                for($j=1; $j <$i; $j++)
                  if ($t[$i][$j]['���������'] >  0)
                    $a++;
                for($j=$i+1; $j <=$n; $j++)
                  if($t[$j][$i]['���������'] >  0)
                    $a++;
                if ($a >0){
                  $count_paths_++;
                  if ($path_ ==0) $path_ =$i;
                }
            }
        }#get_path

#������� ���������� ��������
#0 - �����
#1 - ������ ���������������� 2
#2 - ������ ���������������� 1
        protected static function comparison_variant($v1,$v2){
            if ($v1['������� ������'] == $v2['������� ������']){
                if ($v1['���������'] == $v2['���������']) return 0;
                 else if ($v1['���������'] > $v2['���������']) return 1;
                 else return 2;
            }else if ($v1['������� ������'] < $v2['������� ������']) return 1;
             else return 2;
        }#comparison_variant

        protected static function quick_array_variants(&$v,$iLo,$iHi){
#��������������� ������� ��� sort_table
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
#���������� ������ ���������, ��� ���� $path_, ��������������� � ������� ���������� �������������
#1 - �������� ���������� ������� ��� $path_, ��������� - �������� ����������
            $result_ =array(); $k=1; $n =count($t);
            for($i=1; $i <$path_; $i++)
              if ($t[$path_][$i]['���������'] > 0){
                $result_[$k][1] =$t[$path_][$i];
                $result_[$k++][2] =$i;
              }
            for($i=$path_+1; $i <=$n; $i++)
              if ($t[$i][$path_]['���������'] > 0){
                $result_[$k][1] =$t[$i][$path_];
                $result_[$k++][2] =$i;
              }
            CInfo_swiss_tournament_::quick_array_variants($result_,1,count($result_));
            return $result_;
        }#make_array_variants_of_path

       protected static function mark_path(&$t,$path_,$marker_){
            $n =count($t);
            for($i=1; $i <$path_; $i++)
               $t[$path_][$i]['���������'] +=$marker_;
            for($i=$path_+1; $i <=$n; $i++)
               $t[$i][$path_]['���������'] +=$marker_;
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
#������ �����
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
/*���� ������� ����� ������ i ����� ������������ �� �������
   +1 - �������� ���� ����� (��������� ������ ��� �������� ����� ������)
   +2 - ����� �������� ���� ����� (��������� ��� ������ �������� ����� ������)
   -1 - �������� ���� ������
   -2 - ����� �������� ���� ������
    0 - ����� � ������� ��� �� ����� (� ������ ���� �� ���� ���������), ���� �����
                                 +1|+2|-1|-2|0|       �������� ���� j-�� ������
                                --------------
                             +1| �| �| �| �|�|
                                --------------
                             +2| �| �| �| �|�|
 �������� ���� i-�� ������      --------------
                             -1| �| �| �| �|�|
                                --------------
                             -2| �| �| �| �|�|
                                --------------
                              0| �| �| �| �|�|
                                --------------
*/
             $t_color =array();
             $t_color[1]  =array(1=>'�',2=>'�',-1=>'�',-2=>'�',0=>'�');
             $t_color[2]  =array(1=>'�',2=>'�',-1=>'�',-2=>'�',0=>'�');
             $t_color[-1] =array(1=>'�',2=>'�',-1=>'�',-2=>'�',0=>'�');
             $t_color[-2] =array(1=>'�',2=>'�',-1=>'�',-2=>'�',0=>'�');
             $t_color[0]  =array(1=>'�',2=>'�',-1=>'�',-2=>'�',0=>'�');

             for ($i=1; $i <=count($m); $i++)
                if (($m[$i]['rounds'][$round_]['opponent'] !='') && ($m[$i]['rounds'][$round_]['color'] ==='')){
                  $j =$m[$i]['rounds'][$round_]['opponent'];
                  $wish_color_i = CInfo_swiss_tournament_::wish_color($m,$i);
                  $wish_color_j = CInfo_swiss_tournament_::wish_color($m,$j);
                  $m[$i]['rounds'][$round_]['color'] =$t_color[$wish_color_i][$wish_color_j];
                  if ($m[$i]['rounds'][$round_]['color'] =='�')
                    $m[$j]['rounds'][$round_]['color'] = '�';
                   else
                     $m[$j]['rounds'][$round_]['color'] = '�';
                }
        }#set_colors

#������� �������������� ���������� ��� ������ ����
        protected static function next_round(&$m){
#��������� �����
            for ($i =count($m[1]['rounds'])-1; $i>=1; $i--)
              if ($m[1]['rounds'][$i]['balls'] != ''){
                $round_ =$i+1;
                break;
              }
#��������� ������, ������� ������� +1 ����
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
#����������
            $t =CInfo_swiss_tournament_::make_table_variants($m,$round_);
            if (!CInfo_swiss_tournament_::get_game_(1,$t,$m,$round_))
              throw new Exception('��� ��������� ����. ���� ��������� ������.');
#��������� �����
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
                if (!mysql_query($s,const_::$connect_)) throw new Exception('��� �������� ������� ��������� ������');
            }#for
            $s ='update TTournaments_ set end_=NOW() where id_='.$id_;
            if (!mysql_query($s,const_::$connect_)) throw new Exception('��� �������� ������� ��������� ������');
            CInfo_swiss_tournament_::FirstPlaceTournament($id_,$m);
            CInfo_swiss_tournament_::LastPlaceTournament($id_,$m);
        }#end_tournament_

# �������� ����� ������, ���� �� ����� ������ ����� � �������
        protected static function FirstPlaceTournament($id_,$m){
            $cursor_ =false;
            try{
                if ($m[1]['place2'] ==''){
#�������� ����� �������
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
                throw new Exception('��� ��������� ������ ������ ��������� ������.');
            }
        } #FirstPlaceTournament

# �������� ����� ������, ���� �� ����� ��������� ����� � �������
        public static function LastPlaceTournament($id_,$m){
            $cursor_ =false;
            try{
                if ($m[count($m)]['place2'] ==''){
#�������� ����� �������
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
                throw new Exception('��� ��������� ������ ������ ��������� ������.');
            }
        }#LastPlaceTournament

        protected static function out_table($table_){
#����� ���������� � ���-�� �����
            $rounds_ =count($table_[1]['rounds']);
#��������� ����� �� ��������� ������ ������� ���������� ������
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

#����� �������
            $result_  ='<TABLE style ="border: none" cellspacing="3" cellpadding="0">'.
                       '   <COL span="'.($rounds_*2+1).'">'.
                       '   <TR>'.
                       '        <TD colspan="3" class ="table_head_1" style="white-space:nowrap">&nbsp;</TD>';
                                for($i=1; $i<=$rounds_; $i++)
                                  $result_ .='<TD colspan="2" class ="table_head_1" style="white-space:nowrap">��� '.$i.'</TD>';
            $result_ .='        <TD class ="table_head_1" style="white-space:nowrap">��������</TD>'.
                       '        <TD class ="table_head_1" style="white-space:nowrap">�����</TD>'.
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
                            '  ������������������'.
                            '</A>'.
                         '</DIV><BR>';
             else if ($status_reg == 2)
              $result_ .='<DIV style="font-size: 12pt; text-align: left">'.
                            '<A href="MainPage.php?link_=swiss_Tournament&id_='.$id_tournament.'&del_=question">'.
                            '  �������� �����������'.
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
            CPage_::QuestionPage('����������� ���� ������� ������� ������� � �������.',
                                 'MainPage.php?link_=swiss_Tournament&id_='.$id_,
                                 'MainPage.php?link_=swiss_Tournament&id_='.$id_.'&add_=self');
        }

        public static function outQuestionDel($id_){
            CPage_::QuestionPage('����������� ���� ������� �������� ����������� � �������.',
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
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception('��� ���������� � ����� ������ ��������� ������.');
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception('��� ���������� � ����� ������ ��������� ������.');

                $classA_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],1);
                $classB_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],2);
                $classC_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],3);
                $ratingA_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],1);
                $ratingB_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],2);
                $ratingC_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],3);

                if (!isset($_GET['id_'])) throw new Exception('����� ������� �� ������.');
                if (!ctype_digit($_GET['id_'])) throw new Exception('����� ������� ������ �������.');
                $id_ =$_GET['id_'];

                $header_ ='<DIV id="text_login_">'.
                          '  �����: '.$_SESSION[SESSION_LOGIN_].'<BR>'.
                          '  �����: A'.$classA_.'/B'.$classB_.'/C'.$classC_.
                          '  �������: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'<BR>'.
                          '</DIV>'   .
                          '<DIV id="text_header_">'.
                          '  ������ �'.$id_.
                          '</DIV>';

                $status_ =CInfo_swiss_tournament_::get_status_($id_);
                switch ($status_){
                    case 1: #���� �����������
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
                    case 2: #����� ����������� �����������, ������ ��� ��� �� �����
                            if (CInfo_swiss_tournament_::get_count_logins_registration($id_) < MIN_GAMERS_SWISS_TOURNAMENT){
                              $s ='������ �'.$id_.' �� ���������, �� ������� �������������� ���-�� �������� ������� � ��� �������.';
                              CInfo_swiss_tournament_::send_message_($id_,$s);
                              CInfo_swiss_tournament_::del_tournament($id_);
                              $body_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                                 ' text-decoration: none; font-weight: normal;'.
                                                 ' text-align:center">'.
                                      ' ������ �'.$id_.' �� ��������� �� ������� �������������� ���������� �������� ������� �������.'.
                                      '</DIV>';
                            }else{
                                CInfo_swiss_tournament_::set_count_gamers_($id_);
                                $m=CInfo_swiss_tournament_::first_round_($id_);
                                CInfo_swiss_tournament_::begin_games_of_round($m,$id_,1);
                                CInfo_swiss_tournament_::save_info_round($m,$id_,1);
                                $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            }
                            break;
                    case 3: #���� ���
                            CInfo_swiss_tournament_::set_result_end_games($id_);
                            $m=CInfo_swiss_tournament_::make_table($id_);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            break;
                    case 4: #��� �������� (�� ���������), ��������� ��� �� �����
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
                    case 5: #��������� ��� ��������, �� ������ �� ������
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
                    case 6: #������ ������
                            $m=CInfo_swiss_tournament_::make_table($id_);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            CInfo_swiss_tournament_::set_places($m);
                            $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            break;
                    case 7: #��� �������� (�� ���������), ��������� �� �����, �� ��������� ����� ������, ���������� �� ����
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
                    case 8: #��� �������� (�� ���������), ��������� �� �����, �� ��������� ����� ������, ���������� ����
                            $m=CInfo_swiss_tournament_::make_table($id_);
                            CInfo_swiss_tournament_::set_num_opponents($m);
                            CInfo_swiss_tournament_::calc_buchholz($m);
                            $body_ =CInfo_swiss_tournament_::BodyTournament_($m);
                            break;
                    case 9: #��� ������, ������� ����� ������
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
                    if (const_::Commit_()) $transact_ =false; else throw new Exception('��� ���������� ���������� ��������� ������.');
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
