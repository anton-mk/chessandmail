//������� � ������������� �����
var catalog_images;

function cl_view_game(){
//������� id �����
  this.prefix_cell;
  this.prefix2_cell =''; //������� ����� ������ ����� (����� ������)

//��������� �������
  this.board_f={};
  this.board_f["A"] =new Array('','','','','','','','','');
  this.board_f["B"] =new Array('','','','','','','','','');
  this.board_f["C"] =new Array('','','','','','','','','');
  this.board_f["D"] =new Array('','','','','','','','','');
  this.board_f["E"] =new Array('','','','','','','','','');
  this.board_f["F"] =new Array('','','','','','','','','');
  this.board_f["G"] =new Array('','','','','','','','','');
  this.board_f["H"] =new Array('','','','','','','','','');
  
//�������� �������  
  this.board_l={};
  this.board_l["A"] =new Array('','','','','','','','','');
  this.board_l["B"] =new Array('','','','','','','','','');
  this.board_l["C"] =new Array('','','','','','','','','');
  this.board_l["D"] =new Array('','','','','','','','','');
  this.board_l["E"] =new Array('','','','','','','','','');
  this.board_l["F"] =new Array('','','','','','','','','');
  this.board_l["G"] =new Array('','','','','','','','','');
  this.board_l["H"] =new Array('','','','','','','','','');

//������� �������
  this.board_c={};
  this.board_c["A"] =new Array('','','','','','','','','');
  this.board_c["B"] =new Array('','','','','','','','','');
  this.board_c["C"] =new Array('','','','','','','','','');
  this.board_c["D"] =new Array('','','','','','','','','');
  this.board_c["E"] =new Array('','','','','','','','','');
  this.board_c["F"] =new Array('','','','','','','','','');
  this.board_c["G"] =new Array('','','','','','','','','');
  this.board_c["H"] =new Array('','','','','','','','','');
  
//������ ���������, ����� ���:
//moves_[]['before']{������=>��������,������=>��������,...}
//        ['after']{������=>��������,������=>��������,...}
  this.moves_ =new Array(); 
  
//������� ��� (������ �� ����)
  this.curr_move =0;
  
//��������� ������ ������ 
  this.record_game ='';
//������� ������������ �������
  this.lexx_ ='';

// ���������� ���� ������
  this.get_color_cell =function(cell_){
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
  }//get_color_cell
  
  
  this.move_forward =function($move_on_board){
    for(var i in this.moves_[this.curr_move]['after']){
      this.board_c[i.charAt(0)][i.charAt(1)] = this.moves_[this.curr_move]['after'][i];
      if($move_on_board){
        $("#"+this.prefix_cell+i).attr("src",catalog_images+
                                             this.board_c[i.charAt(0)][i.charAt(1)]+
                                             this.get_color_cell(i)+".jpg");
        if(this.prefix2_cell !='')
          $("#"+this.prefix2_cell+i).attr("src",catalog_images+
                                               this.board_c[i.charAt(0)][i.charAt(1)]+
                                               this.get_color_cell(i)+".jpg");
      }  
    }//for
    this.curr_move++;
  }//move_forward
  
  this.move_back =function(){
    this.curr_move--;      
    for(var i in this.moves_[this.curr_move]['before']){
      this.board_c[i.charAt(0)][i.charAt(1)] = this.moves_[this.curr_move]['before'][i];
      $("#"+this.prefix_cell+i).attr("src",catalog_images+
                                           this.board_c[i.charAt(0)][i.charAt(1)]+
                                           this.get_color_cell(i)+".jpg");
      if(this.prefix2_cell !='')                                       
        $("#"+this.prefix2_cell+i).attr("src",catalog_images+
                                             this.board_c[i.charAt(0)][i.charAt(1)]+
                                             this.get_color_cell(i)+".jpg");
    }//for
  }//move_back

  this.begin_position =function(){
    var b ={};    
    b["A"] =new Array('','wr','wp','','','','','bp','br');
    b["B"] =new Array('','wn','wp','','','','','bp','bn');
    b["C"] =new Array('','wb','wp','','','','','bp','bb');
    b["D"] =new Array('','wq','wp','','','','','bp','bq');
    b["E"] =new Array('','wk','wp','','','','','bp','bk');
    b["F"] =new Array('','wb','wp','','','','','bp','bb');
    b["G"] =new Array('','wn','wp','','','','','bp','bn');
    b["H"] =new Array('','wr','wp','','','','','bp','br');    
    return b;  
  }//begin_position
  
  this.copy_board =function(source_,dest_){
    for (var b='A'; b <='H'; b=String.fromCharCode(b.charCodeAt(0)+1))
      for (var n=1; n <=8; n++)
        dest_[b][n] =source_[b][n]  ;
  }//copy_board
  
  this.repaint_board =function(){
    for (var b='A'; b <='H'; b=String.fromCharCode(b.charCodeAt(0)+1))
      for (var n=1; n <=8; n++){
        $("#"+this.prefix_cell+b+n).attr("src",catalog_images+
                                               this.board_c[b][n]+
                                               this.get_color_cell(b+n)+".jpg");
        if(this.prefix2_cell !='')
          $("#"+this.prefix2_cell+b+n).attr("src",catalog_images+
                                                 this.board_c[b][n]+
                                                 this.get_color_cell(b+n)+".jpg");
      }//for                                      
  }//repaint_board
  
//���������� ����� ��������, ���� ������ �� ������ - 0  
  this.get_lexx_record_game =function(){
    var result_ =0;    
    var f =false;
    var l;
    
    for(var i=1; i < 11; i++){
      switch(i){
        case 1 :l=this.record_game.match(/^\s*\d+\s*\.\s*/);break;
        case 2 :l=this.record_game.match(/^\.{3}/);break;
        case 3 :l=this.record_game.match(/^\s*-\s*/);break;
        //� - 041A, � - 0440, � -0424, � -041B, � -0421
        case 4 :l=this.record_game.match(/^\u041A\u0440?|^[\u0424\u041B\u0421]/);break;
        case 5 :l=this.record_game.match(/^[a-h][1-8]/);break;
        case 6 :l=this.record_game.match(/^\s*,\s*/);break;
        case 7 :l=this.record_game.match(/^\s*\.\s*/);break;
        case 8 :l=this.record_game.match(/^0-0-0/);break;
        case 9 :l=this.record_game.match(/^0-0/);break; 
        case 10 :l=this.record_game.match(/^\s*/);break;     
      }//switch
      if(l){
        this.lexx_ =l.toString();
        this.record_game =this.record_game.substr(this.lexx_.length);
        this.lexx_.replace(/^\s+|\s+$/g,"");
        result_=i;
        break;
      }
    }//for

    return result_;
  }//get_lexx_record_game
  
  this.get_figure =function(f){
    r ='';  
    switch(f){
      case '��' :r ='k';break;                                  
      case '�'  :r ='q';break;
      case '�'  :r ='r';break;
      case '�'  :r ='n';break;    
      case '�'  :r ='b';break;                      
    }//switch
    return r;  
  }//get_figure
  
  this.parser_record_game =function(){
/* ����������
 *   ��������: 1. �����_����  
 *             2. ���������
 *             3. ����
 *             4. ������
 *             5. ������
 *             6. �������
 *             7. �����
 *             8. ����_���������
 *             9. ���������
 *            10. ���_���������
 *   S ->A. | B. | Z. | K. |A1. | B1. | Z1. | K1.
 *   A ->B ������
 *   B ->C ������
 *   Z ->F ���������
 *   K ->F ����_���������
 *   C ->D -
 *   D ->E ������ | F ������
 *   E ->F ������
 *   F ->A1 ���_��������� | B1 ���_��������� | Z1 ���_��������� | K1 ���_��������� | L ���_���������
 *   A1 ->B1 ������
 *   B1 ->C1 ������
 *   Z1 ->F1 ��������� | M ���������
 *   K1 ->F1 ����_��������� | M ����_���������
 *   C1 ->D1 -
 *   D1 ->E1 ������ | F1 ������ | M ������
 *   E1 ->F1 ������ | M ������
 *   F1 ->�����
 *   G ->A, | B, | Z, | K,
 *   L ->F1 ���������
 *   M ->G �����
 */      
    var currS ='H';
    var i =-1;
    var result_=new Array();
    var n_lexx_ =0;
    
    this.record_game.replace(/\r|\n/g,"");
    this.record_game.replace(/^\s+|\s+$/g,"");
    while((currS != 'Err') && (this.record_game.length >0)){
      n_lexx_ =this.get_lexx_record_game();
      switch(currS){
        case 'H': 
          if(n_lexx_ ==1){
            currS ='F1';
            result_[++i] ={};
          }else currS ='Err'; 
          break;
        case 'F1':
          if(n_lexx_ ==5){
            currS ='D1';
            result_[i]['white'] ={};
            result_[i]['white']['figure_'] ='';
            result_[i]['white']['cell_1'] =this.lexx_.toUpperCase();
          }else if(n_lexx_ ==4){
            currS ='E1';
            result_[i]['white'] ={};
            result_[i]['white']['figure_'] ='w'+this.get_figure(this.lexx_);
          }else if(n_lexx_ ==2){
            currS ='L';
            result_[i]['black'] ={};            
          }else if(n_lexx_ ==8){
            currS ='K1';              
            result_[i]['white'] ={};
            result_[i]['white']['figure_'] ='0-0-0';
          }else if(n_lexx_ ==9){
            currS ='Z1';              
            result_[i]['white'] ={};
            result_[i]['white']['figure_'] ='0-0';
          }else currS ='Err';
          break;  
        case 'D1':  
          if(n_lexx_ ==3){  
            currS ='C1';  
          }else currS ='Err';
          break;  
        case 'C1':  
          if(n_lexx_ ==5){  
            currS ='B1';  
            result_[i]['white']['cell_2'] =this.lexx_.toUpperCase();            
          }else currS ='Err';
          break;  
        case 'K1':
          if(n_lexx_==10){
            currS ='F';
            result_[++i] ={};
            result_[i]['black'] ={};
          }else if (n_lexx_==7){
            currS ='S';
          }else currS ='Err';
          break;  
        case 'Z1':
          if(n_lexx_==10){
            currS ='F';
            result_[++i] ={};
            result_[i]['black'] ={};
          }else if (n_lexx_==7){
            currS ='S';
          }else currS ='Err';
          break;  
        case 'B1':
          if(n_lexx_ ==4){
            currS ='A1';
            result_[i]['white']['figure_after'] ='w'+this.get_figure(this.lexx_);
          }else if(n_lexx_==10){
            currS ='F';
            result_[i]['white']['figure_after'] ='';
            result_[++i] ={};
            result_[i]['black'] ={};
          }else if (n_lexx_==7){
            currS ='S';
            result_[i]['white']['figure_after'] ='';
          }else currS ='Err';
          break;  
        case 'A1':
          if(n_lexx_==10){
            currS ='F';
            result_[++i] ={};
            result_[i]['black'] ={};
          }else if (n_lexx_==7){
            currS ='S';
          }else currS ='Err';
          break;  
        case 'F':
          if(n_lexx_ ==5){
            currS ='D';
            result_[i]['black'] ={};
            result_[i]['black']['figure_'] ='';
            result_[i]['black']['cell_1'] =this.lexx_.toUpperCase();
          }else if(n_lexx_ ==4){
            currS ='E';
            result_[i]['black'] ={};
            result_[i]['black']['figure_'] ='b'+this.get_figure(this.lexx_);
          }else if(n_lexx_ ==8){
            currS ='K';              
            result_[i]['black'] ={};
            result_[i]['black']['figure_'] ='0-0-0';
          }else if(n_lexx_ ==9){
            currS ='Z';              
            result_[i]['black'] ={};
            result_[i]['black']['figure_'] ='0-0';
          }else currS ='Err';
          break;  
        case 'Z':
          if(n_lexx_==7){
            currS ='Z';  
          }else if(n_lexx_==6){
            currS ='G';  
          }else currS ='Err';
          break;
        case 'K':
          if(n_lexx_==7){
            currS ='Z';  
          }else if(n_lexx_==6){
            currS ='G';  
          }else currS ='Err';
          break;
        case 'E':
          if(n_lexx_==5){
            currS ='D';
            result_[i]['black']['cell_1'] =this.lexx_.toUpperCase();
          }else currS ='Err';
          break;
        case 'D':  
          if(n_lexx_ ==3){  
            currS ='C';  
          }else currS ='Err';
          break;  
        case 'C':  
          if(n_lexx_ ==5){  
            currS ='B';  
            result_[i]['black']['cell_2'] =this.lexx_.toUpperCase();            
          }else currS ='Err';
          break;  
        case 'B':
          if(n_lexx_ ==4){
            currS ='A';
            result_[i]['black']['figure_after'] ='b'+this.get_figure(this.lexx_);
          }else if(n_lexx_==6){
            currS ='G';
            result_[i]['black']['figure_after'] ='';
          }else if (n_lexx_==7){
            currS ='S';
            result_[i]['black']['figure_after'] ='';
          }else currS ='Err';
          break;  
        case 'A':
          if(n_lexx_==6){
            currS ='G';
          }else if (n_lexx_==7){
            currS ='S';
          }else currS ='Err';
          break;  
        case 'E1':
          if(n_lexx_==5){
            currS ='D1';
            result_[i]['white']['cell_1'] =this.lexx_.toUpperCase();
          }else currS ='Err';
          break;
        case 'G': 
          if(n_lexx_ ==1){
            currS ='M';
            result_[++i] ={};
          }else currS ='Err'; 
          break;
        case 'M':
          if(n_lexx_ ==5){
            currS ='D1';
            result_[i]['white'] ={};
            result_[i]['white']['figure_'] ='';
            result_[i]['white']['cell_1'] =this.lexx_.toUpperCase();
          }else if(n_lexx_ ==4){
            currS ='E1';
            result_[i]['white'] ={};
            result_[i]['white']['figure_'] ='w'+this.get_figure(this.lexx_);
          }else if(n_lexx_ ==8){
            currS ='K1';              
            result_[i]['white'] ={};
            result_[i]['white']['figure_'] ='0-0-0';
          }else if(n_lexx_ ==9){
            currS ='Z1';              
            result_[i]['white'] ={};
            result_[i]['white']['figure_'] ='0-0';
          }else currS ='Err';
          break;  
        case 'L':
          if(n_lexx_==10){
            currS ='F';
            result_[i]['black'] ={};
          }else currS ='Err';
          break;  
        case 'S':
          currS ='Err';
          break;
      }//switch      
    }//while
    
/*    alert(result_[0]['white']['figure_']+result_[0]['white']['cell_1']+result_[0]['white']['cell_2']+result_[0]['white']['figure_after']+
          result_[0]['black']['figure_']+result_[0]['black']['cell_1']+result_[0]['black']['cell_2']+result_[0]['black']['figure_after']+','+        
          result_[1]['white']['figure_']+result_[1]['white']['cell_1']+result_[1]['white']['cell_2']+result_[1]['white']['figure_after']+
          result_[1]['black']['figure_']+result_[1]['black']['cell_1']+result_[1]['black']['cell_2']+result_[1]['black']['figure_after']+','+            
          result_[2]['white']['figure_']+result_[2]['white']['cell_1']+result_[2]['white']['cell_2']+result_[2]['white']['figure_after']+
          result_[2]['black']['figure_']+result_[2]['black']['cell_1']+result_[2]['black']['cell_2']+result_[2]['black']['figure_after']+','+        
          result_[3]['white']['figure_']+result_[3]['white']['cell_1']+result_[3]['white']['cell_2']+result_[3]['white']['figure_after']+
          result_[3]['black']['figure_']+result_[3]['black']['cell_1']+result_[3]['black']['cell_2']+result_[3]['black']['figure_after']);
 */         
    if(currS =='S') return result_; else return false;
  }//parser_record_game
  
//������ this.moves_ �� ������� �����, ������� ����� ���:
//t[][white]{figure_=>������,cell_1=>������1,cell_2=>������,figure_after=>������}
//   [black]{figure_=>������,cell_1=>������1,cell_2=>������,figure_after=>������}
  this.make_moves =function(t){
    if(t === false){
      alert('��� �������� ������ ��������� ������.');
      return;
    }
      
    var fa;
    var c1,c2,c3;
    
    this.copy_board(this.board_f, this.board_c);
    
    for(var i=0; i < t.length; i++){
//��� �����
//--------------------------------------------------------------------------------------
      if(t[i]['white']){
//�������� ��������� 
        if(t[i]['white']['figure_'] =='0-0'){
          this.moves_[this.curr_move]={};
          this.moves_[this.curr_move]['before'] ={};
          this.moves_[this.curr_move]['before']['E1'] =this.board_c['E'][1];
          this.moves_[this.curr_move]['before']['F1'] =this.board_c['F'][1];
          this.moves_[this.curr_move]['before']['G1'] =this.board_c['G'][1];
          this.moves_[this.curr_move]['before']['H1'] =this.board_c['H'][1];
          this.moves_[this.curr_move]['after'] ={};
          this.moves_[this.curr_move]['after']['E1'] ='';
          this.moves_[this.curr_move]['after']['F1'] ='wr';
          this.moves_[this.curr_move]['after']['G1'] ='wk';
          this.moves_[this.curr_move]['after']['H1'] ='';
//�������� ��������� 
        }else if(t[i]['white']['figure_'] =='0-0-0'){
          this.moves_[this.curr_move]={};  
          this.moves_[this.curr_move]['before'] ={};
          this.moves_[this.curr_move]['before']['E1'] =this.board_c['E'][1];
          this.moves_[this.curr_move]['before']['A1'] =this.board_c['A'][1];
          this.moves_[this.curr_move]['before']['C1'] =this.board_c['C'][1];
          this.moves_[this.curr_move]['before']['D1'] =this.board_c['D'][1];
          this.moves_[this.curr_move]['after'] ={};
          this.moves_[this.curr_move]['after']['E1'] ='';
          this.moves_[this.curr_move]['after']['C1'] ='wk';
          this.moves_[this.curr_move]['after']['A1'] ='';
          this.moves_[this.curr_move]['after']['D1'] ='wr';
//������ �� ������� 
        }else if((t[i]['white']['cell_1'].charAt(1) ==5) && 
                 (t[i]['white']['cell_2'].charAt(1) ==6) &&
                 (t[i]['white']['figure_'] =='') &&                     
                 (t[i]['white']['cell_1'].charAt(0) !=t[i]['white']['cell_2'].charAt(0)) &&
                 (this.board_c[(t[i]['white']['cell_2']).charAt(0)][(t[i]['white']['cell_2']).charAt(0).charAt(1)] =='')){
          c1=t[i]['white']['cell_1'];
          c2=t[i]['white']['cell_2'];
          c3=c2.charAt(0) + c1.charAt(1);
          this.moves_[this.curr_move]={};
          this.moves_[this.curr_move]['before'] ={};
          this.moves_[this.curr_move]['before'][c1] =this.board_c[c1.charAt(0)][c1.charAt(1)];
          this.moves_[this.curr_move]['before'][c2] =this.board_c[c2.charAt(0)][c2.charAt(1)];
          this.moves_[this.curr_move]['before'][c3] =this.board_c[c3.charAt(0)][c3.charAt(1)];
          this.moves_[this.curr_move]['after'] ={};
          this.moves_[this.curr_move]['after'][c1] ='';
          this.moves_[this.curr_move]['after'][c2] =this.board_c[c1.charAt(0)][c1.charAt(1)];
          this.moves_[this.curr_move]['after'][c3] ='';
//������� ���                                   
        }else{
          c1=t[i]['white']['cell_1'];
          c2=t[i]['white']['cell_2']; 
          if(t[i]['white']['figure_after'] !='') 
            fa =t[i]['white']['figure_after']
           else fa=this.board_c[c1.charAt(0)][c1.charAt(1)];
          this.moves_[this.curr_move]={};
          this.moves_[this.curr_move]['before'] ={};
          this.moves_[this.curr_move]['before'][c1] =this.board_c[c1.charAt(0)][c1.charAt(1)];
          this.moves_[this.curr_move]['before'][c2] =this.board_c[c2.charAt(0)][c2.charAt(1)];
          this.moves_[this.curr_move]['after'] ={};
          this.moves_[this.curr_move]['after'][c1] ='';
          this.moves_[this.curr_move]['after'][c2] =fa;
        }

//��� ������
//--------------------------------------------------------------------------------------
      }else if(t[i]['black']){
//�������� ��������� 
        if(t[i]['black']['figure_'] =='0-0'){
          this.moves_[this.curr_move]={};
          this.moves_[this.curr_move]['before'] ={};
          this.moves_[this.curr_move]['before']['E8'] =this.board_c['E'][8];
          this.moves_[this.curr_move]['before']['F8'] =this.board_c['F'][8];
          this.moves_[this.curr_move]['before']['G8'] =this.board_c['G'][8];
          this.moves_[this.curr_move]['before']['H8'] =this.board_c['H'][8];
          this.moves_[this.curr_move]['after'] ={};
          this.moves_[this.curr_move]['after']['E8'] ='';
          this.moves_[this.curr_move]['after']['F8'] ='br';
          this.moves_[this.curr_move]['after']['G8'] ='bk';
          this.moves_[this.curr_move]['after']['H8'] ='';
//�������� ��������� 
        }else if(t[i]['black']['figure_'] =='0-0-0'){
          this.moves_[this.curr_move]={};
          this.moves_[this.curr_move]['before'] ={};
          this.moves_[this.curr_move]['before']['E8'] =this.board_c['E'][8];
          this.moves_[this.curr_move]['before']['A8'] =this.board_c['A'][8];
          this.moves_[this.curr_move]['before']['C8'] =this.board_c['C'][8];
          this.moves_[this.curr_move]['before']['D8'] =this.board_c['D'][8];
          this.moves_[this.curr_move]['after'] ={};
          this.moves_[this.curr_move]['after']['E8'] ='';
          this.moves_[this.curr_move]['after']['C8'] ='bk';
          this.moves_[this.curr_move]['after']['A8'] ='';
          this.moves_[this.curr_move]['after']['D8'] ='br';
//������ �� ������� 
        }else if((t[i]['black']['cell_1'].charAt(1) ==4) && 
                 (t[i]['black']['cell_2'].charAt(1) ==3) &&
                 (t[i]['black']['figure_'] =='') &&                     
                 (t[i]['black']['cell_1'].charAt(0) !=t[i]['black']['cell_2'].charAt(0)) &&
                 (this.board_c[(t[i]['black']['cell_2']).charAt(0)][(t[i]['black']['cell_2']).charAt(1)] =='')){
          c1=t[i]['black']['cell_1'];
          c2=t[i]['black']['cell_2'];
          c3=c2.charAt(0) + c1.charAt(1);
          this.moves_[this.curr_move] ={};
          this.moves_[this.curr_move]['before'] ={};
          this.moves_[this.curr_move]['before'][c1] =this.board_c[c1.charAt(0)][c1.charAt(1)];
          this.moves_[this.curr_move]['before'][c2] =this.board_c[c2.charAt(0)][c2.charAt(1)];
          this.moves_[this.curr_move]['before'][c3] =this.board_c[c3.charAt(0)][c3.charAt(1)];
          this.moves_[this.curr_move]['after'] ={};
          this.moves_[this.curr_move]['after'][c1] ='';
          this.moves_[this.curr_move]['after'][c2] =this.board_c[c1.charAt(0)][c1.charAt(1)];
          this.moves_[this.curr_move]['after'][c3] ='';
//������� ���                                   
        }else{
          c1=t[i]['black']['cell_1'];
          c2=t[i]['black']['cell_2']; 
          if(t[i]['black']['figure_after'] !='') 
            fa=t[i]['black']['figure_after'];
           else fa=this.board_c[c1.charAt(0)][c1.charAt(1)];
          this.moves_[this.curr_move]={};
          this.moves_[this.curr_move]['before'] ={};
          this.moves_[this.curr_move]['before'][c1] =this.board_c[c1.charAt(0)][c1.charAt(1)];
          this.moves_[this.curr_move]['before'][c2] =this.board_c[c2.charAt(0)][c2.charAt(1)];
          this.moves_[this.curr_move]['after'] ={};
          this.moves_[this.curr_move]['after'][c1] =''; 
          this.moves_[this.curr_move]['after'][c2] =fa; 
        }
      }
      this.move_forward(false);
    }//for  
    
    this.copy_board(this.board_c, this.board_l);
    this.copy_board(this.board_f, this.board_c);
    this.curr_move =0;
  }//make_move

//������������� ����������� ������� �� ������ ���������� ������
  this.link_events =function(id_begin,id_back,id_forward,id_last){
    var vg =this;  
    $(id_begin).bind('click', function(){
     if(vg.curr_move > 0){   
       vg.copy_board(vg.board_f,vg.board_c);
       vg.curr_move =0;
       vg.repaint_board();
       if($(".example-game .current_").length > 0) $(".example-game .current_").removeClass('current_');       
     }  
    })
    
    $(id_back).bind('click',function(){
     if(vg.curr_move > 0){
       vg.move_back();
       if($(".example-game .current_").length > 0) $(".example-game .current_").removeClass('current_');
       if(vg.curr_move > 0) $("#"+vg.prefix_cell+vg.curr_move).addClass('current_');
     }
    })
    
    $(id_forward).bind('click',function(){
     if(vg.curr_move < vg.moves_.length){
       vg.move_forward(true);   
       if($(".example-game .current_").length > 0) $(".example-game .current_").removeClass('current_');
       $("#"+vg.prefix_cell+vg.curr_move).addClass('current_');
     }  
    })
    
    $(id_last).bind('click',function(){
     if(vg.curr_move < vg.moves_.length){
       vg.copy_board(vg.board_l,vg.board_c);
       vg.curr_move =vg.moves_.length;
       vg.repaint_board();
       if($(".example-game .current_").length > 0) $(".example-game .current_").removeClass('current_');
       $("#"+vg.prefix_cell+vg.curr_move).addClass('current_');       
     }   
    })
  }
}//cl_view_game

