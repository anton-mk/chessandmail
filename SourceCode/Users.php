<?php
	require_once('const_.php');
	require_once('lib/mylib.php');
	require_once('Games.php');
#	require_once('Calls.php');

	class EAutotenficError extends Exception{}; #Если неверно указан логин или пароль
	class ERegistrationError extends Exception{}; #Если возникли ошибки при регистрации нового пользователя

	class CUsers_{
		public static $last_value_read_dhtml_ =false; #Переменная используется, когда различных функция
		                                              #проверяют установку флага "использовать dhtml"
#		var $list_;  --нужно удалить, похоже не используется
#Отпуск
#------------------------------------------------------------------------------------------------
#Запускаю часы в партиях игрока $id_
        protected static function Start_clock_($id_,$begin_move){
            $connect_  =false;
            $transact_ =false;
            $cursor_   =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $curr_time=time();
                $s ='select id_'.
                    ' from TGames_'.
                    ' where (result_ is null) and (beginMove_ = 0) and '.
                    '       (((idWGamer_ ='.$id_.') and (isMoveWhite_=\'Y\')) or'.
                    '        ((idBGamer_ ='.$id_.') and (isMoveWhite_=\'N\')))';
                $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                while($row_ =mysql_fetch_array($cursor_)){
                    $s ='update TGames_ set beginMove_ ='.$begin_move.' where id_='.$row_['id_'];
                    if (!mysql_query($s,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0)) throw new Exception();
                }#while

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При запуске часов произошла ошибка.');
            }
        }#Start_clock_

#Останавлмваю часы в партиях игрока $id_
        protected static function Stop_clock_($id_){
            $connect_  =false;
            $transact_ =false;
            $cursor_   =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $curr_time=time();
                $s ='select id_,idWGamer_,idBGamer_,clockWhite_,clockBlack_,beginMove_,isMoveWhite_'.
                    ' from TGames_'.
                    ' where (result_ is null) and (beginMove_ <> 0) and (class_ <> \'B\') and (no_otpusk is null) and '.
                    '       (((idWGamer_ ='.$id_.') and (isMoveWhite_=\'Y\')) or'.
                    '        ((idBGamer_ ='.$id_.') and (isMoveWhite_=\'N\')))';
                $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                while($row_ =mysql_fetch_array($cursor_)){
                    if (!CGames_::endGameIfClockZero($row_['id_'])){
                        $s ='update TGames_ set';
                        if ($row_['isMoveWhite_'] =='Y')
                                $s .=' clockWhite_ ='.($row_['clockWhite_'] - ($curr_time - $row_['beginMove_'])).',';
                            else
                                $s .=' clockBlack_ ='.($row_['clockBlack_'] - ($curr_time - $row_['beginMove_'])).',';
                        $s .=' beginMove_ =0 where id_='.$row_['id_'];
                        if (!mysql_query($s,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0)) throw new Exception();
                    }
                } #while

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При остановке часов в партиях произошла ошибка.');
            }
        }#Stop_clock_

        protected static function Set_otpusk_($id_,$b_otpusk,$c_otpusk='',$e_otpusk=''){
            $connect_  =false;
            $transact_ =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s='update TGamers_ set';
                    if ($e_otpusk !=='') $s .=' e_otpusk ='.$e_otpusk.',';
                    if ($c_otpusk !=='') $s .=' c_otpusk ='.$c_otpusk.',';
                    $s .=' b_otpusk ='.(($b_otpusk != 0) ? $b_otpusk : 'null').' where id_='.$id_;
                if (!mysql_query($s,const_::$connect_) || (mysql_affected_rows(const_::$connect_) == 0)) throw new Exception();

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_) const_::Disconnect_();
            }catch (Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При изменении информации об отпуске произошла ошибка');
            }
        } #Set_otpusk_

#Активирует отпуск
        public static function Start_otpusk_($id_gamer_){
            $connect_  =false;
            $transact_ =false;
            $cursor_   =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                CUsers_::Stop_clock_($id_gamer_);
                $curr_time =time();
                CUsers_::Set_otpusk_($id_gamer_,$curr_time);

                $s ='select A.id_'.
                    ' from TGamers_ A'.
                    ' where (A.id_ <>'.$id_gamer_.') and '.
                    '       exists (select * from TGames_ '.
                    '                 where (result_ is null) and (((idWGamer_ = '.$id_gamer_.') and (idBGamer_ = A.id_)) or'.
                    '                                              ((idBGamer_ = '.$id_gamer_.') and (idWGamer_ = A.id_))))';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                while($row_ =mysql_fetch_array($cursor_)){
                    $id_=$row_['id_'];
                    $s =htmlspecialchars($_SESSION[SESSION_LOGIN_]).' взял(а) отпуск';
                    CUsers_::writeEvents($s,$id_);
                }#while
                mysql_free_result($cursor_); $cursor_ =false;

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_) const_::Disconnect_();
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При активации отпуска произошла ошибка.');
            }
        } #Start_otpusk

#Прерываю отпуск
        public static function Stop_otpusk_($id_gamer_){
            $connect_  =false;
            $transact_ =false;
            $cursor_   =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='select b_otpusk,c_otpusk from TGamers_ where id_='.$id_gamer_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                if (is_null($row_['b_otpusk'])) throw new Exception();
                if (is_null($row_['c_otpusk'])) throw new Exception();
                $b_otpusk=$row_['b_otpusk'];
                $c_otpusk=$row_['c_otpusk'];
                mysql_free_result($cursor_); $cursor_ =false;

                $curr_time =time();
                $c =$c_otpusk - ($curr_time - $b_otpusk); if ($c < 0) $c =0;
                if ($c ==0) $b =$b_otpusk + $c_otpusk; else $b =$curr_time;

                CUsers_::Start_clock_($id_gamer_,$b);
                CUsers_::Set_otpusk_($id_gamer_,0,$c);

                $s ='select A.id_'.
                    ' from TGamers_ A'.
                    ' where (A.id_ <>'.$id_gamer_.') and '.
                    '       exists (select * from TGames_ '.
                    '                 where (result_ is null) and (((idWGamer_ = '.$id_gamer_.') and (idBGamer_ = A.id_)) or'.
                    '                                              ((idBGamer_ = '.$id_gamer_.') and (idWGamer_ = A.id_))))';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                while($row_ =mysql_fetch_array($cursor_)){
                    $id_=$row_['id_'];
                    $s =htmlspecialchars($_SESSION[SESSION_LOGIN_]).' вышел(ла) из отпуска';
                    CUsers_::writeEvents($s,$id_);
                }#while
                mysql_free_result($cursor_); $cursor_ =false;

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_) const_::Disconnect_();
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('Прервать отпуск не удалось. Произошла ошибка.');
            }
       } #Stop_otpusk

