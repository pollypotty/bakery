<script setup>
import {LINKS} from "../../../constants.js"

</script>

<template>

<!--    Navbar links only shown to guest user-->
    <a class="nav-link" :href="LINKS.login">Login</a>
    <a class="nav-link" :href="LINKS.registration">Register</a>

</template>

<style scoped>

</style>
