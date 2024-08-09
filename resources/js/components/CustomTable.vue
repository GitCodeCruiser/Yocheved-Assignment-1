<template>
    <div>
        <div class="d-flex justify-content-end">
            <!-- Any additional controls can be placed here -->
        </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th v-for="(header, index) in headers" :key="index">{{ header.label }}</th>
                    <th v-if="actions.length">{{ actions.length > 1 ? 'Actions' : actions[0].text }}</th> <!-- Conditionally render header -->
                </tr>
            </thead>
            <tbody>
                <tr v-if="!data.length">
                    <td :colspan="headers.length + (actions.length ? 1 : 0)" class="text-center">No records found</td>
                </tr>
                <template v-else>
                    <tr v-for="(row, index) in data" :key="index">
                        <td v-for="(header, index) in headers" :key="index">{{ row[header.key] }}</td>
                        <td v-if="actions.length">
                            <div class="d-flex">
                                <div v-for="(action, i) in actions" :key="i">
                                    <CustomButton v-if="typeof action.condition === 'function' && action.condition(row)" :clickHandler="() => handleAction(action, row.id)" :text="action.text" :buttonClass="'submit-button custom-button-blue mx-1'" />
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
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
        actions: {
            type: Array,
            default: () => [] 
        }
    },
    methods: {
        handleAction(action, row) {
            this.$emit('action', { action, data: row }); // Emit action with handler and rowId
        }
    }
};
</script>

<style scoped>
/* Add any additional styling here */
</style>
