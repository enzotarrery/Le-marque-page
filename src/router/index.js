import Vue from 'vue'
import VueRouter from 'vue-router'
import Store from '@/store/index'
import Login from "@/views/Login";
import Produits from "@/views/Produits";
import Promotions from "@/views/Promotions";
import Evenements from "@/views/Evenements";

Vue.use(VueRouter)

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/produits',
    name: 'Produits',
    component: Produits
  },
  {
    path: '/promotions',
    name: 'Promotions',
    component: Promotions
  },
  {
    path: '/evenements',
    name: 'Evenements',
    component: Evenements
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router

router.beforeEach((to, from, next) => {
  const publicPages = ['/login']
    Store.state.beforeConnection = to.path
  const authRequired = !publicPages.includes(to.path)
  const loggedIn = sessionStorage.getItem('token')

  if (authRequired && !loggedIn)
    return next('/login')
  next()
})