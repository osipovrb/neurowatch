<template>
    <b-modal id="userModal" :ok-disabled="loading" @ok="submit" :title="user && user.username || '...'">
        <vue-element-loading :active="loading" />
        <b-form v-if="user" @submit.stop.prevent="submit">

            <b-form-group label="ID" label-for="user.id">
                <b-form-input
                    id="user.id"
                    v-model="user.id"
                    type="text"
                    disabled
                    ></b-form-input>
            </b-form-group>

            <b-form-group label="Имя пользователя" label-for="user.username">
                <b-form-input
                    id="user.username"
                    v-model="user.username"
                    type="text"
                    disabled
                    ></b-form-input>
            </b-form-group>

            <b-form-group label="Полное имя" label-for="user.name">
                <b-form-input
                    id="user.name"
                    v-model="user.name"
                    type="text"
                    ></b-form-input>
            </b-form-group>

            <b-form-group label="GUID" label-for="user.guid">
                <b-form-input
                    id="user.guid"
                    v-model="user.guid"
                    type="text"
                    disabled
                    ></b-form-input>
            </b-form-group>

            <b-form-group label="Домен" label-for="user.domain">
                <b-form-input
                    id="user.domain"
                    v-model="user.domain"
                    type="text"
                    disabled
                    ></b-form-input>
            </b-form-group>

        </b-form>

    </b-modal>
</template>

<script>
    export default {
        props: [ 'id', 'show' ],
        data() {
            return {
                user: {},
                loading: false,
            }
        },
        methods: {
            get() {
                this.user = {};
                this.loading = true;
                if (this.id) {
                    axios.get('/api/users/' + this.id).then(response => {
                        this.user = response.data.user;
                        this.loading = false;
                    }).catch(e => {
                        this.error(e);
                    });
                }
            },
            submit(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.loading = true;
                axios.patch('/api/users/' + this.id, this.user).then(response => {
                    this.loading = false;
                    this.$emit('saved', this.user);
                }).catch(e => {
                    this.error(e);
                    this.loading = false;
                });
            },
        },
        mounted() {
            this.$root.$on('bv::modal::show', (bvEvent, modalId) => {
                this.get();
            });
        }
    }
</script>
