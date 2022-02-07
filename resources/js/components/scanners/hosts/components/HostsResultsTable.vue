<template>
    <div>
        <div class="row mb-3">
            <div class="col-6">
                <b-form-input v-model="filter" type="search" placeholder="Поиск..." />
            </div>
        </div>
        <b-table :items="hosts" :fields="fields" :tbody-tr-class="rowClass" :filter="filter" bordered>
            <template #cell(hostname)="data">
                <router-link :to="{ name: 'scanners.neuro', query: { hostname: data.value }}">{{ data.value }}</router-link>
            </template>
            <template #cell(isOnline)="data">
                <i v-if="data.value" class="bi bi-lightbulb"></i>
                <i v-else class="bi bi-lightbulb-off"></i>
            </template>
        </b-table>
    </div>
</template>

<script>
    export default {
        props: ['hosts'],
        data() {
            return {
                fields: [
                    { label: 'Хост', key: 'hostname', sortable: true },
                    { label: 'IP', key: 'ip', sortable: true },
                    { label: 'Дерево', key: 'searchtree', sortable: true },
                    { label: 'Пользователь', key: 'username', sortable: true },
                    { label: '', key: 'isOnline', sortable: false },
                ],
                filter: '',
            }
        },
        methods: {
            rowClass(item, type) {
                if (!item || type !== 'row') return;
                return (item.isOnline) ? 'table-success' : 'table-danger';
            }
        }
    }
</script>
