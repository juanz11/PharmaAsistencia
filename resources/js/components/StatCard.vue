<template>
  <div :class="cardClass" class="rounded-xl shadow-lg overflow-hidden">
    <div class="p-6">
      <div class="flex items-center">
        <div :class="iconClass" class="p-3 rounded-lg mr-4">
          <i :class="iconName" class="text-2xl"></i>
        </div>
        <div>
          <p class="text-sm font-medium opacity-70">{{ title }}</p>
          <h3 class="text-2xl font-bold mt-1">{{ value }}</h3>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StatCard',
  
  props: {
    title: {
      type: String,
      required: true
    },
    value: {
      type: [Number, String],
      required: true
    },
    color: {
      type: String,
      default: 'blue',
      validator: (value) => ['blue', 'green', 'purple'].includes(value)
    },
    icon: {
      type: String,
      required: true
    }
  },

  computed: {
    cardClass() {
      const classes = {
        blue: 'bg-gradient-to-br from-blue-600 to-blue-400',
        green: 'bg-gradient-to-br from-green-600 to-green-400',
        purple: 'bg-gradient-to-br from-purple-600 to-purple-400'
      }
      return `${classes[this.color]} text-white`
    },

    iconClass() {
      return 'bg-white bg-opacity-20'
    },

    iconName() {
      return `fas fa-${this.icon}`
    }
  }
}
</script>
