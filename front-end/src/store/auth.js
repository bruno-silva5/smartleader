import { defineStore } from 'pinia'
import axios from 'axios'
import { getApiBaseUrl } from '../utils/tenant'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token
  },

  actions: {
    async login(email, password) {
      try {
        const response = await axios.post(`${getApiBaseUrl()}/login`, {
          email,
          password
        })

        this.token = response.data.access_token
        this.user = response.data.user
        localStorage.setItem('token', this.token)

        // Set the token in axios headers for all future requests
        axios.defaults.headers.common['Authorization'] = `${response.data.token_type} ${this.token}`

        return true
      } catch (error) {
        console.error('Login error:', error)
        throw error
      }
    },

    async register(name, email, password) {
      try {
        const response = await axios.post(`${getApiBaseUrl()}/register`, {
          name,
          email,
          password
        })

        this.token = response.data.token
        this.user = response.data.user
        localStorage.setItem('token', this.token)

        // Set the token in axios headers for all future requests
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`

        return true
      } catch (error) {
        console.error('Registration error:', error)
        throw error
      }
    },

    logout() {
      this.user = null
      this.token = null
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
    }
  }
})
