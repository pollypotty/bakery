<script setup>
import {ref, onMounted} from 'vue'
import {loadStripe} from '@stripe/stripe-js'
import {useCheckoutStore} from "../../stores/checkout.js"
import {BUTTONS, ERROR_MESSAGES, LINKS, STYLES} from "../../constants.js"
import CustomButton from "./CustomButton.vue"

const token = document.head.querySelector('meta[name="csrf-token"]').content

const checkoutStore = useCheckoutStore()

const stripePromise = loadStripe(checkoutStore.stripeKey)

const error = ref('')

let cardElement = null

onMounted(async () => {
    try {
        const stripe = await stripePromise
        const elements = stripe.elements()

        cardElement = elements.create('card')
        cardElement.mount('#card-element')
    } catch (err) {
        error.value = ERROR_MESSAGES.stripeInitError
    }
})

const handleSubmit = async () => {
    error.value = null

    if (!cardElement) {
        error.value = ERROR_MESSAGES.stripeError
        return
    }

    const stripe = await stripePromise

    try {
        //Make a POST request to create a payment intent with amount
        const response = await fetch( 'api' + LINKS.stripePayment,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({
                amount: checkoutStore.getPaymentTotal(),
            })
        })

        //Parse the response to get the client secret
        const data = await response.json()

        //Catching 4xx or 5xx status codes
        if (!response.ok) {
            throw new Error(ERROR_MESSAGES.unexpectedError)
        }

        //Ensure card element is properly initialized
        if (!cardElement) {
            throw new Error(ERROR_MESSAGES.noCardElement)
        }

        //Confirming card payment
        const result = await stripe.confirmCardPayment(data.clientSecret, {
            payment_method: {
                card: cardElement,
            },
        })

        if (result.error) {
            error.value = result.error.message;
        } else {
            if (result.paymentIntent.status === 'succeeded') {
                checkoutStore.stripeSuccess = true
            }
        }
    } catch (err) {
        error.value = err.message || ERROR_MESSAGES.stripeError
    }
}

</script>

<template>

<!--    Div containing the stripe payment form-->
    <div class="row d-flex justify-content-center mb-3">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <form @submit.prevent="handleSubmit">
                <div id="card-element"><!-- Stripe Element will be inserted here --></div>

<!--                Submit button with the due amount-->
                <CustomButton
                    type="submit" :text="BUTTONS.proceedPayment + ' ' + useCheckoutStore().getPaymentTotal() + '€'"
                    class="mb-2"/>
                <p v-if="error" class="errorMsg">{{ error }}</p>
            </form>
        </div>
    </div>

</template>


<style scoped>

#card-element {
    margin: 10px 0;
    padding: 25px;
    border: v-bind("STYLES.border");
    border-radius: 20px;
    background-color: v-bind("STYLES.lightGreyTrans");
}

.errorMsg {
    color: v-bind("STYLES.warningYellow");
}
</style>
