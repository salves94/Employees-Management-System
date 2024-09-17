<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import AnchorLink from '@/Components/AnchorLink.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import ResetButton from '@/Components/ResetButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Heading from '@/Components/Heading.vue';
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: null,
  email: null,
  phone: null,
});

const submit = () => {
    form.post(route('departments.store'), {
        onFinish: () => form.reset(),
        preserveScroll: (page) => Object.keys(page.props.errors).length
    });
};

const resetForm = () => {
    form.clearErrors();
    form.reset();
};

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <Heading>Create New Department</Heading>
        </template>
        
        <div v-show="form.hasErrors">
            <div class="text-red-600 font-medium"> Please, make the following corrections</div>
        </div>
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name"></InputLabel>
                <TextInput
                type="text"
                id="name"
                class="mt-1 block w-1/2"
                v-model="form.name"
                required
                autofocus
                ></TextInput>
                <InputError :message="form.errors.name"></InputError>
            </div>
            <div class="mt-4">
                <InputLabel for="email" value="Email"></InputLabel>
                <TextInput
                type="email"
                id="email"
                class="mt-1 block w-1/2"
                v-model="form.email"
                ></TextInput>
                <InputError :message="form.errors.email"></InputError>
            </div>
            <div class="mt-4">
                <InputLabel for="phone" value="Phone"></InputLabel>
                <TextInput
                type="text"
                id="phone"
                class="mt-1 block w-1/2"
                v-model="form.phone"
                ></TextInput>
                <InputError :message="form.errors.phone"></InputError>
            </div>
            <div class="flex items-center justify-end mt-4">
                <ResetButton @click="resetForm">Reset</ResetButton>
                <PrimaryButton :class="{'opacity-25' : form.processing}" :disabled="form.processing">Create</PrimaryButton>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
