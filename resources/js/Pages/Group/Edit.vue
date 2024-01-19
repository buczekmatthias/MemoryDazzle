<template>
    <AppLayout>
        <div class="flex flex-col gap-4 p-3 w-full max-w-6xl mx-auto">
            <p class="text-3xl font-semibold">Edit group</p>
            <div class="flex flex-col gap-4 bg-white rounded-xl shadow-md p-3">
                <form
                    @submit.prevent="handleFormSubmit"
                    class="flex flex-col gap-4 bg-white rounded-xl shadow-md p-3"
                >
                    <InputBox
                        label="Group id"
                        id="id"
                        :placeholder="group.id"
                        :readonly="true"
                    />
                    <div class="relative grid grid-cols-[1fr_5fr] gap-2">
                        <p
                            class="border border-solid border-gray-300 rounded-md p-2 outline-none focus:border-gray-500 cursor-pointer flex items-center justify-center text-2xl leading-[0]"
                            @click="showPicker = !showPicker"
                        >
                            {{ form.icon }}
                        </p>
                        <EmojiPicker
                            :lazy="true"
                            class="picker absolute top-[115%]"
                            :native="true"
                            :disable-skin-tones="true"
                            :hide-group-names="true"
                            v-if="showPicker"
                            @select="handleIconPick"
                        />
                        <InputBox
                            label="Name"
                            id="name"
                            :value="group.name"
                            v-model="form.name"
                        />
                    </div>
                    <ButtonComponent>Update</ButtonComponent>
                    <div
                        class="border-t border-solid border-t-gray-300 mt-3 pt-3 flex flex-col gap-2"
                    >
                        <p class="text-3xl font-semibold">Delete group</p>
                        <p class="text-sm text-gray-500">
                            This process can't be undone. Be sure if you want to
                            do this action.
                        </p>
                        <ButtonComponent
                            @click="handleDeleteGroupButton"
                            type="button"
                            classes="bg-red-600 w-full hover:bg-red-700 mt-3"
                        >
                            Delete
                        </ButtonComponent>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import ButtonComponent from "../../Components/ButtonComponent.vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import InputBox from "../../components/InputBox.vue";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";

const props = defineProps({
    group: Object,
});

const form = useForm({
    icon: props.group.icon,
    name: props.group.name,
});

const showPicker = ref(false);

const handleDeleteGroupButton = () => {
    router.delete(`/groups/${props.group.id}/delete`);
};

const handleIconPick = (e) => {
    showPicker.value = false;
    form.icon = e["i"];
};

const handleFormSubmit = () => {
    form.post(`/groups/${props.group.id}/edit`, {
        onSuccess: () => {
            createForm.reset();
        },
    });
};
</script>
