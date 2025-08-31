<template>
  <div class="auth-container">
    <h2>Sign Up</h2>
    <form @submit.prevent="handleSubmit" class="auth-form">
      <div class="form-group">
        <label for="name">Name:</label>
        <input
          type="text"
          id="name"
          v-model="name"
          required
          class="form-input"
        >
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input
          type="email"
          id="email"
          v-model="email"
          required
          class="form-input"
        >
      </div>
      
      <div class="form-group">
        <label for="password">Password:</label>
        <input
          type="password"
          id="password"
          v-model="password"
          required
          class="form-input"
        >
      </div>
      
      <button type="submit" class="submit-button">Sign Up</button>
      
      <p class="auth-link">
        Already have an account? 
        <router-link to="/signin">Sign In</router-link>
      </p>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../store/auth'

export default {
  name: 'SignUp',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const name = ref('')
    const email = ref('')
    const password = ref('')
    
    const handleSubmit = async () => {
      try {
        await authStore.register(name.value, email.value, password.value)
        router.push('/tasks')
      } catch (error) {
        alert('Registration failed')
      }
    }
    
    return {
      name,
      email,
      password,
      handleSubmit
    }
  }
}
</script>

<style scoped>
.auth-container {
  max-width: 400px;
  margin: 40px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-input {
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.submit-button {
  padding: 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.submit-button:hover {
  background-color: #45a049;
}

.auth-link {
  text-align: center;
}

.auth-link a {
  color: #4CAF50;
  text-decoration: none;
}
</style>
