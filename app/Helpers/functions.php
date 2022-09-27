<?php

function memory(){
    return round(memory_get_usage()/1024/1024, 2) . 'MB';
}

function parentReload() {
	echo <<<HTML
	<script>
		window.parent.location.reload();
	</script>
HTML;
	exit;

}

function parentRedirect($url) {
    echo <<<HTML
	<script>
		window.parent.location = "$url";
	</script>
HTML;
    exit;

}

function parentReloadTab() {
    echo <<<HTML
	<script>
		window.parent.reloadTab();
	</script>
HTML;
    exit;

}

function parentReloadSelect() {
    echo <<<HTML
	<script>
		window.parent.reloadSelect();
	</script>
HTML;
    exit;

}

function reload() {
    echo <<<HTML
	<script>
		window.location.reload();
	</script>
HTML;
    exit;

}

function frameError($msg){
    echo <<<HTML
	<script>
        window.parent.flashHeaderMessage('{$msg}', 'danger');
		window.parent.jQuery.fancybox.close();
	</script>
HTML;
    exit;
}


function getDateTime() {
	return date("Y-m-d H:i:s");
}

function getDateTimeRu() {
	return date("d.m.Y H:i:s");
}

function getDateFormat($date) {
	$YY = substr($date, - 4);
	$DD = substr($date, 0, 2);
	$MM = substr($date, 3, 2);

	return $YY . "-" . $MM . "-" . $DD;
}

function getDateFormatEn($date)
{
    if($date && strlen($date) > 9 && $date!='0000-00-00'){
        return date('Y-m-d', strtotime($date));
    }
    return null;
}

function getDateFormatRu($date,$is_null = 0) {
    if(strlen($date) > 9 && $date!='0000-00-00'){
        return date('d.m.Y', strtotime($date));
    }
    return ($is_null == 0) ? null : '';
}

function setDateTimeFormat($date) {
	if (strlen($date) > 0 && $date != '1970-01-01 00:00:00' && $date != '1970-01-01' && $date != '01.01.1970') {
		return date('Y-m-d H:i:s', strtotime($date));
	} else {
		return null;
	}
}

function setDateTimeFormatRu($date, $type = 0) {
	if (strlen($date) > 0 && $date != '1970-01-01 00:00:00' && $date != '1970-01-01' && $date != '01.01.1970' && $date != '0000-00-00') {
	    if($type == 0) return date('d.m.Y H:i', strtotime($date));
	    else return date('d.m.Y', strtotime($date));
    }else {
		return '';
	}
}

function getDateFormatTimeRu($date) {
    if (strlen($date) > 0 && $date != '1970-01-01 00:00:00' && $date != '1970-01-01' && $date != '01.01.1970' && $date != '0000-00-00') {
        return date('H:i', strtotime($date));
    }else {
        return '';
    }
}

function getFloatFormat($str) {
	$str = str_replace(' ', "", $str);
	$str = str_replace(',', ".", $str);

	return floatval($str);
}

function getFloatFormatWin($str) {
    $str = str_replace(' ', "", $str);
    $str = str_replace('.', ",", $str);

    return $str;
}


function titleFloatFormat($num, $is_xls = 0, $is_null = 0, $decimals = 2)
{
    if (strlen($num) == 0) {
        if($is_null == 1){
            return '';
        }
        $num = 0;
    }

    if($is_xls == 1) return number_format($num, $decimals, '.', '');

    return number_format($num, $decimals, ',', ' ');
}


function titleNumberFormat($num, $is_null = 0) {
    if (strlen($num) == 0) {
        if($is_null == 1){
            return '';
        }
        $num = 0;
    }

    return number_format($num, 0, '', ' ');
}

function getPhoneFormat($str) {

    $str = str_replace(' ', "", $str);
    $str = str_replace('(', "", $str);
    $str = str_replace(')', "", $str);
    $str = str_replace('-', "", $str);
    $str = str_replace(';', "", $str);
    $str = str_replace('+', "", $str);

    return $str;
}

function getDocumentNumberFormat($str) {

    $str = str_replace(' ', "", $str);
    $str = str_replace('-', "", $str);
    $str = str_replace('_', "", $str);

    return $str;
}

function getStatusColor($order_state) {
	$res = '';
	switch ($order_state) {
		case 0:
			$res = 'white';
			break;
		case 7:
		case 8:
			$res = 'purple';
			break;
		case 2:
		case 5:
			$res = 'yellow';
			break;
		case 3:
		case 4:
		case 6:
			$res = 'green';
			break;
	}

	return $res;
}

