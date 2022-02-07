<template>
    <div class="card box-shadow">
        <div class="card-header">
            Деревья поиска
            <div class="float-right">
                <button class="btn btn-success btn-sm" title="Добавить" role="button" @click.prevent="openModal(null)">
                    <i class="bi bi-plus-lg"></i>
                </button>
                <button class="btn btn-outline-primary btn-sm" title="Обновить" role="button" @click.prevent="getHostGroups" :disabled="loading">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <vue-element-loading :active="loading" />
            <table v-if="hostGroups && hostGroups.length > 0" class="table table-hover table-bordered mb-0">
                <thead>
                    <tr>
                        <th scope="col">Название</th>
                        <th scope="col">Дерево поиска</th>
                    </tr>
                </thead>
                <tbody>
                    <host-group-row v-for="hostGroup in hostGroups" :hostGroup="hostGroup" :key="hostGroup.id" @clicked="openModal" @destroyed="destroyed" />
                </tbody>
            </table>
            <div v-else-if="!loading" class="alert alert-primary mb-0" role="alert">
                Таблица деревьев поиска пуста
            </div>
            <host-group-modal :id="modalHostGroupId" @stored="modalStored" @updated="modalUpdated" />
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                hostGroups: [],
                loading: false,
                modalHostGroupId: null,
            }
        },
        methods: {
            getHostGroups() {
                this.loading = true;
                axios.get('/api/host_groups').then(response => {
                    this.hostGroups = response.data.hostGroups;
                    this.loading = false;
                }).catch(e => {
                    this.error(e);
                    this.hostGroups = [];
                    this.loading = false;
                });
            },
            openModal(id) {
                this.modalHostGroupId = id;
                this.$nextTick(() => {
                    this.$bvModal.show('hostGroupModal');
                });
            },
            closeModal() {
                this.$nextTick(() => {
                    this.$bvModal.hide('hostGroupModal');
                });
            },
            modalStored(storedHostGroup) {
                this.hostGroups.push(storedHostGroup);
                this.closeModal();
                this.success('Добавлено новое дерево поиска "' + storedHostGroup.title + '"');
            },
            modalUpdated(updatedHostGroup) {
                this.hostGroups = this.hostGroups.map(hostGroup => hostGroup.id == updatedHostGroup.id ? updatedHostGroup : hostGroup);
                this.closeModal();
                this.success('Дерево поиска "' + updatedHostGroup.title + '" обновлено');
            },
            destroyed(destroyedHostGroup) {
                this.hostGroups = this.hostGroups.filter((hostGroup) => hostGroup.id != destroyedHostGroup.id);
                this.success('Дерево поиска "' + destroyedHostGroup.title + '" удалено');
            }
        },
        mounted() {
            this.getHostGroups();
        },
    }
</script>
