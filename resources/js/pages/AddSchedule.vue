<template>
    <div class="d-flex justify-content-center">
        <div class="custom-form custom-form-white shadow-lg" style="width: 400px;">
            <h4 class="text-bold mb-4">Add Schedule</h4>
            <ValidationObserver v-slot="{ invalid }">
                <form @submit.prevent="scheduleStudent()">
                    <label for="student">Available Students</label>
                    <ValidationProvider name="Student" rules="required" v-slot="{ errors }">
                    <select v-model="data.student_id" class="form-control mt-2" id="student">
                        <option value="" disabled>Select a user</option>
                        <option v-if="students.length === 0">No student found</option>
                        <option v-for="student in students" :key="student.id" :value="student.id">
                        {{ student.full_name }}
                        </option>
                    </select>
                    <span class="text-danger">{{ errors[0] }}</span>
                    </ValidationProvider>

                    <div class="d-flex justify-content-center mt-5">
                        <CustomButton type="submit" text="Add Schedule" :buttonClass="'submit-button custom-button-blue w-100'"
                        :disabled="invalid" />
                    </div>
                </form>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
import StudentApiService from '../api-services/student-api-service';
import CustomButton from '../components/CustomButton.vue';
import CustomInput from '../components/CustomInput.vue';

export default {
    components: {
        CustomButton,
        CustomInput
    },
    data() {
        return {
            isDisabled: false,
            
            students: [],
            data:{
                student_id: null,
            }
        }
    },
    created() {
        this.getAvailableUsers();
    },

    methods: {
        
        getAvailableUsers(){
            let data = { 'session_id': this.$route.params.id };

            StudentApiService.getStudentAvailabilityForSession(data).then(({data}) => {
                if(data.status){
                    this.students = data.data;
                }
            });
        },
        
        scheduleStudent(){
            let data = {
                'session_id': this.$route.params.id,
                'student_id': this.data.student_id,
            };
            StudentApiService.scheduleStudent(data).then(({data}) => {
                if(data.status){
                    this.$router.push({ name: 'Sessions' });
                    this.$toast.success(data.message);
                }
                else{
                    this.$toast.error(data.message);
                }
            });
        }
    },
}
</script>

<style lang="scss" scoped></style>