import Vue from "vue";
import Router from "vue-router";
import Auth from "./auth";
import Login from "./pages/Login.vue";
import Dashboard from "./pages/Dashboard.vue";
import Students from "./pages/Students.vue";
import AddStudent from "./pages/AddStudent.vue";
import AddAvailability from "./pages/AddAvailability.vue";
import AddSchedule from "./pages/AddSchedule.vue";

Vue.use(Router);

const ifAuthenticated = (to, from, next) => {
    if (Auth.check()) {
        next(); // Proceed with navigation
    } else {
        next({ name: "Login" }); // Redirect to Login if not authenticated
    }
};

const routes = [
    { path: "/", name: "Login", component: Login },
    { path: "/dashboard", name: "Dashboard", component: Dashboard, beforeEnter: ifAuthenticated },
    { path: "/students", name: "Students", component: Students, beforeEnter: ifAuthenticated },
    { path: "/add-student", name: "AddStudent", component: AddStudent },
    { path: "/add-availabilities/:id", name: "AddAvailability", component: AddAvailability, beforeEnter: ifAuthenticated },
    { path: "/add-schedule/:id", name: "AddSchedule", component: AddSchedule, beforeEnter: ifAuthenticated },
];

const router = new Router({
    mode: "history",
    routes,
});

export default router;
