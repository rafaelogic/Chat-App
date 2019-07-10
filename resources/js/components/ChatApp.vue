<template>
   <div class="chat-app">
       <Conversation />
       <ContactList :contacts="contacts"/>
   </div>
</template>

<script>
    import { mapGetters } from 'vuex'; 
    import { updateUnreadContact } from '../mixins'
    import Conversation from './Conversation';
    import ContactList  from './ContactList';

    export default {
        name: 'messenger',

        props: {
            user: {
                type: Object,
                required: true,
            },
        },

        mixins: [updateUnreadContact],

        components: { 
            Conversation, 
            ContactList, 
        },

        created() {
            Echo.private(`messages.${this.user.id}`)
                .listen('NewMessage', (e) => {
                    this.messageHandler(e.message);
                });

            this.$store.dispatch('FETCH_CONTACTS');
        },

        computed: {
            ...mapGetters({
                contacts: 'contacts',
                selectedContact: 'selectedContact'
            })
        },

        methods: {
            messageHandler(message) {
                if (this.selectedContact && message.from == this.selectedContact.id) {
                    this.$store.commit('MESSAGES', message);
                    this.$store.commit('MESSAGE', message);
                    return;
                }

                this.updateUnreadContact(message.sender, this.contacts, false);
            },
        },
    }
</script>
<style lang="scss" scoped>
    .chat-app {
        display: flex;
    }
</style>