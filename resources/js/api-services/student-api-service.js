import axios from '../axios.config';
const baseUrl = process.env.MIX_API_BASE_URL;

export default{
    addStudent : (data) => axios.post(`${baseUrl}/student/add`,data),
    getStudents: (data) => axios.get(`${baseUrl}/student/get`,data),
    allStudents: (data) => axios.post(`${baseUrl}/student/all`,data),

    addAvailability : (data) => axios.post(`${baseUrl}/availability/add`,data),
    getAvailability: (data) => axios.post(`${baseUrl}/availability/get`,data),

    getStudentAvailabilityForSession: (data) => axios.post(`${baseUrl}/availability/student-for-session`,data),

    
    addSchedule: (data) => axios.post(`${baseUrl}/schedule/add`,data),
    scheduleStudent: (data) => axios.post(`${baseUrl}/schedule/student`,data),
}