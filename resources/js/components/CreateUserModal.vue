<template>
  <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$emit('close')"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <form @submit.prevent="handleSubmit">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  {{ isEditing ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
                </h3>
                <div class="mt-6 space-y-4">
                  <!-- Nombre -->
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input
                      type="text"
                      id="name"
                      v-model="formData.name"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                  </div>

                  <!-- Email -->
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                      type="email"
                      id="email"
                      v-model="formData.email"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                  </div>

                  <!-- Identificación -->
                  <div>
                    <label for="identification" class="block text-sm font-medium text-gray-700">Identificación</label>
                    <input
                      type="text"
                      id="identification"
                      v-model="formData.identification"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                  </div>

                  <!-- Ubicación -->
                  <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                    <select
                      id="location"
                      v-model="formData.location"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    >
                      <option value="">Seleccione una ubicación</option>
                      <option v-for="location in locations" :key="location" :value="location">
                        {{ location }}
                      </option>
                    </select>
                  </div>

                  <!-- Fecha de Ingreso -->
                  <div>
                    <label for="join_date" class="block text-sm font-medium text-gray-700">Fecha de Ingreso</label>
                    <input
                      type="date"
                      id="join_date"
                      v-model="formData.join_date"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                  </div>

                  <!-- Estado -->
                  <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select
                      id="status"
                      v-model="formData.status"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    >
                      <option value="active">Activo</option>
                      <option value="inactive">Inactivo</option>
                      <option value="pending">Pendiente</option>
                    </select>
                  </div>

                  <!-- Contraseña (solo para nuevos usuarios) -->
                  <div v-if="!isEditing">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input
                      type="password"
                      id="password"
                      v-model="formData.password"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="submit"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              {{ isEditing ? 'Guardar Cambios' : 'Crear Usuario' }}
            </button>
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="$emit('close')"
            >
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
  name: 'CreateUserModal',
  
  props: {
    user: {
      type: Object,
      default: null
    }
  },

  setup(props, { emit }) {
    const formData = ref({
      name: '',
      email: '',
      identification: '',
      location: '',
      join_date: '',
      status: 'active',
      password: ''
    })

    const locations = ref([
      'Sede Principal',
      'Sucursal Norte',
      'Sucursal Sur',
      'Sucursal Este',
      'Sucursal Oeste'
    ])

    const isEditing = computed(() => !!props.user)

    onMounted(() => {
      if (props.user) {
        formData.value = { ...props.user }
      }
    })

    const handleSubmit = async () => {
      try {
        if (isEditing.value) {
          await axios.put(`/api/users/${props.user.id}`, formData.value)
          emit('user-updated')
        } else {
          await axios.post('/api/users', formData.value)
          emit('user-created')
        }
        emit('close')
      } catch (error) {
        console.error('Error saving user:', error)
      }
    }

    return {
      formData,
      locations,
      isEditing,
      handleSubmit
    }
  }
}
</script>
