<template>
    <div class="d-flex justify-content-center">
        <div class="custom-form custom-form-white shadow-lg" style="width: 600px;">
            <h4 class="text-bold mb-4">Add Report</h4>

            <ValidationObserver v-slot="{ invalid }">
                <form @submit.prevent="submitForm">
                    <ValidationProvider name="title" rules="required" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="title" v-model="data.title" type="text" placeholder="Enter Title"
                                label="Title" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <ValidationProvider name="body" rules="required" v-slot="{ errors }">
                        <div class="mb-2">
                            <div class="form-group mt-3">
                                <label class="mb-2">Body</label>
                                <ckeditor class="form-control mt-2" :editor="editor" v-model="data.body" :config="editorConfig"></ckeditor>
                            </div>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>


                    <div class="d-flex justify-content-center mt-3 mb-3">
                        <CustomButton type="submit" text="Submit" :buttonClass="'submit-button custom-button-blue w-100'" :disabled="invalid" />
                    </div>
                </form>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
import 'ckeditor5/ckeditor5.css';
import CustomInput from '../components/CustomInput.vue';
import CustomButton from '../components/CustomButton.vue';
import ReportApiService from '../api-services/report-api-service';
import { ClassicEditor, Bold, Essentials, Italic, Mention, Paragraph, Undo } from 'ckeditor5';

export default {
    components: {
        CustomButton,
        CustomInput
    },
    data() {
        return {
            data:{
                title: null,
                body: "Please type report here",
            },
            
            editor: ClassicEditor,
            editorData: '<p>Hello from CKEditor 5 in Vue 2!</p>',
            editorConfig: {
                toolbar: {
                    items: [ 'undo', 'redo', '|', 'bold', 'italic' ],
                 },
                plugins: [
                    Bold, Essentials, Italic, Mention, Paragraph, Undo
                ],
            }
        };
    },

    mounted () {
        this.getReport();
    },

    methods: {
        submitForm() {
            ReportApiService.addReport(this.data).then(({data}) => {
                if(data.status){
                    this.$toast.success(data.message);
                }
                else{
                    this.$toast.error(data.message);
                }
            });
        },

        getReport(){
            ReportApiService.getReport().then(({data}) => {
                if(data.status){
                    this.data.title = data.data.title;
                    this.data.body = data.data.body;
                }
                else{
                    this.$toast.error(data.message);
                }
            });
        }
    },
};
</script>


<style>
.ck-editor__editable_inline {
  min-height: 250px; /* Adjust the height as needed */
}
</style>