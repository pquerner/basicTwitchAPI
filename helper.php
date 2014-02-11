<?php

function object2array($obj) {
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val) {
        $val = (is_array($val) || is_object($val)) ? object2array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

function getCurrentPathWithHostName() {
    $url = $_SERVER['REQUEST_URI'];
    $parts = explode('/', $url);
    $dir = $_SERVER['SERVER_NAME'];
    for ($i = 0; $i < count($parts) - 1; $i++) {
        $dir .= $parts[$i] . "/";
    }
    return $dir;
}

function httpOrHttps() {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        return "https://";
    } else {
        return "http://";
    }
}

function showData($data = NULL) {
    if ($data != NULL)
        echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function arrangeData($data, $mode = 'basic') {
    try {
        switch ($mode) {
            case "basic":
                foreach (object2array($data) as $property => $value) {
                    $infos[$property] = $value;
                }
                showData($infos);
                break;
            case "all":

                break;
            default:
                break;
        }
    } catch (Exception $e) {
        var_dump($e);
    }
}

?>
