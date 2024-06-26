import "./bootstrap";
import { createApp } from "vue";
import App from "./views/App.vue";
import router from "./routes";
import Antd from "ant-design-vue";
import "ant-design-vue/dist/antd.css";
import ConfirmModal from "./components/ConfirmModal.vue";
import Vue3Lottie from 'vue3-lottie'

import mitt from "mitt";
import store from "./store";
import moment from "moment";

const emitter = mitt();
moment.locale("vn");
const app = createApp(App)
    .component("confirm-modal", ConfirmModal)
    .use(router)
    .use(store)
    .use(Antd)
    .use(Vue3Lottie);

app.config.globalProperties.emitter = emitter;
store.emitter = emitter;
app.mount("#app");
