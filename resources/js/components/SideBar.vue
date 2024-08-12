<template>
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Student Tracker</h3> <!-- Sidebar title -->
        </div>

        <ul class="list-unstyled components">
            <!-- Navigation links using Vue Router -->
            <li>
                <router-link :to="{ name: 'Students' }">Students</router-link>
            </li>
            <li>
                <router-link :to="{ name: 'Sessions' }">Sessions</router-link>
            </li>
            <li>
                <router-link :to="{ name: 'ImportDoc' }">Import Session</router-link>
            </li>
            <li>
                <router-link :to="{ name: 'AddReport' }">Report Template</router-link>
            </li>
            <li>
                <router-link :to="{ name: 'GenerateReport' }">Generate Report</router-link>
            </li>
        </ul>
    </nav>
</template>