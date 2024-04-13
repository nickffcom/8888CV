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
  {
    name: "Login",
    path: "/login",
    beforeEnter: ifAuthenticated,
    component: lazyLoad("LoginScreen"),
  },
  {
    name: "MeetingGuest",
    path: "/meeting/:link_id/:token/guest",
    component: () => import("../views/Admin/Home/MeetingGuest.vue"),
  },
  {
    name: "LoginScreenWeb",
    path: "/login_guest/:link_id/:token?",
    component: () => import("../views/Auth/Login/LoginScreenWeb.vue"),
  },
  {
    name: "LoginChatMeeting",
    path: "/chat-meeting/login/:profile_id?",
    component: () => import("../views/Auth/Login/LoginChatMeeting.vue"),
  },
  {
    name: "LoggedInChatMeeting",
    path: "/chat-meeting/logged-in/:profile_id?",
    component: () => import("../views/Auth/Login/LoggedInChatMeeting.vue"),
  },
  {
    name: "Login",
    path: "/login",
    component: () => import("../views/Auth/Login/LoginScreen.vue"),
  },
  {
    name: "Register",
    path: "/register",
    component: () => import("../views/Auth/Register/RegisterScreen.vue"),
  },
  {
    name: "AccountRegister",
    path: "/register/:id",
    component: () => import("../views/Auth/Register/RegisterScreen.vue"),
  },
  {
    name: "ChatMeetingInvite",
    path: "/register/:email_token/:profile_id",
    component: () => import("../views/Auth/Register/RegisterScreen.vue"),
  },
  {
    name: "LoginWeb",
    path: "/meeting/:token/login",
    component: () => import("../views/Auth/Login/LoginScreenWeb.vue"),
  },

  {
    name: "ForgetPassword",
    path: "/password",
    component: () => import("../views/Auth/Password/ForgetPasswordScreen.vue"),
  },
  {
    name: "ResetPassword",
    path: "/password/:token",
    component: () => import("../views/Auth/Password/ForgetPasswordScreen.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
