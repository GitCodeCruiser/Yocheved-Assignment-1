<template>
    <div v-if="isAuth && !['Login'].includes($route.name)">
        <div class="wrapper">
            <SideBar />
            <div id="content">
                <TopBar />
                <router-view />
            </div>
        </div>
    </div>
    <div v-else>
        <router-view />
    </div>
</template>

<script>
import Auth from './auth';
import SideBar from './components/Sidebar.vue';
import TopBar from './components/TopBar.vue';

export default {
    components: {
        SideBar,
        TopBar
    },

    created() {
        this.$emitter.on('logged-in', () => {
            this.isAuth = Auth.check();
            this.$router.push({"name": "Dashboard"});
        });
    },

    data() {
        return {
            isAuth: false,
        }
    },
    mounted() {
        this.isAuth = Auth.check();
    },
}
</script>