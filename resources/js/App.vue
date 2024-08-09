<template>
    <div>
      <div :class="{ 'wrapper': isAuth && $route.name !== 'Login' }">
        <SideBar v-if="isAuth && $route.name !== 'Login'" />
        <div :id="isAuth && $route.name !== 'Login' ? 'content' : ''">
          <TopBar v-if="isAuth && $route.name !== 'Login'" />
          <!-- Always render router-view inside #content -->
          <router-view />
        </div>
      </div>
    </div>
</template>


<script>
import Auth from './auth';
import TopBar from './components/TopBar.vue';
import SideBar from './components/SideBar.vue';

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