<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link ,useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    reminder:Object

});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
              Show reminder            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >

                    <div class="p-6 text-gray-900 dark:text-gray-100">


                        <div>
                            <h1 class="mb-4 text-2xl">Edit  Reminder</h1>



                          {{ reminder }}



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
