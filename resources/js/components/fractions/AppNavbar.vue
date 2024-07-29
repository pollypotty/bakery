<script setup>
import UserLinks from "./UserLinks.vue"
import GuestLinks from "./GuestLinks.vue"
import {useAuthenticationStore} from "../../stores/authentication.js"
import {LINKS} from "../../constants.js"
import {useCartStore} from "../../stores/cart.js"

const authStore = useAuthenticationStore()
let authenticated = authStore.authentication

const cartStore = useCartStore()

</script>

<template>

<!--    Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid mx-5">

<!--            Logo with link to home page-->
            <a class="navbar-brand" href="/">proofed</a>

<!--            Navbar collapse button-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                <div class="navbar-nav mx-3">

<!--                    Cart link-->
                    <a v-if="authenticated" class="nav-link" :href="LINKS.cart">Cart({{cartStore.cartItems.length}})</a>

<!--                    About link-->
                    <a class="nav-link" href="#">About</a>

<!--                    Products link-->
                    <a class="nav-link" :href="LINKS.products">Products</a>

<!--                    Links for authenticated users-->
                    <div v-if="authenticated" class="navbar-nav">
                        <UserLinks/>
                    </div>

<!--                    Links for guest users-->
                    <div v-else class="navbar-nav">
                        <GuestLinks/>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="spacing"></div>

</template>

<style scoped>

.navbar {
    height: 100px;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 200;
    width: 100%;
}

.spacing {
    height: 100px;
}

</style>
