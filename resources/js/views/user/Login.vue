<script setup>

    import {inject, onMounted, reactive} from "vue";
    import {useRouter} from "vue-router";

    const router = useRouter();
    const form = reactive({
        email: null,
        password: null,
    });
    const getToken = inject("getToken");
    onMounted(() => {
        getToken();
    });
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
            }).catch(e => {
                console.log(e);
            })
        });
    }

</script>

<template>

    <div class="w-96 mx-auto">
        <form @submit.prevent="login">
            <div class="mb-3">
                <input type="email" placeholder="email" class="w-96 p-1 mb-2 border border-inherit rounded-lg" v-model="form.email">
            </div>
            <div class="mb-3">
                <input type="password" placeholder="password" class="w-96 p-1 mb-2 border border-inherit rounded-lg" v-model="form.password">
            </div>
            <div class="mb-3">
                <input type="submit" value="Login" class="hover:text-red-700 block float-right mx-auto w-32 p-1 bg-sky-400 text-white cursor-pointer">
            </div>
        </form>
    </div>

</template>

<style scoped>

</style>
