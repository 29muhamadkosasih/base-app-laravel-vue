<template>
    <nav class="sidebar bg-gray-800 text-white" :class="{ 'show': open }">
        <div class="sidebar-inner px-4 pt-3">
            <ul class="nav flex-column pt-1 pt-md-0">
                <li class="nav-item">
                    <span class="mt-2 d-flex justify-content-between">
                        <h5 class="ms-3 text-white fw-bold">{{ slug }}</h5>
                    </span>
                </li>

                <li role="separator" class="dropdown-divider mt-1 mb-2 border-gray-700"></li>

                <li v-if="hasPermission('dashboard.view')" class="nav-item"
                    :class="{ 'active': $page.url.startsWith('/admin/dashboard') }">
                    <Link href="/admin/dashboard" class="nav-link d-flex justify-content-between"
                        @click="handleNavigate">
                    <span class="sidebar-text"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</span>
                    </Link>
                </li>

                <li v-if="hasAnyPermission([
                        'users.view', 'users.create', 'users.edit', 'users.delete',
                        'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
                        'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete'
                    ])"
                    class="nav-item" :class="{ 'active': isManagementRoute }">
                    <button type="button"
                        class="nav-link nav-link-toggle d-flex justify-content-between align-items-center w-100"
                        @click="toggleManagementMenu">
                        <span class="sidebar-text"><i class="fa fa-users-cog me-2"></i>User &amp; Management</span>
                        &nbsp;
                        <i class="fa fa-chevron-right sidebar-chevron" :class="{ 'rotated': isManagementOpen }"></i>
                    </button>

                    <ul v-show="isManagementOpen" class="sidebar-submenu list-unstyled">
                        <li v-if="hasAnyPermission(['users.view', 'users.create', 'users.edit', 'users.delete'])"
                            class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/users') }">
                            <Link href="/admin/users" class="nav-link d-flex align-items-center"
                                @click="handleNavigate">
                            <i class="fa fa-circle sidebar-submenu-icon"></i>
                            <span class="sidebar-text">User</span>
                            </Link>
                        </li>

                        <li v-if="hasAnyPermission(['roles.view', 'roles.create', 'roles.edit', 'roles.delete'])"
                            class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/roles') }">
                            <Link href="/admin/roles" class="nav-link d-flex align-items-center"
                                @click="handleNavigate">
                            <i class="fa fa-circle sidebar-submenu-icon"></i>
                            <span class="sidebar-text">Role</span>
                            </Link>
                        </li>

                        <li v-if="hasAnyPermission(['permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete'])"
                            class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/permissions') }">
                            <Link href="/admin/permissions" class="nav-link d-flex align-items-center"
                                @click="handleNavigate">
                            <i class="fa fa-circle sidebar-submenu-icon"></i>
                            <span class="sidebar-text">Permission</span>
                            </Link>
                        </li>
                    </ul>
                </li>

                <li v-if="hasAnyPermission(['settings.index'])" class="nav-item"
                    :class="{ 'active': $page.url.startsWith('/admin/settings') }">
                    <Link href="/admin/settings" class="nav-link d-flex justify-content-between"
                        @click="handleNavigate">
                    <span class="sidebar-text"><i class="fa fa-cogs me-2"></i>Setting</span>
                    </Link>
                </li>

            </ul>
        </div>
    </nav>
</template>

<script>
    import {
        Link
    } from '@inertiajs/vue3';

    export default {
        props: ['open'],
        emits: ['close'],

        data() {
            return {
                managementOpen: false,
            };
        },

        components: {
            Link,
        },

        watch: {
            '$page.url': {
                immediate: true,
                handler(url) {
                    this.managementOpen = url.startsWith('/admin/users') ||
                        url.startsWith('/admin/roles') ||
                        url.startsWith('/admin/permissions');
                },
            },
        },

        computed: {
            slug() {
                return this.$page.props.appSettings?.slug || 'admin-panel';
            },
            isManagementRoute() {
                return this.$page.url.startsWith('/admin/users') ||
                    this.$page.url.startsWith('/admin/roles') ||
                    this.$page.url.startsWith('/admin/permissions');
            },
            isManagementOpen() {
                return this.managementOpen;
            },
            userPermissions() {
                return this.$page.props.auth.user?.permissions || [];
            },
        },

        methods: {
            hasPermission(permission) {
                return this.userPermissions.includes(permission);
            },
            hasAnyPermission(permissions) {
                return permissions.some((permission) => this.hasPermission(permission));
            },
            handleNavigate() {
                this.$emit('close');
            },
            toggleManagementMenu() {
                this.managementOpen = !this.managementOpen;
            },
        },
    }
</script>

<style>
    .sidebar {
        width: 265px;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 1040;
        transition: .3s;
    }

    .nav-link-toggle {
        background: transparent;
        border: 0;
        color: inherit;
        text-align: left;
        cursor: pointer;
        opacity: 1;
    }

    .sidebar-submenu {
        margin-top: .35rem;
        margin-left: .75rem;
        padding-left: .75rem;
        border-left: 1px solid rgba(255, 255, 255, .15);
    }

    .sidebar-submenu .nav-link {
        padding-top: .45rem;
        padding-bottom: .45rem;
        font-size: .95rem;
        opacity: .9;
    }

    .sidebar-submenu .nav-item.active .nav-link {
        background: rgba(255, 255, 255, .08);
        border-radius: .5rem;
        opacity: 1;
    }

    .sidebar-submenu-icon {
        font-size: .4rem;
        margin-right: .75rem;
        opacity: .8;
    }

    .sidebar-chevron {
        font-size: .8rem;
        transition: transform .2s ease;
    }

    .sidebar-chevron.rotated {
        transform: rotate(180deg);
    }

    /* mobile default hidden */
    @media(max-width:991px) {

        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
            box-shadow: 0 0 40px rgba(0, 0, 0, .4);
        }

    }
</style>
