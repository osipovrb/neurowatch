<template>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="card box-shadow">
                <div class="card-header">
                    Neurowatch
                </div>
                <div class="card-body">
                    <vue-element-loading :active="loading" />
                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <label for="username">Имя пользователя</label>
                            <input
                                id="username"
                                type="text"
                                class="form-control "
                                name="username"
                                value=""
                                autocomplete="username"
                                required
                                v-model="credentials.samaccountname"
                                :disabled="loading">
                        </div>

                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input
                                id="password"
                                type="password"
                                class="form-control "
                                name="password"
                                value=""
                                autocomplete="password"
                                required
                                v-model="credentials.password"
                                :disabled="loading">
                        </div>

                        <div class="form-group form-check">
                            <input class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="remember">
                            <label class="form-check-label" for="remember">
                                Запомнить меня
                            </label>
                        </div>

                        <button class="btn btn-primary float-left" type="submit" :disabled="loading">
                            Войти
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-md-4"></div>
    </div>


</template>

<script>

    export default {
        data() {
            return {
                loading: false,
                credentials: {
                    samaccountname: '',
                    password: '',
                }
            }
        },
        methods: {
            async submit() {
                this.loading = true;
                await this.$store.dispatch('logIn', this.credentials);
                this.loading = false;
                if (this.$store.getters.getAuthenticated) {
                    this.success('Добро пожаловать!');
                    this.$router.push({ name: 'admin.home' });
                } else {
                    this.credentials.password = '';
                    this.error('Не удалось войти в систему. Проверьте имя пользователя и пароль.');
                }
            }
        }
    }
</script>

<style scoped>
    .btn {
        min-width: 90px;
    }
</style>
