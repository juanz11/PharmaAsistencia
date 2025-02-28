<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Estadísticas -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <stat-card
          title="Total Usuarios"
          :value="statistics.total"
          color="blue"
          icon="users"
        />
        <stat-card
          title="Usuarios Activos"
          :value="statistics.active"
          color="green"
          icon="user-check"
        />
        <stat-card
          title="Nuevos este mes"
          :value="statistics.new"
          color="purple"
          icon="user-plus"
        />
      </div>

      <!-- Barra de herramientas -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
          <h1 class="text-2xl font-bold text-gray-900">Gestión de Usuarios</h1>
          <div class="flex gap-4">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar usuarios..."
                class="pl-10 pr-4 py-2 border rounded-lg w-64 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                @input="handleSearch"
              />
              <span class="absolute left-3 top-2.5 text-gray-400">
                <i class="fas fa-search"></i>
              </span>
            </div>
            <button
              @click="showCreateModal = true"
              class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2"
            >
              <i class="fas fa-plus"></i>
              Nuevo Usuario
            </button>
          </div>
        </div>
      </div>

      <!-- Tabla de usuarios -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th v-for="header in tableHeaders" 
                  :key="header.key"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                {{ header.label }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                    <span class="text-white font-medium">{{ getUserInitials(user.name) }}</span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="getStatusClass(user.status)">
                  {{ user.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ user.identification }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ user.location }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(user.join_date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end gap-2">
                  <button @click="editUser(user)" 
                          class="text-blue-600 hover:text-blue-900">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button @click="deleteUser(user)" 
                          class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-4 rounded-lg shadow">
        <pagination
          :total="totalPages"
          :current="currentPage"
          @page-change="handlePageChange"
        />
      </div>
    </div>

    <!-- Modales -->
    <create-user-modal
      v-if="showCreateModal"
      @close="showCreateModal = false"
      @user-created="handleUserCreated"
    />
    
    <edit-user-modal
      v-if="showEditModal"
      :user="selectedUser"
      @close="showEditModal = false"
      @user-updated="handleUserUpdated"
    />
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import StatCard from './StatCard.vue'
import Pagination from './Pagination.vue'
import CreateUserModal from './CreateUserModal.vue'
import EditUserModal from './EditUserModal.vue'

export default {
  name: 'UserManagement',
  
  components: {
    StatCard,
    Pagination,
    CreateUserModal,
    EditUserModal
  },

  setup() {
    const users = ref([])
    const statistics = ref({
      total: 0,
      active: 0,
      new: 0
    })
    const searchQuery = ref('')
    const currentPage = ref(1)
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const selectedUser = ref(null)

    const tableHeaders = [
      { key: 'name', label: 'Nombre' },
      { key: 'status', label: 'Estado' },
      { key: 'identification', label: 'Identificación' },
      { key: 'location', label: 'Ubicación' },
      { key: 'join_date', label: 'Fecha de Ingreso' },
      { key: 'actions', label: 'Acciones' }
    ]

    const filteredUsers = computed(() => {
      if (!searchQuery.value) return users.value
      
      return users.value.filter(user => 
        user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        user.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        user.identification.toLowerCase().includes(searchQuery.value.toLowerCase())
      )
    })

    const fetchUsers = async () => {
      try {
        const response = await axios.get('/api/users')
        users.value = response.data.users
        updateStatistics()
      } catch (error) {
        console.error('Error fetching users:', error)
      }
    }

    const updateStatistics = () => {
      statistics.value = {
        total: users.value.length,
        active: users.value.filter(u => u.status === 'active').length,
        new: users.value.filter(u => {
          const oneMonthAgo = new Date()
          oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1)
          return new Date(u.created_at) > oneMonthAgo
        }).length
      }
    }

    const getUserInitials = (name) => {
      return name.split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
    }

    const formatDate = (date) => {
      if (!date) return 'No especificada'
      return new Date(date).toLocaleDateString('es-ES')
    }

    const getStatusClass = (status) => {
      return {
        'active': 'bg-green-100 text-green-800',
        'inactive': 'bg-red-100 text-red-800',
        'pending': 'bg-yellow-100 text-yellow-800'
      }[status] || 'bg-gray-100 text-gray-800'
    }

    const handleSearch = () => {
      currentPage.value = 1
    }

    const handlePageChange = (page) => {
      currentPage.value = page
    }

    const editUser = (user) => {
      selectedUser.value = user
      showEditModal.value = true
    }

    const deleteUser = async (user) => {
      if (!confirm('¿Está seguro de que desea eliminar este usuario?')) return
      
      try {
        await axios.delete(`/api/users/${user.id}`)
        await fetchUsers()
      } catch (error) {
        console.error('Error deleting user:', error)
      }
    }

    const handleUserCreated = async () => {
      showCreateModal.value = false
      await fetchUsers()
    }

    const handleUserUpdated = async () => {
      showEditModal.value = false
      await fetchUsers()
    }

    onMounted(() => {
      fetchUsers()
    })

    return {
      users,
      statistics,
      searchQuery,
      currentPage,
      showCreateModal,
      showEditModal,
      selectedUser,
      tableHeaders,
      filteredUsers,
      getUserInitials,
      formatDate,
      getStatusClass,
      handleSearch,
      handlePageChange,
      editUser,
      deleteUser,
      handleUserCreated,
      handleUserUpdated
    }
  }
}
</script>
