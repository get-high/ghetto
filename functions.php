<?
	
	
	
	
	
	
	
	
// ОТКЛЮЧАЕМ ОШИБКИ
error_reporting(0);
// Подключаем файлы
require("config.php");
require("language.php");
require("db.php"); ///

// Текущее время
define('TIMENOW', time());
// Текущий год
$copyrightyear = date('Y', time());
// Текущий месяц
$nowmonth = date('m', time());
// Текущий день
$nowday = date('d', time());

// Адрес скрипта, используется для логина админа и редактора
$scripturl = ereg_replace("^/", "", $_SERVER['REQUEST_URI']);

// Создаём класс $db_sql и коннектимся к базе
$db_sql = new db_sql;
$db_sql->database=$dbname;
$db_sql->server=$hostname;
$db_sql->user=$dbusername;
$db_sql->password=$dbpassword;
$db_sql->myconnect();
mysql_query("SET NAMES cp1251");


function rus_date() {
// Перевод
 $translate = array(
 "am" => "дп",
 "pm" => "пп",
 "AM" => "ДП",
 "PM" => "ПП",
 "Monday" => "Понедельник",
 "Mon" => "Пн",
 "Tuesday" => "Вторник",
 "Tue" => "Вт",
 "Wednesday" => "Среда",
 "Wed" => "Ср",
 "Thursday" => "Четверг",
 "Thu" => "Чт",
 "Friday" => "Пятница",
 "Fri" => "Пт",
 "Saturday" => "Суббота",
 "Sat" => "Сб",
 "Sunday" => "Воскресенье",
 "Sun" => "Вс",
 "January" => "января",
 "Jan" => "Янв",
 "February" => "февраля",
 "Feb" => "Фев",
 "March" => "марта",
 "Mar" => "Мар",
 "April" => "апреля",
 "Apr" => "Апр",
 "May" => "мая",
 "May" => "Мая",
 "June" => "июня",
 "Jun" => "Июн",
 "July" => "июля",
 "Jul" => "Июл",
 "August" => "августа",
 "Aug" => "Авг",
 "September" => "сентября",
 "Sep" => "Сен",
 "October" => "октября",
 "Oct" => "Окт",
 "November" => "ноября",
 "Nov" => "Ноя",
 "December" => "декабря",
 "Dec" => "Дек",
 "st" => "ое",
 "nd" => "ое",
 "rd" => "е",
 "th" => "ое"
 );
 // если передали дату, то переводим ее
 if (func_num_args() > 1) {
 $timestamp = func_get_arg(1);
 return strtr(date(func_get_arg(0), $timestamp), $translate);
 } else {
// иначе текущую дату
 return strtr(date(func_get_arg(0)), $translate);
 }
 }


function agetostr($age)
{
$str="";
$num=$age>100 ? substr($age, -2) : $age;
if($num>=5&&$num<=14) $str = "лет";
else
{
$num=substr($age, -1);
if($num==0||($num>=5&&$num<=9)) $str='лет';
if($num==1) $str='год';
if($num>=2&&$num<=4) $str='года';
}
return $age." ".$str;
}


// Функция вывода шаблонов
function template($name, $browsertitle = false, $heading = false)
{
	global $db_sql, $prefix;
	// Берём из базы шаблон
	$template = $db_sql->sql_query("SELECT * FROM " . $prefix . "templates WHERE name = '$name'");
	$template = $db_sql->fetch_array($template);
	$str = addslashes($template["template"]);
	$str = str_replace('$browsertitle', $browsertitle, $str);
	$str = str_replace('$heading', $heading, $str);
	$str = str_replace("\'", "'", $str);
	return $str;
}


function wrapped_string($text)
{
	return preg_replace('#([^\s&<>"\\-\[\]]|&[\#a-z0-9]{1,7};){30}#i', '$0  ', $text);
}

// Функция создания превьюшки
function resizeimg($filename, $smallimage, $w, $h)
{
	$ratio = $w/$h;
	$size_img = getimagesize($filename);
	$src_ratio=$size_img[0]/$size_img[1];
	
    if ($ratio<$src_ratio)
    {
		$h = $w/$src_ratio;
	}
	else
	{
		$w = $h*$src_ratio;
    }
	
    $dest_img = imagecreatetruecolor($w, $h);
    $white = imagecolorallocate($dest_img, 255, 255, 255);
    if ($size_img[2]==2)  $src_img = imagecreatefromjpeg($filename);
    else if ($size_img[2]==1) $src_img = imagecreatefromgif($filename);
    else if ($size_img[2]==3) $src_img = imagecreatefrompng($filename);
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $w, $h, $size_img[0], $size_img[1]);
    if ($size_img[2]==2)  imagejpeg($dest_img, $smallimage);
    else if ($size_img[2]==1) imagegif($dest_img, $smallimage);
    else if ($size_img[2]==3) imagepng($dest_img, $smallimage);
    imagedestroy($dest_img);
    imagedestroy($src_img);
    return true;
}

function resizeimgw($filename, $smallimage, $w)
{
	$size_img = getimagesize($filename);
	$src_ratio=$size_img[0]/$size_img[1];
	
	$h = $w/$src_ratio;
	
    $dest_img = imagecreatetruecolor($w, $h);
    $white = imagecolorallocate($dest_img, 255, 255, 255);
    if ($size_img[2]==2)  $src_img = imagecreatefromjpeg($filename);
    else if ($size_img[2]==1) $src_img = imagecreatefromgif($filename);
    else if ($size_img[2]==3) $src_img = imagecreatefrompng($filename);
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $w, $h, $size_img[0], $size_img[1]);
    if ($size_img[2]==2)  imagejpeg($dest_img, $smallimage);
    else if ($size_img[2]==1) imagegif($dest_img, $smallimage);
    else if ($size_img[2]==3) imagepng($dest_img, $smallimage);
    imagedestroy($dest_img);
    imagedestroy($src_img);
    return true;
}