#Проверяю окночание отпуска и продление
        public static function Check_otpusk($id_gamer){
            $connect_  =false;
            $transact_ =false;
            $cursor_   =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $days_30 =60*60*24*30;
                $curr_time =time();
                $s ='select e_otpusk,b_otpusk,c_otpusk from TGamers_ where id_='.$id_gamer;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $d =getdate();
                $b =mktime(0,0,0,1,1,$d['year']);
                $e =mktime(0,0,0,1,1,$d['year']+1);
                if (is_null($row_['e_otpusk'])) CUsers_::Set_otpusk_($id_gamer,0,$days_30,$e);
                    else if ($row_['e_otpusk'] < $curr_time)
                        if (is_null($row_['b_otpusk']))
                            CUsers_::Set_otpusk_($id_gamer,0,$days_30,$e);
                        elseif (($row_['c_otpusk'] - ($b-$row_['b_otpusk'])) <= 0){
                            CUsers_::Stop_otpusk_($id_gamer);
                            CUsers_::Set_otpusk_($id_gamer,0,$days_30,$e);
                        }else{
                            CUsers_::Set_otpusk_($id_gamer,$b,$days_30,$e);
                            if (($days_30 - ($curr_time-$b)) <=0)
                                CUsers_::Stop_otpusk_($id_gamer);
                        }
                    elseif (!is_null($row_['b_otpusk']) && (($row_['c_otpusk'] - ($curr_time-$row_['b_otpusk'])) <= 0))
                        CUsers_::Stop_otpusk_($id_gamer);
               mysql_free_result($cursor_); $cursor_ =false;

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_) const_::Disconnect_();
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении информации об отпуске произошла ошибка.');
            }
        }#Check_otpusk

#Возвращает true, если игрок находится в отпуске. False в противном случае.
        public static function Status_Otpusk($id_gamer_){
            $connect_  =false;
            $transact_ =false;
            $cursor_   =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='select b_otpusk from TGamers_ where id_='.$id_gamer_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $result_ =!is_null($row_['b_otpusk']);
                mysql_free_result($cursor_); $cursor_ =false;

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_) const_::Disconnect_();

                return $result_;
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении информации об отпуске произошла ошибка');
            }
        }#Status_Otpusk

#Возвращает количество время отпуска
        public static function Ostatok_Otpusk($id_gamer_){
            $connect_  =false;
            $transact_ =false;
            $cursor_   =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='select b_otpusk, c_otpusk from TGamers_ where id_='.$id_gamer_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $result_ =is_null($row_['c_otpusk']) ? 0 : $row_['c_otpusk'];
                if (!is_null($row_['b_otpusk']))
                    $result_ =$result_ - (time() -$row_['b_otpusk']);
                if ($result_ < 0) $result_=0;
                mysql_free_result($cursor_); $cursor_ =false;

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_) const_::Disconnect_();

                return $result_;
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception($e->getMessage());
            }
       }#Ostatok_Otpusk

#Генерирует элементы html информация об отпуске
		public static function html_info_otpusk($id_gamer_){
			$connect_  =false;
			$transact_ =false;
			try{
				ob_start(); #начинаю буферизацию
				if (!const_::$connect_)
					if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
				if (!const_::$isTransact_)
					if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $a =CUsers_::Ostatok_Otpusk($id_gamer_);
				echo('<DIV style="font-size: 12pt; font-style: normal; font-weight: normal; color : black; text-align: center; white-space:nowrap">'."\n");
				echo('Осталось отпуска: '.clockToStr($a).'&nbsp;');
				if (CUsers_::Status_Otpusk($id_gamer_))
					echo('(в отпуске)'."\n");
				elseif ($a > 0)
					echo('(не в отпуске)'."\n");
				echo('</DIV>'."\n");

				if ($transact_)
					if (const_::Commit_()) $transact_ =false; else throw new Exception();
				if ($connect_) const_::Disconnect_();
				ob_end_flush(); #завершаю буферизацию и передаю данные
			}catch (Exception $e){
				ob_end_clean(); #очищаю буфер и завершаю буферизацию
				if ($transact_) const_::Rollback_();
				if ($connect_) const_::Disconnect_();
				throw new Exception($e->getMessage());
			}
		}#html_info_otpusk
#--------------------------------------------------------------------------------------------
#Запись события
        public static function writeEvents($info_,$id_gamer_=0){
            $connect_  =false;
            $transact_ =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='insert into TInfo_(info_'.(($id_gamer_ !=0) ? ',id_gamer_' : '').')'.
                    ' values(\''.mysql_real_escape_string($info_,const_::$connect_).'\''.(($id_gamer_ !=0) ? ','. $id_gamer_ : '').')';
                $s =convert_cyr_string($s,'w','d');
                if (!mysql_query($s,const_::$connect_)) throw new Exception();

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
            }catch (Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception(($e->getMessage() !='') ? $e->getMessage() : 'При записи информации о событии произошла ошибка.');
            }
        } #writeEvents

