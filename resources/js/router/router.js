import { createRouter, createWebHistory } from "vue-router";
import HomeRoute from "../views/HomeRoute.vue";
import TestRoute from "../views/TestRoute.vue";
import Login from "../views/user/Login.vue";
import Register from "../views/user/Register.vue";
import Personal from "../views/user/Personal.vue";

const routes = [
    {
        path: '/',
        component: HomeRoute,
    },
    {
        path: '/test',
        component: TestRoute,
        name: 'test'
    },
    {
        path: '/user/login', component: Login, name: 'user.login'
    },
    {
        path: '/user/register', component: Register, name: 'user.register'
    },
    {
        path: '/user/personal', component: Personal, name: 'user.personal'
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
                localStorage.key('x-xsrf-token') ? localStorage.removeItem('x-xsrf-token') : '';
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

    if (to.name === 'user.login' || to.name === 'user.register' && token) {
        return next({
            name: 'user.personal'
        })
    }

    next();
})

export default router;
