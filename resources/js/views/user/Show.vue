<script setup>

    import {inject, onMounted, ref} from "vue";
    import Post from "../../components/Post.vue";
    import {useRoute} from "vue-router";

    const route = useRoute();
    const posts = ref(null);
    const userId = route.params.id;

    onMounted(() => {
        getPosts();
    })

    function getPosts()
    {
        axios.get(`/api/users/${userId}/posts`).then(response => {
            posts.value = response.data.data;
        })
    }

</script>

<template>

<div class="w-96 mx-auto text-center">
    <h1 class="mb-4">Show</h1>
    <div v-if="posts" class="mt-7">
        <h1 class="text-4xl mb-8">Posts</h1>
        <Post v-for="post in posts" :post="post"/>
    </div>
</div>

</template>

<style scoped>

</style>
