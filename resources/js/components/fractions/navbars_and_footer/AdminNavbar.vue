<script setup>
import {useAdminStore} from "../../../stores/admin.js"
import {ERROR_MESSAGES, LINKS} from "../../../constants.js";

const adminStore = useAdminStore()

const logout = () => {
    axios.post(LINKS.adminLogout)
        .then( ()  => {
            window.location.href = LINKS.adminLogin
            localStorage.clear()
        })
        .catch(error => {
            console.error(ERROR_MESSAGES.logoutError, error)
        })
}
</script>

<template>
    <!--    Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid mx-5">

            <!--            Logo with link to home page-->
            <a class="navbar-brand" href="/admin">proofed</a>

            <!--            Navbar collapse button-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                <div v-if="adminStore.authentication" class="navbar-nav mx-3">

                    <!--                    Products link-->
                    <a class="nav-link" :href="LINKS.products">Products</a>

                    <!--                    Orders link-->
                    <a class="nav-link" href="#">Orders</a>

                    <!--                    Logout link-->
                    <a class="nav-link" href="#" @click="logout" >Log out</a>

                </div>
            </div>
        </div>
    </nav>
    <div class="spacing"></div>
</template>

<style scoped>

</style>
