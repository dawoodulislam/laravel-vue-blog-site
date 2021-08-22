require('./bootstrap');
import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import FirstPage from './component/pages/firstPage.vue'
import SecondPage from './component/pages/secondPage.vue'
import Home from './component/pages/home.vue'
import tags from './admin/pages/tags.vue'
import category from './admin/pages/category.vue'
import adminUser from './admin/pages/adminusers.vue'
import login from './admin/pages/adminLogin.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/login',
        name: 'login',
        component: login
    },
    {
        path: '/tags',
        name: 'tags',
        component: tags
    },
    {
        path: '/category',
        name: 'category',
        component: category
    },
    {
        path: '/adminUser',
        name: 'adminUser',
        component: adminUser
    },
    {
        path: '/first-page',
        name: 'first-page',
        component: FirstPage
    },
    {
        path: '/second-page',
        name: 'second-page',
        component: SecondPage
    }
]

export default new Router({
    mode: 'history',
    routes
})