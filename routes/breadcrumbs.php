<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Главная
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Neurowatch', route('home'));
});

// Главная > Пользователи
Breadcrumbs::for('admin.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Учетные записи', route('admin.users.index'));
});

// Главная > Пользователи > [Пользователь]
Breadcrumbs::for('admin.users.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('admin.users.index');
    $trail->push($user->name, route('admin.users.edit', $user));
});
