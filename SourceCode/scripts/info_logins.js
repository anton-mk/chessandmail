function cl_info_logins(){
  this.last_id;
  this.timer_;
  
  this.show_ =function(info_){
    var s;
    var d;
    if((info_.error_ =='') && (info_.table_.length > 0)){
//Формирую контент
      s ='';
      if(info_.table_.length > 1){
        s +='<div class="count_peoples">'+ info_.table_.length + get_padej(info_.table_.length,' человек',' человека',' человек') + '</div>';
        s +='<h2>Последний</h2>';
      }
      s +='<img src="'+info_.table_[info_.table_.length-1]['photo']+'">'+
          '<div class="login_people">'+info_.table_[info_.table_.length-1]['login_']+'</div>'+
          '<div class="clear_">&nbsp</div>';
//Оформляю
      s ='<div id="div-info-logins">' +
//Верхняя часть, имеющая округление          
         '  <div class="lt"><div class="rt"><div class="body_">&nbsp;</div></div></div>' +
//Заголовок
         '  <div class="left_"><div class="right_"><h1>На сайт вошли</h1></div></div>'+
//Линия подчеркивания        
         '  <div class="l_line"><div class="r_line"><div class="w_line">&nbsp;</div></div></div>'+
//Тело            
         '  <div class="left_"><div class="right_"><div class="body_">' + s + '</div></div></div>'+
//Нижняя часть, имеющая округление 
         '  <div class="lb"><div class="rb"><div class="body_">&nbsp;</div></div></div>' +
         '</div>';
//Вывожу
      $("body").append(s);
      d =$("#div-info-logins");
      d.css('left',($(window).width() -d.width()-$("body").offset().left-10) +'px');
      d.css('top',($(window).height() - d.height()-$("body").offset().top-10)+'px');
      $("#div-info-logins").fadeIn(200,
                                 function(){
                                   window.setTimeout(
                                     function(){
                                       $("#div-info-logins").fadeOut(200,function(){$("#div-info-logins").remove();})  
                                     },3000);              
                                 })
//id последнего показанного логина
      this.last_id =info_.table_[info_.table_.length-1]['id_'];
    }//if
  }//show_
  
  this.start_ =function(){
    var l_this =this;  
    this.o_timer =window.setTimeout(
      function(){
        $.ajax({async:true,url:'ajax_info_logins.php',
                type:'POST',data:{last_id:l_this.last_id},dataType:'json',
                success:function(info_){
                          l_this.show_(info_);
                          l_this.start_();
                        },
                error:function(){l_this.start_();}        
               });  
      }
      ,20000);
  }//start_
    
}//cl_info_logins