#Проводит завершение партий, время которых истекло, снимает вызовы у которых тоже истекло время, проверяет состояние отпуска, закончился или нет
		public static function InitUserInfo($id_){
			$connect_  =false;
			$transact_ =false;
			try{
				if (!const_::$connect_)
					if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
				if (!const_::$isTransact_)
					if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

#удаляю вызовы, срок действия которых истёк
				$Calls_ = new CCalls_;
				$Calls_->connect_ =const_::$connect_;
				$Calls_->DelCallsEndTime();
#проверяю состояние отпуска
				CUsers_::Check_otpusk($id_);
#завершаю партии время которых просроченно
				Games::EndGames_ClockZero($Calls_->connect_);

				if ($transact_)
					if (const_::Commit_()) $transact_ =false; else throw new Exception();
				if ($connect_) const_::Disconnect_();
			}catch (Exception $e){
				if ($transact_) const_::Rollback_();
				if ($connect_) const_::Disconnect_();
				throw new Exception($e->getMessage());
			}
		}#InitUserInfo

        public static function Read_dhtml_($login_=''){
            $connect_ =false;
            $transact_ =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                if ($login_ =='') $login_ =$_SESSION[SESSION_LOGIN_];
                $s ='select dhtml_ from TGamers_ where login_ =\''.mysql_real_escape_string($login_,const_::$connect_).'\'';
                $s =convert_cyr_string($s,'w','d');
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                if ($row_) $result_ =($row_['dhtml_'] =='Y'); else $result_ =false;
                mysql_free_result($cursor_); $cursor_ =false;

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
                CUsers_::$last_value_read_dhtml_ =$result_;
                return $result_;
            }catch (Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении информации из базы данных произошла ошибка.');
            }
        } #Read_dhtml_

        public static function Read_scale_board(){
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='select scale_board from TGamers_ where login_ =\''.mysql_real_escape_string($_SESSION[SESSION_LOGIN_],const_::$connect_).'\'';
                $s =convert_cyr_string($s,'w','d');
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $result_ =is_null($row_['scale_board']) ?  1 : $row_['scale_board'];
                mysql_free_result($cursor_); $cursor_ =false;

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
                return $result_;
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении информации о масштабе доски произошла ошибка.');
            }
        } #Read_scale_board

        public static function Read_view_board($exception_on_error=true){
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='select figures_ from TGamers_ where id_ ='.$_SESSION[SESSION_ID_];
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $result_ =$row_['figures_'];
                mysql_free_result($cursor_); $cursor_ =false;

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
                return $result_;
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                if($exception_on_error)
                  throw new Exception('При чтении информации о способе отображения доски произошла ошибка.');
                else
                  return null;  
            }
        }#Read_view_board

        public static function Read_id_($login_){
          $connect_ =false;
          $transact_ =false;
          try{
            if (!const_::$connect_)
              if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

            if (!const_::$isTransact_)
              if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

            $s ='select id_ from TGamers_ where login_ =\''.mysql_real_escape_string($login_,const_::$connect_).'\'';
            $s =convert_cyr_string($s,'w','d');
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
            $row_ =mysql_fetch_array($cursor_);
            if ($row_) $result_ =$row_['id_']; else $result_ =0;
            mysql_free_result($cursor_); $cursor_ =false;

            if ($transact_)
              if (const_::Commit_()) $transact_ =false; else throw new Exception();
            if ($connect_)const_::Disconnect_();
            return $result_;
          }catch (Exception $e){
            if ($transact_) const_::Rollback_();
            if ($connect_) const_::Disconnect_();
            throw new Exception('При чтении информации из базы данных произошла ошибка.');
          }
        } #Read_id_
        
        public static function Read_last_id_infoLogins(){
          $connect_  =false;
          $transact_ =false;
          $cursor_   =false;
          try{
            if (!const_::$connect_)
              if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

            if (!const_::$isTransact_)
              if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

            $s ='select max(id_) as id_ from TInfoLogins_';
            $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
            $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
            $result_ =$row_['id_'];
            mysql_free_result($cursor_); $cursor_ =false;

            if ($transact_)
              if (const_::Commit_()) $transact_ =false; else throw new Exception();
            if ($connect_)const_::Disconnect_();
            return $result_;
          }catch (Exception $e){
            if ($cursor_) mysql_free_result($cursor_); 
            if ($transact_) const_::Rollback_();
            if ($connect_) const_::Disconnect_();
            throw new Exception('При чтении информации из базы данных произошла ошибка.');
          }
        } #Read_id_
        

		public static function ReadLogins_($id_){
			$connect_ =false;
			$transact_ =false;
			try{
#Получаю список логинов
				if (!const_::$connect_)
					if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

				if (!const_::$isTransact_)
					if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

				$s ='';
				for($i=0; $i < count($id_); $i++){
					if($s !=='') $s .=',';
					$s .=$id_[$i];
				} #for

				$q ='select login_ from TGamers_ where id_ in ('.$s.')';
				$cursor_=mysql_query($q,const_::$connect_); if (!$cursor_) throw new Exception();
				$result_ ='';
				while($row_ =mysql_fetch_array($cursor_)){
					if ($result_ !=='') $result_ .=',';
					$result_ .=convert_cyr_string($row_['login_'],'d','w');
				} #while
				mysql_free_result($cursor_); $cursor_ =false;
				if ($result_ ==='') throw new Exception();

				if ($transact_)
					if (const_::Commit_()) $transact_ =false; else throw new Exception();
				if ($connect_)const_::Disconnect_();
				return $result_;
			}catch (Exception $e){
				if ($transact_) const_::Rollback_();
				if ($connect_) const_::Disconnect_();
				throw new Exception($e->getMessage());
			}
		} #ReadLogins_

