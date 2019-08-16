/**
 * @name:          base.js
 * @description:   This script imports all of the globally required javascript files. Anything used across the entire site is imported here.
 */

// Vue.js
import Vue from "vue";
window.Vue = Vue;

// Vue Router
import VueRouter from "vue-router";
window.VueRouter = VueRouter;
Vue.use(window.VueRouter);

// Vuex
import Vuex from "vuex";
window.Vuex = Vuex;
Vue.use(window.Vuex);

// Axios
import axios from "axios";
window.axios = axios;