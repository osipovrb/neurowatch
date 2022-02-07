<template>
    <div class="list-group box-shadow mb-3">
        <a href="#" class="list-group-item list-group-item-action" @click.prevent="logout">Выход</a>
    </div>
</template>

<script>
    export default {
        methods: {
            async logout() {
                await this.$store.dispatch('logOut');
                this.success('Вы вышли из системы');
                this.$router.push({ name: 'auth.login' });
            },
        }
    }
</script>
