<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';


interface Category {
  id?: number; 
  name?: string;
}

interface PageProps {
  auth: {
    user: any; 
  };
  category?: Category;
  [key: string]: any;
}

const { props } = usePage<PageProps>();
const category = props.category;

console.log(category);

interface FormData {
  name: string;
}

const form = useForm<FormData>({
  name: category?.name || ''
});

const handleSubmit = () => {
    if (category) {
        form.put(route('admin.categories.update', category.id));
    } else {
        form.post(route('admin.categories.store'));
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
                            enctype="multipart/form-data"
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