<?php

require_once('basicTwitch.php'); //Class for twitch
require_once('helper.php'); //Helper functions

$url = $_SERVER["REQUEST_URI"];
$url = explode("/", $url);
$channel = $url[2];
@$mode = $url[3];

$_conf["acceptedModes"] = Array(
    "basic", "all", "followers", "viewers",
);
$_conf["_defaults"]["mode"] = 'basic';
$_conf["_user"] = Array();

if (empty($_GET["channel"])) {
    echo("Please give me a channel name. You also can use a subfolder as channel name. eg. ");
    echo(httpOrHttps() . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?channel=airfrischung");
    die();
} elseif (is_string($channel)) {
    $twitchChannel = strip_tags($_GET["channel"]);
    if (!empty($mode) && in_array($mode, $_conf["acceptedModes"])) {
        $_conf["_user"]["mode"] = $_GET["mode"];
    }
}
@$mode = ($_conf["_user"]["mode"]) ? $_conf["_user"]["mode"] : $_conf["_defaults"]["mode"];
$twitch = new TwitchChannel($twitchChannel, $mode);
arrangeData($twitch, $_conf["_defaults"]["mode"]);
?>