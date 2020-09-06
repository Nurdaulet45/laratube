import numeral from 'numeral'

Vue.component('subscribe-button',{
    props:{
        channel:{
            type: Object,
            required: true,
            default: () => ({})
        },
        subscriptions:{
            type: Array,
            required: true,
            default: () => []
        }
    },
    methods: {
        toggleSubscription(){
            if (! __auth())
                return  alert('Please login to subscribe');

            if (this.owner)
                return  alert('You cannot subscribe to you channel');

            if (this.subscribed)
                axios.delete(`/channels/${this.channel.id}/subscriptions/${this.subscription.id}`)
            else
                axios.post(`/channels/${this.channel.id}/subscriptions`)
        },

    },
    computed:{
        subscribed(){
            if (! __auth() || this.channel.user_id === __auth().id) return null;

            return !! this.subscription
        },
        subscription(){
            if (! __auth())
                return false;
            return this.subscriptions.find(subscription=> subscription.user_id === __auth().id)
        },
        owner(){
            if (__auth() && this.channel.user_id === __auth().id) return true;
            return false
        },
        count(){
            return numeral(this.subscriptions.length).format('0a')
        }
    }

});
