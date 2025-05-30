<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, Link, useForm} from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";

const props = defineProps({ reminders: Array });
const confirmingReminderDeletion = ref(false);
const reminderIdToDelete = ref(null);
const deleteform = useForm({});


const DeleteReminder = (id) => {
    reminderIdToDelete.value = id;
    confirmingReminderDeletion.value = true;
};

const reminderdelete = () => {
    if (!reminderIdToDelete.value) return;
    deleteform.delete(route("reminders.destroy", reminderIdToDelete.value), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => {
    confirmingReminderDeletion.value = false;
};

</script>

<template>
    <Head title="Reminders" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Reminders
                </h2>
                <!-- <Link href="/reminder/create" class="px-4 py-2 ml-4 text-white bg-blue-600 rounded hover:bg-blue-700">Create Reminder</Link> -->
            </div>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Title</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">URL</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Remind At</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="reminder in props.reminders" :key="reminder.id" class="bg-white dark:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ reminder.title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a :href="`https://www.youtube.com/watch?v=${reminder.url}`" target="_blank" rel="noopener" class="text-blue-600 underline hover:text-blue-800">{{ reminder.url }}</a>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ reminder.remind_at }}</td>
                                    <td class="flex gap-2 px-6 py-4 whitespace-nowrap">
                                        <Link :href="`/reminders/${reminder.id}`" class="px-2 py-1 text-white ">View</Link>
                                        <Link :href="`/reminders/${reminder.id}/edit`" class="px-2 py-1 text-white ">Edit</Link>
                                        <button @click="DeleteReminder(reminder.id)" class="px-2 py-1 text-white bg-red-600 rounded hover:bg-red-700">Delete</button>
                                    </td>
                                </tr>
                                <tr v-if="!props.reminders.length">
                                    <td colspan="4" class="py-4 text-center text-gray-500">No reminders found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         <Modal :show="confirmingReminderDeletion" @close="closeModal">
            <div class="p-6 mx-7">
                <h2 class="text-lg font-medium text-black">
                    Are you sure you want to delete this Reminder?
                </h2>

                <div class="flex justify-end mt-6">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': deleteform.processing }"
                        :disabled="deleteform.processing"
                        @click="reminderdelete"
                    >
                        Delete Reminder
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
