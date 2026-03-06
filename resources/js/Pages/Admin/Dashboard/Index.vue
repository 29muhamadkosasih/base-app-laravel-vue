<template>
    <div v-if="$page.props.flash.success" class="alert alert-success mt-3">
        {{ $page.props.flash.success }}
    </div>

    <div class="mt-3">
        <div class="card border-0 shadow-sm mb-4 bg-light">
            <div class="card-body p-4 p-lg-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h5 class="fw-bold mb-2 text-dark">Dashboard Admin</h5>
                        <p class="text-muted mb-0">
                            Ringkasan data utama yang saat ini tersedia di aplikasi untuk memudahkan monitoring dan pengelolaan.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                            {{ $page.props.appSettings?.name_app || 'Aplikasi Ujian Online' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid px-0 mb-5">
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-xl-4" v-for="item in summaryCards" :key="item.key">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <div class="text-muted small fw-semibold mb-2">{{ item.label }}</div>
                                    <div class="fs-2 fw-bold text-dark mb-2">{{ item.value }}</div>
                                    <div class="text-muted small">{{ item.description }}</div>
                                </div>
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white flex-shrink-0" :class="item.iconClass" style="width: 56px; height: 56px;">
                                    <i :class="item.icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';

export default {
    layout: LayoutAdmin,

    props: {
        summary: Object,
    },

    computed: {
        summaryCards() {
            return [
                {
                    key: 'users',
                    label: 'Pengguna',
                    value: this.summary.users,
                    description: 'Total akun pengguna yang tersimpan.',
                    icon: 'fa fa-users',
                    iconClass: 'bg-primary',
                },
                {
                    key: 'roles',
                    label: 'Role',
                    value: this.summary.roles,
                    description: 'Role yang tersedia untuk pengaturan akses.',
                    icon: 'fa fa-user-shield',
                    iconClass: 'bg-success',
                },
                {
                    key: 'permissions',
                    label: 'Permission',
                    value: this.summary.permissions,
                    description: 'Hak akses yang digunakan dalam sistem.',
                    icon: 'fa fa-key',
                    iconClass: 'bg-warning',
                },
                {
                    key: 'settings',
                    label: 'Setting Aplikasi',
                    value: this.summary.settings,
                    description: 'Data setting aplikasi yang aktif.',
                    icon: 'fa fa-cogs',
                    iconClass: 'bg-dark',
                },
            ];
        },
    },
}
</script>
