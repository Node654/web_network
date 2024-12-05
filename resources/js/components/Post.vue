<script setup>

import axios from "axios";
import {inject, ref} from "vue";
import {useRoute, useRouter} from "vue-router";

const checkToken = inject('checkToken');
const isReposted = ref(false);
const comments = ref([]);
const commentAnswer = ref(null);
const errors = ref([]);
const route = useRoute();
const form = ref({
    title: '',
    content: '',
    body: '',
})
const isShowed = ref(false);

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    stats: {
        type: Object,
        default: () => {},
    }
})

const post = props.post;

function likedPost(post) {
    if (checkToken()) {
        axios.post(`/api/posts/${post.id}/liked_post`).then(response => {
            post.is_liked = response.data.data.is_liked;
            post.likes_count = response.data.data.likes_count;
        })
    }
}

function openRepost() {
    if (! checkPersonalRoute()) {
        isReposted.value = !isReposted.value;
    }
}

function repost(post) {
    if (! checkPersonalRoute()) {
        axios.post(`/api/posts/${post.id}/repost`, {title: form.value.title, content: form.value.content}).then(response => {
            form.value.title = '';
            form.value.content = '';
            post.reposted_count++;
        }).catch(reject => {
            errors.value = reject.response.data.errors;
        })
    }
}

function checkPersonalRoute() {
    return route.name === 'user.personal';
}

function storeComment(post) {
    axios.post(`/api/posts/${post.id}/comment`, {body: form.value.body, parent_id: commentAnswer.value?.id}).then(response => {
        form.value.body = '';
        post.comments_count++;
        comments.value.unshift(response.data.data)
        commentAnswer.value = null;
        isShowed.value = true;
    }).catch(reject => {
        errors.value = reject.response.data.errors;
    })
}

function answer(comment) {
    commentAnswer.value = comment;
}

function getComments(post) {
    axios.get(`/api/posts/${post.id}/comment`).then(response => {
        comments.value = response.data.data;
        isShowed.value = true;
    })
}

</script>

