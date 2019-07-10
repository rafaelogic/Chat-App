<template>
    <div class="conversation">
        <h1>{{ contact ? contact.name : 'Select a Contact' }}</h1>
        <MessagesFeed />
        <MessagesComposer />
    </div>
</template>

<script>
    import { mapGetters }   from 'vuex';
    import MessagesFeed     from './MessagesFeed';
    import MessagesComposer from './MessagesComposer';

    export default {
        components: {
            MessagesFeed,
            MessagesComposer,
        },

        computed: {
            ...mapGetters({
                contact: 'selectedContact'
            }),
        },
    }
</script>
<style lang="scss" scoped>
    .conversation {
        flex: 5;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        h1 {
            font-size: 20px;
            padding: 10px;
            margin: 0;
            border-bottom: 1px dashed lightgray;
        }
    }
</style>