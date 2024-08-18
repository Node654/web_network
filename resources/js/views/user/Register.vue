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
                console.log(response);
                localStorage.setItem('x_xsrf_token', document.cookie.match(/XSRF-TOKEN=([^;]*)/)[1]);
                if (getToken) {
                    getToken();
                }
                router.push({ name: 'user.personal'});
            }).catch(rejected => {
                console.log(rejected);
            })
        })
    }


</script>

<template>

    <div class="mt-5 p-5">
        <form @submit.prevent="register">
            <div class="mb-3">
                <input type="text" placeholder="name" class="form-control" v-model="form.name">
            </div>
            <div class="mb-3">
                <input type="email" placeholder="email" class="form-control" v-model="form.email">
            </div>
            <div class="mb-3">
                <input type="password" placeholder="password" class="form-control" v-model="form.password">
            </div>
            <div class="mb-3">
                <input type="password" placeholder="password_confirmation" class="form-control" v-model="form.password_confirmation">
            </div>
            <div class="mb-3">
                <input type="submit" value="Register" class="btn btn-primary">
            </div>
        </form>
    </div>

</template>

<style scoped>

</style>
