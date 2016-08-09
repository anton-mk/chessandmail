<?php
    require_once('const_.php');
    require_once('Users.php');
    require_once('info_.php');
    error_reporting(E_ALL);

#������������� ������
    session_name(NAME_SESSION_);
    session_start();

    session_unset();

    try{
      if (!isset($_POST['login_']) || !isset($_POST['password_']))
        CPage_::FirstPage();
      else{
        $_POST['login_'] =trim($_POST['login_']);
        $_POST['password_'] =trim($_POST['password_']);
        if ($_POST['login_'] =='')
          throw new EAutotenficError('��� �� �������.');
        if ($_POST['password_'] =='')
          throw new EAutotenficError('������ �� ������');

        if (!const_::SetConnect_() || !const_::StartTransaction_())
          throw new Exception('�� ������� ���������� ����� � ����� ������. ���������� ����� �����.');

        $login_ =CUsers_::CheckUser($_POST['login_'], $_POST['password_']);
        CUsers_::SetLast_Visit($login_);
        if (!const_::Commit_()) throw new Exception('�� ������� ��������� ����������.');

        $_SESSION[SESSION_LOGIN_] =$login_;
        $_SESSION[SESSION_ID_] =CUsers_::Read_id_($login_);

        CUsers_::end_games_clock_zero();
        CUsers_::del_calls_end_time();

        if (!const_::StartTransaction_())
          throw new Exception('�� ������� ���������� ����� � ����� ������. ���������� ����� �����.');
        $id_boxInfo=0;
        CPage_::$boxInfo_ =CUsers_::Read_firstBoxInfo($id_boxInfo);
        CInfoPage_::MakePage(1);
        if(CPage_::$boxInfo_ !='')
          CUsers_::setRead_firstBoxInfo($id_boxInfo);
        if (!const_::Commit_()) throw new Exception('�� ������� ��������� ����������.');
        
        const_::Disconnect_();
      }#else
    }catch(Exception $e){
      if (const_::$isTransact_) const_::Rollback_();
      if (const_::$connect_) const_::Disconnect_();
      CPage_::$text_error_ =$e->getMessage();
      if ($e instanceof EAutotenficError)
        CPage_::FirstPage();
      else
        CPage_::PageErr();
    }

?>