// Функция создания превьюшки
function makepreview($filename, $smallimage, $w, $h)
{
	$ratio = $w/$h;
	$size_img = getimagesize($filename);
	$src_ratio=$size_img[0]/$size_img[1];
	$width = $size_img[0];
	$height = $size_img[1];
	$xratio = $w / $width;
	$yratio = $h / $height;
	$k = min($w/$width, $h/$height);
	
	if (($width <= $w) OR ($height <= $h))
	{
		$newwidth = $width * $k;
		$newheight = $height * $k;
     
	}
	else if (($xratio * $height) < $h)
	{
    	$newheight = ceil($xratio * $height);
    	$newwidth = $w;
	}
	else
	{
    	 $newwidth = ceil($yratio * $width);
    	 $newheight = $h;
	}
	
	
    $dest_img = imagecreatetruecolor($w, $h);
    $white = imagecolorallocate($dest_img, 255, 255, 255);
    imagefill($dest_img, 0, 0, $white);
    if ($size_img[2]==2)  $src_img = imagecreatefromjpeg($filename);
    else if ($size_img[2]==1) $src_img = imagecreatefromgif($filename);
    else if ($size_img[2]==3) $src_img = imagecreatefrompng($filename);
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    if ($size_img[2]==2)  imagejpeg($dest_img, $smallimage);
    else if ($size_img[2]==1) imagegif($dest_img, $smallimage);
    else if ($size_img[2]==3) imagepng($dest_img, $smallimage);
    imagedestroy($dest_img);
    imagedestroy($src_img);
    return true;
}


// Функция ошибки
function stop_message($text)
{
	global $sitename, $siteaddress;
	eval('print("' . template('stop_message') . '");');
}


// Функция подтверждения действия
function confirm($text, $name, $yes_url)
{
	global $sitename, $siteaddress;
	eval('print("' . template('confirm_message') . '");');
}


// Функция редиректа
function redirect($url, $text)
{
	global $sitename, $siteaddress;
	eval('print("' . template('redirect_message') . '");');
}


// Функция обратная функции htmlspecialchars
function unhtmlspecialchars($text)
{
	return str_replace(array('&lt;', '&gt;', '&quot;', '&amp;'), array('<', '>', '"', '&'), $text);
}


// Проверка заполнения формы
function filled_out($form_vars)
{
	foreach ($form_vars as $key => $value)
	{
		if (!isset($key) || ($value == ""))
		{
			return false;
		}
	}
	return true;
}


// Проверка валидности мыла
function validemail($address)
{
	if (ereg("^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $address))
	{
		return true;
	}
	else
	{
		return false;
	}
}


// Функция удаления каталога
function remove_dir($dir)
{
	if(is_dir($dir))
	{
		$dir = (substr($dir, -1) != "/")? $dir."/":$dir;
		$openDir = opendir($dir);
		while($file = readdir($openDir))
		{
			if(!in_array($file, array(".", "..")))
			{
				if(!is_dir($dir.$file))
					@unlink($dir.$file);
				else
					remove_dir($dir.$file);
			}
		}
		closedir($openDir);
		@rmdir($dir);
	}
}


function debug($what, $is_die = true)
{
    print '<pre>';
        print_r($what);
    print '</pre>';
    
    if ($is_die) die();
}


function debugQuery($sql, $is_die = true)
{
    $query = mysql_query($sql) or die(mysql_error());
        
    if ($num_rows = mysql_num_rows($query)) 
    {
        print 'NumRows: '. $num_rows .'<br>';
        if ($num_rows == 1) {
            $result = mysql_fetch_assoc($query);
            debug($result, false);
            
            print '<hr/>SQL:';
            debug($sql, $is_die);
        }
        else {
            $results_list = array();
            while ($result = mysql_fetch_assoc($query)) {
                $results_list[] = $result;
            }
            debug($results_list, false);
            
            print '<hr/>SQL:';
            debug($sql, $is_die);
        }
    }
    else 
    {
        print 'NumRows: '. $num_rows;
        
            print '<hr/>SQL:';
            debug($sql, $is_die);
    }
}


// Имена исполнителей для поиска
function searchartisname($malb,$falb)
{
	global $db_sql, $prefixf;
	// Разделяем ID исполнителей
	$mal = explode(",", $malb);
	$fal = explode(",", $falb);
	foreach ($mal as $mra)
	{
		if (!empty($mra))
		{
			$allarts = $db_sql->sql_query("SELECT title, identifier FROM " . $prefixf . "artist WHERE id = $mra");
			$numrowz = $db_sql->fetch_array($allarts);
			$artname = addslashes($numrowz['title']);
			$artistname .= $artname . " ";
		}
	}
	foreach ($fal as $fra)
	{
		if (!empty($fra))
		{
			$allarts = $db_sql->sql_query("SELECT title, identifier FROM " . $prefixf . "artist WHERE id = $fra");
			$numrowz = $db_sql->fetch_array($allarts);
			$artname = addslashes($numrowz['title']);
			$artistname .= $artname . " ";
		}
	}
	$artistname = substr($artistname,0,strlen($artistname)-1);
	return $artistname;
}


// ВЫВОД БАННЕРОВ
function banners()
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	// Берём из базы все банеры
	$artarray = $db_sql->sql_query("SELECT title, banner FROM " . $prefix . "banners");
	$numrows = $db_sql->num_rows($artarray);
	// Заполняем массив
	for ($i=0; $i!=$numrows; $i++)
	{
		$arts[$i] = $db_sql->fetch_array($artarray);
	}
	// Перемешиваем
	shuffle($arts);
	foreach ($arts as $bann)
	{
		$banner = $bann['banner'];
		$btitle = htmlspecialchars($bann['title']);
		eval('$artistlinks .= "' . template('block_banners') . '";');
	}
	return $artistlinks;
}


// ВЫВОД АНОНСОВ
function announce()
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	$artarray = $db_sql->sql_query("SELECT id, title, url FROM " . $prefix . "announce ORDER BY id DESC");
	while ($bann = $db_sql->fetch_array($artarray))
	{
		$anid = $bann['id'];
		$url = $bann['url'];
		$btitle = $bann['title'];
		eval('$ali .= "' . template('announce_li') . '";');
	}
	eval('$artistlinks .= "' . template('announce') . '";');
	return $artistlinks;
}


