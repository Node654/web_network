<script setup>

import axios from "axios";
import {inject} from "vue";

const checkToken = inject('checkToken');

defineProps({
    post: {
        type: Object,
        required: true
    },
})

function likedPost(post) {
    if (checkToken()) {
        axios.get(`/api/posts/${post.id}/liked_post`).then(response => {
            post.is_liked = response.data.data.is_liked;
            post.likes_count = response.data.data.likes_count;
        })
    }
}

</script>

<template>
    <div class="mb-14">
        <h3 class="text-2xl mb-4">Title: {{ post.title }}</h3>
        <img v-if="post.image_url" :src="post.image_url" :alt="post.title" class="w-56 block mx-auto mb-4">
        <p class="text-xl mb-4">Content: {{ post.content }}</p>
        <div class="pb-4 border-b-4 flex justify-between items-center">
            <div class="flex items-center">
                <svg class="mr-3" @click.prevent="likedPost(post)" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 489.543 489.543" :class="['cursor-pointer w-8', post.is_liked ? 'fill-red-600' : 'hover:fill-red-600  fill-slate-400']"><g><g><path d="M270.024,0c-22.6,0-15,48.3-15,48.3s-48.3,133.2-94.5,168.7c-9.9,10.4-16.1,21.9-20,31.3l0,0l0,0 c-0.9,2.3-1.7,4.5-2.4,6.5c-3.1,6.3-9.7,16-23.8,24.5l46.2,200.9c0,0,71.5,9.3,143.2,7.8c28.7,2.3,59.1,2.5,83.3-2.7 c82.2-17.5,61.6-74.8,61.6-74.8c44.3-33.3,19.1-74.9,19.1-74.9c39.4-41.1,0.7-75.6,0.7-75.6s21.3-33.2-6.2-58.3 c-34.3-31.4-127.4-10.5-127.4-10.5l0,0c-6.5,1.1-13.4,2.5-20.8,4.3c0,0-32.2,15,0-82.7C346.324,15.1,292.624,0,270.024,0z"/><path d="M127.324,465.7l-35-166.3c-2-9.5-11.6-17.3-21.3-17.3h-66.8l-0.1,200.8h109.1C123.024,483,129.324,475.2,127.324,465.7z" /></g></g></svg>
                <p class="text-xl">{{ post.likes_count }}</p>
            </div>
            <p class="text-lg">Дата добавления поста: {{ post.create_post }}</p>
        </div>
    </div>
</template>

<style scoped>

</style>
