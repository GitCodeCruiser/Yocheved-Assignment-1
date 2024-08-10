<template>
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Student Tracker</h3>
        </div>

        <ul class="list-unstyled components">
            <li>
                <router-link :to="{ name: 'Students' }">Students</router-link>
            </li>
            <li>
                <router-link :to="{ name: 'Sessions' }">Sessions</router-link>
            </li>
            <li>
                <router-link :to="{ name: 'ImportDoc' }">Import Session</router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
}
</script>

<style lang="scss" scoped></style>