// ФУНКЦИЯ ВЫВОДА ДНЕЙ РОЖДЕНИЯ ИСПОЛНИТЕЛЕЙ
function nowbirthday()
{
	global $db_sql, $prefixf, $siteaddress, $copyrightyear, $nowmonth, $nowday;
	$topalbums = $db_sql->sql_query("SELECT id, addon, identifier, title, birthday, deathday, flag, logo FROM " . $prefixf . "artist WHERE birthday LIKE '%-$nowmonth-$nowday' ORDER BY title ASC");
	if ($db_sql->num_rows($topalbums)>0)
	{
		$fflag = 1;
		$htitle = "В этот день";
		eval('$hotstuff .= "' . template('headbar_alt') . '";');
		while ($topalbum = $db_sql->fetch_array($topalbums))
		{
			$nid = $topalbum['id'];
			$addon = $topalbum['addon'];
			$flag = $topalbum['flag'];
			$alogo = $topalbum['logo'];
			$birthday = $topalbum['birthday'];
			$deathday = $topalbum['deathday'];
			// Считаем сколько лет
			$byear = explode("-", $birthday);
			$artyear = $copyrightyear - $byear[0];
			$let = agetostr($artyear);
			$artistidentifier = $topalbum['identifier'];
			$artistname = htmlspecialchars($topalbum['title']);
			if (!empty($alogo)) {$logo = "<img src='". $alogo ."' style='display: block;margin-left: auto;margin-right: auto;padding-top: 8px' />";}
			else
			{
				// Есть ли логотип у группы
				$garray = $db_sql->sql_query("SELECT a.logo, g.groupid FROM " . $prefixf . "artist_group g LEFT JOIN " . $prefixf . "artist a ON g.groupid = a.id WHERE g.artistid = '$artistid' AND a.logo !='' ORDER BY g.id DESC LIMIT 1");
				if ($db_sql->num_rows($garray)>0)
				{
					while ($artq = $db_sql->fetch_array($garray))
					{
						$alogo = $artq["logo"];
						if (!empty($alogo)) {$logo = "<img src='". $alogo ."' style='display: block;margin-left: auto;margin-right: auto;padding-top: 8px' />";}
					}
				}
			}
			if ($flag == 1)
			{
				if ($deathday == '0000-00-00') {eval('$hotstuff .= "' . template('bithday_bit_link') . '";');}
				else {eval('$hotstuff .= "' . template('bithday_bit_link_death') . '";');}
			}
			else
			{
				if ($deathday == '0000-00-00') {eval('$hotstuff .= "' . template('bithday_bit') . '";');}
				else {eval('$hotstuff .= "' . template('bithday_bit_death') . '";');}
			}
			$logo = "";
		}
	}
	// Альбом вышежший в этот день
	$balbums = $db_sql->sql_query("SELECT id, identifier, artidentifier, title, releasedate, main, feat FROM " . $prefixf . "content WHERE releasedate LIKE '%-$nowmonth-$nowday' ORDER BY title ASC");
	if ($db_sql->num_rows($balbums)>0)
	{
		if ($fflag != 1)
		{
			$htitle = "В этот день";
			eval('$hotstuff .= "' . template('headbar_alt') . '";');
		}
		while ($talbum = $db_sql->fetch_array($balbums))
		{
			$nid = $talbum['id'];
			$releasedate = $talbum['releasedate'];
			// Считаем сколько лет
			$byear = explode("-", $releasedate);
			$artyear = $copyrightyear - $byear[0];
			$let = agetostr($artyear);
			$albidentifier = $talbum['identifier'];
			$artidentifier = $talbum['artidentifier'];
			$falb = $talbum['feat'];
			$malb = $talbum['main'];
			$title = getartisname($malb,$falb,1);
			$title .= " - ". htmlspecialchars($talbum['title']);
			eval('$hotstuff .= "' . template('today_album_bit_link') . '";');
		}
	}
	return $hotstuff;
}


// ФУНКЦИЯ ВЫВОДА ПОПУЛЯРНЫХ ВИДЕО
function hot_stuff($numvid)
{
	global $db_sql, $prefix, $siteaddress, $copyrightyear;
	if(empty($numvid)){$numvid = 3;}
	$htitle = "Топ видео " . $copyrightyear . " года";
	eval('$hotstuff .= "' . template('headbar_alt') . '";');
	$topalbums = $db_sql->sql_query("SELECT id, identifier, title, releaseyear,content FROM " . $prefix . "video WHERE releaseyear = '$copyrightyear' ORDER BY views DESC LIMIT $numvid");
	$numrows = $db_sql->num_rows($topalbums);
	if ($numrows == 0) {$topalbums = $db_sql->sql_query("SELECT id, identifier, title, releaseyear, content FROM " . $prefix . "video WHERE releaseyear = $copyrightyear-1 ORDER BY views DESC LIMIT $numvid");}
	while ($topalbum = $db_sql->fetch_array($topalbums))
	{
		$nid = $topalbum['id'];
		$artistidentifier = $topalbum['identifier'];
		$releaseyear = $topalbum['releaseyear'];
		$ytlink = $topalbum["content"];
		$artistname = htmlspecialchars($topalbum['title']);
		eval('$hotstuff .= "' . template('artist_videos_bit') . '";');
	}
	return $hotstuff;
}

// ФУНКЦИЯ ВЫВОДА СВЕЖИХ ВИДЕО
function new_stuff($numvid)
{
	global $db_sql, $prefix, $siteaddress, $copyrightyear;
	if(empty($numvid)){$numvid = 3;}
	$htitle = "Видео";
	eval('$hotstuff .= "' . template('headbar_alt') . '";');
	$topalbums = $db_sql->sql_query("SELECT id, identifier, title, releaseyear, content FROM " . $prefix . "video WHERE releaseyear = '$copyrightyear' ORDER BY id DESC LIMIT $numvid");
	$numrows = $db_sql->num_rows($topalbums);
	if ($numrows == 0) {$topalbums = $db_sql->sql_query("SELECT id, identifier, title, releaseyear, content FROM " . $prefix . "video WHERE releaseyear = $copyrightyear-1 ORDER BY id DESC LIMIT $numvid");}
	while ($topalbum = $db_sql->fetch_array($topalbums))
	{
		$nid = $topalbum['id'];
		$artistidentifier = $topalbum['identifier'];
		$releaseyear = $topalbum['releaseyear'];
		$ytlink = $topalbum["content"];
		$artistname = htmlspecialchars($topalbum['title']);
		eval('$hotstuff .= "' . template('artist_videos_bit') . '";');
	}
	return $hotstuff;
}


