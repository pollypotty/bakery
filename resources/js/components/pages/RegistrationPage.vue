<script setup>
import PageLayout from "../layouts/PageLayout.vue"
import DynamicForm from "../fractions/DynamicForm.vue"
import * as Yup from 'yup'
import {ref, watch} from "vue"
import {useValidationErrorsStore} from "../../stores/validation_errors.js"
import {ERROR_MESSAGES, BUTTONS} from "../../constants.js"

const pwToMatch = ref('')
const token = document.head.querySelector('meta[name="csrf-token"]').content

const props = defineProps(['errors'])

const errorsStore = useValidationErrorsStore()
errorsStore.setValidationErrors(props.errors)

const passwordCheck = (name, value) => {
    if (name === 'password') {
        pwToMatch.value = value
    }
}

const text = BUTTONS.registration

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
            label: 'First name',
            name: 'firstName',
            as: 'input',
            rules: Yup.string().required().matches(/^[A-Za-z -]+$/, ERROR_MESSAGES.registrationNameError),
        },
        {
            label: 'Last name',
            name: 'lastName',
            as: 'input',
            rules: Yup.string().required().matches(/^[A-Za-z -]+$/, ERROR_MESSAGES.registrationNameError),
        },
        {
            label: 'Password',
            name: 'password',
            as: 'input',
            type: 'password',
            rules: Yup.string().min(6).required(),
        },
        {
            label: 'Password again',
            name: 'password_confirmation',
            as: 'input',
            type: 'password',
            rules: Yup.string().min(6).required().oneOf([pwToMatch.value], ERROR_MESSAGES.passwordError),
        },
        {
            name: '_token',
            as: 'input',
            value: token,
            hidden: true,
        },
    ],
})

watch(pwToMatch, () => {
    formSchema.value = {
        ...formSchema.value,
        fields: formSchema.value.fields.map((field) => {
            if (field.name === 'password_confirmation') {
                return {
                    ...field,
                    rules: Yup.string().min(6).required().oneOf([pwToMatch.value], ERROR_MESSAGES.passwordError),
                }
            }
            return field
        }),
    }
})

</script>

<template>

    <page-layout>
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-10">
                <DynamicForm :action="'/registration'" :method="'POST'" @handle-input="passwordCheck"
                             :schema="formSchema" :button_text="text"/>
            </div>
        </div>
    </page-layout>

</template>

<style scoped>
</style>
