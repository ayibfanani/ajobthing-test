
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('alert', require('./components/Alert.vue'));
Vue.component('posted-jobs', require('./components/PostedJobs.vue'));
Vue.component('submitted-jobs', require('./components/SubmittedJobs.vue'));
Vue.component('active-jobs', require('./components/ActiveJobs.vue'));
Vue.component('available-jobs', require('./components/AvailableJobs.vue'));
Vue.component('upgrade-box', require('./components/UpgradeBox.vue'));
Vue.component('add-job-form', require('./components/AddJobForm.vue'));
Vue.component('apply-form', require('./components/ApplyForm.vue'));
Vue.component('applier-lists', require('./components/ApplierLists.vue'));

const app = new Vue({
    el: '#app'
});
