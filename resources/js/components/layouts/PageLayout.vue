<script setup>
import AppNavbar from "../fractions/AppNavbar.vue"
import AppFooter from "../fractions/AppFooter.vue"
import {FILES} from "../../constants.js"

</script>

<template>
    <div class="container-fluid h-100 w-100 mx-0 p-0 bg"
         :style='{ backgroundImage: "url(" + FILES.backgroundImagePath + ")" }'
    >
        <app-navbar></app-navbar>

        <slot/>

    </div>
    <div class="footer-div w-100">
        <app-footer></app-footer>
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
