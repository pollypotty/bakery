<script setup>
import {useProductStore} from "../../stores/product.js"
import AdminLayout from "../layouts/AdminLayout.vue"
import {BUTTONS, ERROR_MESSAGES, LINKS, STYLES, SUCCESS_MESSAGES} from "../../constants.js"
import CustomButton from "../fractions/reusables/CustomButton.vue"
import {ref} from "vue"
import {useFlashMessageStore} from "../../stores/flash_message.js"

const props = defineProps(['products'])

const token = document.head.querySelector('meta[name="csrf-token"]').content

const productStore = useProductStore()
productStore.setProducts(props.products)

const flashStore = useFlashMessageStore()

const editingProductId = ref(null)
const editedProduct = ref({})

const newProduct = ref({})
const newProductRow = ref(false)
const productValidationErrors = ref({})

function editItem(productId) {
    productValidationErrors.value = {}

    const product = productStore.getProducts().find((product) => product.id === productId)
    editingProductId.value = productId
    editedProduct.value = {
        ...product,
        availability: Boolean(product.availability)
    }

    newProductRow.value = false
}

function cancelEdit() {
    editingProductId.value = null
}

function processEdit() {
    validateProduct(editedProduct)

    if (Object.keys(productValidationErrors.value).length !== 0) {
        return
    }

    const editData = {
        name: editedProduct.value.name,
        description: editedProduct.value.description,
        prepare_days: parseInt(editedProduct.value.prepare_days, 10),
        price: editedProduct.value.price,
        availability: editedProduct.value.availability ? 1 : 0,
    }

    saveEdit(editData, editedProduct.value.id)
}

async function saveEdit(editData, productId) {
    try {
        const response = await fetch('api' + LINKS.products + '/' + productId, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': token,
            },
            body: JSON.stringify(editData)
        })


        // handle error response, flash error message
        if (!response.ok) {
            const errorData = await response.json()

            flashStore.displayMessage(errorData.message || response.statusText, 'error')
            return
        }

        // handle success response, flash success, reset editing state
        const updatedProduct = await response.json();
        productStore.updateProduct(updatedProduct)
        editingProductId.value = null

        flashStore.displayMessage(SUCCESS_MESSAGES.editSuccess + updatedProduct.name, 'success')


    } catch (error) {
        flashStore.displayMessage(ERROR_MESSAGES.unexpectedError, 'error')
    }
}

function deleteItem(productId) {
    const confirmDeletion = confirm(SUCCESS_MESSAGES.confirmDeletion)

    if (confirmDeletion) {
        processDeleteItem(productId)
    }
}

async function processDeleteItem(productId) {
    try {
        const response = await fetch('api' + LINKS.products + '/' + productId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': token,
            }
        })

        // handle error response
        if (!response.ok) {
            const errorData = await response.json()

            flashStore.displayMessage(errorData.message || response.statusText, 'error')
            return
        }

        // handle success response
        flashStore.displayMessage(SUCCESS_MESSAGES.deleteSuccess + productStore.getNameOfProduct(productId), 'success')
        productStore.deleteProduct(productId)

    } catch (error) {
        flashStore.displayMessage(ERROR_MESSAGES.unexpectedError, 'error')
    }
}

function addNewProduct() {
    editingProductId.value = null
    editingProductId.value = null
    newProductRow.value = !newProductRow.value
    productValidationErrors.value = {}
}

function cancelNewProduct() {
    newProductRow.value = false
    newProduct.value = {}
}

function processNewProduct() {
    validateProduct(newProduct)

    if (Object.keys(productValidationErrors.value).length !== 0) {
        return
    }

    newProduct.value.availability = newProduct.value.availability ? 1 : 0
    saveNewProduct()
}