// ФУНКЦИЯ ВЫВОДА БЛИЖАЙШИХ РЕЛИЗОВ
function new_releases($numvid)
{
	global $db_sql, $prefix, $siteaddress, $copyrightyear, $nowmonth, $nowday;
	if(empty($numvid)){$numvid = 3;}
	$htitle = "Ближайшие релизы";
	eval('$hotstuff .= "' . template('headbar_alt') . '";');
	$topalbums = $db_sql->sql_query("SELECT id, artist, title, releasedate, source FROM " . $prefix . "releases WHERE releasedate >= '$copyrightyear-$nowmonth-$nowday' ORDER BY releasedate ASC LIMIT $numvid");
	$style = "alt1";
	$sc = "0";
	while ($topalbum = $db_sql->fetch_array($topalbums))
	{
		if ($sc % 2) {$style = "aalt2";}
		else {$style = "aalt1";}
		$nid = $topalbum['id'];
		// Меняем формат даты
		$rdate = explode("-", $topalbum['releasedate']);
		$releasedate = rus_date("j F Y", mktime(0, 0, 0, $rdate[1], $rdate[2], $rdate[0]));
		$artistname = htmlspecialchars($topalbum['artist']);
		$releasetitle = htmlspecialchars($topalbum['title']);
		$releasesource = htmlspecialchars($topalbum['source']);
		eval('$hotstuff .= "' . template('block_release_bit') . '";');
		$sc++;
	}
	return $hotstuff;
}


// ССЫЛКИ НА $x СЛУЧАЙНЫХ ИСПОЛНИТЕЛЕЙ
function randomartists($x)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	
	eval('$artistlinks .= "' . template('block_artists') . '";');
	// Берём из базы всех исполнителей
	$artarray = $db_sql->sql_query("SELECT id, identifier, title, addon FROM " . $prefixf . "artist WHERE flag = '1'");
	$numrows = $db_sql->num_rows($artarray);
	// Заполняем массив
	for ($i=0; $i!=$numrows; $i++)
	{
		$arts[$i] = $db_sql->fetch_array($artarray);
	}
	// Перемешиваем
	shuffle($arts);
	$style = "alt1";
	$sc = "0";
	for ($a=0; $a!=$x; $a++)
	{
		if ($sc % 2) {$style = "aalt2";}
		else {$style = "aalt1";}
		
		$artid = $arts[$a]['id'];
		$artistidentifier = $arts[$a]['identifier'];
		$artistname = htmlspecialchars($arts[$a]['title']);
		$artdescription = $arts[$a]['addon'];
		$sc++;
		eval('$artistlinks .= "' . template('block_artists_bit') . '";');
	}
	return $artistlinks;
}


// СВЕЖИЕ НОВОСТИ
function lastnews($x)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	$style = "alt1";
	$sc = "0";
	$htitle = "Новости";
	eval('$artistlinks .= "' . template('headbar_alt') . '";');
	// Берём нужное количество последнийх новостей
	$newsarray = $db_sql->sql_query("SELECT id, identifier, title, publishdate FROM " . $prefix . "news ORDER BY publishdate DESC LIMIT $x");
	while ($news = $db_sql->fetch_array($newsarray))
	{
		if ($sc % 2) {$style = "aalt2";}
		else {$style = "aalt1";}
		
		$artid = $news["id"];
		$artistidentifier = $news["identifier"];
		$artistname = htmlspecialchars($news["title"]);
		$publishdate = date('d.m.Y', $news["publishdate"]);
		$sc++;
		eval('$artistlinks .= "' . template('block_news_bit') . '";');
	}
	return $artistlinks;
}


// ССЫЛКИ НА АЛЬБОМЫ ИСПОЛНИТЕЛЕЙ
function albumlinks($query, $flag)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	if (!empty($query))
	{
		// Берём из базы все альбомы исполнителя
		$albumsarray = $db_sql->sql_query("SELECT a.id as artid, h.artistid, c.artidentifier, c.id, c.identifier, c.title, c.year, c.main, c.feat, c.tracks FROM " . $prefixf . "hash h LEFT JOIN " . $prefixf . "artist a ON h.artistid = a.id LEFT JOIN " . $prefixf . "content c ON c.id = h.albumid WHERE (a.id IN ($query) OR a.mainid IN ($query)) AND c.type = '0' ORDER BY c.year DESC");
		if ($db_sql->num_rows($albumsarray)>0)
		{
			$htitle = "Альбомы";
			eval('$artistlinks .= "' . template('headbar_alt') . '";');
			$style = "alt1";
			$sc = "0";
			eval('$bodycont .= "' . template('headbar_alt') . '";');
			$bodycont .= "<div style=\"float:left;width:100%;\">";
			
			while ($album = $db_sql->fetch_array($albumsarray))
			{
				$artistidentifier = $album["artidentifier"];
				$albumyear = $album["year"];
				if (empty($flag))
				{
					if ($sc % 2) {$style = "aalt2";}
					else {$style = "aalt1";}
					$albumid = $album["id"];
					$albumidentifier = $album["identifier"];
					$albumtitle = htmlspecialchars($album["title"]);
					$artistid = $album["artistid"];
					$marts = $album["main"];
					$farts = $album["feat"];
					$artistname = getartisname($marts,$farts,1);
					if ($sc <= 4)
					{
						eval('$artistlinks .= "' . template('artist_albums_bit') . '";');
					}
					else
					{
						eval('$fullalbums .= "' . template('artist_albums_bit') . '";');
					}
					$sc++;
				}
				else
				{
					$artidentifier = $album["artidentifier"];
					$newsidentifier = $album["identifier"];
					$malb = $album["main"];
					$falb = $album["feat"];
					$fullartist = getartisname($malb,$falb,1);
					$contid = $album["id"];
					$tracks = $album["tracks"];
					$newstitle = htmlspecialchars($album["title"]);
					if ($sch%2) {$alg = "right";}
					else {$alg = "left";}
					$alby = "Год: " . $albumyear . "<br />";
					eval('$bodycont .= "' . template('albums_section_bit_td') . '";');
					$sch++;
				}
			}
			$bodycont .= "</div>";
			if ($sc > 5)
			{
				eval('$artistlinks .= "' . template('artist_full_albums_bit') . '";');
			}
			if (empty($flag)) {return $artistlinks;}
			else
			{
				// Если есть группы выводим альбомы групп
				$garray = $db_sql->sql_query("SELECT g.groupid, a.title FROM " . $prefixf . "artist_group g LEFT JOIN " . $prefixf . "artist a ON g.groupid = a.id WHERE g.artistid IN ($query) GROUP BY a.id ORDER BY a.title ASC");
				while ($galbum = $db_sql->fetch_array($garray))
				{
					$gid = $galbum["groupid"];
					// Берём все альбомы группы
					$albumsarray = $db_sql->sql_query("SELECT a.id as artid, a.title as arttitle, h.artistid, c.artidentifier, c.id, c.identifier, c.title, c.year, c.main, c.feat, c.tracks FROM " . $prefixf . "hash h LEFT JOIN " . $prefixf . "artist a ON h.artistid = a.id LEFT JOIN " . $prefixf . "content c ON c.id = h.albumid WHERE a.id = $gid AND c.type = '0' ORDER BY c.year DESC");
					if ($db_sql->num_rows($albumsarray)>0)
					{
						$htitle = "Альбомы группы " . htmlspecialchars($galbum["title"]);
						$bodycont .= "<div style=\"float:left;width:100%;\">";
						eval('$bodycont .= "' . template('headbar_alt') . '";');
						while ($album = $db_sql->fetch_array($albumsarray))
						{
							$albumyear = $album["year"];
							$artidentifier = $album["artidentifier"];
							$newsidentifier = $album["identifier"];
							$malb = $album["main"];
							$falb = $album["feat"];
							$fullartist = getartisname($malb,$falb,1);
							$contid = $album["id"];
							$tracks = $album["tracks"];
							$newstitle = htmlspecialchars($album["title"]);
							if ($sch%2) {$alg = "right";}
							else {$alg = "left";}
							$alby = "Год: " . $albumyear . "<br />";
							eval('$bodycont .= "' . template('albums_section_bit_td') . '";');
						}
						$bodycont .= "</div>";
					}
				}
				return $bodycont;
			}
		}
	}
}


