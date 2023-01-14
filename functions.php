<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * View a template php file and finish execution
 * 
 * @param string $view_path  Self-explanatory
 * @param array  $stata      One dimensional associative array with needed variables
 * 
 * @return void
 */
function view($view_path = '', $data = [])
{
    global $_CONFIG, $__all_vars;

    $parts = explode('/', 'views/' . $view_path);

    $original_fname = array_pop($parts) . '.blade.php';
    $original_path = join('/', $parts) . '/';
    $original_fullpath = $original_path . $original_fname;

    $compiled_path = str_replace('views/', 'views_compiled/', $original_path);
    $compiled_fname = str_replace('.blade', '', $original_fname);
    $compiled_fullpath = $compiled_path . $compiled_fname;

    if (!file_exists($original_fullpath)) {
        echo '<strong>View not found:</strong> ' . $original_fullpath;
        exit;
    }

    if ($_CONFIG['debug'] || !file_exists($compiled_fullpath) || (filemtime($original_fullpath) > filemtime($compiled_fullpath))) {
        bladeCompile($original_path, $original_fname, $compiled_path, $compiled_fname);
    }

    $__all_vars = $__all_vars ?: [];
    $__all_vars += $data;
    extract($__all_vars);

    require($compiled_fullpath);
}

/**
 * My own blade implementation
 * Compiles a blade file to a executable php file
 * Supported keywords: include, if-endif, elseif, else, foreach-endforeach, forelse-empty-endforelse, {{  }}, {{--  --}}, {!!  !!}
 */
function bladeCompile($original_path, $original_fname, $compiled_path, $compiled_fname)
{
    $content = file_get_contents($original_path . $original_fname);

    $enclosed =         '(\((?:(?:\'.*?\')|(?:".*")|[^()]|(?1)*)*\))';
    $enclosed_string =  '\([\'"](.*?)[\'"]\)';
    $enclosed_dblstring =  '\((?:[\'"](.*?)[\'"]\s*,\s*?(.*)\))';
    $enclosed_echo =    '\{\{((?:(?:\'.*?\')|(?:".*?")|[^{}])*)\}\}';
    // $enclosed_comment = '\{\{--((?:(?:\'.*\')|(?:".*")|[^{}])*)--\}\}';
    $enclosed_comment = '\{\{--(([^{}])|\{\{(?2)*\}\})*--\}\}';
    $enclosed_literal = '\{!!((?:(?:\'.*\')|(?:".*")|[^{}])*)!!\}';
    $content = preg_replace("/$enclosed_comment/s", '', $content); // must come before echo
    $content = preg_replace("/@include\s*?$enclosed/", '<?php view$1; ?>', $content);
    $content = preg_replace("/@extends\s*?$enclosed_string(.*?)$/s", "$2\n<?php view('$1', ['__sections' => \$__sections ?? [], '__pushes' => \$__pushes ?? [], '__all_vars' => \$__all_vars]); ?>", $content);
    $content = preg_replace("/@section\s*?$enclosed_dblstring(.*?)/", '<?php $__sections[\'$1\'] = $2; ?>', $content);
    $content = preg_replace("/@section\s*?$enclosed_string(.*?)@endsection/s", '<?php ob_start(); ?>$2<?php $__sections[\'$1\'] = ob_get_clean(); ?>', $content);
    $content = preg_replace("/@push\s*?$enclosed_string(.*?)@endpush/s", '<?php ob_start(); ?>$2<?php $__pushes[\'$1\'][] = ob_get_clean(); ?>', $content);
    $content = preg_replace("/@yield\s*?$enclosed_string/", '<?= $__sections[\'$1\'] ?? \'\' ?>', $content);
    $content = preg_replace("/@stack\s*?$enclosed_string/", '<?= join(PHP_EOL, $__pushes[\'$1\'] ?? []) ?>', $content);
    $content = preg_replace("/@json\s*?$enclosed/", '<?= json_encode $1 ?>', $content);
    $content = preg_replace("/@if\s*?$enclosed/", '<?php if $1 { ?>', $content);
    $content = preg_replace("/@elseif\s*?$enclosed/", '<?php } elseif $1 { ?>', $content);
    $content = preg_replace("/@else/", '<?php } else { ?>', $content);
    $content = preg_replace("/@foreach\s*?$enclosed/", '<?php foreach $1 { ?>', $content);
    $content = preg_replace("/@for\s*?$enclosed/", '<?php for $1 { ?>', $content);
    $content = preg_replace("/@continue\s*?$enclosed/", '<?php if $1 { continue; } ?>', $content);
    $content = preg_replace("/@forelse\s*(\((.*)?\s+as\s+.*?\))/", '<?php if (!empty($2)) { foreach $1 { ?>', $content);
    $content = preg_replace("/@empty/", '<?php } } else { ?>', $content);
    $content = preg_replace("/$enclosed_echo/", '<?= e($1) ?>', $content);
    $content = preg_replace("/$enclosed_literal/", '<?= $1 ?>', $content);
    $content = preg_replace("/@php/", '<?php', $content);
    $content = preg_replace("/@endphp/", '?>', $content);
    $content = preg_replace(['/@endif/', '/@endforeach/', '/@endforelse/', '/@endfor/'], '<?php } ?>', $content);
    $content = preg_replace("/\?>(\s+)<\?php/", "?>\n<?php", $content);
    $content = preg_replace("/^\s+<\?php/", "<?php", $content);
    if (!is_dir($compiled_path)) {
        mkdir($compiled_path, 0777, true);
    }
    file_put_contents($compiled_path . $compiled_fname, $content);
}

