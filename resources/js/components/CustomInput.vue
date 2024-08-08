<template>
    <div class="form-group">
        <label :for="label">{{ label }}</label>
        <input :id="label" :type="type" class="form-control mt-1" :placeholder="placeholder" v-model="inputValue" @input="handleInput" :disabled="disabled">
    </div>
</template>

<script>
export default {
    name: "CustomInput",
    props: {
        type: {
            type: String,
            default: "text",
        },
        placeholder: {
            type: String,
            default: "",
        },
        label: {
            type: String,
            default: "",
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            inputValue: "",
        };
    },
    methods: {
        handleInput(event) {
            this.$emit("input", this.inputValue);
        },
    },
};
</script>