<script setup>
import {ref} from "vue"
import * as Yup from "yup"
import DynamicForm from "./DynamicForm.vue"
import {BUTTONS, ERROR_MESSAGES, LINKS} from "../../constants.js"
import {useCheckoutStore} from "../../stores/checkout.js"
import {ErrorMessage, Field, Form} from "vee-validate"
import CustomButton from "./CustomButton.vue";

const token = document.head.querySelector('meta[name="csrf-token"]').content

const checkoutStore = useCheckoutStore()

const addressSchema = Yup.object({
    zip: Yup.string().required().matches(/^[1-9]\d{3}$/, ERROR_MESSAGES.zipError),
    city: Yup.string().min(3).required().matches(/^[a-zA-Z][-a-zA-Z\s]*$/, ERROR_MESSAGES.formatError),
    street: Yup.string().min(3).required(),
    house: Yup.string().required().min(1),
});


const formData = ref({
    zip: '',
    city: '',
    street: '',
    house: '',
    info: '',
    type: checkoutStore.addressType,
})

function processForm() {
    if (checkoutStore.addressType === null) {
        window.alert(ERROR_MESSAGES.addressType)
        return
    }
    submitForm()
}

async function submitForm() {
    const response = await fetch('api' + LINKS.userAddresses, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-Token': token,
        },
        body: JSON.stringify(formData.value)
    })
        .then(function (response) {
            if (!response.ok) {
                return response.json().then(errorResponse => {
                    let errorMessage = ''

                    for (const error in errorResponse.errors) {
                        if (errorResponse.errors.hasOwnProperty(error)) {
                            const errorArray = errorResponse.errors[error];
                            errorArray.forEach(message => {
                                errorMessage += message
                                errorMessage += '\n'
                            })
                        }
                    }

                    window.alert(errorMessage)
                })
            }
            return response.json().then((response) => {
                checkoutStore.addressSaved = true
                checkoutStore.addressSavedMsg = true
                checkoutStore.addressId = response[0].id
                checkoutStore.newAddressDetails = response
            })
        })
}
</script>

<template>

    <Form :validation-schema="addressSchema" @submit="processForm">

        <div class="row mx-5 d-flex justify-content-center">
            <div class="col-lg-2  col-md-3 col-sm-4 me-3">
                <div class="row">
                    <Field v-model="formData.zip" name="zip" type="text" placeholder="Zip code"/>
                </div>
                <div class="row">
                    <ErrorMessage name="zip"/>
                </div>
            </div>

            <div class="col-lg-4 col-md-9 col-sm-8 ms-3">
                <div class="row">
                    <Field v-model="formData.city" name="city" type="text" placeholder="City"/>
                </div>
                <div class="row">
                    <ErrorMessage name="city"/>
                </div>
            </div>
        </div>

        <div class="row mx-5 d-flex justify-content-center mt-2">
            <div class="col-lg-4 col-md-9 col-sm-8 me-3">
                <div class="row">
                    <Field v-model="formData.street" name="street" type="text" placeholder="Street name"/>
                </div>
                <div class="row">
                    <ErrorMessage name="street"/>
                </div>
            </div>

            <div class="col-lg-2  col-md-3 col-sm-4 ms-3">
                <div class="row">
                    <Field v-model="formData.house" name="house" type="text" placeholder="Building"/>
                </div>
                <div class="row">
                    <ErrorMessage name="house"/>
                </div>
            </div>
        </div>

        <div class="row mx-5 d-flex justify-content-center my-2">
            <div class="col-lg-6 col-md-8 col-sm-10 ms-5">
                <div class="row">
                    <Field v-model="formData.info" name="info" type="text" placeholder="Additional information"/>
                </div>
            </div>

        </div>

        <CustomButton :text="BUTTONS.saveAddress" class="my-3"/>

    </Form>


</template>

<style scoped>

input {
    background-color: #f7fab2;
}

</style>

