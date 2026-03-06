<template>
    <div class="container-fluid mt-3 profile-page">
        <div class="row">
            <div class="col-12">
                <div class="profile-hero card border-0 shadow overflow-hidden">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <img :src="avatarUrl" :alt="profile.name" class="profile-avatar rounded-circle shadow-sm">
                                    <div class="profile-summary-text">
                                        <div class="text-uppercase small fw-semibold text-primary mb-2">Profil Saya</div>
                                        <h3 class="mb-1 fw-bold text-dark">{{ profile.name }}</h3>
                                        <p class="profile-email mb-2">{{ profile.email }}</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge rounded-pill bg-primary">{{ profile.primary_role || 'Tanpa role' }}</span>
                                            <span class="badge rounded-pill bg-primary text-dark border">ID User: {{ profile.id }}</span>
                                            <span v-if="profile.email_verified_at" class="badge rounded-pill bg-success">Email Terverifikasi</span>
                                            <span v-else class="badge rounded-pill bg-warning text-dark">Email Belum Verifikasi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="mini-stat card border-0 shadow-sm h-100">
                                            <div class="card-body py-3">
                                                <div class="mini-stat-label small">Total Role</div>
                                                <div class="mini-stat-value fs-3 fw-bold">{{ profile.roles.length }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mini-stat card border-0 shadow-sm h-100">
                                            <div class="card-body py-3">
                                                <div class="mini-stat-label small">Total Permission</div>
                                                <div class="mini-stat-value fs-3 fw-bold">{{ profile.permissions.length }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-0 g-4">
            <div class="col-lg-5">
                <div class="card border-0 shadow h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 section-heading">Informasi Akun</h5>

                        <div class="info-list">
                            <div class="info-item">
                                <span class="info-label">Nama Lengkap</span>
                                <span class="info-value">{{ profile.name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Email</span>
                                <span class="info-value">{{ profile.email }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Role Utama</span>
                                <span class="info-value">{{ profile.primary_role || '-' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Bergabung</span>
                                <span class="info-value">{{ profile.created_at || '-' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Terakhir Update</span>
                                <span class="info-value">{{ profile.updated_at || '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 section-heading">Role</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <span v-if="!profile.roles.length" class="badge bg-light text-muted border">Belum ada role</span>
                            <span v-for="role in profile.roles" :key="role" class="badge rounded-pill border role-badge">{{ role }}</span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <h5 class="fw-bold mb-0 section-heading">Permission</h5>
                            <span class="badge bg-light text-dark border">{{ profile.permissions.length }} permission</span>
                        </div>

                        <div class="row g-2">
                            <div v-if="!profile.permissions.length" class="col-12">
                                <div class="alert alert-light border mb-0">Belum ada permission.</div>
                            </div>

                            <div v-for="permission in profile.permissions" :key="permission" class="col-md-6">
                                <div class="permission-item">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    <span>{{ permission }}</span>
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
        profile: Object,
    },

    computed: {
        avatarUrl() {
            return `https://ui-avatars.com/api/?name=${this.profile.name}&background=4e73df&color=ffffff&size=140`;
        },
    },
};
</script>

<style scoped>
.profile-hero {
    background: linear-gradient(135deg, #f8fbff 0%, #ffffff 45%, #eef4ff 100%);
}

.profile-page,
.profile-page .card,
.profile-page .card-body,
.profile-page .permission-item,
.profile-page .mini-stat {
    color: #212529;
}

.profile-page .card {
    background: #ffffff;
}

.profile-summary-text {
    color: #212529;
}

.profile-email {
    color: #5f6b7a;
}

.profile-avatar {
    width: 92px;
    height: 92px;
    object-fit: cover;
    border: 4px solid rgba(78, 115, 223, 0.16);
}

.mini-stat {
    background: rgba(255, 255, 255, 0.8);
}

.mini-stat-label {
    color: #6c757d;
}

.mini-stat-value,
.section-heading {
    color: #212529;
}

.info-list {
    display: grid;
    gap: 14px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding-bottom: 12px;
    border-bottom: 1px dashed #e9ecef;
}

.info-item:last-child {
    border-bottom: 0;
    padding-bottom: 0;
}

.info-label {
    color: #6c757d;
    min-width: 120px;
}

.info-value {
    color: #212529;
    font-weight: 600;
    text-align: right;
}

.role-badge {
    font-size: .9rem;
    color: #212529;
    background: #f8f9fc;
}

.permission-item {
    display: flex;
    align-items: center;
    height: 100%;
    padding: 12px 14px;
    background: #f8f9fc;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    color: #212529;
}

@media (max-width: 767.98px) {
    .info-item {
        flex-direction: column;
        gap: 6px;
    }

    .info-value {
        text-align: left;
    }
}
</style>