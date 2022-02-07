<template>
    <b-modal id="hostGroupModal" :ok-disabled="loading" @ok="submit" :title="(this.id) ? 'Редактирование' : 'Добавление'">
        <vue-element-loading :active="loading" />
        <b-form @submit.stop.prevent="submit">

            <b-form-group label="Название" label-for="hostGroup.title">
                <b-form-input
                    id="hostGroup.title"
                    v-model="hostGroup.title"
                    type="text"
                    ></b-form-input>
            </b-form-group>

            <b-form-group label="Дерево поиска" label-for="hostGroup.searchtree">
                <b-form-textarea
                    id="hostGroup.searchtree"
                    v-model="hostGroup.searchtree"
                    spellcheck="false"
                    ></b-form-textarea>
            </b-form-group>

        </b-form>
    </b-modal>
</template>

<script>
    export default {
        props: [ 'id' ],
        data() {
            return {
                hostGroup: {},
                loading: false,
            }
        },
        methods: {
            get() {
                this.hostGroup = {};
                this.loading = true;
                axios.get('/api/host_groups/' + this.id).then(response => {
                    this.hostGroup = response.data.hostGroup;
                    this.loading = false;
                }).catch(e => {
                    this.error(e);
                });
            },
            submit(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.loading = true;
                if (this.id) {
                    var url = '/api/host_groups/' + this.id;
                    var method = 'patch';
                    var emit = 'updated';
                } else {
                    var url = '/api/host_groups/';
                    var method = 'post';
                    var emit = 'stored';
                }
                this.post(url, method, emit);
            },
            post(url, method, emit) {
                axios[method](url, this.hostGroup).then(response => {
                    this.loading = false;
                    this.$emit(emit, response.data.hostGroup || this.hostGroup);
                }).catch(e => {
                    this.error(e);
                    this.loading = false;
                });
            }
        },
        mounted() {
            this.$root.$on('bv::modal::show', (bvEvent, modalId) => {
                this.hostGroup = {};
                if (this.id) {
                    this.get();
                }
            });
        }
    }
</script>
