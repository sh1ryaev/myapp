import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/sign-up',
        name: 'SignUp',
        component: () => import('../components/SignUp.vue')
    },
    {
        path: '/log-in',
        name: 'LogIn',
        component: () => import('../components/LogIn.vue')
    },
    {
        path: '/profile',
        name: 'UserProfile',
        component: () => import('../components/UserProfile.vue')
    },
    {
        path:'/post',
        name:'PostView',
        component: () => import('../components/PostView.vue')
    },
    {
        path: '/',
        name: 'HomePage',
        component: () => import('../components/HomePage.vue')
    }
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

export default router
