<template>
    <div>
        <table class="table table-hover table-bordered mb-0">
            <thead>
                <tr>
                    <th scope="col">Предупреждения</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="warning in warnings" class="table-warning">
                    <td>{{ warning }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['warnings'],
    }
</script>
