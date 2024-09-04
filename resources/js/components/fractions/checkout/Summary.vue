<script setup>
import {useCheckoutStore} from "../../../stores/checkout.js"
import {BUTTONS, ERROR_MESSAGES, LINKS, STYLES} from "../../../constants.js"
import {useCartStore} from "../../../stores/cart.js"
import CustomButton from "../reusables/CustomButton.vue"

const token = document.head.querySelector('meta[name="csrf-token"]').content

const checkoutStore = useCheckoutStore()
const cartStore = useCartStore()

const formData = {
    deliveryType: checkoutStore.deliveryType,
    paymentType: checkoutStore.paymentType,
    deliveryDate: cartStore.deliveryDate,
    orderItems: checkoutStore.getOrderItems(),
    addressId: checkoutStore.addressId,
    paymentTotal: checkoutStore.getPaymentTotal(),
}

async function placeOrder() {
    try {
        const response = await fetch('api' + LINKS.order, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': token,
            },
            body: JSON.stringify(formData)
        })

        const responseData = await response.json()

        if (response.ok && responseData.success) {
            checkoutStore.resetState()
            useCartStore().clearCart()
            checkoutStore.setOrderSuccess(true)
            window.location.href = LINKS.profile
            return
        }

        if (response.ok && !responseData.success) {
            window.alert(responseData.message)
            return
        }

        let errorMessage = ''

        for (const error in responseData.errors) {
            if (responseData.errors.hasOwnProperty(error)) {
                const errorArray = responseData.errors[error]

                errorArray.forEach(message => {
                    errorMessage += message
                    errorMessage += '\n'
                })
            }
        }

        window.alert(errorMessage)
    } catch (error) {
        window.alert(ERROR_MESSAGES.unexpectedError)
    }
}

</script>

<template>

    <!--    Ordered items summary-->
    <div class="container-fluid">

        <!--        Products summary-->
        <div class="row">
            <div class="row items-head text-light p-2 mt-5 mb-4 mx-2 text-start col-10">
                <h4>Products:</h4>
            </div>
        </div>
        <ul class="list-group list-group-flush w-75 text-start ms-5">
            <li v-for="item in cartStore.cartItems" :key="item.productId" class="list-group-item">
                <div class="row">
                    <div class="col-4">{{ item.productName }}</div>
                    <div class="col-2">{{ item.pricePerItem }} €/piece</div>
                    <div class="col-2">{{ item.quantity }} pieces</div>
                    <div class="col-2">{{ item.pricePerItem * item.quantity }} € total</div>
                </div>
            </li>
        </ul>

        <!--        Payment total-->
        <hr class="text-dark">
        <div class="row text-dark ms-5 col-10 w-75">
            <div class="col-8 text-start">
                <h5>Total:</h5>
            </div>
            <div class="col-2 text-dark text-start">
                <h5>{{ checkoutStore.getPaymentTotal() }} €</h5>
            </div>
        </div>

        <!--        Shipping summary-->
        <div class="row">
            <div class="row items-head text-light p-2 mt-3 mb-4 mx-2 text-start col-10">
                <h4>Shipping:</h4>
            </div>
        </div>

        <!--        Address and date details-->
        <div class="row text-dark text-start ms-5">
            <div v-if="checkoutStore.deliveryType === 'PICK_UP'">
                <h4>Pick up point: Bioshop (4587, Gyula Main street 17.)</h4>
                <h6>Delivery on: {{ cartStore.getFormattedDeliveryDate() }}, between 8 AM and 18 PM</h6>
            </div>

            <div v-if="checkoutStore.deliveryType === 'HOME' && !checkoutStore.addressSaved">
                <h4>Home delivery</h4>
                <h5>Address: {{
                        checkoutStore.userAddresses[checkoutStore.indexOfSavedAddress].zip_code + ', ' + checkoutStore.userAddresses[checkoutStore.indexOfSavedAddress].city + ' ' + checkoutStore.userAddresses[checkoutStore.indexOfSavedAddress].line1 + ' ' + checkoutStore.userAddresses[checkoutStore.indexOfSavedAddress].building_number
                    }}</h5>
                <h6>Delivery on {{ cartStore.getFormattedDeliveryDate() }}, between 7 AM and 9 AM</h6>
            </div>

            <div v-if="checkoutStore.deliveryType === 'HOME' && checkoutStore.addressSaved">
                <h4>Home delivery</h4>
                <h5>{{
                        checkoutStore.newAddressDetails[0].zip_code + ', ' + checkoutStore.newAddressDetails[0].city + ' ' + checkoutStore.newAddressDetails[0].line1 + ' ' + checkoutStore.newAddressDetails[0].building_number
                    }}</h5>
                <h6>Delivery on {{ cartStore.getFormattedDeliveryDate() }}, between 7 AM and 9 AM</h6>
            </div>
        </div>

        <!--        Payment summary-->
        <div class="row">
            <div class="row items-head text-light p-2 mt-3 mb-4 mx-2 text-start col-10">
                <h4>Payment:</h4>
            </div>
        </div>

        <div class="row text-dark text-start ms-5 mb-3">
            <h5>{{ checkoutStore.paymentType }}</h5>
        </div>
    </div>

    <!--    Finalize button-->
    <div class="row">
        <div class="row d-flex justify-content-end col-10 mb-5">
            <CustomButton @click="placeOrder" :text="BUTTONS.placeOrder" :is-large="true"/>
        </div>
    </div>

</template>

<style scoped>

.items-head {
    border-radius: 20px;
    background-color: v-bind('STYLES.lightGrey');
}

</style>
