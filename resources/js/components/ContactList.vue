<template>
    <div class="contact-list">
        <ul>
            <li v-for="contact in sortedContacts" :key="contact.id" @click="startConversationWith(contact)" :class="{ 'selected': contact === selected }">
                <div class="avatar">
                    <img :src="contact.profile_pic" :alt="contact.name">
                </div>
               <div class="contact">
                    <p class="name">{{ contact.name }}</p>
                    <p class="email">{{ contact.email }}</p>
                </div>
                <span class="unread" v-if="contact.unread">{{ contact.unread }}</span>
            </li>
        </ul>
    </div>
</template>

<script>
    import { updateUnreadContact } from '../mixins'

    export default {
        props: {
            contacts: {
                type: Array,
                required: true,
            }
        },
        
        mixins: [updateUnreadContact],

        data() {
            return {
                selected: null,
                allContacts: this.contacts,
            };
        },

        computed: {
            sortedContacts() {
                let contacts = this.contacts;
                return _.sortBy(contacts, [(contact) => {
                    if(contact == this.selected) {
                        return Infinity;
                    }

                    return contact.unread;
                }]).reverse();
            },
        },

        methods: {
            startConversationWith(contact) {
                this.selected = contact;

                this.$store.commit('MESSAGES', 'reset');
                this.$store.commit('SELECTED_CONTACT', contact);

                this.allContacts = this.updateUnreadContact(contact, this.contacts, true);

                this.$store.dispatch('FETCH_MESSAGES', contact.id);
            },
        }
    }
</script>
<style lang="scss" scoped>
    .contact-list {
        flex: 2;
        max-height: 600px;
        overflow: scroll;
        border-left: 1px solid #a6a6a6;
        
        ul {
            list-style-type: none;
            padding-left: 0;
            li {
                display: flex;
                padding: 2px;
                border-bottom: 1px solid #aaaaaa;
                height: 80px;
                position: relative;
                cursor: pointer;
                &.selected {
                    background: #dfdfdf;
                }
                span.unread {
                    background: #82e0a8;
                    color: #fff;
                    position: absolute;
                    right: 11px;
                    top: 20px;
                    display: flex;
                    font-weight: 700;
                    min-width: 20px;
                    justify-content: center;
                    align-items: center;
                    line-height: 20px;
                    font-size: 12px;
                    padding: 0 4px;
                    border-radius: 3px;
                }
                .avatar {
                    flex: 1;
                    display: flex;
                    align-items: center;
                    img {
                        width: 35px;
                        border-radius: 50%;
                        margin: 0 auto;
                    }
                }
                .contact {
                    flex: 3;
                    font-size: 10px;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    p {
                        margin: 0;
                        &.name {
                            font-weight: bold;
                        }
                    }
                }
            }
        }
    }
</style>