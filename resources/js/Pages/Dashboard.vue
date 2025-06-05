<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({

    reminderCount: Number
});


</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Dashboard {{ reminderCount }}
                </h2>
            </div>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
                            <div class="p-4 text-center bg-blue-600 rounded ">
                                <div class="text-gray-700 dark:text-gray-300">Total Reminders</div>
                                <div class="text-2xl font-bold">4</div>

                            </div>
                            <div class="p-4 text-center bg-green-600 rounded ">
                                <div class="text-gray-700 dark:text-gray-300">Reminders This Week</div>
                                <div class="text-2xl font-bold">7</div>

                            </div>
                            <div class="p-4 text-center bg-red-600 rounded ">
                                <div class="text-gray-700 dark:text-gray-300">Overdue Reminders</div>
                                <div class="text-2xl font-bold">8</div>

                            </div>

                                            <div class="p-4 text-center bg-indigo-600 rounded">
                                <div class="text-gray-700 dark:text-gray-300">watched Videos</div>
                                <div class="text-2xl font-bold">5</div>

                            </div>




                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
