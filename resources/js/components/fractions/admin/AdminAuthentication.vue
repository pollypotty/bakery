<script setup>
import DynamicForm from "../reusables/DynamicForm.vue"
import {ref} from "vue"
import * as Yup from "yup"
import {BUTTONS, LINKS} from "../../../constants.js"

const token = document.head.querySelector('meta[name="csrf-token"]').content

const formSchema = ref({
    fields: [
        {
            label: 'Admin email address',
            name: 'admin_email',
            as: 'input',
            type: 'email',
            rules: Yup.string().email().required(),
        },
        {
            label: 'Admin password',
            name: 'admin_password',
            as: 'input',
            type: 'password',
            rules: Yup.string().min(6).required(),
        },
        {
            name: '_token',
            as: 'input',
            value: token,
            hidden: true,
        },
    ],
})

</script>

<template>

    <div class="row d-flex justify-content-center">

        <!--            Admin login form-->
        <div class="col-lg-4 col-md-6 col-sm-10">
            <DynamicForm :action="LINKS.adminLogin" :method="'POST'" :schema="formSchema" :button_text="BUTTONS.login"/>
        </div>

    </div>

</template>

<style scoped>

</style>