// ССЫЛКИ НА ГРУППЫ ИСПОЛНИТЕЛЯ
function artistgroup($aid)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	if (!empty($aid))
	{
		// Берём все псевдонимы
		$altarray = $db_sql->sql_query("SELECT title FROM " . $prefixf . "artist WHERE mainid = '$aid' ORDER BY title ASC");
		if ($db_sql->num_rows($altarray)>0)
		{
			$htitle = "A.K.A.";
			eval('$newslinks .= "' . template('headbar_alt') . '";');
			while ($album = $db_sql->fetch_array($altarray))
			{
				$artistname = htmlspecialchars($album["title"]);
				eval('$padcont .= "' . template('artist_related_bit_nobio') . '";');
			}
			eval('$newslinks .= "' . template('padding_4px') . '";');
		}
		$padcont = "";
		// Берём всех участников группы
		$albumsarray = $db_sql->sql_query("SELECT a.identifier, a.title, a.id, a.addon, a.flag, a.logo FROM " . $prefixf . "artist a LEFT JOIN " . $prefixf . "artist_group g ON g.artistid = a.id WHERE g.groupid = '$aid' GROUP BY a.id ORDER BY a.title ASC");
		if ($db_sql->num_rows($albumsarray)>0)
		{
			$htitle = "Состав группы:";
			eval('$newslinks .= "' . template('headbar_alt') . '";');
			while ($album = $db_sql->fetch_array($albumsarray))
			{
				// Создаём ссылку на исполнителя
				$artistidentifier = $album["identifier"];
				$addon = $album["addon"];
				$artistid = $album["id"];
				$alogo = $album["logo"];
				$artistname = htmlspecialchars($album["title"]);
				$artistflag = $album["flag"];
				if ($artistflag == 1)
				{
					if (!empty($alogo)) {$logo = "<img src='". $alogo ."' style='display: block;margin-left: auto;margin-right: auto;padding-top: 8px' />";}
					else
					{
						// Есть ли логотип у группы
						$garray = $db_sql->sql_query("SELECT a.logo, g.groupid FROM " . $prefixf . "artist_group g LEFT JOIN " . $prefixf . "artist a ON g.groupid = a.id WHERE g.artistid = '$artistid' AND a.logo !='' ORDER BY g.id DESC LIMIT 1");
						if ($db_sql->num_rows($garray)>0)
						{
							while ($artq = $db_sql->fetch_array($garray))
							{
								$alogo = $artq["logo"];
								if (!empty($alogo)) {$logo = "<img src='". $alogo ."' style='display: block;margin-left: auto;margin-right: auto;padding-top: 8px' />";} 
							}
						}
					}
					eval('$padcont .= "' . template('artist_related_bit') . '";');
				}
				else
				{
					eval('$padcont .= "' . template('artist_related_bit_nobio') . '";');
				}
				$logo = "";
			}
			eval('$newslinks .= "' . template('padding_4px') . '";');
		}
		$padcont = "";
		// Берём все группы в состав которых входит исполнитель
		$groupsarray = $db_sql->sql_query("SELECT a.identifier, a.title, a.id, a.addon, a.flag, a.logo FROM " . $prefixf . "artist a LEFT JOIN " . $prefixf . "artist_group g ON g.groupid = a.id WHERE g.artistid = '$aid' GROUP BY a.id ORDER BY a.title ASC");
		if ($db_sql->num_rows($groupsarray)>0)
		{
			$htitle = "Входит в состав:";
			eval('$newslinks .= "' . template('headbar_alt') . '";');
			while ($gr = $db_sql->fetch_array($groupsarray))
			{
				// Создаём ссылку на исполнителя
				$artistidentifier = $gr["identifier"];
				$addon = $gr["addon"];
				$artistid = $gr["id"];
				$alogo = $gr["logo"];
				$artistname = htmlspecialchars($gr["title"]);
				$artistflag = $gr["flag"];
				if ($artistflag == 1)
				{
					if (!empty($alogo)) {$logo = "<img src='". $alogo ."' style='display: block;margin-left: auto;margin-right: auto;padding-top: 8px' />";}
					eval('$padcont .= "' . template('artist_related_bit') . '";');
				}
				else
				{
					eval('$padcont .= "' . template('artist_related_bit_nobio') . '";');
				}
				$logo = "";
			}
			eval('$newslinks .= "' . template('padding_4px') . '";');
		}
	}
	return $newslinks;
}