function getMonthById($id, $case = false) {
	$months = getRuMonthes($case);
	return isset($months[$id]) ? $months[$id] : '';
}

function getRuMonthes($case = false){

    switch($case){

        case "rod":
            return [
                1  => 'Января',
                2  => 'Февраля',
                3  => 'Марта',
                4  => 'Апреля',
                5  => 'Мая',
                6  => 'Июня',
                7  => 'Июля',
                8  => 'Августа',
                9  => 'Сентября',
                10 => 'Октября',
                11 => 'Ноября',
                12 => 'Декабря',
            ];

            break;

        default :
            return [
                1  => 'Январь',
                2  => 'Февраль',
                3  => 'Март',
                4  => 'Апрель',
                5  => 'Май',
                6  => 'Июнь',
                7  => 'Июль',
                8  => 'Август',
                9  => 'Сентябрь',
                10 => 'Октябрь',
                11 => 'Ноябрь',
                12 => 'Декабрь',
            ];

    }

}

function getYearsRange($from, $to){
    $years = range(date('Y')+$from, date('Y')+$to);
    $years = array_combine($years, $years);
    return $years;
}

function getYearsKasko(){

    $years = [];

    for($i = date('Y');$i>=(date('Y')-7); $i--){
        $years[] = $i;
    }
    return $years;
}



function getTypeCountTransportationsClientsPlanning($type_count_transportations) {
    $type_count_transportations_title = '';
    switch ($type_count_transportations) {
        case 0:
            $type_count_transportations_title = "день";
            break;
        case 1:
            $type_count_transportations_title = "месяц";
            break;
        case 2:
            $type_count_transportations_title = "год";
            break;
    }
    return $type_count_transportations_title;
}

function getStatusPaymentTypeClientsPlanning($status_payment_type) {
    $status_payment_type_title = '';
    switch ($status_payment_type) {
        case \App\Models\Subject\Type::STATUS_SENDER_PAYMENT_TUPE_DEFAULT:
            $status_payment_type_title = trans('clients/clients.edit.STATUS_SENDER_PAYMENT_TUPE_DEFAULT');
            break;
        case \App\Models\Subject\Type::STATUS_SENDER_PAYMENT_TUPE_CAUTION:
            $status_payment_type_title = trans('clients/clients.edit.STATUS_SENDER_PAYMENT_TUPE_CAUTION');
            break;
        case \App\Models\Subject\Type::STATUS_SENDER_PAYMENT_TUPE_TRUST:
            $status_payment_type_title = trans('clients/clients.edit.STATUS_SENDER_PAYMENT_TUPE_TRUST');
            break;
    }
    return $status_payment_type_title;
}

function getPlanningTimeHous($km){

    $hour = ceil($km / 70);
    $hour_rest = ceil($hour / 10)*8;
    $hour = ($hour+$hour_rest);

    return $hour;
}

function getHTMLSecurityService($obj, $type){

    $myHTML = '';

    $myHTML = Form::select('status_security_service', \App\Models\Security\Security::STATUS_SECURITY_SERVICE, old('status_security_service'),  ['class' => 'form-control status_security_service']);


    return ($myHTML);
}



function getTotalSumToPrice($price, $percent) {

    $percent = getFloatFormat($percent);
    $price = getFloatFormat($price);
    $sum = ($price / 100) * $percent;
    return getFloatFormat(getPriceFormat($sum));
}

function getPriceToTotalSum($amount, $price) {

    return getFloatFormat($price)/(getFloatFormat($amount)/100);
}


function getPriceFormat($price){
    return number_format($price, 2, ',', ' ');
}


