<?php
  require_once('json/json.php');
  require_once('const_.php');
  
//Объект обмена информацией
  class CExchange_{
    public $table_ =array(); 
    public $error_ ='';
  }#CExchange_  
  
//инициализация сессии
  session_name(NAME_SESSION_);
  session_start();

  $oJSON =new Services_JSON();
  $oExchange =new CExchange_;
 
  header('Content-Type: text/html; charset=windows-1251');
  header('Cache-Control: no-cache');
  
  $connect_=false;
  $transact_ =false;
  $cursor_ =false;
  try{
    if (!isset($_SESSION[SESSION_ID_])) throw new Exception('Требуется авторизация.');
    if (!isset($_POST['last_id']) || !ctype_digit($_POST['last_id']))
      throw new Exception('Прамаетр last_id не указан либо указан неверно.');
    $last_id=$_POST['last_id'];
    
    if (const_::SetConnect_()) $connect_ =true; else throw new Exception('При соединении с базой произошла ошибка');
    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception('При старте транзакции произошла ошибка.');
    $s ='select B.login_, if(B.photo_ is null,0,1) as photo_, A.id_, A.id_gamer_'.
        ' from TInfoLogins_ A, TGamers_ B'.
        ' where (A.id_ > '.$last_id.') and (A.type_=1) and (A.id_gamer_=B.id_)'.
        ' order by A.id_';
    $cursor_=mysql_query($s,const_::$connect_); 
    if (!$cursor_) throw new Exception('При получении информации о заходах на сайт произошла ошибка.');
    $i=0;
    while ($row_ =mysql_fetch_array($cursor_)){
      $oExchange->table_[$i]['login_'] =htmlspecialchars(iconv("windows-1251","UTF-8",convert_cyr_string($row_['login_'],'d','w')));
      $oExchange->table_[$i]['id_'] =$row_['id_'];
      $oExchange->table_[$i++]['photo'] =($row_['photo_'] ==1 ? 'MainPage.php?link_=photo&id_='.$row_['id_gamer_'] : 'Image/no_photo.png');
    } //while
    if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
    const_::Disconnect_(); $const_ =false;
    
    echo($oJSON->encode($oExchange));
  }catch (Exception $e){
   $oExchange->error_ =$e->getMessage();
   echo($oJSON->encode($oExchange));
  }
?>
