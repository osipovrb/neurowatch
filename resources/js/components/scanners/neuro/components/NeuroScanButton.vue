<template>
    <div>
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-4">
                <b-form-group label="Имя хоста" label-for="hostname">
                    <b-form-input
                        id="hostname"
                        v-model="hostname"
                        type="text"
                        ></b-form-input>
                </b-form-group>
                <b-form-group label="Порог срабатывания (%)" label-for="threshold">
                    <b-form-input
                        id="threshold"
                        v-model="threshold"
                        type="number"
                        min="50"
                        max="100"
                        ></b-form-input>
                </b-form-group>
                <button class="btn btn-primary" @click="start">Сканировать</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                hostname: '',
                threshold: 50,
            }
        },
        methods: {
            start() {
                if (!this.hostname) {
                    this.error('Поле "Имя хоста" не может быть пустым');
                    return;
                }
                if (!this.threshold || this.threshold < 50 || this.threshold > 100) {
                    this.error('Поле "Порог срабатывания" должно иметь значение от 50 до 100');
                    return;
                }
                if (hostname) {
                    this.$emit('clicked');
                    axios.post('/api/scanners/neuro/start', { hostname: this.hostname, threshold: this.threshold })
                        .then(response => this.$emit('started'))
                        .catch(e => this.error(e));
                }
            },
        },
        created() {
            if (this.$route.query.hostname) {
                this.hostname = this.$route.query.hostname;
            }
        },
    }
</script>
