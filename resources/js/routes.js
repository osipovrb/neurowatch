export const routes = [
	{
		path: '/',
		redirect: { name: 'auth.login' }
	}, {
		path: '/auth/login',
		name: 'auth.login',
		component: require('./components/auth/LoginComponent.vue').default,
	}, {
        path: '/admin/home',
        name: 'admin.home',
        component: require('./components/admin/home/HomeComponent.vue').default,
    }, {
        path: '/admin/users',
        name: 'admin.users',
        component: require('./components/admin/users/UsersComponent.vue').default,
    }, {
        path: '/admin/host_groups',
        name: 'admin.host_groups',
        component: require('./components/admin/hostGroups/HostGroupsComponent.vue').default,
    }, {
        path: '/admin/locks',
        name: 'admin.locks',
        component: require('./components/admin/locks/LocksComponent.vue').default,
    }, {
        path: '/scanners/hosts',
        name: 'scanners.hosts',
        component: require('./components/scanners/hosts/HostsComponent.vue').default,
    }, {
        path: '/scanners/neuro',
        name: 'scanners.neuro',
        component: require('./components/scanners/neuro/NeuroComponent.vue').default,
    }
];