#Изменяет рейтинг игроков
        public static function СhangeRating($id_game_){
            global $countMoveForChangeRating_;
            $transact_ =false;
            $cursor_ =false;
            try{
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();
#получаю номер последнего хода
                $s ='select max(num_) as num_max from TMoves_ where idGame_='.$id_game_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                if (is_null($row_['num_max'])) $num_=0; else $num_=$row_['num_max'];
                mysql_free_result($cursor_); $cursor_ =false;
                if ($num_ >=$countMoveForChangeRating_){
#получаю результат и рейтинги игроков
                    $s ='select A.result_,A.idWGamer_,A.idBGamer_,A.gameIsRating_,A.class_,'.
                        '       B.ratingA_ as ratingA_W, C.ratingA_ as ratingA_B,'.
                        '       B.ratingB_ as ratingB_W, C.ratingB_ as ratingB_B,'.
                        '       B.ratingC_ as ratingC_W, C.ratingC_ as ratingC_B,'.
                        '       B.classA_ as classA_W, C.classA_ as classA_B,'.
                        '       B.classB_ as classB_W, C.classB_ as classB_B,'.
                        '       B.classA_ as classA_W, C.classA_ as classC_B'.
                        ' from TGames_ A, TGamers_ B, TGamers_ C'.
                        ' where (A.id_ ='.$id_game_.') and (B.id_=A.idWGamer_) and (C.id_=A.idBGamer_)';
                    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                    $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                    if (($row_['gameIsRating_'] =='Y') && !is_null($row_['result_'])){
#изменение рейтинга игрока idWGamer_
                        switch ($row_['class_']){
                            case 'A' : $Ra =$row_['ratingA_W'];
                                       $Rb =$row_['ratingA_B'];
                                       $class_ =$row_['classA_W'];
                                       break;
                            case 'B' : $Ra =$row_['ratingB_W'];
                                       $Rb =$row_['ratingB_B'];
                                       $class_ =$row_['classB_W'];
                                       break;
                            case 'C' : $Ra =$row_['ratingC_W'];
                                       $Rb =$row_['ratingC_B'];
                                       $class_ =$row_['classC_W'];
                                       break;
                            default :  throw new Exception();
                        }#switch
                        if ($row_['result_'] == 1) $Sa =1;
                            elseif ($row_['result_'] == 2) $Sa =0.5;
                            elseif ($row_['result_'] == 0) $Sa =0;
                            else throw new Exception();
                        $RW_new =const_::CalcRating($Ra,$Rb,$Sa,$class_);
#изменение рейтинга игрока idBGamer_
                        switch ($row_['class_']){
                            case 'A' : $Ra =$row_['ratingA_B'];
                                       $Rb =$row_['ratingA_W'];
                                       $class_ =$row_['classA_B'];
                                       break;
                            case 'B' : $Ra =$row_['ratingB_B'];
                                       $Rb =$row_['ratingB_W'];
                                       $class_ =$row_['classB_B'];
                                       break;
                            case 'C' : $Ra =$row_['ratingC_B'];
                                       $Rb =$row_['ratingC_W'];
                                       $class_ =$row_['classC_B'];
                                       break;
                            default :  throw new Exception();
                        }#switch
                        if ($row_['result_'] == 1) $Sa =0;
                            elseif ($row_['result_'] == 2) $Sa =0.5;
                            elseif ($row_['result_'] == 0) $Sa =1;
                            else throw new Exception();
                        $RB_new =const_::CalcRating($Ra,$Rb,$Sa,$class_);
#изменяю рейтинг idWGamer_
                        if (is_null($row_['idWGamer_'])) throw new Exception();
                        $id_ =$row_['idWGamer_'];
                        switch ($row_['class_']){
                            case 'A' : $s ='update TGamers_ set ratingA_ ='.$RW_new.' where id_='.$id_;
                                       if (!mysql_query($s,const_::$connect_)) throw new Exception();
                                       $s =$RW_new-$row_['ratingA_W']; if ($s >= 0) $s ='+'.$s;
                                       break;
                            case 'B' : $s ='update TGamers_ set ratingB_ ='.$RW_new.' where id_='.$id_;
                                       if (!mysql_query($s,const_::$connect_)) throw new Exception();
                                       $s =$RW_new-$row_['ratingB_W']; if ($s >= 0) $s ='+'.$s;
                                       break;
                            case 'C' : $s ='update TGamers_ set ratingC_ ='.$RW_new.' where id_='.$id_;
                                       if (!mysql_query($s,const_::$connect_)) throw new Exception();
                                       $s =$RW_new-$row_['ratingC_W']; if ($s >= 0) $s ='+'.$s;
                                       break;
                        }#switch
                        CUsers_::writeEvents('Изменение Вашего рейтинга '.$row_['class_'].': '.$s,$id_);
#изменяю рейтинг idBGamer_
                        if (is_null($row_['idBGamer_'])) throw new Exception();
                        $id_ =$row_['idBGamer_'];
                        switch ($row_['class_']){
                            case 'A' : $s ='update TGamers_ set ratingA_ ='.$RB_new.' where id_='.$id_;
                                       if (!mysql_query($s,const_::$connect_)) throw new Exception();
                                       $s =$RB_new-$row_['ratingA_B']; if ($s >= 0) $s ='+'.$s;
                                       break;
                            case 'B' : $s ='update TGamers_ set ratingB_ ='.$RB_new.' where id_='.$id_;
                                       if (!mysql_query($s,const_::$connect_)) throw new Exception();
                                       $s =$RB_new-$row_['ratingB_B']; if ($s >= 0) $s ='+'.$s;
                                       break;
                            case 'C' : $s ='update TGamers_ set ratingC_ ='.$RB_new.' where id_='.$id_;
                                       if (!mysql_query($s,const_::$connect_)) throw new Exception();
                                       $s =$RB_new-$row_['ratingC_B']; if ($s >= 0) $s ='+'.$s;
                                       break;
                        }#switch
                        CUsers_::writeEvents('Изменение Вашего рейтинга '.$row_['class_'].': '.$s,$id_);
                    }
                    mysql_free_result($cursor_); $cursor_ =false;
                }
                if ($transact_ && !const_::Commit_()) throw new Exception();
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                throw new Exception();
            }
        }#СhangeRating

        #$A_B_C: 1 - класс A, 2 - класс B, 3 - класс C
        public static function ReadClass_($id_,$A_B_C){
            $connect_  =false;
            $transact_ =false;
            $cursor_   = false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='select classA_,classB_,classC_ from TGamers_ where id_ ='.$id_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                switch ($A_B_C){
                    case 1: $result_ =$row_['classA_']; break;
                    case 2: $result_ =$row_['classB_']; break;
                    case 3: $result_ =$row_['classC_']; break;
                    default: throw new Exception();
                }#switch

                mysql_free_result($cursor_); $cursor_ =false;
                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
                return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении информации о классе произошла ошибка.');
            }
        } #ReadClass_

        #$A_B_C: 1 - класс A, 2 - класс B, 3 - класс C
        public static function SetClass_($id_,$A_B_C,$class_){
            $connect_  =false;
            $transact_ =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                switch ($A_B_C){
                    case 1: $s='update TGamers_ set classA_='.$class_.' where id_ ='.$id_; break;
                    case 2: $s='update TGamers_ set classB_='.$class_.' where id_ ='.$id_; break;
                    case 3: $s='update TGamers_ set classC_='.$class_.' where id_ ='.$id_; break;
                    default: throw new Exception();
                }#switch
                if (!mysql_query($s,const_::$connect_)) throw new Exception();

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
                return $result_;
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При изменении класса игрока произошла ошибка.');
            }
        }#SetClass_

        #Изменение класса $A_B_C: 1 - класс A8-A7, 2 - класс B8-B7, 3 - класс C8-C7
        #Проверяет класс игрока и количество завершенных партий
        public static function checkChangeClass8($id_,$A_B_C){
            global $countMoveForChangeClass8_;
            global $countGamesForChangeClass8_;

            $connect_  =false;
            $transact_ =false;
            $cursor_ =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

#проверяю класс игрока
                switch ($A_B_C){
                    case 1: $s='select classA_ from TGamers_ where id_ ='.$id_; $class_='A'; break;
                    case 2: $s='select classB_ from TGamers_ where id_ ='.$id_; $class_='B'; break;
                    case 3: $s='select classC_ from TGamers_ where id_ ='.$id_; $class_='C'; break;
                    default: throw new Exception();
                }#switch
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $s =$row_[0];
                mysql_free_result($cursor_); $cursor_ =false;
                if ($s == 8){
#проверяю количество завершенных партий
                    $s ='select count(*) as count_'.
                        ' from TGames_ A'.
                        ' where ((A.idWGamer_ ='.$id_.') or (A.idBGamer_ ='.$id_.')) and'.
                        '       (A.class_ =\''.$class_.'\') and (not A.result_ is null) and'.
                        '       exists (select * from TMoves_ where (idGame_ = A.id_) and (num_ >='.$countMoveForChangeClass8_.'))';
                    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                    $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                    $s =$row_['count_'];
                    mysql_free_result($cursor_); $cursor_ =false;
                    if ($s >=$countGamesForChangeClass8_)
#изменяю класс
                        CUsers_::SetClass_($id_,$A_B_C,7);
                } #if ($s==8)
                if ($transact_ && !const_::Commit_()) throw new Exception();
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                throw new Exception('При проверки необходимости изменить класс произошла ошибка');
            }
        } #checkChangeClass8

		#$A_B_C: 1 - рейтинг A, 2 - рейтинг B, 3 - рейтинг C
        public static function ReadRating_($id_,$A_B_C){
			$connect_  =false;
			$transact_ =false;
			$cursor_    =false;
			try{
				if (!const_::$connect_)
					if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

				if (!const_::$isTransact_)
					if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

				$s ='select ratingA_,ratingB_,ratingC_ from TGamers_ where id_='.$id_;
				$cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
				$row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                switch ($A_B_C){
                    case 1: $result_ =$row_['ratingA_']; break;
                    case 2: $result_ =$row_['ratingB_']; break;
                    case 3: $result_ =$row_['ratingC_']; break;
                    default: throw new Exception();
                }#switch

				mysql_free_result($cursor_); $cursor_ =false;
				if ($transact_)
					if (const_::Commit_()) $transact_ =false; else throw new Exception();
				if ($connect_)const_::Disconnect_();
				return $result_;
			}catch(Exception $e){
				if ($cursor_) mysql_free_result($cursor_);
				if ($transact_) const_::Rollback_();
				if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении информации об рейтинге произошла ошибка.');
			}
		} #ReadRating_

        public static function ReadAboutYou($id_){
            $connect_  =false;
            $transact_ =false;
            $cursor_    =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='select famil_,ima_,otchest_,country_,punkt_,'.
                          ' dayofmonth(date_birth) as day_birth,'.
                          ' month(date_birth) as month_birth,'.
                          ' year(date_birth) as year_birth,'.
                          ' last_connect_'.
                          ' from TGamers_ where id_='.$id_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                if (is_null($row_['famil_'])) $result_['famil_'] ='';
                  else $result_['famil_'] =trim(convert_cyr_string($row_['famil_'],'d','w'));
                if (is_null($row_['ima_'])) $result_['ima_'] ='';
                  else $result_['ima_'] =trim(convert_cyr_string($row_['ima_'],'d','w'));
                if (is_null($row_['otchest_'])) $result_['otchest_'] ='';
                  else $result_['otchest_'] =trim(convert_cyr_string($row_['otchest_'],'d','w'));
                if (is_null($row_['country_'])) $result_['country_'] ='';
                  else $result_['country_'] =trim(convert_cyr_string($row_['country_'],'d','w'));
                if (is_null($row_['punkt_'])) $result_['punkt_'] ='';
                  else $result_['punkt_'] =trim(convert_cyr_string($row_['punkt_'],'d','w'));
                if (is_null($row_['day_birth']) || is_null($row_['month_birth']) || is_null($row_['year_birth']))
                   $result_['date_birth'] ='';
                 else
                   $result_['date_birth'] =dateToStr_($row_['day_birth'],$row_['month_birth'],$row_['year_birth']);
                if (is_null($row_['last_connect_'])) $result_['last_connect_'] ='';
                 else{
                   $a =getdate($row_['last_connect_']);
                   $result_['last_connect_'] =sprintf("%02u-%02u-%04u %02u:%02u",
                                                      $a['mday'],$a['mon'],$a['year'],
                                                      $a['hours'],$a['minutes']);
                }

                mysql_free_result($cursor_); $cursor_ =false;
                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
                return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении регистационной информации произошла ошибка.');
			}
        }#ReadAboutYou

        public static function ReadMoveToE_Mail($id_){
            $connect_  =false;
            $transact_ =false;
            $cursor_    =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='select move_to_email_ from TGamers_ where id_='.$id_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                if (is_null($row_['move_to_email_'])) $result_ =false;
                  else $result_ =($row_['move_to_email_']=='Y');

                mysql_free_result($cursor_); $cursor_ =false;
                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
                return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении информации о e-mail произошла ошибка.');
            }
        }#ReadMoveToE_Mail

        public static function ReadE_Mail($id_){
            $connect_  =false;
            $transact_ =false;
            $cursor_    =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='select AES_DECRYPT(email_,\''.mysql_escape_string(KEY_CRIPT_EMAIL).'\') as email_'.
                    ' from TGamers_ where id_='.$id_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                if (is_null($row_['email_'])) $result_ ='';
                  else $result_ =trim(convert_cyr_string($row_['email_'],'d','w'));

                mysql_free_result($cursor_); $cursor_ =false;
                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
                return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении информации закладки e-mail произошла ошибка.');
            }
        }#ReadE_Mail

