<script setup>

    import Post from "../../components/Post.vue";
    import {onMounted, ref} from "vue";
    import axios from "axios";

    const posts = ref([]);

    function getFeedPosts()
    {
        axios.get('/api/users/following_posts').then(response => {
            posts.value = response.data.data;
            console.log(posts);
        })
    }

    onMounted(() => {
        getFeedPosts()
    })

</script>

<template>
    <div class="w-96 mx-auto text-center">
        <div v-if="posts" class="mt-7">
            <h1 class="text-4xl mb-10">Feed posts</h1>
            <Post v-for="post in posts" :post="post"/>
        </div>
    </div>
</template>

<style scoped>

</style>
