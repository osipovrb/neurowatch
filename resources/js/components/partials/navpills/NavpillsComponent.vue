<template>
    <div>
        <div class="list-group box-shadow mb-3">
            <div class="card mb-n1">
                <div class="card-header">Администратор</div>
            </div>
            <router-link :to="{name: 'admin.home'}" class="list-group-item list-group-item-action">Главная</router-link>
            <router-link :to="{name: 'admin.users'}" class="list-group-item list-group-item-action">Пользователи</router-link>
            <router-link :to="{name: 'admin.host_groups'}" class="list-group-item list-group-item-action">Деревья поиска</router-link>
            <router-link :to="{name: 'admin.locks'}" class="list-group-item list-group-item-action">Замки</router-link>
        </div>

        <div class="list-group box-shadow mb-3">
            <div class="card mb-n1">
                <div class="card-header">Сканеры</div>
            </div>
            <router-link :to="{name: 'scanners.hosts'}" class="list-group-item list-group-item-action">Хосты</router-link>
            <router-link :to="{name: 'scanners.neuro'}" class="list-group-item list-group-item-action" exact-path>Нейросеть</router-link>
        </div>

        <hr />

        <logout-pill></logout-pill>
    </div>

</template>
