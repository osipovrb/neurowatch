<template>
    <tr :key="user.id" @click="click">
        <th scope="row" class="pl-20">{{ user.id }}</th>
        <td>{{ user.username }}</td>
        <td>
            <div class="float-left">
                {{ user.name }}
            </div>
            <!--<div class="float-right">
                <x-links.edit href="{{ route('admin.users.edit', $user) }}" />
                <x-links.delete href="{{ route('admin.users.destroy', $user) }}" />
            </div>-->
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['user'],
        methods: {
            click() {
                this.$emit('clicked', this.user.id);
            }
        }
    }
</script>

<style scoped>
    tr {
        cursor: pointer;
    }
</style>
