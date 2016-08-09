<?php
   require_once('const_.php');
   require_once('Info/oboi.php');
   require_once('Info/links.php');
   require_once('Info/work_.php');

#инициализация сессии
  session_name(NAME_SESSION_);
  session_start();

  if (isset($_GET['link_']) && ($_GET['link_']=='oboi')){
    COboi::manager();
  }else if (isset($_GET['link_']) && ($_GET['link_']=='work_')){
    CWork_::MakePage();
  }else{
    CLinks::manager();
  }