// ССЫЛКИ НА ВИДЕО ИСПОЛНИТЕЛЕЙ
function videolinks($query, $maxvid)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	if (!empty($query))
	{
		if (!empty($maxvid)) {$limit = "LIMIT " . $maxvid;}
		// Берём из базы все видео исполнителя
		$albumsarray = $db_sql->sql_query("SELECT v.id, v.identifier, v.title, v.releaseyear, v.content FROM " . $prefix . "video_hash h LEFT JOIN " . $prefixf . "artist a ON h.artistid = a.id LEFT JOIN " . $prefix . "video v ON v.id = h.videoid WHERE a.id in ($query) GROUP BY v.id ORDER BY v.releaseyear DESC, v.id DESC $limit");
		if ($db_sql->num_rows($albumsarray)>0)
		{
			$htitle = "Видео";
			eval('$artistlinks .= "' . template('headbar_alt') . '";');
			$style = "alt1";
			$sc = "0";
			while ($album = $db_sql->fetch_array($albumsarray))
			{
				if ($sc % 2) {$style = "alt2";}
				else {$style = "alt1";}
				$releaseyear = $album["releaseyear"];
				$nid = $album["id"];
				$ytlink = $album["content"];
				$artistidentifier = $album["identifier"];
				$artistname = htmlspecialchars($album["title"]);
				if ($sc <= 2)
				{
					eval('$artistlinks .= "' . template('artist_videos_bit') . '";');
				}
				else
				{
					eval('$fullalbums .= "' . template('artist_videos_bit') . '";');
				}
				$sc++;
			}
			if ($sc > 3)
			{
				eval('$artistlinks .= "' . template('artist_full_videos_bit') . '";');
				eval('$artistlinks .= "' . template('line') . '";');
				$artistlinks .= "<br />";
			}
			return $artistlinks;
		}
	}
}


// ССЫЛКИ НА АЛЬБОМЫ В ТЕМУ
function albumlinksartist($query, $albid)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	if (!empty($query))
	{
		// Берём из базы все альбомы исполнителя
		$albumsarray = $db_sql->sql_query("SELECT a.id as artid, h.artistid, c.artidentifier, c.id, c.identifier, c.title, c.year, c.main, c.feat, c.tracks FROM " . $prefixf . "hash h LEFT JOIN " . $prefixf . "artist a ON (h.artistid = a.id OR (h.artistid = a.mainid AND a.mainid != 0)) LEFT JOIN " . $prefixf . "content c ON c.id = h.albumid WHERE (a.id IN ($query) OR a.mainid IN ($query)) AND c.type = '0' AND c.id != $albid GROUP BY c.id ORDER BY c.year DESC");
		if ($db_sql->num_rows($albumsarray)>0)
		{
			$htitle = "Альбомы в тему";
			eval('$artistlinks .= "' . template('headbar_alt') . '";');
			$style = "alt1";
			$sc = "0";
			eval('$bodycont .= "' . template('headbar_alt') . '";');
			$bodycont .= "<div style=\"float:left;width:100%;\">";
			
			while ($album = $db_sql->fetch_array($albumsarray))
			{
				$artistidentifier = $album["artidentifier"];
				$albumyear = $album["year"];
				$artidentifier = $album["artidentifier"];
				$newsidentifier = $album["identifier"];
				$malb = $album["main"];
				$falb = $album["feat"];
				$fullartist = getartisname($malb,$falb,1);
				$contid = $album["id"];
				$tracks = $album["tracks"];
				$newstitle = htmlspecialchars($album["title"]);
				if ($sch%2) {$alg = "right";}
				else {$alg = "left";}
				$alby = "Год: " . $albumyear . "<br />";
				eval('$bodycont .= "' . template('albums_section_bit_td') . '";');
				$sch++;
			}
			$bodycont .= "</div>";
			if ($sc > 5)
			{
				eval('$artistlinks .= "' . template('artist_full_albums_bit') . '";');
			}
			// Если есть группы выводим альбомы групп
			$garray = $db_sql->sql_query("SELECT g.groupid, a.title FROM " . $prefixf . "artist_group g LEFT JOIN " . $prefixf . "artist a ON g.groupid = a.id WHERE g.artistid IN ($query) GROUP BY a.id ORDER BY a.title ASC");
			while ($galbum = $db_sql->fetch_array($garray))
			{
				$gid = $galbum["groupid"];
				// Берём все альбомы группы
				$albumsarray = $db_sql->sql_query("SELECT a.id as artid, a.title as arttitle, h.artistid, c.artidentifier, c.id, c.identifier, c.title, c.year, c.main, c.feat, c.tracks FROM " . $prefixf . "hash h LEFT JOIN " . $prefixf . "artist a ON h.artistid = a.id LEFT JOIN " . $prefixf . "content c ON c.id = h.albumid WHERE a.id = $gid AND c.type = '0' AND c.id != $albid AND a.id NOT IN ($query) ORDER BY c.year DESC");
				if ($db_sql->num_rows($albumsarray)>0)
				{
					$htitle = "Альбомы группы " . htmlspecialchars($galbum["title"]);
					$bodycont .= "<div style=\"float:left;width:100%;\">";
					eval('$bodycont .= "' . template('headbar_alt') . '";');
					while ($album = $db_sql->fetch_array($albumsarray))
					{
						$albumyear = $album["year"];
						$artidentifier = $album["artidentifier"];
						$newsidentifier = $album["identifier"];
						$malb = $album["main"];
						$falb = $album["feat"];
						$fullartist = getartisname($malb,$falb,1);
						$contid = $album["id"];
						$tracks = $album["tracks"];
						$newstitle = htmlspecialchars($album["title"]);
						if ($sch%2) {$alg = "right";}
						else {$alg = "left";}
						$alby = "Год: " . $albumyear . "<br />";
						eval('$bodycont .= "' . template('albums_section_bit_td') . '";');
					}
					$bodycont .= "</div>";
				}
			}
			return $bodycont;
		}
	}
}


