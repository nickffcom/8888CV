import './bootstrap';
import { createApp } from "vue";
import App from "./views/App.vue";
import router from "./routes";
import Antd from "ant-design-vue";
import "ant-design-vue/dist/antd.css";
import ConfirmModal from "./components/ConfirmModal.vue";
import mitt from "mitt";
import store from "./store";
const emitter = mitt();
moment.locale("ja"); const app = createApp(App)
    .component("confirm-modal", ConfirmModal)
    .use(router)
    .use(store)
    .use(Antd);

app.config.globalProperties.emitter = emitter;
store.emitter = emitter;
app.mount("#app");
