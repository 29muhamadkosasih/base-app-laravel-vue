<template>

    <!-- overlay mobile -->
    <div
        v-if="sidebarOpen"
        class="sidebar-overlay d-lg-none"
        @click="sidebarOpen=false">
    </div>

    <!-- sidebar -->
    <Sidebar :open="sidebarOpen" @close="sidebarOpen=false" />

    <!-- title -->
    <Head :title="`${appName}`" />

    <main class="content">

        <!-- navbar -->
        <Navbar @toggle-sidebar="toggleSidebar" />

        <!-- content -->
        <slot />

    </main>

</template>

<script>
import { Head } from '@inertiajs/vue3'
import Navbar from "../Components/Navbar.vue"
import Sidebar from "../Components/Sidebar.vue"

export default {
    components: { Navbar, Sidebar, Head },

    data() {
        return {
            sidebarOpen: false,
        }
    },

    computed: {
        appName() {
            return this.$page.props.appSettings?.name_app || 'Aplikasi Ujian Online';
        }
    },

    methods:{
        toggleSidebar(){
            this.sidebarOpen = !this.sidebarOpen
        }
    }
}
</script>

<style>
.sidebar-overlay{
    position: fixed;
    inset:0;
    background: rgba(0,0,0,.4);
    z-index: 1030;
}
</style>
