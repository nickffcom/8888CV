import { createWebHistory, createRouter } from "vue-router";
import { ADMIN_ROLE, IS_LOGIN } from "../constants";
import NotFound from "../views/Notfound/NotfoundScreen.vue";
import DashboardUser from "../components/DashboardUser.vue";
const routes = [
  {
    name: "DashboardUser",
    path: "/",
    component: DashboardUser,
    meta: { requiresAuth: true },
    children: [
      {
        name: "Home",
        path: "",
        component: () => import("../views/User/Home.vue"),
      },
      {
        name: "HistoryPurchase",
        path: "/history",
        component: NotFound,
      },
    ],
  },
  {
    name: "LoginMethod",
    path: "/login",
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
  const isAdminRole = JSON.parse(localStorage.getItem(ADMIN_ROLE));
  const isLogin = JSON.parse(localStorage.getItem(IS_LOGIN));
  if (to.fullPath !== "/" && to.meta.requiresAuth && !isLogin) {
    console.log("Beforeach", to);
    console.log("Beforeach", from);
    return {
      path: "/login",
    };
  }
  if (to.meta.requiresAuth === ADMIN_ROLE && !isAdminRole) {
    console.log("Đã zô 404");
    return {
      path: "/404",
    };
  }
});

export default router;
