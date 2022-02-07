<template>
    <tr :key="lock.id">
        <td>{{ lock.name }}</td>
        <td>
            <span v-if="lock.locked" class="text-danger">Замок установлен</span>
            <span v-else class="text-success">Замок снят</span>
        </td>
        <td>
            <button :disabled="!lock.locked || loading" class="btn btn-sm btn-danger" @click.prevent="unlock">
                <span v-if="loading">Снимаю...</span>
                <span v-else>Снять замок</span>
            </button>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['lock'],
        data() {
            return {
                loading: false,
            }
        },
        methods: {
            unlock() {
                this.loading = true;
                axios.delete('/api/locks/' + this.lock.id).then(response => {
                    this.loading = false;
                }).catch(e => {
                    this.error(e);
                    this.loading = false;
                });
            }
        },
    }
</script>
