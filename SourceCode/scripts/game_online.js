/* Автор: Колосовский Антон Михайлович */

/* Соглашения:
     cl -впереди функции означает, что функция является конструктором класса
     o - впереди переменной означает, что переменная является ссылкой на объект

   Замечания:
     Для работы данного скрипта требуется подключенный скрипт json2.js (скрипт предоставляющий методы для работы с запросами json)
                                требуется подключенный скрипт script_.js (библиотека часто используемых функций)
*/

/* Объект запроса информаци.
    type_ = 1 - запрос состояния партии
    type_ = 2 - выбор хода
    type_ = 3 - совершение хода
*/
function cl_game_to_server_(){
  this.num_game_ =0; //номер партии
  this.type_ =0;
  this.cell_1 = '';
  this.cell_2 = '';
  this.piece_='';
  this.num_last_move =0;
} //cl_game_to_server_

/* Объект управления партией
     o_name_ - имя переменной, которая будет содержать ссылку на данный объект,
               данная переменная будет использована в вызове setTimeout
*/
function  cl_control_game_(pathImages,id_game,pathImageBoard,size_cell){
  this.num_game_ =id_game; //номер партии
  this.pathImage_ =pathImages;
  this.pathImageBoard_ =pathImageBoard;
  this.o_xmlhttp_status_ =createXMLHttp(); //объект для опроса статуса партии
  this.o_timer_status_ =null; //объект Timer для опроса статуса партии
  this.may_move_ =false; //можно совершить ход
  this.sel_cell1_ ='';   //первая выделенная ячейка
  this.sel_cell2_ ='';   //вторая выделенная ячейка
  this.piece_ ='';       //фигура в которую должна превратиться пешка
  this.color_ = '';      //цвет, которым играет пользователь
  this.o_modal_ = new cl_modal_();
    this.o_modal_.use_top =false;
    this.o_modal_.use_transporant =false;

  this.row_operation_make_move =-1; //номер строки "сделать ход" или первой строки "выбрать"(от ноля)
  this.count_row_operation_make_move =0; //если доступна операция сделать ход =2, если выбрать превращение пешки=8
  this.row_operation_drawn =-1; //номер строки "предложить ничью"
  this.row_operation_accept_drawn =-1; //номер строки "принять ничью"
  this.row_in_variant =-1; //номер первой строки входящие варианты
  this.in_variants =new Array(); //массив входящих вариантов, содержит id_ вариантов

  this.board_=new Array();
  this.board_["A"] =new Array('','','','','','','','','');
  this.board_["B"] =new Array('','','','','','','','','');
  this.board_["C"] =new Array('','','','','','','','','');
  this.board_["D"] =new Array('','','','','','','','','');
  this.board_["E"] =new Array('','','','','','','','','');
  this.board_["F"] =new Array('','','','','','','','','');
  this.board_["G"] =new Array('','','','','','','','','');
  this.board_["H"] =new Array('','','','','','','','','');

//Загружаю изображения
  this.lib_images =new Array();
  this.lib_images["b"]    =new Image(size_cell,size_cell); this.lib_images["b"].src    =this.pathImageBoard_+"b.jpg";
  this.lib_images["bbb"]  =new Image(size_cell,size_cell); this.lib_images["bbb"].src  =this.pathImageBoard_+"bbb.jpg";
  this.lib_images["bbbs"] =new Image(size_cell,size_cell); this.lib_images["bbbs"].src =this.pathImageBoard_+"bbbs.jpg";
  this.lib_images["bbw"]  =new Image(size_cell,size_cell); this.lib_images["bbw"].src  =this.pathImageBoard_+"bbw.jpg";
  this.lib_images["bbws"] =new Image(size_cell,size_cell); this.lib_images["bbws"].src =this.pathImageBoard_+"bbws.jpg";
  this.lib_images["bkb"]  =new Image(size_cell,size_cell); this.lib_images["bkb"].src  =this.pathImageBoard_+"bkb.jpg";
  this.lib_images["bkbs"] =new Image(size_cell,size_cell); this.lib_images["bkbs"].src =this.pathImageBoard_+"bkbs.jpg";
  this.lib_images["bkw"]  =new Image(size_cell,size_cell); this.lib_images["bkw"].src  =this.pathImageBoard_+"bkw.jpg";
  this.lib_images["bkws"] =new Image(size_cell,size_cell); this.lib_images["bkws"].src =this.pathImageBoard_+"bkws.jpg";
  this.lib_images["bnb"]  =new Image(size_cell,size_cell); this.lib_images["bnb"].src  =this.pathImageBoard_+"bnb.jpg";
  this.lib_images["bnbs"] =new Image(size_cell,size_cell); this.lib_images["bnbs"].src =this.pathImageBoard_+"bnbs.jpg";
  this.lib_images["bnw"]  =new Image(size_cell,size_cell); this.lib_images["bnw"].src  =this.pathImageBoard_+"bnw.jpg";
  this.lib_images["bnws"] =new Image(size_cell,size_cell); this.lib_images["bnws"].src =this.pathImageBoard_+"bnws.jpg";
  this.lib_images["bpb"]  =new Image(size_cell,size_cell); this.lib_images["bpb"].src  =this.pathImageBoard_+"bpb.jpg";
  this.lib_images["bpbs"] =new Image(size_cell,size_cell); this.lib_images["bpbs"].src =this.pathImageBoard_+"bpbs.jpg";
  this.lib_images["bpw"]  =new Image(size_cell,size_cell); this.lib_images["bpw"].src  =this.pathImageBoard_+"bpw.jpg";
  this.lib_images["bpws"] =new Image(size_cell,size_cell); this.lib_images["bpws"].src =this.pathImageBoard_+"bpws.jpg";
  this.lib_images["bqb"]  =new Image(size_cell,size_cell); this.lib_images["bqb"].src  =this.pathImageBoard_+"bqb.jpg";
  this.lib_images["bqbs"] =new Image(size_cell,size_cell); this.lib_images["bqbs"].src =this.pathImageBoard_+"bqbs.jpg";
  this.lib_images["bqw"]  =new Image(size_cell,size_cell); this.lib_images["bqw"].src  =this.pathImageBoard_+"bqw.jpg";
  this.lib_images["bqws"] =new Image(size_cell,size_cell); this.lib_images["bqws"].src =this.pathImageBoard_+"bqws.jpg";
  this.lib_images["brb"]  =new Image(size_cell,size_cell); this.lib_images["brb"].src  =this.pathImageBoard_+"brb.jpg";
  this.lib_images["brbs"] =new Image(size_cell,size_cell); this.lib_images["brbs"].src =this.pathImageBoard_+"brbs.jpg";
  this.lib_images["brw"]  =new Image(size_cell,size_cell); this.lib_images["brw"].src  =this.pathImageBoard_+"brw.jpg";
  this.lib_images["brws"] =new Image(size_cell,size_cell); this.lib_images["brws"].src =this.pathImageBoard_+"brws.jpg";
  this.lib_images["bs"]   =new Image(size_cell,size_cell); this.lib_images["bs"].src   =this.pathImageBoard_+"bs.jpg";
  this.lib_images["w"]    =new Image(size_cell,size_cell); this.lib_images["w"].src    =this.pathImageBoard_+"w.jpg";
  this.lib_images["wbb"]  =new Image(size_cell,size_cell); this.lib_images["wbb"].src  =this.pathImageBoard_+"wbb.jpg";
  this.lib_images["wbbs"] =new Image(size_cell,size_cell); this.lib_images["wbbs"].src =this.pathImageBoard_+"wbbs.jpg";
  this.lib_images["wbw"]  =new Image(size_cell,size_cell); this.lib_images["wbw"].src  =this.pathImageBoard_+"wbw.jpg";
  this.lib_images["wbws"] =new Image(size_cell,size_cell); this.lib_images["wbws"].src =this.pathImageBoard_+"wbws.jpg";
  this.lib_images["wkb"]  =new Image(size_cell,size_cell); this.lib_images["wkb"].src  =this.pathImageBoard_+"wkb.jpg";
  this.lib_images["wkbs"] =new Image(size_cell,size_cell); this.lib_images["wkbs"].src =this.pathImageBoard_+"wkbs.jpg";
  this.lib_images["wkw"]  =new Image(size_cell,size_cell); this.lib_images["wkw"].src  =this.pathImageBoard_+"wkw.jpg";
  this.lib_images["wkws"] =new Image(size_cell,size_cell); this.lib_images["wkws"].src =this.pathImageBoard_+"wkws.jpg";
  this.lib_images["wnb"]  =new Image(size_cell,size_cell); this.lib_images["wnb"].src  =this.pathImageBoard_+"wnb.jpg";
  this.lib_images["wnbs"] =new Image(size_cell,size_cell); this.lib_images["wnbs"].src =this.pathImageBoard_+"wnbs.jpg";
  this.lib_images["wnw"]  =new Image(size_cell,size_cell); this.lib_images["wnw"].src  =this.pathImageBoard_+"wnw.jpg";
  this.lib_images["wnws"] =new Image(size_cell,size_cell); this.lib_images["wnws"].src =this.pathImageBoard_+"wnws.jpg";
  this.lib_images["wpb"]  =new Image(size_cell,size_cell); this.lib_images["wpb"].src  =this.pathImageBoard_+"wpb.jpg";
  this.lib_images["wpbs"] =new Image(size_cell,size_cell); this.lib_images["wpbs"].src =this.pathImageBoard_+"wpbs.jpg";
  this.lib_images["wpw"]  =new Image(size_cell,size_cell); this.lib_images["wpw"].src  =this.pathImageBoard_+"wpw.jpg";
  this.lib_images["wpws"] =new Image(size_cell,size_cell); this.lib_images["wpws"].src =this.pathImageBoard_+"wpws.jpg";
  this.lib_images["wqb"]  =new Image(size_cell,size_cell); this.lib_images["wqb"].src  =this.pathImageBoard_+"wqb.jpg";
  this.lib_images["wqbs"] =new Image(size_cell,size_cell); this.lib_images["wqbs"].src =this.pathImageBoard_+"wqbs.jpg";
  this.lib_images["wqw"]  =new Image(size_cell,size_cell); this.lib_images["wqw"].src  =this.pathImageBoard_+"wqw.jpg";
  this.lib_images["wqws"] =new Image(size_cell,size_cell); this.lib_images["wqws"].src =this.pathImageBoard_+"wqws.jpg";
  this.lib_images["wrb"]  =new Image(size_cell,size_cell); this.lib_images["wrb"].src  =this.pathImageBoard_+"wrb.jpg";
  this.lib_images["wrbs"] =new Image(size_cell,size_cell); this.lib_images["wrbs"].src =this.pathImageBoard_+"wrbs.jpg";
  this.lib_images["wrw"]  =new Image(size_cell,size_cell); this.lib_images["wrw"].src  =this.pathImageBoard_+"wrw.jpg";
  this.lib_images["wrws"] =new Image(size_cell,size_cell); this.lib_images["wrws"].src =this.pathImageBoard_+"wrws.jpg";
  this.lib_images["ws"]   =new Image(size_cell,size_cell); this.lib_images["ws"].src   =this.pathImageBoard_+"ws.jpg";

  /* Таблица ходов, нумерация ходов начинается от ноля.*/
  this.c_wpiece     =0; //c_wpiece
  this.c_wmove      =1; //c_wmove
  this.c_w_to_piece =2; //c_w_to_piece
  this.c_w_isCheck  =3; //c_w_isCheck
  this.c_bpiece     =4; //c_bpiece
  this.c_bmove      =5; //c_bmove
  this.c_b_to_piece =6; //c_b_to_piece
  this.c_b_isCheck  =7; //c_b_isCheck
  this.table_moves_ =new Array();
//позиции в таблице операций для вставки ссылок
  this.c_ins_link_make_move =4;
  this.c_ins_link_drawn =6;
  this.c_ins_link_accept_drawn =6;
  this.c_ins_link_in_variant =8;

//------------------------------------------------------------------------------------
  this.color_cell_ =function(cell_){
     if ((cell_ == 'B8') || (cell_ == 'D8') || (cell_ == 'F8') || (cell_ == 'H8') ||
         (cell_ == 'A7') || (cell_ == 'C7') || (cell_ == 'E7') || (cell_ == 'G7') ||
         (cell_ == 'B6') || (cell_ == 'D6') || (cell_ == 'F6') || (cell_ == 'H6') ||
         (cell_ == 'A5') || (cell_ == 'C5') || (cell_ == 'E5') || (cell_ == 'G5') ||
         (cell_ == 'B4') || (cell_ == 'D4') || (cell_ == 'F4') || (cell_ == 'H4') ||
         (cell_ == 'A3') || (cell_ == 'C3') || (cell_ == 'E3') || (cell_ == 'G3') ||
         (cell_ == 'B2') || (cell_ == 'D2') || (cell_ == 'F2') || (cell_ == 'H2') ||
         (cell_ == 'A1') || (cell_ == 'C1') || (cell_ == 'E1') || (cell_ == 'G1'))
       return 'b';
      else
       return 'w';
  }//color_cell_
//------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------
  this.image_cell_ =function(cell_char,cell_num,isSelect){
/*    var result_;
    result_ =this.pathImageBoard_ + this.board_[cell_char][cell_num] +this.color_cell_(cell_char+cell_num);
    result_ +=(isSelect ? 's' : '') + '.jpg';
    return result_;
*/
    return this.board_[cell_char][cell_num] + this.color_cell_(cell_char+cell_num) + (isSelect ? 's' : '');   
  }//image_cell_
//------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------
  this.cell_table_move =function(num_move,is_white){
    var result_ ='';
    if (is_white){
         if (this.table_moves_[--num_move][this.c_wpiece] != '')
           result_ +='<IMG style="border:none" src="'+ this.pathImage_ + 'fw' + this.table_moves_[num_move][this.c_wpiece] + '.png"/>';
         result_ +=this.table_moves_[num_move][this.c_wmove];
         if (this.table_moves_[num_move][this.c_w_to_piece] != '')
           result_ +='<IMG style="border:none" src="'+ this.pathImage_ + 'fw' + this.table_moves_[num_move][this.c_w_to_piece] + '.png"/>';
         result_ +=this.table_moves_[num_move][this.c_w_isCheck];
    }else{
         if (this.table_moves_[--num_move][this.c_bpiece] != '')
           result_ +='<IMG style="border:none" src="'+ this.pathImage_ + 'fw' + this.table_moves_[num_move][this.c_bpiece] + '.png"/>';
         result_ +=this.table_moves_[num_move][this.c_bmove];
         if (this.table_moves_[num_move][this.c_b_to_piece] != '')
           result_ +='<IMG style="border:none" src="'+ this.pathImage_ + 'fw' + this.table_moves_[num_move][this.c_b_to_piece] + '.png"/>';
         result_ +=this.table_moves_[num_move][this.c_b_isCheck];
    }
    return result_;
  }//cell_table_move
//------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------
  this.make_table_moves =function(num_last_move_){
    var rows_=20;
    var table_ =document.getElementById("table_moves");
    var style_1 ='"border-top: none; border-left: none; border-right: 1px solid black; border-bottom: none"';
    var style_2 ='"border-top: none; border-left: none; border-right: 2px solid black; border-bottom: none"';
    var style_3 ='"border-top: 1px solid black; border-left: none; border-right: 1px solid black; border-bottom: none; white-space:nowrap"';
    var style_4 ='"border-top: 1px solid black; border-left: none; border-right: 2px solid black; border-bottom: none; white-space:nowrap"';
    var style_5 ='"border-top: 1px solid black; border-left: none; border-right: none; border-bottom: none; white-space:nowrap"';

    var s ='<TABLE style ="border: 1px solid black" cellspacing="0" cellpadding="3">'+
           '<COL span="6">' +
           '<TBODY>' +
           '<TR><TD style='+style_1+'>ход</TD><TD style='+style_1+'>белые</TD><TD style='+style_2+'>черные</TD>'+
               '<TD style='+style_1+'>ход</TD><TD style='+style_1+'>белые</TD><TD style="border:none">черные</TD>'+
           '</TR>';

    while (num_last_move_ > (rows_*2)) rows_*=2;
    for (var i=1; i <=rows_; i++){
       s +='<TR><TD style=' + style_3 +'>'+i+'</TD>'+
               '<TD id="move_white_'+i +'" style=' + style_3 +'>'+((this.table_moves_.length >=i) ? this.cell_table_move(i,true) : '&nbsp;')+'</TD>'+
               '<TD id="move_black_'+i +'" style=' + style_4 +'>'+(((this.table_moves_.length >=i) && (this.table_moves_[i-1][this.c_bmove] != '')) ? this.cell_table_move(i,false) : '&nbsp;')+'</TD>'+
               '<TD style=' + style_3 +'>'+(i+rows_)+'</TD>'+
               '<TD id="move_white_'+(i+rows_)+ '" style=' + style_3 +'>'+((this.table_moves_.length >=(i+rows_)) ? this.cell_table_move(i+rows_,true) : '&nbsp;')+'</TD>'+
               '<TD id="move_black_'+(i+rows_)+ '" style=' + style_5 +'>'+(((this.table_moves_.length >=(i+rows_)) && (this.table_moves_[i+rows_-1][this.c_bmove] != '')) ? this.cell_table_move(i+rows_,false) : '&nbsp;')+'</TD>'+
           '</TR>';
    }//for
    s +='</TBODY></TABLE>';
    table_.innerHTML =s;
  }//make_table_moves
//------------------------------------------------------------------------------------

//добавляет ход в this.table_moves_
//------------------------------------------------------------------------------------
  this.add_to_table_moves =function(cell1,cell2,o_move){
    var is_move_white = this.board_[cell1.charAt(0)][cell1.charAt(1)].charAt(0) =='w';
    if (is_move_white)
      this.table_moves_[this.table_moves_.length] =new Array("","","","","","","","");
    var i =this.table_moves_.length-1;

    if (is_move_white){
//короткая рокировка белых
      if ((cell1 == 'E1') && (cell2 == 'G1') && (this.board_[cell1.charAt(0)][cell1.charAt(1)] =='wk'))
        this.table_moves_[i][this.c_wmove] ='0-0';
//длинная рокировка белых
      else if ((cell1 == 'E1') && (cell2 == 'C1') && (this.board_[cell1.charAt(0)][cell1.charAt(1)] =='wk'))
        this.table_moves_[i][this.c_wmove] ='0-0-0';
//взятие на проходе
      else if ((this.board_[cell1.charAt(0)][cell1.charAt(1)] =='wp') && (cell1.charAt(1)==5) &&
               (cell2.charAt(0) !=cell1.charAt(0)) && (this.board_[cell2.charAt(0)][cell2.charAt(1)] ==''))
        this.table_moves_[i][this.c_wmove] =(cell1+":"+cell2).toLowerCase();
      else{
        if (this.board_[cell2.charAt(0)][cell2.charAt(1)] !='')
          this.table_moves_[i][this.c_wmove] =(cell1+":"+cell2).toLowerCase();
         else
          this.table_moves_[i][this.c_wmove] =(cell1+"-"+cell2).toLowerCase();
        this.table_moves_[i][this.c_w_to_piece] =o_move.WPiece_;
        if (this.board_[cell1.charAt(0)][cell1.charAt(1)] !='wp')
          this.table_moves_[i][this.c_wpiece] =this.board_[cell1.charAt(0)][cell1.charAt(1)].charAt(1);
        if (o_move.w_isCheck_) this.table_moves_[i][this.c_w_isCheck] ='+';
          else if (o_move.w_isCheckMate_) this.table_moves_[i][this.c_w_isCheck] ='#';
      }
    } else {
//короткая рокировкка черных
      if ((cell1 == 'E8') && (cell2 == 'G8') && (this.board_[cell1.charAt(0)][cell1.charAt(1)] =='bk'))
        this.table_moves_[i][this.c_bmove] ='0-0';
//длинная рокировка черных
      else if ((cell1 == 'E8') && (cell2 == 'C8') && (this.board_[cell1.charAt(0)][cell1.charAt(1)] =='bk'))
        this.table_moves_[i][this.c_bmove] ='0-0-0';
//взятие на проходе
      else if ((this.board_[cell1.charAt(0)][cell1.charAt(1)] =='bp') && (cell1.charAt(1)==4) &&
               (cell2.charAt(0) !=cell1.charAt(0)) && (this.board_[cell2.charAt(0)][cell2.charAt(1)] ==''))
        this.table_moves_[i][this.c_bmove] =(cell1+":"+cell2).toLowerCase();
      else{
        if (this.board_[cell2.charAt(0)][cell2.charAt(1)] !='')
          this.table_moves_[i][this.c_bmove] =(cell1+":"+cell2).toLowerCase();
         else
          this.table_moves_[i][this.c_bmove] =(cell1+"-"+cell2).toLowerCase();
        this.table_moves_[i][this.c_b_to_piece] =o_move.BPiece_;
        if (this.board_[cell1.charAt(0)][cell1.charAt(1)] !='bp')
          this.table_moves_[i][this.c_bpiece] =this.board_[cell1.charAt(0)][cell1.charAt(1)].charAt(1);
        if (o_move.b_isCheck_) this.table_moves_[i][this.c_b_isCheck] ='+';
          else if (o_move.b_isCheckMate_) this.table_moves_[i][this.c_b_isCheck] ='#';
      }
    }
  }//add_to_table_moves
//------------------------------------------------------------------------------------

//совершает ход (таблицу ходов HTML не изменяет)
//------------------------------------------------------------------------------------
  this.move_on_board =function(cell1,cell2,o_move){
    this.add_to_table_moves(cell1,cell2,o_move);

    var is_move_white = this.board_[cell1.charAt(0)][cell1.charAt(1)].charAt(0) =='w';
    if (is_move_white){
//короткая рокировка белых
      if ((cell1 == 'E1') && (cell2 == 'G1') && (this.board_[cell1.charAt(0)][cell1.charAt(1)] =='wk')){
        this.board_[cell2.charAt(0)][cell2.charAt(1)] =this.board_[cell1.charAt(0)][cell1.charAt(1)];
        this.board_[cell1.charAt(0)][cell1.charAt(1)] ='';
        this.board_['F']['1'] ='wr';
        this.board_['H']['1'] ='';
        document.getElementById(cell1).src =this.lib_images[this.image_cell_(cell1.charAt(0),cell1.charAt(1),false)].src;
        document.getElementById(cell2).src =this.lib_images[this.image_cell_(cell2.charAt(0),cell2.charAt(1),false)].src;
        document.getElementById('F1').src =this.lib_images[this.image_cell_('F','1',false)].src;
        document.getElementById('H1').src =this.lib_images[this.image_cell_('H','1',false)].src;
//длинная рокировка белых
      }else if ((cell1 == 'E1') && (cell2 == 'C1') && (this.board_[cell1.charAt(0)][cell1.charAt(1)] =='wk')){
        this.board_[cell2.charAt(0)][cell2.charAt(1)] =this.board_[cell1.charAt(0)][cell1.charAt(1)];
        this.board_[cell1.charAt(0)][cell1.charAt(1)] ='';
        this.board_['D']['1'] ='wr';
        this.board_['A']['1'] ='';
        document.getElementById(cell1).src =this.lib_images[this.image_cell_(cell1.charAt(0),cell1.charAt(1),false)].src;
        document.getElementById(cell2).src =this.lib_images[this.image_cell_(cell2.charAt(0),cell2.charAt(1),false)].src;
        document.getElementById('D1').src =this.lib_images[this.image_cell_('D','1',false)].src;
        document.getElementById('A1').src =this.lib_images[this.image_cell_('A','1',false)].src;
//взятие на проходе
      }else if ((this.board_[cell1.charAt(0)][cell1.charAt(1)] =='wp') && (cell1.charAt(1)==5) &&
               (cell2.charAt(0) !=cell1.charAt(0)) && (this.board_[cell2.charAt(0)][cell2.charAt(1)] =='')){
        this.board_[cell2.charAt(0)][cell2.charAt(1)] =this.board_[cell1.charAt(0)][cell1.charAt(1)];
        this.board_[cell1.charAt(0)][cell1.charAt(1)] ='';
        this.board_[cell2.charAt(0)]['5'] ='';
        document.getElementById(cell1).src =this.lib_images[this.image_cell_(cell1.charAt(0),cell1.charAt(1),false)].src;
        document.getElementById(cell2).src =this.lib_images[this.image_cell_(cell2.charAt(0),cell2.charAt(1),false)].src;
        document.getElementById(cell2.charAt(0)+'5').src =this.lib_images[this.image_cell_(cell2.charAt(0),'5',false)].src;
      }else{
        if (o_move.WPiece_ !='')
          this.board_[cell2.charAt(0)][cell2.charAt(1)] ='w' + o_move.WPiece_;
         else
          this.board_[cell2.charAt(0)][cell2.charAt(1)] =this.board_[cell1.charAt(0)][cell1.charAt(1)];
        this.board_[cell1.charAt(0)][cell1.charAt(1)] ='';
        document.getElementById(cell1).src =this.lib_images[this.image_cell_(cell1.charAt(0),cell1.charAt(1),false)].src;
        document.getElementById(cell2).src =this.lib_images[this.image_cell_(cell2.charAt(0),cell2.charAt(1),false)].src;
      }
    } else {
//короткая рокировкка черных
      if ((cell1 == 'E8') && (cell2 == 'G8') && (this.board_[cell1.charAt(0)][cell1.charAt(1)] =='bk')){
        this.board_[cell2.charAt(0)][cell2.charAt(1)] =this.board_[cell1.charAt(0)][cell1.charAt(1)];
        this.board_[cell1.charAt(0)][cell1.charAt(1)] ='';
        this.board_['F']['8'] ='br';
        this.board_['H']['8'] ='';
        document.getElementById(cell1).src =this.lib_images[this.image_cell_(cell1.charAt(0),cell1.charAt(1),false)].src;
        document.getElementById(cell2).src =this.lib_images[this.image_cell_(cell2.charAt(0),cell2.charAt(1),false)].src;
        document.getElementById('F8').src =this.lib_images[this.image_cell_('F','8',false)].src;
        document.getElementById('H8').src =this.lib_images[this.image_cell_('H','8',false)].src;
//длинная рокировка черных
      }else if ((cell1 == 'E8') && (cell2 == 'C8') && (this.board_[cell1.charAt(0)][cell1.charAt(1)] =='bk')){
        this.board_[cell2.charAt(0)][cell2.charAt(1)] =this.board_[cell1.charAt(0)][cell1.charAt(1)];
        this.board_[cell1.charAt(0)][cell1.charAt(1)] ='';
        this.board_['D']['8'] ='br';
        this.board_['A']['8'] ='';
        document.getElementById(cell1).src =this.lib_images[this.image_cell_(cell1.charAt(0),cell1.charAt(1),false)].src;
        document.getElementById(cell2).src =this.lib_images[this.image_cell_(cell2.charAt(0),cell2.charAt(1),false)].src;
        document.getElementById('D8').src =this.lib_images[this.image_cell_('D','8',false)].src;
        document.getElementById('A8').src =this.lib_images[this.image_cell_('A','8',false)].src;
//взятие на проходе
      }else if ((this.board_[cell1.charAt(0)][cell1.charAt(1)] =='bp') && (cell1.charAt(1)==4) &&
               (cell2.charAt(0) !=cell1.charAt(0)) && (this.board_[cell2.charAt(0)][cell2.charAt(1)] =='')){
        this.board_[cell2.charAt(0)][cell2.charAt(1)] =this.board_[cell1.charAt(0)][cell1.charAt(1)];
        this.board_[cell1.charAt(0)][cell1.charAt(1)] ='';
        this.board_[cell2.charAt(0)]['4'] ='';
        document.getElementById(cell1).src =this.lib_images[this.image_cell_(cell1.charAt(0),cell1.charAt(1),false)].src;
        document.getElementById(cell2).src =this.lib_images[this.image_cell_(cell2.charAt(0),cell2.charAt(1),false)].src;
        document.getElementById(cell2.charAt(0)+'4').src =this.lib_images[this.image_cell_(cell2.charAt(0),'4',false)].src;
      }else{
        if (o_move.BPiece_ !='')
          this.board_[cell2.charAt(0)][cell2.charAt(1)] ='b' + o_move.BPiece_;
         else
          this.board_[cell2.charAt(0)][cell2.charAt(1)] =this.board_[cell1.charAt(0)][cell1.charAt(1)];
        this.board_[cell1.charAt(0)][cell1.charAt(1)] ='';
        document.getElementById(cell1).src =this.lib_images[this.image_cell_(cell1.charAt(0),cell1.charAt(1),false)].src;
        document.getElementById(cell2).src =this.lib_images[this.image_cell_(cell2.charAt(0),cell2.charAt(1),false)].src;
      }
    }
  }//move_on_board
//------------------------------------------------------------------------------------

//совершение ходов
//------------------------------------------------------------------------------------
  this.make_moves =function(o_result_){
    var last_move =this.table_moves_.length;
    var is_white_last_move = (last_move > 0) && (this.table_moves_[last_move-1][this.c_bmove] =='');

    var c=o_result_.o_status_game.moves_.length;
    if ((c > 0) && (!document.getElementById('move_white_'+o_result_.o_status_game.moves_[c-1].num_)))
      this.make_table_moves(o_result_.o_status_game.moves_[c-1].num_);
    for (var i=0; i < c; i++){
       if (o_result_.o_status_game.moves_[i].num_ > last_move){
         this.move_on_board(o_result_.o_status_game.moves_[i].cell1_white_,o_result_.o_status_game.moves_[i].cell2_white_,o_result_.o_status_game.moves_[i]);
         document.getElementById('move_white_'+o_result_.o_status_game.moves_[i].num_).innerHTML
           =this.cell_table_move(o_result_.o_status_game.moves_[i].num_,true);
       }
       if (((o_result_.o_status_game.moves_[i].num_ > last_move) ||
            ((o_result_.o_status_game.moves_[i].num_ == last_move) && is_white_last_move)) &&
           (o_result_.o_status_game.moves_[i].cell1_black_ != '')){
         this.move_on_board(o_result_.o_status_game.moves_[i].cell1_black_,o_result_.o_status_game.moves_[i].cell2_black_,o_result_.o_status_game.moves_[i]);
         document.getElementById('move_black_'+o_result_.o_status_game.moves_[i].num_).innerHTML
           =this.cell_table_move(o_result_.o_status_game.moves_[i].num_,false);
       }
    }//for
  }//make_moves
//------------------------------------------------------------------------------------

//отображает состояние партии
//------------------------------------------------------------------------------------
  this.show_status=function(o_result_){
    var s ='';
    document.getElementById('clock_white').innerHTML =o_result_.o_status_game.time_white_;
    document.getElementById('clock_black').innerHTML =o_result_.o_status_game.time_black_;

    this.make_moves(o_result_);
    if (o_result_.o_status_game.result_ != '')
      document.getElementById('info_result_or_move').innerHTML ='Результат: ' + o_result_.o_status_game.result_;
    this.may_move_=o_result_.o_status_game.may_move;
    this.color_ =o_result_.o_status_game.color_;

//отображение ссылок предложить/принять ничью
    if ((o_result_.o_status_game.result_ != '') || (o_result_.o_status_game.offerDrawn !=''))
      if (this.row_operation_drawn !=-1) this.del_link_drawn();
    if ((o_result_.o_status_game.result_ != '') || (o_result_.o_status_game.offerDrawn =='') ||
        !(((o_result_.o_status_game.offerDrawn =='w') && (this.color_ =='b')) ||
          ((o_result_.o_status_game.offerDrawn =='b') && (this.color_ =='w')))){
      if (this.row_operation_accept_drawn !=-1) this.del_link_accept_drawn();
    }
    if ((o_result_.o_status_game.result_ == '') && (this.color_ !=''))
      if (o_result_.o_status_game.offerDrawn ==''){
          if (this.row_operation_drawn ==-1) this.add_link_drawn();
      }else if (((o_result_.o_status_game.offerDrawn =='w') && (this.color_ =='b')) ||
                ((o_result_.o_status_game.offerDrawn =='b') && (this.color_ =='w')))
                  if (this.row_operation_accept_drawn ==-1) this.add_link_accept_drawn();

//отображает входящие варианты
    var new_variants =false;
    if (o_result_.o_status_game.in_variants_.length != 0){
      for (var i=0; i < o_result_.o_status_game.in_variants_.length; i++)
         if (!this.exists_id_variants(o_result_.o_status_game.in_variants_[i])){
            if (this.in_variants.length > 0){
              this.del_links_in_variants();
              this.in_variants.length =0;
            }
            new_variants =true;
            break;
         }
      if (new_variants){
        for (var i=0; i < o_result_.o_status_game.in_variants_.length; i++)
           this.in_variants[i] =o_result_.o_status_game.in_variants_[i];
        this.add_links_in_variants();
      }
    }else
      if (this.in_variants.length > 0){
        this.del_links_in_variants();
        this.in_variants.length =0;
      }

//Информация о входящих вариантах и предложений ничьей
    s ='';
    if (this.in_variants.length > 0)
      s +='<DIV style="font-size: 14pt; color:white">' +
                  'присланы варианты продолжения' +
               '</DIV>';
    if (o_result_.o_status_game.offerDrawn !=''){
//      if (s == '') s +='<BR/>';
      s +='<DIV style="font-size: 14pt; color:white">';
      if (o_result_.o_status_game.offerDrawn =='w')
        s +='белые предложили ничью';
       else
        s +='чёрные предложили ничью';
      s +='</DIV>';
    }
    document.getElementById('label_drawn').innerHTML =s;

    if (!this.may_move_){
      if (this.sel_cell1_ != ''){
        document.getElementById(this.sel_cell1_).src =this.lib_images[this.image_cell_(this.sel_cell1_.charAt(0),
                                                                                       this.sel_cell1_.charAt(1),false)].src;
        this.sel_cell1_ ='';
      }
      if (this.sel_cell2_ != ''){
        document.getElementById(this.sel_cell2_).src =this.lib_images[this.image_cell_(this.sel_cell2_.charAt(0),
                                                                                       this.sel_cell2_.charAt(1),false)].src;
        this.sel_cell2_ ='';
      }
      this.del_link_make_move();
    }
  }//show_status
//------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------
  this.onready_status =function(){
    var o_result_;
    var o_this_ =this;
    if (this.o_xmlhttp_status_.readyState ==4){
      if (this.o_xmlhttp_status_.status ==200){
        try{
//        alert(this.o_xmlhttp_status_.responseText);
          o_result_ =JSON.parse(this.o_xmlhttp_status_.responseText);
//          alert(o_result_.text_error_);
          if (o_result_.text_error_ ==='')
            this.show_status(o_result_);
        }catch(e){
//        alert(this.o_xmlhttp_status_.responseText);
        }
      }
//      this.o_timer_status_ =window.setTimeout(function(){o_this_.read_status();},2000);
    }
  }//onready_status
//------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------
  this.read_status =function(){
    var q_ =new cl_game_to_server_();
    var o_this_ =this;
    q_.type_=1;
    q_.num_last_move =this.table_moves_.length;
    q_.num_game_ =this.num_game_;
    if (this.o_xmlhttp_status_.readyState !=0)
      this.o_xmlhttp_status_.abort();

    this.o_timer_status_ =window.setTimeout(function(){o_this_.read_status();},2000);

    this.o_xmlhttp_status_.open("post","ajax_game.php",true);
    this.o_xmlhttp_status_.onreadystatechange=function(){
      o_this_.onready_status();
    }
    this.o_xmlhttp_status_.send(JSON.stringify(q_));
  }//read_status
//------------------------------------------------------------------------------------


//добавляет ссылку предложить ничью
//------------------------------------------------------------------------------------
  this.add_link_drawn =function(){
    var i=this.c_ins_link_drawn;
    var tr;
    var td;

    this.row_operation_drawn =i+this.count_row_operation_make_move;
    tr =document.getElementById("operations_").insertRow(i++);
    td =document.createElement("td");
    td.setAttribute("height","25");
    td.setAttribute("valign","top");
    td.setAttribute("class","left_");
    td.innerHTML ='<A href="MainPage.php?link_=game&id='+this.num_game_+'&question_drawn=yes">' +
                  ' <IMG src="Image/label_drawn.png" width="181" height="23" border="0" style="border:none; vertical-align: top; cursor:pointer">' +
                  '</A>';
    tr.appendChild(td);
    td =document.createElement("td");
    td.setAttribute("colspan","2");
    td.setAttribute("valign","top");
    td.innerHTML ='<A href="MainPage.php?link_=game&id='+this.num_game_+'&question_drawn=yes">' +
                  ' <IMG src="Image/button.jpg" width="24" height="25" border="0" style="border:none; vertical-align: top; cursor:pointer">'+
                  '</A>';
    tr.appendChild(td);
    tr =document.getElementById("operations_").insertRow(i++);
    td =document.createElement("td");
    td.setAttribute("height","15");
    td.setAttribute("colspan","3");
    tr.appendChild(td);

    if (this.row_in_variant !=-1) this.row_in_variant +=2;
  }//add_link_drawn
//------------------------------------------------------------------------------------

//удаляет ссылку предложить ничью
//------------------------------------------------------------------------------------
  this.del_link_drawn =function(){
    document.getElementById("operations_").deleteRow(this.row_operation_drawn);
    document.getElementById("operations_").deleteRow(this.row_operation_drawn);

    this.row_operation_drawn =-1;
    if (this.row_in_variant !=-1) this.row_in_variant -=2;
  }//clear_table_operations
//------------------------------------------------------------------------------------

//добавляет ссылку принять ничью
//------------------------------------------------------------------------------------
  this.add_link_accept_drawn =function(){
    var i=this.c_ins_link_accept_drawn;
    var tr;
    var td;

    this.row_operation_accept_drawn =i+this.count_row_operation_make_move;
    tr =document.getElementById("operations_").insertRow(i++);
    td =document.createElement("td");
    td.setAttribute("height","25");
    td.setAttribute("valign","top");
    td.setAttribute("class","left_");
    td.innerHTML ='<A href="MainPage.php?link_=game&id='+this.num_game_+'&question_accept_drawn=yes">' +
                  ' <IMG src="Image/label_accept_drawn.png" width="181" height="23" border="0" style="border:none; vertical-align: top; cursor:pointer">' +
                  '</A>';
    tr.appendChild(td);
    td =document.createElement("td");
    td.setAttribute("colspan","2");
    td.setAttribute("valign","top");
    td.innerHTML ='<A href="MainPage.php?link_=game&id='+this.num_game_+'&question_accept_drawn=yes">' +
                  ' <IMG src="Image/button.jpg" width="24" height="25" border="0" style="border:none; vertical-align: top; cursor:pointer">'+
                  '</A>';
    tr.appendChild(td);
    tr =document.getElementById("operations_").insertRow(i++);
    td =document.createElement("td");
    td.setAttribute("height","15");
    td.setAttribute("colspan","3");
    tr.appendChild(td);

    if (this.row_in_variant !=-1) this.row_in_variant +=2;
  }//add_link_drawn
//------------------------------------------------------------------------------------

//удаляет ссылку принять ничью
//------------------------------------------------------------------------------------
  this.del_link_accept_drawn =function(){
    document.getElementById("operations_").deleteRow(this.row_operation_accept_drawn);
    document.getElementById("operations_").deleteRow(this.row_operation_accept_drawn);

    this.row_operation_accept_drawn =-1;
    if (this.row_in_variant !=-1) this.row_in_variant -=2;
  }//del_link_accept_drawn
//------------------------------------------------------------------------------------

//добавляет ссылки входящие варианты
//------------------------------------------------------------------------------------
  this.add_links_in_variants =function(){
    var i=this.c_ins_link_in_variant + this.count_row_operation_make_move;
    var tr;
    var td;

    this.row_in_variant =i;
    for (var j=0; j < this.in_variants.length; j++){
        tr =document.getElementById("operations_").insertRow(i++);
        td =document.createElement("td");
        td.setAttribute("height","25");
        td.setAttribute("valign","top");
        td.setAttribute("class","left_");
        td.innerHTML ='<A href="MainPage.php?link_=in_variant&id='+this.num_game_+'&id_variant='+this.in_variants[j]+'">'+
                      ' <IMG src="Image/label_in_variant.png" width="181" height="23" border="0" style="border:none; vertical-align: top; cursor:pointer">' +
                      '</A>';
        tr.appendChild(td);
        td =document.createElement("td");
        td.setAttribute("colspan","2");
        td.setAttribute("valign","top");
        td.innerHTML ='<A href="MainPage.php?link_=in_variant&id='+this.num_game_+'&id_variant='+this.in_variants[j]+'">'+
                      ' <IMG src="Image/button.jpg" width="24" height="25" border="0" style="border:none; vertical-align: top; cursor:pointer">'+
                      '</A>';
        tr.appendChild(td);
        tr =document.getElementById("operations_").insertRow(i++);
        td =document.createElement("td");
        td.setAttribute("height","15");
        td.setAttribute("colspan","3");
        tr.appendChild(td);
    }
  }//add_links_in_variants
//------------------------------------------------------------------------------------

//удаляет ссылки на входящие варианты
//------------------------------------------------------------------------------------
  this.del_links_in_variants =function(){
    for (var i=0; i < this.in_variants.length; i++){
      document.getElementById("operations_").deleteRow(this.row_in_variant);
      document.getElementById("operations_").deleteRow(this.row_in_variant);
    }
    this.row_in_variant =-1;
  }//del_links_in_variants
//------------------------------------------------------------------------------------

//функция проверяет существование id_ варианта в массиве in_variants
//------------------------------------------------------------------------------------
  this.exists_id_variants =function(id_){
    for (var i=0; i < this.in_variants.length; i++)
       if (this.in_variants[i] ==id_)
         return true;
    return false;
  }//exists_id_variants
//------------------------------------------------------------------------------------

//обработка нажатий на доске
//---------------------------------------------------------------------------------------------------------
  this.onClickCell =function(cell_){
    if (this.o_xmlhttp_status_){
      var c1 = this.sel_cell1_;

      if (this.may_move_){
        if (c1 == ''){
          if ((this.board_[cell_.charAt(0)][cell_.charAt(1)] !='') &&
              (this.board_[cell_.charAt(0)][cell_.charAt(1)].charAt(0) ==this.color_)){
            document.getElementById(cell_).src =this.lib_images[this.image_cell_(cell_.charAt(0),cell_.charAt(1),true)].src;
            this.sel_cell1_ =cell_;
          }

        }else if ((c1 !='') && (c1 ==cell_)){
          document.getElementById(c1).src =this.lib_images[this.image_cell_(c1.charAt(0),c1.charAt(1),false)].src;
          this.sel_cell1_ ='';

        }else if ((c1 != '') && (c1 !=cell_)){
          if ((this.piece_ =='') &&
              (((this.color_=='b') && (cell_.charAt(1) == 1) && (this.board_[c1.charAt(0)][c1.charAt(1)] == 'bp')) ||
               ((this.color_=='w') && (cell_.charAt(1) == 8) && (this.board_[c1.charAt(0)][c1.charAt(1)] == 'wp')))){
            if (this.o_timer_status_){
              clearTimeout(this.o_timer_status_);
              this.o_timer_status_ =null;
            }
            if (this.o_xmlhttp_status_.readyState !=0)
              this.o_xmlhttp_status_.abort();
            var q_ =new cl_game_to_server_();
            q_.type_=2;
            q_.num_last_move =this.table_moves_.length;
            q_.num_game_ =this.num_game_;
            q_.cell_1 = c1;
            q_.cell_2 = cell_;
            this.o_xmlhttp_status_.open("post","ajax_game.php",false);
            this.o_xmlhttp_status_.send(JSON.stringify(q_));
            if (this.o_xmlhttp_status_.status ==200){
             try{
//             alert(this.o_xmlhttp_status_.responseText);
               var o_result_ =JSON.parse(this.o_xmlhttp_status_.responseText);
//             alert(o_result_.text_error_);
               if (o_result_.text_error_ ==='move_access'){
                 this.sel_cell2_ = cell_;
                 var s ='';
                 if (this.color_=='w')
                   s ='<TABLE cellspacing="3">' +
                      '     <COL span="4">' +
                      '     <TR>'+
                      '       <TD><IMG width="68" src="Image/wqb.jpg" style="cursor:pointer" onclick="o_game_.o_modal_.hide_(); o_game_.piece_=\'q\'; o_game_.onClickCell(o_game_.sel_cell2_);"/></TD>'+
                      '       <TD><IMG width="68" src="Image/wrb.jpg" style="cursor:pointer" onclick="o_game_.o_modal_.hide_(); o_game_.piece_=\'r\'; o_game_.onClickCell(o_game_.sel_cell2_);"/></TD>'+
                      '       <TD><IMG width="68" src="Image/wbb.jpg" style="cursor:pointer" onclick="o_game_.o_modal_.hide_(); o_game_.piece_=\'b\'; o_game_.onClickCell(o_game_.sel_cell2_);"/></TD>'+
                      '       <TD><IMG width="68" src="Image/wnb.jpg" style="cursor:pointer" onclick="o_game_.o_modal_.hide_(); o_game_.piece_=\'n\'; o_game_.onClickCell(o_game_.sel_cell2_);"/></TD>'+
                      '     </TR>'+
                      '  </TABLE>';
                  else
                   s ='<TABLE cellspacing="3">' +
                      '     <COL span="4">' +
                      '     <TR>'+
                      '     <TR>'+
                      '       <TD><IMG width="68" src="Image/bqw.jpg" style="cursor:pointer" onclick="o_game_.o_modal_.hide_(); o_game_.piece_=\'q\'; o_game_.onClickCell(o_game_.sel_cell2_);"/></TD>'+
                      '       <TD><IMG width="68" src="Image/brw.jpg" style="cursor:pointer" onclick="o_game_.o_modal_.hide_(); o_game_.piece_=\'r\'; o_game_.onClickCell(o_game_.sel_cell2_);"/></TD>'+
                      '       <TD><IMG width="68" src="Image/bbw.jpg" style="cursor:pointer" onclick="o_game_.o_modal_.hide_(); o_game_.piece_=\'b\'; o_game_.onClickCell(o_game_.sel_cell2_);"/></TD>'+
                      '       <TD><IMG width="68" src="Image/bnw.jpg" style="cursor:pointer" onclick="o_game_.o_modal_.hide_(); o_game_.piece_=\'n\'; o_game_.onClickCell(o_game_.sel_cell2_);"/></TD>'+
                      '     </TR>'+
                      '  </TABLE>';
                 this.o_modal_.content_ =s;
                 this.o_modal_.show_();
               }
             }catch(e){
//              alert(this.o_xmlhttp_status_.responseText);
             }
            }
            this.read_status();

          }else{
            if (this.o_timer_status_){
              clearTimeout(this.o_timer_status_);
              this.o_timer_status_ =null;
            }
            if (this.o_xmlhttp_status_.readyState !=0)
              this.o_xmlhttp_status_.abort();
            var q_ =new cl_game_to_server_();
            q_.type_=3;
            q_.num_last_move =this.table_moves_.length;
            q_.num_game_ =this.num_game_;
            q_.cell_1 = c1;
            q_.cell_2 = cell_;
            q_.piece_ =this.piece_;
            this.o_xmlhttp_status_.open("post","ajax_game.php",false);
            this.o_xmlhttp_status_.send(JSON.stringify(q_));
            if (this.o_xmlhttp_status_.status ==200){
             try{
//             alert(this.o_xmlhttp_status_.responseText);
               var o_result_ =JSON.parse(this.o_xmlhttp_status_.responseText);
//             alert(o_result_.text_error_);
//             if (o_result_.text_error_ ==='move_access'){
               if (o_result_.text_error_ ===''){
                 this.sel_cell1_ ='';
                 this.sel_cell2_ ='';
                 this.show_status(o_result_);
               }
             }catch(e){
//               alert(this.o_xmlhttp_status_.responseText);
             }
            }
            this.piece_ ='';
            this.read_status();
          }
        }
      } //if (this.may_move_)
    }//if (this.o_xmlhttp_status_)
  }//onClickCell
//---------------------------------------------------------------------------------------------------------

  this.init =function(){
    if (this.o_xmlhttp_status_){
      if (this.color_ !='')
        this.make_table_moves(this.table_moves_.length);
      this.read_status();
    }
  }//init
//------------------------------------------------------------------------------------
}//cl_control_game_