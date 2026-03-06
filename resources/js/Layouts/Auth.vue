<template>
    <section class="auth-layout min-vh-100 d-flex align-items-center py-4 py-lg-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <slot />
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {

    }
</script>

<style scoped>
.auth-layout {
    background:
        radial-gradient(circle at top left, rgba(78, 115, 223, 0.18), transparent 28%),
        radial-gradient(circle at bottom right, rgba(17, 24, 39, 0.15), transparent 24%),
        linear-gradient(180deg, #f7faff 0%, #eef3fb 100%);
}
</style>