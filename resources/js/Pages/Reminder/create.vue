<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link ,useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps([ 'title', 'url'])

const form = useForm({

  title: props.title || '',
  url: props.url || '',
  remind_at: '', // user selects this
})

const recentSuccess = ref(false);

watch(() => form.isSuccess, (val) => {
  if (val) {
    recentSuccess.value = true;
    setTimeout(() => recentSuccess.value = false, 2000);
  }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Create Reminder
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >

                    <div class="p-6 text-gray-900 dark:text-gray-100">


                        <div>
                            <h1 class="mb-4 text-2xl">Add Reminder</h1>



                            <div v-if="$page.props" class="text-red-500">
                                {{ $page.props.flash.error }}
                            </div>

                             <form @submit.prevent="form.post('/reminders')">
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
                                    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Video URL</label>
                                    <input
                                        v-model="form.url"
                                        type="text"
                                        placeholder="Video URL"
                                        readonly
                                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    <div v-if="form.errors.url" class="mt-1 text-sm text-red-500">{{ form.errors.url }}</div>
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
                                <button
                                    type="submit"
                                    class="px-4 py-2 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    Save Reminder
                                </button>
  <div v-if="form.recentlySuccessful" class="mb-4 font-bold text-green-600">Saved!</div>




  </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
