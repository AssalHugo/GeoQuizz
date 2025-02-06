import { createRouter, createWebHistory } from 'vue-router'
import DashboardView from '@/views/DashboardView.vue'
import RegisterView from '@/views/RegisterView.vue'
import LoginView from '@/views/LoginView.vue'
import GameView from '@/views/GameView.vue'
// import { name } from '@vue/eslint-config-prettier/skip-formatting'
// import { useUserStore } from '@/stores/userStore.js'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: DashboardView,
      meta: { requiresAuth: true },
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
      meta: { requiresGuest: true },
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { requiresGuest: true },
    },
    {
      path: '/game/:id',
      name: 'game',
      component: GameView,
      meta: { requiresGuest: true },
    },
  ],
})

// router.beforeEach((to, from, next) => {
//   const userStore = useUserStore();
//   const isLogged = userStore.isAuthenticated;
//   if (to.meta.requiresAuth && !isLogged) {
//     next({ name: 'login' });
//   } else if (to.meta.requiresGuest && isLogged) {
//     next({ name: 'home' });
//   } else {
//     next();
//   }
// });

export default router
