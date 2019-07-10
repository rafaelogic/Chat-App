export default {
    state: {
        contact: {},
        contacts: [],
        selectedContact: {},
    },

    getters: {
        contact(state) {
            return state.contact;
        },

        contacts(state) {
            return state.contacts;
        },

        selectedContact(state) {
            return state.selectedContact;
        },
    },
    
    mutations: {
        CONTACT(state, payload) {
            state.contact = payload;
        },

        CONTACTS(state, payload) {
            state.contacts = payload;
        },

        SELECTED_CONTACT(state, payload) {
            state.selectedContact = payload;
        },
    },

    actions: {
        FETCH_CONTACTS(context) {
            axios.get('/contacts')
                .then(response => {
                    context.commit('CONTACTS', response.data);
                });
        },
        
    }
}