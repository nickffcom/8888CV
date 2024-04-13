import { createApp } from "vue";
import AuthApp from "./views/AuthApp.vue";
import authRoute from "./routes/authRoute";
import store from "./store/index";
import mitt from "mitt";

const emitter = mitt();

const app = createApp(AuthApp).use(authRoute).use(store);

app.config.globalProperties.emitter = emitter;

app.mount("#auth");
