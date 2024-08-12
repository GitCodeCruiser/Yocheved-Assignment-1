<template>
  <!-- Button component with dynamic class, style, type, and click event -->
  <button
    :class="['custom-button', buttonClass]"
    :style="buttonStyle"
    :type="type"
    @click="handleClick"
    :disabled="disabled"
  >
    <slot>{{ text }}</slot> <!-- Slot to allow custom content or use provided text -->
  </button>
</template>

<script>
export default {
  name: 'CustomButton',
  props: {
    text: {
      type: String,
      default: 'Button' // Default button text
    },
    buttonClass: {
      type: String,
      default: '' // Default class is empty
    },
    buttonStyle: {
      type: Object,
      default: () => ({}) // Default style is an empty object
    },
    type: {
      type: String,
      default: 'button' // Default type is 'button'
    },
    disabled: {
      type: Boolean,
      default: false // Button is enabled by default
    },
    clickHandler: {
      type: Function,
      default: () => {} // Default click handler does nothing
    }
  },
  methods: {
    // Handle click event, only trigger if button is not disabled
    handleClick(event) {
      if (!this.disabled) {
        this.clickHandler(event);
      }
    }
  }
};
</script>
