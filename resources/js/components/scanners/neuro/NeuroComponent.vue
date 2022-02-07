<template>
    <div>
        <div v-if="errorMessage" class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">Сканер остановлен</h5>
                <p class="card-text">{{ errorMessage }}</p>
            </div>
        </div>
        <div class="card box-shadow mb-3">
            <div class="card-header">
                Нейросетевой сканер
            </div>
            <div class="card-body">
                <vue-element-loading :active="isPageLoading" />
                <div v-if="isScannerRunning === true">
                    <div class="row">
                        <div class="col-12">
                            <span>Текущий путь: {{ uri }}</span><br />
                            <hr />
                            <span>{{ operation }}</span><br />
                            <span><i class="bi bi-stopwatch" /> {{ elapsedTime }}</span>
                            <hr />
                            <span>Хост: {{ info.hostname }}</span><br />
                            <span>IP: {{ info.ip }}</span><br />
                            <span>Пользователь: {{ info.username }}</span></br />
                        </div>
                    </div>
                </div>
                <div v-if="isScannerRunning === false">
                    <neuro-scan-button @clicked="scanButtonClicked()" @started="scanStarted()" />
                </div>
            </div>
        </div>

        <b-card v-if="isScannerRunning === true" no-body class="box-shadow bg-white">
            <b-tabs card active-nav-item-class="bg-white border-bottom-color-white">
                <b-tab title="Результаты" active>
                    <b-card-text>
                        <neuro-results-table :results="results" />
                    </b-card-text>
                </b-tab>
                <b-tab>
                    <template #title>
                        Предупреждения <b-badge v-if="warnings.length > 0" variant="warning">{{ warnings.length }}</b-badge>
                    </template>
                    <b-card-text>
                        <neuro-warnings-table v-if="warnings.length > 0" :warnings="warnings" />
                        <p v-if="warnings.length == 0" class="text-center">Предупреждений нет</p>
                    </b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                operation: '--',
                results: [],
                timer: 0,
                uri: '--',
                warnings: [],
                errorMessage: '',

                info: {
                    hostname: '--',
                    ip: '--',
                    username: '--',
                },

                isScannerRunning: null,
                isPageLoading: true,
            }
        },
        computed: {
            scanIsDone: function() {
                return this.operation == 'Сканирование завершено';
            },
            elapsedTime: function() {
                const hours = Math.floor(this.timer / 60 / 60);
                const minutes = Math.floor(this.timer / 60) - (hours * 60);
                const seconds = Math.floor(this.timer % 60);
                return [
                    hours.toString().padStart(2, '0'),
                    minutes.toString().padStart(2, '0'),
                    seconds.toString().padStart(2, '0'),
                ].join(':');
            },
        },
        methods: {
            scanButtonClicked() {
                this.isPageLoading = true;
            },
            scanStarted() {
                this.isPageLoading = false;
                this.isScannerRunning = true;
                this.info('Запуск сканера...');
                this.operation = 'Запуск сканера...';
            },
            getHistory() {
                axios.get('/api/scanners/neuro/getHistory').then(response => {
                    this.results = response.data.results.concat(this.results);
                    this.warnings = response.data.warnings.concat(this.warnings);
                    this.operation = response.data.operation;
                    this.uri = response.data.uri;
                    this.timer = response.data.timer;
                    this.info = response.data.info;
                    if (response.data.error) {
                        this.error(response.data.error);
                        this.errorMessage = response.data.error;
                    }
                });
            }
        },
        mounted() {
            axios.get('/api/scanners/neuro/is_locked').then(response => {
                this.isScannerRunning = Boolean(response.data);
                this.isPageLoading = false;
                if (this.isScannerRunning) {
                    this.getHistory();
                }
            });
        },
        created() {
            window.Echo.private('neuro_scanner')
                .listen('NeuroScanner\\Error', data => {
                    this.error(data.error);
                    this.errorMessage = data.error;
                })
                .listen('NeuroScanner\\Operation', data => {
                    this.operation = data.operation;
                })
                .listen('NeuroScanner\\Result', data => {
                    this.results.push(data.result);
                })
                .listen('NeuroScanner\\Timer', data => {
                    this.timer = data.timer;
                })
                .listen('NeuroScanner\\Uri', data => {
                    this.uri = data.uri;
                })
                .listen('NeuroScanner\\Warning', data => {
                    this.warnings.push(data.warning);
                })
                .listen('NeuroScanner\\Info', data => {
                    this.info = data.info;
                });
        },
        destroyed() {
            window.Echo.leave('neuro_scanner');
        }
    }
</script>

<style>
    .border-bottom-color-white {
        border-bottom-color: #fff !important;
    }
</style>
