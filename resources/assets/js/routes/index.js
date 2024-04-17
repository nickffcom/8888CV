import { createWebHistory, createRouter } from "vue-router";
import { ADMIN_ROLE, STAFF_ROLE } from "../constants";
import NotFound from "../views/Notfound/NotfoundScreen.vue";

const routes = [

  {
    name: "LoginMethod",
    path: "/login-method",
    component: () => import("../views/Auth/Login/Login.vue"),
  },
  {
    name: "NotFound",
    path: "/404",
    component: NotFound,
  },
  {
    path: "/:catchAll(.*)",
    redirect: "/404",
  },
];
const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from) => {

  // Check login && role
  const isAdminRole = JSON.parse(localStorage.getItem("isRoleAdmin"));
  const isLogin = JSON.parse(localStorage.getItem("isLogin"));
  if (to.meta.requiresAuth && !isLogin) {
    return {
      path: "/login-method",
    };
  }
  if (to.meta.requiresAuth === ADMIN_ROLE && !isAdminRole) {
    return {
      path: "/404",
    };
  }
});

export default router;
