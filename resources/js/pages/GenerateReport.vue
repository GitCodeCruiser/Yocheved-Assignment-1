<template>
    <div class="d-flex justify-content-center">
        <div class="custom-form custom-form-white shadow-lg" style="width: 600px;">
            <h4 class="text-bold mb-4">Generate Report</h4>
            <ValidationObserver v-slot="{ invalid }">
                <form @submit.prevent="downloadPDF">
                    <div>
                        <label for="studentDropdown">Select Student</label>
                        <ValidationProvider name="Student" rules="required" v-slot="{ errors }">
                            <select v-model="data.selectedStudent" id="studentDropdown" class="form-control" @scroll="handleScroll">
                                <option v-for="student in students" :value="student.id" :key="student.id">
                                    {{ student.full_name }}
                                </option>
                            </select>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div class="form-group">
                        <label for="start_time">Start Date</label>
                        <ValidationProvider name="Start Date" rules="required" v-slot="{ errors }">
                            <input v-model="data.start_date" type="date" class="form-control mt-2"
                                id="start_time">
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div class="form-group">
                        <label for="end_time">End Date</label>
                        <ValidationProvider name="End Date" rules="required" v-slot="{ errors }">
                            <input v-model="data.end_date" type="date" class="form-control mt-2"
                                id="end_time">
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div class="form-group">
                        <label for="split_option">Split Option</label>
                        <ValidationProvider name="Split Option" rules="required" v-slot="{ errors }">
                            <select v-model="data.split" class="form-control mt-2">
                                <option v-for="split_option in split_options" :value="split_option.key" :key="split_option.key">{{ split_option.label }}</option>
                            </select>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div class="d-flex justify-content-center mt-3 mb-3">
                        <CustomButton type="submit" text="Generate Report"
                            :buttonClass="'submit-button custom-button-blue w-100'" :disabled="invalid" />
                    </div>
                </form>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
import CustomButton from '../components/CustomButton.vue';
import SessionApiService from '../api-services/session-api-service';
import StudentApiService from '../api-services/student-api-service';
import ReportApiService from '../api-services/report-api-service';

export default {
    components: {
        CustomButton
    },
    data() {
        return {
            data: {
                start_date: "",
                end_date: "",
                split: "",
                selectedStudent: null,
            },

            students:[],
            currentPage: 1,
            lastPage: null,
            loading: false,

            split_options: [
                {'label': '15 min', 'key': '15'},
                {'label': '10 min', 'key': '10'},
                {'label': '5 min', 'key': '5'},
                {'label': '2 min', 'key': '2'},
            ],
        }
    },

    computed: {
        minDate() {
            return new Date().toISOString().split('T')[0];
        }
    },

    mounted(){
        this.getStudents();
    },

    methods: {
        addSession() {
            SessionApiService.addSession(this.data).then(({data}) => {
                if(data.status){
                    this.$router.push({name: 'Sessions'})
                }
                else{
                    this.$toast.error(data.message);
                }
            });
        },

        getStudents(){
            StudentApiService.allStudents().then(({data}) => {
                if(data.status){
                    this.students = [...this.students, ...data.data];
                    this.currentPage = data.current_page;
                    this.lastPage = data.last_page;
                }
                else{
                    this.$toast.error(data.message);
                }
            });
        },

        handleScroll(event) {
            const bottom = event.target.scrollHeight - event.target.scrollTop === event.target.clientHeight;
            if (bottom) {
                this.getStudents(this.currentPage + 1);
            }
        },

        generateReport(){
            ReportApiService.printReport().then(({data}) => {
                if(data.status){

                }
                else{
                    this.$toast.error(data.message);
                }
            })
        },

        downloadPDF() {
            ReportApiService.printReport(this.data).then(response => {
                const blob = new Blob([response.data], { type: 'application/pdf' });
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'report.pdf';
                link.click();
            })
            .catch(error => {
                console.error('There was an error generating the PDF:', error);
            });
        }
    },
}
</script>
