import './bootstrap'
import 'bootstrap'
import { createApp } from "vue"
import HomePage from "./components/userPages/HomePage.vue"
import { createPinia } from "pinia"
import FlashMessage from "@smartweb/vue-flash-message"
import RegistrationPage from "./components/userPages/RegistrationPage.vue"
import LoginPage from "./components/userPages/LoginPage.vue"
import ProfilePage from "./components/userPages/ProfilePage.vue"
import ProductsPage from "./components/userPages/ProductsPage.vue"
import OrderPage from "./components/userPages/OrderPage.vue"
import piniaPluginPersistedstate from "pinia-plugin-persistedstate"
import CartPage from "./components/userPages/CartPage.vue"
import AdminLoginPage from "./components/adminPages/AdminLoginPage.vue"
import AdminDashboardPage from "./components/adminPages/AdminDashboardPage.vue"
import AdminProductsPage from "./components/adminPages/AdminProductsPage.vue";

const pinia = createPinia()
const app = createApp({})

app.component("HomePage", HomePage)
    .component("RegistrationPage", RegistrationPage)
    .component("LoginPage", LoginPage)
    .component("ProfilePage", ProfilePage)
    .component("ProductsPage", ProductsPage)
    .component("OrderPage", OrderPage)
    .component("CartPage", CartPage)
    .component("AdminLoginPage", AdminLoginPage)
    .component("AdminDashboardPage", AdminDashboardPage)
    .component("AdminProductsPage", AdminProductsPage)

pinia.use(piniaPluginPersistedstate)
app.use(pinia)
app.use(FlashMessage)

app.mount('#app')
