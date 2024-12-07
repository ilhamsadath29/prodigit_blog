<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

interface Tag {
    id?: number;
    name?: string;
}

interface Category {
    id: number;
    name: string;
}

interface PageProps {
    post?: {
        id: number;
        title: string;
        content: string;
        category_id: number;
        tags: Tag[];
    };
    categories: Category[];
    tags: Tag[];
    auth: {
        user: any;
    };
    [key: string]: any;
}

const { props } = usePage<PageProps>();
const { post, categories, tags } = props;

const form = useForm({
    title: post?.title || '',
    content: post?.content || '',
    category_id: post?.category_id || null,
    tags: (post?.tags ?? []).map((tag: Tag) => tag.id),
    image: null as File | null,
});

const handleImageChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    form.image = input.files ? input.files[0] : null;
};

const handleSubmit = () => {
    if (post) {
        form.put(route('admin.posts.update', post.id));
    } else {
        form.post(route('admin.posts.store'));
    }
};
</script>

<template>
    <Head title="Post" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ post ? 'Edit' : 'Create' }} Post
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="handleSubmit" class="mt-6 space-y-6">
                            <div>
                                <InputLabel for="name" value="Name" />
                                <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title"
                                    required autofocus autocomplete="title" />

                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel for="content" value="Content" />
                                <TextAreaInput id="content" class="mt-1 block w-full" v-model="form.content" required />
                                <InputError class="mt-2" :message="form.errors.content" />
                            </div>

                            <div>
                                <InputLabel for="category" value="Category" />

                                <select id="category" v-model="form.category_id" required
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 block w-full">
                                    <option value="">Select a category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>

                                <!-- <InputError class="mt-2" :message="form.errors.category" /> -->
                            </div>

                            <div>
                                <InputLabel for="tag" value="Tag" />

                                <div class="flex flex-wrap gap-2">
                                    <div v-for="tag in tags" :key="tag.id" class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            :value="tag.id" 
                                            v-model="form.tags" 
                                            class="mr-2" 
                                        />
                                        <span>{{ tag.name }}</span>
                                    </div>
                                </div>
                            </div>


                            <div>
                                <InputLabel for="image" value="Image" />

                                <input
                                    type="file"
                                    id="image"
                                    @change="handleImageChange"
                                    class="border border-gray-300 rounded-lg p-2 w-full"
                                />

                                <InputError class="mt-2" :message="form.errors.image" />
                            </div>

                            <div class="flex justify-end">
                                <PrimaryButton>{{ post ? 'Update' : 'Create' }}</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
