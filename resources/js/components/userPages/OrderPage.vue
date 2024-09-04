<script setup>
import {useAuthenticationStore} from "../../stores/authentication.js"
import PageLayout from "../layouts/PageLayout.vue"
import {useProductStore} from "../../stores/product.js"
import {STYLES, ERROR_MESSAGES, BUTTONS, FILES, LINKS} from "../../constants.js"
import {useDateStore} from "../../stores/date.js"
import ProductCards from "../fractions/products/ProductCards.vue"
import CustomButton from "../fractions/reusables/CustomButton.vue"
import {ref, watch, onMounted} from "vue"
import {useCartStore} from "../../stores/cart.js"

const props = defineProps(['authenticated', 'products'])

const productStore = useProductStore()
productStore.setProducts(props.products)

const dateStore = useDateStore()
dateStore.setMinDate()
dateStore.setMaxDate()

const cartStore = useCartStore()

const dateInput = ref(null)
const isShaking = ref(false)
const dateChange = ref(false)

onMounted(() => {
    if (cartStore.deliveryDate !== null && cartStore.cartItems.length > 0) {
        dateInput.value = cartStore.deliveryDate
        dateStore.selectedDate = cartStore.deliveryDate
        dateStore.processDateInput()
    }
})

function handleDateInput() {
    if (!dateStore.isValidDateFormat(dateInput.value)) {
        window.alert(ERROR_MESSAGES.dateFormatError)
        return
    }

    if (!dateStore.isDateInRange(dateInput.value)) {
        window.alert(ERROR_MESSAGES.dateRangeError)
        return
    }

    if (!dateStore.isDateChangeSafe(dateInput.value)) {
        dateChange.value = true
        return
    }

    dateStore.selectedDate = dateInput.value
    dateStore.processDateInput()
}

function processDate() {
    dateChange.value = false

    dateStore.selectedDate = dateInput.value
    dateStore.processDateInput()
    cartStore.refreshCart()

    if (cartStore.removedNames.length > 0) {
        window.alert(ERROR_MESSAGES.removeProduct + cartStore.removedNames)
    }
}

function revertInput() {
    dateChange.value = false
    dateInput.value = dateStore.selectedDate
}

function addToCart(slotProps) {
    if (!cartStore.checkQuantityInput(slotProps.quantity)) {
        window.alert(ERROR_MESSAGES.quantityError)
        return
    }

    if (!cartStore.checkQuantityRange(slotProps.quantity)) {
        window.alert(ERROR_MESSAGES.quantityRangeError)
        return
    }

    cartStore.addItem(slotProps.product_id, slotProps.quantity)
}

watch(cartStore.cartItems, () => {
        isShaking.value = true
        setTimeout(() => {
            isShaking.value = false;
        }, 1000)
    }
)

</script>

<template>
    <page-layout>

        <div class="date-div row container py-3 px-2 m-auto mt-5 text-light d-flex justify-content-center align-items-center">

<!--            Date picker bar-->
            <div class="col-lg-3 col-md-12 mb-3 mb-md-2 text-start">
                <label class="fs-5">Select delivery date:</label>
            </div>

            <div class="col-lg-2 col-md-12 mb-3 mb-md-3 text-start">
                <input v-model="dateInput"
                       :min="dateStore.minDate"
                       :max="dateStore.maxDate"
                       type="date" class="w-100"
                >
            </div>

<!--            Date picker submit-->
            <div class="col-lg-2 col-md-12 mb-3 mb-md-2 ms-md-3 text-start">
                <CustomButton :text="BUTTONS.showProducts"
                              @click="handleDateInput"
                              :disabled="!dateInput"
                              :isLarge="true"
                />
            </div>

<!--            Clear cart button-->
            <div class="col-lg-2 col-md-12 mb-3 mb-md-2 ms-md-3 text-start">
                <CustomButton :text="BUTTONS.clearCart"
                              @click="cartStore.clearCart()"
                              :disabled="!cartStore.cartItems.length"
                              :isLarge="true"
                />
            </div>

<!--            Cart icon-->
            <div class="col-lg-2 col-md-12 d-flex justify-content-center justify-content-md-end">
                <div class="d-inline-block position-relative" :class="{'d-none': isShaking }">
                    <button class="items position-absolute bottom-0 start-0 h-50 disabled">{{
                            cartStore.cartItems.length
                        }}
                    </button>
                </div>
                <div class="d-inline-block align-bottom">
                    <a :href="LINKS.cart">
                        <img :src="FILES.cartImage"
                             :alt="BUTTONS.cartAlt"
                             :class="{'shake': isShaking}"
                             class="h-100 me-5"
                        >
                    </a>
                </div>
            </div>

<!--            No available products-->
            <div v-if="productStore.noAvailableProduct" class="container-fluid text-center mt-5 text-warning">
                <h4>There are no available products for the selected delivery date.</h4>
            </div>

<!--            Date change information, and pick to proceed or not-->
            <div v-if="dateChange" class="container-fluid text-center mt-5 text-warning">
                <h4>If you chose an earlier date you can lose items from your cart.</h4>
                <h4>Do you want to continue?</h4>
                <div class="row d-flex justify-content-center">

                    <CustomButton :text="BUTTONS.yes"
                                  @click="processDate"
                                  class="me-2"/>

                    <CustomButton :text="BUTTONS.no"
                                  @click="revertInput"
                                  class="ms-2"/>
                </div>
            </div>

        </div>

<!--        Available products-->
        <product-cards :productsToShow="productStore.availableProducts" v-slot="slotProps">

<!--            Quantity picker-->
            <li class="list-group-item">
                <div class="row">
                    <div class="col-6 text-center>">
                        <input v-model="slotProps.quantity"
                               type="number" class="w-50 text-center" min="1" max="50"
                        >
                    </div>

<!--                    Add to cart button-->
                    <div class="col-6 text-center>">
                        <CustomButton :text="BUTTONS.addToCart"
                                      @click="addToCart(slotProps)"
                        />
                    </div>
                </div>
            </li>

        </product-cards>
    </page-layout>

</template>

<style scoped>

.date-div {
    background-color: v-bind('STYLES.lightGrey');
    border: v-bind('STYLES.border');
    border-radius: 20px;
}

.items {
    background-color: #b44747;
    color: white;
    border-radius: 20px;
    border: 2px solid black;
    cursor: default;
}

.date-div img {
    max-width: 70px;
    height: auto;
    border-radius: 20px;
}

.shake {
    animation: shake-animation 0.5s infinite alternate;
}

@keyframes shake-animation {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(40px);
    }
}

</style>
