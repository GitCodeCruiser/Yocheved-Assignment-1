<template>
    <div>
        <!-- Button to show the form for adding a new student -->
        <div class="d-flex justify-content-end">
            <CustomButton
                type="button"
                :clickHandler="showAddStudentForm"
                text="Add student"
                :buttonClass="'submit-button custom-button-blue mb-2'"
                :disabled="isDisabled"
            />
        </div>

        <!-- Table to display students if available -->
        <template v-if="students && students.data.length > 0">
            <CustomTable
                :headers="headers"
                :data="students.data"
                :actions="actions"
                :pagination="students"
                @action="handleAction"
                @paginate="paginate"
            />
        </template>

        <!-- Message when no records are found -->
        <div v-else class="text-center">No Records Found</div>
    </div>
</template>

<script>
import CustomButton from '../components/CustomButton.vue';
import CustomTable from '../components/CustomTable.vue';
import StudentApiService from '../api-services/student-api-service';

export default {
    components: {
        CustomTable,
        CustomButton
    },

    mounted() {
        this.getStudents(); // Fetch students when the component is mounted
    },

    data() {
        return {
            headers: [
                { label: 'Email', key: 'email' },
                { label: 'Name', key: 'full_name' },
                { label: 'Date of Birth', key: 'date_of_birth' },
            ],
            students: null, // Data for students
            isDisabled: false, // Disabled state for buttons
            logs: null,
            actions: [
                { text: 'Availabilities', handler: this.handleAction, key: 'availability', condition: (row) => true },
            ],
        }
    },

    methods: {
        // Navigate to the form for adding a new student
        showAddStudentForm() {
            this.$router.push({ name: 'AddStudent' });
        },

        // Handle actions triggered from the table
        handleAction(data) {
            if (data.action.key === "availability") {
                this.$router.push({ name: 'AddAvailability', params: { id: data.data.slug } });
            }
        },

        // Fetch students data
        getStudents(page = 1) {
            StudentApiService.getStudents({ params: { page } })
                .then(({ data }) => {
                    if (data.status) {
                        this.students = data.data;
                    } else {
                        this.$toast.error(data.message);
                    }
                });
        },

        // Handle pagination
        paginate(page) {
            this.getStudents(page);
        }
    },
}
</script>