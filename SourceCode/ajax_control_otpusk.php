<?php
    require_once('const_.php');
    require_once('Users.php');
    require_once('configuration_.php');

//инициализация сессии
    session_name(NAME_SESSION_);
    session_start();

    header('Content-Type: text/html; charset=windows-1251');
    header('Cache-Control: no-cache');

    $connect_=false;
    $transact_ =false;
    try{        if (!isset($_SESSION[SESSION_LOGIN_]) || !isset($_SESSION[SESSION_ID_])) throw new Exception();
        ob_start(); #начинаю буферизацию
        if (const_::SetConnect_()) $connect_ =true; else throw new Exception();
        if (const_::StartTransaction_()) $transact_ =true; else throw new Exception();

        CUsers_::Check_otpusk($_SESSION[SESSION_ID_]);
        $count_otpusk = CUsers_::Ostatok_Otpusk($_SESSION[SESSION_ID_]);
        $in_otpusk =CUsers_::Status_Otpusk($_SESSION[SESSION_ID_]);
        if ($in_otpusk) echo('start'); else echo('stop');
        echo(CConfiguration_::getHTMLcontrolOtpusk($count_otpusk,$in_otpusk));

        if (const_::Commit_()) $transact_ =false; else throw new Exception();
        if ($connect_) const_::Disconnect_();
        ob_end_flush(); #завершаю буферизацию и передаю данные
    }catch (Exception $e){
        ob_end_clean(); #очищаю буфер и завершаю буферизацию
        if ($transact_) const_::Rollback_();
        if ($connect_) const_::Disconnect_();
        echo('error');
    }
?>
