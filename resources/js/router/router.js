import { createRouter, createWebHistory } from "vue-router";
import Login from "../views/user/Login.vue";
import Register from "../views/user/Register.vue";
import Personal from "../views/user/Personal.vue";
import Index from "../views/Index.vue";
import Page from "../views/Page.vue";

const routes = [
    {
        path: '/',
        component: Index,
        name: 'index',
    },
    {
        path: '/page',
        component: Page,
        name: 'page'
    },
    {
        path: '/user/login',
        component: Login,
        name: 'user.login'
    },
    {
        path: '/user/register',
        component: Register,
        name: 'user.register'
    },
    {
        path: '/personal',
        component: Personal,
        name: 'user.personal'
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    axios.get('/api/user')
        .catch(e => {
            if (e.response.status === 401) {
                localStorage.key('x_xsrf_token') ? localStorage.removeItem('x_xsrf_token') : '';
            }
        });

    const token = localStorage.getItem('x_xsrf_token');

    if (!token) {
        if (to.name === 'user.login' || to.name === 'user.register') {
            return next();
        } else {
            return next({
                name: 'user.login'
            })
        }
    }

    if (to.name === 'user.login' || to.name === 'user.register' || !to.name && token) {
        return next({
            name: 'user.personal'
        })
    }

    next();
})

export default router;
