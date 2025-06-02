<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, Link, useForm ,router} from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";

const props = defineProps({
    youtubeData: Array,
});

const confirmingVideoDeletion = ref(false);
const videoIdToDelete = ref(null);
const deleteform = useForm({});


const DeleteVideo = (playlistitemid) => {
    videoIdToDelete.value = playlistitemid;
    confirmingVideoDeletion.value = true;
};

const VideoDelete = () => {
    if (!videoIdToDelete.value) return;
    deleteform.delete(route('watchlater.destroy', videoIdToDelete.value), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
});
};

const closeModal = () => {
    confirmingVideoDeletion.value = false;
};

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

                            <p>  <div v-if="$page.props.flash.success" class="text-green-500">
                                {{ $page.props.flash.success }}
                            </div></p>

                            <div v-if="$page.props.flash.error " class="text-red-500">
                                {{ $page.props.flash.error }}
                            </div>
                            <div v-if="!youtubeData || youtubeData.length === 0" class="mb-6 text-gray-500">
                                No videos found .
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
    <div class="flex mt-2">
                                    <Link
                                        :href="`/reminders/create?title=${encodeURIComponent(video.snippet.title)}
                                        &url=${encodeURIComponent(video.snippet.resourceId.videoId)}
                                        &id=${encodeURIComponent(video.id)}`"
                                        class="px-1 py-1 text-white bg-blue-600 rounded hover:bg-blue-700 min-w-[120px] text-center mr-2"
                                        as="button"
                                    >
                                        Add Reminder
                                    </Link>
                                    <button
                                        @click="DeleteVideo(video.id, video.snippet.playlistId)"
                                        class="px-1  text-white bg-red-600 rounded hover:bg-red-700 min-w-[80px] text-center"
                                    >
                                        Remove
                                    </button>

                                </div>
                                <h2 class="mt-2 font-semibold text-l">
                                    {{ video.snippet.title }}
                                </h2>

                                <!-- <p class="mt-2 break-words whitespace-pre-line">
{{ video.snippet.resourceId.videoId }}..

                                  {{ video.id }}....<br/>

                                  {{ video.snippet.playlistId }}
                                </p> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <Modal :show="confirmingVideoDeletion" @close="closeModal">
            <div class="p-6 mx-3">
                <h2 class="text-lg font-medium text-black">
                    Are you sure you want to delete this Video?
                </h2>
                <p class="font-medium text-red-500 text-m">
                    This will remove this video from your youtube watchlist
                    and if a reminder is set it will be removed to
                </p>

                <div class="flex mt-6">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': deleteform.processing }"
                        :disabled="deleteform.processing"
                        @click="VideoDelete"
                    >
                        Delete
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
