<template>
    <div class="d-flex justify-content-center">
        <div class="custom-form custom-form-white shadow-lg">
            <h4 class="text-bold mb-4">Add Student</h4>
            <ValidationObserver v-slot="{ invalid }">
                <form @submit.prevent="submitForm">
                    <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="email" v-model="data.email" type="email" placeholder="Enter Email"
                                label="Email" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <ValidationProvider name="First Name" rules="required|name" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="first_name" v-model="data.first_name" type="text" placeholder="Enter First Name"
                                label="First Name" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <ValidationProvider name="Middle Name" rules="name" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="middle_name" v-model="data.middle_name" type="text" placeholder="Enter Middle Name"
                                label="Middle Name" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <ValidationProvider name="Last Name" rules="required|name" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="last_name" v-model="data.last_name" type="text" placeholder="Enter Last Name"
                                label="Last Name" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <ValidationProvider name="Date of birth" rules="required|date" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="date_of_birth" v-model="data.date_of_birth" type="date" placeholder="Enter Last Name"
                                label="Date of Birth" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                    
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
                isDisabled: false,
                data:{
                    email: null,
                    first_name: null,
                    middle_name: null,
                    last_name: null,
                    date_of_birth: null,
                },
            }
        },
        methods: {
            submitForm() {
                this.isDisabled = true;
                StudentApiService.addStudent(this.data).then(({data}) => {
                    if(data.status)
                    {
                        this.$toast.success(data.message);
                        this.isDisabled = false;
                        this.$router.push({name: 'Students'});
                    }
                    else
                    {
                        this.$toast.error(data.message);
                        this.isDisabled = false;
                    }
                })
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>