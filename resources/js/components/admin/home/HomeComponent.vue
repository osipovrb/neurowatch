<template>
    <div class="card box-shadow">
        <div class="card-header">
            Neurowatch
        </div>
        <div class="card-body">
            Вы авторизованы под учетной записью &laquo;{{ user.name }}&raquo;
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            user: function() {
                return this.$store.getters.getUser;
            }
        }
    }
</script>
