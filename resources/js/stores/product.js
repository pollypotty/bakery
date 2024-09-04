import {defineStore} from 'pinia'
import {FILES} from '../constants.js'
import {useDateStore} from './date.js'
import {useCartStore} from "./cart.js";

export const useProductStore = defineStore('product', {
    state: () => {
        return {
            products: [],
            featureImagePath: null,
            featureImageIndex: null,
            modalShow: false,
            modalImages: [],
            productName: null,
            paths: [],
            availableProducts: [],
            noAvailableProduct: false,
            availableProductId: [],
        }
    },
    actions: {
        setProducts(products) {
            this.products = products
        },
        getProducts() {
            return [...this.products].reverse()
        },
        getAvailableProducts() {
            return this.products.filter(product => product.availability !== 0)
        },
        getFeatureImage(product_images) {
            this.setFeatureImage(product_images)
            return this.featureImagePath
        },
        setFeatureImage(product_images) {
            if (!product_images.length) {
                this.featureImagePath = FILES.placeholderImagePath
                return
            }

            const featureImagePath
                = product_images.find(image => image.feature_image === 1)
            if (featureImagePath
            ) {
                this.featureImagePath
                    = featureImagePath.image_path
                return
            }

            this.featureImagePath
                = product_images[0].image_path
        },
        openModal(product_id) {
            const product = this.products.find(product => product.id === product_id)

            this.modalImages = product ? product.product_images : []

            if (!this.modalImages.length) {
                return
            }

            this.featureImagePath = this.getFeatureImage(product.product_images)
            this.paths = this.modalImages.map((image) => image.image_path)
            this.featureImageIndex = this.paths.indexOf(this.featureImagePath)

            if (this.featureImageIndex !== 0) {
                this.paths.splice(this.featureImageIndex, 1)
                this.paths.unshift(this.featureImagePath)
            }

            this.productName = product.name
            this.modalShow = true
        },
        closeModal() {
            this.modalShow = false
        },
        showAvailableProducts() {
            this.availableProducts = this.products.filter(product => product.prepare_days <= useDateStore().dayDifference)

            if (!this.availableProducts.length) {
                this.noAvailableProduct = true
                return
            }

            this.noAvailableProduct = false
        },
        getPriceOfProduct(product_id) {
            const product = this.products.find(product => product.id === product_id)
            return product.price
        },
        getMaxPrepareDays() {
            return this.products.reduce((maxPrepareDays, product) => {
                return Math.max(maxPrepareDays, product.prepare_days);
            }, this.products[0].prepare_days)
        },
        getNamesByIds() {
            const removedProducts = this.products.filter(product => useCartStore().removedIds.includes(product.id))
            const names = removedProducts.map(product => product.name);
            return names.join(', ');
        },
        getNameOfProduct(product_id) {
            const product = this.products.find(product => product.id === product_id)
            return product.name
        },
        updateProduct(updatedProduct) {
            const index = this.products.findIndex(product => product.id === updatedProduct.id)

            if (index !== -1) {
                this.products[index] = { ...updatedProduct, availability: Boolean(updatedProduct.availability) }
            }
        },
        deleteProduct(productId) {
            this.products = this.products.filter(product => product.id !== productId)
        },
        addProduct(productData) {
            this.products.push(productData)
        },
    }
})
