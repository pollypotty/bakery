<script setup>
import {useProductStore} from "../../stores/product.js"
import {ref} from "vue"
import {STYLES} from "../../constants.js"

const productStore = useProductStore()
const currentSlide = ref(0)

window.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        productStore.closeModal()
    }

    if (event.key === 'ArrowRight') {
        slideTo(currentSlide.value + 1)
    }

    if (event.key === 'ArrowLeft') {
        slideTo(currentSlide.value - 1)
    }
})

function slideTo(index) {
    if (index === -1) {
        currentSlide.value = productStore.paths.length - 1
        return
    }

    if (index === productStore.paths.length) {
        currentSlide.value = 0
        return
    }

    currentSlide.value = index
}

</script>

<template>

    <div class="modal fade w-100 h-100"
         :class="{'show d-block':productStore.modalShow}"
         tabindex="-1"
         @click.self="productStore.closeModal"
    >
        <div class="image-container p-4 row d-flex align-items-center justify-content-center">
            <div class="col-3 text-center">
                <button @click="slideTo(currentSlide -1)" class="btn btn-lg"><</button>
            </div>
            <div class="col-6">
                <div>
                    <img :src="productStore.paths[currentSlide]" :alt="productStore.productName" class="p-2">
                </div>
            </div>
            <div class="col-3 text-center">
                <button @click="slideTo(currentSlide +1)" class="btn btn-lg">></button>
            </div>
        </div>
    </div>

</template>

<style scoped>


.modal img {
    margin: auto;
    display: block;
    max-width: 100%;
    height: auto;
    border: v-bind('STYLES.border');
    border-radius: 10px;
}

.image-container {
    background-color: v-bind('STYLES.modalImgContainer');
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: v-bind('STYLES.modalContainer');
    padding-top: 60px;
}

button {
    max-width: fit-content;
    background-color: black;
    color: white;
    border-radius: 25px;
}
</style>
