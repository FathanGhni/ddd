import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'

import Housekeeping from '../views/hk/Housekeeping.vue'
import Listhk from '../views/hk/Listhk.vue'
import Orderhousekeeping from '../views/hk/Orderhousekeeping.vue'

import Posmenu from '../views/pos/Posmenu.vue'
import Orderpos from '../views/pos/Orderpos.vue'
import Menu from '../views/pos/Menu.vue'

import Otherindex from '../views/other/index.vue'
import List from '../views/other/List.vue'
import Orderother from '../views/other/Orderother.vue'

import History from '../views/History.vue'
import Guest from '../views/Guest.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Login',
      component: LoginView
    },
    {
      path: '/home',
      name: 'Home',
      component: HomeView
    },
    {
      path: '/Hk',
      component: Housekeeping,
      children: [
        {
          path: '/Hk',
          name: 'Housekeeping',
          component: Listhk
        },
        {
          path: '/order-hk',
          name: 'Orderhousekeeping',
          component: Orderhousekeeping
        },
      ]
    },
    {
      path: '/other',
      component: Otherindex,
      children: [
        {
          path: '/other',
          name: 'Otherindex',
          component: List
        },
        {
          path: '/order-oth',
          name: 'Orderother',
          component: Orderother
        },
      ]
    },
    {
      path: '/menu',
      component: Posmenu,
      children: [
        {
          path: '/menu',
          name: 'Posmenu',
          component: Menu
        },
        {
          path: '/order-pos',
          name: 'Orderpos',
          component: Orderpos
        },
      ]
    },
    {
      path: '/history',
      name: 'History',
      component: History
    },
    {
      path: '/guest',
      name: 'Guest',
      component: Guest
    },
    ]
})

export default router
