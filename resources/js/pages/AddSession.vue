<template>
    <div class="d-flex justify-content-center">
        <!-- Form container for adding a session -->
        <div class="custom-form custom-form-white shadow-lg" style="width: 600px;">
            <h4 class="text-bold mb-4">Add Session</h4>
            <ValidationObserver v-slot="{ invalid }">
                <form @submit.prevent="addSession">
                    <!-- Input for start date -->
                    <div class="form-group">
                        <label for="start_time">Start Date</label>
                        <ValidationProvider name="Start Time" rules="required" v-slot="{ errors }">
                            <input v-model="data.start_date" type="date" class="form-control mt-2"
                                id="start_time" :min="minDate">
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!-- Input for start time -->
                    <div class="form-group">
                        <label for="end_time">Start Time</label>
                        <ValidationProvider name="End Time" rules="required" v-slot="{ errors }">
                            <input v-model="data.start_time" type="time" class="form-control mt-2"
                                id="end_time">
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!-- Input for end time -->
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <ValidationProvider name="End Time" rules="required" v-slot="{ errors }">
                            <input v-model="data.end_time" type="time" class="form-control mt-2"
                                id="end_time">
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!-- Input for target rating -->
                    <div class="form-group">
                        <label for="target">Target</label>
                        <ValidationProvider name="Target" rules="required|integer" v-slot="{ errors }">
                            <div class="mb-2">
                                <input class="form-control mt-2" id="target" v-model="data.target" type="text" placeholder="Enter your target">
                                <span class="text-danger">{{ errors[0] }}</span>
                            </div>
                        </ValidationProvider>
                    </div>

                    <!-- Checkbox for daily session -->
                    <div class="form-group form-check">
                        <ValidationProvider name="isDaily" rules="" v-slot="{ errors }">
                            <input class="form-check-input" v-model="data.is_daily" type="checkbox" id="is_daily">
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                        <label for="is_daily">Is Daily</label>
                    </div>

                    <!-- Submit button for adding the session -->
                    <div class="d-flex justify-content-center mt-3 mb-3">
                        <CustomButton 
                            type="submit" 
                            text="Add Session"
                            :buttonClass="'submit-button custom-button-blue w-100'" 
                            :disabled="invalid" 
                        />
                    </div>
                </form>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
import CustomButton from '../components/CustomButton.vue';
import SessionApiService from '../api-services/session-api-service';

export default {
    components: {
        CustomButton
    },
    data() {
        return {
            data: {
                is_daily: false, // Default value for daily session checkbox
                start_date: "", // Start date of the session
                start_time: "", // Start time of the session
                end_time: "", // End time of the session
                target: 10, // Default target rating
            },
        }
    },

    computed: {
        // Calculate the minimum date for the start date input (today's date)
        minDate() {
            return new Date().toISOString().split('T')[0];
        }
    },

    methods: {
        // Method to add the session by calling the API
        addSession() {
            SessionApiService.addSession(this.data).then(({data}) => {
                if(data.status){
                    this.$router.push({name: 'Sessions'}) // Redirect to Sessions page on success
                } else {
                    this.$toast.error(data.message); // Show error message if adding session fails
                }
            });
        }
    },
}
</script>