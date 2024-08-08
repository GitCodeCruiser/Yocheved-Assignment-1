import axios from "axios";
import router from "./router.js";

class Auth {
    constructor() {
        this.token = window.localStorage.getItem("token");
        let userData = window.localStorage.getItem("user");

        if (userData === "undefined") {
            window.localStorage.removeItem("token");
            window.localStorage.removeItem("user");
            window.location.href = "login";
        } else {
            this.user = userData ? JSON.parse(userData) : null;
        }

        if (this.token) {
            axios.defaults.headers.common["Authorization"] =
                "Bearer " + this.token;
        }
    }

    login(token, user) {
        window.localStorage.setItem("token", token);
        window.localStorage.setItem("user", JSON.stringify(user));
        axios.defaults.headers.common["Authorization"] = "Bearer " + token;

        this.token = token;
        this.user = user;
    }

    logout() {
        window.localStorage.removeItem("token");
        window.localStorage.removeItem("user");
        this.user = null;

        router.push({ name: "Login" });
    }

    check() {
        if (
            window.localStorage.getItem("token") === null ||
            window.localStorage.getItem("token") === "undefined"
        ) {
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

    userRefresh() {
        OwnerAuthService.getUser().then(({ data }) => {
            window.localStorage.setItem("user", JSON.stringify(data.data));
        });
    }

    checkProfileCompletion() {
        if (window.localStorage.getItem("user")) {
            let user = JSON.parse(window.localStorage.getItem("user"));
            return user.profile_completion;
        }

        return false;
    }

    getToken() {
        return this.token;
    }

    getUser() {
        return this.user;
    }    
}

export default new Auth();
