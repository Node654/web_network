<script setup>

import {reactive, ref} from "vue";

    const form = reactive({
        title: '',
        content: '',
        image: null
    })

    const file = ref(null);
    const image = ref(null);

    function addPost() {
        const imageId = image.value ? image.value.id : null;

        axios.post('/api/posts', {title: form.title, content: form.content, image_id: imageId}).then(response => {
            console.log(response);
        }).catch(reject => {

        })
    }

    function selectFile() {
        file.value.click();
    }

    function storeImage(e) {
        const file = e.target.files[0];
        const formData = new FormData();
        formData.append('image', file);
        axios.post('api/posts/images', formData).then(response => {
            image.value = response.data.data;
        });
    }

    function removeImageInput() {
        image.value = null;
    }

</script>

<template>

<div class="w-96 mx-auto text-center">
    <h1 class="mb-4">Personal</h1>
    <form @submit.prevent="addPost">
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
        <div class="mb-4">
            <label for="content">Content:</label>
            <textarea
                v-model="form.content"
                class="ml-1.5 w-64 border-2 border-blue-400 p-1 rounded-lg"
                rows="1"
                id="content"
            ></textarea>
        </div>
        <div class="flex items-center pl-4 pr-4 mb-3">
            <div>
                <input @change="storeImage" type="file" class="hidden" ref="file">
                <input @click.prevent="selectFile" type="submit" class="hover:bg-gray-600 hover:text-red-700 border-2 rounded-full bg-sky-400 p-3 w-28 cursor-pointer" value="Image">
            </div>
            <div class="ml-4">
                <input @click.prevent="removeImageInput" type="submit" class="hover:bg-gray-600 hover:text-red-700 border-2 rounded-full bg-sky-400 p-3 w-36 cursor-pointer" value="Remove image">
            </div>
        </div>
        <div v-if="image" class="mb-2">
            <img :src="image.url" alt="preview">
        </div>
        <div>
            <input type="submit" class="block hover:bg-gray-600 hover:text-red-700 border-2 rounded-full bg-sky-400 p-3 w-28 cursor-pointer ml-auto" value="Add">
        </div>
    </form>
</div>

</template>

<style scoped>

</style>
