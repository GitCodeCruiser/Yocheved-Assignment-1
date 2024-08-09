<template>
    <div>
        <div class="d-flex justify-content-end">
            <CustomButton type="button" :clickHandler="showAddSessionForm" text="Add Session" :buttonClass="'submit-button custom-button-blue mb-2'" :disabled="isDisabled" />
        </div>
        <template v-if="sessions && (sessions.length == 0 || sessions.length > 0)">
            <CustomTable
                :headers="headers"
                :data="sessions"
                :showActions="true"
                :actions="actions"
                @action="handleAction"
            />
        </template>
        <div v-else class="text-center">Loading...</div>
    </div>
</template>

<script>
import CustomButton from '../components/CustomButton.vue';
import CustomTable from '../components/CustomTable.vue';
import SessionApiService from '../api-services/session-api-service';

export default {
    components: {
        CustomButton,
        CustomTable
    },
    data() {
        return {
            isDisabled: false,
            sessions: [],
            headers: [
                { label: 'Start Date Time', key: 'start_date_time' },
                { label: 'End Date Time', key: 'end_date_time' },
                { label: 'Target', key: 'target' },
            ],
            
            actions: [
                { text: 'Create Schedule', handler: this.handleAction, key: 'assignStudent' },
            ]
        }
    },
    
    mounted(){
        this.getSessions();
    },

    methods: {
        showAddSessionForm() {
            this.$router.push({name: 'AddSession'});
        },

        handleAction(data) {
            if(data.action.key == "assignStudent"){
                this.$router.push({ name: 'AddSchedule', params: { id: data.data } });
            }
        },

        getSessions(){
            SessionApiService.getSession().then(({data}) => {
                if(data.status){
                    this.sessions = data.data;
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