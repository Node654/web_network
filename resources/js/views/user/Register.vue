<script setup>

    import {inject, reactive} from "vue";
    import {useRouter} from "vue-router";

    const router = useRouter();
    const form = reactive({
        name: null,
        email: null,
        password: null,
        password_confirmation: null
    });
    const getToken = inject("getToken");

    function register() {
        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.post('/register', {
                name: form.name,
                email: form.email,
                password: form.password,
                password_confirmation: form.password_confirmation
            }).then(response => {
                // обработать валидацию!
                localStorage.setItem('x_xsrf_token', document.cookie.match(/XSRF-TOKEN=([^;]*)/)[1]);
                if (getToken) {
                    getToken();
                }
                console.log(response);
                router.push({ name: 'user.personal'});
            }).catch(rejected => {
                console.log(rejected);
            })
        }).catch(error => {
            console.error('Error getting CSRF token:', error);
        });
    }

</script>

<template>

    <div class="w-96 mx-auto">
        <form @submit.prevent="register">
            <div class="mb-3">
                <input
                    type="text"
                    placeholder="name"
                    class=" w-96 p-1 mb-2 border border-inherit rounded-lg"
                    v-model="form.name"
                >
            </div>
            <div class="mb-3">
                <input
                    type="email"
                    placeholder="email"
                    class="w-96 p-1 mb-2 border border-inherit rounded-lg"
                    v-model="form.email"
                >
            </div>
            <div class="mb-3">
                <input
                    type="password"
                    placeholder="password"
                    class="w-96 p-1 mb-2 border border-inherit rounded-lg"
                    v-model="form.password"
                >
            </div>
            <div class="mb-3">
                <input
                    type="password"
                    placeholder="password_confirmation"
                    class="w-96 p-1 mb-2 border border-inherit rounded-lg"
                    v-model="form.password_confirmation"
                >
            </div>
            <div class="mb-3">
                <button
                    type="submit"
                    class="hover:text-red-500 cursor-pointer block float-right mx-auto w-32 p-1 bg-sky-400  text-white rounded-lg"
                >Register</button>
            </div>
        </form>
    </div>

</template>

<style scoped>

</style>
