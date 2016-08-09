<?php
	abstract class CPartOfQuery_{
		protected	$connect_;			#указатель на соединение с mysql
		public		$cVisibleNumPage;	#количество видимых номеров страниц
		public		$cRecordOnPage;		#количество записей на странице
		public		$cCountPages = 0;	#общее количество страниц
		protected	$listRecords;		#прочитанные записи
		public		$page_ = 0;			#прочитанная страница

		function __construct($connect_){
			$this->connect_ =$connect_;
			$this->cVisibleNumPage =5;
			$this->cRecordOnPage =10;
			$this->listRecords =array();
		} #__construct

# строка в кодировке win1251: sql запрос выводящий общее количество записей,
# должен возврощать одну строку и одну колонку с именем count_
		abstract protected function str_select_for_countPage();
# строка в кодировке win1251: sql запрос выводящий требуемые строки,
# к данному запросу будет добавлена конструкция limit
		abstract protected function str_select_for_getRecords();

		public function countPages($start_transact){
			try{
				$transact_ =false;
				if ($start_transact)
					if (mysql_query('start transaction',$this->connect_)) $transact_ =true; else throw new Exception();

				$s_ =$this->str_select_for_countPage();
				$s_ =convert_cyr_string($s_,'w','d');
				$cursor_=mysql_query($s_,$this->connect_); if (!$cursor_) throw new Exception();
				$row_ =mysql_fetch_array($cursor_);
				$b =$row_['count_'];
				mysql_free_result($cursor_);
				if ($transact_)
					if (!mysql_query('commit',$this->connect_)) throw new Exception();
				$this->cCountPages =floor($b / $this->cRecordOnPage);
				if (($b % $this->cRecordOnPage) != 0) $this->cCountPages++;

				return true;
			}catch(Exception $e){
				if ($transact_) mysql_query('rollback',$this->connect_);
				return false;
			}
		}#countPages

		public function getFirstVisibleNum($num_page){
			$result_ =floor($num_page / $this->cVisibleNumPage);
			if (($num_page % $this->cVisibleNumPage) == 0) $result_--;
			$result_=$result_ * $this->cVisibleNumPage +1;
			return $result_;
		}#getFirstVisibleNum

		public function getLastVisibleNum($num_page){
			$a =$this->getFirstVisibleNum($num_page);
			$result_ =$a;
			for($i=2; ($i <=$this->cVisibleNumPage) && ($result_ < $this->cCountPages); $i++) $result_++;
			return $result_;
		}#getLastVisibleNum

		protected function getRecords($start_transact,$page_,$columns_){
			try{
				$this->listRecords_ =array();
				$transact_ =false;
				if ($start_transact)
					if (mysql_query('start transaction',$this->connect_)) $transact_ =true; else throw new Exception();

				if (!$this->countPages(false)) throw new Exception();
				if ($this->cCountPages > 0){
					if (($page_ > $this->cCountPages) || ($page_ <= 0)) $page_=1;

					$s_ =$this->str_select_for_getRecords().' limit '.(($page_-1)* $this->cRecordOnPage).', '.$this->cRecordOnPage;
					$s_ =convert_cyr_string($s_,'w','d');
					$cursor_=mysql_query($s_,$this->connect_); if (!$cursor_) throw new Exception();
					while($row_ =mysql_fetch_array($cursor_)){
						$j =count($this->listRecords);
						for($i=0; $i < count($columns_); $i++)
							$this->listRecords[$j][$columns_[$i]] = $row_[$i];
					} #while
					mysql_free_result($cursor_);
					$this->page_ =$page_;
				}else $this->page_ =0;

				if ($transact_)
					if (!mysql_query('commit',$this->connect_)) throw new Exception();
				return true;
			}catch(Exception $e){
				if ($transact_) mysql_query('rollback',$this->connect_);
				return false;
			}
		}#getRecords
	} #CPartOfQuery

 class EmylibError extends Exception{};

	class mylib{
		public static function ChiperXOR($str_,$key_){
			$result_ ='';
			for($i=0, $j=0; $i < strlen($str_); $i++,($j < (strlen($key_)-1)) ? $j++ : $j=0)
				$result_ .=$str_{$i} ^ $key_{$j};
			return $result_;
		}#ChiperXOR

#функция конвертирует указанное пользователем изображение в png
#file_ - массив возвращаемый $_FILES['имя поля']
#max_px -высота и ширина полученного после конвертации изображение не должна превышать это значение
#max_byte -размер изображения после конвертации не должен превышать указанное значение
#в случае ошибки будет сгенерированно исключение EmylibError
        public static function load_and_convert_to_png($file_,$max_px,$max_byte){
            if ($file_['name'] =='') throw new EmylibError('Файл не указан.');
            if ($file_['error'] !=0) throw new EmylibError('При загрузке файла произошла ошибка.');
            $photo_ =@getimagesize($file_['tmp_name']); #получаю информацию об изображении
            if (!$photo_) throw new EmylibError('Формат файла не удалось распознать.');
            $im_1 =false;
            switch ($photo_[2]){
              case 1:
                $im_1 = @imagecreatefromgif($file_['tmp_name']);
                break;
              case 2:
                $im_1 = @imagecreatefromjpeg($file_['tmp_name']);
                break;
              case 3:
                $im_1 = @imagecreatefrompng($file_['tmp_name']);
                break;
              case 6:
                $im_1 = @imagecreatefromwbmp($file_['tmp_name']);
                break;
              case 15:
                $im_1 = @imagecreatefromwbmp($file_['tmp_name']);
                break;
              case 16:
                $im_1 = @imagecreatefromxbm($file_['tmp_name']);
                break;
            }#switch
            if ($im_1){
              $a=1;
              $x =imagesx($im_1);
              $y =imagesy($im_1);
              if (($x > $y) && ($x > $max_px))
                $a =$x/$max_px;
               else if (($y > $x) && ($y > $max_px))
                $a =$y/$max_px;
              if ($a !=1){
                $x_new = round($x/$a);
                $y_new = round($y/$a);
                $im_2 =imagecreatetruecolor($x_new, $y_new);
                imagecopyresampled ($im_2, $im_1, 0, 0, 0, 0, $x_new, $y_new, $x, $y);
                imagedestroy($im_1);
                $im_1=$im_2;
              }
              ob_start(); #включаю буферизацию вывода
              imagepng($im_1);
              $photo_=ob_get_contents(); #забираю изображение из буфера
              ob_end_clean(); #отключаю буферизацию
              imagedestroy($im_1);
              if (strlen($photo_) > $max_byte)
                throw new EmylibError('Размер сохранянмых данных превышает ограничение (попробуйте выбрать другой файл).');
              return $photo_;
            }else
              throw new EmylibError('Формат файла не удалось распознать.');
        }#convert_img_to_png
	}#mylib
?>