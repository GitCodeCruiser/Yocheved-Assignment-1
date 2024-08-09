<template>
    <div>
        <div class="d-flex justify-content-end">
            <CustomButton type="button" :clickHandler="showAddStudentForm" text="Add student" :buttonClass="'submit-button custom-button-blue mb-2'" :disabled="isDisabled" />
        </div>
        <template v-if="students && (students.length == 0 || students.length > 0)">
            <CustomTable
                :headers="headers"
                :data="students"
                :showActions="true"
                :actions="[
                    { text: 'Availabilities', handler: handleAction, key: 'availability' },
                ]"
                @action="handleAction"
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

    mounted(){
        this.getStudents();
    },

    data() {
        return {
            headers: [
                { label: 'Name', key: 'full_name' },
                { label: 'Date of Birth', key: 'date_of_birth' },
            ],
            students: [

            ],

            isDisabled: false,
        }
    },

    methods: {
        showAddStudentForm() {
            this.$router.push({name: 'AddStudent'});
        },

        getStudents() {
            StudentApiService.getStudents().then(({data}) => {
                if(data.status)
                {
                    this.students = data.data;
                }
                else
                {
                    this.$toast.error(data.message);
                }
            })
        },

        handleAction(data) {
            if(data.action.key == "availability"){
                this.$router.push({ name: 'AddAvailability', params: { id: data.data } });
            }
            
        }
    },
}
</script>

<style lang="scss" scoped></style>