<script setup>
import PageLayout from "../layouts/PageLayout.vue"
import {useAuthenticationStore} from "../../stores/authentication.js"

const props = defineProps(['authenticated'])

const authStore = useAuthenticationStore()
authStore.setAuth(props.authenticated)

</script>

<template>
    <page-layout>

    </page-layout>
</template>

<style scoped>

</style>
