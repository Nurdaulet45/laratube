Vue.component('channel-uploads',{
    props:{
        channel:{

            type: Object,
            required: true,
            default:()=>({})
        }
    },

    data(){
        return{
            selected: false,
            videos: [],
            progress: {}
        }
    },
    methods:{
        uploads(){
            this.selected = true;
            this.videos = Array.from(this.$refs.videos.files);

            const uploaders = this.videos.map(video=>{
                const form = new FormData;
                form.append('video', video);
                form.append('title', video.name);
                return axios.post(`/channels/${this.channel.id}/videos`, form,{
                    onUploadProgress:(event)=>{
                        this.progress[video.name] = Math.ceil((event.loaded / event.total) * 100 )
                        this.$forceUpdate()
                    }
                })
            })

        }
    }

})
