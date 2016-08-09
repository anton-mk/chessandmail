<?php
    require_once('const_.php');
    require_once('Users.php');
    require_once('lib/mylib.php');

    class CListMens extends CPartOfQuery_{
/*     params_['class_'] - класс,
       params_['rating_A_1'] - рейтинг А нижняя граница,
       params_['rating_A_2'] - рейтинг А верхняя граница,
       params_['rating_B_1'] - рейтинг B нижняя граница,
       params_['rating_B_2'] - рейтинг B верхняя граница,
       params_['rating_C_1'] - рейтинг C нижняя граница,
       params_['rating_C_2'] - рейтинг C верхняя граница,
       params_['last_visit'] - дата последней активности на сайте
       params_['city_']      - город/населеннный пункт
       params_['country_']   - страна
       params_['rule_country_'] - правило анализа страны
       params_['login_'] - логин участника
       params_['sort_field_'] - сортировка
       params_['sort_order_'] - правило сортировки
*/
       protected $params_;
       public $records_ =array();
       public static $type_=1;

       public static function get_link($params_,$page_){
            $href_='MainPage.php?link_='.(CListMens::$type_ ==1 ? 'mens_on_site' : 'find_mens');
            if ($params_['class_'] != '')
              $href_ .='&find_class_='.$params_['class_'];
            if ($params_['rating_A_1'] != '')
              $href_ .='&find_rating_A_1='.$params_['rating_A_1'];
            if ($params_['rating_A_2'] != '')
              $href_ .='&find_rating_A_2='.$params_['rating_A_2'];
            if ($params_['rating_B_1'] != '')
              $href_ .='&find_rating_B_1='.$params_['rating_B_1'];
            if ($params_['rating_B_2'] != '')
              $href_ .='&find_rating_B_2='.$params_['rating_B_2'];
            if ($params_['rating_C_1'] != '')
              $href_ .='&find_rating_C_1='.$params_['rating_C_1'];
            if ($params_['rating_C_2'] != '')
              $href_ .='&find_rating_C_2='.$params_['rating_C_2'];
            if ($params_['last_visit'] != '')
              $href_ .='&find_last_visit='.$params_['last_visit'];
            if ($params_['city_'] != '')
              $href_ .='&find_city_='.urlencode($params_['city_']);
            if ($params_['country_'] != '')
              $href_ .='&find_country_='.urlencode($params_['country_']);
            if ($params_['rule_country_'] != '')
              $href_ .='&find_rule_country_='.$params_['rule_country_'];
            if ($params_['login_'] != '')
              $href_ .='&find_login_='.urlencode($params_['login_']);
            if ($params_['sort_field_'] != '')
              $href_ .='&sort_field_='.$params_['sort_field_'];
            if ($params_['sort_order_'] != '')
              $href_ .='&sort_order_='.$params_['sort_order_'];

            $href_ .='&page='.$page_;
            return $href_;
       }#get_link

       public function __construct($params_){
            $this->params_ =$params_;
            parent::__construct(const_::$connect_);
       }#__construct

       protected function get_where_and_sort(&$where_,&$sort_){
            $where_='';
            if ($this->params_['class_'] != ''){
              if ($this->params_['class_']{0} =='A')
                 $where_ ='(A.classA_ ='.$this->params_['class_']{1}.')';
               else if ($this->params_['class_']{0} =='B')
                 $where_ ='(A.classB_ ='.$this->params_['class_']{1}.')';
               else if ($this->params_['class_']{0} =='C')
                 $where_ ='(A.classC_ ='.$this->params_['class_']{1}.')';
            }
            if ($this->params_['rating_A_1'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='(A.ratingA_ >='.$this->params_['rating_A_1'].')';
            }
            if ($this->params_['rating_A_2'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='(A.ratingA_ <='.$this->params_['rating_A_2'].')';
            }
            if ($this->params_['rating_B_1'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='(A.ratingB_ >='.$this->params_['rating_B_1'].')';
            }
            if ($this->params_['rating_B_2'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='(A.ratingB_ <='.$this->params_['rating_B_2'].')';
            }
            if ($this->params_['rating_C_1'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='(A.ratingC_ >='.$this->params_['rating_C_1'].')';
            }
            if ($this->params_['rating_C_2'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='(A.ratingC_ <='.$this->params_['rating_C_2'].')';
            }
            $a =getdate(time());
            $sec_   =$a['seconds'];
            $min_   =$a['minutes'];
            $hour_  =$a['hours'];
            $day_   =$a['mday'];
            $month_ =$a['mon'];
            $year_  =$a['year'];
            if ($this->params_['last_visit'] == 'on_line'){
              if ($where_ !='') $where_ .=' and ';
              $a =time() - TIME_CHECK_ON_SITE;
              $where_ .='(not A.last_connect_ is null) and (A.last_connect_ >='.$a.')';
            }else if ($this->params_['last_visit'] == 'today'){
              if ($where_ !='') $where_ .=' and ';
              $a =mktime(0,0,0,$month_,$day_,$year_);
              $where_ .='(not A.last_connect_ is null) and (A.last_connect_ >='.$a.')';
            }else if (($this->params_['last_visit'] >= 'day_1') && ($this->params_['last_visit'] <= 'day_7')){
              if ($where_ !='') $where_ .=' and ';
              $day_ -=$this->params_['last_visit']{4};
              $a =mktime(0,0,0,$month_,$day_,$year_);
              $b =mktime(0,0,0,$month_,$day_+1,$year_);
              $where_ .='(not A.last_connect_ is null) and (A.last_connect_ >='.$a.') and (A.last_connect_ <'.$b.')';
            }else if (($this->params_['last_visit'] >= 'week_1') && ($this->params_['last_visit'] <= 'week_5')){
              if ($where_ !='') $where_ .=' and ';
              $day_ -=$this->params_['last_visit']{5}*7;
              $a =mktime(0,0,0,$month_,$day_,$year_);
              $where_ .='(not A.last_connect_ is null) and (A.last_connect_ >='.$a.')';
            }else if (($this->params_['last_visit'] >= 'month_1') && ($this->params_['last_visit'] <= 'month_6')){
              if ($where_ !='') $where_ .=' and ';
              $month_ -=$this->params_['last_visit']{6};
              $a =mktime(0,0,0,$month_,$day_,$year_);
              $where_ .='(not A.last_connect_ is null) and (A.last_connect_ >='.$a.')';
            }else if ($this->params_['last_visit'] == 'larger_month_6'){
              if ($where_ !='') $where_ .=' and ';
              $month_ -=6;
              $a =mktime(0,0,0,$month_,$day_,$year_);
              $where_ .='((A.last_connect_ is null) or (A.last_connect_ < '.$a.'))';
            }
            if ($this->params_['city_'] != ''){
              if ($where_ !='') $where_ .=' and ';
              if ((strpos($this->params_['city_'],'%') !==false) || (strpos($this->params_['city_'],'_') !==false))
                 $where_ .='(A.punkt_ like \''. mysql_escape_string($this->params_['city_']).'\')';
              else
                 $where_ .='(A.punkt_ like \'%'. mysql_escape_string($this->params_['city_']).'%\')';
            }
            if ($this->params_['country_'] != ''){
              if ($where_ !='') $where_ .=' and (';
              if ($this->params_['rule_country_'] == 2)
                 $where_ .=' not';
              if ((strpos($this->params_['country_'],'%') !==false) || (strpos($this->params_['country_'],'_') !==false))
                 $where_ .=' A.country_ like \''. mysql_escape_string($this->params_['country_']).'\'';
              else
                 $where_ .=' A.country_ like \'%'. mysql_escape_string($this->params_['country_']).'%\'';
              $where_ .=')';
            }
            if ($this->params_['login_'] != ''){
              if ($where_ !='') $where_ .=' and ';
              $where_ .='(A.login_ =\''. mysql_escape_string($this->params_['login_']).'\')';
            }

            $sort_ ='';
            if ($this->params_['sort_field_'] != ''){
              switch ($this->params_['sort_field_']){
                case 1 : $sort_ ='A.login_'; break;
                case 2 : $sort_ ='A.famil_'; break;
                case 3 : $sort_ ='A.last_connect_'; break;
                case 4 : $sort_ ='A.classA_'; break;
                case 5 : $sort_ ='A.classB_'; break;
                case 6 : $sort_ ='A.classC_'; break;
                case 7 : $sort_ ='A.ratingA_'; break;
                case 8 : $sort_ ='A.ratingB_'; break;
                case 9 : $sort_ ='A.ratingC_'; break;
              }#switch
            }
            if (($sort_ != '') && ($this->params_['sort_order_'] == 2))
              $sort_ .=' desc';
       }#get_where_and_sort

       protected function str_select_for_countPage(){
            $result_ ='select count(*) as count_'.
                      ' from TGamers_ A';
            $where_ =''; $sort_ ='';
            $this->get_where_and_sort($where_,$sort_);
            if ($where_ !='') $result_ .=' where '.$where_;
            if ($sort_ !='') $result_ .=' order by '.$sort_;
            return $result_;
       } #str_select_for_countPage

       protected function str_select_for_getRecords(){
            $result_ ='select A.id_,A.login_,A.famil_,A.ima_,A.otchest_,A.classA_,A.ratingA_,A.classB_,'.
                      '       A.ratingB_,A.classC_,A.ratingC_,A.country_,A.punkt_,A.date_birth,A.last_connect_'.
                      ' from TGamers_ A';
            $where_ =''; $sort_ ='';
            $this->get_where_and_sort($where_,$sort_);
            if ($where_ !='') $result_ .=' where '.$where_;
            if ($sort_ !='') $result_ .=' order by '.$sort_;
            return $result_;
        }#str_select_for_countPage

       public function get_records($page_){
            try{
                if (!$this->getRecords(false,$page_,array('id_','login_','famil_','ima_','otchest_','classA_','ratingA_',
                                                          'classB_','ratingB_','classC_','ratingC_','country_',
                                                          'punkt_','date_birth','last_connect_')))
                    throw new Exception();
                for($i=0; $i<count($this->listRecords); $i++){
                    $this->records_[$i]['id_']        =$this->listRecords[$i]['id_'];
                    $this->records_[$i]['login_']     =convert_cyr_string($this->listRecords[$i]['login_'],'d','w');
                    $this->records_[$i]['famil_']     =convert_cyr_string($this->listRecords[$i]['famil_'],'d','w');
                    $this->records_[$i]['ima_']       =convert_cyr_string($this->listRecords[$i]['ima_'],'d','w');
                    $this->records_[$i]['otchest_']   =convert_cyr_string($this->listRecords[$i]['otchest_'],'d','w');
                    if (is_null($this->listRecords[$i]['last_connect_']))
                      $this->records_[$i]['last_connect_'] ='';
                    else{
                      $a =getdate($this->listRecords[$i]['last_connect_']);
                      $this->records_[$i]['last_connect_'] =sprintf("%02u-%02u-%04u %02u:%02u",
                                                                    $a['mday'],$a['mon'],$a['year'],
                                                                    $a['hours'],$a['minutes']);
                    }
                    $this->records_[$i]['class_']     ='A'.$this->listRecords[$i]['classA_'].
                                                       '/B'.$this->listRecords[$i]['classB_'].
                                                       '/C'.$this->listRecords[$i]['classC_'];
                    $this->records_[$i]['rating_'] ='A'.$this->listRecords[$i]['ratingA_'].
                                                    '/B'.$this->listRecords[$i]['ratingB_'].
                                                    '/C'.$this->listRecords[$i]['ratingC_'];
                    $this->records_[$i]['country_']   =convert_cyr_string($this->listRecords[$i]['country_'],'d','w');
                    $this->records_[$i]['punkt_']     =convert_cyr_string($this->listRecords[$i]['punkt_'],'d','w');
                    $this->records_[$i]['date_birth'] =$this->listRecords[$i]['date_birth'];
                } #for
            }catch(Exception $e){
#                throw new Exception(mysql_error());
                throw new Exception('При чтении информации об игроках произошла ошибка.');
            }
       }#get_records

       public function out_records(){
            $result_ ='<SPAN style="font-family: Liberation Serif, Times, sans-serif;'.
                                   'font-size: 12pt; color: black;'.
                                   'text-decoration: none; font-weight: normal">'."\n".
                      '   <TABLE style ="border: none; margin-left: auto; margin-right: auto" cellspacing="0" cellpadding="0">'."\n".
                      '      <COL span="1">'."\n";
            $m ='';
            if ($this->cCountPages > 1){
                $a =$this->getFirstVisibleNum($this->page_);
                $b =$this->getLastVisibleNum($this->page_);
                if ($a > 1) $m ='<A href="'.CListMens::get_link($this->params_,$a-1).'">'.htmlspecialchars('<<').'</A>';
                for ($i=$a; $i <=$b; $i++){
                    if ($m != '') $m .='&nbsp;';
                    if ($this->page_ != $i) $m .='<A href="'.CListMens::get_link($this->params_,$i).'">'.$i.'</A>'; else $m.=$i;
                }#for
                if ($b < $this->cCountPages) $m .='&nbsp;<A href="'.CListMens::get_link($this->params_,$b+1).'">'.htmlspecialchars('>>').'</A>';
                $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            }
            $result_ .='<TR><TD>'."\n".
                       '<TABLE cellspacing="3">'."\n".
                       '     <COL span="10">'."\n".
                       '     <TR><TD class="table_head_1">логин</TD>'."\n".
                       '         <TD class="table_head_1">фамилия</TD>'."\n".
                       '         <TD class="table_head_1">имя</TD>'."\n".
                       '         <TD class="table_head_1">отчество</TD>'."\n".
                       '         <TD class="table_head_1">последняя активность</TD>'."\n".
                       '         <TD class="table_head_1">класс</TD>'."\n".
                       '         <TD class="table_head_1">рейтинг</TD>'."\n".
                       '         <TD class="table_head_1">страна</TD>'."\n".
                       '         <TD class="table_head_1">города</TD>'."\n".
                       '         <TD class="table_head_1">дата рождения</TD>'."\n".
                       '     </TR>';
            for($i=0; $i < count($this->records_); $i++){
                $result_ .='<TR>'.
                              '<TD class="table_body_1">'.
                                   '<A href="MainPage.php?link_=about_gamer&login_='.urlencode($this->records_[$i]['login_']).'">'.
                                        htmlspecialchars($this->records_[$i]['login_']).
                                   '</A>'.
                              '</TD>'."\n".
                              '<TD class="table_body_1">'.($this->records_[$i]['famil_'] == '' ? '&nbsp;'  : htmlspecialchars($this->records_[$i]['famil_'])).'</TD>'."\n".
                              '<TD class="table_body_1">'.($this->records_[$i]['ima_'] == '' ? '&nbsp;' : htmlspecialchars($this->records_[$i]['ima_'])).'</TD>'."\n".
                              '<TD class="table_body_1">'.($this->records_[$i]['otchest_'] =='' ?  '&nbsp;' : htmlspecialchars($this->records_[$i]['otchest_'])).'</TD>'."\n".
                              '<TD class="table_body_1">'.($this->records_[$i]['last_connect_']  == '' ? '&nbsp;' : htmlspecialchars($this->records_[$i]['last_connect_'])).'</TD>'."\n".
                              '<TD class="table_body_1">'.htmlspecialchars($this->records_[$i]['class_']).'</TD>'."\n".
                              '<TD class="table_body_1">'.htmlspecialchars($this->records_[$i]['rating_']).'</TD>'."\n".
                              '<TD class="table_body_1">'.($this->records_[$i]['country_'] == '' ? '&nbsp;' : htmlspecialchars($this->records_[$i]['country_'])).'</TD>'."\n".
                              '<TD class="table_body_1">'.($this->records_[$i]['punkt_'] == '' ?  '&nbsp;' : htmlspecialchars($this->records_[$i]['punkt_'])).'</TD>'."\n".
                              '<TD class="table_body_1">'.($this->records_[$i]['date_birth'] == '' ? '&nbsp;' : htmlspecialchars($this->records_[$i]['date_birth'])).'</TD>'."\n".
                           '</TR>';
            } #for
            $result_ .='</TABLE>'."\n".
                       '</TD></TR>'."\n";
            if ($m != '')
              $result_ .='<TR><TD style="border: none; text-align: right">'.$m.'</TD></TR>'."\n";
            $result_ .='</TABLE></SPAN>';
            return $result_;
       }#out_records
    }#CListMens

    class CMens_{
        public static function MakeMenuMainPage($type_){            $i =CPage_::PositionMenu_('Игроки') +1;

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=mens_on_site';
            CPage_::$menu_[$i]['image'] ='Image/label_mens_on_line.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==1) ? 'Y' : 'N';

            CPage_::$menu_[$i]['link'] = 'MainPage.php?link_=find_mens';
            CPage_::$menu_[$i]['image'] ='Image/label_find.png';
            CPage_::$menu_[$i]['submit'] =false;
            CPage_::$menu_[$i]['level'] =2;
            CPage_::$menu_[$i++]['active'] =($type_ ==2) ? 'Y' : 'N';

            CPage_::MakeMenu_(CPage_::PositionMenu_('Игроки'));
        }#MakeMenuMainPage

        protected static function paramsForMensOnSite(){          $result_['class_'] ='';
          $result_['last_visit'] ='on_line';
          $result_['rating_A_1'] ='';
          $result_['rating_A_2'] ='';
          $result_['rating_B_1'] ='';
          $result_['rating_B_2'] ='';
          $result_['rating_C_1'] ='';
          $result_['rating_C_2'] ='';
          $result_['city_'] ='';
          $result_['country_'] ='';
          $result_['rule_country_'] =1;
          $result_['login_'] ='';
          $result_['sort_field_'] =1;
          $result_['sort_order_'] =1;

          return $result_;
        }#paramsForMensOnSite

        protected static function BodyMensOnSite($page_){          $params_ =CMens_::paramsForMensOnSite();

          $c =new CListMens($params_);
          $c->get_records($page_);

          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                              'font-size: 16pt; color: white; text-align: center;'.
                              'text-decoration: none; font-weight: normal">'."\n".
                       'Игроки, находящиеся сейчас на сайте'."\n".
                    '</DIV><BR><BR>'."\n";

          if (count($c->records_) > 0)
             $result_ .=$c->out_records();
          else
             $result_ .='<DIV style="text-align: center">игроки не найдены</DIV>';
          return $result_;
        }#BodyMensOnSite

        protected static function BodyFind($params_,$page_,$error_){
          if ($error_ == ''){
            $c =new CListMens($params_);
            $c->get_records($page_);
          }

          $result_ ='<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                'font-size: 16pt; color: white; text-align: center;'.
                                'text-decoration: none; font-weight: normal">'."\n".
                         'Поиск игроков'."\n".
                    '</DIV><BR><BR>'."\n".
                    '<DIV style="font-family: Liberation Serif, Times, sans-serif;'.
                                 'font-size: 12pt; color: black; text-align:left;'.
                                 'text-decoration: none; font-weight: normal">'."\n".
                         '<SPAN style="color: white">'.
                           'Параметры поиска'.(($error_ != '') ? ' ('.htmlspecialchars($error_).')' : '').'<BR>'.
                         '</SPAN>'."\n";

          $result_ .=    '<FORM action="MainPage.php?link_=find_mens&page_='.$page_.'" method="POST">'."\n".
                         '  <TABLE>'."\n".
                         '    <COL span="2">'."\n".
                         '    <TR>'."\n".
                         '      <TD>класс</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_class_">'."\n".
                         '           <OPTION '.(($params_['class_'] == '') ? 'selected' : '').' value="all">любой</OPTION>'."\n".
                         '           <OPTGROUP label="класс A">'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A8') ? 'selected' : '').' value="A8">A8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A7') ? 'selected' : '').' value="A7">A7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A6') ? 'selected' : '').' value="A6">A6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A5') ? 'selected' : '').' value="A5">A5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A4') ? 'selected' : '').' value="A4">A4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A3') ? 'selected' : '').' value="A3">A3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A1') ? 'selected' : '').' value="A2">A2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'A1') ? 'selected' : '').' value="A1">A1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="класс B">'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B8') ? 'selected' : '').' value="B8">B8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B7') ? 'selected' : '').' value="B7">B7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B6') ? 'selected' : '').' value="B6">B6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B5') ? 'selected' : '').' value="B5">B5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B4') ? 'selected' : '').' value="B4">B4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B3') ? 'selected' : '').' value="B3">B3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B2') ? 'selected' : '').' value="B2">B2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'B1') ? 'selected' : '').' value="B1">B1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="класс C">'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C8') ? 'selected' : '').' value="C8">C8</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C7') ? 'selected' : '').' value="C7">C7</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C6') ? 'selected' : '').' value="C6">C6</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C5') ? 'selected' : '').' value="C5">C5</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C4') ? 'selected' : '').' value="C4">C4</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C3') ? 'selected' : '').' value="C3">C3</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C2') ? 'selected' : '').' value="C2">C2</OPTION>'."\n".
                         '              <OPTION '.(($params_['class_'] == 'C1') ? 'selected' : '').' value="C1">C1</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".

                         '    <TR>'."\n".
                         '               <TD>рейтинг А</TD>'."\n".
                         '               <TD><INPUT type="text" id="find_rating_A_1" name="find_rating_A_1" value="'.htmlspecialchars($params_['rating_A_1']).'">'."\n".
                         '                        &nbsp;-&nbsp;'."\n".
                         '                        <INPUT type="text" id="find_rating_A_2" name="find_rating_A_2" value="'.htmlspecialchars($params_['rating_A_2']).'">'."\n".
                         '               </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '               <TD>рейтинг B</TD>'."\n".
                         '               <TD><INPUT type="text" id="find_rating_B_1" name="find_rating_B_1" value="'.htmlspecialchars($params_['rating_B_1']).'">'."\n".
                         '                       &nbsp;-&nbsp;'."\n".
                         '                       <INPUT type="text" id="find_rating_B_2" name="find_rating_B_2" value="'.htmlspecialchars($params_['rating_B_2']).'">'."\n".
                         '               </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '               <TD>рейтинг C</TD>'."\n".
                         '               <TD><INPUT type="text" id="find_rating_C_1" name="find_rating_C_1" value="'.htmlspecialchars($params_['rating_C_1']).'">'."\n".
                         '                        &nbsp;-&nbsp;'."\n".
                         '                        <INPUT type="text" id="find_rating_C_2" name="find_rating_C_2" value="'.htmlspecialchars($params_['rating_C_2']).'">'."\n".
                         '                </TD>'."\n".
                         '    </TR>'."\n".

                         '    <TR>'."\n".
                         '      <TD>активность на сайте</TD>'."\n".
                         '      <TD>'."\n".
                         '        <SELECT name="find_last_visit">'."\n".
                         '           <OPTION '.(($params_['last_visit'] == '') ? 'selected' : '').' value="all">не учитывать</OPTION>'."\n".
                         '           <OPTION '.(($params_['last_visit'] == 'on_line') ? 'selected' : '').' value="on_line">на сайте</OPTION>'."\n".
                         '           <OPTION '.(($params_['last_visit'] == 'today') ? 'selected' : '').' value="today">сегодня</OPTION>'."\n".
                         '           <OPTGROUP label="---------">'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'day_1') ? 'selected' : '').' value="day_1">назад 1 день</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'day_2') ? 'selected' : '').' value="day_2">назад 2 дня</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'day_3') ? 'selected' : '').' value="day_3">назад 3 дня</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'day_4') ? 'selected' : '').' value="day_4">назад 4 дня</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'day_5') ? 'selected' : '').' value="day_5">назад 5 дней</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'day_6') ? 'selected' : '').' value="day_6">назад 6 дней</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'day_7') ? 'selected' : '').' value="day_7">назад 7 дней</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="---------">'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'week_1') ? 'selected' : '').' value="week_1">не более недели назад</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'week_2') ? 'selected' : '').' value="week_2">не более 2 недель назад</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'week_3') ? 'selected' : '').' value="week_3">не более 3 недель назад</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'week_4') ? 'selected' : '').' value="week_4">не более 4 недель назад</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'week_5') ? 'selected' : '').' value="week_5">не более 5 недель назад</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="---------">'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'month_1') ? 'selected' : '').' value="month_1">не более 1 месяц назад</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'month_2') ? 'selected' : '').' value="month_2">не более 2 месяца назад</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'month_3') ? 'selected' : '').' value="month_3">не более 3 месяца назад</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'month_4') ? 'selected' : '').' value="month_4">не более 4 месяца назад</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'month_5') ? 'selected' : '').' value="month_5">не более 5 месяцев назад</OPTION>'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'month_6') ? 'selected' : '').' value="month_6">не более 6 месяцев назад</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '           <OPTGROUP label="---------">'."\n".
                         '              <OPTION '.(($params_['last_visit'] == 'larger_month_6') ? 'selected' : '').' value="larger_month_6">более 6 месяцев назад</OPTION>'."\n".
                         '           </OPTGROUP>'."\n".
                         '        </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".

                         '    <TR>'."\n".
                         '      <TD>город/нас. пункт</TD>'."\n".
                         '      <TD>'."\n".
                         '         <INPUT type="text" id="find_city_" name="find_city_" value="'.htmlspecialchars($params_['city_']).'">'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".

                         '    <TR>'."\n".
                         '      <TD>страна</TD>'."\n".
                         '      <TD>'."\n".
                         '          <INPUT type="text" id="find_country_" name="find_country_" value="'.htmlspecialchars($params_['country_']).'">'."\n".
                         '          &nbsp; '.
                         '          <SELECT name="find_rule_country_">'."\n".
                         '              <OPTION '.(($params_['rule_country_'] != '2') ? 'selected' : '').' value="1">совпадает</OPTION>'."\n".
                         '              <OPTION '.(($params_['rule_country_'] == '2') ? 'selected' : '').' value="2">не совпадает</OPTION>'."\n".
                         '           </SELECT>'."\n".
                         '       </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD>логин</TD>'."\n".
                         '      <TD>'."\n".
                         '         <INPUT type="text" id="find_login_" name="find_login_" value="'.htmlspecialchars($params_['login_']).'" autocomplete="off">'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n".
                         '    <TR><TD colspan="2">&nbsp;</TD></TR>'."\n".
                         '    <TR>'."\n".
                         '      <TD colspan="2">'."\n".
                         '         <SPAN style="color: white">'.
                         '            Сортировать'."\n".
                         '         </SPAN>&nbsp;'."\n".
                         '         <SELECT name="sort_field_">'."\n".
                         '            <OPTION '.(($params_['sort_field_'] == '1') ? 'selected' : '').' value="1">логин</OPTION>'."\n".
                         '            <OPTION '.(($params_['sort_field_'] == '2') ? 'selected' : '').' value="2">фамилия</OPTION>'."\n".
                         '            <OPTION '.(($params_['sort_field_'] == '3') ? 'selected' : '').' value="3">активность на сайте</OPTION>'."\n".
                         '            <OPTION '.(($params_['sort_field_'] == '4') ? 'selected' : '').' value="4">класс A</OPTION>'."\n".
                         '            <OPTION '.(($params_['sort_field_'] == '5') ? 'selected' : '').' value="5">класс B</OPTION>'."\n".
                         '            <OPTION '.(($params_['sort_field_'] == '6') ? 'selected' : '').' value="6">класс C</OPTION>'."\n".
                         '            <OPTION '.(($params_['sort_field_'] == '7') ? 'selected' : '').' value="7">рейтинг A</OPTION>'."\n".
                         '            <OPTION '.(($params_['sort_field_'] == '8') ? 'selected' : '').' value="8">рейтинг B</OPTION>'."\n".
                         '            <OPTION '.(($params_['sort_field_'] == '9') ? 'selected' : '').' value="9">рейтинг C</OPTION>'."\n".
                         '         </SELECT>'."\n".
                         '         <SELECT name="sort_order_">'."\n".
                         '            <OPTION '.(($params_['sort_order_'] == '1') ? 'selected' : '').' value="1">по возрастанию</OPTION>'."\n".
                         '            <OPTION '.(($params_['sort_order_'] == '2') ? 'selected' : '').' value="2">по убыванию</OPTION>'."\n".
                         '         </SELECT>'."\n".
                         '      </TD>'."\n".
                         '    </TR>'."\n";
          if (!CUsers_::Read_dhtml_())
               $result_ .='<TR>'."\n".
                          '   <TD colspan="2">'."\n".
                          '     Если установить флаг DHTML в разделе "настройки", то поле "логин участника" '.
                          '     будет содержать выпадающий список, который появлятся при нажатии клавиш.'.
                          '   </TD>'."\n".
                          '</TR>'."\n";
           else
               $result_ .='<SCRIPT type="text/javascript" src="scripts/json2.js"></SCRIPT>'."\n".
                          '<SCRIPT type="text/javascript" src="scripts/hints_.js"></SCRIPT>'."\n".
                          '<SCRIPT type="text/javascript" src="scripts/script_.js"></SCRIPT>'."\n".
                          '<SCRIPT type="text/javascript">'."\n".
                          '  var find_o_hints_;'.
                          '  window.onload =function(){'.
                          '       find_o_hints_ =new cl_hints_(document.getElementById("find_login_"),5,"ajax_tournaments_.php");'."\n".
                          '                           }'."\n".
                          '</SCRIPT>'."\n";
          $result_ .='    <TR><TD colspan="2">'."\n".
                     '          <INPUT type="submit" value="Поиск">'."\n".
                     '        </TD>'."\n".
                     '    </TR>'."\n".
                     '  </TABLE>'."\n".
                     '</FORM>';

          if (($error_ =='') && (count($c->records_) > 0))
               $result_ .=$c->out_records();
            else
               $result_ .='<DIV style="text-align: center">игроки не найдены</DIV>';
          $result_ .='</DIV>';
          return $result_;
        }#BodyFind

