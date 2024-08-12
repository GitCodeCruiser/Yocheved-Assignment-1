<template>
    <div class="d-flex justify-content-center">
        <!-- Form container for adding availabilities -->
        <div class="custom-form custom-form-white shadow-lg" style="width: 600px;">
            <h4 class="text-bold mb-4">Add Availabilities</h4>
            <div class="d-flex justify-content-between mb-2">
                <div><b class="text-bold">Day</b></div>
                <div><b class="text-bold">Availability</b></div>
            </div>
            <!-- Loop through weekDays and create a checkbox for each day -->
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
            <!-- Submit button for the form -->
            <div class="d-flex justify-content-end mt-3">
                <CustomButton 
                    type="button" 
                    :clickHandler="submitAvailability" 
                    text="Add Availability"
                    :buttonClass="'submit-button custom-button-blue mb-2'" 
                    :disabled="isDisabled" 
                />
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
            isDisabled: false, // Disabled state for the submit button
            weekDays: this.$constants.weekdays, // Array of weekdays from constants
            selectedDays: [], // Array to hold selected days
        }
    },
    created() {
        this.getAvailabilities(); // Fetch existing availabilities when component is created
    },

    methods: {
        // Submit selected days to the API
        submitAvailability() {
            let data = {
                'days': this.selectedDays,
                'student_id': this.$route.params.id
            }

            if (this.selectedDays.length < 1) {
                this.$toast.error("Please select days"); // Show error if no days are selected
                return;
            }

            StudentApiService.addAvailability(data)
                .then(({ data }) => {
                    if (data.status) {
                        this.$toast.success(data.message);
                        this.$router.push({ name: 'Students' }); // Redirect to Students page on success
                    } else {
                        this.$toast.error(data.message); // Show error message from API response
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.$toast.error('An error occurred while adding availability. Please try again.');
                });
        },

        // Fetch and populate existing availabilities
        getAvailabilities() {
            let data = {
                'student_id': this.$route.params.id
            }

            StudentApiService.getAvailability(data).then(({ data }) => {
                if (data.status) {
                    data.data.student_availability.forEach((day) => {
                        this.selectedDays.push(day.day); // Populate selected days
                    })
                } else {
                    this.$toast.error(data.message); // Show error message if fetching fails
                }
            })
        }
    },
}
</script>
