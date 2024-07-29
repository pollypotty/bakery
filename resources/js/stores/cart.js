import {defineStore} from 'pinia'
import {useProductStore} from "./product.js";
import {useDateStore} from "./date.js";
import {LINKS} from "../constants.js";
import {useCheckoutStore} from "./checkout.js";

export const useCartStore = defineStore('cart', {
    state: () => {
        return {
            deliveryDate: null,
            cartItems: [],
            removedIds: [],
            removedNames: '',
        }
    },
    persist: true,
    actions: {
        addItem(product_id, quantity) {
            this.deliveryDate = useDateStore().selectedDate

            if (this.cartItems.length) {
                const repeatedItem = this.cartItems.find(item => item.productId === product_id)

                if (repeatedItem) {
                    for (let item of this.cartItems) {
                        if (item.productId === product_id) {
                            item.quantity = item.quantity + quantity
                            return
                        }
                    }
                }
            }

            this.cartItems.push({
                productId: product_id,
                productName: useProductStore().getNameOfProduct(product_id),
                quantity: quantity,
                pricePerItem: useProductStore().getPriceOfProduct(product_id)
            })
        },
        checkQuantityInput(quantity) {
            if (!/^\d+$/.test(quantity))
            {
                return false;
            }

            return !(quantity < 1)
        },
        checkQuantityRange(quantity) {
            return !(quantity > 50)
        },
        refreshCart() {
            const availableIds = useProductStore().availableProducts.map(product => product.id)

            this.removedIds = []
            this.cartItems = this.cartItems.filter(item => {
                if (!availableIds.includes(item.productId)) {
                    this.removedIds.push(item.productId)
                    return false
                }
                return true
            })

            this.deliveryDate = useDateStore().selectedDate

            if (this.removedIds.length === 0) {
                return
            }

            this.removedNames = useProductStore().getNamesByIds()
        },
        getFormattedDeliveryDate() {
            const parts = this.deliveryDate.split('-')

            const year = parts[0];
            const month = parts[1].padStart(2, '0')
            const day = parts[2].padStart(2, '0')

            return `${month}/${day}/${year}`
        },
        deleteItem(product_id) {
            this.cartItems = this.cartItems.filter(item => item.productId !== product_id)
        },
        clearCart() {
            this.cartItems = []
            this.deliveryDate = null
            useCheckoutStore().resetState()
        }
    }
})
