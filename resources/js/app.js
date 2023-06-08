import './bootstrap';
window.Vue = require('vue').default;


import Vue from 'vue';
window.Vue = Vue;

import App from'./components/App.vue';
Vue.component('app', App);

const app = new Vue({
    el:'#app'
});
