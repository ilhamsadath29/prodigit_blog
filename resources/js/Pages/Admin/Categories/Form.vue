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

const category = usePage().props.category;
console.log(category);

const form = useForm({
    name: category?.name || '',
    content: category?.content || '',
    img: category?.img_path || null,
});

const handleSubmit = () => {
    if (category) {
        form.put(route('categories.update', category.id));
    } else {
        form.post(route('categories.store'));
    }
};

</script>

<template>
    <Head title="Category" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ category ? 'Edit' : 'Create' }} Category
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
                                <InputLabel for="name" value="Name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
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
                                <PrimaryButton>{{ category ? 'Update' : 'Create' }}</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>