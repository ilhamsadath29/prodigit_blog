<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { defineProps, computed, ref, watch, PropType } from 'vue';

const props = defineProps({
    posts: {
        type: Array as PropType<{
            id: number;
            title: string;
            content: string;
            image?: string;
            category: { name: string };
            tags: { name: string }[];
        }[]>,
        required: true,
    },
    tags: Array as PropType<{ id: number; name: string }[]>,
    categories: Array as PropType<{ id: number; name: string }[]>,
});

const currentPage = ref(1);
const postsPerPage = 2;

// Initialize posts with a fallback
const posts = ref([]);

interface FormData {
    author: string,
    comment: string

}

const newComment = useForm<FormData>({
    author: '',
    comment: '',
});

watch(
    () => props.posts,
    (newPosts) => {
        if (Array.isArray(newPosts)) {
            posts.value = newPosts;
        }
    },
    { immediate: true }
);

const paginatedPosts = computed(() => {
    if (!posts.value || posts.value.length === 0) return [];
    const start = (currentPage.value - 1) * postsPerPage;
    return posts.value.slice(start, start + postsPerPage);
});

const totalPages = computed(() => {
    if (!posts.value || posts.value.length === 0) return 0;
    return Math.ceil(posts.value.length / postsPerPage);
});

const selectedPost = ref(null);

selectedPost.value = props.posts.length > 0 ? props.posts[0] : null;

const loadPost = (post) => {
    selectedPost.value = post;
}

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
}

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
}
</script>


<template>
    <Head title="Blogs" />

    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header Section -->
        <div class="relative">
            <img src="/images/homebanner.jpg" alt="Header Image" class="w-full h-64 object-cover" />
            <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center px-6">
                <p class="text-sm text-gray-300 mb-2">Published in <span class="font-semibold">Prodigit</span></p>
                <h1 class="text-3xl font-bold text-white leading-tight">
                    Welcome to Prodigit, your trusted companion on the journey through the ever-evolving digital landscape.
                </h1>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-wrap md:flex-nowrap">
            <!-- Sidebar -->
            <aside class="w-full md:w-1/4 bg-gray-50 p-4">
                <!-- Latest Blogs Section -->
                <div class="my-6">
                    <h3 class="text-md font-semibold">LATEST POSTS</h3>

                    <div v-for="(post, index) in paginatedPosts" :key="post.id" class="mb-4 cursor-pointer"  @click="loadPost(post)">
                        <div class="flex items-center space-x-4">
                            <img :src="post.image ? post.image : 'https://via.placeholder.com/60'" alt="News image"
                                class="w-16 h-16 object-cover rounded" />
                            <div>
                                <h4 class="text-md font-bold">{{ post.title.substring(0, 25) }}...</h4>
                                <p class="text-sm text-gray-700 truncate">{{ post.content.substring(0, 20)  }}...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="flex justify-between items-center mt-4">
                        <button @click="prevPage" :disabled="currentPage === 1" 
                            class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 disabled:opacity-50">
                            Previous
                        </button>
                        <button @click="nextPage" :disabled="currentPage === totalPages" 
                            class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 disabled:opacity-50">
                            Next
                        </button>
                    </div>
                </div>

                <!-- Categories Section -->
                <div class="my-6">
                    <h3 class="text-md font-semibold">Category List</h3>
                    <div class="flex flex-wrap space-y-0.5">
                        <a v-for="category in categories" :key="category.id"
                            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">
                            {{ category.name }}
                        </a>
                    </div>
                </div>

                <!-- Tags Section -->
                <div class="my-6">
                    <h3 class="text-md font-semibold">Tags List</h3>
                    <div class="flex flex-wrap space-y-0.5">
                        <a v-for="tag in tags" :key="tag.id"
                            class="bg-gray-800 text-gray-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">
                            {{ tag.name }}
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Blog Content -->
            <main class="w-full md:w-3/4 p-6">
                <div v-if="selectedPost" class="flex-1 bg-white p-6 overflow-y-auto">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="text-sm">
                            <p class="text-gray-700 font-medium">
                                By Jese Leos - 
                                <a 
                                    class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">
                                    {{ selectedPost.category.name }}
                                </a>
                            </p>
                            <p class="text-gray-500">August 3, 2022, 2:20am EDT</p>
                            <p class="flex">
                                <a v-for="tag in selectedPost.tags" :key="tag.id"
                                    class="bg-gray-800 text-gray-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">
                                    {{ tag.name }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold">{{ selectedPost.title }}</h1>

                    <p class="text-gray-700">
                        {{ selectedPost.content }}
                    </p>

                    <div class="mt-6">
                        <h2 class="text-xl font-bold mb-4">Comments</h2>
                        <div v-if="selectedPost.comments.length > 0">
                            <div v-for="comment in selectedPost.comments" :key="comment.id" class="mb-4">
                                <p class="text-sm font-semibold text-gray-800">{{ comment.author }}</p>
                                <p class="text-gray-600">{{ comment.comment }}</p>
                                <p class="text-xs text-gray-500">{{ comment.created_at }}</p>
                            </div>
                        </div>
                        <p v-else class="text-gray-500">No comments yet.</p>
                    </div>

                    <!-- New comment form -->
                    <div class="mt-6">
                        <h2 class="text-xl font-bold mb-4">Add a Comment</h2>
                        <form @submit.prevent="newComment.post(route('comments.store', selectedPost.id))" class="space-y-4">
                            <div>
                                <label for="author" class="block text-sm font-medium text-gray-700">Your Name</label>
                                <input
                                    id="author"
                                    v-model="newComment.author"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    required
                                />
                            </div>
                            <div>
                                <label for="comment" class="block text-sm font-medium text-gray-700">Your Comment</label>
                                <textarea
                                    id="comment"
                                    v-model="newComment.comment"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    rows="4"
                                    required
                                ></textarea>
                            </div>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600"
                            >
                                Post Comment
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer Section -->
        <div class="p-6 flex items-center justify-between border-t border-gray-200">
            <div class="flex space-x-3 text-gray-500"></div>
            <button class="text-blue-600 font-semibold hover:underline">Subscribe</button>
        </div>
    </div>
</template>
