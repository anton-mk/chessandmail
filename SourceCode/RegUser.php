<?php
  require_once('const_.php');
  require_once('Users.php');
  require_once('info_.php');

//инициализация сессии
  session_name(NAME_SESSION_);
  session_start();

  class CNewUserPage_{
    public static function BodyNewUser($login_){
      CPage_::$body_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                   'font-size: 14pt; color: white;'.
                                   'text-decoration: none; font-weight: normal">'."\n";
      if (CPage_::$text_error_ !='')
        CPage_::$body_ .='<DIV style="text-align:center">'."\n".
                            CPage_::$text_error_."\n".
                         '</DIV>'."\n";
      CPage_::$body_ .='  <TABLE style="margin-left: auto; margin-right: auto">'."\n".
                       '    <COL span="2">'."\n".
                       '    <TR>'."\n".
                       '       <TD><LABEL for="login_">имя</LABEL></TD>'."\n".
                       '       <TD><INPUT type="text" id="login_" name="login_" value="'.$login_.'"></TD>'."\n".
                       '    </TR>'."\n".
                       '    <TR>'."\n".
                       '       <TD><LABEL for="password_">пароль</LABEL></TD>'."\n".
                       '       <TD><INPUT type="password" id="password_" name="password_"></TD>'."\n".
                       '    </TR>'."\n".
                       '    <TR>'."\n".
                       '       <TD><LABEL  for="confirm_">подтверждение пароля</LABEL></TD>'."\n".
                       '       <TD><INPUT type="password" id="confirm_" name="confirm_"></TD>'."\n".
                       '    </TR>'."\n".
                       '  </TABLE><BR>'."\n".
                       '</SPAN>'."\n".
                       '<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                    'font-size: 12pt; color: black;'.
                                    'text-decoration: none;	font-weight: normal;'.
                                    'text-align: justify; text-indent: 30px">'."\n".
                       '  <DIV>'."\n".
                       '    Начальные и конечные пробелы будут подавлены.'."\n".
                       '    В поле "имя" прописные и строчные буквы (буквы в верхнем и нижнем регистрах) считаются одинаковыми.'."\n".
                       '  </DIV>'."\n".
                       '</SPAN>'."\n".
                       CPage_::get_metrika_yandex();
    }#BodyNewUser

    public static function NewUserPage(){
      CPage_::$header_ ='<DIV id="text_header_">'.
                        '  Регистрация'.
                        '</DIV>';
      
      CPage_::$action_form_ ='RegUser.php';

      CPage_::$menu_[0]['link'] = 'RegUser.php';
      CPage_::$menu_[0]['image'] ='Image/label_send.png';
      CPage_::$menu_[0]['submit'] =true;
      CPage_::$menu_[0]['level'] =1;
      CPage_::$menu_[0]['active'] ='N';
      CPage_::$menu_[1]['link'] = 'index.php';
      CPage_::$menu_[1]['image'] ='Image/label_cancel.png';
      CPage_::$menu_[1]['submit'] =false;
      CPage_::$menu_[1]['level'] =1;
      CPage_::$menu_[1]['active'] ='N';

      $login_ ='';
      if (isset($_POST['login_'])) $login_ = $_POST['login_'];
      CNewUserPage_::BodyNewUser($login_);
      CPage_::$title_ ='ChessAndMail. Страница регистрации новой учетной записи.';
      CPage_::MakePage();
    }#NewUserPage
  }#CNewUserPage_

  try{
    if (!isset($_POST['login_'])) CNewUserPage_::NewUserPage();
     else{
       $_POST['login_']    =trim($_POST['login_']);
       $_POST['password_'] =trim($_POST['password_']);
       $_POST['confirm_']  =trim($_POST['confirm_']);
       if ($_POST['login_'] =='')
         throw new ERegistrationError('Имя не указано.');
       if ($_POST['password_'] =='')
         throw new ERegistrationError('Пароль не указан');
       if ($_POST['password_'] !=$_POST['confirm_'])
         throw new ERegistrationError('Пароль не совпадает');

       if (!const_::SetConnect_() || !const_::StartTransaction_())
         throw new Exception('Не удалось установить связь с базой данных. Попробуйте зайти позже.');
       CUsers_::InsRegInfo($_POST['login_'],$_POST['password_']);
       CUsers_::SetLast_Visit($_POST['login_']);
       if (!const_::Commit_()) throw new Exception('Не удалось завершить транзакцию.');

       $_SESSION[SESSION_LOGIN_] =$_POST['login_'];
       $_SESSION[SESSION_ID_] =CUsers_::Read_id_($_POST['login_']);

       CInfoPage_::MakePage(1);
       const_::Disconnect_();
     }#else
  }catch(Exception $e){
    if (const_::$isTransact_) const_::Rollback_();
    if (const_::$connect_) const_::Disconnect_();
    CPage_::$text_error_ =$e->getMessage();
    if ($e instanceof ERegistrationError)
      CNewUserPage_::NewUserPage();
     else
      CPage_::PageErr();
  }
?>

