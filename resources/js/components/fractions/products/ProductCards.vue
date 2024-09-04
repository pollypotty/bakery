<script setup>
import ModalGallery from "./ModalGallery.vue"
import {useProductStore} from "../../../stores/product.js"
import {STYLES} from "../../../constants.js"

const productStore = useProductStore()

const props = defineProps(['productsToShow'])

</script>

<template>

<!--    Modal gallery component-->
    <ModalGallery />

<!--    Product card-->
    <div class="card-deck row p-2 mt-5 mx-md-2 mx-lg-5 d-flex justify-content-center align-items-center">
        <div v-for="product in props.productsToShow" :key="product.id"
             class="card card-main col-lg-3 col-md-4 col-sm-6 col-xs-6 m-2 mx-lg-3">
            <div class="container-fluid row d-flex justify-content-center align-items-center m-0">

<!--                Product feature image-->
                <div class="container d-flex justify-content-center align-items-center text-center my-2">
                    <img :src="productStore.getFeatureImage(product.product_images)"
                         class="card-img-top mx-2 my-2"
                         @click="productStore.openModal(product.id)"
                         :alt="product.name">
                </div>
            </div>

<!--            Product details-->
            <div class="card-body">
                <h5 class="card-title">{{ product.name }}</h5>
                <p class="card-text">{{ product.description }}</p>
            </div>
            <ul class="list-group list-group-flush text-center">
                <li class="list-group-item"><h5>Price: {{ product.price }} €</h5></li>
                <li class="list-group-item"><h5>Prepare days: {{ product.prepare_days }}</h5></li>

<!--                Slot for order quantity and button-->
                <slot :product_id="product.id" :quantity="1" />

            </ul>
        </div>
    </div>

</template>

<style scoped>

.card-main {
    position: relative;
    border-style: solid;
    border-width: 5px;
    border-color: v-bind('STYLES.darkGrey');
    border-radius: 20px;
}

.card-img-top {
    width: 75%;
    height: auto;
}

.card-deck {
    margin-bottom: 5%;
}

</style>
