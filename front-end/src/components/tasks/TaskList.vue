<template>
  <div class="tasks-container">
    <div class="header">
      <h2>Tasks</h2>
      <button @click="isAddingTask = true" class="add-button">Add Task</button>
      <button @click="logout" class="logout-button">Logout</button>
    </div>

    <!-- Add Task Modal -->
    <div v-if="isAddingTask" class="modal">
      <div class="modal-content">
        <h3>Add New Task</h3>
        <form @submit.prevent="handleAddTask">
          <div class="form-group">
            <label for="title">Title:</label>
            <input
              type="text"
              id="title"
              v-model="newTask.title"
              required
              class="form-input"
            >
          </div>

          <div class="form-group">
            <label for="description">Description:</label>
            <textarea
              id="description"
              v-model="newTask.description"
              class="form-input"
            ></textarea>
          </div>

          <div class="form-group">
            <label for="status">Status:</label>
            <select
              id="status"
              v-model="newTask.status"
              required
              class="form-input"
            >
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
            </select>
          </div>

          <div class="form-group">
            <label for="priority">Priority:</label>
            <select
              id="priority"
              v-model="newTask.priority"
              required
              class="form-input"
            >
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
          </div>

          <div class="modal-buttons">
            <button type="submit" class="submit-button">Add</button>
            <button type="button" @click="isAddingTask = false" class="cancel-button">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Task Modal -->
    <div v-if="editingTask" class="modal">
      <div class="modal-content">
        <h3>Edit Task</h3>
        <form @submit.prevent="handleEditTask">
          <div class="form-group">
            <label for="edit-title">Title:</label>
            <input
              type="text"
              id="edit-title"
              v-model="editingTask.title"
              required
              class="form-input"
            >
          </div>

          <div class="form-group">
            <label for="edit-description">Description:</label>
            <textarea
              id="edit-description"
              v-model="editingTask.description"
              class="form-input"
            ></textarea>
          </div>

          <div class="modal-buttons">
            <button type="submit" class="submit-button">Save</button>
            <button type="button" @click="editingTask = null" class="cancel-button">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tasks List -->
    <div class="tasks-list">
      <div v-for="task in tasks" :key="task.id" class="task-card" :class="{ 'task-completed': task.status === 'completed' }">
        <div class="task-content">
          <h3>{{ task.title }}</h3>
          <p>{{ task.description }}</p>
          <span class="task-status">Status: {{ task.status }}</span>
        </div>
        <div class="task-actions">
          <button 
            v-if="task.status !== 'completed'"
            @click="completeTask(task.id)" 
            class="complete-button"
          >
            Mark as Complete
          </button>
          <button @click="startEdit(task)" class="edit-button">Edit</button>
          <button @click="deleteTask(task.id)" class="delete-button">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useTaskStore } from '../../store/tasks'
import { useAuthStore } from '../../store/auth'
import { storeToRefs } from 'pinia'

export default {
  name: 'TaskList',
  setup() {
    const router = useRouter()
    const taskStore = useTaskStore()
    const authStore = useAuthStore()
    const { tasks } = storeToRefs(taskStore)
    
    const isAddingTask = ref(false)
    const editingTask = ref(null)
    const newTask = ref({
      title: '',
      description: '',
      status: 'pending',
      priority: 'medium'
    })

    onMounted(async () => {
      try {
        console.log('Fetching tasks...')
        await taskStore.fetchTasks()
      } catch (error) {
        console.error('Failed to fetch tasks:', error)
      }
    })

    const completeTask = async (taskId) => {
      try {
        await taskStore.completeTask(taskId)
      } catch (error) {
        console.error('Error completing task:', error)
      }
    }

    const handleAddTask = async () => {
      try {
        await taskStore.createTask(
          newTask.value.title,
          newTask.value.description,
          newTask.value.status,
          newTask.value.priority
        )
        newTask.value = { 
          title: '', 
          description: '', 
          status: 'pending', 
          priority: 'medium' 
        }
        isAddingTask.value = false
      } catch (error) {
        alert('Failed to add task')
      }
    }

    const startEdit = (task) => {
      editingTask.value = { ...task }
    }

    const handleEditTask = async () => {
      try {
        await taskStore.updateTask(editingTask.value.id, {
          title: editingTask.value.title,
          description: editingTask.value.description
        })
        editingTask.value = null
      } catch (error) {
        alert('Failed to update task')
      }
    }

    const deleteTask = async (taskId) => {
      if (confirm('Are you sure you want to delete this task?')) {
        try {
          await taskStore.deleteTask(taskId)
        } catch (error) {
          alert('Failed to delete task')
        }
      }
    }

    const logout = () => {
      authStore.logout()
      router.push('/signin')
    }

    return {
      tasks,
      isAddingTask,
      editingTask,
      newTask,
      handleAddTask,
      startEdit,
      handleEditTask,
      deleteTask,
      completeTask,
      logout
    }
  }
}
</script>

<style scoped>
.tasks-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.add-button {
  padding: 8px 16px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.logout-button {
  padding: 8px 16px;
  background-color: #f44336;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  width: 100%;
  max-width: 500px;
}

.form-group {
  margin-bottom: 15px;
}

.form-input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

textarea.form-input {
  min-height: 100px;
}

.modal-buttons {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}

.submit-button {
  padding: 8px 16px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.cancel-button {
  padding: 8px 16px;
  background-color: #f44336;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}


.task-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.task-status {
  font-size: 0.9em;
  padding: 4px 8px;
  border-radius: 4px;
  text-transform: capitalize;
}

.task-status.completed {
  background-color: #4CAF50;
  color: white;
}

.task-status.pending {
  background-color: #FFC107;
  color: black;
}

.task-status.in_progress {
  background-color: #2196F3;
  color: white;
}

.complete-button {
  padding: 6px 12px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 8px;
}

.complete-button:hover {
  background-color: #45a049;
}
.tasks-list {
  display: grid;
  gap: 20px;
}

.task-card {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px;
  display: flex;
  justify-content: space-between;
  align-items: start;
}

.task-completed {
  background-color: #f8f9fa;
  border-color: #e9ecef;
}

.task-completed h3,
.task-completed p {
  color: #6c757d;
}

.task-status {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 4px;
  background-color: #e9ecef;
  color: #495057;
  font-size: 0.875rem;
  margin-top: 8px;
}

.task-content {
  flex: 1;
}

.task-content h3 {
  margin: 0 0 10px 0;
}

.task-content p {
  margin: 0;
  color: #666;
}

.task-actions {
  display: flex;
  gap: 10px;
}

.edit-button {
  padding: 6px 12px;
  background-color: #2196F3;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.delete-button {
  padding: 6px 12px;
  background-color: #f44336;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  opacity: 0.9;
}
</style>
