<template>
    <div class="container-fluid mb-5 mt-3">
        <div class="row">
            <div class="col-md-12">
                <Link href="/admin/users" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i
                    class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-user"></i> Edit User</h5>
                        <hr>
                        <form @submit.prevent="submit">

                            <div class="mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" placeholder="Masukkan Nama Lengkap" v-model="form.name">
                                <div v-if="errors.name" class="alert alert-danger mt-2">{{ errors.name }}</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" class="form-control" placeholder="Masukkan Email" v-model="form.email">
                                        <div v-if="errors.email" class="alert alert-danger mt-2">{{ errors.email }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Password <span class="text-muted">(kosongkan jika tidak ingin mengubah)</span></label>
                                        <input type="password" class="form-control" placeholder="Masukkan Password" v-model="form.password">
                                        <div v-if="errors.password" class="alert alert-danger mt-2">{{ errors.password }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Konfirmasi Password</label>
                                        <input type="password" class="form-control" placeholder="Masukkan Konfirmasi Password" v-model="form.password_confirmation">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="mb-2">Assign Role</label>
                                <div class="row">
                                    <div v-for="role in roles" :key="role" class="col-md-4 col-sm-6 mb-2">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" :value="role" v-model="form.roles">
                                            <span class="form-check-label">{{ role }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div v-if="errors.roles" class="alert alert-danger mt-2">{{ errors.roles }}</div>
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
//import layout
import LayoutAdmin from '../../../Layouts/Admin.vue';

//import Heade and Link from Inertia
import {
    Link,
    router
} from '@inertiajs/vue3';

//import reactive from vue
import {
    reactive
} from 'vue';

//import sweet alert2
import Swal from 'sweetalert2';

export default {

    //layout
    layout: LayoutAdmin,

    //register components
    components: {
        Link
    },

    //props
    props: {
        errors: Object,
        user: Object,
        roles: Array,
    },

    //inisialisasi composition API
    setup(props) {

        //define form with reactive
        const form = reactive({
            name: props.user.name,
            email: props.user.email,
            roles: [...props.user.roles],
            password: '',
            password_confirmation: ''
        });

        //method "submit"
        const submit = () => {

            //send data to server
            router.put(`/admin/users/${props.user.id}`, {
                //data
                name: form.name,
                email: form.email,
                password: form.password,
                roles: form.roles,
                password_confirmation: form.password_confirmation,
            }, {
                onSuccess: () => {
                    //show success alert
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data berhasil diupdate',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                },
            });

        }

        const resetForm = () => {
            form.name = props.user.name;
            form.email = props.user.email;
            form.roles = [...props.user.roles];
            form.password = '';
            form.password_confirmation = '';
        }

        //return
        return {
            form,
            submit,
            resetForm,
        }

    }

}
</script>