//Добавляет регистрационную информация, если команда insert выполнена успешно возвращается true,
//иначе возвращается false. Если ошибка распознанна - $this->lastError_ содержит код ошибки
		public static function InsRegInfo($login_,$password_){
			$s ='insert into TGamers_(login_,password_) values(';
			$s .='\''.mysql_escape_string($login_).'\',';
			$s .='\''.mysql_escape_string(md5($password_)).'\')';
			$s =convert_cyr_string($s,'w','d');

			if (!mysql_query($s,const_::$connect_))
				if (mysql_errno() == 1062)
						throw new ERegistrationError('Указанный логин уже занят.');
					else
						throw new Exception('При добавлении регистрационной информации произошла ошибка.');
		} #InsRegInfo

        public static function ChangePassword($password_){
            $connect_  =false;
            $transact_ =false;
            try{
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'update TGamers_ set'.
                   ' password_ =\''.mysql_escape_string(md5($password_)).'\''.
                   ' where id_='.$_SESSION[SESSION_ID_];
              $s =convert_cyr_string($s,'w','d');
              if (!mysql_query($s,const_::$connect_)) throw new Exception();
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При изменении пароля произошла ошибка.');
            }
        }#ChangePassword

        public static function ChangeAboutYou($famil_,$ima_,$otchest_,$date_birth,$country_,$punkt_){
            $connect_  =false;
            $transact_ =false;
            try{
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'update TGamers_ set'.
                   ' famil_='.(($famil_ == '') ? 'null' : '\''.mysql_escape_string($famil_).'\'').','.
                   ' ima_='.(($ima_ == '') ? 'null' : '\''.mysql_escape_string($ima_).'\'').','.
                   ' otchest_='.(($otchest_ == '') ? 'null' : '\''.mysql_escape_string($otchest_).'\'').','.
                   ' date_birth='.(($date_birth == '') ? 'null' : '\''.$date_birth.'\'').','.
                   ' country_='.(($country_ == '') ? 'null' : '\''.mysql_escape_string($country_).'\'').','.
                   ' punkt_='.(($punkt_ == '') ? 'null' : '\''.mysql_escape_string($punkt_).'\'').
                   ' where id_='.$_SESSION[SESSION_ID_];
              $s =convert_cyr_string($s,'w','d');
              if (!mysql_query($s,const_::$connect_)) throw new Exception();
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При изменении информации произошла ошибка.');
            }
        }#ChangeAboutYou

        public static function ChangeMoveToE_Mail($move_to_e_mail){
            $connect_  =false;
            $transact_ =false;
            try{
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'update TGamers_ set'.
                   ' move_to_email_='.($move_to_e_mail  ? '\'Y\'' : 'NULL').
                   ' where id_='.$_SESSION[SESSION_ID_];
              if (!mysql_query($s,const_::$connect_)) throw new Exception();
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При изменении информации закладки e-mail произошла ошибка.');
            }
        }#ChangeMoveToE_Mail

        public static function ChangeE_mail($e_mail_){
            $connect_  =false;
            $transact_ =false;
            try{
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'update TGamers_ set'.
                   ' email_='.(($e_mail_ == '') ? 'null' : 'AES_ENCRYPT(\'' . mysql_escape_string($e_mail_) . '\',\''.mysql_escape_string(KEY_CRIPT_EMAIL).'\')').
                   ' where id_='.$_SESSION[SESSION_ID_];
              $s =convert_cyr_string($s,'w','d');
              if (!mysql_query($s,const_::$connect_)) throw new Exception();
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При изменении e-mail произошла ошибка.');
            }
        }#ChangeE_mail

        public static function SendToEMail($message_,$e_mail_){
            $subject_ ='ChessAndMail';
            $header_ ='Content-type: text/plain; charset=windows-1251'."\r\n".
                      'From: ChessAndMail <no_replay@chessandmail.ru>'."\r\n";
            return mail($e_mail_,$subject_,$message_,$header_);
        }

        public static function ChangeDHTML($dhtml_){
            $connect_  =false;
            $transact_ =false;
            try{
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'update TGamers_ set'.
                   ' dhtml_=\''.($dhtml_ ? 'Y' : 'N').'\''.
                   ' where id_='.$_SESSION[SESSION_ID_];
              if (!mysql_query($s,const_::$connect_)) throw new Exception();
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При изменении информации произошла ошибка.');
            }
        }#ChangeE_mail

        public static function ChangeScale_board($scale_board_){
            $connect_  =false;
            $transact_ =false;
            try{
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'update TGamers_ set'.
                   ' scale_board='.(($scale_board_ == '') ? 'null' : $scale_board_).
                   ' where id_='.$_SESSION[SESSION_ID_];
              if (!mysql_query($s,const_::$connect_)) throw new Exception();
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При изменении масштаба доски произошла ошибка.');
            }
        }#ChangeScale_board

        public static function ChangeView_board($num_board_){
            $connect_  =false;
            $transact_ =false;
            try{
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'update TGamers_ set'.
                   ' figures_='.(($num_board_ == 0) ? 'null' : $num_board_).
                   ' where id_='.$_SESSION[SESSION_ID_];
              if (!mysql_query($s,const_::$connect_)) throw new Exception();
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При изменении внешнего вида доски произошла ошибка.');
            }
        }#ChangeView_board

        public static function ExistsPhoto($id_){
            $connect_  =false;
            $transact_ =false;
            try{
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'select id_ from TGamers_ where (id_='.$id_.') and (not photo_ is null)';
              $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              $row_ =mysql_fetch_array($cursor_);
              if ($row_) $result_=true; else $result_=false;

              mysql_free_result($cursor_);
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
              return $result_;
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении информации об фотографии произошла ошибка..');
            }
        }#ExistsPhoto

        public static function ChangePhoto($photo_){
            $connect_  =false;
            $transact_ =false;
            try{
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'update TGamers_ set'.
                   ' photo_ ='.(($photo_ == '') ? 'NULL' : '\'' . mysql_escape_string($photo_) . '\'').
                   ' where id_='.$_SESSION[SESSION_ID_];
              if (!mysql_query($s,const_::$connect_)) throw new Exception();
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При сохранении фотографии произошла ошибка.');
            }
        }#ChangePhoto

        public static function ReadPhoto($id_){
            $connect_  =false;
            $transact_ =false;
            $cursor_   =false;
            try{
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $result_ ='';

              $s = 'select photo_ from TGamers_ where id_='.$id_;
              $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
              if (!is_null($row_['photo_'])) $result_ =$row_['photo_'];
              mysql_free_result($cursor_); $cursor_ =false;
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
              return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении фотографии произошла ошибка.');
            }
        }#ReadPhoto

