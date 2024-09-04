<script setup>
import {STYLES} from "../../../constants.js"

</script>

<template>

<!--    Footer-->
    <div class="container-fluid footer-div pt-3">
        <footer>
            <p class="text-center text-muted"><small>&copy; 2024 PROOFED</small></p>
        </footer>
    </div>

</template>

<style scoped>

.footer-div {
    background: v-bind('STYLES.lightGreyTrans');
}

</style>
