<template>
    <div class="card-body">
        <div v-for="row in hostGroupsRows" class="row">
            <div v-for="hostGroup in row" class="col-lg-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" v-model="selectedHostGroups" :value="hostGroup.id" :id="id(hostGroup.id)">
                    <label class="form-check-label" :for="id(hostGroup.id)">
                        {{ hostGroup.title }}
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <button class="btn btn-primary" @click="start">Запустить сканирование</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                hostGroups: [],
                selectedHostGroups: [],
            }
        },
        methods: {
            start() {
                const prompt = 'Вы не выбрали ни одно дерево поиска. Поиск будет осуществлен по всем деревьям. Это может занять продолжительное время. Продолжить?';
                const selected = (this.selectedHostGroups.length > 0);
                const notSelected = (this.selectedHostGroups.length == 0);
                if (selected || (!selected && confirm(prompt))) {
                    this.$emit('clicked');
                    axios.post('/api/scanners/hosts/start', { hostGroups: this.selectedHostGroups });
                }
            },
            id: function(id) {
                return `hostGroupCheckbox${id}`;
            },
        },
        computed: {
            hostGroupsRows: function() {
                if (!this.hostGroups) return [];
                let rows = [];
                let currentRow = 0;
                let counter = 0;
                this.hostGroups.forEach((item) => {
                    if (counter >= 3) {
                        currentRow += 1;
                        counter = 0;
                    }
                    if (!rows[currentRow]) {
                        rows[currentRow] = [];
                    }
                    rows[currentRow].push(item);
                    counter++;
                });
                return rows;
            },
        },
        mounted() {
            axios.get('/api/host_groups').then(response => {
                this.hostGroups = response.data.hostGroups;
                this.$emit('loaded');
            });
        },
    }
</script>
