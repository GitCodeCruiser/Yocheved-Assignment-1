<template>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <div class="d-flex justify-content-between w-100">
                <!-- Sidebar toggle button -->
                <div>
                    <button type="button" id="sidebarCollapse" class="text-white btn">
                        <i class="fas fa-align-left"></i>
                    </button>
                </div>
                <!-- Logout button -->
                <div>
                    <button class="btn btn-danger" @click="logout()">Logout</button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import Auth from '../auth';

export default {
    methods: {
        // Logout function calling the Auth service's logout method
        logout() {
            Auth.logout();
        }
    },
}
</script>
