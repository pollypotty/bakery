<script setup>
import PageLayout from "../layouts/PageLayout.vue"
import {useProductStore} from "../../stores/product.js"
import ProductCards from "../fractions/ProductCards.vue"
import {useAuthenticationStore} from "../../stores/authentication.js"

const props = defineProps(['products', 'authenticated'])

const productStore = useProductStore()
productStore.setProducts(props.products)

</script>

<template>
    <page-layout>

        <ProductCards :productsToShow = productStore.products />

    </page-layout>
</template>

<style scoped>

</style>
