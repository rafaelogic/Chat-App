<template>
    <div class="feed" ref="feed">
        <infinite-loading v-if="messages.length" :identifier="contact.id" direction="top" @infinite="fetchMoreMessages"></infinite-loading>

        <ul v-if="contact">
            <li v-for="message in messages" 
                :key="message.id + '-' + Math.random()" 
                :class="`message${message.to == contact.id ? ' sent' : ' received'}`">
                <div class="text">
                    {{ message.body }}
                    <br>
                    <small>{{ formatDate(message.created_at) }}</small>
                </div> 
            </li>
        </ul>
    </div>
</template>

<script>
    import moment from 'moment';
    import { mapGetters }  from 'vuex';
    import InfiniteLoading from 'vue-infinite-loading';

    export default {
        components: { InfiniteLoading },

        data() {
            return {
                page: 2,
                lastPage: 0,
            };
        },

        computed: {
            ...mapGetters({
                contact : 'selectedContact',
                message : 'message',
                messages: 'messages',
                per_page: 'per_page'
            }),
        },

        methods: {
            scrollToBottom() {
                setTimeout(() => {
                    this.$refs.feed.scrollTop = this.$refs.feed.scrollHeight - this.$refs.feed.clientHeight;
                }, 50)
            },

            fetchMoreMessages($state) {
                setTimeout(function() {
                    axios.get(`/conversation/${this.contact.id}?page=${this.page}`)
                    .then(response => {
                        if (response.data.data.length > 0) {
                            this.lastPage = response.data.last_page;

                            this.$store.commit('MESSAGES', response.data.data);
                        
                            if (this.page -1 === this.lastPage) {
                                this.page = 2;
                                $state.complete();
                            } else {
                                this.page += 1;
                            }

                            $state.loaded();
                        } else {
                            this.page = 2;
                            $state.complete();
                        }
                    })
                     .catch(e => console.log(e));
                }.bind(this), 1000);
            },

            formatDate(date) {
                return moment(date).calendar();
            },
        },

        watch: {
            contact(contact) {
                this.scrollToBottom();
            },

            message(message) {
                this.scrollToBottom();
            }
        }
    }
</script>
<style lang="scss" scoped>
    .feed {
        background: #f0f0f0;
        height: 100%;
        max-height: 470px;
        overflow: scroll;
        ul {
            list-style-type: none;
            padding: 5px;
            li {
                &.message {
                    margin: 10px 0;
                    width: 100%;
                    .text {
                        max-width: 200px;
                        border-radius: 5px;
                        padding: 12px;
                        display: inline-block;
                    }
                    &.received {
                        text-align: right;
                        .text {
                            background: #b2b2b2;
                        }
                    }
                    &.sent {
                        text-align: left;
                        .text {
                            background: #81c4f9;
                        }
                    }
                }
            }
        }
    }
</style>