export default {
    methods: {
        updateUnreadContact(selectedContact, contacts, reset) {
            return this.contacts.map((contact) => {
                if (contact.id !== selectedContact.id) {
                    return contact;
                }

                if (reset) {
                    contact.unread = 0;
                } else {
                    contact.unread += 1;
                }

                return contact;
            })
        }
    }
}