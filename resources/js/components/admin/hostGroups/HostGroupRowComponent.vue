<template>
    <tr :key="hostGroup.id" @click="click">
        <td>{{ hostGroup.title }}</td>
        <td>
            <div class="float-left">
                {{ hostGroup.searchtree }}
            </div>
            <div class="float-right">
                <button class="btn btn-sm btn-danger hover-show" title="Удалить" @click.stop="destroy">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['hostGroup'],
        methods: {
            click() {
                this.$emit('clicked', this.hostGroup.id);
            },
            destroy() {
                if (confirm('Вы уверены?')) {
                    axios.delete('/api/host_groups/' + this.hostGroup.id).then(response => {
                        this.$emit('destroyed', this.hostGroup);
                    }).catch(error => {
                        this.error(error.message);
                    });
                }
            }
        }
    }
</script>

<style scoped>
    tr {
        cursor: pointer;
    }
    tr .hover-show {
        opacity: 0;
    }
    tr:hover .hover-show {
        opacity: 1;
    }
</style>
