/* Автор: Колосовский Антон Михайлович */

/* Соглашения:
     cl -впереди функции означает, что функция является конструктором класса
     o - впереди переменной означает, что переменная является ссылкой на объект

   Замечания:
     Для работы данного скрипта требуется подключенный скрипт json2.js (скрипт предоставляющий методы для работы с запросами json)
*/

/* Объект запроса информаци.
    type_ = 1 - запрос игроков on_line
*/
function cl_game_to_server_(){
  this.type_ =0;
  this.page_ =1;
  this.del_call =0; //номер удаляемого вызова
  this.accept_call =0; //номер принимаемого вызова
} //cl_game_to_server_
//-----------------------------------------------------------------------------------------

/* Объект управляет вызовами on-line */
function cl_control_calls_on_line(){
  this.count_visible_num_pages =5; //количество видимых номеров страниц
  this.o_xmlhttp_status_ =createXMLHttp(); //объект для опроса статуса партии
  this.current_page_ =1; //отображаемая страница
  this.o_timer_status_ =null; //объект Timer для опроса статуса партии
  this.del_call =0; //номер удаляемого исходящего вызова
  this.accept_call =0; //номер принимаемого вызова

  this.out_out_calls = function (calls_){
    var s='';
    if (calls_.length == 0)
        s ='<DIV style="text-align: center; color: black">вызовов нет</DIV>';
     else{
        s ='<TABLE cellspacing="3">' +
           '  <COL span="7">' +
           '     <TR><TD class="table_head_1">№</TD>' +
           '         <TD class="table_head_1">&nbsp;</TD>' +
           '         <TD class="table_head_1">Кому отправлен</TD>' +
           '         <TD class="table_head_1">Регламент</TD>' +
           '         <TD class="table_head_1">Ваш цвет</TD>' +
           '         <TD class="table_head_1">Рейтинговая</TD>' +
//           '         <TD class="table_head_1">Время действия</TD>' +
           '         <TD class="table_head_1">Комментарий</TD>' +
           '     </TR>';
        for(var i=0; i < calls_.length; i++){
          s +='<TR><TD class="table_body_1">' + calls_[i]['num'] + '</TD>' +
              '    <TD class="table_body_1">' +
              '       <span style="color:blue; cursor:pointer" onclick="o_control_calls_on_line.click_del_call('+calls_[i]['num']+')">отменить</span>'+
              '    </TD>';
          if (calls_[i]['to_login'] != '')
            s +='  <TD class="table_body_1">' + calls_[i]['to_login'] + '</TD>';
           else
            s +='  <TD class="table_body_1">&nbsp;</TD>';
          s +='    <TD class="table_body_1">'+ calls_[i]['reglament'] + '</TD>' +
              '    <TD class="table_body_1">' + calls_[i]['our_color'] + '</TD>' +
              '    <TD class="table_body_1">' + calls_[i]['rating'] + '</TD>' +
//              '    <TD class="table_body_1">' + calls_[i]['time'] + '</TD>' +
              '    <TD class="table_body_1">' + (ltrim(calls_[i]['comment']) == '' ? '&nbsp;' : calls_[i]['comment']) + '</TD>' +
              '</TR>';
        }//for
        s +='</TABLE>';
     }

    if (document.getElementById("out_calls").innerHTML != s)
      document.getElementById("out_calls").innerHTML =s;
  }//out_out_calls

  this.out_personal_calls = function (calls_){
    var s='';
    if (calls_.length == 0)
        s ='<DIV style="text-align: center; color: black">вызовов нет</DIV>';
     else{
        s ='<TABLE cellspacing="3">' +
           '  <COL span="9">' +
           '     <TR><TD class="table_head_1">№</TD>' +
           '         <TD class="table_head_1">&nbsp;</TD>' +
           '         <TD class="table_head_1">&nbsp;</TD>' +
           '         <TD class="table_head_1">Кто отправил</TD>' +
           '         <TD class="table_head_1">Рейтинг</TD>' +
           '         <TD class="table_head_1">Регламент</TD>' +
           '         <TD class="table_head_1">Ваш цвет</TD>' +
           '         <TD class="table_head_1">Рейтинговая</TD>' +
//           '         <TD class="table_head_1">Время действия</TD>' +
           '         <TD class="table_head_1">Комментарий</TD>' +
           '     </TR>';
        for(var i=0; i < calls_.length; i++){
          s +='<TR><TD class="table_body_1">' + calls_[i]['num'] + '</TD>' +
              '    <TD class="table_body_1">' +
              '         <span style="color:blue; cursor:pointer" onclick="o_control_calls_on_line.click_accept_call('+calls_[i]['num']+')">принять</span>'+
              '    </TD>' +
              '    <TD class="table_body_1">' +
              '         <span style="color:blue; cursor:pointer" onclick="o_control_calls_on_line.click_del_call('+calls_[i]['num']+')">отклонить</span>'+
              '    </TD>' +
              '    <TD class="table_body_1">' + calls_[i]['from_login'] + '</TD>'+
              '    <TD class="table_body_1">' + calls_[i]['ratingB_'] + '</TD>'+
              '    <TD class="table_body_1">'+ calls_[i]['reglament'] + '</TD>' +
              '    <TD class="table_body_1">' + calls_[i]['our_color'] + '</TD>' +
              '    <TD class="table_body_1">' + calls_[i]['rating'] + '</TD>' +
//              '    <TD class="table_body_1">' + calls_[i]['time'] + '</TD>' +
              '    <TD class="table_body_1">' + (ltrim(calls_[i]['comment']) == '' ? '&nbsp;' : calls_[i]['comment']) + '</TD>' +
              '</TR>';
        }//for
        s +='</TABLE>';
     }

    if (document.getElementById("person_calls").innerHTML != s)
      document.getElementById("person_calls").innerHTML =s;
  }//out_personal_calls

  this.getFirstVisibleNum =function(visible_page){
    var result_=0;
    result_ =Math.floor(visible_page / this.count_visible_num_pages);
    if ((visible_page % this.count_visible_num_pages) == 0) result_--;
    result_=result_ * this.count_visible_num_pages +1;
    return result_;
  }//getFirstVisibleNum

  this.getLastVisibleNum =function(visible_page,count_pages){
    var result_;
    result_ =this.getFirstVisibleNum(visible_page);
    for(var i=2; (i <=this.count_visible_num_pages) && (result_ < count_pages); i++) result_++;
    return result_;
  }//getLastVisibleNum

  this.out_label_pages = function(visible_page,count_pages){
    var s ='';
    var a =0;
    var b =0;
    if (count_pages > 1){
      a =this.getFirstVisibleNum(visible_page);
      b =this.getLastVisibleNum(visible_page,count_pages);
      if (a > 1) s ='<span style="color:blue; cursor:pointer" onclick="o_control_calls_on_line.click_label('+(a-1)+')">&lt;&lt;</span>';
      for (var i=a; i <=b; i++){
        if (s != '') s +='&nbsp';
          if (visible_page != i)
            s +='<span style="color:blue; cursor:pointer" onclick="o_control_calls_on_line.click_label('+i+')">'+i+'</span>';
          else
            s +='<span>'+i+'</span>';
      } //for i
      if (b < count_pages) s +='&nbsp<span style="color:blue; cursor:pointer" onclick="o_control_calls_on_line.click_label('+(b+1)+')">&gt;&gt;</span>';
    }

    if (document.getElementById("labels_calls_on_line").innerHTML !=s)
      document.getElementById("labels_calls_on_line").innerHTML =s;
  } //out_label_pages

  this.out_calls = function (calls_){
    var s='';
    if (calls_.length == 0)
        s ='<DIV style="text-align: center; color: black">вызовов нет</DIV>';
     else{
        s ='<TABLE cellspacing="3">' +
           '  <COL span="8">' +
           '     <TR><TD class="table_head_1">№</TD>' +
           '         <TD class="table_head_1">&nbsp;</TD>' +
           '         <TD class="table_head_1">Кто отправил</TD>' +
           '         <TD class="table_head_1">Рейтинг</TD>' +
           '         <TD class="table_head_1">Регламент</TD>' +
           '         <TD class="table_head_1">Ваш цвет</TD>' +
           '         <TD class="table_head_1">Рейтинговая</TD>' +
//           '         <TD class="table_head_1">Время действия</TD>' +
           '         <TD class="table_head_1">Комментарий</TD>' +
           '     </TR>';
        for(var i=0; i < calls_.length; i++){
          s +='<TR><TD class="table_body_1">' + calls_[i]['num'] + '</TD>' +
              '    <TD class="table_body_1">' +
              '         <span style="color:blue; cursor:pointer" onclick="o_control_calls_on_line.click_accept_call('+calls_[i]['num']+')">принять</span>'+
              '    </TD>' +
              '    <TD class="table_body_1">' + calls_[i]['from_login'] + '</TD>'+
              '    <TD class="table_body_1">' + calls_[i]['ratingB_'] + '</TD>'+
              '    <TD class="table_body_1">'+ calls_[i]['reglament'] + '</TD>' +
              '    <TD class="table_body_1">' + calls_[i]['our_color'] + '</TD>' +
              '    <TD class="table_body_1">' + calls_[i]['rating'] + '</TD>' +
//              '    <TD class="table_body_1">' + calls_[i]['time'] + '</TD>' +
              '    <TD class="table_body_1">' + (ltrim(calls_[i]['comment']) == '' ? '&nbsp;' : calls_[i]['comment']) + '</TD>' +
              '</TR>';
        }//for
        s +='</TABLE>';
     }

    if (document.getElementById("total_calls").innerHTML != s)
      document.getElementById("total_calls").innerHTML =s;
  }//out_calls

  this.out_start_games =function(games_){
    if (games_.length > 0){
      var s ='';
      s +='<SPAN style="font-family: Liberation Serif, Times, sans-serif;' +
                       'font-size: 12pt; color: black; text-decoration: none; font-weight: normal">' +
          '   <DIV style="font-weight: bold">' +
          '      Начавшиеся партии' +
          '   </DIV>' +
          '  <TABLE cellspacing="3">' +
          '     <COL span="4">' +
          '     <TR><TD class="table_head_1">№</TD>' +
          '         <TD class="table_head_1">Белые</TD>' +
          '         <TD class="table_head_1">Черные</TD>' +
          '         <TD class="table_head_1">Регламент</TD>' +
          '     </TR>';
      for(var i=0; i < games_.length; i++){
       s +='<TR><TD class="table_body_1"><A href="MainPage.php?link_=game&id=' + games_[i]['num'] + '">' + games_[i]['num'] + '</A></TD>' +
           '    <TD class="table_body_1">' + games_[i]['login_white'] + '</TD>' +
           '    <TD class="table_body_1">' + games_[i]['login_black'] + '</TD>' +
           '    <TD class="table_body_1">' + games_[i]['reglament'] + '</TD>' +
           '</TR>';
      }//for
      s +='  </TABLE>' +
          '  <SPAN style="text-align: right; color:blue; cursor:pointer" onclick="top.o_modal_.hide_();">закрыть</SPAN>'+
          '</SPAN>';
      top.o_modal_.content_ =s;
      top.o_modal_.show_();
    }
  }//out_start_games

  this.out_results =function(result_){
//Вывожу исходящие вызовы
    this.out_out_calls(result_.object_.out_calls_);
//Вывожу персональные вызовы
    this.out_personal_calls(result_.object_.person_calls_);
//Вывожу общие вызовы
    this.out_label_pages(result_.object_.current_page,result_.object_.count_pages);
    this.out_calls(result_.object_.calls_);
//Вывожу информацию о начавшихся партиях
    this.out_start_games(result_.object_.games_);
  }//out_results

  this.onready_status =function(){
    var o_result_;
    var o_this_ =this; 
    if (this.o_xmlhttp_status_.readyState ==4){
      if (this.o_xmlhttp_status_.status ==200){
        try{
//          alert(this.o_xmlhttp_status_.responseText);
          o_result_ =JSON.parse(this.o_xmlhttp_status_.responseText);
//          alert(o_result_.error_);
          if (o_result_.error_ ===''){
            this.current_page_ =o_result_.object_.current_page;
            this.out_results(o_result_);
          }
        }catch(e){
//        alert(this.o_xmlhttp_status_.responseText);
        }
      }
      if (o_result_.object_.games_.length ==0)
        this.o_timer_status_ =window.setTimeout(function(){o_this_.read_info();},5000);
    }
  }//onready_status

  this.read_info =function(){
    var q_ =new cl_game_to_server_();
    var o_this_ =this;

    q_.type_ =2;
    q_.page_ =this.current_page_;
    q_.del_call =this.del_call; this.del_call =0;
    q_.accept_call =this.accept_call; this.accept_call =0;
    if (this.o_xmlhttp_status_.readyState !=0)
        this.o_xmlhttp_status_.abort();
    this.o_xmlhttp_status_.open("post","ajax_calls_on_line.php",true);
    this.o_xmlhttp_status_.onreadystatechange=function(){
      o_this_.onready_status();
    }
    this.o_xmlhttp_status_.send(JSON.stringify(q_));
  } //read_info

  this.click_label =function(page_){
    if (this.o_timer_status_){
      clearTimeout(this.o_timer_status_);
      this.o_timer_status_ =null;
    }
    if (this.o_xmlhttp_status_.readyState !=0)
      this.o_xmlhttp_status_.abort();

    this.current_page_ =page_;
    this.read_info();
  }//click_label

  this.click_del_call =function(num_call){
    if (this.o_timer_status_){
      clearTimeout(this.o_timer_status_);
      this.o_timer_status_ =null;
    }
    if (this.o_xmlhttp_status_.readyState !=0)
      this.o_xmlhttp_status_.abort();

    this.del_call =num_call;
    this.read_info();
  }//click_del_call

  this.click_accept_call =function(num_call){
    if (this.o_timer_status_){
      clearTimeout(this.o_timer_status_);
      this.o_timer_status_ =null;
    }
    if (this.o_xmlhttp_status_.readyState !=0)
      this.o_xmlhttp_status_.abort();

    this.accept_call =num_call;
    this.read_info();
  }//click_accept_call
}//cl_control_calls_on_line
//-----------------------------------------------------------------------------------------