#$type_: 1 - сейчас на сайте, 2 - поиск
        public static function MakePage($type_){            unset($_SESSION[SESSION_LINK_ESC_ABOUT_GAMER]);
            $link_esc_about_gamer='';
            unset($_SESSION[SESSION_LINK_ESC_DOC]);
            $link_esc_doc='';

            $connect_ =false;
            $transact_ =false;
            try{
                if (!const_::$connect_)
                    if (const_::SetConnect_()) $connect_ =true; else throw new Exception('При соединении с базой данных произошла ошибка.');
                if (!const_::$isTransact_)
                    if (const_::StartTransaction_()) $transact_ =true; else throw new Exception('При соединении с базой данных произошла ошибка.');

                $classA_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],1);
                $classB_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],2);
                $classC_ =CUsers_::ReadClass_($_SESSION[SESSION_ID_],3);
                $ratingA_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],1);
                $ratingB_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],2);
                $ratingC_ =CUsers_::ReadRating_($_SESSION[SESSION_ID_],3);

                if (!isset($_GET['page']) || !ctype_digit($_GET['page']))
                   $p=1;
                 else $p =$_GET['page'];

                switch ($type_){
                    case 1 :
                        CListMens::$type_ =1;
                        $body_ =CMens_::BodyMensOnSite($p);
                        $link_esc_about_gamer =CListMens::get_link(CMens_::paramsForMensOnSite(),$p);
                        $link_esc_doc =CListMens::get_link(CMens_::paramsForMensOnSite(),$p);
                        break;
                    case 2 :
                        CListMens::$type_ =2;
                        $params_['class_'] =''; $err_ ='';
                        if (isset($_REQUEST['find_class_']))
                           if ((($_REQUEST['find_class_'] >= 'A1') && ($_REQUEST['find_class_'] <= 'A8')) ||
                               (($_REQUEST['find_class_'] >= 'B1') && ($_REQUEST['find_class_'] <= 'B8')) ||
                               (($_REQUEST['find_class_'] >= 'C1') && ($_REQUEST['find_class_'] <= 'C8')))
                             $params_['class_'] =$_REQUEST['find_class_'];
                        $params_['last_visit'] ='';
                        if (isset($_REQUEST['find_last_visit']))
                           if (($_REQUEST['find_last_visit'] == 'on_line') ||
                               ($_REQUEST['find_last_visit'] == 'today') ||
                               (($_REQUEST['find_last_visit'] >= 'day_1') && ($_REQUEST['find_last_visit'] <= 'day_8')) ||
                               (($_REQUEST['find_last_visit'] >= 'week_1') && ($_REQUEST['find_last_visit'] <= 'week_5')) ||
                               (($_REQUEST['find_last_visit'] >= 'month_1') && ($_REQUEST['find_last_visit'] <= 'month_6')) ||
                               ($_REQUEST['find_last_visit'] == 'larger_month_6'))
                              $params_['last_visit'] =$_REQUEST['find_last_visit'];
                        $params_['rating_A_1'] ='';
                        if (isset($_REQUEST['find_rating_A_1']) && ($_REQUEST['find_rating_A_1'] !='')){
                          if (!ctype_digit($_REQUEST['find_rating_A_1']))
                            $err_ ='Нижняя граница рейтинга A указана неверно';
                          $params_['rating_A_1'] =$_REQUEST['find_rating_A_1'];
                        }
                        $params_['rating_A_2'] ='';
                        if (isset($_REQUEST['find_rating_A_2']) && ($_REQUEST['find_rating_A_2'] !='')){
                          if (!ctype_digit($_REQUEST['find_rating_A_2'])){
                            if ($err_ != '') $err_ .=', ';
                            $err_ .='Верхняя граница рейтинга A указана неверно';
                          }
                          $params_['rating_A_2'] =$_REQUEST['find_rating_A_2'];
                        }
                        $params_['rating_B_1'] ='';
                        if (isset($_REQUEST['find_rating_B_1']) && ($_REQUEST['find_rating_B_1'] !='')){
                          if (!ctype_digit($_REQUEST['find_rating_B_1'])){
                            if ($err_ != '') $err_ .=', ';
                            $err_ .='Нижняя граница рейтинга B указана неверно';
                          }
                          $params_['rating_B_1'] =$_REQUEST['find_rating_B_1'];
                        }
                        $params_['rating_B_2'] ='';
                        if (isset($_REQUEST['find_rating_B_2']) && ($_REQUEST['find_rating_B_2'] !='')){
                          if (!ctype_digit($_REQUEST['find_rating_B_2'])){
                            if ($err_ != '') $err_ .=', ';
                            $err_ .='Верхняя граница рейтинга B указана неверно';
                          }
                          $params_['rating_B_2'] =$_REQUEST['find_rating_B_2'];
                        }
                        $params_['rating_C_1'] ='';
                        if (isset($_REQUEST['find_rating_C_1']) && ($_REQUEST['find_rating_C_1'] !='')){
                          if (!ctype_digit($_REQUEST['find_rating_C_1'])){
                            if ($err_ != '') $err_ .=', ';
                            $err_ .='Нижняя граница рейтинга C указана неверно';
                          }
                          $params_['rating_C_1'] =$_REQUEST['find_rating_C_1'];
                        }
                        $params_['rating_C_2'] ='';
                        if (isset($_REQUEST['find_rating_C_2']) && ($_REQUEST['find_rating_C_2'] !='')){
                          if (!ctype_digit($_REQUEST['find_rating_C_2'])){
                            if ($err_ != '') $err_ .=', ';
                            $err_ .='Верхняя граница рейтинга C указана неверно';
                          }
                          $params_['rating_C_2'] =$_REQUEST['find_rating_C_2'];
                        }
                        $params_['city_'] ='';
                        if (isset($_REQUEST['find_city_']))
                          $params_['city_'] =trim($_REQUEST['find_city_']);
                        $params_['country_'] ='';
                        if (isset($_REQUEST['find_country_']))
                          $params_['country_'] =trim($_REQUEST['find_country_']);
                        $params_['rule_country_'] =1;
                        if (isset($_REQUEST['find_rule_country_']) && ($_REQUEST['find_rule_country_'] ==2))
                          $params_['rule_country_'] =2;
                        $params_['login_'] ='';
                        if (isset($_REQUEST['find_login_']))
                          $params_['login_'] =trim($_REQUEST['find_login_']);
                        $params_['sort_field_'] =1;
                        if (isset($_REQUEST['sort_field_']) && ctype_digit($_REQUEST['sort_field_']))
                          if (($_REQUEST['sort_field_'] >= 1) && ($_REQUEST['sort_field_'] <= 9))
                             $params_['sort_field_'] =$_REQUEST['sort_field_'];
                        $params_['sort_order_'] =1;
                        if (isset($_REQUEST['sort_order_']) && ($_REQUEST['sort_order_'] ==2))
                          $params_['sort_order_'] =2;
                        if ($err_ =='')
                          $body_ =CMens_::BodyFind($params_,$p,'');
                         else
                          $body_ =CMens_::BodyFind($params_,1,$err_);
                        $link_esc_about_gamer =CListMens::get_link($params_,$p);
                        $link_esc_doc =CListMens::get_link($params_,$p);
                        break;
                }#switch

                if ($transact_)
                    if (const_::Commit_()) $transact_ =false; else throw new Exception('При завершении транзакции произошла ошибка.');
                if ($connect_)const_::Disconnect_();

                CPage_::$header_ ='<DIV id="text_login_">'.
                                  '  логин: '.$_SESSION[SESSION_LOGIN_].'<BR>'.
                                  '  класс: A'.$classA_.'/B'.$classB_.'/C'.$classC_.
                                  '  рейтинг: A'.$ratingA_.'/B'.$ratingB_.'/C'.$ratingC_.'<BR>'.
                                  '</DIV>'   .
                                  '<DIV id="text_header_">'.
                                  '  Игроки'.
                                  '</DIV>';
                CMens_::MakeMenuMainPage($type_);
                CPage_::$body_ =$body_;
                CPage_::MakePage();
                if ($link_esc_about_gamer !='')
                   $_SESSION[SESSION_LINK_ESC_ABOUT_GAMER]=$link_esc_about_gamer;
                if ($link_esc_doc !='')
                   $_SESSION[SESSION_LINK_ESC_DOC]=$link_esc_doc;
            }catch(Exception $e){
				if ($transact_) const_::Rollback_();
				if ($connect_) const_::Disconnect_();
                CPage_::$text_error_ =$e->getMessage();
				CPage_::PageErr();
            }#try
        }#MakePage
    }#CMens_


?>
