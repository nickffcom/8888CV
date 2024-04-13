import { createWebHistory, createRouter } from "vue-router";
import DashboardAdmin from "../views/Admin/DashboardAdmin/DashboardAdmin.vue";
import { ADMIN_ROLE, STAFF_ROLE } from "../constants";
import NotFound from "../views/Notfound/NotfoundScreen.vue";

const routes = [
  {
    name: "DashboardAdmin",
    path: "/",
    component: DashboardAdmin,
    meta: { requiresAuth: true },
    children: [
      {
        name: "MeetingDetail",
        path: "detail/:id",
        component: () => import("../views/Admin/Home/Home.vue"),
      },
      {
        name: "HomeByLinkID",
        path: "/meeting/:link_id/:token?",
        component: () => import("../views/Admin/Home/Home.vue"),
      },
      {
        name: "Home",
        path: "",
        component: () => import("../views/Admin/Home/Home.vue"),
      },
      {
        name: "Meeting",
        path: "meeting",
        component: () => import("../views/Admin/Meeting/Meeting.vue"),
      },
      {
        name: "Account",
        path: "account",
        component: () => import("../views/Admin/Account/Account.vue"),
      },
      {
        name: "CreatingMeeting",
        path: "meeting/create-meeting",
        component: () =>
          import("../views/Admin/Meeting/CreatingMeeting/CreatingMeeting.vue"),
      },
      {
        name: "Profile",
        path: "/profile/:profile_id",
        component: () => import("../views/Profile/UserProfile.vue"),
      },
      {
        name: "EditMeeting",
        path: "meeting/edit-meeting/:id",
        component: () =>
          import("../views/Admin/Meeting/CreatingMeeting/EditMeeting.vue"),
      },
    ],
  },
  {
    name: "MeetingGuest",
    path: "/meeting/:link_id/:token/guest",
    component: () => import("../views/Admin/Home/MeetingGuest.vue"),
  },
  {
    name: "ProfileGuest",
    path: "/profile/:profile_id/guest",
    component: () => import("../views/Profile/ProfileGuest.vue"),
  },
  {
    name: "LoginScreenWeb",
    path: "/login_guest/:link_id/:token?",
    component: () => import("../views/Auth/Login/LoginScreenWeb.vue"),
  },
  {
    name: "Login",
    path: "/login/:profile_id?",
    component: () => import("../views/Auth/Login/LoginScreen.vue"),
  },
  {
    name: "Register",
    path: "/register/:profile_id?",
    component: () => import("../views/Auth/Register/RegisterScreen.vue"),
  },
  {
    name: "RegisterMeetingLink",
    path: "/register/meeting_link/:token_guest",
    component: () => import("../views/Auth/Register/RegisterScreen.vue"),
  },
  {
    name: "RegisterInvite",
    path: "/register/invite/:email_token?/:profile_id?",
    component: () => import("../views/Auth/Register/RegisterScreen.vue"),
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

  {
    name: "AccountRegister",
    path: "/register/:id",
    component: () => import("../views/Auth/Register/RegisterScreen.vue"),
  },
  {
    name: "ChatMeetingInvite",
    path: "/register/:email_token/:profile_id",
  },
  {
    name: "LoginWeb",
    path: "/meeting/:token/login",
    component: () => import("../views/Auth/Login/LoginScreenWeb.vue"),
  },
  {
    name: "LoginChatMeeting",
    path: "/chat-meeting/login/:email_token?/:profile_id?",
    component: () => import("../views/Auth/Login/LoginChatMeeting.vue"),
  },
  {
    name: "LoggedInChatMeeting",
    path: "/chat-meeting/logged-in/:profile_id?",
    component: () => import("../views/Auth/Login/LoggedInChatMeeting.vue"),
  },
  {
    name: "temp",
    path: "/chat-meeting/:profile_id?",
  },
  {
    name: "editPublic",
    path: "/:link_id/edit",
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
  let isLogin = JSON.parse(localStorage.getItem("isLogin"));
  if (to.name === "temp" && to.params.profile_id) {
    if (!isLogin) {
      return {
        path: `/chat-meeting/login/${to.params.profile_id}`,
      };
    } else {
      return {
        path: `/chat-meeting/logged-in/${to.params.profile_id}`,
      };
    }
  } else if (
    to.name === "ChatMeetingInvite" &&
    to.params.profile_id &&
    to.params.email_token
  ) {
    if (!isLogin) {
      return {
        path: `/chat-meeting/login/${to.params.email_token}/${to.params.profile_id}`,
      };
    } else {
      return {
        path: `/chat-meeting/logged-in/${to.params.profile_id}`,
      };
    }
  } else if (to.name === "Profile" && !isLogin) {
    return {
      path: `/profile/${to.params.profile_id}/guest`,
    };
  } else if (to.meta.requiresAuth && !isLogin) {
    if (to.params.link_id) {
      if (to.params.token) {
        return {
          path: `/login_guest/${to.params.link_id}/${to.params.token}`,
        };
      }
    }
    return {
      path: "/login",
    };
  }
});

export default router;
