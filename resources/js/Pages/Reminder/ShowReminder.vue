<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    reminder: Object
});
</script>

<template>
    <Head title="Reminder Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Show Reminder
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-lg rounded-2xl dark:bg-gray-900">
                    <div class="p-8 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h1 class="text-3xl font-bold flex items-center gap-2">
                                <span class=" w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 7.165 6 9.388 6 12v2.159c0 .538-.214 1.055-.595 1.436L4 17h5" />
                                    </svg>
                                </span>
                                Reminder Details
                            </h1>
                            <Link href="/reminders" class="text-sm text-blue-600 hover:underline dark:text-blue-400">Back</Link>
                        </div>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Title</div>
                                    <div class="font-semibold text-lg">{{ props.reminder.title }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                                    <span :class="{
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': props.reminder.status === 'watched',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': props.reminder.status === 'notyetwatched'
                                    }" class="px-3 py-1 rounded-full text-xs font-medium">
                                        {{ props.reminder.status === 'watched' ? 'Watched' : 'Not Yet Watched' }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Remind At</div>
                                    <div class="font-semibold">{{ props.reminder.remind_at }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Video URL</div>
                                    <a :href="`https://www.youtube.com/watch?v=${props.reminder.url}`" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">
                                        Watch Video
                                    </a>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Google Event</div>
                                    <div class="font-mono text-xs">{{ props.reminder.google_event_id }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Created At</div>
                                    <div class="text-xs">{{ props.reminder.created_at }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Updated At</div>
                                    <div class="text-xs">{{ props.reminder.updated_at }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <Link :href="`/reminders/${props.reminder.id}/edit`" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Edit Reminder
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
