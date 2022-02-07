import Vue from 'vue';

Vue.component('app', require('./app.vue').default);

Vue.component('navpills', require('./components/partials/navpills/NavpillsComponent.vue').default);
Vue.component('logout-pill', require('./components/partials/navpills/LogoutPillComponent.vue').default);

Vue.component('login', require('./components/auth/LoginComponent.vue').default);

Vue.component('home', require('./components/admin/home/HomeComponent.vue').default);

Vue.component('users', require('./components/admin/users/UsersComponent.vue').default);
Vue.component('user-row', require('./components/admin/users/UserRowComponent.vue').default);
Vue.component('user-modal', require('./components/admin/users/UserModalComponent.vue').default);

Vue.component('host-groups', require('./components/admin/hostGroups/HostGroupsComponent.vue').default);
Vue.component('host-group-row', require('./components/admin/hostGroups/HostGroupRowComponent.vue').default);
Vue.component('host-group-modal', require('./components/admin/hostGroups/HostGroupModalComponent.vue').default);

Vue.component('locks', require('./components/admin/locks/LocksComponent.vue').default);
Vue.component('lock-row', require('./components/admin/locks/LockRowComponent.vue').default);

Vue.component('hosts', require('./components/scanners/hosts/HostsComponent.vue').default);
Vue.component('hosts-scan-button', require('./components/scanners/hosts/components/HostsScanButton.vue').default);
Vue.component('hosts-results-table', require('./components/scanners/hosts/components/HostsResultsTable.vue').default);
Vue.component('hosts-warnings-table', require('./components/scanners/hosts/components/HostsWarningsTable.vue').default);

Vue.component('neuro', require('./components/scanners/neuro/NeuroComponent.vue').default);
Vue.component('neuro-scan-button', require('./components/scanners/neuro/components/NeuroScanButton.vue').default);
Vue.component('neuro-results-table', require('./components/scanners/neuro/components/NeuroResultsTable.vue').default);
Vue.component('neuro-warnings-table', require('./components/scanners/neuro/components/NeuroWarningsTable.vue').default);
