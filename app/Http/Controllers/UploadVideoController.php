<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Jobs\Videos\ConvertForStreaming;
use Illuminate\Http\Request;

class UploadVideoController extends Controller
{
    public function index(Channel $channel){
        return view('channels.upload', compact('channel'));
    }

    public function store(Channel $channel){
        $video = $channel->videos()->create([
            'title' => request()->title,
            'path' => request()->video->store("channels/{$channel->id}")

        ]);

        $this->dispatch(new ConvertForStreaming($video));
        // php artisan queue:work --sleep=0 -timeout 60000

        return $video;

    }
}
