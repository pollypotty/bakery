import {defineStore} from 'pinia'

export const useAuthenticationStore = defineStore('authentication', {
    state: () => {
        return {authentication: false}
    },
    persist: true,
    actions: {
        setAuth(auth) {
            this.authentication = auth
        }
    }
})
