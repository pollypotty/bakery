import {defineStore} from 'pinia'

export const useAdminStore = defineStore('admin', {
    state: () => {
        return {
            authentication: false,
        }
    },
    persist: true,
    actions: {
        setAdminAuth(auth) {
            this.authentication = auth
        }
    }
})
