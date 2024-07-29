<script setup>
import PageLayout from "../layouts/PageLayout.vue"
import {useAuthenticationStore} from "../../stores/authentication.js"
import {useCheckoutStore} from "../../stores/checkout.js"
import {flashMessage} from "@smartweb/vue-flash-message"
import {onMounted} from "vue"

const props = defineProps({
    authenticated: Boolean
})

const authStore = useAuthenticationStore()
authStore.setAuth(props.authenticated)

onMounted(() => {
    if (useCheckoutStore().orderSuccess) {
        flashMessage.show({
            text: 'order successful',
            type: 'success',
            time: 5000,
        })
        useCheckoutStore().resetOrderSuccess()
    }
})

</script>

<template>

    <page-layout>

        <FlashMessage position="top right" />

    </page-layout>

</template>

<style scoped>

</style>
