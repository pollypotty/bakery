<script setup>
import PageLayout from "../layouts/PageLayout.vue"
import {useCartStore} from "../../stores/cart.js"
import {BUTTONS, ERROR_MESSAGES, LINKS, STYLES} from "../../constants.js"
import CustomButton from "../fractions/CustomButton.vue"
import {useCheckoutStore} from "../../stores/checkout.js"
import Summary from "../fractions/Summary.vue"
import Shipping from "../fractions/Shipping.vue"
import Payment from "../fractions/Payment.vue"
import Products from "../fractions/Products.vue"

const props = defineProps({
    stripeKey: {
        type: String,
        required: true
    }
});

const cartStore = useCartStore()
const checkoutStore = useCheckoutStore()

checkoutStore.setStripeKey(props.stripeKey)

function goBack() {
    if (checkoutStore.products) {
        window.location.href = '/order'
        return
    }

    if (checkoutStore.shipping) {
        checkoutStore.shipping = false
        checkoutStore.products = true
        checkoutStore.addressSavedMsg = false
        checkoutStore.newAddress = false
        return
    }

    if (checkoutStore.payment) {
        checkoutStore.payment = false
        checkoutStore.shipping = true
    }

    if (checkoutStore.summary) {
        checkoutStore.summary = false
        checkoutStore.payment = true
    }
}

function goForth() {
    if (checkoutStore.products) {

        for (let item of cartStore.cartItems) {
            if (!cartStore.checkQuantityInput(item.quantity) || !cartStore.checkQuantityRange(item.quantity)) {
                window.alert(ERROR_MESSAGES.quantityError + '\n' + ERROR_MESSAGES.clearCart)
                return
            }
        }

        checkoutStore.products = false
        checkoutStore.shipping = true
        return
    }

    if (checkoutStore.shipping) {
        if (!checkoutStore.deliveryType) {
            window.alert(ERROR_MESSAGES.chooseDelivery)
            return
        }

        if (checkoutStore.deliveryType === 'HOME' && !checkoutStore.newAddress && checkoutStore.addressId < 0) {
            window.alert(ERROR_MESSAGES.selectAddress)
            return
        }

        if (checkoutStore.deliveryType === 'HOME' && !checkoutStore.newAddress && checkoutStore.addressId >= 0) {
            checkoutStore.indexOfSavedAddress = checkoutStore.userAddresses.findIndex(address => address.id === checkoutStore.addressId)
        }

        if (checkoutStore.deliveryType === 'HOME' && checkoutStore.newAddress && !checkoutStore.addressSaved) {
            window.alert(ERROR_MESSAGES.addAddress)
            return
        }

        checkoutStore.shipping = false
        checkoutStore.payment = true

        checkoutStore.addressSavedMsg = false
        checkoutStore.newAddress = false

        return
    }

    if (checkoutStore.payment) {
        if (!checkoutStore.paymentType) {
            window.alert(ERROR_MESSAGES.choosePayment)
            return
        }

        if (checkoutStore.paymentType === 'STRIPE' && !checkoutStore.stripeSuccess) {
            window.alert(ERROR_MESSAGES.payWithStripe)
            return
        }

        checkoutStore.payment = false
        checkoutStore.summary = true
        return
    }

    return
}

</script>

<template>
    <page-layout>

        <div
            class="cart-div container py-3 px-5 m-auto mt-5 text-light d-flex justify-content-center align-items-center">

<!--            Show order date-->
            <div v-if="cartStore.cartItems.length > 0" class="text-center container">
                <h3>Your order for <span class="text-warning">{{ cartStore.getFormattedDeliveryDate() }}:</span></h3>
                <hr>

<!--                Cart navbar-->
                <div class="row">
                    <div class="col-md-8">
                        <ul class="nav nav-tabs">
                            <li class="nav-item me-1 py-2 fs-5" :class="{'activate fw-bold': checkoutStore.products}">
                                <div class="px-5">Products</div>
                            </li>
                            <li class="nav-item mx-1 py-2 fs-5" :class="{'activate fw-bold': checkoutStore.shipping}">
                                <div class="px-5">Shipping</div>
                            </li>
                            <li class="nav-item mx-1 py-2 fs-5" :class="{'activate fw-bold': checkoutStore.payment}">
                                <div class="px-5">Payment</div>
                            </li>
                            <li class="nav-item ms-1 py-2 fs-5" :class="{'activate fw-bold': checkoutStore.summary}">
                                <div class="px-5">Summary</div>
                            </li>
                        </ul>
                    </div>

<!--                    Clear cart button-->
                    <div class="col-md-3 text-end me-5">
                        <CustomButton
                            @click="cartStore.clearCart"
                            :text="BUTTONS.clearCart"/>
                    </div>

                </div>

<!--                Current checkout process-->
                <div class="items container m-auto mt-2 text-center">
                    <Products v-if="checkoutStore.products"/>
                    <Shipping v-if="checkoutStore.shipping"/>
                    <Payment v-if="checkoutStore.payment"/>
                    <Summary v-if="checkoutStore.summary"/>
                </div>

                <div class="row mt-5">

<!--                    Go back button-->
                    <div class="col-6 text-start">
                        <CustomButton
                            :text="BUTTONS.back"
                            @click="goBack"
                        />
                    </div>

<!--                    Go forth button-->
                    <div class="col-6 text-end">
                        <CustomButton
                            :text="BUTTONS.forth"
                            @click="goForth"
                            :class="{'d-none': checkoutStore.summary}"
                        />
                    </div>
                </div>
            </div>

<!--            If cart is empty, link to order page-->
            <div v-else class="text-center">
                <h3>Your cart is empty.</h3>
                <h4 class="mt-5">Click <a :href="LINKS.order" class="text-warning">HERE</a> to make a purchase.</h4>
            </div>
        </div>
    </page-layout>

</template>

<style scoped>

.cart-div {
    background-color: v-bind('STYLES.lightGrey');
    border: v-bind('STYLES.border');
    border-radius: 20px;
}

.items {
    position: relative;
    background-color: white;
    border: v-bind('STYLES.border');
    border-radius: 20px;
}

.nav-item {
    background-color: v-bind('STYLES.darkGrey');
    border: v-bind('STYLES.border');
    /* border-radius: 20px;*/
    color: white;
}

.activate {
    background-color: v-bind('STYLES.warningYellow');
    color: v-bind('STYLES.darkGrey');
}

</style>
