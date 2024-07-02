<script setup>
import axios from 'axios'
import {ERROR_MESSAGES, LINKS} from "../../constants.js"

const logout = () => {
    axios.post(LINKS.logout)
        .then( ()  => {
            window.location.href = LINKS.home
            localStorage.clear()
        })
        .catch(error => {
            console.error(ERROR_MESSAGES.logoutError, error)
        })
}

</script>

<template>

    <a class="nav-link" :href="LINKS.order">Order</a>
    <a class="nav-link" :href="LINKS.profile">Profile</a>
    <a class="nav-link" href="#" @click="logout">Log out</a>

</template>

<style scoped>

</style>
