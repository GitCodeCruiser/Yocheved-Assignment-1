import axios from "axios";
import router from "./router.js";

class Auth {
    constructor() {
        this.token = window.localStorage.getItem("token");
        let userData = window.localStorage.getItem("user");

        // Handle undefined user data
        if (userData === "undefined") {
            window.localStorage.removeItem("token");
            window.localStorage.removeItem("user");
            window.location.href = "login"; // Redirect to login if user data is invalid
        } else {
            this.user = userData ? JSON.parse(userData) : null;
        }

        // Set the authorization header if token is available
        if (this.token) {
            axios.defaults.headers.common["Authorization"] =
                "Bearer " + this.token;
        }
    }

    // Login method to store token and user data
    login(token, user) {
        window.localStorage.setItem("token", token);
        window.localStorage.setItem("user", JSON.stringify(user));
        axios.defaults.headers.common["Authorization"] = "Bearer " + token;

        this.token = token;
        this.user = user;
    }

    // Logout method to clear token and user data
    logout() {
        window.localStorage.removeItem("token");
        window.localStorage.removeItem("user");
        this.user = null;

        router.push({ name: "Login" }); // Redirect to login page
    }

    // Check if user is authenticated
    check() {
        if (
            window.localStorage.getItem("token") === null ||
            window.localStorage.getItem("token") === "undefined"
        ) {
            // If token is not valid, clear user data and token
            if (this.user != null && this.user.email_verified_at == null) {
                window.localStorage.removeItem("deviceToken");
                window.localStorage.removeItem("token");
                window.localStorage.removeItem("user");
                this.user = null;

                return false;
            }

            window.localStorage.removeItem("deviceToken");
            window.localStorage.removeItem("token");
            window.localStorage.removeItem("user");
            this.user = null;

            return false;
        }

        return true;
    }

    // Refresh the user data by fetching it from the server
    userRefresh() {
        OwnerAuthService.getUser().then(({ data }) => {
            window.localStorage.setItem("user", JSON.stringify(data.data));
        });
    }

    // Check if the user's profile is complete
    checkProfileCompletion() {
        if (window.localStorage.getItem("user")) {
            let user = JSON.parse(window.localStorage.getItem("user"));
            return user.profile_completion;
        }

        return false;
    }

    // Get the stored token
    getToken() {
        return this.token;
    }

    // Get the stored user data
    getUser() {
        return this.user;
    }    
}

export default new Auth();
