<?php
    require_once('const_.php');
    require_once('rule_game_.php');

#-------------------------------------------------------------------------------------------------
    class CPageMakeTask_{        public static $board_ =array();
        public static $start_ ='ход белых';
        public static $result_ =1;
        public static $answer_ ='';
        public static $id_task_ ='';
        public static $check_task_ =false;

        public static function read_info_task($id_task){
          $cursor_ =false;
          try{
            $s ='select result_,start_,answer_,check_task_ from TTasks_ where id_ ='.$id_task;
            $cursor_=mysql_query($s,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о задаче произошла ошибка.');
            $row_ =mysql_fetch_array($cursor_);
            if (!$row_) throw new Exception('Задача не найдена.');
            CPageMakeTask_::$start_ =$row_['start_'];
            CPageMakeTask_::$result_ =$row_['result_'];
            if (is_null($row_['answer_']))
              CPageMakeTask_::$answer_ ='';
             else
              CPageMakeTask_::$answer_ =convert_cyr_string($row_['answer_'],'d','w');
            CPageMakeTask_::$check_task_ =(!is_null($row_['check_task_']) && ($row_['check_task_'] =='Y'));

            mysql_free_result($cursor_); $cursor_ =false;
          }catch(Exception $e){
            if ($cursor_) mysql_free_result($cursor_);
            throw new Exception($e->getMessage());
          }
        }#read_position_task

        public static function clear_board(){
          CPageMakeTask_::$board_['A'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
          CPageMakeTask_::$board_['B'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
          CPageMakeTask_::$board_['C'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
          CPageMakeTask_::$board_['D'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
          CPageMakeTask_::$board_['E'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
          CPageMakeTask_::$board_['F'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
          CPageMakeTask_::$board_['G'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
          CPageMakeTask_::$board_['H'] =array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'');
        }#clear_board

        public static function read_position_task($id_task){          $cursor_ =false;
          try{            $s ='select colorPiece_,piece_,cell_ from TPositionTask_ where id_task_ ='.$id_task;
            $cursor_=mysql_query($s,const_::$connect_);
            if (!$cursor_) throw new Exception('При чтении информации о задаче произошла ошибка.');
            while ($row_ =mysql_fetch_array($cursor_)){              CPageMakeTask_::$board_[$row_['cell_']{0}][$row_['cell_']{1}] =$row_['colorPiece_'].$row_['piece_'];            }#while
            mysql_free_result($cursor_); $cursor_ =false;
          }catch(Exception $e){            if ($cursor_) mysql_free_result($cursor_);
            throw new Exception($e->getMessage());
          }        }#read_position_task
        public static function save_to_TTasks_(&$id_){           if (($_POST['start_'] !='W') && ($_POST['start_'] !='B'))
             throw new Exception('Параметр ход белых/ход черных указан не верно.');
           $start_ =$_POST['start_'];

           if (!ctype_digit($_POST['result_']) || ($_POST['result_'] < 1) || ($_POST['result_'] >6))
             throw new Exception('Тип задачи указан не верно.');
           $result_ =$_POST['result_'];

           $answer_ =$_POST['answer_'];

           $check_task_ =isset($_POST['check_task_']);

           if ($id_==='')
             $s ='insert into TTasks_(result_,start_,answer_,check_task_,id_gamer_maker_)'.
                '  values('.$result_.',\''.$start_.'\',\''.mysql_escape_string($answer_).'\','.
                           ($check_task_ ? '\'Y\'' : 'NULL').','.$_SESSION[SESSION_ID_].')';
           else
             $s ='update TTasks_ set'.
                 ' result_ ='.$result_.','.
                 ' start_ =\''.$start_.'\','.
                 ' answer_ =\''.mysql_escape_string($answer_).'\','.
                 ' check_task_ ='.($check_task_ ? '\'Y\'' : 'NULL').
                 ' where id_='.$id_;
           $s =convert_cyr_string($s,'w','d');
           if (!mysql_query($s,const_::$connect_)) throw new Exception('При сохранении задачи произошла ошибка.');
           if ($id_==='') $id_=mysql_insert_id(const_::$connect_);
        }#save_to_TTasks_

        public static function save_to_TPositionTask_($id_){#удаляю информацию о позиции             $s ='delete from TPositionTask_ where id_task_ ='.$id_;
             if (!mysql_query($s,const_::$connect_)) throw new Exception('При сохранении задачи произошла ошибка.');
#произвожу размор параметра position_
             $s =trim($_POST['position_']);
             if ($s !=''){/*Грамматика S -> A число
             A -> F буква
             F -> C фигура
             C -> w | b | P w| P b
             P -> S ,
             число -> 1..8
             буква -> A..H
             фигура -> k, q, p, r, b, n
   цель: S*/
               $position_ =array();
               $state_ ='H'; $i=0; $j=strlen($s); $k=0;
               while ($i < $j){                 switch ($state_){                    case 'H': case 'P':
                      if (($s{$i} =='w') || ($s{$i} =='b')){                        $position_[$k]['colorPiece_'] = $s{$i};
                        $state_ ='C';
                      }else throw new Exception('Информация о позиции не соответствует формату.');
                      break;
                    case 'C':
                      if (($s{$i} =='k') || ($s{$i} =='q') || ($s{$i} =='p') ||
                         ($s{$i} =='r') || ($s{$i} =='b') || ($s{$i} =='n')){                        $position_[$k]['piece_'] = $s{$i};
                        $state_ ='F';                      }else throw new Exception('Информация о позиции не соответствует формату.');
                      break;
                    case 'F':
                      if (($s{$i} >='A') && ($s{$i} <='H')){
                        $position_[$k]['cell_'] = $s{$i};
                        $state_ ='A';
                      }else throw new Exception('Информация о позиции не соответствует формату.');
                      break;
                    case 'A':
                       if (($s{$i} >='1') && ($s{$i} <='8')){
                         $position_[$k]['cell_'] .= $s{$i};
                         $state_ ='S';
                       }else throw new Exception('Информация о позиции не соответствует формату.');
                       break;
                    case 'S':
                       if ($s{$i} ==','){
                         $state_ ='P';
                         $k++;
                       }else throw new Exception('Информация о позиции не соответствует формату.');
                       break;
                 }#switch
                 $i++;               }#while
               if ($state_ !='S') throw new Exception('Информация о позиции не соответствует формату.');
#сохраняю информацию
               for ($i =0; $i < count($position_); $i++){                  $s ='insert into TPositionTask_(id_task_,colorPiece_,piece_,cell_)'.
                      ' value('.$id_.',\''.$position_[$i]['colorPiece_'].'\',\''.$position_[$i]['piece_'].'\','.
                              '\''.$position_[$i]['cell_'].'\')';
                  if (!mysql_query($s,const_::$connect_)) throw new Exception('При сохранении задачи произошла ошибка.');
               }#for
             }        }#save_to_TPositionTask_
        public static function Body_(){#Масштаб доски
            $k_=0.5;
            $l_=round(68*$k_);  settype($l_,"integer"); $w_  ='width="'.$l_.'"'; $h_ ='height="'.$l_.'"';
            $l_=round(19*$k_);  settype($l_,"integer"); $w_l ='width="'.$l_.'"';
            $l_=round(18*$k_);  settype($l_,"integer"); $w_r ='width="'.$l_.'"';
            $l_=round(20*$k_);  settype($l_,"integer"); $h_t  ='height="'.$l_.'"';

            if (CPageMakeTask_::$id_task_ !='')
              $result_ ='<FORM action="tasks_.php?add=make_&task_='.CPageMakeTask_::$id_task_.'" method="POST">'."\n";
             else
              $result_ ='<FORM action="tasks_.php?add=make_" method="POST">'."\n";

            $result_ .='<TABLE style ="border: none">'."\n".
                       '  <COL span="2">'."\n".
                       '  <TR><TD style="vertical-align: top">'."\n".
                       '  <TABLE style ="border: none" cellspacing="0" cellpadding="0">'."\n".
                       '     <COL span="10">'."\n";
//верхняя часть доски
            $result_ .='<TR><TD><IMG '.$w_l.' '.$h_t.' style="border:none; vertical-align: bottom" src="'.const_::$catalog_image_fugure.'board_tl.jpg"/></TD>'."\n";
            for($i_=1; $i_<9; $i_++)
                $result_ .='<TD><IMG '.$w_.' '.$h_t.' style="border:none; vertical-align: bottom" src="'.const_::$catalog_image_fugure.'board_top.jpg"/></TD>'."\n";
            $result_ .='<TD><IMG '.$w_r.' '.$h_t.' style="border:none; vertical-align: bottom" src="'.const_::$catalog_image_fugure.'board_tr.jpg"/></TD>'."\n".
                       '</TR>'."\n";
#доска
            for($i_=8; $i_>=1; $i_--){
                $result_ .='<TR>'."\n".
                           '<TD><IMG '.$w_l.' '.$h_.' style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.'board_'.$i_.'.jpg"/></TD>'."\n";
                for($j_='A'; $j_<='H'; $j_=chr(ord($j_)+1)){
                    $result_ .='<TD>';
                    $result_ .='<IMG id="'.$j_.$i_.'" '.$w_.' '.$h_.' onclick="task_.onClickCell(\''.$j_.$i_.'\');"';
                    $result_ .=' style="border:none; vertical-align: top; cursor:pointer" src="'.const_::$catalog_image_fugure.
                               CPageMakeTask_::$board_[$j_][$i_].
                               CRuleGame::getColorBoard($j_.$i_).
                               '.jpg"/>';
                    $result_ .='</TD>';
                }//for $j_
                $result_ .='<TD><IMG  '.$w_r.' '.$h_.'  style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.'board_right.jpg"/></TD>'."\n".
                           '</TR>';
            }//for $i_
//нижняя часть доски
            $result_ .='<TR><TD><IMG  '.$w_l.' '.$h_t.' style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.'board_bl.jpg"/></TD>'."\n";
            for($i_='a'; $i_<='h'; $i_=chr(ord($i_)+1))
                $result_ .='<TD><IMG  '.$w_.' '.$h_t.' style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.'board_'.$i_.'.jpg"/></TD>'."\n";
            $result_ .='<TD><IMG  '.$w_r.' '.$h_t.' style="border:none; vertical-align: top" src="'.const_::$catalog_image_fugure.'board_br.jpg"/></TD>'."\n".
                       '</TR>'."\n";

            $result_ .='</TABLE>'."\n".
                       '</TD>'."\n";

            $result_ .='    <TD style="vertical-align: top">'.
                       '       <br>'.
                       '       <TABLE style ="border: none; margin-left: 10px;" cellspacing="0" cellpadding="0">';
            if ($_SESSION[SESSION_ID_] ==1){              $result_ .='       <TR>'.
                         '         <TD colspan="2">'.
                         '            <INPUT type="checkbox" name="check_task_" '.(CPageMakeTask_::$check_task_ ? 'checked' : '').'> задача одобрена'."\n".
                         '         </TD>'.
                         '       </TR>'.
                        '         <TR><TD colspan="2">&nbsp;</TD></TR>';            }
            $result_ .='         <TR>'.
                       '           <TD>'.
                       '              <INPUT type="button" value="очистить доску" onclick="task_.clearBoard()">'."\n".
                       '           </TD>'.
                       '           <TD>'.
                       '              <INPUT type="button" value="очистить ячейку" onclick="task_.onClearCell()">'."\n".
                       '           </TD>'.
                       '         </TR>'.
                       '       </TABLE>'.
                       '       <TABLE style ="border: none; margin-left: 10px; margin-right: auto" cellspacing="0" cellpadding="0">'.
                       '         <TR><TD colspan="6">&nbsp;</TD></TR>'.
                       '         <TR>'.
                       '           <TD><IMG onclick="task_.onClickF(\'wp\');" '.$w_.' '.$h_.' id="wp" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'wpw.jpg"/></TD>'.
                       '           <TD><IMG onclick="task_.onClickF(\'wn\');" '.$w_.' '.$h_.' id="wn" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'wnw.jpg"/></TD>'.
                       '           <TD><IMG onclick="task_.onClickF(\'wb\');" '.$w_.' '.$h_.' id="wb" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'wbw.jpg"/></TD>'.
                       '           <TD><IMG onclick="task_.onClickF(\'wr\');" '.$w_.' '.$h_.' id="wr" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'wrw.jpg"/></TD>'.
                       '           <TD><IMG onclick="task_.onClickF(\'wq\');" '.$w_.' '.$h_.' id="wq" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'wqw.jpg"/></TD>'.
                       '           <TD><IMG onclick="task_.onClickF(\'wk\');" '.$w_.' '.$h_.' id="wk" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'wkw.jpg"/></TD>'.
                       '         </TR>'.
                       '         <TR>'.
                       '           <TD><IMG onclick="task_.onClickF(\'bp\');" '.$w_.' '.$h_.' id="bp" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'bpw.jpg"/></TD>'.
                       '           <TD><IMG onclick="task_.onClickF(\'bn\');" '.$w_.' '.$h_.' id="bn" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'bnw.jpg"/></TD>'.
                       '           <TD><IMG onclick="task_.onClickF(\'bb\');" '.$w_.' '.$h_.' id="bb" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'bbw.jpg"/></TD>'.
                       '           <TD><IMG onclick="task_.onClickF(\'br\');" '.$w_.' '.$h_.' id="br" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'brw.jpg"/></TD>'.
                       '           <TD><IMG onclick="task_.onClickF(\'bq\');" '.$w_.' '.$h_.' id="bq" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'bqw.jpg"/></TD>'.
                       '           <TD><IMG onclick="task_.onClickF(\'bk\');" '.$w_.' '.$h_.' id="bk" style="border:none; cursor:pointer" src="'.const_::$catalog_image_fugure.'bkw.jpg"/></TD>'.
                       '         </TR>'.
                       '         <TR><TD colspan="6">&nbsp;</TD></TR>'.
                       '       </TABLE>'.
                       '       <TABLE style ="border: none; margin-left: 10px; margin-right: auto" cellspacing="0" cellpadding="0">'.
                       '         <TR>'.
                       '            <TD>'.
                       '               <SELECT name="start_">'."\n".
                       '                 <OPTION value="W" '.(CPageMakeTask_::$start_ == 'W' ? 'selected' : '').'>ход белых</OPTION>'."\n".
                       '                 <OPTION value="B" '.(CPageMakeTask_::$start_ == 'B' ? 'selected' : '').'>ход черных</OPTION>'."\n".
                       '               </SELECT>'."\n".
                       '            </TD>'.
                       '            <TD>'.
                       '               <SELECT name="result_">'."\n".
                       '                 <OPTION value="1" '.(CPageMakeTask_::$result_ == 1 ? 'selected' : '').'>мат в два хода</OPTION>'."\n".
                       '                 <OPTION value="2" '.(CPageMakeTask_::$result_ == 2 ? 'selected' : '').'>мат в три хода</OPTION>'."\n".
                       '                 <OPTION value="3" '.(CPageMakeTask_::$result_ == 3 ? 'selected' : '').'>мат в четры хода</OPTION>'."\n".
                       '                 <OPTION value="4" '.(CPageMakeTask_::$result_ == 4 ? 'selected' : '').'>мат в пять ходов</OPTION>'."\n".
                       '                 <OPTION value="5" '.(CPageMakeTask_::$result_ == 5 ? 'selected' : '').'>выигрыш</OPTION>'."\n".
                       '                 <OPTION value="6" '.(CPageMakeTask_::$result_ == 6 ? 'selected' : '').'>ничья</OPTION>'."\n".
                       '               </SELECT>'."\n".
                       '            </TD>'.
                       '         </TR>'.
                       '       </TABLE><BR>'.
                       '       <TABLE style ="border: none; margin-left: 10px; margin-right: auto" cellspacing="0" cellpadding="5px">'.
                       '         <TR>'.
                       '           <TD><IMG id="fp_" style="border:none; cursor:pointer" src="Image/fwp.png"/></TD>'.
                       '           <TD><IMG id="fn_" style="border:none; cursor:pointer" src="Image/fwn.png"/></TD>'.
                       '           <TD><IMG id="fb_" style="border:none; cursor:pointer" src="Image/fwb.png"/></TD>'.
                       '           <TD><IMG id="fr_" style="border:none; cursor:pointer" src="Image/fwr.png"/></TD>'.
                       '           <TD><IMG id="fq_" style="border:none; cursor:pointer" src="Image/fwq.png"/></TD>'.
                       '           <TD><IMG id="fk_" style="border:none; cursor:pointer" src="Image/fwk.png"/></TD>'.
                       '         </TR>'.
                       '       </TABLE>'.
                       '    </TD>'.
                       '  </TR>'."\n".
                       '</TABLE>'."\n".
                       '<TEXTAREA rows="20" style="width:100%" name="answer_">'.CPageMakeTask_::$answer_.'</TEXTAREA>'.
                       '<INPUT type="hidden" id="position_" name="position_">'.
                       '<DIV style="text-align:right">'.
                       '  <INPUT type="submit" name="save_" value="сохранить" onclick="task_.position_info();">'.
                       '  <INPUT type ="submit" name="view_task_" value="просмотр задачи" onclick="task_.position_info();">'.
                       '  <INPUT type ="submit" name="view_answer_" value="просмотр решения" onclick="task_.position_info();">'.
                       '  <INPUT type="submit" value="разместить">'.
                       '</DIV>'.
                       '</FORM>';
            return $result_;
        }#Body_
    }#CPageMakeTask_
?>
