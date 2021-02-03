require('./bootstrap');

import Vue from 'vue';

Vue.component(
    'posts-index',
    require('./components/Posts/index').default
)

const app = new Vue({
    el: '#app'
});