<template>
    <div class="mb-14 border-b-4">
        <h3 class="text-2xl mb-4">Title: {{ post.title }}</h3>
        <RouterLink :to="`/users/${post.user.id}/posts`" class="text-sky-600">Author: {{ post.user.name }}</RouterLink>
        <img v-if="post.image_url" :src="post.image_url" :alt="post.title" class="w-56 block mx-auto mb-4">
        <p class="text-xl mb-4">Content: {{ post.content }}</p>
        <div v-if="post.reposted_post" class="bg-gray-100 p-4 my-4 border border-gray-200">
            <h3 class="text-2xl mb-4">Title: {{ post.reposted_post.title }}</h3>
            <p class="text-xl mb-4">Author: {{ post.reposted_post.user.name }}</p>
            <img v-if="post.reposted_post.image_url" :src="post.reposted_post.image_url" :alt="post.title" class="w-56 block mx-auto mb-4">
            <p class="text-xl mb-4">Content: {{ post.reposted_post.content }}</p>
        </div>
        <div class="pb-4 flex justify-between items-center">
            <div class="flex justify-between">
                <div class="flex items-center mr-5">
                    <svg class="mr-3" @click.prevent="likedPost(post)" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 489.543 489.543" :class="['cursor-pointer w-8', post.is_liked ? 'fill-red-600' : 'hover:fill-red-600  fill-slate-400']"><g><g><path d="M270.024,0c-22.6,0-15,48.3-15,48.3s-48.3,133.2-94.5,168.7c-9.9,10.4-16.1,21.9-20,31.3l0,0l0,0 c-0.9,2.3-1.7,4.5-2.4,6.5c-3.1,6.3-9.7,16-23.8,24.5l46.2,200.9c0,0,71.5,9.3,143.2,7.8c28.7,2.3,59.1,2.5,83.3-2.7 c82.2-17.5,61.6-74.8,61.6-74.8c44.3-33.3,19.1-74.9,19.1-74.9c39.4-41.1,0.7-75.6,0.7-75.6s21.3-33.2-6.2-58.3 c-34.3-31.4-127.4-10.5-127.4-10.5l0,0c-6.5,1.1-13.4,2.5-20.8,4.3c0,0-32.2,15,0-82.7C346.324,15.1,292.624,0,270.024,0z"/><path d="M127.324,465.7l-35-166.3c-2-9.5-11.6-17.3-21.3-17.3h-66.8l-0.1,200.8h109.1C123.024,483,129.324,475.2,127.324,465.7z" /></g></g></svg>
                    <p class="text-xl">{{ post.likes_count }}</p>
                </div>
                <div v-if="! checkPersonalRoute()" class="flex items-center">
                    <svg @click.prevent="openRepost" class="w-8 cursor-pointer mr-3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 426.667 426.667" style="enable-background:new 0 0 426.667 426.667;" xml:space="preserve"><g><g><path d="M352,256c-23.983,0-45.13,11.569-58.801,29.201l-144.438-55.553c0.146-1.897,0.572-3.714,0.572-5.647 c0-3.589-0.568-7.025-1.06-10.479l148.009-68.32c13.693,15.487,33.473,25.466,55.717,25.466c41.167,0,74.667-33.5,74.667-74.667 c0-41.167-33.5-74.667-74.667-74.667S277.333,54.833,277.333,96c0,3.589,0.568,7.025,1.06,10.479l-148.009,68.32 c-13.693-15.487-33.473-25.466-55.717-25.466C33.5,149.333,0,182.833,0,224c0,41.167,33.5,74.667,74.667,74.667 c23.983,0,45.13-11.569,58.801-29.201l144.438,55.553c-0.146,1.897-0.572,3.714-0.572,5.647c0,41.167,33.5,74.667,74.667,74.667 s74.667-33.5,74.667-74.667C426.667,289.5,393.167,256,352,256z" fill="#000000" style="fill: rgb(0, 170, 255);"></path></g></g></svg>
                    <p class="text-xl mr-4">{{ post.reposted_count ?? 0 }}</p>
                </div>
            </div>
            <p class="text-lg">Дата добавления поста: {{ post.create_post }}</p>
        </div>
        <div v-if="! checkPersonalRoute()">
            <form v-if="isReposted" @submit.prevent="repost(post)" class="mb-4">
                <div class="mb-4">
                    <label for="title">Title:</label>
                    <input
                        v-model="form.title"
                        type="text"
                        name="title"
                        id="title"
                        class="ml-1.5 w-64 border-2 border-blue-400 p-1 rounded-lg"
                    >
                </div>
                <p v-if="errors.title" v-for="message in errors.title" class="text-red-500 text-sm italic font-bold mb-3">{{ message }}</p>
                <div class="mb-4">
                    <label for="content">Content:</label>
                    <textarea
                        v-model="form.content"
                        class="ml-1.5 w-64 border-2 border-blue-400 p-1 rounded-lg"
                        rows="1"
                        id="content"
                    ></textarea>
                </div>
                <p v-if="errors.content" v-for="message in errors.content" class="text-red-500 text-sm italic font-bold mb-3">{{ message }}</p>
                <div>
                    <input type="submit" class="block hover:bg-gray-600 hover:text-red-700 border-2 rounded-full bg-sky-400 p-3 w-28 cursor-pointer ml-auto" value="Add repost">
                </div>
            </form>
        </div>
        <div v-if="post.comments_count > 0">
            <p v-if="!isShowed" class="mx-auto mb-3 cursor-pointer w-36" @click.prevent="getComments(post)">Show {{ post.comments_count }} comments</p>
            <p v-if="isShowed" class="mx-auto mb-3 cursor-pointer w-36" @click.prevent="isShowed = false">Close comments</p>
            <div v-if="comments && isShowed">
                <div v-for="comment in comments" class="mb-4">
                    <div class="flex justify-around mb-4">
                        <h3 class="text-xl mr-4">Author: {{ comment.user.name }}</h3>
                        <p class="text-xl text-sky-500 cursor-pointer" @click.prevent="answer(comment)" v-if="! commentAnswer">Answer</p>
                    </div>
                    <p class="text-xl border-b-4 pb-4">Body: <span v-if="comment.answered_for_user" class="text-sky-400">{{ comment.answered_for_user }}, </span>{{ comment.body }}</p>
                </div>
            </div>
        </div>
        <div class="mb-6 flex justify-around" v-if="commentAnswer">
            <h3>Answered for {{ commentAnswer.user.name }}</h3>
            <button @click.prevent="commentAnswer = null">Cancel</button>
        </div>
        <div>
            <form @submit.prevent="storeComment(post)">
                <div class="mb-4">
                    <label for="body">Body:</label>
                    <textarea
                        v-model="form.body"
                        class="ml-1.5 w-64 border-2 border-blue-400 p-1 rounded-lg"
                        rows="1"
                        id="body"
                    ></textarea>
                </div>
                <p v-if="errors.body" v-for="message in errors.body" class="text-red-500 text-sm italic font-bold mb-3">{{ message }}</p>
                <div>
                    <input type="submit" class="block hover:bg-gray-600 hover:text-red-700 border-2 rounded-full bg-sky-400 p-3 w-32 cursor-pointer ml-auto mb-4" value="Add comment">
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
