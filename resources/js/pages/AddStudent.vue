<template>
    <div class="d-flex justify-content-center">
        <!-- Form container for adding a student -->
        <div class="custom-form custom-form-white shadow-lg">
            <h4 class="text-bold mb-4">Add Student</h4>
            <ValidationObserver v-slot="{ invalid }">
                <form @submit.prevent="submitForm">
                    <!-- Email input with validation -->
                    <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="email" v-model="data.email" type="email" placeholder="Enter Email"
                                label="Email" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <!-- First Name input with validation -->
                    <ValidationProvider name="First Name" rules="required|name" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="first_name" v-model="data.first_name" type="text" placeholder="Enter First Name"
                                label="First Name" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <!-- Middle Name input with validation (optional) -->
                    <ValidationProvider name="Middle Name" rules="name" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="middle_name" v-model="data.middle_name" type="text" placeholder="Enter Middle Name"
                                label="Middle Name" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <!-- Last Name input with validation -->
                    <ValidationProvider name="Last Name" rules="required|name" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="last_name" v-model="data.last_name" type="text" placeholder="Enter Last Name"
                                label="Last Name" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <!-- Date of Birth input with validation -->
                    <ValidationProvider name="Date of birth" rules="required|date|notFutureDate" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="date_of_birth" v-model="data.date_of_birth" type="date" placeholder="Enter Date of Birth"
                                label="Date of Birth" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                    
                    <!-- Submit button -->
                    <div class="d-flex justify-content-center mt-3 mb-3">
                        <CustomButton type="submit" text="Submit" :buttonClass="'submit-button custom-button-blue w-100'"
                            :disabled="invalid" />
                    </div>
                </form>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
import CustomInput from '../components/CustomInput.vue';
import CustomButton from '../components/CustomButton.vue';
import StudentApiService from '../api-services/student-api-service';

export default {
    components: {
        CustomButton,
        CustomInput
    },
    data() {
        return {
            isDisabled: false, // Disabled state for the submit button
            data: {
                email: null,
                first_name: null,
                middle_name: null,
                last_name: null,
                date_of_birth: null,
            },
        }
    },
    methods: {
        // Submit the form to add a new student
        submitForm() {
            this.isDisabled = true; // Disable the button to prevent multiple submissions
            StudentApiService.addStudent(this.data).then(({data}) => {
                if(data.status) {
                    this.$toast.success(data.message); // Show success message
                    this.isDisabled = false;
                    this.$router.push({name: 'Students'}); // Redirect to Students page
                } else {
                    this.$toast.error(data.message); // Show error message
                    this.isDisabled = false;
                }
            })
        }
    },
}
</script>