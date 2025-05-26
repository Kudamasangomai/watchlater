<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

const props = defineProps({ reminders: Array });

const handleDelete = (id) => {
    if (confirm("Are you sure you want to delete this reminder?")) {
        router.delete(`/reminders/${id}`);
    }
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
                                        <button @click="handleDelete(reminder.id)" class="px-2 py-1 text-white bg-red-600 rounded hover:bg-red-700">Delete</button>
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
    </AuthenticatedLayout>
</template>
