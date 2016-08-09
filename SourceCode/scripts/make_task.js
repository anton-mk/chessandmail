/* Автор: Колосовский Антон Михайлович */

function cl_task(pathImages, pathImageFigures){  this.pathImage_ =pathImages;
  this.pathImageFigures_ =pathImageFigures;

  this.board_=new Array();
  this.board_["A"] =new Array('','','','','','','','','');
  this.board_["B"] =new Array('','','','','','','','','');
  this.board_["C"] =new Array('','','','','','','','','');
  this.board_["D"] =new Array('','','','','','','','','');
  this.board_["E"] =new Array('','','','','','','','','');
  this.board_["F"] =new Array('','','','','','','','','');
  this.board_["G"] =new Array('','','','','','','','','');
  this.board_["H"] =new Array('','','','','','','','','');

  this.select_f =''; //выбранная фигура
  this.select_cell =''; //выбранная ячейка

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

  this.image_cell_ =function(cell_char,cell_num,isSelect){
    var result_;
    result_ =this.pathImageFigures_ + this.board_[cell_char][cell_num] +this.color_cell_(cell_char+cell_num);
    result_ +=(isSelect ? 's' : '') + '.jpg';
    return result_;
  }//image_cell_
//------------------------------------------------------------------------------------

//обновляет шахматную клетку
  this.refresh_cell =function(cell1,isSelect){    document.getElementById(cell1).src =this.image_cell_(cell1.charAt(0),cell1.charAt(1),isSelect);
  }//refresh_cell
//------------------------------------------------------------------------------------

//обработчик выбора фигуры
  this.onClickF =function(f_){    if (this.select_f !='')      document.getElementById(this.select_f).src =this.pathImageFigures_ + this.select_f + 'w.jpg';
    if (this.select_f !=f_){
      document.getElementById(f_).src =this.pathImageFigures_ + f_ + 'ws.jpg';
      this.select_f =f_;
    }else this.select_f ='';

    if (this.select_cell !='')
      document.getElementById(this.select_cell).src =this.image_cell_(this.select_cell.charAt(0),this.select_cell.charAt(1),false);
  }//onClickF
//------------------------------------------------------------------------------------

//обработчик нажатий на доске
  this.onClickCell =function(cell_){    if (this.select_f !=''){        this.board_[cell_.charAt(0)][cell_.charAt(1)] =this.select_f;
        this.refresh_cell(cell_,false);
        document.getElementById(this.select_f).src =this.pathImageFigures_ + this.select_f + 'w.jpg';
        this.select_f ='';    }else{        if (this.select_cell !='')
            document.getElementById(this.select_cell).src =this.image_cell_(this.select_cell.charAt(0),this.select_cell.charAt(1),false);
        if ((this.select_cell === cell_) || (this.board_[cell_.charAt(0)][cell_.charAt(1)] ==''))
          this.select_cell ='';         else{            this.select_cell =cell_;
            document.getElementById(cell_).src =this.image_cell_(cell_.charAt(0),cell_.charAt(1),true);         }    }  }//onClickCell
//------------------------------------------------------------------------------------

//очистка доски
  this.clearBoard =function(){    for (var b='A'; b <='H'; b=String.fromCharCode(b.charCodeAt(0)+1))
      for (var n=1; n <=8; n++)        if (this.board_[b][n] !=''){          this.board_[b][n] ='';
          this.refresh_cell(b+n);        }  }//clearBoard

//очистка клетки
  this.onClearCell =function(){    if (this.select_cell !=''){        this.board_[this.select_cell.charAt(0)][this.select_cell.charAt(1)] ='';
        this.refresh_cell(this.select_cell,false);
        this.select_cell ='';    }  }//onClearСell

//подготавливает информацию о позиции
  this.position_info =function(){    var result_ ='';
    for (var b='A'; b <='H'; b=String.fromCharCode(b.charCodeAt(0)+1))
      for (var n=1; n <=8; n++)
        if (this.board_[b][n] !=''){            if (result_ != '') result_ +=',';
            result_ +=this.board_[b][n] + b + n;        }
    document.getElementById('position_').value =result_;  }//position_info
}//cl_task