/* Объект управляет игроками on-line */
function cl_control_mens_on_line(){
  this.count_visible_num_pages =5; //количество видимых номеров страниц
  this.o_xmlhttp_status_ =createXMLHttp(); //объект для опроса статуса партии
  this.o_timer_status_ =null; //объект Timer для опроса статуса партии
  this.current_page_ =1; //отображаемая страница

//Выводит информацию о вновь зашедших на сайт игроках
  this.out_info_enter_mens =function(info_){
    document.getElementById("enter_mens").innerHTML=info_;
  }//out_info_enter_mens

  this.getFirstVisibleNum =function(visible_page){
    var result_=0;
    result_ =Math.floor(visible_page / this.count_visible_num_pages);
    if ((visible_page % this.count_visible_num_pages) == 0) result_--;
    result_=result_ * this.count_visible_num_pages +1;
    return result_;
  }//getFirstVisibleNum

  this.getLastVisibleNum =function(visible_page,count_pages){
    var result_;
    result_ =this.getFirstVisibleNum(visible_page);
    for(var i=2; (i <=this.count_visible_num_pages) && (result_ < count_pages); i++) result_++;
    return result_;
  }//getLastVisibleNum

  this.out_label_pages = function(visible_page,count_pages){
    var s ='';
    var a =0;
    var b =0;
    if (count_pages > 1){
      a =this.getFirstVisibleNum(visible_page);
      b =this.getLastVisibleNum(visible_page,count_pages);
      if (a > 1) s ='<span style="color:blue; cursor:pointer" onclick="o_control_mens_on_line.click_label('+(a-1)+')">&lt;&lt;</span>';
      for (var i=a; i <=b; i++){
        if (s != '') s +='&nbsp';
          if (visible_page != i)
            s +='<span style="color:blue; cursor:pointer" onclick="o_control_mens_on_line.click_label('+i+')">'+i+'</span>';
          else
            s +='<span>'+i+'</span>';
      } //for i
      if (b < count_pages) s +='&nbsp<span style="color:blue; cursor:pointer" onclick="o_control_mens_on_line.click_label('+(b+1)+')">&gt;&gt;</span>';
    }

    if (document.getElementById("labels_mens_on_line").innerHTML !=s)
      document.getElementById("labels_mens_on_line").innerHTML =s;
  } //out_label_pages

  this.out_logins = function (array_logins){
    var s='';
    for(var i=0; i < array_logins.length; i++){
      if (s != '') s +='<br/>';
      s +='<span>'+array_logins[i] + '</span>';
    }
    if (document.getElementById("list_mens").innerHTML != s)
      document.getElementById("list_mens").innerHTML =s;
  }//out_logins

  this.out_results =function(result_){
//Вывожу информацию о вновь зашедших
    if (document.getElementById("enter_mens").innerHTML !=result_.object_.new_gamers)
      this.out_info_enter_mens(result_.object_.new_gamers);
//Вывожу закладки страниц
    this.out_label_pages(result_.object_.current_page,result_.object_.count_pages);
//Вывожу логины
    this.out_logins(result_.object_.gamers_);
  }//out_results

  this.onready_status =function(){
    var o_result_;
    var o_this_ =this;
    if (this.o_xmlhttp_status_.readyState ==4){
      if (this.o_xmlhttp_status_.status ==200){
        try{
//          alert(this.o_xmlhttp_status_.responseText);
          o_result_ =JSON.parse(this.o_xmlhttp_status_.responseText);
//          alert(o_result_.error_);
          if (o_result_.error_ ===''){
            this.current_page_ =o_result_.object_.current_page;
            this.out_results(o_result_);
          }
        }catch(e){
//        alert(this.o_xmlhttp_status_.responseText);
        }
      }
      this.o_timer_status_ =window.setTimeout(function(){o_this_.read_info();},5000);
    }
  }//onready_status

  this.read_info =function(){
    var q_ =new cl_game_to_server_();
    var o_this_ =this;
    q_.type_ =1;
    q_.page_ =this.current_page_;
    if (this.o_xmlhttp_status_.readyState !=0)
        this.o_xmlhttp_status_.abort();
    this.o_xmlhttp_status_.open("post","ajax_calls_on_line.php",true);
    this.o_xmlhttp_status_.onreadystatechange=function(){
      o_this_.onready_status();
    }
    this.o_xmlhttp_status_.send(JSON.stringify(q_));
  } //read_info

  this.click_label =function(page_){
    if (this.o_timer_status_){
      clearTimeout(this.o_timer_status_);
      this.o_timer_status_ =null;
    }
    if (this.o_xmlhttp_status_.readyState !=0)
      this.o_xmlhttp_status_.abort();

    this.current_page_ =page_;
    this.read_info();
  }//click_label
} //cl_control_mens_on_line
