<?php
	require_once('const_.php');
	require_once('Users.php');

//инициализация сессии
	session_name(NAME_SESSION_);
	session_start();

	class CCommentGame_{
#comments_[]['comment'] - комментарий,
#comments_[]['isWhite'] - true создали белые, falseе чёрные
#comments_[]['id_'] - id_
        protected static $comments_ =array();

        public static function ReadComments($id_){
            $cursor_ =false;
            try{
                $s ='select id_,comment_,isWhite_ from TCommentsGame_ where id_game_='.$id_.' order by id_ desc';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception('При чтении комментариев произошла ошибка.');
                while ($row_ =mysql_fetch_array($cursor_)){
                    CCommentGame_::$comments_[]['comment']=trim(convert_cyr_string($row_['comment_'],'d','w'));
                    CCommentGame_::$comments_[count(CCommentGame_::$comments_)-1]['isWhite'] = ($row_['isWhite_'] == 'Y');
                    CCommentGame_::$comments_[count(CCommentGame_::$comments_)-1]['id_'] = $row_['id_'];
                } //while
                mysql_free_result($cursor_);
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception($e->getMessage());
            }
        } #ReadComments

        public static function WriteComment($comment_,$isWhite,$id_){
            if (strlen($comment_) > 200) $comment_ =substr($comment_,1,200);
            $s ='insert into TCommentsGame_(id_game_,comment_,isWhite_)'.
                ' values('.$id_.','.
                           '\''.mysql_escape_string($comment_).'\','.
                           ($isWhite ? '\'Y\'' : '\'N\'').
                         ')';
            $s =convert_cyr_string($s,'w','d');

            if (!mysql_query($s,const_::$connect_)) throw new Exception('При записи комментария произошла ошибка.');
        }//WriteComment

        public static function ReadLogins($id_,&$l_w,&$l_b){
            $cursor_ =false;
            try{
                $s ='select B.login_ as login_white,C.login_ as login_black'.
                    '  from TGames_ A, TGamers_ B, TGamers_ C'.
                    '  where (A.id_='.$id_.') and (A.idWGamer_ = B.id_) and (A.idBGamer_ =C.id_)';
                $cursor_=mysql_query($s,const_::$connect_); if (!$cursor_) throw new Exception();
                $row_ =mysql_fetch_array($cursor_);
                $l_w =trim(convert_cyr_string($row_['login_white'],'d','w'));
                $l_b =trim(convert_cyr_string($row_['login_black'],'d','w'));
                mysql_free_result($cursor_);
            }catch (Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                throw new Exception($e->getMessage());
            }
        }//ReadLogins

        protected static function Header_main(){
            echo('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">'."\n");
            echo('<HTML>'."\n");
            echo('	<HEAD>'."\n");
            echo('		<META http-equiv="Content-Type" content="text/html; charset=windows-1251">'."\n");
            echo('		<META name="Author" content="Колосовский Антон Михайлович">'."\n");

            echo('		<LINK href="styles/chess.css" rel="stylesheet" type="text/css">'."\n");
            echo('	</HEAD>'."\n");
            echo('	<BODY class="body_frame_comments_game">'."\n");
        }//Header_main

        protected static function Header_list(){
            echo('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">'."\n");
            echo('<HTML>'."\n");
            echo('	<HEAD>'."\n");
            echo('		<META http-equiv="Content-Type" content="text/html; charset=windows-1251">'."\n");
            echo('		<META name="Author" content="Колосовский Антон Михайлович">'."\n");

            echo('		<LINK href="styles/chess.css" rel="stylesheet" type="text/css">'."\n");
            echo('	</HEAD>'."\n");
            echo('	<BODY class="body_list_comments_game">'."\n");
        }//Header_list


        protected static function Footer_(){
            echo('	</BODY>'."\n");
            echo('</HTML>'."\n");
        }

        public static function ShowComments($l_w,$l_b,$id_){
            $id_last_=-1;
            CCommentGame_::Header_list();
            echo('<SPAN class="text_comments_game" id="list_comments">'."\n");
            for($i=0; $i < count(CCommentGame_::$comments_); $i++){
                if ($i==0) $id_last_ =CCommentGame_::$comments_[$i]['id_'];
                echo('<DIV style="color: blue; text-decoration: underline">'."\n");
                echo(htmlspecialchars(CCommentGame_::$comments_[$i]['isWhite'] ? $l_w : $l_b)."\n");
                echo('</DIV>'."\n");
                echo('<DIV>'."\n");
                echo(htmlspecialchars(CCommentGame_::$comments_[$i]['comment'])."\n");
                echo('</DIV>'."\n");
            }//for
            echo('</SPAN>'."\n");

            if (CUsers_::$last_value_read_dhtml_){
                echo('<SCRIPT type="text/javascript" src="scripts/json2.js"></SCRIPT>'."\n".
                     '<SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n".
                     '<SCRIPT type="text/javascript" src="scripts/control_comments.js"></SCRIPT>'."\n".
                     '<SCRIPT type="text/javascript">'."\n".
                     '  var o_comments = null;'."\n".
                     '  window.onload =function(){ '."\n".
                     '     o_comments = new cl_control_comments_game('.$id_.');'."\n");
                if ($id_last_ !=-1)
                  echo('     o_comments.last_id_='.$id_last_.";\n");
                echo('     o_comments.login_white=\''.addslashes($l_w).'\';'."\n".
                     '     o_comments.login_black=\''.addslashes($l_b).'\';'."\n".
                     '     o_comments.init();'."\n".
                     '  }'."\n".
                     '</SCRIPT>'."\n");
            }

            CCommentGame_::Footer_();
        }//ShowComments

        public static function ShowError_($str){
            CCommentGame_::Header_list();
            echo('<SPAN class="text_comments_game">'."\n");
            if ($str != '')
                echo('<DIV style="color:red">'.htmlspecialchars($str).'</DIV>'."\n");
            echo('</SPAN>'."\n");
            CCommentGame_::Footer_();
		}//ShowError_

        public static function ShowMain($id_){
            CCommentGame_::Header_main();
            echo('<IFRAME style="border: 1px solid black; width: 170px; height: 230px" frameborder="0" src="CommentsGamePage.php?id='.$id_.'&list=yes"></IFRAME>'."\n");
            echo('<SPAN class="text_comments_game">'."\n");
            echo('	<FORM name="form_" action ="CommentsGamePage.php?id='.$id_.'" method="post">'."\n");
            if (CUsers_::$last_value_read_dhtml_){
              echo('	<DIV style="text-align:left">'."\n");
              echo('		осталось&nbsp;'."\n");
              echo('		<INPUT type="text" name="chars_" size="4" value="200" readonly>'."\n");
              echo('	</DIV>'."\n");
            }
            echo('	<TEXTAREA rows="3" cols="17" name="sayComment" '.(CUsers_::$last_value_read_dhtml_ ? 'onkeyup="CheckLengthMessage()"' : '').'></TEXTAREA>'."\n");
            echo('	<INPUT type="submit" name="say" value="Сказать">'."\n");
            echo('	</FORM>'."\n");
            echo('</SPAN>'."\n");
            if (CUsers_::$last_value_read_dhtml_){
                echo('<SCRIPT type="text/javascript">'."\n");
                echo('	function CheckLengthMessage(){'."\n");
                echo('		if (document.form_.sayComment.value.length > 200)'."\n");
                echo('			document.form_.sayComment.value =document.form_.sayComment.value.substring(0,200);'."\n");
                echo('		document.form_.chars_.value = 200 - document.form_.sayComment.value.length;'."\n");
                echo('	}'."\n");
                echo('</SCRIPT>'."\n");
            }
            CCommentGame_::Footer_();
        }//ShowMain

	} //CCommentGame_

    $connect_ =false;
    $transact_ =false;
    try{
       if (!isset($_SESSION[SESSION_LOGIN_]) || !isset($_SESSION[SESSION_ID_]))
           throw new Exception('Вход на сайт не выполнен.');

       if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) throw new Exception('номер партии указан неверно.');
       $id_ =$_GET['id'];

       if (const_::SetConnect_()) $connect_ =true; else throw new Exception('При соединении с базой данных произошла ошибка.');
       if (const_::StartTransaction_()) $transact_ =true; else throw new Exception('При соединении с базой данных произошла ошибка.');

       $l_w ='';
       $l_b ='';
       CCommentGame_::ReadLogins($id_,$l_w,$l_b);
       CUsers_::Read_dhtml_();
       if (!isset($_GET['list'])){
            if (isset($_POST['say']) && isset($_POST['sayComment']) && (trim($_POST['sayComment']) !='') &&
                (($_SESSION[SESSION_LOGIN_] === $l_w) || ($_SESSION[SESSION_LOGIN_] === $l_b)))
                CCommentGame_::WriteComment(trim($_POST['sayComment']),$l_w===$_SESSION[SESSION_LOGIN_],$id_);
           CCommentGame_::ShowMain($id_);
       }else{
           if(($_SESSION[SESSION_LOGIN_] === $l_w) || ($_SESSION[SESSION_LOGIN_] === $l_b)){
             CCommentGame_::ReadComments($id_);
             CCommentGame_::ShowComments($l_w,$l_b,$id_);
           }
       }

       if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
       const_::Disconnect_();
    }catch (Exception $e){
       if ($transact_) const_::Rollback_();
       if ($connect_) const_::Disconnect_();
       CCommentGame_::ShowError_($e->getMessage());
    }
?>

