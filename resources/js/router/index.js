import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/views/Home.vue'
import Stories from '@/views/Stories.vue'
import Story from '@/views/Story.vue'
import About from '@/views/About.vue'
import Auth from '@/views/Auth.vue'
import { useAuth } from '@/composables/Auth.js'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/stories',
        name: 'stories',
        component: Stories
    },
    {
        path: '/story/:id',
        name: 'Story',
        component: Story,
        props : true
    },
    {
        path: '/login',
        name: 'Login',
        component: Auth
    },
    {
        path: '/about',
        name: 'About',
        component: About,
        meta: { requiresAuth: true }
      }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Navigation guard
router.beforeEach((to, from, next) => {
    const { isAuthenticated } = useAuth()
    
    if (to.meta.requiresAuth && !isAuthenticated.value) {
      next({ path: '/login', query: { redirect: to.fullPath } })
    } else {
      next()
    }
})

export default router
