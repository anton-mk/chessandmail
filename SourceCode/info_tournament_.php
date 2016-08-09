<?php
    require_once('const_.php');
    require_once('Users.php');

    class CInfo_tournament_{
        #[][0] - �����
        #[][1..n]['id'] - id ������
        #[][1..n]['result'] - ���������
        #[][n+1] - ��������� ����
        #[][n+2][1] - ������ ������� �����
        #[][n+2][2] - ������ ������� �����
        public static $table_ =array();
        protected static $class_;
        protected static $reglament_;

        public static function outQuestionAddSelf($id_){
            CPage_::QuestionPage('����������� ���� ������� ������� ������� � �������.',
                                 'MainPage.php?link_=Tournament&id_='.$id_,
                                 'MainPage.php?link_=Tournament&id_='.$id_.'&add_=self');
        }


        protected static function mayPlayInTournament($id_){
            $cursor_ =false;
            try{
#�������� ������������� �������
                $s ='select A.cGamers_, count(B.id_tournament_) as count_,A.class_'.
                    ' from TTournaments_ A left join TMembersTournament_ B on A.id_=B.id_tournament_'.
                    ' where A.id_='.$id_.
                    ' group by A.cGamers_,A.class_';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $result_ =($row_['cGamers_']+0) > ($row_['count_']+0);
                $a=is_null($row_['class_']) ? '' : $row_['class_'];
                mysql_free_result($cursor_); $cursor_=false;
#��������, ��� ����� ��� �� ������ ������� � �������
                if ($result_){
                    $s ='select count(*) as count_'.
                        ' from TMembersTournament_ A'.
                        ' where (A.id_tournament_ ='.$id_.') and (A.id_gamer_ ='.$_SESSION[SESSION_ID_].')';
                    $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                    $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                    $result_ =($row_['count_'] ==0);
                    mysql_free_result($cursor_); $cursor_=false;
                }
#�������� ������ �������
                if ($result_){
                    $classA_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],1);
                    $classB_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],2);
                    $classC_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],3);
                    $result_ =($a=='') ||
                              ($a ==('A'.$classA_)) ||
                              ($a ==('B'.$classB_)) ||
                              ($a ==('C'.$classC_));
                }

                return $result_;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('��� ������ ���������� �� ������� ��������� ������.');
            }
        }#mayPlayInTournament

        public static function GetInfo($id_){
           global $reglaments_;
           try{
                $cursor_ =false;
#�������� table_
                $s ='select cGamers_,class_,reglament_ from TTournaments_ where id_='.$id_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $qGamers =$row_['cGamers_'];
                CInfo_tournament_::$class_=$row_['class_'];
                CInfo_tournament_::$reglament_ =$reglaments_['reglament'.$row_['reglament_'].'_'];
                mysql_free_result($cursor_); $cursor=false;

                $a=0;
                $s ='select A.num_,B.login_ '.
                    ' from TMembersTournament_ A,TGamers_ B '.
                    ' where (A.id_gamer_ =B.id_) and (A.id_tournament_='.$id_.')'.
                    ' order by A.num_ ASC';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                while ($row_ =mysql_fetch_array($cursor_)){
                    if (($row_['num_'] > $qGamers) || ($a+1 != $row_['num_'])) throw new Exception();
                    CInfo_tournament_::$table_[][0] =convert_cyr_string($row_['login_'],'d','w');
                    $a++;
                } #while
                while($a++ < $qGamers) CInfo_tournament_::$table_[][0] ='';

                for($i=0; $i<$qGamers; $i++){
                    for($j=1; $j<=$qGamers; $j++){
                        CInfo_tournament_::$table_[$i][$j]['id'] ='';
                        CInfo_tournament_::$table_[$i][$j]['result'] ='';
                    } #for $j
                    CInfo_tournament_::$table_[$i][$qGamers+1] =0;
                    CInfo_tournament_::$table_[$i][$qGamers+2][1] ='';
                    CInfo_tournament_::$table_[$i][$qGamers+2][2] ='';
                } #for $i
                mysql_free_result($cursor_); $cursor_=false;
#�������� table_
                $s ='select B.id_,B.result_,C.num_ as numW,D.num_ as numB'.
                    ' from TGamesTournament_ A, TGames_ B, TMembersTournament_ C,TMembersTournament_ D'.
                    ' where (A.id_tournament_='.$id_.') and (A.id_game = B.id_) and'.
                    '       (B.idWGamer_=C.id_gamer_) and (B.idBGamer_=D.id_gamer_) and'.
                    '       (C.id_tournament_=A.id_tournament_) and (D.id_tournament_=A.id_tournament_)';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                while ($row_ =mysql_fetch_array($cursor_)){
                    if (($row_['numW'] < 0) || ($row_['numW'] > $qGamers) || ($row_['numB'] < 0) || ($row_['numB'] > $qGamers))
                        throw new Exception();
                    CInfo_tournament_::$table_[$row_['numW']-1][$row_['numB']]['id']=$row_['id_'];
                    CInfo_tournament_::$table_[$row_['numB']-1][$row_['numW']]['id']=$row_['id_'];
                    if ($row_['result_'] =='0'){
                        CInfo_tournament_::$table_[$row_['numW']-1][$row_['numB']]['result'] =0;
                        CInfo_tournament_::$table_[$row_['numB']-1][$row_['numW']]['result'] =1;
                    }elseif ($row_['result_'] ==1){
                        CInfo_tournament_::$table_[$row_['numW']-1][$row_['numB']]['result'] =1;
                        CInfo_tournament_::$table_[$row_['numB']-1][$row_['numW']]['result'] =0;
                    }elseif ($row_['result_'] ==2){
                        CInfo_tournament_::$table_[$row_['numW']-1][$row_['numB']]['result'] =0.5;
                        CInfo_tournament_::$table_[$row_['numB']-1][$row_['numW']]['result'] =0.5;
                    }
                }#while
                mysql_free_result($cursor_); $cursor_=false;
#����������� �����
                for($i=0; $i < $qGamers; $i++){
                    $a=0;
                    for($j=1; $j <=$qGamers; $j++)
                        $a +=(CInfo_tournament_::$table_[$i][$j]['result'] !='') ? CInfo_tournament_::$table_[$i][$j]['result'] : 0;
                    CInfo_tournament_::$table_[$i][$qGamers+1] =$a;
                } #for $i
#��������� �����
                for($i=0; $i < $qGamers; $i++){
                    CInfo_tournament_::$table_[$i][$qGamers+2][1] =1;
                    CInfo_tournament_::$table_[$i][$qGamers+2][2] =0;
                    for($j=0; $j < $qGamers; $j++)
                        if ($i != $j)
                            if (CInfo_tournament_::$table_[$i][$qGamers+1] < CInfo_tournament_::$table_[$j][$qGamers+1])
                                CInfo_tournament_::$table_[$i][$qGamers+2][1] +=1;
                            elseif (CInfo_tournament_::$table_[$i][$qGamers+1] == CInfo_tournament_::$table_[$j][$qGamers+1])
                                CInfo_tournament_::$table_[$i][$qGamers+2][2] +=1;
                } #for $i
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('��� ������ ���������� � ������� ��������� ������.');
            }
        }#GetInfo

        protected static function BodyInfoTournaments($id_){
           CInfo_tournament_::GetInfo($id_);
           $is_may_play = CInfo_tournament_::mayPlayInTournament($id_);

           $result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 16pt; color: white;'.
                                 'text-decoration: none; font-weight: normal">'."\n".
                        '<DIV style="text-align: center">'."\n".
                              '��������� �������'."\n".
                        '</DIV>'."\n".
                        '<DIV style="font-size: 14pt; text-align: center">'."\n".
                          '�����: '.CInfo_tournament_::$class_.'<BR>'."\n".
                          '���������: '.CInfo_tournament_::$reglament_."\n".
                        '</DIV><BR>'."\n".
                     '</SPAN>'."\n".
                     '<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                  'font-size: 12pt; color: black;'.
                                  'text-decoration: none; font-weight: normal">'."\n";

            $style_table='border-top: 1px solid black; border-left: 1px solid black; border-right: none; border-bottom: none;'.
                         'margin-left: auto; margin-right: auto';
            $style_head='border-top: none; border-left: none; border-right: 1px solid black; border-bottom: 1px solid black;'.
                        'margin: 0px; padding: 0px 5px 0px 5px; background-color: silver; text-align: center';
            $style_body='border-top: none; border-left: none; border-right: 1px solid black; border-bottom: 1px solid black;'.
                        'margin: 0px; padding: 0px 5px 0px 5px';
            $style_body_emty='border-top: none; border-left: none; border-right: 1px solid black; border-bottom: 1px solid black;'.
                             'margin: 0px; padding: 0px 5px 0px 5px; background-color: silver';
            $style_body_leader=$style_body.'; color: red';

            $qGamers=count(CInfo_tournament_::$table_); #��������� ������������ ���������� ������� � ������� (���������� �����)

            $result_ .='<FORM action="MainPage.php?link_=Tournament&id_='.$id_.'&add_=question" method="POST">'."\n".
                       '    <TABLE style ="border: none; margin-left: auto; margin-right: auto" cellspacing="0" cellpadding="0">'."\n".
                       '        <COL span="1">'."\n".
                       '        <TR><TD>'."\n".
                       '              <TABLE cellspacing="3">'."\n".
                       '                 <COL span="'.($qGamers+4).'">'."\n";
#���������
            $result_ .='                 <TR>'."\n".
                       '                    <TD class ="table_head_1" colspan="2">&nbsp;</TD>'."\n";
            for($i=1; $i <=$qGamers; $i++)
                $result_ .='                <TD class ="table_head_1">'.$i.'</TD>'."\n";
            $result_ .='                    <TD class ="table_head_1">����</TD>'."\n".
                       '                    <TD class ="table_head_1">�����</TD>'."\n".
                       '                 </TR>'."\n";
#����
            for($i=0; $i <$qGamers; $i++){
                $result_ .='<TR>'."\n".
                           '<TD class="table_body_1">'.($i+1).'</TD>'."\n";
                if (CInfo_tournament_::$table_[$i][0] !='')
                        $s ='<A href="MainPage.php?link_=about_gamer&login_='.urlencode(CInfo_tournament_::$table_[$i][0]).'">'.htmlspecialchars(CInfo_tournament_::$table_[$i][0]).'</A>';
                    else
                        $s ='&nbsp;';
                $result_ .='<TD class="table_body_1">'.$s.'</TD>'."\n";

                for($j=1; $j <=$qGamers; $j++){
                    if ($i==$j-1)
                        $result_ .='<TD class="table_head_1">&nbsp;</TD>'."\n";
                    else{
                        $s =(CInfo_tournament_::$table_[$i][$j]['result'] === 0.5) ? '1/2' : CInfo_tournament_::$table_[$i][$j]['result'];
                        if ($s ==='') $s ='&nbsp;';
                        if (CInfo_tournament_::$table_[$i][$j]['id'] != '')
                            $s ='<A href="MainPage.php?link_=game&id='.CInfo_tournament_::$table_[$i][$j]['id'].'">'.$s.'</A>';
                        $result_ .='<TD class="table_body_1">'.$s.'</TD>'."\n";
                    }
                }#for $j
                $isLeader =(CInfo_tournament_::$table_[$i][$qGamers+2][1] + CInfo_tournament_::$table_[$i][$qGamers+2][2]) <4;
                $result_ .='<TD class="table_body_1" '.($isLeader ? 'style="color:white"' : '').'>'.((CInfo_tournament_::$table_[$i][$qGamers+1] !=0) ? CInfo_tournament_::$table_[$i][$qGamers+1] : '&nbsp;').'</TD>'."\n";
                $s =CInfo_tournament_::$table_[$i][$qGamers+2][1];
                if (CInfo_tournament_::$table_[$i][$qGamers+2][2] !=0)
                    $s.='-'.(CInfo_tournament_::$table_[$i][$qGamers+2][1] + CInfo_tournament_::$table_[$i][$qGamers+2][2]);
                $result_ .='<TD class="table_body_1" '.($isLeader ? 'style="color:white"' : '').'>'.($s).'</TD>'."\n";
                $result_ .='</TR>'."\n";
            }#for $i

            $result_ .='</TABLE>'."\n";

            $result_ .='</TD></TR>'."\n";
            if ($is_may_play){
                $result_ .='<TR><TD style="border: none; text-align: left; padding: 5px 0px 0px 0px">'."\n".
                           '		<INPUT type="submit" value="������� �������" name="ButtonPost">'.
                           '	</TD></TR>'."\n";
            }
            $result_ .='</TABLE>'."\n".
                       '</FORM>'."\n".
                       '</SPAN>'."\n";
            return $result_;
        }#BodyInfoTournaments

        public static function AddGamer($id_){
            $cursor_ =false;
            try{
#������� �������� cGamers_ �� ������� ��������
                $s ='select cGamers_,firstColorWhite_,reglament_,class_ from TTournaments_ where id_ ='.$id_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                $cGamers_ =$row_['cGamers_'];
                if (!($cGamers_ > 1)) throw new Exception();
                $first_color = ($row_['firstColorWhite_'] == 'Y');
                $reglament_ =$row_['reglament_'];
                $class_='';
                if (!is_null($row_['class_'])) $class_ =$row_['class_']{0};
                mysql_free_result($cursor_); $cursor_ =false;
#�������� �������� num_ (����� ��������� �������)
                $s ='select max(num_) as max_num_ from TMembersTournament_ where id_tournament_='.$id_;
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_); if (!$row_) throw new Exception();
                if (is_null($row_['max_num_'])) $num_ =1; else $num_ =$row_['max_num_'] +1;
                if ($num_ > $cGamers_) throw new Exception();
                mysql_free_result($cursor_); $cursor_ =false;
#�������� ������ ��������� �������
                $s ='insert into TMembersTournament_(num_,id_tournament_,id_gamer_)'.
                    ' values('.$num_.','.$id_.','.$_SESSION[SESSION_ID_].')';
                if (!mysql_query($s,const_::$connect_)) throw new Exception();
#������ ������
                $s ='select num_,id_gamer_ from TMembersTournament_ where (id_tournament_ ='.$id_.') and (num_ <>'.$num_.')';
                $cursor_=mysql_query($s,const_::$connect_);	if (!$cursor_) throw new Exception();
                while($row_ =mysql_fetch_array($cursor_)){
#��������� ����
                    if ($num_ < $row_['num_']){
                        $a =$num_; $b =$row_['num_'];
                        $a_id_ =$_SESSION[SESSION_ID_]; $b_id_ =$row_['id_gamer_'];
                    }else{
                        $a =$row_['num_']; $b =$num_;
                        $a_id_ =$row_['id_gamer_']; $b_id_ =$_SESSION[SESSION_ID_];
                    }
                    if (((($a % 2) !=0) && (($b % 2) ==0)) || ((($a % 2) ==0) && (($b % 2) !=0))) $color_ =$first_color; else $color_ =!$first_color;
                    if ($color_){
                        $idWGamer_ =$a_id_; $idBGamer_ =$b_id_;
                    }else{
                        $idBGamer_ =$a_id_; $idWGamer_ =$b_id_;
                    }
#������� ������
                    CUsers_::Check_otpusk($idWGamer_);
                    if (CUsers_::Status_Otpusk($idWGamer_)) $time_ =0; else $time_ =time();
                    $s ='insert into TGames_(idWGamer_,idBGamer_,reglament_,clockWhite_,clockBlack_,beginMove_,isMoveWhite_,gameIsRating_,class_)'.
                        ' values('.$idWGamer_.','.$idBGamer_.','.$reglament_.','.GetBeginTime($reglament_).','.GetBeginTime($reglament_).','.
                                 $time_.',\'Y\',\'Y\','.(trim($class_) != '' ? '\''.$class_.'\'' : 'null').')';
                    if (!mysql_query($s,const_::$connect_)) throw new Exception();
#�������� ���������� � ������� ������ �������
                    $id_game_ =mysql_insert_id(const_::$connect_);
                    $s ='insert into TGamesTournament_(id_tournament_,id_game) values('.$id_.','.$id_game_.')';
                    if (!mysql_query($s,const_::$connect_)) throw new Exception();
                }#while
                mysql_free_result($cursor_); $cursor_ =false;
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception('��� ���������� � ������ ������ ��������� ������.');
            }
        } #AddGamer

        public static function MakeMenuMainPage(){
            $i =0;
            if (isset($_SESSION[SESSION_LINK_ESC_INFO_TOURNAMENT])){
                CPage_::$menu_[$i]['link'] = $_SESSION[SESSION_LINK_ESC_INFO_TOURNAMENT];
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

                $message_='';
                if (!isset($_GET['add_'])){
                  $body_ =CInfo_tournament_::BodyInfoTournaments($id_);
                  $link_esc_game ='MainPage.php?link_=Tournament&id_='.$id_;
                }elseif ($_GET['add_'] =='self'){
                    if (CInfo_tournament_::mayPlayInTournament($id_)){
                        CInfo_tournament_::AddGamer($id_);
                        $body_ =CInfo_tournament_::BodyInfoTournaments($id_);
                        $link_esc_game ='MainPage.php?link_=Tournament&id_='.$id_;
                    }else $message_ ='������ �� ���� ������� � ������� ��������.';
                }

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception('��� ���������� ���������� ��������� ������.');
                if ($connect_)const_::Disconnect_();

                CPage_::$header_ ='<DIV id="text_login_">'.
                                                 '  �����: '.$_SESSION[SESSION_LOGIN_].'<BR>'.
                                                 '  �����: A'.$classA_.'/B'.$classB_.'/C'.$classC_.
                                                 '  �������: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'<BR>'.
                                                 '</DIV>'   .
                                                 '<DIV id="text_header_">'.
                                                 '  ������ �'.$id_.
                                                 '</DIV>';
                if ($message_ !='')
                  CPage_::MessagePage($message_,'MainPage.php?link_=Tournament&id_='.$id_);
                elseif (!isset($_GET['add_']) ||($_GET['add_'] !='question')){
                  CInfo_tournament_::MakeMenuMainPage();
                  CPage_::$body_ =$body_;
                  CPage_::MakePage();
                }else CInfo_tournament_::outQuestionAddSelf($id_);

                if ($link_esc_game !='')
                    $_SESSION[SESSION_LINK_ESC_GAME]=$link_esc_game;
            }catch(Exception $e){
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
                CPage_::PageErr();
            }#try
        }#MakePage
    }#CInfo_tournament_
?>
