<?php

namespace App\Youtube;

use DateInterval;
use Illuminate\Support\Facades\Http;

class YoutubeServices{

    private $key = null;

    public function __construct($key) {
        $this->key = $key;
    }

    public function handleYoutubeVideoDuration(string $video_url)
    {
        //ex of video_url : https://www.youtube.com/embed/hccLteUCwT0
        //the video id is the code after embed/ 

        //get video id from video_url
        //get the id and store it in $matches
        preg_match('/embed\/(.+)/', $video_url, $matches);

        $id = $matches[1];

        //call youtube api to get the duration
        $response = Http::get("https://www.googleapis.com/youtube/v3/videos?id={$id}&key={$this->key}&part=snippet,contentDetails,statistics,status");

        $decoded_response = json_decode($response);
        $duration = $decoded_response->items[0]->contentDetails->duration;

        //convert to seconds
        $interval = new DateInterval($duration);

        return $interval->s + $interval->i * 60 + $interval->h * 3600;
    }
}
