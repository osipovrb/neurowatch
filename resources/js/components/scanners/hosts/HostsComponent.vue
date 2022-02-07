<template>
    <div>

        <div class="card box-shadow mb-3">
            <div class="card-header">
                Сканер хостов
            </div>
            <div class="card-body">
                <vue-element-loading :active="isPageLoading" />
                <div v-if="isScannerRunning === true">
                    <b-progress :max="hostsCount" height="2rem">
                        <b-progress-bar :value="hostsScanned" />
                    </b-progress>
                    <div class="row">
                        <div class="col-9">
                            <span>{{ scanner.operation }}</span><br />
                            <span>Просканировано {{ progress }}% ({{ hostsScanned }} из {{ hostsCount }})</span><br />
                            <span><i class="bi bi-stopwatch" /> {{ elapsedTime }}</span>
                        </div>

                        <div class="col-3">
                            <span class="text-success"><i class="bi bi-lightbulb"></i> онлайн: {{ hostsOnlineCount }}</span><br />
                            <span class="text-danger"><i class="bi bi-lightbulb-off"></i> оффлайн: {{ hostsOfflineCount }}</span><br />
                            <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> предупреждения: {{ warningsCount }}</span></br />
                        </div>
                    </div>
                </div>
                <div v-if="isScannerRunning === false">
                    <vue-element-loading :active="!isScanButtonLoaded" />
                    <hosts-scan-button @loaded="scanButtonLoaded()" @clicked="scanButtonClicked()" />
                </div>
            </div>
        </div>

        <b-card v-if="isScannerRunning === true" no-body class="box-shadow bg-white">
            <b-tabs card active-nav-item-class="bg-white border-bottom-color-white">
                <b-tab title="Результаты" active>
                    <b-card-text>
                        <hosts-results-table :hosts="scanner.results" />
                    </b-card-text>
                </b-tab>
                <b-tab>
                    <template #title>
                        Предупреждения <b-badge v-if="warningsCount > 0" variant="warning">{{ warningsCount }}</b-badge>
                    </template>
                    <b-card-text>
                        <hosts-warnings-table v-if="warningsCount > 0" :warnings="scanner.warnings" />
                        <p v-if="warningsCount == 0" class="text-center">Предупреждений нет</p>
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
                scanner: {
                    hostsCount: 0,
                    operation: '',
                    results: [],
                    warnings: [],
                    timer: 0,
                },

                timer: 0,

                isScannerRunning: null,
                isPageLoading: true,
                isScanButtonLoaded: false,
            }
        },
        computed: {
            hostsCount: function() {
                return this.scanner.hostsCount - this.scanner.warnings.length;
            },
            hostsScanned: function() {
                return this.scanner.results.length;
            },
            warningsCount: function() {
                return this.scanner.warnings.length;
            },
            progress: function() {
                const result = Math.round(this.hostsScanned * 100 / this.hostsCount);
                return (isNaN(result)) ? 0 : result;
            },
            hostsOnlineCount: function() {
                return this.scanner.results.filter(host => host.isOnline).length;
            },
            hostsOfflineCount: function() {
                return this.scanner.results.filter(host => !host.isOnline).length;
            },
            scanIsDone: function() {
                return this.scanner.operation == 'Сканирование завершено';
            },
            elapsedTime: function() {
                const seconds = (this.scanner.timer % 60).toString().padStart(2, '0');
                const minutes = (Math.floor(this.scanner.timer / 60)).toString().padStart(2, '0');
                return `${minutes}:${seconds}`;
            },
        },
        methods: {
            scanButtonLoaded() {
                this.isScanButtonLoaded = true;
                this.timer = 0;
                this.timerHandler = setInterval(() => this.timer += 1, 1000);
            },
            scanButtonClicked() {
                this.isPageLoading = true;
            },
        },
        watch: {
            scanner: function(newScanner) {
                if (newScanner.operation == 'Сканирование завершено') {
                    clearInterval(this.timerHandler);
                }
            }
        },
        mounted() {
            axios.get('/api/scanners/hosts/is_locked').then(response => {
                this.isScannerRunning = Boolean(response.data);
                this.isPageLoading = false;
            });
        },
        created() {
            window.Echo.private('hosts_scanner')
                .listen('HostsScannerUpdated', data => {
                    this.isPageLoading = false;
                    this.isScannerRunning = true;
                    this.scanner = data.scanner;
                })
        },
        destroyed() {
            window.Echo.leave('hosts_scanner');
        }
    }
</script>

<style>
    .border-bottom-color-white {
        border-bottom-color: #fff !important;
    }
</style>
