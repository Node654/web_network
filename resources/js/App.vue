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
    <div class="flex justify-center space-x-2 mt-5 mb-4">
        <div>
            <RouterLink v-if="token" :to="{ name: 'index' }">Index</RouterLink>
        </div>
        <div>
            <RouterLink v-if="!token" :to="{ name: 'user.login' }" class="hover:text-red-700 ">Login</RouterLink>
        </div>
        <div>
            <RouterLink v-if="token" :to="{ name: 'get.index' }">Get</RouterLink>
        </div>
        <div>
            <RouterLink v-if="!token" :to="{ name: 'user.register' }" class="hover:text-red-700 ">Register</RouterLink>
        </div>
        <div>
            <RouterLink v-if="token" :to="{ name: 'user.personal' }">Personal</RouterLink>
        </div>
        <div>
            <a v-if="token" @click.prevent="logout" href="#">Logout</a>
        </div>
    </div>

    <div>
        <router-view/>
    </div>
</template>

<style scoped>

</style>

