<script setup>
import {ref, watch} from "vue"
import * as Yup from "yup"
import {BUTTONS, ERROR_MESSAGES, LINKS, STYLES} from "../../../constants.js"
import {useCheckoutStore} from "../../../stores/checkout.js"
import {ErrorMessage, Field, Form} from "vee-validate"
import CustomButton from "../reusables/CustomButton.vue"

const token = document.head.querySelector('meta[name="csrf-token"]').content

const checkoutStore = useCheckoutStore()

const addressSchema = Yup.object({
    zip: Yup.string().required().matches(/^[1-9]\d{3}$/, ERROR_MESSAGES.zipError),
    city: Yup.string().min(3).required().matches(/^[a-zA-Z][-a-zA-Z\s]*$/, ERROR_MESSAGES.formatError),
    street: Yup.string().min(3).required(),
    house: Yup.string().required().min(1),
})

const formData = ref({
    zip: '',
    city: '',
    street: '',
    house: '',
    info: '',
    type: checkoutStore.addressType,
})

watch(() => checkoutStore.addressType, (newType) => {
    formData.value.type = newType;
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

<!--    Form to add new address-->
    <Form :validation-schema="addressSchema" @submit="processForm">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card p-4 mb-3">
                        <div class="card-body">

                            <div class="row mb-3">

<!--                                Zip code-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <Field v-model="formData.zip" name="zip" type="text" placeholder="Zip code"
                                               class="form-control"/>
                                        <ErrorMessage name="zip" class="msg"/>
                                    </div>
                                </div>

<!--                                City-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <Field v-model="formData.city" name="city" type="text" placeholder="City"
                                               class="form-control"/>
                                        <ErrorMessage name="city" class="msg"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">

<!--                                Street-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <Field v-model="formData.street" name="street" type="text"
                                               placeholder="Street name" class="form-control"/>
                                        <ErrorMessage name="street" class="msg"/>
                                    </div>
                                </div>

<!--                                House-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <Field v-model="formData.house" name="house" type="text" placeholder="Building"
                                               class="form-control"/>
                                        <ErrorMessage name="house" class="msg"/>
                                    </div>
                                </div>
                            </div>

<!--                            Additional information-->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <Field v-model="formData.info" name="info" type="text"
                                               placeholder="Additional information" class="form-control"/>
                                    </div>
                                </div>
                            </div>

<!--                            Submit button-->
                            <div class="row justify-content-center">
                                <div class="col-md-6 text-center">
                                    <CustomButton :text="BUTTONS.saveAddress" class="btn btn-primary"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Form>

</template>

<style scoped>

input {
    background-color: v-bind('STYLES.inputYellow');
}

.msg {
    color: v-bind('STYLES.warningYellow');
}

.card {
    background-color: v-bind('STYLES.lightGreyTrans');
    border: v-bind('STYLES.border');
    border-radius: 20px;
}

</style>
