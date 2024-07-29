import './bootstrap'
import 'bootstrap'
import { createApp } from "vue"
import HomePage from "./components/pages/HomePage.vue"
import { createPinia } from "pinia"
import FlashMessage from "@smartweb/vue-flash-message"
import RegistrationPage from "./components/pages/RegistrationPage.vue"
import LoginPage from "./components/pages/LoginPage.vue"
import ProfilePage from "./components/pages/ProfilePage.vue"
import ProductsPage from "./components/pages/ProductsPage.vue"
import OrderPage from "./components/pages/OrderPage.vue"
import piniaPluginPersistedstate from "pinia-plugin-persistedstate"
import CartPage from "./components/pages/CartPage.vue"

const pinia = createPinia()
const app = createApp({})

app.component("HomePage", HomePage)
    .component("RegistrationPage", RegistrationPage)
    .component("LoginPage", LoginPage)
    .component("ProfilePage", ProfilePage)
    .component("ProductsPage", ProductsPage)
    .component("OrderPage", OrderPage)
    .component("CartPage", CartPage)

pinia.use(piniaPluginPersistedstate)
app.use(pinia)
app.use(FlashMessage)

app.mount('#app')
