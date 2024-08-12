<template>
    <div>
        <div class="d-flex justify-content-end">
            <!-- Additional controls can be placed here if needed -->
        </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <!-- Render table headers dynamically -->
                    <th v-for="(header, index) in headers" :key="index">{{ header.label }}</th>
                    <!-- Conditionally render 'Actions' header if actions are available -->
                    <th v-if="actions.length">{{ actions.length > 1 ? 'Actions' : actions[0].text }}</th>
                </tr>
            </thead>
            <tbody>
                <!-- Display a message if no data is available -->
                <tr v-if="!data.length">
                    <td :colspan="headers.length + (actions.length ? 1 : 0)" class="text-center">No records found</td>
                </tr>
                <template v-else>
                    <!-- Render table rows dynamically -->
                    <tr v-for="(row, rowIndex) in data" :key="rowIndex">
                        <td v-for="(header, colIndex) in headers" :key="colIndex">{{ row[header.key] }}</td>
                        <!-- Render action buttons if any actions are defined -->
                        <td v-if="actions.length">
                            <div class="d-flex">
                                <div v-for="(action, actionIndex) in actions" :key="actionIndex">
                                    <CustomButton
                                        v-if="typeof action.condition === 'function' && action.condition(row)"
                                        :clickHandler="() => handleAction(action, row)"
                                        :text="action.text"
                                        :buttonClass="'submit-button custom-button-blue mx-1'"
                                    />
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <!-- Render pagination controls if pagination is available -->
        <nav v-if="pagination && pagination.total > pagination.per_page">
            <ul class="pagination justify-content-center">
                <li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
                    <a class="page-link" href="#" @click.prevent="$emit('paginate', pagination.current_page - 1)">Previous</a>
                </li>
                <li class="page-item" v-for="page in pages" :key="page" :class="{ active: pagination.current_page === page }">
                    <a class="page-link" href="#" @click.prevent="$emit('paginate', page)">{{ page }}</a>
                </li>
                <li class="page-item" :class="{ disabled: !pagination.next_page_url }">
                    <a class="page-link" href="#" @click.prevent="$emit('paginate', pagination.current_page + 1)">Next</a>
                </li>
            </ul>
        </nav>
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
            required: true, // Headers are required for table rendering
        },
        data: {
            type: Array,
            required: true, // Data array is required to populate table rows
        },
        actions: {
            type: Array,
            default: () => [], // Default to an empty array if no actions are provided
        },
        pagination: {
            type: Object,
            required: false, // Pagination is optional
            default: null,
        },
    },
    computed: {
        // Calculate the pages array for pagination
        pages() {
            if (!this.pagination) return [];
            let pages = [];
            for (let i = 1; i <= this.pagination.last_page; i++) {
                pages.push(i);
            }
            return pages;
        }
    },
    methods: {
        // Handle action button clicks, emit the action event with relevant data
        handleAction(action, row) {
            this.$emit('action', { action, data: row });
        }
    }
};
</script>

<style scoped>
/* Add any additional styling here */
.pagination {
    margin-top: 20px;
}
</style>
