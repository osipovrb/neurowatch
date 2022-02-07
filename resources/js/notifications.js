import Vue from 'vue';
import Notifications from 'vue-notification';

Vue.use(Notifications);

Vue.mixin({
    methods: {
        success(msg) {
            this.$notify({
                type: 'success',
                text: msg,
            });
        },
        warning(msg) {
            this.$notify({
                type: 'warn',
                text: msg,
            });
        },
        error(msg) {
            this.$notify({
                type: 'error',
                text: msg
            });
        }
    }
});