//Проверяет логин и пароль (в базе ищется человек с указанным логином и паролем)
	public static function CheckUser($login_,$password_){
          $cursor_   = false;
	  try{
	    $s ='select login_ from TGamers_ '.
	        ' where (login_ =\''.mysql_real_escape_string($login_,const_::$connect_).'\') and '.
	        '       (password_ =\''.mysql_real_escape_string(md5($password_),const_::$connect_).'\')';
	    $s =convert_cyr_string($s,'w','d');
	    $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('');
	    $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new EAutotenficError('Логин или пароль указан неверно.');

	    $s =convert_cyr_string($row_['login_'],'d','w');
	    mysql_free_result($cursor_); $cursor_ = false;
	    return $s;
          }catch(Exception $e){
	    if ($cursor_) mysql_free_result($cursor_);
	    if ($e instanceof EAutotenficError)
	     throw new EAutotenficError(($e->getMessage()=='') ? 'При проверки логина и пароля произошла ошибка.' : $e->getMessage());
	    else
	     throw new Exception(($e->getMessage()=='') ? 'При проверки логина и пароля произошла ошибка.' : $e->getMessage());
          }
        } #CheckUser

        public static function SetLast_Visit($login_){
          try{
            $s ='update TGamers_ set last_visit_=NOW() where login_=\''.mysql_real_escape_string($login_,const_::$connect_).'\'';
            $s =convert_cyr_string($s,'w','d');
            if (!mysql_query($s,const_::$connect_)) throw new Exception();
            $s ='insert into TInfoLogins_(id_gamer_,type_)'.
                 ' select id_,1 from TGamers_ where login_=\''.mysql_real_escape_string($login_,const_::$connect_).'\'';
            $s =convert_cyr_string($s,'w','d');
            if (!mysql_query($s,const_::$connect_)) throw new Exception();
          }catch (Exception $e){
            throw new Exception('При входе на сайт произошла ошибка.');
          }
        }#SetLast_Visit

        public static function accept_call($id_call_){
            $cursor_ =false;
            try{
#Получаю информацию о вызове
                $s ='select reglament_,id_gamerMakeCall_,id_gamer_,gamerMakeCallIsWhite_,gameIsRating_,class_'.
                    ' from TCallsToGame_ where (id_='.$id_call_.')';
                $cursor_ =mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('При чтении информации о вызове произошла ошибка.');
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception('Вызов не найден.');
#Проверяю вызов
                if($row_['id_gamerMakeCall_']==$_SESSION[SESSION_ID_])
                    throw new Exception('Вы не можете принять этот вызов');
                if (!is_null($row_['id_gamer_']) && ($row_['id_gamer_'] !=$_SESSION[SESSION_ID_]))
                    throw new Exception('Вы не можете принять этот вызов');
#Начинаю партию
                $reglament_ =$row_['reglament_'];
                if ($row_['gamerMakeCallIsWhite_'] =='Y'){
                    $idWGamer_ = $row_['id_gamerMakeCall_'];
                    $idBGamer_ = $_SESSION[SESSION_ID_];
                }else{
                    $idBGamer_ = $row_['id_gamerMakeCall_'];
                    $idWGamer_ = $_SESSION[SESSION_ID_];
                }
                $clockWhite_ =GetBeginTime($reglament_);
                if ($clockWhite_ ==0) throw new Exception('Регламент указан неверно. Сообщите, пожалуйста, об этой ошибке разработчику.');
                $clockBlack_ = $clockWhite_;
                if ($row_['class_'] != 'B'){
                  CUsers_::Check_otpusk($idWGamer_);
                  if (CUsers_::Status_Otpusk($idWGamer_)) $beginMove_ =0; else $beginMove_ =time();
                }else $beginMove_ =time();
                $gameIsRating_ = $row_['gameIsRating_'];
                $class_ =$row_['class_'];
                mysql_free_result($cursor_); $cursor_ =false;
                $s = 'insert into TGames_(idWGamer_,idBGamer_,reglament_,gameIsRating_,clockWhite_,'.
                                         'clockBlack_,beginMove_,isMoveWhite_,class_)'.
                     ' values('.$idWGamer_.','.$idBGamer_.','.$reglament_.',\''.$gameIsRating_.'\','.$clockWhite_.','.
                              $clockBlack_.','.$beginMove_.',\'Y\',\''.$class_.'\')';
                if (!mysql_query($s,const_::$connect_)) throw new Exception('При принятии вызова произошла ошибка.');
                $s ='delete from TCallsToGame_ where id_ ='.$id_call_;
                if (!mysql_query($s,const_::$connect_)) throw new Exception('При принятии вызова произошла ошибка.');

            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception($e->getMessage());
            }
        }#accept_call

