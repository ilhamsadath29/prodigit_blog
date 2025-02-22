<script setup lang="ts">
import ButtonLink from '@/Components/ButtonLink.vue';
import DangerButton from '@/Components/DangerButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { PropType } from 'vue';

defineProps({
    categories: Array as PropType<{ 
        id: number; 
        name: string; 
    }[]>
});

const form = useForm({});

const deleteCategory = async (id: number) => {
    try {
        const confirmDelete = window.confirm('Are you sure you want to delete this category?');
        if (confirmDelete) {
            form.delete(route('admin.categories.destroy', id), {
                preserveScroll: true
            });
        }
    } catch (error) {
        console.error('Error deleting category:', error);
        alert('An error occurred while deleting the category. Please try again.');
    }
};
</script>

<template>
    <Head title="Categories" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Categories
                </h2>
                
                <ButtonLink :href="route('admin.categories.create')" >Create Category</ButtonLink>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full border-collapse bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b">{{ category.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b">
                                        <ButtonLink :href="route('admin.categories.edit', category.id)">
                                            <font-awesome-icon :icon="['fas', 'pencil-alt']" />
                                        </ButtonLink>

                                        <DangerButton
                                            class="ms-3"
                                            :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing"
                                            @click="deleteCategory(category.id)"
                                        >
                                            <font-awesome-icon :icon="['fas', 'trash']" />
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
