<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link ,useForm } from "@inertiajs/vue3";


const props = defineProps({
    reminder:Object

});

const form = useForm({

  title: props.reminder.title,
  url:props.reminder.url,
  remind_at:props.reminder.remind_at // user selects this
})


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                <!-- Edit Reminder -->
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >

                    <div class="p-6 text-gray-900 dark:text-gray-100">


                        <div>
                            <h1 class="mb-4 text-2xl">Edit  Reminder</h1>


                            <div v-if="$page.props" class="text-red-500">
                                {{ $page.props.flash.error }}
                            </div>

                             <form
                              @submit.prevent="form.put(route('reminders.update',props.reminder?.id))"
                              >
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Title</label>
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        placeholder="Title"
                                        readonly
                                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    <div v-if="form.errors.title" class="mt-1 text-sm text-red-500">{{ form.errors.title }}</div>
                                </div>
                            
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Remind At</label>
                                    <input
                                        v-model="form.remind_at"
                                        type="datetime-local"
                                        class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    <div v-if="form.errors.remind_at" class="mt-1 text-sm text-red-500">{{ form.errors.remind_at }}</div>
                                </div>
                                <div class="flex w-full ">
                                <button
                                    type="submit"
                                    class="px-4 py-2 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"                                >
                                    Update Reminder
                                </button>
  <p v-if="form.recentlySuccessful" class="ml-4 font-bold text-green-600">Updated!</p></div>




  </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
