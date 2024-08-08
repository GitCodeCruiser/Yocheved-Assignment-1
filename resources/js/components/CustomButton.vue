<template>
  <button
    :class="['custom-button', buttonClass]"
    :style="buttonStyle"
    :type="type"
    @click="handleClick"
    :disabled="disabled"
  >
    <slot>{{ text }}</slot>
  </button>
</template>

<script>
export default {
  name: 'CustomButton',
  props: {
    text: {
      type: String,
      default: 'Button'
    },
    buttonClass: {
      type: String,
      default: ''
    },
    buttonStyle: {
      type: Object,
      default: () => ({})
    },
    type: {
      type: String,
      default: 'button' // Default type is 'button'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    clickHandler: {
      type: Function,
      default: () => {}
    }
  },
  methods: {
    handleClick(event) {
      if (!this.disabled) {
        this.clickHandler(event);
      }
    }
  }
};
</script>
