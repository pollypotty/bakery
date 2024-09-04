<script setup>
import {useCartStore} from "../../../stores/cart.js"
import CustomButton from "../reusables/CustomButton.vue"
import {BUTTONS, ERROR_MESSAGES} from "../../../constants.js"
import {STYLES} from "../../../constants.js"

const cartStore = useCartStore()

function checkQuantityInput() {
    if (!cartStore.checkQuantityInput(event.target.value)) {
        window.alert(ERROR_MESSAGES.quantityError)
        return
    }

    if (!cartStore.checkQuantityRange(event.target.value)) {
        window.alert(ERROR_MESSAGES.quantityRangeError)
    }
}
</script>

<template>

    <!--    Selected Products summary-->
    <div class="container-fluid">

<!--        Table head-->
        <div class="row items-head text-light p-2 mt-2 mx-2">
            <div class="col-5">Item</div>
            <div class="col-2">Price</div>
            <div class="col-2">Quantity</div>
            <div class="col-2">Remove</div>
            <div class="col-1">Total</div>
        </div>

<!--        Products-->
        <div class="row">
            <ul class="list-group list-group-flush w-100">
                <li v-for="item in cartStore.cartItems" :key="item.productId" class="list-group-item">
                    <div class="row">

<!--                        Name-->
                        <div class="col-5">{{ item.productName }}</div>

<!--                        Price per item-->
                        <div class="col-2">{{ item.pricePerItem }} €</div>

<!--                        Quantity change option-->
                        <div class="col-2">
                            <input v-model="item.quantity"
                                   @input="checkQuantityInput()"
                                   type="number" class="w-75 text-center form-control" min="1" max="50"
                            >
                        </div>

<!--                        Delete item option-->
                        <div class="col-2">
                            <CustomButton :text="BUTTONS.x"
                                          @click="cartStore.deleteItem(item.productId)"
                            />
                        </div>

<!--                        Price sum per item-->
                        <div class="col-1">{{ item.pricePerItem * item.quantity }} €</div>
                    </div>
                </li>
            </ul>
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
