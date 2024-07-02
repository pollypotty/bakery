import {defineStore} from 'pinia'
import {useProductStore} from "./product.js"
import {useCartStore} from "./cart.js";

export const useDateStore = defineStore('date', {
    state: () => {
        return {
            minDate: null,
            maxDate: null,
            selectedDate: null,
            dayDifference: null,
        }
    },
    persist: true,
    actions: {
        setMinDate() {
            const today = new Date()
            const twoDaysFromToday = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 2)
            this.minDate = this.formatDate(twoDaysFromToday)
        },
        setMaxDate() {
            const today = new Date()
            const tenDaysFromToday = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 10)
            this.maxDate = this.formatDate(tenDaysFromToday)
        },
        formatDate(date) {
            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        processDateInput() {
            const today = new Date()
            today.setHours(0, 0, 0, 0);
            const selected = new Date(this.selectedDate)

            let differenceInMilliseconds = selected.getTime() - today.getTime()
            this.dayDifference = Math.round(differenceInMilliseconds / (1000 * 3600 * 24))
            useProductStore().showAvailableProducts()
        },
        isValidDateFormat(input) {
            const dateFormatRegex = /^\d{4}-\d{2}-\d{2}$/

            return dateFormatRegex.test(input);
        },
        isDateInRange(input) {
            const inputDate = new Date(input)
            const rangeMin = new Date(this.minDate)
            const rangeMax = new Date(this.maxDate)

            return inputDate >= rangeMin && inputDate <= rangeMax
        },
        isDateChangeSafe(inputDate) {
            if (!useCartStore().cartItems.length) {
                return true
            }

            const today = new Date
            today.setHours(0, 0, 0, 0);
            const newDate = new Date(inputDate)

            const maxPrepareDays = useProductStore().getMaxPrepareDays()
            const firstDateWithAllProducts = new Date(today.getFullYear(), today.getMonth(), today.getDate()
                + maxPrepareDays)

            if (newDate >= firstDateWithAllProducts) {
                return true
            }

            const previousDate = new Date(this.selectedDate)
            return newDate >= previousDate;
        },
    }
})