// ССЫЛКИ НА ВИДЕО В ТЕМУ
function videolinksartist($vids, $query)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	if (!empty($query))
	{
		// Берём из базы все видео исполнителя
		$videosarray = $db_sql->sql_query("SELECT v.id, v.identifier, v.title, v.content FROM " . $prefix . "video_hash h LEFT JOIN " . $prefixf . "artist a ON h.artistid = a.id LEFT JOIN " . $prefix . "video v ON v.id = h.videoid WHERE a.id in ($query) AND v.id != $vids GROUP BY v.id ORDER BY v.releaseyear DESC, v.publishdate DESC");
		if ($db_sql->num_rows($videosarray)>0)
		{
			$sch = 0;
			while ($news = $db_sql->fetch_array($videosarray))
			{
				$newsid = $news["id"];
				$newsidentifier = $news["identifier"];
				$ytlink = $news["content"];
				$newstitle = htmlspecialchars($news["title"]);
				if ($sch%2)
				{
					$alg = "right";
					eval('$td_vid .= "' . template('videos_section_bit_td_artist') . '";');
					eval('$tr_vid .= "' . template('videos_section_bit') . '";');
					$td_vid = "";
				}
				else
				{
					$alg = "left";
					eval('$td_vid .= "' . template('videos_section_bit_td_artist') . '";');
				}
				$sch++;
			}
			if ($sch%2)
			{
				$td_vid .= "<td width=\"50%\"></td>";
				eval('$tr_vid .= "' . template('videos_section_bit') . '";');
			}
			eval('$videosnav = "' . template('videos_section') . '";');
			if ($vids != 0) {eval('$artistlinks .= "' . template('videos_related_theme') . '";');}
			eval('$artistlinks .= "' . template('videos_related') . '";');
			return $artistlinks;
		}
	}
}


// ССЫЛКИ НА ПОХОЖИХ ИСПОЛНИТЕЛЕЙ
function relatedartists($query)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	if (!empty($query))
	{
		$htitle = "Похожие исполнители";
		eval('$artistlinks .= "' . template('headbar_alt') . '";');
		// Берём из базы всех похожиш исполнителей
		$albumsarray = $db_sql->sql_query("SELECT id, identifier, title, flag, addon, logo FROM " . $prefixf . "artist WHERE id in ($query) ORDER BY title ASC");
		if ($db_sql->num_rows($albumsarray)>0)
		{
			while ($album = $db_sql->fetch_array($albumsarray))
			{
				$artistid = $album["id"];
				$addon = $album["addon"];
				$alogo = $album["logo"];
				$artistidentifier = $album["identifier"];
				$artistname = htmlspecialchars($album["title"]);
				$artistflag = $album["flag"];
				if (!empty($alogo)) {$logo = "<img src='". $alogo ."' style='display: block;margin-left: auto;margin-right: auto;padding-top: 8px' />";}
				else
				{
					// Есть ли логотип у группы
					$garray = $db_sql->sql_query("SELECT a.logo, g.groupid FROM " . $prefixf . "artist_group g LEFT JOIN " . $prefixf . "artist a ON g.groupid = a.id WHERE g.artistid = '$artistid' AND a.logo !='' ORDER BY g.id DESC LIMIT 1");
					if ($db_sql->num_rows($garray)>0)
					{
						while ($artq = $db_sql->fetch_array($garray))
						{
							$alogo = $artq["logo"];
							if (!empty($alogo)) {$logo = "<img src='". $alogo ."' style='display: block;margin-left: auto;margin-right: auto;padding-top: 8px' />";} 
						}
					}
				}
				if ($artistflag == 1)
				{
					eval('$artistlinks .= "' . template('artist_related_bit') . '";');
				}
				else
				{
					eval('$artistlinks .= "' . template('artist_related_bit_nobio') . '";');
				}
				$logo = "";
			}
		}
	}
	return $artistlinks;
}


// ССЫЛКИ НА СТАТЬИ ИСПОЛНИТЕЛЕЙ
function relatedinfo($query)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	if (!empty($query))
	{
		// Берём из базы все инфо исполнителя
		$newsarray = $db_sql->sql_query("SELECT i.id, i.identifier, i.title, i.publishdate FROM " . $prefix . "info_hash h LEFT JOIN " . $prefixf . "artist a ON h.artistid = a.id LEFT JOIN " . $prefix . "info i ON i.id = h.infoid WHERE a.id in ($query) GROUP BY i.id ORDER BY i.publishdate DESC");
		if ($db_sql->num_rows($newsarray)>0)
		{
			$htitle = "Инфо";
			eval('$artistlinks .= "' . template('headbar_alt') . '";');
			$style = "alt1";
			$sc = "0";
			while ($news = $db_sql->fetch_array($newsarray))
			{
				if ($sc % 2) {$style = "alt2";}
				else {$style = "alt1";}
				$nid = $news["id"];
				$artistidentifier = $news["identifier"];
				$artistname = htmlspecialchars($news["title"]);
				$publishdate = date('d.m.Y', $news["publishdate"]);
				eval('$artistlinks .= "' . template('artist_info_bit') . '";');
				$sc++;
			}
			return $artistlinks;
		}
	}
}


// ССЫЛКИ НА НОВОСТИ ИСПОЛНИТЕЛЕЙ
function newslinks($newsid, $query)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	if (!empty($query))
	{
		// Берём из базы все новости исполнителя
		$newsarray = $db_sql->sql_query("SELECT n.id, n.identifier, n.title, n.publishdate FROM " . $prefix . "news_hash h LEFT JOIN " . $prefixf . "artist a ON (h.artistid = a.id OR (h.artistid = a.mainid AND a.mainid != 0)) LEFT JOIN " . $prefix . "news n ON n.id = h.newsid WHERE (a.id in ($query) OR a.mainid in ($query)) AND n.id != $newsid GROUP BY n.id ORDER BY n.publishdate DESC");
		if ($db_sql->num_rows($newsarray)>0)
		{
			eval('$newslinks .= "' . template('artist_news') . '";');
			$style = "alt1";
			$sc = "0";
			while ($news = $db_sql->fetch_array($newsarray))
			{
				if ($sc % 2) {$style = "aalt2";}
				else {$style = "aalt1";}
				$artid = $news["id"];
				$artistidentifier = $news["identifier"];
				$artistname = htmlspecialchars($news["title"]);
				$publishdate = date('d.m.Y', $news["publishdate"]);
				if ($sc <= 4)
				{
					eval('$newslinks .= "' . template('block_news_bit') . '";');
				}
				else
				{
					eval('$fullalbums .= "' . template('block_news_bit') . '";');
				}
				$sc++;
			}
			if ($sc > 5)
			{
				eval('$newslinks .= "' . template('artist_full_news_bit') . '";');
				//eval('$newslinks .= "' . template('line') . '";');
				//$newslinks .= "<br />";
			}
			return $newslinks;
		}
	}
}


