<script setup>

defineProps({
    stats: {
        type: Object,
        required: true,
    }
})

</script>

<template>

    <div class="flex justify-between mb-8" >
        <div>
            Subscribers {{ stats.subscribers_count }}
        </div>
        <div>
            Followings {{ stats.followings_count }}
        </div>
        <div>
            Likes {{ stats.likes_count }}
        </div>
        <div>
            Posts {{ stats.posts_count }}
        </div>
    </div>

</template>

<style scoped>

</style>
