<template>
    <div class="card box-shadow">
        <div class="card-header">
            Учетные записи
            <button class="btn btn-outline-primary btn-sm float-right" title="Обновить" role="button" @click.prevent="getUsers" :disabled="loading">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        <div class="card-body">
            <vue-element-loading :active="loading" />
            <table v-if="users.length > 0" class="table table-hover table-bordered mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Логин</th>
                        <th scope="col">Ф.И.О.</th>
                    </tr>
                </thead>
                <tbody>
                    <user-row v-for="user in users" :user="user" :key="user.id" @clicked="openModal"></user-row>
                </tbody>
            </table>
            <user-modal :id="modalUserId" @saved="modalSaved" />
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                loading: false,
                modalUserId: null,
            }
        },
        methods: {
            getUsers() {
                this.loading = true;
                axios.get('/api/users').then(response => {
                    this.users = response.data.users;
                    this.loading = false;
                }).catch(e => {
                    this.error(e);
                    this.users = [];
                    this.loading = false;
                });
            },
            openModal(id) {
                this.modalUserId = id;
                this.$nextTick(() => {
                    this.$bvModal.show('userModal');
                });
            },
            closeModal() {
                this.$nextTick(() => {
                    this.$bvModal.hide('userModal');
                });
            },
            modalSaved(savedUser) {
                this.users = this.users.map(user => user.id == savedUser.id ? savedUser : user);
                this.closeModal();
                this.success('Учетная запись ' + savedUser.username + ' обновлена');
            }
        },
        mounted() {
            this.getUsers();
        },
    }
</script>
