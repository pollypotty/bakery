<script setup>
import {Form, Field, ErrorMessage} from 'vee-validate'
import {defineProps} from 'vue'
import CustomButton from "./CustomButton.vue"
import {useValidationErrorsStore} from "../../../stores/validation_errors.js"
import {STYLES} from "../../../constants.js"

const errorsStore = useValidationErrorsStore()
let errors = errorsStore.validation_errors

const props = defineProps({
    schema: Object,
    action: String,
    method: String,
    button_text: String,
})

const emits = defineEmits([
    'handleInput',
])

</script>

<template>

<!--    Dynamic form component-->
    <div class="form-div container-fluid p-5 mt-5 text-light col-12 text-center">
        <Form :action="action" :method="method">
            <div>

<!--                Errors coming from backend validation-->
                <div v-if="errors && errors.length > 0" class="alert alert-danger">
                    <div v-for="error in errors" :key="error" class="text-center">
                        {{ error }}
                    </div>
                </div>

<!--                Form fields-->
                <div v-for="{ as, name, label, ...attrs } in schema.fields" :key="name" class="m-1">
                    <label :for="name" class="form-label fs-5">{{ label }}</label>
                    <Field :as="as" :id="name" :name="name" v-bind="attrs"
                           @blur="$emit('handleInput', name, $event.target.value)"
                           class="form-control"/>

<!--                    Error messages for each field coming from frontend validation-->
                    <ErrorMessage :name="name" class="text-warning"/>
                </div>

<!--                Submit button-->
                <div class="row d-flex justify-content-center">
                    <CustomButton :text="button_text" :is-large="true" type="submit"/>
                </div>
            </div>
        </Form>
    </div>

</template>

<style scoped>

.form-div {
    background-color: v-bind('STYLES.lightGrey');
    border: v-bind('STYLES.border');
    border-radius: 20px;
}

</style>
