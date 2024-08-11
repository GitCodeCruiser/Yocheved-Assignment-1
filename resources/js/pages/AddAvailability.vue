<template>
    <div class="d-flex justify-content-center">
        <div class="custom-form custom-form-white shadow-lg" style="width: 600px;">
            <h4 class="text-bold mb-4">Add Availabilities</h4>
            <div class="d-flex justify-content-between mb-2">
                <div><b class="text-bold">Day</b class="text-bold"></div>
                <div><b class="text-bold">Availability</b class="text-bold"></div>
            </div>
            <div v-for="(day, index) in weekDays" :key="index" class="d-flex justify-content-between">
                <div>
                    <label :for="'checkbox-' + index" class="form-check-label">
                        {{ day }}
                    </label>
                </div>
                <div>
                    <input type="checkbox" :id="'checkbox-' + index" v-model="selectedDays" :value="index"
                        class="form-check-input" />
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <CustomButton type="button" :clickHandler="submitAvailability" text="Add Availability"
                    :buttonClass="'submit-button custom-button-blue mb-2'" :disabled="isDisabled" />
            </div>
        </div>
    </div>
</template>

<script>
import StudentApiService from '../api-services/student-api-service';
import CustomButton from '../components/CustomButton.vue';

export default {
    components: {
        CustomButton
    },
    data() {
        return {
            isDisabled: false,
            weekDays: this.$constants.weekdays,
            selectedDays: [],
        }
    },
    created() {
        this.getAvailabilities();
    },

    methods: {
        submitAvailability() {
            let data = {
                'days': this.selectedDays,
                'student_id': this.$route.params.id
            }

            if (this.selectedDays.length < 1) {
                this.$toast.error("Please select days");
                return;
            }

            StudentApiService.addAvailability(data)
                .then(({ data }) => {
                    if (data.status) {
                        this.$toast.success(data.message);
                        this.$router.push({ name: 'Students' });
                    } else {
                        this.$toast.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.$toast.error('An error occurred while adding availability. Please try again.');
                });
        },

        getAvailabilities() {
            let data = {
                'student_id': this.$route.params.id
            }

            StudentApiService.getAvailability(data).then(({ data }) => {
                if (data.status) {
                    data.data.student_availability.forEach((day) => {
                        this.selectedDays.push(day.day);
                    })
                }
                else {
                    this.$toast.error(data.message);
                }
            })
        }
    },
}
</script>

<style lang="scss" scoped></style>