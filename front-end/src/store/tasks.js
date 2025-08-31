import { defineStore } from 'pinia'
import axios from 'axios'
import { getApiBaseUrl } from '../utils/tenant'

export const useTaskStore = defineStore('tasks', {
  state: () => ({
    tasks: []
  }),
  
  actions: {
    async fetchTasks() {
      try {
        const response = await axios.get(`${getApiBaseUrl()}/tasks`)
        this.tasks = response.data.data ?? []
        console.log(response.data.data)
      } catch (error) {
        console.error('Error fetching tasks:', error)
        throw error
      }
    },
    
    async createTask(title, description, status, priority) {
      try {
        const response = await axios.post(`${getApiBaseUrl()}/tasks`, {
          title,
          description,
          status,
          priority
        })
        if (!Array.isArray(this.tasks)) {
          this.tasks = []
        }
        this.tasks.push(response.data)
      } catch (error) {
        console.error('Error creating task:', error)
        throw error
      }
    },
    
    async updateTask(taskId, data) {
      try {
        const response = await axios.put(`${getApiBaseUrl()}/tasks/${taskId}`, data)
        const index = this.tasks.findIndex(task => task.id === taskId)
        if (index !== -1) {
          this.tasks[index] = response.data
        }
      } catch (error) {
        console.error('Error updating task:', error)
        throw error
      }
    },

    async completeTask(taskId) {
      try {
        const response = await axios.patch(`${getApiBaseUrl()}/tasks/${taskId}/complete`)
        const index = this.tasks.findIndex(task => task.id === taskId)
        if (index !== -1) {
          this.tasks[index] = response.data
        }
      } catch (error) {
        console.error('Error completing task:', error)
        throw error
      }
    },
    
    async deleteTask(taskId) {
      try {
        await axios.delete(`${getApiBaseUrl()}/tasks/${taskId}`)
        this.tasks = this.tasks.filter(task => task.id !== taskId)
      } catch (error) {
        console.error('Error deleting task:', error)
        throw error
      }
    }
  }
})
