<script setup>
import PageLayout from "../layouts/PageLayout.vue"
import {useAuthenticationStore} from "../../stores/authentication.js"
import {useCheckoutStore} from "../../stores/checkout.js"
import {flashMessage} from "@smartweb/vue-flash-message"
import {onMounted} from "vue"
import {SUCCESS_MESSAGES} from "../../constants.js"

const props = defineProps({
    authenticated: Boolean
})

const authStore = useAuthenticationStore()
authStore.setAuth(props.authenticated)

onMounted(() => {
    if (useCheckoutStore().orderSuccess) {
        flashMessage.show({
            text: SUCCESS_MESSAGES.orderSuccess,
            type: 'success',
            time: 5000,
        })
        useCheckoutStore().resetOrderSuccess()
    }

    if (authStore.loginSuccess === true) {
        flashMessage.show({
            text: SUCCESS_MESSAGES.loginSuccess,
            type: 'success',
            time: 5000,
        })
        authStore.resetLoginSuccess()
    }
})

</script>

<template>

    <page-layout>

        <FlashMessage position="right bottom" />

    </page-layout>

</template>

<style scoped>
</style>
