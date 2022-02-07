<template>
    <div class="container">
        <vue-element-loading :active="loading" is-full-screen />
        <div class="row justify-content mt-3">
            <div v-if="!guest" class="col-3">
                <navpills />
            </div>
            <div :class="{ 'col-lg-9': (!guest), 'col-lg-12': (guest) }">
                <router-view />
            </div>
        </div>
        <notifications />
    </div>
</template>

<script>
	export default {
        data() {
            return {
                loading: 'hide',
            }
        },
		methods: {
            async checkAuth() {
                var redirectTo = (this.$route.name == 'auth.login') ? 'admin.home' : this.$route.name;
                await this.$store.dispatch('me');
                if (!this.guest) {
                    this.$router.push({ name: redirectTo });
                } else {
                    this.$router.push({ name: 'auth.login' });
                }
            }
		},
        computed: {
            guest() {
                return !this.$store.getters.getAuthenticated;
            }
        },
		async created() {
            this.loading = true;
            await this.checkAuth();
            this.loading = false;
		},
	};
</script>
