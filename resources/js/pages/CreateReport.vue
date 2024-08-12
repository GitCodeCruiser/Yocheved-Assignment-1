<template>
    <div class="d-flex justify-content-center">
        <!-- Form container for adding a report -->
        <div class="custom-form custom-form-white shadow-lg" style="width: 600px;">
            <h4 class="text-bold mb-4">Add Report</h4>

            <ValidationObserver v-slot="{ invalid }">
                <form @submit.prevent="submitForm">
                    <!-- Title input with validation -->
                    <ValidationProvider name="title" rules="required" v-slot="{ errors }">
                        <div class="mb-2">
                            <CustomInput id="title" v-model="data.title" type="text" placeholder="Enter Title"
                                label="Title" />
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <!-- CKEditor input for report body with validation -->
                    <ValidationProvider name="body" rules="required" v-slot="{ errors }">
                        <div class="mb-2">
                            <div class="form-group mt-3">
                                <label class="mb-2">Body</label>
                                <ckeditor class="form-control mt-2" :editor="editor" v-model="data.body" :config="editorConfig"></ckeditor>
                            </div>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>

                    <!-- Submit button -->
                    <div class="d-flex justify-content-center mt-3 mb-3">
                        <CustomButton 
                            type="submit" 
                            text="Submit" 
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
            data: {
                title: null, // Title of the report
                body: "Please type report here", // Default body content for the report
            },
            editor: ClassicEditor, // CKEditor instance
            editorData: '<p>Hello from CKEditor 5 in Vue 2!</p>',
            editorConfig: {
                toolbar: {
                    items: [ 'undo', 'redo', '|', 'bold', 'italic' ], // Toolbar configuration
                },
                plugins: [
                    Bold, Essentials, Italic, Mention, Paragraph, Undo // Plugins for CKEditor
                ],
            }
        };
    },

    mounted () {
        this.getReport(); // Fetch existing report data when the component is mounted
    },

    methods: {
        // Submit the report form data
        submitForm() {
            ReportApiService.addReport(this.data).then(({data}) => {
                if(data.status){
                    this.$toast.success(data.message); // Show success message
                } else {
                    this.$toast.error(data.message); // Show error message if submission fails
                }
            });
        },

        // Fetch the current report data
        getReport() {
            ReportApiService.getReport().then(({data}) => {
                if(data.status){
                    this.data.title = data.data.title; // Populate title with fetched data
                    this.data.body = data.data.body; // Populate body with fetched data
                } else {
                    this.$toast.error(data.message); // Show error message if fetching fails
                }
            });
        }
    },
};
</script>

<style>
.ck-editor__editable_inline {
  min-height: 250px; /* Adjust the height of the CKEditor as needed */
}
</style>
