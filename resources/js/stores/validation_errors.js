import {defineStore} from 'pinia'

export const useValidationErrorsStore = defineStore('validation_errors', {
    state: () => {
        return {validation_errors: null}
    },
    actions: {
        setValidationErrors(errors) {
            this.validation_errors = errors
        }
    }
})
