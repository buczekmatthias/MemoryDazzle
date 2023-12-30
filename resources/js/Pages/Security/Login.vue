<template>
    <Head title="Login" />
    <SecurityLayout>
        <form
            @submit.prevent="form.post($page.props.login_route)"
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
                label="Password"
                placeholder="**********"
                id="password"
                type="password"
                v-model="form.password"
                :errors="form.errors.password"
            />
            <div class="flex justify-between">
                <InputCheckbox v-model="form.remember_me"
                    >Stay signed in</InputCheckbox
                >
                <Link
                    :href="$page.props.reset_password"
                    class="self-end text-indigo-700 font-semibold duration-150 hover:-translate-x-1"
                    >Forgot password?</Link
                >
            </div>
            <ButtonComponent>Sign in</ButtonComponent>
        </form>
    </SecurityLayout>
</template>

<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import SecurityLayout from "../../Layouts/SecurityLayout.vue";
import InputBox from "../../Components/InputBox.vue";
import InputCheckbox from "../../Components/InputCheckbox.vue";
import ButtonComponent from "../../Components/ButtonComponent.vue";

const form = useForm({
    username: null,
    password: null,
    remember_me: false,
});
</script>
