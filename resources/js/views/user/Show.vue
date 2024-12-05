<script setup>

import {computed, inject, onMounted, ref} from "vue";
    import Post from "../../components/Post.vue";
    import {useRoute} from "vue-router";
    import axios from "axios";
    import Stat from "../../components/Stat.vue";

    const route = useRoute();
    const posts = ref(null);
    const userId = route.params.id;
    const stats = ref({});
    const totalLikesCount = ref(0);

    onMounted(() => {
        getPosts();
        getStats();
    })

    function getPosts()
    {
        axios.get(`/api/users/${userId}/posts`).then(response => {
            posts.value = response.data.data;
        })
    }

    function getStats() {
        axios.post('/api/users/stats', {user_id: userId}).then(response => {
            stats.value = response.data.data;
            totalLikesCount.value = computed(() => {
                return posts.value.reduce((acc, post) => acc + post.likes_count, 0)
            })
            stats.value.likes_count = totalLikesCount;
        })
    }

</script>

<template>

<div class="w-96 mx-auto text-center">
    <Stat :stats="stats" />
    <h1 class="mb-4">Show</h1>
    <div v-if="posts" class="mt-7">
        <h1 class="text-4xl mb-8">Posts</h1>
        <Post v-for="post in posts" :post="post"/>
    </div>
</div>

</template>

<style scoped>

</style>
