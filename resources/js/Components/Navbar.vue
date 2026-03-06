<template>
    <nav
        class="navbar bg-gray-800 text-white navbar-top navbar-expand mt-3 navbar-dashboard navbar-dark ps-0 pe-3 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex align-items-center">
                    <button class="btn btn-link text-white d-lg-none ms-2 mb-3" @click="$emit('toggle-sidebar')">
                        ☰
                    </button>
                    <h5 class="ms-3 mb-3 text-white fw-bold">{{ appName }}</h5>
                </div>
                <ul class="navbar-nav align-items-center mb-2">
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="d-flex align-items-center">
                                <!-- nama hidden di mobile -->
                                <span class="d-none d-lg-inline me-2">
                                    {{ $page.props.auth.user.name }}
                                </span>
                                <img class="avatar rounded-circle"
                                    :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&background=4e73df&color=ffffff&size=100`">
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end border-0 shadow profile-dropdown py-2">
                            <Link class="dropdown-item d-flex align-items-center profile-link" href="/admin/profile">
                                <svg class="dropdown-icon text-dark me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A10.97 10.97 0 0112 15c2.5 0 4.847.815 6.879 2.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Profil Saya
                            </Link>

                            <div class="dropdown-divider my-1"></div>

                            <Link class="dropdown-item d-flex align-items-center logout-btn" href="/logout" method="POST"
                                as="button">
                                <svg class="dropdown-icon text-danger me-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </Link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
import {
    Link
} from '@inertiajs/vue3';

export default {
    components: {
        Link
    },

    computed: {
        appName() {
            return this.$page.props.appSettings?.name_app || 'ADMIN PANEL';
        },
    },
}
</script>

<style scoped>
.profile-dropdown {
    min-width: 220px;
    border-radius: 14px;
}

.profile-link {
    color: #212529 !important;
}

.profile-link:hover,
.profile-link:focus {
    color: #212529 !important;
}

.logout-btn {
    border-radius: 10px;
}
</style>