function createPageNavigator($total, $pageNumber, $elementsPerPage, $path = '')
    {

        $path = preg_replace('/(|&|\?)page=\d+/', '', $path);

        
        if(substr($path, -1) == '/') {
            $path .= '?';
        } else {
            $path .= '&';
        }

        $out = '';
        if (!$pageNumber) {
            $pageNumber = 1;
        }

        if ($total > $elementsPerPage) { //if all elements couldn't be placed on the page
            //[prev]
            if ($pageNumber > 1) {
                $out .= '<a  href="' . ($path . 'page=' . ($pageNumber - 1)) . '">&lt;&lt; пред.</a> &nbsp;&nbsp;';
            }

            //digital links
            $k = $pageNumber - 1;// $elementsPerPage;

            //not more than 4 links to the left
            $min = $k - 5;
            if ($min < 0) {
                $min = 0;
            } else {
                if ($min >= 1) { //link on the 1st page
                    $out .= '<a  href="' . ($path . 'page=1') . '">1</a> &nbsp;&nbsp;';
                    if ($min != 1) {
                        $out .= '... &nbsp;';
                    }
                }
            }

            for ($i = $min; $i < $k; $i++) {
                $out .= '<a  href="' . ($path . 'page=' . ($i + 1)) . '">' . ($i + 1) . '</a> &nbsp;&nbsp;';
            }

            $out .= '<span class="active">' . ($k + 1) . '</span> &nbsp;&nbsp;';

            //not more than 5 links to the right
            $min = $k + 6;
            if ($min > ceil($total / $elementsPerPage)) {
                $min = ceil($total / $elementsPerPage);
            }

            for ($i = $k + 1; $i < $min; $i++) {
                $out .= '<a  href="' . ($path . 'page=' . ($i + 1)) . '">' . ($i + 1) . '</a> &nbsp;&nbsp;';
            }

            if ($min * $elementsPerPage < $total) { //the last link
                if ($min * $elementsPerPage < $total - $elementsPerPage) {
                    $out .= ' ... &nbsp;&nbsp;';
                }

                if (!($total % $elementsPerPage == 0)) {
                    $out .= '<a  href="' . ($path . 'page=' . (floor($total / $elementsPerPage) + 1)) . '">' . (floor($total / $elementsPerPage) + 1) . '</a> &nbsp;&nbsp;';
                } else { //$total is divided by $elementsPerPage
                    $out .= '<a  href="' . ($path . 'page=' . (floor($total / $elementsPerPage))) . '">' . (floor($total / $elementsPerPage)) . '</a> &nbsp;&nbsp;';
                }
                    
            }
            
            //[next]
            if ($pageNumber < $total / $elementsPerPage) {
                $out .= '<a href="' . ($path . 'page=' . ($pageNumber + 1)) . '">след. &gt;&gt;</a> ';
            }
        }

        return '<span class="paginator">' . $out . '</span>';
    }


/**
 * Сумма прописью
 * @author runcore
 * @url rche.ru
 */
function num2str($inn, $stripkop=false) {
    $nol = 'ноль';
    $str[100]= array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот', 'восемьсот','девятьсот');
    $str[11] = array('','десять','одиннадцать','двенадцать','тринадцать', 'четырнадцать','пятнадцать','шестнадцать','семнадцать', 'восемнадцать','девятнадцать','двадцать');
    $str[10] = array('','десять','двадцать','тридцать','сорок','пятьдесят', 'шестьдесят','семьдесят','восемьдесят','девяносто');
    $sex = array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),// m
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять') // f
    );
    $forms = array(
        array('копейка', 'копейки', 'копеек', 1), // 10^-2
        array('рубль', 'рубля', 'рублей',  0), // 10^ 0
        array('тысяча', 'тысячи', 'тысяч', 1), // 10^ 3
        array('миллион', 'миллиона', 'миллионов',  0), // 10^ 6
        array('миллиард', 'миллиарда', 'миллиардов',  0), // 10^ 9
        array('триллион', 'триллиона', 'триллионов',  0), // 10^12
    );
    $out = $tmp = array();
    // Поехали!
    $tmp = explode('.', str_replace(',','.', $inn));
    $rub = number_format((float)$tmp[0], 0,'','-');
    if ($rub== 0) $out[] = $nol;
    // нормализация копеек
    $kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0,2) : '00';
    $segments = explode('-', $rub);
    $offset = sizeof($segments);
    if ((int)$rub== 0) { // если 0 рублей
        $o[] = $nol;
        $o[] = morph( 0, $forms[1][ 0],$forms[1][1],$forms[1][2]);
    }
    else {
        foreach ($segments as $k=>$lev) {
            $sexi= (int) $forms[$offset][3]; // определяем род
            $ri = (int) $lev; // текущий сегмент
            if ($ri== 0 && $offset>1) {// если сегмент==0 & не последний уровень(там Units)
                $offset--;
                continue;
            }
            // нормализация
            $ri = str_pad($ri, 3, '0', STR_PAD_LEFT);
            // получаем циферки для анализа
            $r1 = (int)substr($ri, 0,1); //первая цифра
            $r2 = (int)substr($ri,1,1); //вторая
            $r3 = (int)substr($ri,2,1); //третья
            $r22= (int)$r2.$r3; //вторая и третья
            // разгребаем порядки
            if ($ri>99) $o[] = $str[100][$r1]; // Сотни
            if ($r22>20) {// >20
                $o[] = $str[10][$r2];
                $o[] = $sex[ $sexi ][$r3];
            }
            else { // <=20
                if ($r22>9) $o[] = $str[11][$r22-9]; // 10-20
                elseif($r22> 0) $o[] = $sex[ $sexi ][$r3]; // 1-9
            }
            // Рубли
            $o[] = morph($ri, $forms[$offset][ 0],$forms[$offset][1],$forms[$offset][2]);
            $offset--;
        }
    }
    // Копейки
    if (!$stripkop) {
        $o[] = $kop;
        $o[] = morph($kop,$forms[ 0][ 0],$forms[ 0][1],$forms[ 0][2]);
    }
    return preg_replace("/\s{2,}/",' ',implode(' ',$o));
}

