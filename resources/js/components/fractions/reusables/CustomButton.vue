<script setup>
import {STYLES} from "../../../constants.js"

const props = defineProps({
    text: String,
    type: String,
    isLarge: {
        type: Boolean,
        default: false,
    },
    color: {
        type: String,
        default: STYLES.darkGrey,
    }
})

</script>

<template>

    <!--    Custom button, choose text and size-->
    <button
        :type="type"
        :class="['btn', {'btn-lg': isLarge}]"
        :style="{ backgroundColor: color}"
    >
        {{ text }}
    </button>

</template>

<style scoped>

button {
    max-width: fit-content;
    color: white;
    border-radius: 20px;
}

</style>
