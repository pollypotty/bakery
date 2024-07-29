<script setup>
import AppNavbar from "../fractions/AppNavbar.vue"
import AppFooter from "../fractions/AppFooter.vue"
import {FILES} from "../../constants.js"

</script>

<template>

<!--    Page background image-->
    <div class="container-fluid h-100 w-100 mx-0 p-0 bg"
         :style='{ backgroundImage: "url(" + FILES.backgroundImagePath + ")" }'
    >

<!--        Navbar-->
        <AppNavbar/>

<!--        Page content-->
        <slot/>
    </div>

<!--    Footer-->
    <div class="footer-div w-100">
        <AppFooter/>
    </div>

</template>

<style scoped>

.footer-div {
    position: fixed;
    bottom: 0;
}

@media only screen and (max-width: 600px) {
    .footer-div {
        position: unset;
        display: none;
    }
}

.bg {
    background-repeat: no-repeat;
    background-position: center;
    position: absolute;
    background-size: cover;
    overflow: auto;
}

</style>