async function saveNewProduct() {
    try {
        const response = await fetch('api' + LINKS.products, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': token,
            },
            body: JSON.stringify(newProduct.value)
        })

        // handle error response, flash error message
        if (!response.ok) {
            const errorData = await response.json()

            flashStore.displayMessage(errorData.message || response.statusText, 'error')
            return
        }

        // handle success response
        const productData = await response.json()
        flashStore.displayMessage(SUCCESS_MESSAGES.productSaveSuccess + productData.name, 'success')
        productStore.addProduct(productData)
        newProductRow.value = false
        newProduct.value = {}

    } catch (e) {
        flashStore.displayMessage(ERROR_MESSAGES.unexpectedError, 'error')
    }
}

function validateProduct(product) {
    productValidationErrors.value = {}

    if (!product.value.name || product.value.name.length > 30) {
        productValidationErrors.value.name = ERROR_MESSAGES.nameRequired
    }

    if (!product.value.description || product.value.description.length > 255) {
        productValidationErrors.value.description = ERROR_MESSAGES.descriptionRequired
    }

    const prepareDaysValue = Number(product.value.prepare_days)
    if (
        !Number.isInteger(prepareDaysValue) ||
        prepareDaysValue <= 0
    ) {
        productValidationErrors.value.prepare_days = ERROR_MESSAGES.invalidPrepDays
    }

    const pricePattern = /^\d+\.\d{2}$/
    if (!product.value.price || !pricePattern.test(product.value.price)) {
        productValidationErrors.value.price = ERROR_MESSAGES.invalidPrice
    }
}

</script>

