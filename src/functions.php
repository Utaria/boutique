<?php  
function getPost($key = null){
	if($key==null)
		return ((object) $_POST);
	else
		return $_POST[$key];
}

function getGet($key = null){
	if($key==null)
		return ((object) $_GET);
	else
		return $_GET[$key];
}

function getSession($key = null){
	if($key==null)
		return ((object) $_SESSION);
	else
		return $_SESSION[$key];
}

function getUser(){
	return (isset($_SESSION['user'])) ? $_SESSION['user'] : null;
}

function isSessionKey($key){
	return (isset($_SESSION[$key])&&!empty($_SESSION[$key]));
}


function isPost(){
	return (isset($_POST)&&!empty($_POST));
}

function isGet(){
	return (isset($_GET)&&!empty($_GET));
}



function privatePage($cancelActiveCheck = false){
    if(!isSessionKey("user")) redirect("/connexion");
}

function connectPage(){
	if(isSessionKey("user")) redirect("/boutique/choix-moyen-paiement");
}

function redirect($url){
	@header('Location: '.$url);
	die();exit();
}
function redirectWithNotif($url, $message, $type){
    setNotif($message, $type);
    redirect($url);
}

function isDevVersion(){
    return (strpos(VERSION, "dev") !== false);
}

function nameToSlug($id, $name){
    $str = strtolower(remove_accents(preg_replace("/ /", "-", $id . "-" . $name)));
    if(substr($str, -1) == "-") $str = substr($str, 0, -1);
    return trim($str);
}
function stringToSlug($str){
    $str = strtolower(remove_accents(preg_replace("/ /", "-", $str)));
    if(substr($str, -1) == "-") $str = substr($str, 0, -1);
    return trim($str);
}

function slugToId($slug){
    return preg_split("/\-/", $slug)[0];
}

function remove_accents($str, $charset = 'utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    $str = preg_replace('/[^A-Za-z0-9\-]/', '', $str); // supprime les caractères spéciaux
    
    return $str;
}

function print_var_name($var) {
    foreach($GLOBALS as $var_name => $value) {
        if ($value === $var) {
            return $var_name;
        }
    }

    return false;
}

function checkCaptcha($captcha, $secretKey){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('blabla'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $data = array('secret' => $secretKey, 'response' => $captcha, 'remoteip' => $_SERVER["REMOTE_ADDR"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $r = curl_exec($ch);
    if(!empty($r)) $r = json_decode($r);
    curl_close($ch);

    return ($r != null && isset($r->success)) ? $r->success : false;
}

function get_web_page( $url ){
    $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

    $options = array(
        CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
        CURLOPT_POST           =>false,        //set to GET
        CURLOPT_USERAGENT      => $user_agent, //set user agent
        CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
        CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function formatDate($date){
	$time = time() - strtotime($date);

	if(strtotime($date)<0) return '? jours';

	$sec = $time;
	$min = floor($sec / 60);
	$hrs = floor($min / 60);
	$day = floor($hrs / 24);
	$wee = floor($day / 7);

	if($min<=0) return ($sec>1) ? $sec.' secondes' : $sec.' seconde';
	else if($hrs<=0) return ($min>1) ? $min.' minutes' : $min.' minute';
	else if($day<=0) return ($hrs>1) ? $hrs.' heures' : $hrs.' heure';
	else if($wee<=0) return ($day>1) ? $day.' jours' : $day.' jour';
	else return ($wee>1) ? $wee.' semaines' : $wee.' semaine';
}
function simpleFormatDate($date){
    $time = $date;

    $sec = $time;
    $min = floor($sec / 60);
    $hrs = floor($min / 60);
    $day = floor($hrs / 24);
    $wee = floor($day / 7);

    $dayText = "";
    $dayWW   = $day - $wee * 7;
    if($dayWW > 0) $dayText = " et " . $dayWW . " jour" . (($dayWW > 1) ? "s" : "");

    if($min<=0) return ($sec>1) ? $sec.' secondes' : $sec.' seconde';
    else if($hrs<=0) return ($min>1) ? $min.' minutes' : $min.' minute';
    else if($day<=0) return ($hrs>1) ? $hrs.' heures' : $hrs.' heure';
    else if($wee<=0) return ($day>1) ? $day.' jours' : $day.' jour';
    else return ($wee>1) ? $wee.' semaines' . $dayText : $wee.' semaine' . $dayText;
}
function formatTime($time){
    $min = floor($time / 60);
    $sec = $time - (60 * $min);

    return str_pad($min, 2, "0", STR_PAD_LEFT) . ":" . str_pad($sec, 2, "0", STR_PAD_LEFT);
}

function setNotif($message, $type = 'success'){
	$_SESSION['notification'] = array('message' => $message, 'type' => $type);
}

function getNotif(){
	if(!isset($_SESSION['notification'])||empty($_SESSION['notification'])) return false;
	echo '<div class="notif notif-'.$_SESSION['notification']['type'].'"><div class="icon"></div><div class="message">'.__($_SESSION['notification']['message']).'</div><div class="close"><i class="fa fa-times"></i></div></div>';
	unset($_SESSION['notification']);
}


function __($str){
    if(isset($GLOBALS['strings']) && isset($GLOBALS['strings'][$str])){
        return $GLOBALS['strings'][$str];
    }else{
        return $str;
    }
}


function isAdmin($user){
    return $user != null && $user->grade_id == 1;
}
?>