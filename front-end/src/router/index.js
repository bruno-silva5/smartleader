import { createRouter, createWebHistory } from 'vue-router'
import SignIn from '../components/auth/SignIn.vue'
import SignUp from '../components/auth/SignUp.vue'
import TaskList from '../components/tasks/TaskList.vue'
import { useAuthStore } from '../store/auth'

const routes = [
  {
    path: '/',
    redirect: '/tasks'
  },
  {
    path: '/signin',
    name: 'SignIn',
    component: SignIn
  },
  {
    path: '/signup',
    name: 'SignUp',
    component: SignUp
  },
  {
    path: '/tasks',
    name: 'Tasks',
    component: TaskList,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/signin')
  } else {
    next()
  }
})

export default router
