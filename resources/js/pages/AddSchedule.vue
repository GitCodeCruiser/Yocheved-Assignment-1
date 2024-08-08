<template>
    <div class="d-flex justify-content-center">
        <div class="custom-form custom-form-white shadow-lg" style="width: 400px;">
            <h4 class="text-bold mb-4">Add Schedule</h4>
            {{ data }}
            <div class="form-group">
                <label for="">Student Name</label>
                <input type="text" class="form-control mt-2" disabled :value="this.availabilities.full_name">
            </div>
            <ValidationObserver v-slot="{ invalid }">
                <form @submit.prevent="addSchedule">
                    <label for="day">Availabilities</label>
                    <ValidationProvider name="Day" rules="required" v-slot="{ errors }">
                    <select v-model="data.day" class="form-control mt-2" id="day">
                        <option value="" disabled>Select an availability</option>
                        <option v-if="availabilities.student_availability.length === 0">No availability found</option>
                        <option v-for="availability in availabilities.student_availability" :key="availability.id" :value="availability.id">
                        {{ getDay(availability.id) }}
                        </option>
                    </select>
                    <span class="text-danger">{{ errors[0] }}</span>
                    </ValidationProvider>

                    <!-- Start Time -->
                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <ValidationProvider name="Start Time" rules="required" v-slot="{ errors }">
                        <input v-model="data.start_time" type="datetime-local" class="form-control mt-2" id="start_time">
                        <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!-- End Time -->
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <ValidationProvider name="End Time"  rules="required" v-slot="{ errors }">
                        <input v-model="data.end_time" type="datetime-local" class="form-control mt-2" id="end_time">
                        <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div class="d-flex justify-content-center mt-3 mb-3">
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
            availabilities: {
                student_availability: []
            },
            data:{
                day: null,
                start_time: null,
                end_time: null
            }
        }
    },
    created() {
        this.getUserAvailabilities();
    },

    methods: {
        
        getUserAvailabilities(){
            let data = {student_id: this.$route.params.id}

            StudentApiService.getAvailability(data).then(({data}) => {
                if(data.status){
                    this.availabilities = data.data;
                }
            })
        },
        
        getDay(day){
            return this.$constants.weekdays[day];
        },

        addSchedule(){
            StudentApiService.addSchedule(this.data).then(({data}) => {
                if(data.status){
                    console.log('asd');
                }
            });
        }
    },
}
</script>

<style lang="scss" scoped></style>