/**
 * Склоняем словоформу
 */
function morph($n, $f1, $f2, $f5) {
    $n = abs($n) % 100;
    $n1= $n % 10;
    if ($n>10 && $n<20) return $f5;
    if ($n1>1 && $n1<5) return $f2;
    if ($n1==1) return $f1;
    return $f5;
}

function breadcrumb($array, $ul_class = 'class="breadcrumb"'){
    $res = '<ul '.$ul_class.'>';
    $url = "";
    foreach($array as $item){
        if(isset($item['label'])){
            $res .= '<li>';
            if(isset($item['url'])){
                $url .= '/'. $item['url'];
                $res .= '<a href="'.url($url).'">'.$item['label'].'</a>';
            }else{
                $res .= $item['label'];
            }
            $res .= '</li>';
        }
    }
    $res .= '</ul>';
    return $res;
}


function translitClass($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return strtolower(preg_replace('/[^A-Za-z]+/', '', str_replace($rus, $lat, $str)));
}

function declension($str, $case){

}

function dynamicColors() {
        $r = rand(1, 255);
        $g = rand(1, 255);
        $b = rand(1, 255);
        return "rgba(" . $r . "," . $g . "," . $b . ", 0.5)";
}


function getLaravelSql($query)
{
    return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
        return is_numeric($binding) ? $binding : "'{$binding}'";
    })->toArray());
}

function getNumbersBsoFormat($number, $count = 9, $v = '0'){
    $temp = '';
    if(strlen($number) < $count){
        for($i=0; $i < ($count - strlen($number)); $i++){
            $temp .= $v;
        }
    }
    return $temp.$number;
}

function urlClient($str, $is_pull = 1)
{
    return ($is_pull == 1)?env('CLIENT_URL').$str : $str;
}

function getContractMd5Token($contract)
{
    return md5("{$contract->agent_id}-{$contract->product_id}-{$contract->created_at}-{$contract->id}".uniqid());
}

function getBsoStatusVolor($status)
{
    $result = 'bg-white';

    if($status == 2) $result = 'bg-green';
    if($status == 3) $result = 'bg-yellow';
    if($status == 4) $result = 'bg-red';

    return $result;
}


function getContractStatusVolor($contract)
{
    $result = 'bg-white';

    //if($contract->statys_id == 4) $result = 'bg-green';
    if($contract->statys_id == -1) $result = 'bg-red';

    return $result;
}


function getSqlSumRow($query, $coloms, $is_xls = 0)
{
    $sql = clone $query;
    $res = $sql->select(DB::raw("SUM($coloms) as result"))->first();

    return titleFloatFormat($res->result, $is_xls);
}


function countMinutesBetweenDates($d2)
{
    $d1_ts = strtotime(date(("Y-m-d H:i:s")));
    $d2_ts = strtotime($d2);

    $seconds = abs($d1_ts - $d2_ts);

    return (int)floor($seconds / 60);
}


function getContractStatusColor($contract)
{

    $result = 'bg-white';

    if($contract->statys_id == 2){
        if($contract->calculation && $contract->calculation->matching)
        {
            if($contract->calculation->matching->status_id == 4){
                $result = 'bg-green';
            }

            if($contract->calculation->matching->status_id == 5){
                $result = 'bg-red';
            }
        }
    }




    return $result;
}


function UUID_V4() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

