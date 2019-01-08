
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import 'babel-polyfill';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

import Vuetify from 'vuetify';
Vue.use(Vuetify);

import VueAlertify from 'vue-alertify';
Vue.use(VueAlertify);

import {HasError, AlertError, AlertSuccess } from 'vform';

const locale = 'en';
import VeeValidate, { Validator } from 'vee-validate';
Vue.use(VeeValidate, { delay: 250 });
import validateMessages from 'vee-validate/dist/locale/en';
Validator.localize(locale, validateMessages);
Vue.mixin({
    $_veeValidate: {
        validator: 'new'
    },
    methods: {
        async formHasErrors () {
            const valid = await this.$validator.validateAll();
            if (valid) {
                this.$validator.pause();
            }
            return !valid;
        }
    }
});

import VueI18n from 'vue-i18n';
Vue.use(VueI18n);
const i18n = new VueI18n({
    locale: 'en',
    messages: {}
});
import messages from './lang/en.json';
i18n.setLocaleMessage(locale, messages);

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component(AlertSuccess.name, AlertSuccess);
Vue.component('add-player', require('./components/AddPlayer.vue'));
Vue.component('players-list', require('./components/PlayersList.vue'));
Vue.component('scroll-up-button', require('./components/ScrollUpButton.vue'));

const app = new Vue({
    el: '#app',
    i18n
});
$('.fullpage-loader').remove();
$('#app').show();
