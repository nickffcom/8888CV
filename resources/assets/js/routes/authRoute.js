import { createRouter, createWebHistory } from "vue-router";
import {
  ADMIN_DASHBOARD_ROUTE,
  HOME_ROUTE,
  LOCAL_STORAGE_ISROLEADMIN,
  LOCAL_STORAGE_TOKEN,
} from "../constants";

/**
 * @description: Lazy load components
 * @param  {string} view
 * @return {mix} component
 */
function lazyLoad(view) {
  return () => import(`../views/Auth/Login/${view}.vue`);
}

/**
 * @description: Check user is authenticated or not
 * @description: If user is not authenticated, redirect to check message login page
 * @param {*} to
 * @param {*} from
 * @param {*} next
 * @returns
 */
const ifAuthenticated = (to, from, next) => {
  if (localStorage.getItem(LOCAL_STORAGE_TOKEN)) {
    const isAdmin = +localStorage.getItem(LOCAL_STORAGE_ISROLEADMIN);
    if (!isAdmin) {
      window.location.href = HOME_ROUTE;
    } else {
      window.location.href = ADMIN_DASHBOARD_ROUTE;
    }

    return;
  }
  next();
};

const routes = [
  // {
  //   name: "Login",
  //   path: "/login",
  //   beforeEnter: ifAuthenticated,
  //   component: lazyLoad("LoginScreen"),
  // },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
