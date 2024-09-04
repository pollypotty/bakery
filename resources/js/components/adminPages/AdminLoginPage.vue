<script setup>
import AdminLayout from "../layouts/AdminLayout.vue"
import AdminAuthentication from "../fractions/admin/AdminAuthentication.vue"
import {useAdminStore} from "../../stores/admin.js"
import {useValidationErrorsStore} from "../../stores/validation_errors.js"

const adminStore = useAdminStore()
adminStore.setAdminAuth(false)

const props = defineProps(['errors'])

const errorsStore = useValidationErrorsStore()
errorsStore.setValidationErrors(props.errors)

</script>

<template>

    <admin-layout>
        <admin-authentication></admin-authentication>
    </admin-layout>

</template>

<style scoped>

</style>
