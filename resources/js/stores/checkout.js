import {defineStore} from 'pinia'
import {useCartStore} from "./cart.js";

export const useCheckoutStore = defineStore('checkout', {
    state: () => {
        return {
            products: true,
            shipping: false,
            payment: false,
            summary: false,
            deliveryType: "",
            userAddresses: [],
            addressId: -1,
            newAddress: false,
            addressType: null,
            addressSaved: false,
            newAddressDetails: [],
            paymentType: "",
            indexOfSavedAddress: '',
            addressSavedMsg: false,
        }
    },
    persist: true,
    actions: {
        getPaymentTotal() {
            const cartStore = useCartStore()
            let total = 0

            for (let i = 0; i < cartStore.cartItems.length; i++) {
                total += cartStore.cartItems[i].pricePerItem * cartStore.cartItems[i].quantity
            }

            return total
        },
        getOrderItems() {
            const cartStore = useCartStore()

            return cartStore.cartItems.map(({ productId, quantity }) => ({ productId, quantity }))
        }
    }
})
