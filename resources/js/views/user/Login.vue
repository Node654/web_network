<script setup>

    import {inject, reactive} from "vue";
    import {useRouter} from "vue-router";

    const router = useRouter();
    const form = reactive({
        email: null,
        password: null,
    });
    const getToken = inject("getToken");
    function login() {
        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.post('/login', {
                email: form.email,
                password: form.password
            }).then(response => {
                localStorage.setItem('x_xsrf_token', document.cookie.match(/XSRF-TOKEN=([^;]*)/)[1]);
                if (getToken) {
                    getToken();
                }
                router.push({ name: 'user.personal'});
            })
        });
    }

</script>

<template>

    <div class="w-full text-center">
        <form @submit.prevent="login">
            <div class="mb-3">
                <input type="email" placeholder="email" class="form-control" v-model="form.email">
            </div>
            <div class="mb-3">
                <input type="password" placeholder="password" class="form-control" v-model="form.password">
            </div>
            <div class="mb-3">
                <input type="submit" value="Login" class="hover:text-sky-500 cursor-pointer">
            </div>
        </form>
    </div>

</template>

<style scoped>

</style>
