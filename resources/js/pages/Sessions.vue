<template>
    <div>
        <div class="d-flex justify-content-end">
            <CustomButton type="button" :clickHandler="showAddSessionForm" text="Add Session" :buttonClass="'submit-button custom-button-blue mb-2'" :disabled="isDisabled" />
        </div>
        <template v-if="sessions && sessions.data.length > 0">
            <CustomTable
                :headers="headers"
                :data="sessions.data"
                :actions="actions"
                :pagination="sessions"
                @action="handleAction"
                @paginate="paginate"
            />
        </template>
        <div v-else class="text-center">Loading...</div>
    </div>
</template>

<script>
import CustomButton from '../components/CustomButton.vue';
import CustomTable from '../components/CustomTable.vue';
import SessionApiService from '../api-services/session-api-service';
import ReportApiService from '../api-services/report-api-service';

export default {
    components: {
        CustomButton,
        CustomTable
    },
    data() {
        return {
            isDisabled: false,
            sessions: null,
            headers: [
                { label: 'Start Date Time', key: 'start_date_time' },
                { label: 'End Date Time', key: 'end_date_time' },
                { label: 'Target', key: 'target' },
                { label: 'Daily', key: 'daily' },
                { label: 'Status', key: 'status_column' },
            ],
            actions: [
                { text: 'Create Schedule', handler: this.handleAction, key: 'assignStudent', condition: (row) => true },
                { text: 'Rate', key: 'rateSession', condition: (row) => row.status_column === 'completed', handler: this.handleAction },
                { text: 'Report', key: 'generate_report', condition: (row) => row.status_column === 'completed', handler: this.handleAction }
            ]
        }
    },

    mounted() {
        this.getSessions();
    },

    methods: {
        showAddSessionForm() {
            this.$router.push({ name: 'AddSession' });
        },

        handleAction(data) {
            if (data.action.key === "assignStudent") {
                this.$router.push({ name: 'AddSchedule', params: { id: data.data } });
            } else if (data.action.key === "rateSession") {
                this.$router.push({ name: 'AddRating', params: { id: data.data } });
            }
            else if(data.action.key == "generate_report"){
                this.downloadPDF(data.data)
            }
        },

        getSessions(page = 1) {
            SessionApiService.getSessions({ params: { page } })
                .then(({ data }) => {
                    if (data.status) {
                        this.sessions = data.data;
                    } else {
                        this.$toast.error(data.message);
                    }
                });
        },

        paginate(page) {
            this.getSessions(page);
        },

        downloadPDF(id) {
            let data = {session_id: id}
            ReportApiService.printReport(data).then(response => {
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
    }
}
</script>

<style lang="scss" scoped>
</style>
