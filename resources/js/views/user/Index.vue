<script setup>

    import {inject, onMounted, ref} from "vue";
    import axios from "axios";

    const getToken = inject('getToken');
    const users = ref(null);
    const checkToken = inject('checkToken');

    onMounted(() => {
        getUsers();
    })

    function getUsers() {
        axios.get('/api/users').then(response => {
            users.value = response.data.data;
        });
    }

    function toggleFollowing(user) {
        if (checkToken()) {
            axios.get(`/api/users/${user.id}/toggle_following`).then(response => {
                console.log(response)
                user.is_followed = response.data.data.is_followed;

            });
        }

    }

</script>

<template>

<div class="w-96 mx-auto">
    <h1 class="text-4xl mb-10 text-center">Users</h1>

    <div v-if="users" class="mt-7">
        <div v-for="user in users" class="mb-10">
            <div class="flex justify-between items-center">
                <div>
                    <RouterLink :to="{name: 'user.show', params: {id: user.id}}">
                        <h3 class="text-xl mb-2 hover:text-green-900 w-14">Id: {{ user.id }}</h3>
                    </RouterLink>
                    <p class="text-xl mb-2">Name: {{ user.name }}</p>
                    <p class="text-xl mb-2">Email: {{ user.email }}</p>
                </div>
                <div>
                    <input
                        @click.prevent="toggleFollowing(user)"
                        type="submit" value="following"
                        :class="['text-lg cursor-pointer p-3  w-28 rounded-lg', user.is_followed ? 'bg-green-500 hover:text-amber-800 text-white' : 'bg-sky-500 hover:text-red-700']"
                    >
                </div>
            </div>
            <p class="pb-6 border-b-4 text-xl">Date register user: {{ user.date }}</p>
        </div>
    </div>
</div>

</template>

<style scoped>

</style>