// ССЫЛКИ НА СТРАНИЦЫ ИСПОЛНИТЕЛЕЙ
function newslinksartists($newsid, $query)
{
	global $db_sql, $prefix, $prefixf, $siteaddress;
	if (!empty($query))
	{
		$sch = 0;
		// Берём из базы всех нужных исполнителей
		$albumsarray = $db_sql->sql_query("SELECT id, identifier, title, addon, mainid, flag, logo FROM " . $prefixf . "artist WHERE id in ($query) AND (flag = '1' OR mainid != 0) ORDER BY title ASC");
		if ($db_sql->num_rows($albumsarray)>0)
		{
			while ($album = $db_sql->fetch_array($albumsarray))
			{
				// Создаём ссылку на исполнителя
				$artistidentifier = $album["identifier"];
				$addon = $album["addon"];
				$artistid = $album["id"];
				$alogo = $album["logo"];
				$flag = $album["flag"];
				$artistname = htmlspecialchars($album["title"]);
				$mainid = $album["mainid"];
				if (!empty($mainid))
				{
					// Берём основного исполнителя
					$alsarray = $db_sql->sql_query("SELECT id, identifier, title, addon, flag, logo FROM " . $prefixf . "artist WHERE id = $mainid AND flag = '1' ORDER BY title ASC");
					$albu = $db_sql->fetch_array($alsarray);
					// Создаём ссылку на исполнителя
					$artistidentifier = $albu["identifier"];
					$addon = $albu["addon"];
					$alogo = $albu["logo"];
					$artistid = $albu["id"];
					$flag = $albu["flag"];
					$artistname = htmlspecialchars($albu["title"]);
				}
				if (!empty($alogo)) {$logo = "<img src='". $alogo ."' style='display: block;margin-left: auto;margin-right: auto;padding-top: 8px' />";}
				else
				{
					// Есть ли логотип у группы
					$garray = $db_sql->sql_query("SELECT a.logo, g.groupid FROM " . $prefixf . "artist_group g LEFT JOIN " . $prefixf . "artist a ON g.groupid = a.id WHERE g.artistid = '$artistid' AND a.logo !='' ORDER BY g.id DESC LIMIT 1");
					if ($db_sql->num_rows($garray)>0)
				{
						while ($artq = $db_sql->fetch_array($garray))
						{
							$alogo = $artq["logo"];
							if (!empty($alogo)) {$logo = "<img src='". $alogo ."' style='display: block;margin-left: auto;margin-right: auto;padding-top: 8px' />";} 
						}
					}
				}
				if ($flag == 1)
				{
					if ($sch == 0) {eval('$newslinks .= "' . template('line') . '";');}
					eval('$padcont .= "' . template('artist_related_bit') . '";');
					$sch++;
				}
				$logo = "";
			}
			eval('$newslinks .= "' . template('padding_4px') . '";');
		}
	}
	return $newslinks;
}


// Составление ссылок на артистов
function getartisname($malb,$falb,$t)
{
	global $db_sql, $prefixf, $siteaddress;
	if (!empty($malb))
	{
		// Считаем количество главных исполнителей
		$artcount = $db_sql->query_array("SELECT Count(*) as total FROM " . $prefixf . "artist WHERE id in ($malb)");
		$result = $artcount['total'];
		// Берём всех главных исполнителей
		$allarts = $db_sql->sql_query("SELECT title, identifier FROM " . $prefixf . "artist WHERE id in ($malb) ORDER BY FIELD(id, $malb)");
		if ($result <= 2 AND $result !=0)
		{
			while ($numrowz = $db_sql->fetch_array($allarts))
			{
				$artname = $numrowz['title'];
				$artistid = $numrowz['identifier'];
				eval('$artlink = "' . template('artist_link') . '";');
				$complitname .= $artlink . " & ";
				$namewithoutlink .= $artname . " & ";
			}
			$complitname = substr($complitname,0,strlen($complitname)-3);
			$namewithoutlink = substr($namewithoutlink,0,strlen($namewithoutlink)-3);
		}
		else
		{
			$sch = 1;
			while ($numrowz = $db_sql->fetch_array($allarts))
			{
				$artname = $numrowz['title'];
				$artistid = $numrowz['identifier'];
				eval('$artlink = "' . template('artist_link') . '";');
				if ($sch != $result - 1)
				{
					$complitname .= $artlink . ", ";
					$namewithoutlink .= $artname . ", ";
				}
				else
				{
					$complitname .= $artlink . " & ";
					$namewithoutlink .= $artname . " & ";
				}
				$sch++;
			}
			$complitname = substr($complitname,0,strlen($complitname)-2);
			$namewithoutlink = substr($namewithoutlink,0,strlen($namewithoutlink)-2);
		}
	}
	if (!empty($falb))
	{
		// Считаем количество feat исполнителей
		$artcount = $db_sql->query_array("SELECT Count(*) as total FROM " . $prefixf . "artist WHERE id in ($falb)");
		$result = $artcount['total'];
		// Берём всех feat исполнителей
		$allarts = $db_sql->sql_query("SELECT title, identifier FROM " . $prefixf . "artist WHERE id in ($falb) ORDER BY FIELD(id, $falb)");
		if ($result <= 2 AND $result !=0)
		{
			while ($numrowz = $db_sql->fetch_array($allarts))
			{
				$artname = $numrowz['title'];
				$artistid = $numrowz['identifier'];
				eval('$artlink = "' . template('artist_link') . '";');
				$featarts .= $artlink . " & ";
				$featwithoutlink .= $artname . " & ";
			}
			$featarts = substr($featarts,0,strlen($featarts)-3);
			$featwithoutlink = substr($featwithoutlink,0,strlen($featwithoutlink)-3);
		}
		else
		{
			$sch = 1;
			while ($numrowz = $db_sql->fetch_array($allarts))
			{
				$artname = $numrowz['title'];
				$artistid = $numrowz['identifier'];
				eval('$artlink = "' . template('artist_link') . '";');
				if ($sch != $result - 1)
				{
					$featarts .= $artlink . ", ";
					$featwithoutlink .= $artname . ", ";
				}
				else
				{
					$featarts .= $artlink . " & ";
					$featwithoutlink .= $artname . " & ";
				}
				$sch++;
			}
			$featarts = substr($featarts,0,strlen($featarts)-2);
			$featwithoutlink = substr($featwithoutlink,0,strlen($featwithoutlink)-2);
		}
	}
	if (!empty($featwithoutlink))
	{
		$complitname .= " feat. " . $featarts;
		$namewithoutlink .= " feat. " . $featwithoutlink;
	}
	if($t == 1)
	{
		return $namewithoutlink;
	}
	else
	{
		return $complitname;
	}
}
?>