/**
 * Escape a string for safe printing
 * 
 * @param string $str  String
 * 
 * @return string  Escaped string
 */
function e($str)
{
    if (!is_array($str)) {
        $str = htmlspecialchars($str);
    }
    return $str;
}

/**
 * Convert an array to string to be used in IN statements
 * 
 * @param array $values
 */
function sqlIn($array, $force_strings = false)
{
    $all = [];
    foreach ($array as $in_item) {
        if (is_numeric($in_item) && !$force_strings) {
            $all[] = $in_item;
        } else {
            $all[] = "'$in_item'";
        }
    }
    return join(',', $all);
}

/**
 * A nicer version of print_r, similar style to Laravel
 * 
 * ...@param mixed  Any number of paramaters to output. 
 */
function dump()
{
    printDebugCss();
    $trace = debug_backtrace();
    foreach (func_get_args() as $arg) {
        echo '<pre class="debug-box">';
        echo '<span class="trace">' . $trace[0]['file'] . ' : ' . $trace[0]['line'] . '</span>';
        prettyPrint(null, $arg);
        echo '</pre>';
    }
}

/**
 * A nicer version of print_r, similar style to Laravel
 * Stops program execution
 * 
 * ...@param mixed  Any number of paramaters to output. 
 */
function dd()
{
    printDebugCss();
    $trace = debug_backtrace();
    foreach (func_get_args() as $arg) {
        echo '<pre class="debug-box">';
        echo '<span class="trace">' . $trace[0]['file'] . ' : ' . $trace[0]['line'] . '</span>';
        prettyPrint(null, $arg);
        echo '</pre>';
    }
    exit;
}

function report($data) {
    if (!is_dir('logs')) {
        mkdir('logs', 775);
    }
    $filename = 'log-' . date('Y-m-d') . '.txt';
    file_put_contents(
        'logs/' . $filename,
        PHP_EOL . date('[Y-m-d H:i:s] ') . print_r($data, true),
        FILE_APPEND | LOCK_EX,
    );
}

/**
 * Recursively print values
 * 
 * @param string $key    Key of variable
 * @param mixed  $val    Value of variable
 * @param int    $depth  Current depth   
 * 
 */
