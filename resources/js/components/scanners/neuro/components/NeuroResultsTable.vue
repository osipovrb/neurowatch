<template>
    <div>
        <div class="row mb-3">
            <div class="col-6">
                <b-form-input v-model="filter" type="search" placeholder="Поиск..." />
            </div>
        </div>
        <b-table :items="results" :fields="fields" :filter="filter" bordered>
            <template #cell(file)="data">
                <a href="#"
                    :title="data.value.uri"
                    v-clipboard:copy="data.value.uri"
                    v-clipboard:success="onCopy"
                    >{{ data.value.filename }}</a>
            </template>
            <template #cell(probe)="data">
                {{ Math.round(data.value[0] * 100) }}%
            </template>
        </b-table>
    </div>
</template>

<script>
    export default {
        props: ['results'],
        data() {
            return {
                fields: [
                    { label: 'Файл', key: 'file', sortable: true },
                    { label: 'Оценка', key: 'probe', sortable: true },
                ],
                filter: '',
            }
        },
        methods: {
            onCopy() {
                this.success('Ссылка скопирована в буфер обмена');
            }
        }
    }
</script>
