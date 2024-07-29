<script setup>
import {BUTTONS, LINKS, STYLES} from "../../constants.js"
import {useCheckoutStore} from "../../stores/checkout.js"
import CustomButton from "./CustomButton.vue"
import AddressForm from "./AddressForm.vue"

const checkoutStore = useCheckoutStore()

async function fetchUserAddresses() {
    const response = await fetch('api' + LINKS.userAddresses)
    checkoutStore.userAddresses = await response.json()
}

function showAddressPicker() {
    fetchUserAddresses()
}

function showAddressForm() {
    checkoutStore.addressId = -1
    checkoutStore.newAddress = true
}

function pickupSettings() {
    checkoutStore.newAddress = false
    checkoutStore.addressSaved = false
    checkoutStore.addressId = -1
    checkoutStore.paymentType = 'STRIPE'
}

function hideNewAddress() {
    checkoutStore.newAddress = false
    checkoutStore.addressSaved = false
}

</script>

<template>

    <div class="container-fluid">

<!--        Shipping options-->
        <div class="row items-head text-light p-2 mt-3 mb-5 mx-2 text-start col-10">
            <h4>Choose from the shipping options:</h4>
        </div>

        <div class="text-dark row">

<!--            Pick up point-->
            <div class="col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-1 text-end">
                        <input
                            v-model="checkoutStore.deliveryType"
                            @input="pickupSettings"
                            type="radio" name="delivery" value="PICK_UP"
                        />
                    </div>
                    <div class="col-md-5 col-sm-11 text-start">
                        <h3><label>Pick up point</label></h3>
                    </div>
                </div>
                <div class="row text-start ms-5">
                    <p>Pick up your order in person between 8 AM and 18 PM.<br>
                        Address: Bioshop (4587, Gyula Main street 17.)</p>
                    <p>If you choose this option, you can only pay online beforehand.</p>
                </div>
            </div>

<!--            Home delivery-->
            <div class="col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-1 text-end">
                        <input
                            v-model="checkoutStore.deliveryType"
                            @input="showAddressPicker"
                            type="radio" name="delivery" value="HOME"
                        />
                    </div>
                    <div class="col-md-5 col-sm-11 text-start">
                        <h3><label>Home delivery</label></h3>
                    </div>
                </div>
                <div class="row text-start ms-5">
                    <p>Get your fresh produce at home between 7 AM and 9 AM in Gyula.</p>
                    <p>You can pay with card or cash when you get your order.<br>
                        But you are still able to pay online, so you don't even have to be there. We will live your
                        order at your door.</p>
                </div>
            </div>

        </div>

<!--        Home delivery addresses-->
        <div v-if="checkoutStore.deliveryType === 'HOME'" class="text-dark">

<!--            Choose from previously saved addresses-->
            <div v-if="checkoutStore.userAddresses.length > 0" class="text-start ms-5">
                <select v-model="checkoutStore.addressId" @input="hideNewAddress">
                    <option value="-1">Select address:</option>
                    <option v-for="(address, index) in checkoutStore.userAddresses" :key="index" :value="address.id">
                        {{ address.zip_code + ', ' + address.city + ' ' + address.line1 + ' ' + address.building_number }}
                    </option>
                </select>
            </div>

<!--            Button to show from to add new address-->
            <div class="text-start ms-5 my-3">
                <CustomButton :text="BUTTONS.addAddress" @click="showAddressForm"/>
            </div>

<!--            Choose address type-->
            <div v-if="checkoutStore.newAddress">
                <div class="col-lg-6 col-md-10 col-sm-12 mb-5">
                    <div class="row">

<!--                        One time-->
                        <div class="col-2 text-end">
                            <input
                                v-model="checkoutStore.addressType"
                                type="radio" name="addressType" value="ONE_TIME">
                        </div>
                        <div class="col-10 text-start">
                            <h4><label>Use only once</label></h4>
                        </div>
                    </div>

<!--                    Delivery-->
                    <div class="row">
                        <div class="col-2 text-end">
                            <input
                                v-model="checkoutStore.addressType"
                                type="radio" name="addressType" value="DELIVERY">
                        </div>
                        <div class="col-10 text-start">
                            <h4><label>Save as delivery address</label></h4>
                        </div>
                    </div>

<!--                    Billing and delivery-->
                    <div class="row">
                        <div class="col-2 text-end">
                            <input
                                v-model="checkoutStore.addressType"
                                type="radio" name="addressType" value="BILLING_AND_DELIVERY">
                        </div>
                        <div class="col-10 text-start">
                            <h4><label>Save for billing and delivery address</label></h4>
                        </div>
                    </div>
                </div>

<!--                Address Form component-->
                    <AddressForm/>

<!--                Success message-->
                <div class="my-2" v-if="checkoutStore.addressSavedMsg">
                    <h5 class="successMsg">Address saved successfully, you can proceed with the payment.</h5>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>

.items-head {
    background-color: v-bind('STYLES.lightGrey');
    border-radius: 20px;
}

.successMsg {
    color: #2b702b;
}

</style>
