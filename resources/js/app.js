import './bootstrap';
import { createApp } from 'vue';
import ProductList from './components/ProductList.vue';
import Toast, { useToast } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

const app = createApp(ProductList);
app.use(Toast)
app.mount('#app')