//Удаляет вызов
        public static function decline_call($id_){
            $cursor_ =false;
            try{
#Получаю информацию о вызове
                $s ='select id_gamerMakeCall_,id_gamer_ from TCallsToGame_ where id_='.$id_;
                $cursor_ =mysql_query($s,const_::$connect_);
                if (!$cursor_) throw new Exception('При чтении информации о вызове произошла ошибка.');
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception('Вызов не найден.');
#Проверяю возможность отклонения/снятия
                if (($row_['id_gamerMakeCall_'] != $_SESSION[SESSION_ID_]) &&
                    (is_null($row_['id_gamer_']) || ($row_['id_gamer_'] !=$_SESSION[SESSION_ID_])))
                  throw new Exception('Вы не можете отклонить/удалить вызов.');

                mysql_free_result($cursor_); $cursor_ =false;
                $s = 'delete from TCallsToGame_ where id_='.$id_;
                if (!mysql_query($s,const_::$connect_)) throw new Exception('При удалении вызова произошла ошибка');
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception($e->getMessage());
            }
        } #decline_call

#завершает партии у которых истекло время
		public static function end_games_clock_zero(){
			$transact_ =false;
			$cursor_ =false;
			try{
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

				$time_ =time();
				$s ='select A.id_,A.isMoveWhite_'.
					   ' from TGames_ A'.
					   ' where ((A.idWGamer_ ='.$_SESSION[SESSION_ID_].') or '.
                       '             (A.idBGamer_ ='.$_SESSION[SESSION_ID_].')) and'.
                       '             (A.result_ is null) and (A.beginMove_ <> 0) and'.
					   '             (((A.isMoveWhite_=\'Y\') and (A.clockWhite_ -'.$time_.'+ A.beginMove_ <=0)) or'.
					   '              ((A.isMoveWhite_=\'N\') and (A.clockBlack_ -'.$time_.'+ A.beginMove_ <=0)))';
				$cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
				while($row_=mysql_fetch_array($cursor_)){
					if ($row_['isMoveWhite_']=='Y')
							CGames_::EndGame($row_['id_'],1,0);
						 else
							CGames_::EndGame($row_['id_'],2,1);
				} #while
				mysql_free_result($cursor_); $cursor_ =false;
                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();

			}catch(Exception $e){
				if ($cursor_) mysql_free_result($cursor_);
				if ($transact_) const_::Rollback_();
				throw new Exception('При завершении партии произошла ошибка.');
			}
		}#end_games_clock_zero

