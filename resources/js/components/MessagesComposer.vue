<template>
    <div class="composer">
        <textarea v-model="message" @keydown.enter="send" placeholder="Message..."></textarea>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        data() {
            return {
                message: '',
            };
        },

        computed: {
            ...mapGetters({
                contact : 'selectedContact',
            }),
        },

        methods: {
            send(e) {
                e.preventDefault();

                if (!this.contact) {
                    return;
                }
                
                if (this.message == '') {
                    return;
                }

                let data = {
                    contact_id: this.contact.id,
                    body: this.message
                }
                this.$store.dispatch('SEND_MESSAGE', data);
                this.message = '';
            }
        }
    }
</script>
<style lang="scss" scoped>
    .composer textarea {
        width: 96%;
        margin: 10px;
        resize: none;
        border-radius: 3px;
        border: 1px solid lightgray;
        padding: 6px;
    }
</style>