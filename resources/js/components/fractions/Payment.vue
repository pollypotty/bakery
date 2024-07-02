<script setup>
import {STYLES} from "../../constants.js"
import {useCheckoutStore} from "../../stores/checkout.js"

const checkoutStore = useCheckoutStore()
</script>

<template>


    <div class="container-fluid">

        <div v-if="checkoutStore.deliveryType === 'HOME'">
            <div class="row">
                <div class="row items-head text-light p-2 mt-3 mb-4 mx-2 text-start col-10">
                    <h4>Choose payment option:</h4>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-10 col-sm-12 mb-5 text-dark">
                        <div class="row">
                            <div class="col-2 text-end">
                                <input
                                    v-model="checkoutStore.paymentType"
                                    type="radio" name="paymentType" value="CASH">
                            </div>
                            <div class="col-10 text-start">
                                <h4><label>Cash at delivery</label></h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2 text-end">
                                <input
                                    v-model="checkoutStore.paymentType"
                                    type="radio" name="paymentType" value="CARD">
                            </div>
                            <div class="col-10 text-start">
                                <h4><label>Card at delivery</label></h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2 text-end">
                                <input
                                    v-model="checkoutStore.paymentType"
                                    type="radio" name="paymentType" value="STRIPE">
                            </div>
                            <div class="col-10 text-start">
                                <h4><label>Online payment with Stripe</label></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="checkoutStore.deliveryType === 'PICK_UP'">
            <div class="row">
                <div class="row items-head text-light p-2 mt-3 mb-4 mx-2 text-start col-10">
                    <h4>Payment:</h4>
                </div>

                <div class="col-lg-6 col-md-10 col-sm-12 mb-5 text-dark">
                    <div class="row">
                        <div class="text-start ms-5">
                            <h4>Online payment with Stripe</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<style scoped>

.items-head {
    background-color: v-bind('STYLES.lightGrey');
}

.items-head {
    border-radius: 20px;
}

</style>
