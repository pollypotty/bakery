import {defineStore} from 'pinia'
import {flashMessage} from "@smartweb/vue-flash-message"

export const useFlashMessageStore = defineStore('flash_message', {
    state: () => {
        return {}
    },
    actions: {
        displayMessage(message, type) {
            flashMessage.show({
            text: message,
            type: type,
            time: 5000,
        })
        }
    }
})