//удаляю вызовы время действия которых истекло
        public static function del_calls_end_time(){
          $transact_ =false;
          $cursor_ =false;
          try{
            $s ='select A.id_,A.id_gamerMakeCall_,A.id_gamer_'.
                '   from TCallsToGame_ A'.
                '   where ((A.id_gamerMakeCall_ ='.$_SESSION[SESSION_ID_].') or '.
                '          (A.id_gamer_ ='.$_SESSION[SESSION_ID_].')) and '.
                '          (NOW() > adddate(A.timeMake_,interval A.callEnd_ day))';
            $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
            while($row_=mysql_fetch_array($cursor_))
              CUsers_::decline_call($row_['id_']);

            mysql_free_result($cursor_); $cursor_ =false;
            if ($transact_)
              if (const_::Commit_()) $transact_ =false; else throw new Exception();

          }catch(Exception $e){
            if ($cursor_) mysql_free_result($cursor_);
            if ($transact_) const_::Rollback_();
            throw new Exception('При удалении вызова произошла ошибка.');
          }
        }#del_calls_end_time

        public static function exists_non_read_personal_message(){
            $result_ =false;
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
              if (!isset($_SESSION[SESSION_ID_])) throw new Exception();
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();
#получаю номер последнего прочитанного сообщения
              $s = 'select id_last_read_person_message from TGamers_ where id_ ='.$_SESSION[SESSION_ID_];
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              $row_=mysql_fetch_array($cursor_);
              $id_last_read_person_message = $row_['id_last_read_person_message'];
              mysql_free_result($cursor_); $cursor_ =false;
#получаю номер последнего персонального сообщения
              $s ='select max(id_) as id_ from TPersonalMessage_ where to_id_gamer_='.$_SESSION[SESSION_ID_];
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              if (($row_=mysql_fetch_array($cursor_)) && !is_null($row_['id_']))
                if (is_null($id_last_read_person_message) || ($id_last_read_person_message < $row_['id_']))
                  $result_ =true;

              mysql_free_result($cursor_); $cursor_ =false;
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
              return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                return false;
            }
        }#exists_non_read_personal_message

        public static function exists_non_read_guest_book(){
            $result_ =false;
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
              if (!isset($_SESSION[SESSION_ID_])) throw new Exception();
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();
#получаю номер последнего прочитанного сообщения
              $s = 'select id_last_read_guest_book from TGamers_ where id_ ='.$_SESSION[SESSION_ID_];
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              $row_=mysql_fetch_array($cursor_);
              $id_last_read_guest_book = $row_['id_last_read_guest_book'];
              mysql_free_result($cursor_); $cursor_ =false;
#получаю номер последнего персонального сообщения
              $s ='select max(id_) as id_ from TGuestBook_';
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              if ($row_=mysql_fetch_array($cursor_))
                if ((is_null($id_last_read_guest_book) && !is_null($row_['id_'])) ||
                    ($id_last_read_guest_book < $row_['id_']))
                  $result_ =true;

              mysql_free_result($cursor_); $cursor_ =false;
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
              return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                return false;
            }
        }#exists_non_read_guest_book

        public static function exists_non_read_info(){
            $result_ =false;
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
              if (!isset($_SESSION[SESSION_ID_])) throw new Exception();
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();
#получаю номер последнего прочитанного сообщения
              $s = 'select id_last_read_info_ from TGamers_ where id_ ='.$_SESSION[SESSION_ID_];
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              $row_=mysql_fetch_array($cursor_);
              $id_last_read_info_ = $row_['id_last_read_info_'];
              mysql_free_result($cursor_); $cursor_ =false;
#получаю номер последнего информационного сообщения
              $s ='select max(id_) as id_ from TInfo_ where (id_gamer_ is null) or (id_gamer_='.$_SESSION[SESSION_ID_].')';
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              if ($row_=mysql_fetch_array($cursor_))
                if ((is_null($id_last_read_info_) && !is_null($row_['id_'])) ||
                    ($id_last_read_info_ < $row_['id_']))
                  $result_ =true;

              mysql_free_result($cursor_); $cursor_ =false;
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
              return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                return false;
            }
        }#exists_non_read_info

        public static function exists_games_wait_move(){
            $result_ =false;
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
              if (!isset($_SESSION[SESSION_ID_])) throw new Exception();
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();
              $s = 'select count(*) as count_'.
                   ' from TGames_'.
                   ' where (((idWGamer_ ='.$_SESSION[SESSION_ID_].') and (isMoveWhite_=\'Y\')) or'.
                   '            ((idBGamer_ ='.$_SESSION[SESSION_ID_].') and (isMoveWhite_=\'N\'))) and'.
                   '           (result_ is null)';
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              if ($row_=mysql_fetch_array($cursor_))
                if (!is_null($row_['count_']) && ($row_['count_'] > 0))
                  $result_ =true;
              mysql_free_result($cursor_); $cursor_ =false;
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
              return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                return false;
            }
        }#exists_non_read_info

        public static function exists_calls_class_A_C(){
            $result_ =false;
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
              if (!isset($_SESSION[SESSION_ID_])) throw new Exception();
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();
              $s = 'select count(*) as count_'.
                   ' from TCallsToGame_'.
                   ' where ((id_gamer_ is null) or (id_gamer_ ='.$_SESSION[SESSION_ID_].')) and'.
                   '       ((class_=\'A\') or (class_=\'C\'))';
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              if ($row_=mysql_fetch_array($cursor_))
                if (!is_null($row_['count_']) && ($row_['count_'] > 0))
                  $result_ =true;
              mysql_free_result($cursor_); $cursor_ =false;
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
              return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                return false;
            }
        }#exists_calls_class_A_C

        public static function exists_calls_class_B(){
            $result_ =false;
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
              if (!isset($_SESSION[SESSION_ID_])) throw new Exception();
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();
              $s = 'select count(*) as count_'.
                   ' from TCallsToGame_'.
                   ' where ((id_gamer_ is null) or (id_gamer_ ='.$_SESSION[SESSION_ID_].')) and'.
                   '       (class_=\'B\')';
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              if ($row_=mysql_fetch_array($cursor_))
                if (!is_null($row_['count_']) && ($row_['count_'] > 0))
                  $result_ =true;
              mysql_free_result($cursor_); $cursor_ =false;
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
              return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                return false;
            }
        }#exists_calls_class_B

        public static function set_id_last_read_guest_book($id_gamer_,$value_){
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
              if (is_null($id_gamer_)) $id_gamer_ =$_SESSION[SESSION_ID_];
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'select id_last_read_guest_book'.
                      ' from TGamers_'.
                      ' where id_ ='.$id_gamer_;
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              $row_=mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
              if (is_null($row_['id_last_read_guest_book']) || ($row_['id_last_read_guest_book'] < $value_)){
                $s='update TGamers_ set  id_last_read_guest_book='.$value_.' where id_='.$id_gamer_;
                if (!mysql_query($s,const_::$connect_)) throw new Exception();
              }
              mysql_free_result($cursor_); $cursor_ =false;
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
            }
        }#set_id_last_read_guest_book

        public static function set_id_last_read_info($id_gamer_,$value_){
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
              if (is_null($id_gamer_)) $id_gamer_ =$_SESSION[SESSION_ID_];
              if (!const_::$connect_)
                if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
              if (!const_::$isTransact_)
                if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

              $s = 'select id_last_read_info_'.
                      ' from TGamers_'.
                      ' where id_ ='.$id_gamer_;
              $cursor_ =mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
              $row_=mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
              if (is_null($row_['id_last_read_info_']) || ($row_['id_last_read_info_'] < $value_)){
                $s='update TGamers_ set  id_last_read_info_='.$value_.' where id_='.$id_gamer_;
                if (!mysql_query($s,const_::$connect_)) throw new Exception();
              }
              mysql_free_result($cursor_); $cursor_ =false;
              if ($transact_)
                if (const_::Commit_()) $transact_ =false; else throw new Exception();
              if ($connect_)const_::Disconnect_();
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
            }
        }#set_id_last_read_info
        
        public static function Read_firstBoxInfo(&$id_){
            $connect_ =false;
            $transact_ =false;
            $cursor_ =false;
            try{
                $result_ ='';
                
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='select A.info_,A.id_'.
                    '  from TBoxInfo_ A '.
                    '  where (A.date_b <=NOW()) and (NOW() <=A.date_e) and'.
                    '        not exists (select * from TReadBoxInfo_ where (id_box_info_=A.id_) and (id_gamer_='.$_SESSION[SESSION_ID_].'))'.
                    '  order by A.date_b';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); 
                if ($row_){
                  $result_ =trim($row_['info_']);
                  $id_ =$row_['id_'];
                } 
                mysql_free_result($cursor_); $cursor_ =false;

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
                return $result_;
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При чтении информации о произошла ошибка.');
            }
        }#Read_firstBoxInfo
        
        public static function setRead_firstBoxInfo($id_boxinfo){
            $connect_ =false;
            $transact_ =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception();

                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

                $s ='insert into TReadBoxInfo_(id_gamer_,id_box_info_)'.
                     ' values('.$_SESSION[SESSION_ID_].','.$id_boxinfo.')';
                if(!mysql_query($s,const_::$connect_)) throw new Exception();

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception();
                if ($connect_)const_::Disconnect_();
            }catch (Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                throw new Exception('При отметке о прочтении информации о произошла ошибка.');
            }
        }#setRead_firstBoxInfo
    }#CUsers_

?>