import './bootstrap';
import { createApp } from "vue";
import App from "./views/App.vue";
import router from "./routes";
import Antd from "ant-design-vue";
import "ant-design-vue/dist/antd.css";
import ConfirmModal from "./components/ConfirmModal.vue";
import mitt from "mitt";
import store from "./store";
import socketio from "socket.io-client";
const emitter = mitt();
var socketIOLocation = process.env.MIX_BASE_SOCKETIO_URL;
moment.locale("ja");
const SocketInstance = socketio.connect(socketIOLocation);
const app = createApp(App)
    .component("confirm-modal", ConfirmModal)
    .use(router)
    .use(store)
    .use(Antd);

app.config.globalProperties.emitter = emitter;
store.emitter = emitter;
app.mount("#app");
