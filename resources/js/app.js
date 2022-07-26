

require('./bootstrap');

import router from "./routes";
import VueRouter from "vue-router";
import Vuex from "Vuex";
import Vue from "vue";
import Index from "./Index"
import moment from 'moment';
import FatalError from "./shared/components/FatalError";
import Success from "./shared/components/Success";
import StarRating from "./shared/components/StarRating";
import ValidationErrors from "./shared/components/ValidationErrors";
import storeDefinition from "./store";
import axios from "axios";


window.Vue = require('vue').default;

Vue.use(VueRouter);
Vue.use(Vuex);

Vue.filter("fromNow",value => moment(value).fromNow());

Vue.component('star-rating', StarRating);
Vue.component('fatal-error', FatalError);
Vue.component('success', Success);
Vue.component('v-errors', ValidationErrors);

const store = new Vuex.Store(storeDefinition);

window.axios.interceptors.response.use(
    response => {
        return response;
    },
    error => {
        if(401 === error.response.status){
            store.dispatch("logout");
        }

        return Promise.reject(error);
    }
);

const app = new Vue({
    el: '#app',
    router,
    store,
    components:{
        "index": Index,
    },

   async beforeCreate(){
        this.$store.dispatch("loadStoredState");
        this.$store.dispatch("loadUser");


    }
});

