   import Vue from 'vue'
    import VueRouter from 'vue-router'

    Vue.use(VueRouter)

    import App from './components/App'
    import Dashboard from './components/Dashboard'
    import Login from './components/Login'
    import Register from './components/Register'
    import Home from './components/Welcome'

    const router = new VueRouter({
        mode: 'history',
        routes: [
            {
                path: '/',
                name: 'home',
                component: Home
            },
            {
                path: '/login',
                name: 'login',
                component: Login
            },
            {
                path: '/register',
                name: 'register',
                component: Register
            },
            {
                path: '/dashboard',
                name: 'dashboard',
                component: Dashboard
            }
        ],
    });

    const app = new Vue({
        el: '#app',
        components: { App },
        router,
    });
