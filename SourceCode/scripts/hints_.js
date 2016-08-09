/* Автор: Колосовский Антон Михайлович */

/* Соглашения:
     cl -впереди функции означает, что функция является конструктором класса
     o - впереди переменной означает, что переменная является ссылкой на объект
     ar - впереди переменной означает, что переменная является массивом

   Замечания:
     Для работы данного скрипта требуется подключенный скрипт json2.js (скрипт предоставляющий методы для работы с запросами json)
                                требуется подключенный скрипт script_.js (библиотека часто используемых функций)
*/
function cl_hints_(o_input_,count_hints_,addr_link_){
  this.old_value_; //предыдущее значение объекта this.o_input_ и текст введенный пользователем
  this.o_input_ =o_input_; //ссылка на объект input, для которого будут выводиться подсказки
  this.count_hints_ =count_hints_; // количество отображаемых подсказок
  this.addr_link_ =addr_link_; // адрес запроса подсказок
  this.ar_hints_ =new Array(); //список подсказок
  this.o_xmlhttp_ =null; //объект для отправки асинхронных запросов
  this.o_list_hints_ =null; //объект, содержащий список подсказок
  this.o_timer_ =null; //объект Timer
  this.sel_hint_=-1; //номер выбранной подсказки, нумеруются от 0, выбираются стрелками вверх и вниз
//цвета могут быть изменены сразу после вызова конструктора класса
  this.inactive_background ="white" ;
  this.inactive_color ="black";
  this.active_background ="#7fb555";
  this.active_color ="white";

  this.old_value_ =this.o_input_.value;

//метод получает список подсказок
  this.get_hints_ =function(first_chars_){
                                          var ar_params_for_server =new Array();
                                          var o_this_;
                                          ar_params_for_server[0] =first_chars_;
                                          ar_params_for_server[1] =this.count_hints_;
                                          o_this_ =this;
                                          if (this.o_xmlhttp_.readyState !=0) this.o_xmlhttp_.abort();
                                          this.o_xmlhttp_.open("post",this.addr_link_,true);
                                          this.o_xmlhttp_.onreadystatechange=function(){
                                              if (o_this_.o_xmlhttp_.readyState ==4)
                                                if (o_this_.o_xmlhttp_.status ==200){
//                                                  alert("text-"+o_this_.o_xmlhttp_.responseText);
                                                  o_this_.ar_hints_ =JSON.parse(o_this_.o_xmlhttp_.responseText);
                                                  if (!(o_this_.ar_hints_ instanceof Array)) o_this_.ar_hints_ =new Array();
                                                  o_this_.show_hints_();
                                                }
                                          }
                                          this.o_xmlhttp_.send(JSON.stringify(ar_params_for_server));
                                         }//get_hints_

//метод скрывает список подсказок
  this.hide_hints_ =function(){
                               this.o_list_hints_.style.visibility ="hidden";
                              }//hide_hints_

//метод отображает список подсказок
  this.show_hints_ =function(){
                               var s ='';
                               var l =0;
                               var t =0;
                               var o_ =this.o_input_;
                               if (this.ar_hints_.length > 0){
                                 this.sel_hint_ =-1;
                                 for(var i=0; i < this.ar_hints_.length; i++)
                                   s +='<div style="cursor:pointer; padding-left:5px">'+this.ar_hints_[i]+'</div>';
                                 this.o_list_hints_.innerHTML =s;
                                 while(o_.tagName != 'BODY'){
                                   l +=o_.offsetLeft;
                                   t +=o_.offsetTop;
                                   o_ =o_.offsetParent;
                                 }//while
                                 l +=o_.offsetLeft; //Смещение элемента BODY
                                 t +=o_.offsetTop;
                                 this.o_list_hints_.style.width =this.o_input_.offsetWidth+'px';
                                 this.o_list_hints_.style.left =l + 'px';
                                 this.o_list_hints_.style.top  =(t - this.o_list_hints_.offsetHeight) + 'px';
                                 this.o_list_hints_.style.visibility ="visible";
                               }
                              }//show_hints_

//метод выделяет подсказку o_target и снимает выделение с остальных подсказок
  this.sel_target_=function(o_target_){
                                       for(var i=0; i < this.o_list_hints_.childNodes.length; i++)
                                         if (this.o_list_hints_.childNodes[i] ==o_target_){
                                           this.o_list_hints_.childNodes[i].style.backgroundColor =this.active_background;
                                           this.o_list_hints_.childNodes[i].style.color =this.active_color;
                                         }else{
                                           this.o_list_hints_.childNodes[i].style.backgroundColor =this.inactive_background;
                                           this.o_list_hints_.childNodes[i].style.color =this.inactive_color;
                                         }
                                      }//this.sel_target_

//метод устанавливает "связь" с объектом o_input_
  this.init_ =function(){
                         var o_this_;
                         o_this_ =this;
  	                     this.o_xmlhttp_ =createXMLHttp();
                         if (this.o_xmlhttp_ != null){
//создаю элемент отображающий подсказки
                           this.o_list_hints_=document.createElement("div");
                           this.o_list_hints_.style.cssText="-moz-box-sizing: border-box; " +
                                                            "box-sizing: border-box; " +
                                                            "border: 1px solid black; " +
                                                            "background-color: white; " +
                                                            "color: black; " +
                                                            "position:absolute";
                           document.body.appendChild(this.o_list_hints_);
                           this.hide_hints_();
//обработчик нажатий клавиш
                           this.o_input_.onkeyup=function(o_event_){
                              var key_;
                              if (!o_event_) o_event_ =window.event;
                              key_ =o_event_.keyCode;

                              if (key_ ==13){//enter
                                if ((o_this_.o_list_hints_.style.visibility =="visible") && (o_this_.sel_hint_ !=-1)){
                                  o_this_.o_input_.value = o_this_.ar_hints_[o_this_.sel_hint_];
                                  o_this_.hide_hints_();
                                  o_event_.returnValue =false;
                                  if (o_event_.preventDefault) o_event_.preventDefault();
                                }
                              }else if (key_ ==27){//esc
                                o_this_.hide_hints_();
                                o_this_.o_input_.value =o_this_.old_value_;
                              }else if (key_ ==38){//стрелка вверх
                                if ((o_this_.o_list_hints_.style.visibility =="visible") && (o_this_.ar_hints_.length > 0)){
                                  if (o_this_.sel_hint_ > 0)
                                    o_this_.sel_hint_--;
                                   else o_this_.sel_hint_ =o_this_.ar_hints_.length-1;
                                  o_this_.o_input_.value = o_this_.ar_hints_[o_this_.sel_hint_];
                                  o_this_.sel_target_(o_this_.o_list_hints_.childNodes[o_this_.sel_hint_]);
                                  o_event_.returnValue =false;
                                  if (o_event_.preventDefault) o_event_.preventDefault();
                                }
                              }else if (key_ ==40){//стрелка вниз
                                if ((o_this_.o_list_hints_.style.visibility =="visible") && (o_this_.ar_hints_.length > 0)){
                                  if ((o_this_.sel_hint_ >=0) && (o_this_.sel_hint_ < o_this_.ar_hints_.length-1))
                                    o_this_.sel_hint_++;
                                   else o_this_.sel_hint_ =0;
                                  o_this_.o_input_.value = o_this_.ar_hints_[o_this_.sel_hint_];
                                  o_this_.sel_target_(o_this_.o_list_hints_.childNodes[o_this_.sel_hint_]);
                                  o_event_.returnValue =false;
                                  if (o_event_.preventDefault) o_event_.preventDefault();
                                }
                              }else if (o_this_.o_input_.value !=o_this_.old_value_){//символьные клавиши
                                o_this_.old_value_ =o_this_.o_input_.value;
                                o_this_.hide_hints_();
                                if (o_this_.o_timer_){
                                   clearTimeout(o_this_.o_timer_);
                                   o_this_.o_timer_ =null;
                                }
                                if (o_this_.o_xmlhttp_ !=0) o_this_.o_xmlhttp_.abort();
                                if (o_this_.o_input_.value !=='')
                                  o_this_.o_timer_ =setTimeout(function(){
                                                                 o_this_.get_hints_(o_this_.old_value_);
                                                               },250);
                              }
                           }//this.o_input_.onkeyup
//обработчик потери фокуса
                           this.o_input_.onblur=function(){
                              o_this_.hide_hints_();
                           }//this.o_input_.onblur
//обработчик событий onmouseover, onmousedown, onmouseup
                           this.o_list_hints_.onmousedown=
                           this.o_list_hints_.onmouseup=
                           this.o_list_hints_.onmouseover=function(o_event_){
                              o_event_ =o_event_ || window.event;
                              o_target =o_event_.target || o_event_.srcElement;
                              if (o_event_.type =="mouseover"){
                                o_this_.sel_target_(o_target);
                                for(var i=0; i < o_this_.o_list_hints_.childNodes.length; i++)
                                  if (o_this_.o_list_hints_.childNodes[i] ==o_target)
                                    o_this_.sel_hint_ =i;
                              }else if (o_event_.type =="mousedown"){
                                o_this_.o_input_.value = o_target.firstChild.nodeValue;
                                o_this_.hide_hints_();
                              }else if (o_event_.type =="mouseup")
                                o_this_.o_input_.focus();
                           }//this.o_list_hints_.onmouseover
                         }//o_xmlhttp_ != null
                        }//init_

  this.init_();
}//cl_hints