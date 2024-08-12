<template>
    <div class="d-flex justify-content-center">
        <!-- Form container for generating a report -->
        <div class="custom-form custom-form-white shadow-lg" style="width: 600px;">
            <h4 class="text-bold mb-4">Generate Report</h4>
            <ValidationObserver v-slot="{ invalid }">
                <form @submit.prevent="downloadPDF">
                    <!-- Dropdown for selecting a student -->
                    <div>
                        <label for="studentDropdown">Select Student</label>
                        <ValidationProvider name="Student" rules="required" v-slot="{ errors }">
                            <select v-model="data.selectedStudent" id="studentDropdown" class="form-control"
                                @scroll="handleScroll">
                                <option v-for="student in students" :value="student.id" :key="student.id">
                                    {{ student.full_name }}
                                </option>
                            </select>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!-- Input for start date -->
                    <div class="form-group">
                        <label for="start_time">Start Date</label>
                        <ValidationProvider name="Start Date" rules="required" v-slot="{ errors }">
                            <input v-model="data.start_date" type="date" class="form-control mt-2" id="start_time">
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!-- Input for end date -->
                    <div class="form-group">
                        <label for="end_time">End Date</label>
                        <ValidationProvider name="End Date" rules="required" v-slot="{ errors }">
                            <input v-model="data.end_date" type="date" class="form-control mt-2" id="end_time">
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!-- Dropdown for selecting split option -->
                    <div class="form-group">
                        <label for="split_option">Split Option</label>
                        <ValidationProvider name="Split Option" rules="required" v-slot="{ errors }">
                            <select v-model="data.split" class="form-control mt-2">
                                <option v-for="split_option in split_options" :value="split_option.key"
                                    :key="split_option.key">{{ split_option.label }}</option>
                            </select>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!-- Submit button for generating the report -->
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
                start_date: "", // Start date for the report
                end_date: "", // End date for the report
                split: "", // Selected split option
                selectedStudent: null, // Selected student ID
            },
            students: [], // List of students
            currentPage: 1, // Current page for student pagination
            lastPage: null, // Last page for student pagination
            loading: false, // Loading state for student fetching

            split_options: [ // Split options for the report
                { 'label': '15 min', 'key': '15' },
                { 'label': '10 min', 'key': '10' },
                { 'label': '5 min', 'key': '5' },
                { 'label': '2 min', 'key': '2' },
            ],
        }
    },

    computed: {
        // Compute the minimum date for the date inputs (today's date)
        minDate() {
            return new Date().toISOString().split('T')[0];
        }
    },

    mounted() {
        this.getStudents(); // Fetch the list of students when the component is mounted
    },

    methods: {
        addSession() {
            SessionApiService.addSession(this.data).then(({ data }) => {
                if (data.status) {
                    this.$router.push({ name: 'Sessions' })
                }
                else {
                    this.$toast.error(data.message);
                }
            });
        },
        // Fetch the list of students from the API
        getStudents() {
            StudentApiService.allStudents().then(({ data }) => {
                if (data.status) {
                    this.students = [...this.students, ...data.data];
                    this.currentPage = data.current_page;
                    this.lastPage = data.last_page;
                } else {
                    this.$toast.error(data.message); // Show error message if fetching fails
                }
            });
        },

        // Handle scroll event to load more students (pagination)
        handleScroll(event) {
            const bottom = event.target.scrollHeight - event.target.scrollTop === event.target.clientHeight;
            if (bottom) {
                this.getStudents(this.currentPage + 1); // Load next page of students
            }
        },

        // Generate the report (not used directly)
        generateReport() {
            ReportApiService.printReport().then(({ data }) => {
                if (data.status) {
                    // Handle report generation success
                } else {
                    this.$toast.error(data.message); // Show error message if generation fails
                }
            })
        },

        // Download the generated report as a PDF
        downloadPDF() {
            ReportApiService.printReport(this.data).then((response) => {
                const contentType = response.headers['content-type'];

                if (contentType === 'application/json') {
                    this.$toast.error("No session with rating found"); // Show error if no sessions found
                } else {
                    const blob = new Blob([response.data], { type: 'application/pdf' });
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'report.pdf'; // Download the PDF report
                    link.click();
                }
            })
        }
    },
}
</script>
