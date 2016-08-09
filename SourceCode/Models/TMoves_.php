<?php
    require_once('const_.php');
    require_once('rule_game_.php');

    class CTMoves{
        public $id; //id_ партии
        
        public function __construct($id_game) {
            $this->id = $id_game;
        }#__construct
        
        public function lastPosition($game){ //$game - объект CRuleGame
            $connect  = false;
            $transact = false;
            $cursor   = false;
            try{
                $game->firstPosition();
                
                if(const_::$connect_ === false){
                    if (const_::SetConnect_()) $connect = true; else throw new Exception();
                }
                if(const_::$isTransact_ === false){
                    if (const_::StartTransaction_()) $transact =true; else throw new Exception();               
                }

                $s ='select num_,WMoveCell1_,WMoveCell2_,WPiece_,w_isCheck_,w_isCheckMate_,'.
                    '       BMoveCell1_,BMoveCell2_,BPiece_,b_isCheck_,b_isCheckMate_'.
                    ' from TMoves_'.
                    ' where idGame_ ='.$this->id.' order by num_';
                $cursor =mysql_query($s,const_::$connect_); if (!$cursor) throw new Exception();
                $not_move_black =false;
                $game->num_ = 0; $game->lastMoveIsWhite_ = false;
                while ($row = mysql_fetch_array($cursor)){
#проверяется пропущен ли ход чёрных
                    if ($not_move_black_)
                        throw new Exception('В информации о партии обнаружена ошибка. Сообщите, пожалуйста, об этом разработчику.');
#читаю строку
                    $wcell1 = $row['WMoveCell1_'];
                    $wcell2 = $row['WMoveCell2_'];
                    $wpiece = (($wpiece = $row['WPiece_']) != '') ? 'w'.$wpiece : '';
                    $bcell1 = $row['BMoveCell1_'];
                    $bcell2 = $row['BMoveCell2_'];
                    $bpiece = (($bpiece = $row['BPiece_']) != '') ? 'b'.$bpiece : '';
                    $game->num_ =$row['num_'];
                    $game->table_moves[]['num'] =$game->num_; $i = count($game->table_moves)-1;
                    if ($row['w_isCheck_'] == 'Y') $game->table_moves[$i]['w_isCheck']='+';
                        elseif  ($row['w_isCheckMate_'] == 'Y') $game->table_moves[$i]['w_isCheck']='#';
                        else $game->table_moves[$i]['w_isCheck']='';
                    if ($row['b_isCheck_'] == 'Y') $game->table_moves[$i]['b_isCheck']='+';
                        elseif  ($row['b_isCheckMate_'] == 'Y') $game->table_moves[$i]['b_isCheck']='#';
                        else $game->table_moves[$i]['b_isCheck']='';
//выполняется ход белых
                    $game->move($wcell1,$wcell2,(($game->board_[$wcell1{0}][$wcell1{1}] == 'wp') && ($wcell2{1} == 8)) ? $wpiece : '',true);
                    $game->lastMoveIsWhite_ =true;
//выполняется ход чёрных
                    if (($bcell1 != '') && ($bcell2 != '')){
                        $game->move($bcell1,$bcell2,(($game->board_[$bcell1{0}][$bcell1{1}] == 'bp') && ($bcell2{1} == 1)) ? $bpiece : '',true);
                        $game->lastMoveIsWhite_ =false;
                    }else $not_move_black_ =true;
                }#while

                if (!$game->lastMoveIsWhite_) $game->num_++;
                mysql_free_result($cursor); $cursor = false;
                
                if($transact){
                    if (const_::Commit_()) $transact = false; else throw new Exception();
                }
                if($connect){
                    if (const_::Disconnect_()) $connect = false; else throw new Exception();
                }
                
            }catch(Exception $e){
                if ($cursor_) mysql_free_result($cursor_);
                if ($transact_) const_::Rollback_();
                if ($connect_) const_::Disconnect_();
                
                throw new Exception('При получении информации об партии произошла ошибка.');
            }
        }#lastPosition
    }#CTMoves


