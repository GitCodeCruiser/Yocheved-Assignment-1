<template>
    <div>
        <div class="d-flex justify-content-end">
            <CustomButton
                type="button"
                :clickHandler="showAddStudentForm"
                text="Add student"
                :buttonClass="'submit-button custom-button-blue mb-2'"
                :disabled="isDisabled"
            />
        </div>
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
        <div v-else class="text-center">Loading...</div>
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
        this.getStudents();
    },

    data() {
        return {
            headers: [
                { label: 'Email', key: 'email' },
                { label: 'Name', key: 'full_name' },
                { label: 'Date of Birth', key: 'date_of_birth' },
            ],
            students: null,
            isDisabled: false,
            actions: [
                { text: 'Availabilities', handler: this.handleAction, key: 'availability', condition: (row) => true },
            ],
        }
    },

    methods: {
        showAddStudentForm() {
            this.$router.push({ name: 'AddStudent' });
        },

        handleAction(data) {
            if (data.action.key === "availability") {
                this.$router.push({ name: 'AddAvailability', params: { id: data.data } });
            }
        },

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

        paginate(page) {
            this.getStudents(page);
        }

       
    },
}
</script>


<style lang="scss" scoped></style>
