import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import BoardView from '../views/BoardView.vue'
import YourBoardsView from '../views/YourBoardsView.vue'
import NotFoundView from '../views/NotFoundView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/login' },
    { path: '/login', component: Login },
    { path: '/register', component: Register },

    { path: '/boards/:id', component: BoardView }, 

    { path: '/boards', component: YourBoardsView },

    { path: '/:pathMatch(.*)*', name: 'NotFoundView', component: NotFoundView }
  ]
})

export default router
