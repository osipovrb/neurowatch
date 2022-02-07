<template>
    <div>
        <div class="row mb-3">
            <div class="col-6">
                <b-form-input v-model="filter" type="search" placeholder="Поиск..." />
            </div>
        </div>
        <b-table :items="warnings" :fields="fields" tbody-tr-class="table-warning" :filter="filter" bordered />
    </div>
</template>

<script>
    export default {
        props: ['warnings'],
        data() {
            return {
                fields: [
                    { label: 'Хост', key: 'hostname', sortable: true },
                    { label: 'Предупреждение', key: 'warning', sortable: false },
                ],
                filter: '',
            }
        },
    }
</script>
