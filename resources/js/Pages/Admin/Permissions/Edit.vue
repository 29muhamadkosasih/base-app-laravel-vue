<template>
    <div class="container-fluid mb-5 mt-3">
        <div class="row">
            <div class="col-md-12">
                <Link href="/admin/permissions" class="btn btn-md btn-primary border-0 shadow mb-3" type="button">
                    <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
                </Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-key"></i> Edit Permission</h5>
                        <hr>
                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <label>Nama Permission</label>
                                <input type="text" class="form-control" placeholder="Masukkan nama permission" v-model="form.name">
                                <div v-if="errors.name" class="alert alert-danger mt-2">{{ errors.name }}</div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Update</button>
                                <button type="button" class="btn btn-md btn-warning border-0 shadow" @click="form.name = permission.name">Reset</button>
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
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import Swal from 'sweetalert2';

export default {
    layout: LayoutAdmin,

    components: {
        Link,
    },

    props: {
        errors: Object,
        permission: Object,
    },

    setup(props) {
        const form = reactive({
            name: props.permission.name,
        });

        const submit = () => {
            router.put(`/admin/permissions/${props.permission.id}`, form, {
                onSuccess: () => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Permission berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });
                },
            });
        };

        return {
            form,
            submit,
        };
    },
};
</script>
