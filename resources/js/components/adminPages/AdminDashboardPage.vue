<script setup>
import AdminLayout from "../layouts/AdminLayout.vue"
import {useAdminStore} from "../../stores/admin.js"

const adminStore = useAdminStore()
adminStore.setAdminAuth(true)

</script>

<template>
<admin-layout>

</admin-layout>
</template>

<style scoped>

</style>
