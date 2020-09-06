@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <channel-uploads :channel="{{ $channel }}" inline-template>
                <div class="col-md-8">
                    <input type="file" multiple ref="videos" id="video-files" @change="uploads" style="display: none">
                    <div class="card p-3 justify-content-center align-items-center" v-if="!selected">
                        <svg onclick="document.getElementById('video-files').click()" width="70px" height="70px" viewBox="0 -77 512.00213 512"  xmlns="http://www.w3.org/2000/svg"><path d="m501.453125 56.09375c-5.902344-21.933594-23.195313-39.222656-45.125-45.128906-40.066406-10.964844-200.332031-10.964844-200.332031-10.964844s-160.261719 0-200.328125 10.546875c-21.507813 5.902344-39.222657 23.617187-45.125 45.546875-10.542969 40.0625-10.542969 123.148438-10.542969 123.148438s0 83.503906 10.542969 123.148437c5.90625 21.929687 23.195312 39.222656 45.128906 45.128906 40.484375 10.964844 200.328125 10.964844 200.328125 10.964844s160.261719 0 200.328125-10.546875c21.933594-5.902344 39.222656-23.195312 45.128906-45.125 10.542969-40.066406 10.542969-123.148438 10.542969-123.148438s.421875-83.507812-10.546875-123.570312zm0 0" fill="#f00"/><path d="m204.96875 256 133.269531-76.757812-133.269531-76.757813zm0 0" fill="#fff"/></svg>

                        <p class="text-center">Upload Video</p>
                    </div>
                    <div class="card p-3" v-else>
                        <div class="my-4" v-for="video in videos">
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="{width: `${ progress[video.name]}%`}" aria-value></div>
                            </div>

                            <div class="row">


                                <div class="col-md-4">
                                    <div class="d-flex justify-content-center align-items-center" style="height: 180px;  background-color:black;color: white; font-size: 18px">
                                        Loading thumnail ....

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <h4 class="text-center">
                                        @{{ video.name }}



                                    </h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </channel-uploads>
        </div>
    </div>
@endsection
