<script setup>
import PageLayout from "../layouts/PageLayout.vue"
import DynamicForm from "../fractions/reusables/DynamicForm.vue"
import {useValidationErrorsStore} from "../../stores/validation_errors.js"
import {ref} from "vue"
import * as Yup from "yup"
import {BUTTONS, LINKS} from "../../constants.js"
import GoogleLink from "../fractions/reusables/GoogleLink.vue"

const token = document.head.querySelector('meta[name="csrf-token"]').content

const props = defineProps(['errors'])

const errorsStore = useValidationErrorsStore()
errorsStore.setValidationErrors(props.errors)

const formSchema = ref({
    fields: [
        {
            label: 'Email address',
            name: 'email',
            as: 'input',
            type: 'email',
            rules: Yup.string().email().required(),
        },
        {
            label: 'Password',
            name: 'password',
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

    <page-layout>
        <div class="row d-flex justify-content-center">

<!--            Login form-->
            <div class="col-lg-4 col-md-6 col-sm-10">
                <DynamicForm :action="LINKS.login" :method="'POST'" :schema="formSchema" :button_text="BUTTONS.login"/>
            </div>
        </div>

<!--        Google sign in-->
        <GoogleLink :link="LINKS.googleSignIn" :text="BUTTONS.googleSingIn" />
    </page-layout>

</template>

<style scoped>

</style>
