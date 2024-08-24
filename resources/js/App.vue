<script setup>

import {useRouter} from "vue-router";
import {onMounted, provide, ref, watch} from "vue";

const router = useRouter();
const token =  ref(null);

function getToken() {
    token.value = localStorage.getItem('x_xsrf_token');
}

function logout() {
    axios.post('/logout').then(r => {
        localStorage.removeItem('x_xsrf_token');
        token.value = null;
        router.push({ name: 'user.login' });
    })
}

onMounted(getToken);
watch(token, () => {

});

provide('getToken', getToken);

</script>

<template>

    <div class="flex justify-between p-8 w-96 mx-auto">
        <RouterLink v-if="!token" :to="{ name: 'user.login' }" class="hover:text-red-700 ">Login</RouterLink>
        <RouterLink v-if="!token" :to="{ name: 'user.register' }" class="hover:text-red-700 ">Register</RouterLink>
        <RouterLink v-if="token" :to="{ name: 'user.personal' }">Personal</RouterLink>
        <a v-if="token" @click.prevent="logout" href="#">Logout</a>
    </div>

    <router-view/>

</template>

<style scoped>

</style>