function dt_diff($d1,$d2){
    $d1 = date_create($d1);
    $d2 = date_create($d2);
    if ($d1>$d2) {$td=$d1;$d1=$d2;$d2=$td;}
    $yr= date_format ($d2,'Y') - date_format ($d1,'Y');
    $mr = date_format ($d2,'m') - date_format ($d1,'m');
    $dr= date_format ($d2,'d') - date_format ($d1,'d');
    $dr = ($dr<0) ?-1 :0;
    $r= $yr*12 +$mr+$dr;
    return $r;
}


function getAddressDefault($type_id){
    $address = new \App\Models\Clients\GeneralSubjectsAddress();
    $address->general_subject_id = null;
    $address->type_id = $type_id;
    return $address;
}


function getDocumentsDefault($type_id){
    $document = new \App\Models\Clients\GeneralSubjectsDocuments();
    $document->general_subject_id = null;
    $document->type_id = $type_id;
    return $document;
}


function countDayToDates($date_s, $date_e)
{
    return $difference = intval(abs(
            strtotime($date_s) - strtotime($date_e)
        )) / (3600 * 24);
}

function setPhoneNumberFormat($phone, $format)// d = цифра
{
    $str = '';
    $set_pos_phone = strlen($phone) - 1;
    for ($i = strlen($format) - 1; $i >= 0; $i--) {
        if ($format[$i] == 'd') {
            $str = (int)$phone[$set_pos_phone] . $str;
            $set_pos_phone--;

        } else $str = $format[$i] . $str;
    }


    return $str;
}


function getSqlPage($page, $counts, $count_all)
{
    $builder = '';
    $page = (int)$page;
    if($page == 0) $page = 1;
    $state = 0;


    //$page=0 - С какой начинать
    //$counts=10 - По сколько
    //$count_all=852 - Всего
    $page_count = $counts;
    $max_row = $count_all;
    $page_max = (int)ceil($max_row / $page_count);

    $next = $page+1;
    if($next > $page_max) {
        $next = $page_max;
        $state = 1;
    }

    $view_row = $page_count * $page;

    if ($page_count != -1) {
        $builder = "limit {$page_count} OFFSET ".($page_count*($page-1));
    }

    if ($count_all < $page_count || $page_count == -1) {
        $view_row = $max_row;
    }

    $progressbar = (int)(($page*100)/$page_max);

    return (object)[
        'builder' => $builder,
        'page_max' => $page_max,
        'page' => $page,
        'max_row' => $max_row,
        'view_row' => $view_row,
        'progressbar' => $progressbar,
        'state' => $state,
        'next' => $next,
    ];
}



function oldSetPhoneNumberFormat($phone, $format)// d = цифра
{
    if(strlen($phone) <= 0) return '';

    $str = '';
    $set_pos_phone = strlen($phone) - 1;

    for ($i = strlen($format) - 1; $i >= 0; $i--) {
        if ($format[$i] == 'd') {
            $str = (int)$phone[$set_pos_phone] . $str;
            $set_pos_phone--;

        } else $str = $format[$i] . $str;

    }


    return $str;
}

function parsePhoneNumber($phone)
{
    $phone = str_replace("+", '', $phone);
    $phone = str_replace("(", '', $phone);
    $phone = str_replace(")", '', $phone);
    $phone = str_replace("-", '', $phone);
    $phone = str_replace(" ", '', $phone);

    return $phone;
}

function getDataIsset($data, $group, $param){
    if(isset($data) && isset($data[$group]) && isset($data[$group][$param])) return $data[$group][$param];
    return '';
}

function getUnixTimestampSQL($column){
    if(env('DB_CONNECTION') == "pgsql"){
        return "extract(epoch FROM {$column})";
    }else{
        return "UNIX_TIMESTAMP({$column})";
    }
}
function getOrderByIfSQL($case, $if_true, $if_false, $order = ''){
    if(env('DB_CONNECTION') == "pgsql"){
        return "CASE WHEN({$case}) THEN {$if_true} ELSE {$if_false} END {$order}";
    }else{
        return "if({$case}, {$if_true}, {$if_false}) {$order}";
    }
}


function getIsArray($arr, $key){
    if(isset($arr[$key])) return $arr[$key];
    return null;
}

function getSexClient($fio){
    $s = mb_substr($fio, -1);
    $s = mb_strtolower($s);
    if($s == 'а') return 1;
    return 0;
}


function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

