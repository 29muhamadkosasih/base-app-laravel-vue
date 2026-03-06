<template>
    <div class="container-fluid mb-5 mt-3">
        <div class="row">
            <div class="col-md-12">
                <Link href="/admin/roles" class="btn btn-md btn-primary border-0 shadow mb-3" type="button">
                    <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
                </Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-user-shield"></i> Tambah Role</h5>
                        <hr>

                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <label>Nama Role</label>
                                <input type="text" class="form-control" placeholder="Contoh: admin, operator, guru" v-model="form.name">
                                <small class="text-muted">Role akan otomatis dibersihkan menjadi huruf kecil.</small>
                                <div v-if="errors.name" class="alert alert-danger mt-2">{{ errors.name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="mb-2">Permission untuk Role</label>
                                <PermissionChecklist v-model="form.permissions" :permissions="permissions" />
                                <div v-if="errors.permissions" class="alert alert-danger mt-2">{{ errors.permissions }}</div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Simpan</button>
                                <button type="button" class="btn btn-md btn-warning border-0 shadow" @click="resetForm">Reset</button>
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
import PermissionChecklist from '../../../Components/PermissionChecklist.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import Swal from 'sweetalert2';

export default {
    layout: LayoutAdmin,

    components: {
        Link,
        PermissionChecklist,
    },

    props: {
        errors: Object,
        permissions: Array,
    },

    setup() {
        const form = reactive({
            name: '',
            permissions: [],
        });

        const submit = () => {
            router.post('/admin/roles', form, {
                onSuccess: () => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Role berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });
                },
            });
        };

        const resetForm = () => {
            form.name = '';
            form.permissions = [];
        };

        return {
            form,
            submit,
            resetForm,
        };
    },
};
</script>
