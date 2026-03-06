<template>
    <div class="container-fluid mb-5 mt-3">
        <div class="row">
            <div class="col-md-12">
                <Link href="/admin/roles" class="btn btn-md btn-primary border-0 shadow mb-3" type="button">
                    <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
                </Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-user-shield"></i> Edit Role</h5>
                        <hr>

                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <label>Nama Role</label>
                                <input type="text" class="form-control" placeholder="Masukkan nama role" v-model="form.name">
                                <div v-if="errors.name" class="alert alert-danger mt-2">{{ errors.name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="mb-2">Permission untuk Role</label>
                                <PermissionChecklist v-model="form.permissions" :permissions="permissions" />
                                <div v-if="errors.permissions" class="alert alert-danger mt-2">{{ errors.permissions }}</div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Update</button>
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
        role: Object,
        permissions: Array,
    },

    setup(props) {
        const form = reactive({
            name: props.role.name,
            permissions: [...props.role.permissions],
        });

        const submit = () => {
            router.put(`/admin/roles/${props.role.id}`, form, {
                onSuccess: () => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Role berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });
                },
            });
        };

        const resetForm = () => {
            form.name = props.role.name;
            form.permissions = [...props.role.permissions];
        };

        return {
            form,
            submit,
            resetForm,
        };
    },
};
</script>
