import Vue from 'vue';
import Auth from './auth';
import Router from 'vue-router';
import Login from './pages/Login.vue';
import Dashboard from './pages/Dashboard.vue';
import Students from './pages/Students.vue';
import AddStudent from './pages/AddStudent.vue';
import AddAvailability from './pages/AddAvailability.vue';

Vue.use(Router);

const ifAuthenticated = (next) => {
  if (Auth.check()) {    
    return true;
  } 
  else {
    router.push({
      name: "Login",
    });
  }
};

const routes = [
  { path: '/', name: "Login", component: Login },
  { path: '/dashboard', name: "Dashboard", component: Dashboard },
  { path: '/students', name: "Students", component: Students },
  { path: '/add-student', name: "AddStudent", component: AddStudent },
  { path: '/add-availabilities/:id', name: "AddAvailability", component: AddAvailability }

];

const router = new Router({
  mode: 'history',
  routes
});

export default router;