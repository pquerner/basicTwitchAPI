<?php

require_once('basicTwitch.php');
if (empty($_GET["channel"])) {
    die("Please give me a channel name.");
} elseif (is_string($_GET["channel"])) {
    $twitchChannel = strip_tags($_GET["channel"]);
}
$twitch = new TwitchChannel($twitchChannel);

//echo 'Property dump:<br>';
foreach (object2array($twitch) as $property => $value) {
//    echo "\$twitch." . $property . " = " . $value . "</br>";
    $infos[$property] = $value;
}

echo "<pre>";
print_r($infos);
echo "</pre>";

function object2array($obj) {
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val) {
        $val = (is_array($val) || is_object($val)) ? object2array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}
?>