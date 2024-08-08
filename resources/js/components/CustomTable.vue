<template>
    <div>
        <div class="d-flex justify-content-end">
            <!-- Any additional controls can be placed here -->
        </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th v-for="header in headers" :key="header">{{ header.label }}</th>
                    <th v-if="showActions">{{ actionText }}</th> <!-- Conditionally render header -->
                </tr>
            </thead>
            <tbody>
                <tr v-if="!data.length">
                    <td :colspan="headers.length + (showActions ? 1 : 0)" class="text-center">No records found</td>
                </tr>
                <tr v-else v-for="(row, index) in data" :key="index">
                    <td v-for="(header, index) in headers" :key="index">{{ row[header.key] }}</td>
                    <td v-if="showActions">
                        <CustomButton :clickHandler="() => handleAction(row.id)" :text="actionText" :buttonClass="'submit-button custom-button-blue mb-2'" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import CustomButton from './CustomButton.vue';

export default {
    components: {
        CustomButton,
    },
    name: 'CustomTable',
    props: {
        headers: {
            type: Array,
            required: true
        },
        data: {
            type: Array,
            required: true
        },
        showActions: {
            type: Boolean,
            default: false 
        },
        actionText: {
            type: String,
            default: "Action" 
        }
    },
    methods: {
        handleAction(row) {
            this.$emit('action', row); // Emit an event with the row data
        }
    }
};
</script>

<style scoped>
/* Add any additional styling here */
</style>
