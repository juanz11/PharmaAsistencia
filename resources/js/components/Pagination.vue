<template>
  <div class="flex items-center justify-between">
    <div class="flex-1 flex justify-between sm:hidden">
      <button
        @click="prevPage"
        :disabled="current === 1"
        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
        :class="{ 'opacity-50 cursor-not-allowed': current === 1 }"
      >
        Anterior
      </button>
      <button
        @click="nextPage"
        :disabled="current === total"
        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
        :class="{ 'opacity-50 cursor-not-allowed': current === total }"
      >
        Siguiente
      </button>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Mostrando página <span class="font-medium">{{ current }}</span> de
          <span class="font-medium">{{ total }}</span>
        </p>
      </div>
      <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
          <button
            @click="prevPage"
            :disabled="current === 1"
            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            :class="{ 'opacity-50 cursor-not-allowed': current === 1 }"
          >
            <span class="sr-only">Anterior</span>
            <i class="fas fa-chevron-left"></i>
          </button>
          
          <template v-for="n in displayedPages" :key="n">
            <button
              v-if="n === '...'"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
            >
              ...
            </button>
            <button
              v-else
              @click="goToPage(n)"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium"
              :class="[
                current === n
                  ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                  : 'bg-white text-gray-500 hover:bg-gray-50'
              ]"
            >
              {{ n }}
            </button>
          </template>

          <button
            @click="nextPage"
            :disabled="current === total"
            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            :class="{ 'opacity-50 cursor-not-allowed': current === total }"
          >
            <span class="sr-only">Siguiente</span>
            <i class="fas fa-chevron-right"></i>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'

export default {
  name: 'Pagination',
  
  props: {
    total: {
      type: Number,
      required: true
    },
    current: {
      type: Number,
      required: true
    }
  },

  setup(props, { emit }) {
    const displayedPages = computed(() => {
      const pages = []
      const maxDisplayed = 7
      let start = Math.max(1, props.current - 2)
      let end = Math.min(props.total, start + maxDisplayed - 1)

      if (end - start + 1 < maxDisplayed) {
        start = Math.max(1, end - maxDisplayed + 1)
      }

      if (start > 1) {
        pages.push(1)
        if (start > 2) pages.push('...')
      }

      for (let i = start; i <= end; i++) {
        pages.push(i)
      }

      if (end < props.total) {
        if (end < props.total - 1) pages.push('...')
        pages.push(props.total)
      }

      return pages
    })

    const prevPage = () => {
      if (props.current > 1) {
        emit('page-change', props.current - 1)
      }
    }

    const nextPage = () => {
      if (props.current < props.total) {
        emit('page-change', props.current + 1)
      }
    }

    const goToPage = (page) => {
      emit('page-change', page)
    }

    return {
      displayedPages,
      prevPage,
      nextPage,
      goToPage
    }
  }
}
</script>
