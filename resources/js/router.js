import Vue from "vue";
import Router from "vue-router";
import Auth from "./auth";
import Login from "./pages/Login.vue";
import ImportDoc from "./pages/ImportDoc.vue";
import Dashboard from "./pages/Dashboard.vue";
import Students from "./pages/Students.vue";
import AddStudent from "./pages/AddStudent.vue";
import AddAvailability from "./pages/AddAvailability.vue";
import AddSchedule from "./pages/AddSchedule.vue";
import Sessions from "./pages/Sessions.vue";
import AddSession from "./pages/AddSession.vue";
import AddRating from "./pages/AddRating.vue";
import CreateReport from "./pages/CreateReport.vue";
import GenerateReport from "./pages/GenerateReport.vue";

Vue.use(Router);

const ifAuthenticated = (to, from, next) => {
    if (Auth.check()) {
        next();
    } else {
        next({ name: "Login" });
    }
};

const routes = [
    { path: "/import-doc", name: "ImportDoc", component: ImportDoc },
    { path: "/", name: "Login", component: Login },
    { path: "/dashboard", name: "Dashboard", component: Dashboard, beforeEnter: ifAuthenticated },
    { path: "/students", name: "Students", component: Students, beforeEnter: ifAuthenticated },
    { path: "/add-student", name: "AddStudent", component: AddStudent },
    { path: "/add-availabilities/:id", name: "AddAvailability", component: AddAvailability, beforeEnter: ifAuthenticated },
    { path: "/sessions", name: "Sessions", component: Sessions, beforeEnter: ifAuthenticated },
    { path: "/add-session", name: "AddSession", component: AddSession, beforeEnter: ifAuthenticated },
    { path: "/add-schedule/:id", name: "AddSchedule", component: AddSchedule, beforeEnter: ifAuthenticated },
    { path: "/add-rating/:id", name: "AddRating", component: AddRating, beforeEnter: ifAuthenticated },
    { path: "/add-report", name: "AddReport", component: CreateReport, beforeEnter: ifAuthenticated },
    { path: "/generate-report", name: "GenerateReport", component: GenerateReport, beforeEnter: ifAuthenticated },
];

const router = new Router({
    mode: "history",
    routes,
});

export default router;
