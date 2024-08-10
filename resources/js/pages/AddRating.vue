<template>
    <div>
        <div class="d-flex justify-content-center">
        <div class="custom-form custom-form-white shadow-lg" style="width: 600px;">
            <h4 class="text-bold mb-4">Add Rating</h4>
            <div class="form-group">
                <label for="total_rating">Total Rating</label>
                <input id="total_rating" type="text" class="form-control mt-1" :value="session.target" disabled>
            </div>
            <ValidationObserver v-slot="{ invalid }">
                <form @submit.prevent="addRating">
                    <ValidationProvider name="obtained_marks" :rules="`required|integer|max:${session.target}`" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="obt_marks" v-model="obtained_marks" type="text" placeholder="Enter Rating" label="Rating" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <div class="d-flex justify-content-center mt-3">
                        <CustomButton type="submit" text="Add Rating"
                            :buttonClass="'submit-button custom-button-blue w-100'" :disabled="invalid" />
                    </div>
                </form>
            </ValidationObserver>
        </div>
    </div>
    </div>
</template>

<script>
import SessionApiService from '../api-services/session-api-service';
import CustomButton from '../components/CustomButton.vue';
import CustomInput from '../components/CustomInput.vue';

export default {
    components: {
        CustomInput,
        CustomButton
    },
    data() {
        return {
            session: {
                target: 0,
            },
            obtained_marks: null,
        }
    },
    mounted () {
        this.getSessions();
    },
    methods: {
        getSessions() {
            let data = {'session_id': this.$route.params.id};
            SessionApiService.getSession(data).then(({data}) => {
                if(data.status)
                {
                    this.session = data.data;
                    if(data?.data?.rating){
                        this.obtained_marks = String(data.data.rating.obtained_rating);
                    }
                }
                else
                {
                    this.$toast.error(data.message);
                }
            })
        },

        addRating(){
            let data = {
                'session_id': this.$route.params.id,
                'rating': this.obtained_marks
            }

            SessionApiService.addSessionRating(data 
            ).then(({data}) => {
                if(data.status){
                    this.$router.push({name: "Sessions"});
                    this.$toast.success(data.message);
                }
                else{
                    this.$toast.error(data.message);
                }
            })
        }
    },
}
</script>

<style lang="scss" scoped>

</style>