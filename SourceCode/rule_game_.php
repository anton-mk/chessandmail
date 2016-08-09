<?php
    require_once('const_.php');
    require_once('Models/TMoves_.php');

    class CRuleGame{
        public $board_;  //шахматная доска
#Шаг перебора правильных ходов (используется функциями возвращающими правильные ходы)
        protected $_curr_step;
#Текущая рабочая ячейка  (используется функциями возвращающими правильные ходы)
        protected $_work_cell;
#Правило, позволяющее слону перескакивать через своего коня
        public $bishop_skip_knight = false;
#Переменные возможности выполнить рокировки. Если ход был сделан королём или ладьёй соответствующие переменные будут сброшены.
#Используются функциями возвращающими правильные ходы.
        public $may_white_short_castling = true;
        public $may_white_long_castling  = true;
        public $may_black_short_castling = true;
        public $may_black_long_castling  = true;
#Таблица ходов
#table_moves[]['num']        - номер хода
#table_moves[]['wpiece']     - фигура белых
#table_moves[]['wmove']      - ход белых
#table_moves[]['w_to_piece'] - превращение белой пешки
#table_moves[]['w_isCheck']  - объявления шаха или мата белыми
#table_moves[]['bpiece']     - фигура черных
#table_moves[]['bmove']      - ход черных
#table_moves[]['b_to_piece'] - превращение черной пешки
#table_moves[]['b_isCheck']  - объявления шаха или мата черными
        public $table_moves;
        public $id_; //id_ партии
#Информация, полученная из таблицы TMoves_
        public $num_; #последний незавершённый ход
        public $lastMoveIsWhite_; #true - если последний ход сделали белые, иначе false
#Информация о предыдущем ходе. 
#При рокировке:
# _pred_c1 - конечная ячейка короля,  _pred_v1 - значение до совершения хода
# _pred_c2 - конечная ячейка ладьи,   _pred_v2 - значение до совершения хода
# _pred_c3 - начальная ячейка ладьи,  _pred_v3 - значение до совершения хода
# _pred_c4 - начальная ячейка короля, _pred_v4 - значение до совершения хода         
        protected $_pred_c1 = ''; protected $_pred_v1 = ''; //начальная ячейка фигуры или пешки, совершившей ход, и значение ячейки до совершения хода
        protected $_pred_c2 = ''; protected $_pred_v2 = ''; //конечная ячейка фигуры или пешки, совершившей ход, и значение ячейки до совершения хода
        protected $_pred_c3 = ''; protected $_pred_v3 = ''; //Используется при рокировке
        protected $_pred_c4 = ''; protected $_pred_v4 = ''; //Используется при рокировке
#Последнии позиция короля. Используются вункциями возвращающими правильные ходы.
        public $white_king_last_position = 'E1';
        public $black_king_last_position = 'E8';

#Расставляет на доске фигуры (начальная позиция)
        public function firstPosition(){
            $this->table_moves = array();
            $this->board_ = array();
            $this->board_['A'] = array(1=>'wr',2=>'wp',3=>'',4=>'',5=>'',6=>'',7=>'bp',8=>'br');
            $this->board_['B'] = array(1=>'wn',2=>'wp',3=>'',4=>'',5=>'',6=>'',7=>'bp',8=>'bn');
            $this->board_['C'] = array(1=>'wb',2=>'wp',3=>'',4=>'',5=>'',6=>'',7=>'bp',8=>'bb');
            $this->board_['D'] = array(1=>'wq',2=>'wp',3=>'',4=>'',5=>'',6=>'',7=>'bp',8=>'bq');
            $this->board_['E'] = array(1=>'wk',2=>'wp',3=>'',4=>'',5=>'',6=>'',7=>'bp',8=>'bk');
            $this->board_['F'] = array(1=>'wb',2=>'wp',3=>'',4=>'',5=>'',6=>'',7=>'bp',8=>'bb');
            $this->board_['G'] = array(1=>'wn',2=>'wp',3=>'',4=>'',5=>'',6=>'',7=>'bp',8=>'bn');
            $this->board_['H'] = array(1=>'wr',2=>'wp',3=>'',4=>'',5=>'',6=>'',7=>'bp',8=>'br');
        }#firstPosition

#Функция сбрасывает $_curr_step
        protected function _clearCurrStep(){
            $this->_curr_step =0;
        }#_clearCurrStep

#Функция сбрасывает $_work_cell
        protected function _clearWorkCell(){
            $this->_work_cell ='';
        } #_clearWorkCell

#Возвращает доступные для белой пешки, находящейся в клетке $cell_, варианты ходов, возвращается клетка, куда пешка может встать . 
#Чтобы получить все доступные варианты ходов необходимо вызвать функцию несколько раз (пока она не вернет '').
#Свойство _curr_step хранит информацию (между вызовами), какие варианты уже были проверены. Перед первым вызовом необходимо выполнить _clearCurrStep     
        protected function _whitePeshkaTryStep($cell_){ //белая пешка
            $result_ ='';
            while (($result_ == '') && ($this->_curr_step < 6)){
                switch ($this->_curr_step++){
                    case 0 : // проверяется возможность хода e2 - e4
                        if (($cell_{1} == '2') && ($this->board_[$cell_{0}][3] == '') && ($this->board_[$cell_{0}][4] == ''))
                            $result_ =$cell_{0}.'4';
                        break;
                    case 1 : // проверяется возможность хода на одну клетку вперёд
                        if (($cell_{1} < 8) && ($this->board_[$cell_{0}][$cell_{1}+1] == ''))
                            $result_ =$cell_{0}.($cell_{1}+1);
                        break;
                    case 2 : // проверяется возможность взятия справа (исключая взятие на проходе)
                        if (($cell_{1} < 8) && ($cell_{0} !='H')){
                            $a =chr(ord($cell_{0})+1).($cell_{1}+1);
                            if (($this->board_[$a{0}][$a{1}] !='') && ($this->board_[$a{0}][$a{1}]{0} =='b'))
                                $result_ =$a;
                        }
                        break;
                    case 3 : // проверяется возможность взятия слева (исключая взятие на проходе)
                        if (($cell_{1} < 8) && ($cell_{0} !='A')){
                            $a =chr(ord($cell_{0})-1).($cell_{1}+1);
                            if (($this->board_[$a{0}][$a{1}] !='') && ($this->board_[$a{0}][$a{1}]{0} =='b'))
                                $result_ =$a;
                        }
                        break;
                    case 4 : //проверяется возможность взятия справа на проходе
                        if (($cell_{0} !='H') && ($cell_{1} ==5) &&
                            ($this->_pred_c1 == (chr(ord($cell_{0})+1).'7')) && ($this->_pred_c2 == (chr(ord($cell_{0})+1).'5')) &&
                            ($this->_pred_v1 == 'bp'))
                                $result_ =chr(ord($cell_{0})+1).($cell_{1}+1);
                        break;
                    case 5 : //проверяется возможность взятия слева на проходе
                        if (($cell_{0} !='A') && ($cell_{1} ==5) &&
                            ($this->_pred_c1 == (chr(ord($cell_{0})-1).'7')) && ($this->_pred_c2 == (chr(ord($cell_{0})-1).'5')) &&
                            ($this->_pred_v1 == 'bp'))
                                $result_ =chr(ord($cell_{0})-1).($cell_{1}+1);
                        break;
                }#switch
            }#while
            return $result_;
        }#_whitePeshkaTryStep

#По аналогии с _whitePeshkaTryStep
        protected function _blackPeshkaTryStep($cell_){ //чёрная пешка
            $result_ ='';
            while (($result_ == '') && ($this->_curr_step < 6)){
                switch ($this->_curr_step++){
                    case 0 : // проверяется возможность хода e7 - e5
                        if (($cell_{1} == '7') && ($this->board_[$cell_{0}][6] == '') && ($this->board_[$cell_{0}][5] == ''))
                            $result_ =$cell_{0}.'5';
                        break;
                    case 1 : // проверяется возможность хода на одну клетку вперёд
                        if (($cell_{1} > 1) && ($this->board_[$cell_{0}][$cell_{1}-1] == ''))
                            $result_ =$cell_{0}.($cell_{1}-1);
                        break;
                    case 2 : // проверяется возможность взятия слева (исключая взятие на проходе)
                        if (($cell_{1} > 1) && ($cell_{0} !='H')){
                            $a =chr(ord($cell_{0})+1).($cell_{1}-1);
                            if (($this->board_[$a{0}][$a{1}] !='') && ($this->board_[$a{0}][$a{1}]{0} =='w'))
                                $result_ =$a;
                        }
                        break;
                    case 3 : // проверяется возможность взятия справа (исключая взятие на проходе)
                        if (($cell_{1} > 1) && ($cell_{0} !='A')){
                            $a =chr(ord($cell_{0})-1).($cell_{1}-1);
                            if (($this->board_[$a{0}][$a{1}] !='') && ($this->board_[$a{0}][$a{1}]{0} =='w'))
                                $result_ =$a;
                        }
                        break;
                    case 4 : //проверяется возможность взятия справа на проходе
                        if (($cell_{0} !='A') && ($cell_{1} ==4) &&
                            ($this->_pred_c1 == (chr(ord($cell_{0})-1).'2')) && ($this->_pred_c2 == (chr(ord($cell_{0})-1).'4')) &&
                            ($this->_pred_v1 == 'wp'))
                                $result_ =chr(ord($cell_{0})-1).($cell_{1}-1);
                        break;
                    case 5 : //проверяется возможность взятия слева на проходе
                        if (($cell_{0} !='H') && ($cell_{1} ==4) &&
                            ($this->_pred_c1 == (chr(ord($cell_{0})+1).'2')) && ($this->_pred_c2 == (chr(ord($cell_{0})+1).'4')) &&
                            ($this->_pred_v1 == 'wp'))
                                $result_ =chr(ord($cell_{0})+1).($cell_{1}-1);
                        break;
                }#switch
            }#while
            return $result_;
        }#_blackPeshkaTryStep

#По аналогии с _whitePeshkaTryStep, $color - цвет коня
        protected function _knightTryStep($color_,$cell_){// конь
            $result_ ='';
            while (($result_ == '') && ($this->_curr_step < 8)){
                $a ='';
                switch ($this->_curr_step++){
                    case 0 :
                        if (($cell_{1} < 7) && ($cell_{0} !='H'))
                            $a =chr(ord($cell_{0})+1).($cell_{1}+2);
                        break;
                    case 1 :
                        if (($cell_{1} < 7) && ($cell_{0} !='A'))
                            $a =chr(ord($cell_{0})-1).($cell_{1}+2);
                        break;
                    case 2 :
                        if (($cell_{1} > 2) && ($cell_{0} !='H'))
                            $a =chr(ord($cell_{0})+1).($cell_{1}-2);
                        break;
                    case 3 :
                        if (($cell_{1} > 2) && ($cell_{0} !='A'))
                            $a =chr(ord($cell_{0})-1).($cell_{1}-2);
                        break;
                    case 4 :
                        if (($cell_{1} < 8) && ($cell_{0} < 'G'))
                            $a =chr(ord($cell_{0})+2).($cell_{1}+1);
                        break;
                    case 5 :
                        if (($cell_{1} < 8) && ($cell_{0} > 'B'))
                            $a =chr(ord($cell_{0})-2).($cell_{1}+1);
                        break;
                    case 6 :
                        if (($cell_{1} > 1) && ($cell_{0} < 'G'))
                            $a =chr(ord($cell_{0})+2).($cell_{1}-1);
                        break;
                    case 7 :
                        if (($cell_{1} > 1) && ($cell_{0} > 'B'))
                            $a =chr(ord($cell_{0})-2).($cell_{1}-1);
                        break;
                }# switch
                if (($a != '') && (($this->board_[$a{0}][$a{1}] =='') || ($this->board_[$a{0}][$a{1}]{0} !=$color_)))
                    $result_ =$a;
            }# while
            return $result_;
        }# _knightTryStep

#По аналогии с _knightTryStep.
#Свойство _work_cell хранит информацию (между вызовами), какие ячейки уже были проверены. Перед первым вызовом необходимо выполнить _clearWorkCell     
        protected function _bishopTryStep($color_,$cell_){// слон
            $result_ ='';
            if ($this->_work_cell == '') $this->_work_cell = $cell_;
            while (($result_ =='') && ($this->_curr_step < 4)){
                switch ($this->_curr_step){
                    case 0 : $this->_work_cell =chr(ord($this->_work_cell{0})+1).($this->_work_cell{1}+1); break;
                    case 1 : $this->_work_cell =chr(ord($this->_work_cell{0})-1).($this->_work_cell{1}+1); break;
                    case 2 : $this->_work_cell =chr(ord($this->_work_cell{0})+1).($this->_work_cell{1}-1); break;
                    case 3 : $this->_work_cell =chr(ord($this->_work_cell{0})-1).($this->_work_cell{1}-1); break;
                }#switch
                if (($this->_work_cell{0} > 'H') || ($this->_work_cell{1} > 8) ||
                    ($this->_work_cell{0} < 'A') || ($this->_work_cell{1} < 1) ||
                    (($this->board_[$this->_work_cell{0}][$this->_work_cell{1}] != '') &&
                     ($this->board_[$this->_work_cell{0}][$this->_work_cell{1}]{0} == $color_) &&
                     (!$this->bishop_skip_knight || ($this->board_[$this->_work_cell{0}][$this->_work_cell{1}] != $color_.'n'))
                    )){
                        $this->_work_cell = $cell_;
                        $this->_curr_step++;
                 }else{
                        if ($this->board_[$this->_work_cell{0}][$this->_work_cell{1}] =='')
                             $result_ = $this->_work_cell;
                         elseif ($this->board_[$this->_work_cell{0}][$this->_work_cell{1}] !=$color_.'n'){
                             $result_ = $this->_work_cell;
                             $this->_work_cell = $cell_;
                             $this->_curr_step++;
                         }
                 }
            }# while
            return $result_;
        }#_bishopTryStep

#По аналогии с _bishopTryStep        
        protected function _rookTryStep($color_,$cell_){// ладья
            $result_ ='';
            if ($this->_work_cell == '') $this->_work_cell = $cell_;
            while (($result_ =='') && ($this->_curr_step < 4)){
                switch ($this->_curr_step){
                    case 0 : $this->_work_cell =chr(ord($this->_work_cell{0})+1).$this->_work_cell{1}; break;
                    case 1 : $this->_work_cell =chr(ord($this->_work_cell{0})-1).$this->_work_cell{1}; break;
                    case 2 : $this->_work_cell =$this->_work_cell{0}.($this->_work_cell{1}+1); break;
                    case 3 : $this->_work_cell =$this->_work_cell{0}.($this->_work_cell{1}-1); break;
                }#switch
                if (($this->_work_cell{0} > 'H') || ($this->_work_cell{0} < 'A') ||
                    ($this->_work_cell{1} > 8) || ($this->_work_cell{1} < 1) ||
                    (($this->board_[$this->_work_cell{0}][$this->_work_cell{1}] != '') &&
                     ($this->board_[$this->_work_cell{0}][$this->_work_cell{1}]{0} == $color_))){
                        $this->_work_cell = $cell_;
                        $this->_curr_step++;
                 }else{
                        if ($this->board_[$this->_work_cell{0}][$this->_work_cell{1}] =='')
                            $result_ = $this->_work_cell;
                         else{
                            $result_ = $this->_work_cell;
                            $this->_work_cell = $cell_;
                            $this->_curr_step++;
                         }
                 }
            }# while
            return $result_;
        }# _rookTryStep

#По аналогии с _bishopTryStep          
        protected function _queenTryStep($color_,$cell_){// ферзь
            $result_ ='';
            if ($this->_work_cell == '') $this->_work_cell = $cell_;
            while (($result_ =='') && ($this->_curr_step < 8)){
                switch ($this->_curr_step){
                    case 0 : $this->_work_cell =chr(ord($this->_work_cell{0})+1).$this->_work_cell{1}; break;
                    case 1 : $this->_work_cell =chr(ord($this->_work_cell{0})-1).$this->_work_cell{1}; break;
                    case 2 : $this->_work_cell =$this->_work_cell{0}.($this->_work_cell{1}+1); break;
                    case 3 : $this->_work_cell =$this->_work_cell{0}.($this->_work_cell{1}-1); break;
                    case 4 : $this->_work_cell =chr(ord($this->_work_cell{0})+1).($this->_work_cell{1}+1); break;
                    case 5 : $this->_work_cell =chr(ord($this->_work_cell{0})-1).($this->_work_cell{1}+1); break;
                    case 6 : $this->_work_cell =chr(ord($this->_work_cell{0})+1).($this->_work_cell{1}-1); break;
                    case 7 : $this->_work_cell =chr(ord($this->_work_cell{0})-1).($this->_work_cell{1}-1); break;
                }#switch
                if (($this->_work_cell{0} > 'H') || ($this->_work_cell{0} < 'A') ||
                    ($this->_work_cell{1} > 8) || ($this->_work_cell{1} < 1) ||
                    (($this->board_[$this->_work_cell{0}][$this->_work_cell{1}] != '') &&
                     ($this->board_[$this->_work_cell{0}][$this->_work_cell{1}]{0} == $color_))){
                       $this->_work_cell = $cell_;
                       $this->_curr_step++;
                  }else{
                       if ($this->board_[$this->_work_cell{0}][$this->_work_cell{1}] =='')
                           $result_ = $this->_work_cell;
                        else{
                           $result_ = $this->_work_cell;
                           $this->_work_cell = $cell_;
                           $this->_curr_step++;
                         }
                  }
            }# while
            return $result_;
        }# _queenTryStep

#По аналогии с _bishopTryStep        
        protected function _kingTryStep($color_,$cell_){
            $result_ ='';
            while (($result_ == '') && ($this->_curr_step < 10)){
                $a ='';
                switch ($this->_curr_step++){
                    case 0 : if ($cell_{0} !='H') $a =chr(ord($cell_{0})+1).($cell_{1}); break;
                    case 1 : if ($cell_{0} !='A') $a =chr(ord($cell_{0})-1).($cell_{1}); break;
                    case 2 : if ($cell_{1} > 1) $a =($cell_{0}).($cell_{1}-1); break;
                    case 3 : if ($cell_{1} < 8) $a =($cell_{0}).($cell_{1}+1); break;
                    case 4 : if (($cell_{1} < 8) && ($cell_{0} != 'H')) $a =chr(ord($cell_{0})+1).($cell_{1}+1); break;
                    case 5 : if (($cell_{1} < 8) && ($cell_{0} != 'A')) $a =chr(ord($cell_{0})-1).($cell_{1}+1); break;
                    case 6 : if (($cell_{1} > 1) && ($cell_{0} != 'A')) $a =chr(ord($cell_{0})-1).($cell_{1}-1); break;
                    case 7 : if (($cell_{1} > 1) && ($cell_{0} != 'H')) $a =chr(ord($cell_{0})+1).($cell_{1}-1); break;
                    case 8 : if (($color_ =='w') && $this->may_white_short_castling && !$this->isCheckCell('w','E1')){
                               if (($this->board_['F'][1] =='') && ($this->board_['G'][1] =='') &&
                                    !$this->isCheckCell('w','F1') && !$this->isCheckCell('w','G1'))
                                    $result_ ='G1';
                             }elseif (($color_ =='b') && $this->may_black_short_castling && !$this->isCheckCell('b','E8')){
                                if (($this->board_['F'][8] =='') && ($this->board_['G'][8] =='') &&
                                    !$this->isCheckCell('b','F8') && !$this->isCheckCell('b','G8'))
                                  $result_ ='G8';
                             } break;
                    case 9 : if (($color_ =='w') && $this->may_white_long_castling && !$this->isCheckCell('w','E1')){
                                if (($this->board_['D'][1] =='') && ($this->board_['C'][1] =='') && ($this->board_['B'][1] =='') &&
                                    !$this->isCheckCell('w','C1') && !$this->isCheckCell('w','D1'))
                                    $result_ ='C1';
                             }elseif (($color_ =='b') && $this->may_black_long_castling && !$this->isCheckCell('b','E8')){
                                if (($this->board_['D'][8] =='') && ($this->board_['C'][8] =='') && ($this->board_['B'][8] =='') &&
                                     !$this->isCheckCell('b','C8') && !$this->isCheckCell('b','D8'))
                                    $result_ ='C8';
                             }
                }#switch
                if (($a != '') && (($this->board_[$a{0}][$a{1}] =='') || ($this->board_[$a{0}][$a{1}]{0} !=$color_)))
                    $result_ =$a;
            }#while
            return $result_;
        }#_kingTryStep

#Проверяет находится ли клетка $cell_ под ударом. Если $color_ = 'w' - проверяется удар чёрных фигур и пешек. Если $color_ ='b' - наоборот.
        public function isCheckCell($color_,$cell_){
            if ($color_ =='w'){
                $a_ =chr(ord($cell_{0})+1).($cell_{1}+1);
                if (($a_{0} <='H') && ($a_{1} < 8) && ($this->board_[$a_{0}][$a_{1}] =='bp')) return true;
                $a_ =chr(ord($cell_{0})-1).($cell_{1}+1);
                if (($a_{0} >='A') && ($a_{1} < 8) && ($this->board_[$a_{0}][$a_{1}] =='bp')) return true;
                $r_ ='br';
                $q_ ='bq';
                $k_ ='bk';
                $b_ ='bb';
                $n_ ='bn';
            }else{
                $a_ =chr(ord($cell_{0})+1).($cell_{1}-1);
                if (($a_{0} <='H') && ($a_{1} > 1) && ($this->board_[$a_{0}][$a_{1}] =='wp')) return true;
                $a_ =chr(ord($cell_{0})-1).($cell_{1}-1);
                if (($a_{0} >='A') && ($a_{1} > 1) && ($this->board_[$a_{0}][$a_{1}] =='wp')) return true;
                $r_ ='wr';
                $q_ ='wq';
                $k_ ='wk';
                $b_ ='wb';
                $n_ ='wn';
            }
#вертикаль и горизонталь
            $a_ =$cell_;
            while($a_{1} < 8){
                $a_{1}=$a_{1}+1;
                if (($this->board_[$a_{0}][$a_{1}] ==$r_) || ($this->board_[$a_{0}][$a_{1}] ==$q_) ||
                    (($cell_{1} == ($a_{1}-1)) && ($this->board_[$a_{0}][$a_{1}] ==$k_)))
                    return true;
                if ($this->board_[$a_{0}][$a_{1}] !='') break;
            }#while
            $a_ =$cell_;
            while($a_{1} > 1){
                $a_{1}=$a_{1}-1;
                if (($this->board_[$a_{0}][$a_{1}] ==$r_) || ($this->board_[$a_{0}][$a_{1}] ==$q_) ||
                    (($cell_{1} == ($a_{1}+1)) && ($this->board_[$a_{0}][$a_{1}] ==$k_)))
                    return true;
                if ($this->board_[$a_{0}][$a_{1}] !='') break;
            }#while
            $a_ =$cell_;
            while($a_{0} < 'H'){
                $a_{0}=chr(ord($a_{0})+1);
                if (($this->board_[$a_{0}][$a_{1}] ==$r_) || ($this->board_[$a_{0}][$a_{1}] ==$q_) ||
                    ((ord($cell_{0}) == ord($a_{0})-1) && ($this->board_[$a_{0}][$a_{1}] ==$k_)))
                    return true;
                if ($this->board_[$a_{0}][$a_{1}] !='') break;
            }#while
            $a_ =$cell_;
            while($a_{0} > 'A'){
                $a_{0}=chr(ord($a_{0})-1);
                if (($this->board_[$a_{0}][$a_{1}] ==$r_) || ($this->board_[$a_{0}][$a_{1}] ==$q_) ||
                    ((ord($cell_{0}) == ord($a_{0})+1) && ($this->board_[$a_{0}][$a_{1}] ==$k_)))
                    return true;
                if ($this->board_[$a_{0}][$a_{1}] !='') break;
            }#while
#диагонали
            $a_ =$cell_;
            while(($a_{0} < 'H') && ($a_{1} < 8)){
                $a_{0}=chr(ord($a_{0})+1); $a_{1} = $a_{1}+1;
                if (($this->board_[$a_{0}][$a_{1}] ==$b_) || ($this->board_[$a_{0}][$a_{1}] ==$q_) ||
                    (($cell_{1} == $a_{1}-1) && ($this->board_[$a_{0}][$a_{1}] ==$k_)))
                    return true;
                if ($this->board_[$a_{0}][$a_{1}] !='') break;
            }#while
            $a_ =$cell_;
            while(($a_{0} < 'H') && ($a_{1} > 1)){
                $a_{0}=chr(ord($a_{0})+1); $a_{1} = $a_{1}-1;
                if (($this->board_[$a_{0}][$a_{1}] ==$b_) || ($this->board_[$a_{0}][$a_{1}] ==$q_) ||
                    (($cell_{1} == $a_{1}+1) && ($this->board_[$a_{0}][$a_{1}] ==$k_)))
                    return true;
                if ($this->board_[$a_{0}][$a_{1}] !='') break;
            }#while
            $a_ =$cell_;
            while(($a_{0} > 'A') && ($a_{1} < 8)){
                $a_{0}=chr(ord($a_{0})-1); $a_{1} = $a_{1}+1;
                if (($this->board_[$a_{0}][$a_{1}] ==$b_) || ($this->board_[$a_{0}][$a_{1}] ==$q_) ||
                    (($cell_{1} == $a_{1}-1) && ($this->board_[$a_{0}][$a_{1}] ==$k_)))
                    return true;
                if ($this->board_[$a_{0}][$a_{1}] !='') break;
            }#while
            $a_ =$cell_;
            while(($a_{0} > 'A') && ($a_{1} > 1)){
                $a_{0}=chr(ord($a_{0})-1); $a_{1} = $a_{1}-1;
                if (($this->board_[$a_{0}][$a_{1}] ==$b_) || ($this->board_[$a_{0}][$a_{1}] ==$q_) ||
                    (($cell_{1} == $a_{1}+1) && ($this->board_[$a_{0}][$a_{1}] ==$k_)))
                    return true;
                if ($this->board_[$a_{0}][$a_{1}] !='') break;
            }#while
#ход конём
            $a_ =chr(ord($cell_{0})+1).($cell_{1}+2);
            if (($a_{0} <='H') && ($cell_{1} <=6) && ($this->board_[$a_{0}][$a_{1}] == $n_)) return true;
            $a_ =chr(ord($cell_{0})-1).($cell_{1}+2);
            if (($a_{0} >='A') && ($cell_{1} <=6) && ($this->board_[$a_{0}][$a_{1}] == $n_)) return true;
            $a_ =chr(ord($cell_{0})+1).($cell_{1}-2);
            if (($a_{0} <='H') && ($cell_{1} >=3) && ($this->board_[$a_{0}][$a_{1}] == $n_)) return true;
            $a_ =chr(ord($cell_{0})-1).($cell_{1}-2);
            if (($a_{0} >='A') && ($cell_{1} >=3) && ($this->board_[$a_{0}][$a_{1}] == $n_)) return true;
            $a_ =chr(ord($cell_{0})+2).($cell_{1}+1);
            if (($a_{0} <='H') && ($a_{1} <=8) && ($this->board_[$a_{0}][$a_{1}] == $n_)) return true;
            $a_ =chr(ord($cell_{0})-2).($cell_{1}+1);
            if (($a_{0} >='A') && ($a_{1} <=8) && ($this->board_[$a_{0}][$a_{1}] == $n_)) return true;
            $a_ =chr(ord($cell_{0})+2).($cell_{1}-1);
            if (($a_{0} <='H') && ($a_{1} >=1) && ($this->board_[$a_{0}][$a_{1}] == $n_)) return true;
            $a_ =chr(ord($cell_{0})-2).($cell_{1}-1);
            if (($a_{0} >='A') && ($a_{1} >=1) && ($this->board_[$a_{0}][$a_{1}] == $n_)) return true;

            return false;
        }#isCheckCell

#Расставляет на доске последнюю позицию. В свойство num_ заносится номер последнего хода. Если ни одного хода не сделано num_ = 0
        public function lastPosition(){
            $moves = new CTMoves($this->id_);
            $moves->lastPosition($this);
        }#lastPosition

#Проверяет правильность указания клетки
        protected function _checkCellBoard($cell_){
            return (strlen($cell_) ==2) && ($cell_{0} >='A') && ($cell_{0} <='H') && ($cell_{1} >='1') && ($cell_{1} <='8');
        }#_checkCellBoard

#Выполняет передвижение на доске
        public function move($cell1_,$cell2_,$piece_ ='',$to_table_move=false){
#Проверка возможности хода
            if (!$this->_checkCellBoard($cell1_) || !$this->_checkCellBoard($cell2_))
              throw new Exception();
#последний ход в таблице ходов
            $i_=count($this->table_moves)-1;
#передвижение
#короткая рокировка белых
            if (($cell1_ =='E1') && ($cell2_ =='G1') && ($this->board_[$cell1_{0}][$cell1_{1}] == 'wk')){
                $this->_pred_c1 ='G1'; $this->_pred_v1 = $this->board_['G']['1']; $this->board_['G']['1'] = 'wk';
                $this->_pred_c2 ='F1'; $this->_pred_v2 = $this->board_['F']['1']; $this->board_['F']['1'] = 'wr';
                $this->_pred_c3 ='H1'; $this->_pred_v3 = $this->board_['H']['1']; $this->board_['H']['1'] = '';
                $this->_pred_c4 ='E1'; $this->_pred_v4 = $this->board_['E']['1']; $this->board_['E']['1'] = '';
                $this->may_white_short_castling =false;
                $this->may_white_long_castling  =false;
                if ($to_table_move){
                    $this->table_moves[$i_]['wpiece'] ='';
                    $this->table_moves[$i_]['wmove']  ='0-0';
                    $this->table_moves[$i_]['w_to_piece']  ='';
                }
#длинная рокировка белых
            }elseif (($cell1_ =='E1') && ($cell2_ =='C1') && ($this->board_[$cell1_{0}][$cell1_{1}] == 'wk')){
                $this->_pred_c1 ='C1'; $this->_pred_v1 = $this->board_['C']['1']; $this->board_['C']['1'] = 'wk';
                $this->_pred_c2 ='D1'; $this->_pred_v2 = $this->board_['D']['1']; $this->board_['D']['1'] = 'wr';
                $this->_pred_c3 ='A1'; $this->_pred_v3 = $this->board_['A']['1']; $this->board_['A']['1'] = '';
                $this->_pred_c4 ='E1'; $this->_pred_v4 = $this->board_['E']['1']; $this->board_['E']['1'] = '';
                $this->may_white_short_castling =false;
                $this->may_white_long_castling  =false;
                if ($to_table_move){
                    $this->table_moves[$i_]['wpiece'] ='';
                    $this->table_moves[$i_]['wmove']  ='0-0-0';
                    $this->table_moves[$i_]['w_to_piece']  ='';
                }
#короткая рокировка черных
            }else if (($cell1_ =='E8') && ($cell2_ =='G8') && ($this->board_[$cell1_{0}][$cell1_{1}] == 'bk')){
                $this->_pred_c1 ='G8'; $this->_pred_v1 = $this->board_['G']['8']; $this->board_['G']['8'] = 'bk';
                $this->_pred_c2 ='F8'; $this->_pred_v2 = $this->board_['F']['8']; $this->board_['F']['8'] = 'br';
                $this->_pred_c3 ='H8'; $this->_pred_v3 = $this->board_['H']['8']; $this->board_['H']['8'] = '';
                $this->_pred_c4 ='E8'; $this->_pred_v4 = $this->board_['E']['8']; $this->board_['E']['8'] = '';
                $this->may_black_short_castling =false;
                $this->may_black_long_castling  =false;
                if ($to_table_move){
                    $this->table_moves[$i_]['bpiece'] ='';
                    $this->table_moves[$i_]['bmove']  ='0-0';
                    $this->table_moves[$i_]['b_to_piece']  ='';
                }
#длинная рокировка черных
            }elseif (($cell1_ =='E8') && ($cell2_ =='C8') && ($this->board_[$cell1_{0}][$cell1_{1}] == 'bk')){
                $this->_pred_c1 ='C8'; $this->_pred_v1 = $this->board_['C']['8']; $this->board_['C']['8'] = 'bk';
                $this->_pred_c2 ='D8'; $THIS->_pred_v2 = $this->board_['D']['8']; $this->board_['D']['8'] = 'br';
                $this->_pred_c3 ='A8'; $this->_pred_v3 = $this->board_['A']['8']; $this->board_['A']['8'] = '';
                $this->_pred_c4 ='E8'; $this->_pred_v4 = $this->board_['E']['8']; $this->board_['E']['8'] = '';
                $this->may_black_short_castling =false;
                $this->may_black_long_castling  =false;
                if ($to_table_move){
                    $this->table_moves[$i_]['bpiece'] ='';
                    $this->table_moves[$i_]['bmove']  ='0-0-0';
                    $this->table_moves[$i_]['b_to_piece']  ='';
                }
#белая пешка - взятие на проходе
            }elseif (($this->board_[$cell1_{0}][$cell1_{1}] == 'wp') &&
                      ($cell1_{1} == 5) &&
                      (($cell2_ ==chr(ord($cell1_{0})+1).($cell1_{1}+1)) || ($cell2_ ==chr(ord($cell1_{0})-1).($cell1_{1}+1))) &&
                      ($this->board_[$cell2_{0}][$cell2_{1}] == '')){
                $this->_pred_c1 =$cell1_;        $this->_pred_v1 = 'wp';                           $this->board_[$cell1_{0}][$cell1_{1}] = '';
                $this->_pred_c2 =$cell2_;        $this->_pred_v2 = '';                             $this->board_[$cell2_{0}][$cell2_{1}] = 'wp';
                $this->_pred_c3 =$cell2_{0}.'5'; $this->_pred_v3 = $this->board_[$cell2_{0}]['5']; $this->board_[$cell2_{0}]['5'] = '';
                $this->_pred_c4 ='';             $this->_pred_v4 = '';
                if ($to_table_move){
                    $this->table_moves[$i_]['wpiece'] ='';
                    $this->table_moves[$i_]['wmove']  =strtolower($cell1_).':'.strtolower($cell2_);
                    $this->table_moves[$i_]['w_to_piece']  ='';
                }
#черная пешка - взятие на проходе
            }elseif (($this->board_[$cell1_{0}][$cell1_{1}] == 'bp') &&
                     ($cell1_{1} == 4) &&
                     (($cell2_ ==chr(ord($cell1_{0})+1).($cell1_{1}-1)) || ($cell2_ ==chr(ord($cell1_{0})-1).($cell1_{1}-1))) &&
                     ($this->board_[$cell2_{0}][$cell2_{1}] == '')){
                $this->_pred_c1 =$cell1_;        $this->_pred_v1 = 'bp';                           $this->board_[$cell1_{0}][$cell1_{1}] = '';
                $this->_pred_c2 =$cell2_;        $this->_pred_v2 = '';                             $this->board_[$cell2_{0}][$cell2_{1}] = 'bp';
                $this->_pred_c3 =$cell2_{0}.'4'; $this->_pred_v3 = $this->board_[$cell2_{0}]['4']; $this->board_[$cell2_{0}]['4'] = '';
                $this->_pred_c4 ='';             $this->_pred_v4 = '';
                if ($to_table_move){
                    $this->table_moves[$i_]['bpiece'] ='';
                    $this->table_moves[$i_]['bmove']  =strtolower($cell1_).':'.strtolower($cell2_);
                    $this->table_moves[$i_]['b_to_piece']  ='';
                }
            }else{
                if ($cell1_ == 'E1'){
                    $this->may_white_short_castling =false;
                    $this->may_white_long_castling  =false;
                }elseif ($cell1_ == 'H1') $this->may_white_short_castling =false;
                elseif  ($cell1_ == 'A1') $this->may_white_long_castling  =false;
                elseif  ($cell1_ == 'H8') $this->may_black_short_castling =false;
                elseif  ($cell1_ == 'A8') $this->may_black_long_castling  =false;
                elseif  ($cell1_ == 'E8'){
                    $this->may_black_short_castling =false;
                    $this->may_black_long_castling  =false;
                }
                $this->_pred_c1 =$cell1_;   $this->_pred_v1 = $this->board_[$cell1_{0}][$cell1_{1}];
                $this->_pred_c2 =$cell2_;   $this->_pred_v2 = $this->board_[$cell2_{0}][$cell2_{1}];
                $this->_pred_c3 ='';        $this->_pred_v3 = '';
                $this->_pred_c4 ='';        $this->_pred_v4 = '';

                $this->board_[$cell2_{0}][$cell2_{1}] =($piece_ !='') ? $piece_ : $this->board_[$cell1_{0}][$cell1_{1}];
                $this->board_[$cell1_{0}][$cell1_{1}] ='';
                if ($to_table_move && (strlen($this->_pred_v1) ==2))
                    if ($this->_pred_v1{0} == 'w'){
                        $this->table_moves[$i_]['wpiece'] =($this->_pred_v1{1} != 'p') ? $this->_pred_v1{1} : '';
                        $this->table_moves[$i_]['wmove']  =($this->_pred_v2 !='') ?	strtolower($cell1_).':'.strtolower($cell2_) : strtolower($cell1_).'-'.strtolower($cell2_);
                        $this->table_moves[$i_]['w_to_piece']  =($piece_ !='') ? $piece_{1} : '';
                    }else{
                        $this->table_moves[$i_]['bpiece'] =($this->_pred_v1{1} != 'p') ? $this->_pred_v1{1} : '';
                        $this->table_moves[$i_]['bmove']  =($this->_pred_v2 !='') ?	strtolower($cell1_).':'.strtolower($cell2_) : strtolower($cell1_).'-'.strtolower($cell2_);
                        $this->table_moves[$i_]['b_to_piece']  =($piece_ !='') ? $piece_{1} : '';
                    }
            }

            if ($cell1_ == $this->white_king_last_position) $this->white_king_last_position =$cell2_;
            if ($cell1_ == $this->black_king_last_position) $this->black_king_last_position =$cell2_;
        }#move

        public function checkFirstClick($cell_){
            $result_ =false;
            if ($this->_checkCellBoard($cell_)){
                $i_ =$cell_{0};
                $j_ =$cell_{1};
                if (($this->board_[$i_][$j_] != '') &&
                    ((!$this->lastMoveIsWhite_ && ($this->board_[$i_][$j_]{0} =='w')) ||
                     ($this->lastMoveIsWhite_ && ($this->board_[$i_][$j_]{0} =='b'))))
                  $result_ =true;
            }
            return $result_;
        }#checkFirstClick

        public function checkMove($cell1_,$cell2_){
            $result_ =false;
            $color_ ='';
            if ($this->checkFirstClick($cell1_)){
                switch ($this->board_[$cell1_{0}][$cell1_{1}]){
#если выбрана белая пешка - проверяю правильность хода                    
                    case 'wp' :
                        $color_ ='w';
                        $this->_clearCurrStep();
                        while (($tryCell_ = $this->_whitePeshkaTryStep($cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;
#если выбрана чёрная пешка - проверяю правильность хода
                    case 'bp' :
                        $color_ ='b';
                        $this->_clearCurrStep();
                        while (($tryCell_ = $this->_blackPeshkaTryStep($cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;
#если выбрана белый конь
                    case 'wn' :
                        $color_ ='w';
                        $this->_clearCurrStep();
                        while (($tryCell_ = $this->_knightTryStep('w',$cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;
#если выбрана черный конь
                    case 'bn' :
                        $color_ ='b';
                        $this->_clearCurrStep();
                        while (($tryCell_ = $this->_knightTryStep('b',$cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;
#если выбран белый слон
                    case 'wb' :
                        $color_ ='w';
                        $this->_clearCurrStep();
                        $this->_clearWorkCell();
                        while (($tryCell_ = $this->_bishopTryStep('w',$cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;
#если выбран чёрный слон
                    case 'bb' :
                        $color_ ='b';
                        $this->_clearCurrStep();
                        $this->_clearWorkCell();
                        while (($tryCell_ = $this->_bishopTryStep('b',$cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;        
#если выбрана белая ладья
                    case 'wr' :
                        $color_ ='w';
                        $this->_clearCurrStep();
                        $this->_clearWorkCell();
                        while (($tryCell_ = $this->_rookTryStep('w',$cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;    
#если выбрана черная ладья
                    case 'br' :
                        $color_ ='b';
                        $this->_clearCurrStep();
                        $this->_clearWorkCell();
                        while (($tryCell_ = $this->_rookTryStep('b',$cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;
#если выбран белый ферзь
                    case 'wq' :
                        $color_ ='w';
                        $this->_clearCurrStep();
                        $this->_clearWorkCell();
                        while (($tryCell_ = $this->_queenTryStep('w',$cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;
#если выбран черный ферзь
                    case 'bq' :
                        $color_ ='b';
                        $this->_clearCurrStep();
                        $this->_clearWorkCell();
                        while (($tryCell_ = $this->_queenTryStep('b',$cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;
#если выбран белый король
                    case 'wk' :
                        $color_ ='w';
                        $this->_clearCurrStep();
                        while (($tryCell_ = $this->_kingTryStep('w',$cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;
#если выбран черный король
                    case 'bk' :
                        $color_ ='b';
                        $this->_clearCurrStep();
                        while (($tryCell_ = $this->_kingTryStep('b',$cell1_)) != '')
                            if ($tryCell_ == $cell2_){
                                $result_ = true;
                                break;
                            }
                        break;
                }#switch
            }

            if ($result_)
                if ($color_ !=='')
                        $result_ =!$this->_isCheckAfterMove($color_,$cell1_,$cell2_);
                    else
                        $result_ =false;

            return $result_;
        }#checkMove

#Проверяется будет ли король находится под ударом после совершения хода.
        protected function _isCheckAfterMove($color_,$cell1_,$cell2_){
            $result_ =false;
            $copy_may_white_short_castling =$this->may_white_short_castling;
            $copy_may_white_long_castling  =$this->may_white_long_castling;
            $copy_may_black_short_castling =$this->may_black_short_castling;
            $copy_may_black_long_castling  =$this->may_black_long_castling;
            $copy_white_king_last_position =$this->white_king_last_position;
            $copy_black_king_last_position =$this->black_king_last_position;

            $copy_pred_c1 = $this->_pred_c1; $copy_pred_v1 = $this->_pred_v1;
            $copy_pred_c2 = $this->_pred_c2; $copy_pred_v2 = $this->_pred_v2;
            $copy_pred_c3 = $this->_pred_c3; $copy_pred_v3 = $this->_pred_v3;
            $copy_pred_c4 = $this->_pred_c4; $copy_pred_v4 = $this->_pred_v4;

            $this->move($cell1_,$cell2_);
            if ($color_ == 'w')
                    $result_=$this->isCheckCell($color_,$this->white_king_last_position);
                else
                    $result_=$this->isCheckCell($color_,$this->black_king_last_position);
#откатываю ход
            $this->board_[$this->_pred_c1{0}][$this->_pred_c1{1}] =$this->_pred_v1;
            $this->board_[$this->_pred_c2{0}][$this->_pred_c2{1}] =$this->_pred_v2;
            if ($this->_pred_c3 !='') $this->board_[$this->_pred_c3{0}][$this->_pred_c3{1}] =$this->_pred_v3;
            if ($this->_pred_c4 !='') $this->board_[$this->_pred_c4{0}][$this->_pred_c4{1}] =$this->_pred_v4;

            $this->_pred_c1 = $copy_pred_c1; $this->_pred_v1 = $copy_pred_v1;
            $this->_pred_c2 = $copy_pred_c2; $this->_pred_v2 = $copy_pred_v2;
            $this->_pred_c3 = $copy_pred_c3; $this->_pred_v3 = $copy_pred_v3;
            $this->_pred_c4 = $copy_pred_c4; $this->_pred_v4 = $copy_pred_v4;

            $this->may_white_short_castling =$copy_may_white_short_castling;
            $this->may_white_long_castling  =$copy_may_white_long_castling;
            $this->may_black_short_castling =$copy_may_black_short_castling;
            $this->may_black_long_castling  =$copy_may_black_long_castling;
            $this->white_king_last_position =$copy_white_king_last_position;
            $this->black_king_last_position =$copy_black_king_last_position;

            return $result_;
        }#_isCheckAfterMove

#Определяет цвет клетки
        public function getColorBoard($cell_){
            if (($cell_ == 'B8') || ($cell_ == 'D8') || ($cell_ == 'F8') || ($cell_ == 'H8') ||
                ($cell_ == 'A7') || ($cell_ == 'C7') || ($cell_ == 'E7') || ($cell_ == 'G7') ||
                ($cell_ == 'B6') || ($cell_ == 'D6') || ($cell_ == 'F6') || ($cell_ == 'H6') ||
                ($cell_ == 'A5') || ($cell_ == 'C5') || ($cell_ == 'E5') || ($cell_ == 'G5') ||
                ($cell_ == 'B4') || ($cell_ == 'D4') || ($cell_ == 'F4') || ($cell_ == 'H4') ||
                ($cell_ == 'A3') || ($cell_ == 'C3') || ($cell_ == 'E3') || ($cell_ == 'G3') ||
                ($cell_ == 'B2') || ($cell_ == 'D2') || ($cell_ == 'F2') || ($cell_ == 'H2') ||
                ($cell_ == 'A1') || ($cell_ == 'C1') || ($cell_ == 'E1') || ($cell_ == 'G1'))
                    return 'b';
               else
                    return 'w';
        }#getColorBoard

#Проверяется возможность осуществить какой-либо ход. true - ходы есть, false - ходов нет
        public function isMayMove($color_){
            for($a_ ='A'; $a_ <='H'; $a_ =chr(ord($a_)+1))
                for($b_ =1; $b_ <=8; $b_++)
                    if (($this->board_[$a_][$b_] != '') && ($this->board_[$a_][$b_]{0} == $color_)){
                        $this->_clearCurrStep();
                        $this->_clearWorkCell();
                        do{
                            $c_ ='';
                            if ($this->board_[$a_][$b_] == 'wp') $c_=$this->_whitePeshkaTryStep($a_.$b_);
                                elseif ($this->board_[$a_][$b_] == 'bp') $c_=$this->_blackPeshkaTryStep($a_.$b_);
                                elseif ($this->board_[$a_][$b_]{1} == 'n') $c_=$this->_knightTryStep($color_,$a_.$b_);
                                elseif ($this->board_[$a_][$b_]{1} == 'b') $c_=$this->_bishopTryStep($color_,$a_.$b_);
                                elseif ($this->board_[$a_][$b_]{1} == 'r') $c_=$this->_rookTryStep($color_,$a_.$b_);
                                elseif ($this->board_[$a_][$b_]{1} == 'q') $c_=$this->_queenTryStep($color_,$a_.$b_);
                                elseif ($this->board_[$a_][$b_]{1} == 'k') $c_=$this->_kingTryStep($color_,$a_.$b_);
                            if (($c_ != '') && !$this->_isCheckAfterMove($color_,$a_.$b_,$c_)) return true;
                        }while($c_ !='');
                    }
            return false;
        }#isMayMove

#Определяет окончание партии. Возвращает 0 - партия не окончена, 1 - поставлен мат, 2 -поставлен пат
#Если $color_ ='w' - анализируется окончание партии после хода чёрных, если $color_ = 'b' - анализируется окончание партии после хода белых
	public function isEndGame($color_){
            if ($color_ == 'w')
                $c_ = $this->white_king_last_position;
            else
                $c_ = $this->black_king_last_position;
	    $a_ = $this->isMayMove($color_);
	    if ($this->isCheckCell($color_,$c_) && !$a_) return 1;
                elseif (!$this->isCheckCell($color_,$c_) && !$a_) return 2;
		else return 0;
	}#isEndGame

#Определяет окончание партии после совершения хода. Возвращает 0 -партия не окончена, 1 - поставлен мат, 2 - поставлен пат
#Если $color_ ='w' - анализируется окончание партии после хода чёрных, если $color_ = 'b' - анализируется окончание партии после хода белых
	public function isEndAfterMove($cell1_,$cell2_,$piece_ =''){
            $color_ ='';
            if ($this->board_[$cell1_{0}][$cell1_{1}]  !=''){
                  $a=$this->board_[$cell1_{0}][$cell1_{1}]{0};
                  if ($a =='w') $color_ ='b';
                    elseif ($a=='b') $color_ ='w';
            }
            if ($color_ =='') throw new Exception('При проверки окончании партии произошла ошибка.');

	    $copy_may_white_short_castling =$this->may_white_short_castling;
	    $copy_may_white_long_castling  =$this->may_white_long_castling;
	    $copy_may_black_short_castling =$this->may_black_short_castling;
	    $copy_may_black_long_castling  =$this->may_black_long_castling;
	    $copy_white_king_last_position =$this->white_king_last_position;
	    $copy_black_king_last_position =$this->black_king_last_position;

	    $copy_pred_c1 = $this->_pred_c1; $copy_pred_v1 = $this->_pred_v1;
	    $copy_pred_c2 = $this->_pred_c2; $copy_pred_v2 = $this->_pred_v2;
	    $copy_pred_c3 = $this->_pred_c3; $copy_pred_v3 = $this->_pred_v3;
	    $copy_pred_c4 = $this->_pred_c4; $copy_pred_v4 = $this->_pred_v4;

	    $this->move($cell1_,$cell2_);
	    $result_ =$this->isEndGame($color_);
            
#откатываю ход
	    $this->board_[$this->_pred_c1{0}][$this->_pred_c1{1}] =$this->_pred_v1;
	    $this->board_[$this->_pred_c2{0}][$this->_pred_c2{1}] =$this->_pred_v2;
	    if ($this->_pred_c3 !='') $this->board_[$this->_pred_c3{0}][$this->_pred_c3{1}] =$this->_pred_v3;
	    if ($this->_pred_c4 !='') $this->board_[$this->_pred_c4{0}][$this->_pred_c4{1}] =$this->_pred_v4;

	    $this->_pred_c1 = $copy_pred_c1; $this->_pred_v1 = $copy_pred_v1;
	    $this->_pred_c2 = $copy_pred_c2; $this->_pred_v2 = $copy_pred_v2;
	    $this->_pred_c3 = $copy_pred_c3; $this->_pred_v3 = $copy_pred_v3;
	    $this->_pred_c4 = $copy_pred_c4; $this->_pred_v4 = $copy_pred_v4;

	    $this->may_white_short_castling =$copy_may_white_short_castling;
	    $this->may_white_long_castling  =$copy_may_white_long_castling;
	    $this->may_black_short_castling =$copy_may_black_short_castling;
	    $this->may_black_long_castling  =$copy_may_black_long_castling;
	    $this->white_king_last_position =$copy_white_king_last_position;
	    $this->black_king_last_position =$copy_black_king_last_position;

	    return $result_;
	}#isEndAfterMove

    }#CRuleGame
