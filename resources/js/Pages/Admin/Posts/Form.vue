<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/components/TextAreaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { Head, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const post = usePage().props.post;
console.log(post);

const form = useForm({
    title: post?.title || '',
    content: post?.content || '',
    img: post?.img_path || null,
});

const handleSubmit = () => {
    if (post) {
        form.put(route('categories.update', post.id));
    } else {
        form.post(route('categories.store'));
    }
};

</script>

<template>
    <Head title="post" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ post ? 'Edit' : 'Create' }} post
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form 
                            @submit.prevent="handleSubmit"
                            class="mt-6 space-y-6"
                        >
                            <div>
                                <InputLabel for="title" value="title" />

                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    autofocus
                                    autocomplete="title"
                                />

                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel for="content" value="Content" />

                                <TextAreaInput
                                    id="content"
                                    class="mt-1 block w-full"
                                    v-model="form.content"
                                />

                                <InputError class="mt-2" :message="form.errors.content" />
                            </div>

                            <div>
                                <InputLabel for="img" value="Image" />

                                <input type="file" id="img" @change="handleFileChange"
                                    class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />

                                <InputError class="mt-2" :message="form.errors.img" />
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