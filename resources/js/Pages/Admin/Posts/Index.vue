<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ButtonLink from '@/Components/ButtonLink.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { PropType } from 'vue';

defineProps({
    posts: Array as PropType<{ 
        id: number; 
        title: string; 
        content: string; 
        deleted_at: string; 
    }[]>
});

const form = useForm({});

const deletePost = async (id: number) => {
    try {
        const confirmDelete = window.confirm('Are you sure you want to delete this post?');
        if (confirmDelete) {
            form.delete(route('admin.posts.destroy', id), {
                preserveScroll: true
            });
        }
    } catch (error) {
        console.error('Error deleting post:', error);
        alert('An error occurred while deleting the post. Please try again.');
    }
};

const restorePost = async (id: number) => {
    try {
        const confirmDelete = window.confirm('Are you sure you want to restore this post?');
        if (confirmDelete) {
            form.post(route('admin.posts.restore', id), {
                preserveScroll: true
            });
        }
    } catch (error) {
        console.error('Error restore post:', error);
        alert('An error occurred while restore the post. Please try again.');
    }
};

</script>

<template>
    <Head title="Posts" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Posts
                </h2>
                
                <ButtonLink 
                    :href="route('admin.posts.create')" >
                    Create post
                </ButtonLink>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full border-collapse bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Content</th>
                                    <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Tag</th> -->
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b">{{ post.title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b">{{ post.category.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b">{{ post.content.substring(0, 50) }}...</td>
                                    <!-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b">
                                        <span v-for="tag in post.tags" :key="tag.id"
                                            class="bg-gray-200 text-gray-800 px-2 py-1 rounded mr-2">
                                            {{ tag.name }}
                                        </span>    
                                    </td> -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b">
                                        <ButtonLink                    
                                            :href="route('admin.posts.edit', post.id)"
                                        >
                                            <font-awesome-icon :icon="['fas', 'pencil-alt']" />
                                        </ButtonLink>

                                        <DangerButton
                                            v-if="post.deleted_at == null"
                                            class="ms-3"
                                            :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing"
                                            @click="deletePost(post.id)"
                                        >
                                            <font-awesome-icon :icon="['fas', 'trash']" />
                                        </DangerButton>

                                        <DangerButton
                                            v-if="post.deleted_at != null"
                                            class="ms-3"
                                            :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing"
                                            @click="restorePost(post.id)"
                                        >
                                            <font-awesome-icon :icon="['fas', 'sync-alt']" />
                                        </DangerButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
