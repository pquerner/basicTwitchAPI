<?php

$_conf["acceptedModes"] = Array(
    "basic", "all", "followers", "viewers",
);
$_conf["_defaults"]["mode"] = 'basic';
$_conf["_user"] = Array();

require_once('basicTwitch.php'); //Class for twitch
require_once('helper.php'); //Helper functions

$url = $_SERVER["REQUEST_URI"];
$url = explode("/", $url);
@$channel = $url[2];
@$mode = $url[3];
$dir = getCurrentPathWithHostName();

//Redirect if index.php is in name. I dont like that. :(
if (stristr($channel, 'index.php'))
    header("Location:" . httpOrHttps() . $dir);

if (empty($channel)) {
    echo("Please call me like: ");
    echo(httpOrHttps() . $dir . "airfrischung/basic/");
    die();
} elseif (is_string($channel)) {
    $twitchChannel = strip_tags($channel);
    if (!empty($mode) && in_array($mode, $_conf["acceptedModes"])) {
        $_conf["_user"]["mode"] = $_GET["mode"];
    }
}
@$mode = ($_conf["_user"]["mode"]) ? $_conf["_user"]["mode"] : $_conf["_defaults"]["mode"];
$twitch = new TwitchChannel($twitchChannel, $mode);
arrangeData($twitch, $_conf["_defaults"]["mode"]);
?>