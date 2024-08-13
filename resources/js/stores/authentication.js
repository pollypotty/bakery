import {defineStore} from 'pinia'

export const useAuthenticationStore = defineStore('authentication', {
    state: () => {
        return {
            authentication: false,
            loginSuccess: true,
        }
    },
    persist: true,
    actions: {
        setAuth(auth) {
            this.authentication = auth
        },
        resetLoginSuccess() {
            this.loginSuccess = false
        }
    }
})
