<template>
    <div class="card box-shadow">
        <div class="card-header">
            Замки
            <button class="btn btn-outline-primary btn-sm float-right" title="Обновить" role="button" @click.prevent="getLocks" :disabled="loading">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        <div class="card-body">
            <vue-element-loading :active="loading" />
            <table v-if="!loading" class="table table-hover table-bordered mb-0">
                <thead>
                    <tr>
                        <th scope="col">Название замка</th>
                        <th scope="col">Состояние</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <lock-row v-for="lock in locks" :lock="lock" :key="lock.id" />
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                locks: [],
                loading: false,
            }
        },
        methods: {
            getLocks() {
                this.loading = true;
                this.locks = [];
                axios.get('/api/locks').then(response => {
                    this.locks = response.data;
                    this.loading = false;
                }).catch(e => {
                    this.error(e);
                    this.loading = false;
                });
            }
        },
        created() {
            window.Echo.private('locks')
                .listen('LocksUpdated', data => {
                    this.locks = data.locks;
                })
        },
        mounted() {
            this.getLocks();
        },
        destroyed() {
            window.Echo.leave('locks');
        }
    }
</script>
