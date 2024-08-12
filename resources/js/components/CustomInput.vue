<template>
    <!-- Input form group with label, input field, and dynamic properties -->
    <div class="form-group">
        <label :for="label">{{ label }}</label>
        <input 
            :id="label" 
            :type="type" 
            class="form-control mt-1" 
            :placeholder="placeholder" 
            v-model="inputValue" 
            @input="handleInput" 
            :disabled="disabled"
        >
    </div>
</template>

<script>
export default {
    name: "CustomInput",
    props: {
        type: {
            type: String,
            default: "text", // Default input type is 'text'
        },
        placeholder: {
            type: String,
            default: "", // Default placeholder is empty
        },
        label: {
            type: String,
            default: "", // Default label is empty
        },
        disabled: {
            type: Boolean,
            default: false // Input is enabled by default
        },
        value: {
            type: String,
            default: "", // Default value is empty
        },
    },
    data() {
        return {
            inputValue: "", // Local data property to hold the input value
        };
    },
    watch: {
        // Watcher to update local input value when prop value changes
        value(newValue) {
            this.inputValue = newValue;
        },
    },
    methods: {
        // Emit input event with the current value when the input changes
        handleInput(event) {
            this.$emit("input", this.inputValue);
        },
    },
};
</script>