<template>
    <admin-layout>
        <div class="container-fluid text-end mt-3 mb-3">
            <CustomButton
                :text="BUTTONS.newProduct"
                :color="STYLES.buttonGreen"
                :is-large="true"
                @click="addNewProduct"
            />
        </div>
        <div
            class="cart-div container-fluid py-3 px-5 text-light d-flex justify-content-center align-items-center">
            <div class="items container-fluid m-auto mt-2 text-center">
                <div class="container-fluid">

                    <!--        Table head-->
                    <div class="row items-head text-light p-2 mt-2 mx-2 justify-content-center align-items-center">
                        <div class="col-3">Name</div>
                        <div class="col-4">Description</div>
                        <div class="col-1">Prep days</div>
                        <div class="col-1">Price</div>
                        <div class="col-1">Availability</div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>

                    </div>

                    <!--        Products-->
                    <div class="row">
                        <ul class="list-group list-group-flush w-100">

                            <!--                            Row for registering new product-->
                            <li
                                v-if="newProductRow && !editingProductId"
                                class="list-group-item"
                            >
                                <div class="row justify-content-center align-items-center my-2">

                                    <!--                                New product name-->
                                    <div class="col-3">
                                        <input
                                            v-model="newProduct.name"
                                            type="text"
                                            class="form-control"
                                        />
                                        <span>{{ productValidationErrors.name }}</span>
                                    </div>

                                    <!--                                New product description-->
                                    <div class="col-4">
                                     <textarea
                                         v-model="newProduct.description"
                                         class="form-control"
                                         rows="5"
                                     >
                                            </textarea>
                                        <span>{{ productValidationErrors.description }}</span>
                                    </div>

                                    <!--                                New product prep days-->
                                    <div class="col-1">
                                        <input
                                            v-model="newProduct.prepare_days"
                                            type="text"
                                            class="form-control"
                                        />
                                        <span>{{ productValidationErrors.prepare_days }}</span>
                                    </div>

                                    <!--                                New product price-->
                                    <div class="col-1">
                                        <input
                                            v-model="newProduct.price"
                                            type="text"
                                            class="form-control"
                                        />
                                        <span>{{ productValidationErrors.price }}</span>
                                    </div>

                                    <!--                                New product availability-->
                                    <div class="col-1">
                                        <input
                                            v-model="newProduct.availability"
                                            type="checkbox"
                                            class="form-check-input"
                                        />
                                    </div>

                                    <!--                                    New product save button-->
                                    <div class="col-1">
                                        <CustomButton
                                            :text="BUTTONS.save"
                                            :color="STYLES.buttonGreen"
                                            @click="processNewProduct"
                                        />
                                    </div>

                                    <!--                                    New product cancel button-->
                                    <div class="col-1">
                                        <CustomButton
                                            :text="BUTTONS.cancel"
                                            :color="STYLES.buttonRed"
                                            @click="cancelNewProduct"
                                        />
                                    </div>
                                </div>
                            </li>
                            <li
                                v-for="product in productStore.getProducts()"
                                :key="product.productId"
                                class="list-group-item"
                            >
                                <div class="row justify-content-center align-items-center my-2">

                                    <!--                                    Check if current product is being edited-->
                                    <template v-if="editingProductId === product.id">

                                        <!--                                        Editable row for name-->
                                        <div class="col-3">
                                            <input
                                                v-model="editedProduct.name"
                                                type="text"
                                                class="form-control"
                                            />
                                            <span>{{ productValidationErrors.name }}</span>
                                        </div>

                                        <!--                                        Editable row for description-->
                                        <div class="col-4">
                                            <textarea
                                                v-model="editedProduct.description"
                                                class="form-control"
                                                rows="5"
                                            >
                                            </textarea>
                                            <span>{{ productValidationErrors.description }}</span>
                                        </div>

                                        <!--                                        Editable row for prepare days-->
                                        <div class="col-1">
                                            <input
                                                v-model="editedProduct.prepare_days"
                                                type="text"
                                                class="form-control"
                                            />
                                            <span>{{ productValidationErrors.prepare_days }}</span>
                                        </div>

                                        <!--                                        Editable row for price-->
                                        <div class="col-1">
                                            <input
                                                v-model="editedProduct.price"
                                                type="text"
                                                class="form-control"
                                            />
                                            <span>{{ productValidationErrors.price }}</span>
                                        </div>

                                        <!--                                        Editable row for availability-->
                                        <div class="col-1">
                                            <input
                                                v-model="editedProduct.availability"
                                                type="checkbox"
                                                class="form-check-input"
                                            />
                                        </div>

                                        <!--                                        Save button-->
                                        <div class="col-1">
                                            <CustomButton
                                                :text="BUTTONS.save"
                                                :color="STYLES.buttonGreen"
                                                @click="processEdit"
                                            />
                                        </div>

                                        <!--                                        Cancel button-->
                                        <div class="col-1">
                                            <CustomButton
                                                :text="BUTTONS.cancel"
                                                :color="STYLES.buttonRed"
                                                @click="cancelEdit"
                                            />
                                        </div>

                                    </template>

                                    <!--                                    Not editable rows for products-->
                                    <template v-else>
                                        <!--                        Name-->
                                        <div class="col-3">{{ product.name }}</div>

                                        <!--                        Description-->
                                        <div class="col-4">{{ product.description }}</div>

                                        <!--                                        Preparation days-->
                                        <div class="col-1">{{ product.prepare_days }}</div>

                                        <!--                                        Price-->
                                        <div class="col-1">{{ product.price }} €</div>

                                        <!--                                        Availability-->
                                        <div v-if="product.availability" class="col-1 available">✔</div>
                                        <div v-else class="col-1"></div>

                                        <!--                                    Edit item-->
                                        <div class="col-1">
                                            <CustomButton
                                                v-if="!editingProductId"
                                                :text="BUTTONS.edit"
                                                :color="STYLES.buttonGreen"
                                                @click="editItem(product.id)"
                                            />
                                        </div>

                                        <!--                                    Delete item-->
                                        <div class="col-1">
                                            <CustomButton
                                                v-if="!editingProductId"
                                                :text="BUTTONS.delete"
                                                :color="STYLES.buttonRed"
                                                @click="deleteItem(product.id)"
                                            />
                                        </div>
                                    </template>


                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <!--        Flash message-->
        <FlashMessage position="right bottom"/>
    </admin-layout>


</template>

<style scoped>

.cart-div {
    background-color: v-bind('STYLES.lightGrey');
    border: v-bind('STYLES.border');
    border-radius: 20px;
}

.items-head {
    font-size: larger;
    font-weight: bold;
}

input, textarea {
    background-color: v-bind('STYLES.inputYellow');
}

span {
    color: v-bind('STYLES.buttonRed');
}

</style>
