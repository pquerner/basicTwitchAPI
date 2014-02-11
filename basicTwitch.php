<?php

class WebClient {

    private $context;

    public function __construct() {
        $options = array('http' =>
            array('timeout' => 30));
        $this->context = stream_context_create($options);
    }

    public function get($url) {
        return file_get_contents($url, false, $this->context);
    }

}

class TwitchChannel {

    private $mode;
    private $apiFullUri;

    public function __construct($channel, $mode) {
        $this->channel = $channel;
        $this->mode = $mode;
        $this->switchApiUri($this->mode);
        $this->Refresh();
    }

    private function switchApiUri($mode) {
        switch ($mode) {
            case "basic":
                $this->apiFullUri = "https://api.twitch.tv/kraken/streams/" . $this->channel;
                break;
            case "followers":
                $this->apiFullUri = "https://api.twitch.tv/kraken/channels/" . $this->channel . "/follows?limit=0&offset=0";
                break;
            case "viewers":
                $this->apiFullUri = "https://api.twitch.tv/kraken/streams/" . $this->channel;
                break;
            case "all":
                break;
            default:
                die('Error');
                break;
        }
    }

    public function Refresh() {
        if (strlen($this->channel) == 0) {
            return;
        }
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n"
            )
        );
        $context = stream_context_create($opts);
        $this->jsonData = file_get_contents($this->apiFullUri, false, $context);
        $this->ReadJSON();
    }

    private function ReadJSON() {
        try {
            $json = json_decode($this->jsonData, true);

            switch ($this->mode) {
                case "basic":
                    $this->getDefaults($json);
                    break;
                case "all":
                    $this->getAll($json);
                    break;
                case "viewers":
                    $this->getViewers($json);
                    break;
                case "followers":
                    $this->getFollowers($json);
                    break;
                default:
                    $this->getDefaults($json);
                    break;
            }
            unset($this->jsonData); //We do not want the jsonData displayed.
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    private function getAll($json) {
        var_dump($json);
        die();
    }

    private function getViewers($json) {
        $stream = $json["stream"];
        $this->Viewer = (string) $stream["viewers"];
    }

    private function getFollowers($json) {
        foreach ($json as $key => $value) {
            if ($key === 'follows') {
                foreach ($value as $vk => $vv) {
                    $this->userUri[] = $vv["_links"]["self"];
                    $this->userName[] = $vv["user"]["display_name"];
                }
            }
        }
    }

    private function getDefaults($json) {
        $stream = $json["stream"];
        $this->Viewer = (string) $stream["viewers"];
        $channel = $stream["channel"];
        $this->ID = (string) $channel["_id"];
        $this->URL = (string) $channel["url"];
        $this->ChatURL = (string) $channel["_links"]["chat"];
        $this->Game = (string) $channel["game"];
        $this->DisplayName = (string) $channel["display_name"];
        $this->Title = (string) $channel["status"];
        $this->UpdatedAt = (string) $channel["updated_at"];
        $this->CreatedAt = (string) $channel["created_at"];
    }

}

?>
