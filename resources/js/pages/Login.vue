<template>
    <div id="main-body">
        <!-- Main container for the login form -->
        <div class="container custom-form-container">
            <div class="custom-form shadow-lg">
                <h4 class="text-bold mb-4">Login</h4>
                <ValidationObserver v-slot="{ invalid }">
                    <form @submit.prevent="submitForm">
                        <!-- Email Field -->
                        <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                            <div class="mb-2">
                                <CustomInput 
                                    id="email" 
                                    v-model="data.email" 
                                    type="email" 
                                    placeholder="Enter your Email"
                                    label="Email" 
                                />
                                <span class="text-danger">{{ errors[0] }}</span>
                            </div>
                        </ValidationProvider>

                        <!-- Password Field -->
                        <ValidationProvider name="Password" rules="required" v-slot="{ errors }">
                            <div class="mb-2">
                                <CustomInput 
                                    id="password" 
                                    v-model="data.password" 
                                    type="password"
                                    placeholder="Enter your password" 
                                    label="Password" 
                                />
                                <span class="text-danger">{{ errors[0] }}</span>
                            </div>
                        </ValidationProvider>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-center mt-3">
                            <CustomButton 
                                type="submit" 
                                text="Login"
                                :buttonClass="'submit-button custom-button-blue w-100'" 
                                :disabled="invalid" 
                            />
                        </div>
                    </form>
                </ValidationObserver>
            </div>
        </div>
    </div>
</template>

<script>
import CustomInput from "../components/CustomInput.vue";
import CustomButton from "../components/CustomButton.vue";
import authApiService from "../api-services/auth-api-service";
import Auth from '../auth.js';

export default {
    components: {
        CustomInput,
        CustomButton
    },

    data() {
        return {
            data: {
                email: null, // Email input model
                password: null, // Password input model
            },

            isDisabled: false, // Disabled state for the submit button
        };
    },

    mounted() {
        // Redirect to Dashboard if already logged in
        if (Auth.check()) {
            this.$router.push({ name: 'Dashboard' });
        }
    },

    methods: {
        // Handles form submission
        submitForm() {
            this.isDisabled = true; // Disable the button to prevent multiple submissions
            authApiService.Login(this.data).then(({ data }) => {
                if (data.status) {
                    this.isDisabled = false;
                    Auth.login(data.data.token, data.data); // Store authentication token
                    this.$toast.success(data.message); // Show success message
                    this.$emitter.emit('logged-in'); // Emit login event
                    this.$router.push({ name: 'Dashboard' }); // Redirect to Dashboard
                } else {
                    this.isDisabled = false;
                    this.$toast.error(data.message); // Show error message
                }
            });
        }
    },
};
</script>
