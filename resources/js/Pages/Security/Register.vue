<template>
    <Head title="Register" />
    <SecurityLayout>
        <form
            @submit.prevent="form.post($page.props.register_route)"
            class="p-4 flex flex-col gap-5"
        >
            <InputBox
                label="Username"
                placeholder="example123"
                id="username"
                v-model="form.username"
                :errors="form.errors.username"
            />
            <InputBox
                label="Email"
                placeholder="example@test.com"
                id="email"
                type="email"
                v-model="form.email"
                :errors="form.errors.email"
            />
            <InputBox
                label="Password"
                placeholder="**********"
                id="password"
                type="password"
                v-model="form.password"
                :errors="form.errors.password"
            />
            <InputBox
                label="Displayname"
                placeholder="example123"
                id="displayname"
                v-model="form.displayname"
                :errors="form.errors.displayname"
                :required="false"
            />
            <InputFile
                label="Select avatar image"
                accepted="image/jpeg,image/svg,image/png"
                v-model="form.avatar"
                :errors="form.errors.avatar"
            />
            <ButtonComponent :disabled="form.processing"
                >Create account</ButtonComponent
            >
        </form>
    </SecurityLayout>
</template>

<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import SecurityLayout from "../../Layouts/SecurityLayout.vue";
import InputBox from "../../Components/InputBox.vue";
import ButtonComponent from "../../Components/ButtonComponent.vue";
import InputFile from "../../Components/InputFile.vue";

const form = useForm({
    username: null,
    email: null,
    password: null,
    displayname: null,
    avatar: null,
});
</script>
