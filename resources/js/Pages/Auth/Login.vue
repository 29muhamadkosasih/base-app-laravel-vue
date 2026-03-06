<template>

    <Head :title="`Login`" />

    <div class="login-card border-0 shadow-lg rounded-4 overflow-hidden w-100">
        <div class="row g-0">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="login-side h-100 d-flex flex-column justify-content-between p-4 p-xl-5">
                    <div class="login-side-overlay"></div>
                    <div class="login-side-content position-relative">
                        <div class="login-brand d-inline-flex align-items-center gap-3">
                            <img v-if="appLogo" :src="appLogo" :alt="appName" class="login-brand-logo">
                            <div v-else class="login-brand-logo placeholder-logo">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                            <div>
                                <div class="small text-white-50 text-uppercase fw-semibold">Selamat Datang</div>
                                <h4 class="text-white fw-bold mb-0">{{ appName }}</h4>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h2 class="text-white fw-bold lh-sm mb-3">Masuk ke panel aplikasi dengan aman dan cepat.</h2>
                            <p class="text-white-50 mb-0">
                                Kelola data ujian, pengguna, role, dan pengaturan aplikasi dalam satu dashboard yang rapi.
                            </p>
                        </div>
                    </div>

                    <div class="login-feature-list position-relative">
                        <div class="login-feature-item">
                            <i class="fa fa-check-circle me-2"></i>
                            Akses panel sesuai role
                        </div>
                        <div class="login-feature-item">
                            <i class="fa fa-check-circle me-2"></i>
                            Tampilan responsif dan modern
                        </div>
                        <div class="login-feature-item">
                            <i class="fa fa-check-circle me-2"></i>
                            Manajemen data terpusat
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="login-form-wrap bg-white p-4 p-lg-5">
                    <div class="text-center text-lg-start mb-1">
                        <div class="login-mobile-brand d-inline-flex d-lg-none align-items-center gap-2 mb-3">
                            <img v-if="appLogo" :src="appLogo" :alt="appName" class="login-mobile-logo">
                            <div v-else class="login-mobile-logo placeholder-logo">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                            <span class="fw-bold text-dark">{{ appName }}</span>
                        </div>

                        <h3 class="fw-bold text-dark mb-2">Login</h3>
                        <p class="text-muted mb-0">Masukkan email dan password untuk melanjutkan ke dashboard.</p>
                    </div>

                    <div v-if="$page.props.flash.error" class="alert alert-danger border-0 shadow-sm">
                        {{ $page.props.flash.error }}
                    </div>

                    <div v-if="$page.props.flash.success" class="alert alert-success border-0 shadow-sm">
                        {{ $page.props.flash.success }}
                    </div>

                    <form @submit.prevent="submit" class="mt-1">
                        <div class="form-group mb-4">
                            <label for="email" class="form-label fw-semibold text-dark">Email</label>
                            <div class="input-group input-group-modern">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <input id="email" type="email" class="form-control" v-model="form.email" placeholder="Masukkan email" autocomplete="email">
                            </div>
                            <div v-if="errors.email" class="alert alert-danger mt-2 mb-0">
                                {{ errors.email }}
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="form-label fw-semibold text-dark">Password</label>
                            <div class="input-group input-group-modern">
                                <span class="input-group-text" id="basic-addon2">
                                    <i class="fa fa-lock"></i>
                                </span>
                                <input id="password" type="password" placeholder="Masukkan password" class="form-control" v-model="form.password" autocomplete="current-password">
                            </div>
                            <div v-if="errors.password" class="alert alert-danger mt-2 mb-0">
                                {{ errors.password }}
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" v-model="form.remember">
                                <label class="form-check-label mb-0 text-muted" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                            <span class="login-hint text-muted small">Gunakan akun yang telah diberikan administrator.</span>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-gray-800 btn login-submit-btn">Masuk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    //import layout
    import LayoutAuth from '../../Layouts/Auth.vue';

    //import Inertia
    import {
        Head,
        router
    } from '@inertiajs/vue3';

    //import reactive
    import {
        reactive
    } from 'vue';

    export default {

        //layout
        layout: LayoutAuth,

        //register component
        components: {
            Head
        },

        //props
        props: {
            errors: Object,
            session: Object
        },

        computed: {
            appName() {
                return this.$page.props.appSettings?.name_app || 'Admin Panel';
            },
            appLogo() {
                return this.$page.props.appSettings?.image_url || null;
            },
        },

        //define composition API
        setup() {

            //define form state
            const form = reactive({
                email: '',
                password: '',
                remember: false,
            });

            //submit method
            const submit = () => {

                //send data to server
                router.post('/login', {
                    email: form.email,
                    password: form.password,
                    remember: form.remember,
                });
            }

            //return form state and submit method
            return {
                form,
                submit,
            };

        }

    }
</script>

<style scoped>
.login-card {
    max-width: 1120px;
    margin: 0 auto;
    background: #ffffff;
}

.login-side {
    min-height: 640px;
    position: relative;
    background:
        linear-gradient(180deg, rgba(17, 24, 39, 0.2), rgba(17, 24, 39, 0.85)),
        url('/assets/images/signin.svg') center center / cover no-repeat,
        linear-gradient(145deg, #1f2937 0%, #374151 50%, #111827 100%);
}

.login-side-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(17, 24, 39, 0.15), rgba(17, 24, 39, 0.75));
}

.login-side-content {
    z-index: 1;
}

.login-brand-logo,
.login-mobile-logo {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    object-fit: cover;
    background: rgba(255, 255, 255, .12);
}

.login-mobile-logo {
    width: 38px;
    height: 38px;
    border-radius: 12px;
}

.placeholder-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 1.25rem;
}

.login-feature-list {
    display: grid;
    gap: .75rem;
    color: rgba(255, 255, 255, .88);
}

.login-feature-item {
    display: flex;
    align-items: center;
    font-size: .95rem;
}

.login-form-wrap {
    min-height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.input-group-modern .input-group-text {
    background: #f8f9fc;
    border-right: 0;
    color: #6c757d;
}

.input-group-modern .form-control {
    border-left: 0;
    padding-top: .85rem;
    padding-bottom: .85rem;
}

.input-group-modern .form-control:focus {
    box-shadow: none;
}

.input-group-modern:focus-within {
    box-shadow: 0 0 0 .2rem rgba(78, 115, 223, .12);
    border-radius: .75rem;
}

.input-group-modern .input-group-text,
.input-group-modern .form-control {
    border-color: #dfe3ea;
}

.login-submit-btn {
    border-radius: .80rem;
    padding-top: .80rem;
    padding-bottom: .80rem;
    font-weight: 700;
    letter-spacing: .01em;
}

.login-hint {
    display: inline-block;
}

@media (max-width: 991.98px) {
    .login-card {
        max-width: 560px;
    }
}
</style>
