<?php
$url = parse_url($_SERVER['REQUEST_URI']);
$url_page = parse_url($_SERVER['REQUEST_URI'])['path'];
if ($url_page === '/') $url_page = '/main';

function appRoute($pageData) {
    global $_SESSION;
    global $data;
    global $locale;

    $pError403 = [
        'title' => $locale['pageError403'],
        'description'  => $locale['pageError403'],
        'keywords' => '',
        'ogtype' => 'website',
        'ogsite_name' => $data['site-name'],
        'ogimage' => '/file/ogimg/main.png?v=1',
        'page' => 'global/error403',
        'access' => 'default',
        'error' => 403
    ];
    $pError404 = [
        'title' => $locale['pageError404'],
        'description'  => $locale['pageError404'],
        'keywords' => '',
        'ogtype' => 'website',
        'ogsite_name' => $data['site-name'],
        'ogimage' => '/file/ogimg/main.png?v=1',
        'page' => 'global/error404',
        'access' => 'default',
        'error' => 404
    ];

    if ($pageData) {
        $access = $pageData['access'];
        if ($access == 'default' or $access == $_SESSION['access']) {
            $p = [
                'title' => ($locale[$pageData['title']] ? $locale[$pageData['title']] : $pageData['title']),
                'description' => ($locale[$pageData['description']] ? $locale[$pageData['description']] : $pageData['description']),
                'keywords' => ($locale[$pageData['keywords']] ? $locale[$pageData['keywords']] : $$pageData['keywords']),
                'ogtype' => $pageData['ogtype'],
                'ogsite_name' => $data['site-name'],
                'ogimage' => ($pageData['ogimage'] ? $pageData['ogimage'] : '/file/ogimg/main.png?v=1'),
                'page' => $pageData['page'],
                'access' => $pageData['access'],
                'error' => 200
            ];
            return $p;
        } else return $pError403;
    } else return $pError404;
}

function getFilmByKpid($kpid) {
    $ch = curl_init();
    $headers = array('accept: application/json', 'x-api-key: 91d00358-6586-40e6-9d4e-9d9070547812');

    curl_setopt($ch, CURLOPT_URL, 'https://kinopoiskapiunofficial.tech/api/v2.1/films/'.$kpid); # URL to post to
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
    $data = curl_exec($ch); # run!
    curl_close($ch);
    $content = json_decode($data, true);

    $contentType = $content['data']['type'];
    if ($content['data']['type'] === 'FILM') $contentType = 'фильм';
    if ($content['data']['type'] === 'TV_SERIES') $contentType = 'сериал';
    if ($content['data']['type'] === 'MINI_SERIES') $contentType = 'мини-сериал';

    $p = [
        'title' => 'Смотреть '.$contentType.' '.$content['data']['nameRu'].' ('.$content['data']['year'].') на INY Media',
        'description' => $content['data']['description'],
        'keywords' => $content['data']['description'],
        'ogtype' => 'website',
        'ogimage' => $content['data']['posterUrl'],
        'page' => 'main',
        'access' => 'default',
        'error' => 200
    ];

    return $p;
}

function getStaffByStaffId($staffId) {
    $staff = json_decode(file_get_contents('https://iny.su/api/method/watch.getNameByStaffId?v=1.0&staff='.$staffId), true);
    return [
        'title' => $staff['title'].' - профиль на INY Media',
        'description'  => $staff['title'],
        'keywords' => $staff['title'].', iny, iny media',
        'ogtype' => 'website',
        'ogimage' => 'https://iny.su/web/file/ogimg/main/media.png',
        'page' => 'main',
        'access' => 'default',
        'error' => 200
    ];
}

$p = appRoute($data['routes'][$url_page]);

// watch data
preg_match('/(\/watch)([0-9-_]{1,})/', $url_page, $p_pregWatch);

if ($p_pregWatch[2]) {
    $p = getFilmByKpid($p_pregWatch[2]);
    http_response_code(200);
}

// watch data
preg_match('/(\/trailer)([0-9-_]{1,})/', $url_page, $p_pregTrailer);

if ($p_pregTrailer[2]) {
    $p = getFilmByKpid($p_pregTrailer[2]);
    http_response_code(200);
}

// staff data
preg_match('/(\/name\/)([0-9-_]{1,})/', $url_page, $p_pregStaff);

if ($p_pregStaff[2]) {
    $p = getStaffByStaffId($p_pregStaff[2]);
    http_response_code(200);
}

if ($modeModule == true) {
    if ($p['access'] == 'default') {
        $pFile = $pages.$p['page'].'.php';
        include_once($pFile);
    }
    if ($p['access'] == 'admin') {
        $pFile = $pagesAdmin.$p['page'].'.php';
        include_once($pFile);
    }
} else {
    if ($p['error']) {
        http_response_code($p['error']);
    }
}
