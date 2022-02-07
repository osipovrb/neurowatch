import axios from 'axios';

export default {
    state: {
        authenticated: false,
        user: null,
    },
	getters: {
		getAuthenticated(state) {
            return state.authenticated;
        },
        getUser(state) {
            return state.user;
        }
	},
	mutations: {
		setAuthenticated(state, value) {
            state.authenticated = value;
        },
        setUser(state, value) {
            state.user = value;
        }
	},
	actions: {
        async logIn(context, credentials) {
            try {
                await axios.get('/sanctum/csrf-cookie');
                await axios.post('/api/auth/login', credentials);
                return context.dispatch('me');
            } catch(e) {
                return false;
            }
        },
        async logOut(context) {
            await axios.delete('/api/auth/logout');
            context.commit('setAuthenticated', false);
            context.commit('setUser', null);
        },
        async me(context) {
            await axios.get('/api/auth/me').then((response) => {
                context.commit('setAuthenticated', true);
                context.commit('setUser', response.data.user);
            }).catch(() => {
                context.commit('setAuthenticated', false);
                context.commit('setUser', null);
            });
        },
	}
}
