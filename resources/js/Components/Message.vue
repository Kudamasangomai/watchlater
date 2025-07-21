<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    errors: Object,
});

const show = ref(true);
const pageProps = usePage().props;

watch(
    () => pageProps.flash,
    () => {
        show.value = true;
    },
    { deep: true }
);
</script>
<template>
    <div
        x-data="{show: true}"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        v-if="pageProps.flash.success && show"
        class="fixed z-50 p-4 px-10 text-sm text-white bg-green-300 rounded bottom-2 right-2"
    >
        {{ $page.props.flash.success }}
    </div>

    <div
        x-data="{show: true}"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        v-if="pageProps.flash.error && show"
        class="fixed z-50 p-4 px-10 text-sm text-white bg-orange-500 rounded bottom-2 right-2"
    >
        {{ $page.props.flash.error }}
    </div>
</template>
