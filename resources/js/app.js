import { createApp } from 'vue'
import App from './components/App.vue'
import router from "./router/index"
import axios from "axios"
import 'mdb-vue-ui-kit/css/mdb.min.css';
import MasonryWall from 'vue-masonry-wall'
import VueEasyLightbox from 'vue-easy-lightbox'
import 'bootstrap'


const app = createApp(App);
app.config.globalProperties.$my = 'hi'
app.use(router, axios,MasonryWall,VueEasyLightbox);

app.mount('#app');

require('./bootstrap');