function prettyPrint($key, $val, $first = true)
{
    ob_start();
    $is_obj = false;
    $key_str = is_null($key) ? '' : '<u debug>' . $key . '</u> => ';
    if (gettype($val) === 'object') {
        $is_obj = true;
        $val = get_object_vars($val);
        $key_str = is_null($key) ? '' : '<u class="obj" debug>' . $key . '</u> => ';
    }
    if (is_array($val)) {
        $type_str = 'array(' . count($val) . ')';
        if ($is_obj) {
            $type_str = 'object';
        }
        echo '<details debug open style="display:inline;margin-left: ' . ($first ? 0 : 1.5) . 'rem" class="' . (empty($val) ? 'empty' : '') . '"><summary debug>' . $key_str . '<span class="opaque">' . $type_str . '</span></summary>';
        foreach ($val as $vkey => $vval) {
            prettyPrint($vkey, $vval, false);
        }
        echo '</details>';
    } else {
        if (in_array(gettype($val), ['boolean', 'integer', 'double', 'NULL'])) {
            $class = 'red';
        } else if (in_array(gettype($val), ['string'])) {
            $class = 'string';
        }
        $val = is_null($val) ? 'null' : $val;
        echo '<samp style="margin-left: ' . ($first ? 0 : 1.5) . 'rem">' . $key_str . '<b debug class="' . $class . '">' . htmlspecialchars($val) . '</b></samp>';
    }
    echo (ob_get_clean()) . "\n";
}

/**
 * Output the debug css
 */
function printDebugCss()
{
    echo '
    <style>
        summary[debug] {
            cursor: pointer;
        }
        .opaque {
            font-style: inherit;
            opacity: .6;
        }
        u[debug] {
            text-decoration: none;
            color: skyblue;
        }
        u.obj[debug] {
            color: yellow;
        }
        b[debug] {
            font-weight: normal;
            color: lime;
        }
        b.red {
            color: #c49;
        }
        b.string:before, b.string:after {
            content: \'"\';
        }
        details[debug] > summary {
            list-style: none;
        }
        summary::-webkit-details-marker {
            display: none
        }
        /*
        details[debug]:not(.empty) > summary[debug]:after {
            content: "[";
            margin-left: 5px;            
        }
        details[debug]:not(.empty):not([open]) > summary[debug]:after {
            content: "[...]";    
        }
        details[debug][open]:not(.empty)::after {
            content: "]";
        }
        */
        .debug-box * {
            font-family: monospace!important;
            line-height: normal!important;
        }
        .debug-box {
            background: #111;
            color: #fff;
            padding: .5rem;
            border-radius: .3rem;
            margin-block: 10px;
            word-wrap: break-word;
            max-width: 100%;
            overflow-x: auto;
        }
        .trace {
            float: right;
            display: block;
            opacity: .4;
        }
        samp > b[debug] {
            white-space: pre-wrap;
        }
    </style>
    ';
}

/**
 * Sanitize and urlify a string
 * 
 * @author https://stackoverflow.com/a/66904781/7976388
 * 
 * @param string $text
 * @param int $length optional
 */
function slugify($text, $length = null)
{
    $replacements = [
        '<' => '', '>' => '', '-' => ' ', '&' => '', '"' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae', 'Ä' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae', 'Ç' => 'C', "'" => '', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D', 'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E', 'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G', 'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I', 'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'L', 'Ľ' => 'L', 'Ĺ' => 'L', 'Ļ' => 'L', 'Ŀ' => 'L', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N', 'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O', 'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S', 'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T', 'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U', 'Ü' => 'U', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U', 'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z', 'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'ae', 'ä' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a', 'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c', 'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e', 'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h', 'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i', 'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j', 'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l', 'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n', 'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ö' => 'o', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe', 'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ś' => 's', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ū' => 'u', 'ü' => 'u', 'ů' => 'u', 'ű' => 'u', 'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y', 'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'α' => 'a', 'ß' => 'ss', 'ẞ' => 'b', 'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', '.' => '-', '€' => '-eur-', '$' => '-usd-'
    ];
    // Replace non-ascii characters
    $text = strtr($text, $replacements);
    // Replace non letter or digits with "-"
    $text = preg_replace('~[^\pL\d.]+~u', '-', $text);
    // Replace unwanted characters with "-"
    $text = preg_replace('~[^-\w.]+~', '-', $text);
    // Trim "-"
    $text = trim($text, '-');
    // Remove duplicate "-"
    $text = preg_replace('~-+~', '-', $text);
    // Convert to lowercase
    $text = strtolower($text);
    // Limit length
    if (isset($length) && $length < strlen($text))
        $text = rtrim(substr($text, 0, $length), '-');

    return $text;
}

