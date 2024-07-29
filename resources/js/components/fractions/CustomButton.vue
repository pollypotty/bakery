<script setup>
import {STYLES} from "../../constants.js"
import {boolean} from "yup"

 const props = defineProps({
     text: String,
     type: String,
     isLarge: {
         type: Boolean,
         default: false,
     },
 })

</script>

<template>

<!--    Custom button, choose text and size-->
<button :type="type" :class="['btn', {'btn-lg': isLarge}]">{{text}}</button>

</template>

<style scoped>

button {
    max-width: fit-content;
    background-color: v-bind('STYLES.darkGrey');
    color: white;
    border-radius: 20px;
}

</style>
