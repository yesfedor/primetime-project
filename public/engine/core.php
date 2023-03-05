<?php
$public = $_SERVER['DOCUMENT_ROOT'];
$core = $public.'/engine/core.php';
#include_once($core);

/* Mode:
 * $modeModule: if true:
 *  include $p['page]
*/

session_name('client_id');
session_start([
    'cookie_lifetime' => 864000,
]);

function debug($bug) {
    if ($bug === false) $bug = '<b>False</b>';
    echo '<h6><pre>';
    print_r($bug);
    echo '</pre></h6>';
}
function appGetSSL() {
	if ( isset( $_SERVER['HTTPS'] ) ) {
		if ( 'on' == strtolower( $_SERVER['HTTPS'] ) ) {
			return 'https://';
		}

		if ( '1' == $_SERVER['HTTPS'] ) {
			return 'https://';
		}
	} elseif ( isset($_SERVER['SERVER_PORT'] ) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
		return 'https://';
	}
	return 'http://';
}
function appGetDomain() {
    return $_SERVER['HTTP_HOST'];
}
function isJSON( $data = null ) {
	if( ! empty( $data ) ) {
		$tmp = json_decode( $data );
		return (
				json_last_error() === JSON_ERROR_NONE
				&& ( is_object( $tmp ) || is_array( $tmp ) )
		);
	}
	return false;
}
function appGetUrl() {
    return appGetSSL().appGetDomain().$_SERVER['REQUEST_URI'];
}
function appLangCheck($lang) {
    switch($lang) {
        case "ru": $accept_lang="ru";break; // русский
        case "de": $accept_lang="de";break; // немецкий
        case "en": case "uk": case "us": $accept_lang="en";break; // английский
        case "ua": $accept_lang="ua";break; // украинский
        default: $accept_lang="ru";break; // по умолчанию, например, русский
    }
    return $accept_lang;
}
function appLangInit() {
    global $_SESSION;
    global $_GET;
    if ($_GET['lang']) {
        $_SESSION['lang'] = appLangCheck($_GET['lang']);
    }
    if (!$_SESSION['lang']) {
        preg_match('/^\w{2}/',$_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches); 
        $accept_lang = appLangCheck(strtolower($matches[0]));
        $_SESSION['lang'] = $accept_lang;
        return $accept_lang;
    } else return $_SESSION['lang'];
}
function appLangChange($newLang) {
    global $_SESSION;
    $accept_lang = appLangCheck($newLang);
    $_SESSION['lang'] = $accept_lang;
    return $accept_lang;
}
function appLangGet() {
    global $_SESSION;
    return $_SESSION['lang'];
}
function appLocaleGet() {
    global $_SESSION;
    $localeFileUrl = appGetSSL().appGetDomain().'/engine/lang/'.$_SESSION['lang'].'.json';
    $localeJson = file_get_contents($localeFileUrl);
    return json_decode($localeJson, true);
}
function appGetData() {
    global $public;
    $dataMainFile = $public.'/engine/disk/data.json';
    $dataMainJson = file_get_contents($dataMainFile);
    $dataOldFile = $public.'/engine/disk/data.old.json';
    if (isJSON($dataMainJson)) {
        return json_decode($dataMainJson, true);
    } else {
        $dataOldJson = file_get_contents($dataOldFile);
        return json_decode($dataOldJson, true);
    }
}
function appTheme() {
    global $_SESSION;
    if (!$_SESSION['theme']) {
        $_SESSION['theme'] = 'light';
        return $_SESSION['theme'];
    } else {
        switch($_SESSION['theme']) {
            default:
            case 'light':
                $_SESSION['theme'] = 'light';
            break;
            case 'dark':
                $_SESSION['theme'] = 'dark';
            break;
        }
        return $_SESSION['theme'];
    }
}
function saveGetWithUri($url) {
    parse_str(parse_url($url)['query'], $get);
    return $get;
}
function htmlLangMore($lang) {
    $html = '';
    // //$acceptLang = ['ru', 'de', 'en', 'uk', 'us', 'ua'];
    // $acceptLang = ['ru','en'];
    // foreach($acceptLang as $value) {
    //     if ($lang != $value) {
    //         if (parse_url(appGetUrl())['query'] == '')
    //             $html .= '<link rel="alternate" hreflang="'.$value.'" href="'.appGetUrl().'?lang='.$value.'" />'.PHP_EOL;
    //         else
    //             $html .= '<link rel="alternate" hreflang="'.$value.'" href="'.appGetUrl().'&lang='.$value.'" />'.PHP_EOL;
    //     }
    // }
    return $html;
}
$_GET = array_merge($_GET, saveGetWithUri(appGetUrl()));

$lang = appLangInit();
$lang = appLangGet();
$locale = appLocaleGet();
$data = appGetData();
$_SESSION['theme'] = appTheme();

$pagesAdmin = $public.'/engine/pages/admin/';
$pages = $public.'/engine/pages/default/';

# modules
$modules = $public.'/engine/modules.php';
include_once($modules);

# routes
$routes = $public.'/engine/routes.php';
include_once($routes);
?>