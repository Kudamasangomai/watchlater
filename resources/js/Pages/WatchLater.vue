<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    youtubeData: Array,
});
</script>

<template>
    <Head title="WatchLater" />

    <AuthenticatedLayout>
      

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <!-- <div class="p-6 text-gray-900 dark:text-gray-100">
                        You're logged in!

                        {{ $page.props.auth.user.name }}
                    </div> -->
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- {{ youtubeData }} -->

                        <div>
                            <h1 class="mb-4 text-2xl">MyWatchLater Videos</h1>

                            <div v-if="$page.props" class="text-red-500">
                                {{ $page.props.flash.error }}
                            </div>
                            <div
                                v-for="video in youtubeData"
                                :key="video.videoId"
                                class="inline-block w-full px-2 mb-10 align-top sm:w-1/2 md:w-1/3 lg:w-1/3"
                            >

                                <a
                                    :href="`https://www.youtube.com/watch?v=${video.snippet.resourceId.videoId}`"
                                    target="_blank"
                                >
                                    <img
                                        :src="
                                            video.snippet.thumbnails.maxres
                                                ?.url ||
                                            video.snippet.thumbnails.high?.url
                                        "
                                        :alt="ok"
                                        class="object-cover w-full h-48 rounded"
                                    />
                                </a>

                                <h2 class="mt-2 font-semibold text-l">
                                    {{ video.snippet.title }}


                                </h2>
                                <p class="text-sm text-gray-500">


                                    <Link
                                         :href="`/reminders/create?title=${encodeURIComponent(video.snippet.title)}&url=${encodeURIComponent(video.snippet.resourceId.videoId)}`"

                                        class="px-3 py-1 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700"
                                        as="button"
                                    >
                                        Add Reminder
                                    </Link>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
