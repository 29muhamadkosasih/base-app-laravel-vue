<template>
    <div class="container-fluid mb-5 mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white border-0 py-4 px-4 px-lg-5">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <h4 class="fw-bold mb-1 text-dark">Setting Aplikasi</h4>
                                <p class="text-muted mb-0">Kelola nama aplikasi, slug, dan logo aplikasi.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-lg-5">
                        <form @submit.prevent="submit">
                            <div class="row g-4">
                                <div class="col-lg-7">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-dark">Nama Aplikasi</label>
                                        <input
                                            v-model="form.name_app"
                                            type="text"
                                            class="form-control form-control"
                                            placeholder="Contoh: Ujian Online Sekolah"
                                        >
                                        <div v-if="errors.name_app" class="alert alert-danger mt-2 mb-0">{{ errors.name_app }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-dark">Slug</label>
                                        <input
                                            v-model="form.slug"
                                            type="text"
                                            class="form-control"
                                            placeholder="ujian-online-sekolah"
                                        >
                                        <small class="text-muted">Jika dikosongkan, slug akan dibuat otomatis dari nama aplikasi.</small>
                                        <div v-if="errors.slug" class="alert alert-danger mt-2 mb-0">{{ errors.slug }}</div>
                                    </div>

                                    <div>
                                        <label class="form-label fw-semibold text-dark">Logo / Icon Aplikasi</label>
                                        <input
                                            class="form-control"
                                            type="file"
                                            accept=".jpg,.jpeg,.png,.webp,.svg"
                                            @change="handleImageChange"
                                        >
                                        <small class="text-muted">Logo akan disimpan ke folder public storage dan dipakai sebagai icon aplikasi.</small>
                                        <div v-if="errors.image" class="alert alert-danger mt-2 mb-0">{{ errors.image }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <div class="setting-preview-card h-100">
                                        <div class="preview-icon-wrap">
                                            <img v-if="previewImage" :src="previewImage" alt="Preview logo" class="preview-icon">
                                            <div v-else class="preview-icon preview-icon-placeholder">
                                                <i class="fa fa-image"></i>
                                            </div>
                                        </div>

                                        <div class="mt-3 text-center">
                                            <div class="fw-bold text-dark fs-5">{{ form.name_app || 'Nama aplikasi' }}</div>
                                            <div class="text-muted mt-1">/{{ normalizedSlug || 'slug-aplikasi' }}</div>
                                        </div>

                                        <div class="preview-meta mt-4">
                                            <div class="preview-meta-item">
                                                <span class="preview-meta-label">Status</span>
                                                <span class="badge bg-success-subtle text-success-emphasis border">Aktif</span>
                                            </div>
                                            <div class="preview-meta-item">
                                                <span class="preview-meta-label">Icon</span>
                                                <span class="preview-meta-value">{{ previewImage ? 'Tersedia' : 'Belum ada' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-light border" @click="resetForm">Reset</button>
                                <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                    <span v-if="form.processing">Menyimpan...</span>
                                    <span v-else>Simpan Setting</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export default {
    layout: LayoutAdmin,

    props: {
        setting: Object,
        errors: Object,
    },

    data() {
        return {
            form: useForm({
                name_app: this.setting?.name_app || '',
                slug: this.setting?.slug || '',
                image: null,
            }),
            previewImage: this.setting?.image_url || null,
        };
    },

    computed: {
        normalizedSlug() {
            return (this.form.slug || this.form.name_app || '')
                .toString()
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        },
    },

    methods: {
        handleImageChange(event) {
            const file = event.target.files[0] || null;

            this.form.image = file;

            if (!file) {
                this.previewImage = this.setting?.image_url || null;
                return;
            }

            this.previewImage = URL.createObjectURL(file);
        },
        resetForm() {
            this.form.name_app = this.setting?.name_app || '';
            this.form.slug = this.setting?.slug || '';
            this.form.image = null;
            this.previewImage = this.setting?.image_url || null;
        },
        submit() {
            this.form.post('/admin/settings', {
                forceFormData: true,
                onSuccess: () => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Setting aplikasi berhasil disimpan',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                },
            });
        },
    },
};
</script>

<style scoped>
.setting-preview-badge {
    display: inline-flex;
    align-items: center;
    padding: .65rem 1rem;
    border-radius: 999px;
    background: #f8f9fc;
    border: 1px solid #e9ecef;
    color: #495057;
    font-weight: 600;
}

.setting-preview-card {
    padding: 1.5rem;
    border-radius: 18px;
    background: linear-gradient(135deg, #f8fbff 0%, #ffffff 45%, #eef4ff 100%);
    border: 1px solid #e9ecef;
}

.preview-icon-wrap {
    display: flex;
    justify-content: center;
}

.preview-icon {
    width: 92px;
    height: 92px;
    border-radius: 20px;
    object-fit: cover;
    border: 1px solid #dee2e6;
    background: #fff;
}

.preview-icon-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.75rem;
}

.preview-meta {
    display: grid;
    gap: .75rem;
}

.preview-meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: .75rem;
    border-bottom: 1px dashed #dee2e6;
}

.preview-meta-item:last-child {
    padding-bottom: 0;
    border-bottom: 0;
}

.preview-meta-label {
    color: #6c757d;
}

.preview-meta-value {
    color: #212529;
    font-weight: 600;
}
</style>