function sendMail($to, $recipient_name, $subject, $view, $view_data = [])
{
    global $_CONFIG;
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = PHPMAILER_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = PHPMAILER_USERNAME;
        $mail->Password   = PHPMAILER_PASSWORD;
        $mail->Port       = PHPMAILER_PORT;


        //Recipients
        $mail->setFrom(MAIL_FROM, 'noreply');
        $mail->addAddress($to, $recipient_name);
        $mail->addReplyTo(MAIL_FROM, 'noreply');

        // if ($uploadSuccess)
        //     $mail->addAttachment($uploaddir . $newest_file,'Uploaded file');

        $token = DB::fetchValue("SELECT token FROM users WHERE email=:email", [
            'email' => $to,
        ]);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        if (!isset($view_data['subject'])) {
            $view_data['subject'] = $subject;
        }

        ob_start();
        view($view, $view_data);
        $html = ob_get_clean();

        $mail->Body = $html;
        // $mail->AltBody = strip_tags($text);

        if ($mail->send()) {
            report('Mail sent succesfully to ' . $to);
            return true;
        } else {
            report('Mail error');
        }
        // $sql = "INSERT INTO mails (ip_address, country_code, subject, receiver, cc, bcc, is_Html, text, file, status, date_time)     VALUES (?,?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP)";
        // $stmt= $pdo->prepare($sql);
        // $stmt->execute([$ip, $countryCode['country'], $subject, $to, $cc, $bcc, $isHtml, $text, $_FILES['picture']['name'], "sent"]);
    } catch (Exception $e) {
        report("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        // $sql = "INSERT INTO mails (ip_address, country_code, subject, receiver, cc, bcc, is_Html, text, file, status, date_time)     VALUES (?,?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP)";
        // $stmt= $pdo->prepare($sql);
        // $stmt->execute([$ip, $countryCode['country'], $subject, $to, $cc, $bcc, $isHtml, $text, $_FILES['picture']['name'], "error"]);

        // $id = $pdo->lastInsertId;

        // $sql = "INSERT INTO errors (id_mail, message, date_time) VALUES (?,?,CURRENT_TIMESTAMP)";
        // $stmt= $pdo->prepare($sql);
        // $stmt->execute([$id, $text]);
    }
    return false;
}

function logPageView() {
    $url_path = strtok($_SERVER['REQUEST_URI'], '?');
    $detect = new Detection\MobileDetect();
    $ip = $_SERVER['REMOTE_ADDR'];

    if (!in_array($ip, ['127.0.0.1', '::1'])) {
        $geo_data = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
        $country = $geo_data['geoplugin_countryCode'];
    }

    DB::query("INSERT INTO page_views SET url=:url, device=:device, country=:country, ip=:ip", [
        'url' => $url_path,
        'device' => $detect->isMobile() ? 'mobile' : 'desktop',
        'ip' => $_SERVER['REMOTE_ADDR'],
        'country' => $country ?? ''
    ]);
}

function checkLogin() {
    if (empty($_SESSION['user'])) {
        $_SESSION['messages'] = ['You are not logged in.'];
        header('Location: /');
        exit;
    }
}

/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);
  
    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;
  
    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
      cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
}

function time_elapsed($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = (array) $now->diff($ago);

    $diff['w'] = floor($diff['d'] / 7);
    $diff['d'] -= $diff['w'] * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff[$k]) {
            $v = $diff[$k] . ' ' . $v . ($diff[$k] > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function getUserNameFromId ($id) {
    $name = DB::fetchRow("SELECT u.firstname AS 'fname', u.lastname AS 'lname' FROM users u INNER JOIN events e ON e.user_id = u.id WHERE u.id = :id", [ 'id' => $id ]);
    return $name['fname'] . ' ' . $name['lname'];
}