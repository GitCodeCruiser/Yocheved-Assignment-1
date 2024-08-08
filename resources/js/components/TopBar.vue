<template>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">

            <div class="d-flex justify-content-between w-100">
                <div>
                    <button type="button" id="sidebarCollapse" class="text-white btn">
                        <i class="fas fa-align-left"></i>
                    </button>
                </div>
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
        logout() {
            Auth.logout();
        }
    },
}
</script>

<style lang="scss" scoped>

</style>