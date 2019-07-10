export default {
    state: {
        message: {},
        messages: [],
    },

    getters: {
        message(state) {
            return state.message;
        },

        messages(state) {
            return state.messages;
        }
    },

    mutations: {
        MESSAGE(state, payload) {
            state.message = payload;
        },

        MESSAGES(state, payload) {
            if (payload === 'reset') {
                state.messages = [];
            }else if (state.messages.length > 0) {
                if (payload.length > 0) {
                    state.messages.unshift(...payload.reverse());
                } else {
                    state.messages.push(payload);
                }
            } else {
                state.messages = payload.reverse();
            }
        }
    },

    actions: {
        FETCH_MESSAGES(context, contactId) {
            axios.get(`/conversation/${contactId}`)
                .then(response => {
                    context.commit('MESSAGES', response.data.data);
                });
        },

        SEND_MESSAGE(context, data) {
            axios.post('/conversation/send', data).then(response => {
                context.commit('MESSAGE', response.data);
                context.commit('MESSAGES', response.data);
            });
        }
    }
}