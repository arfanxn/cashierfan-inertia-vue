<template>
    <input :ref="(elem) => { if (elem) elements.input = elem }"
        class="w-full px-2 py-1 border border-gray-300 file:text-slate-600 focus:outline-none focus:outline-indigo-900/25 focus:outline-4 focus:outline-offset-0 file:mr-2 file:py-1 lg:file:px-4 file:px-2 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-100/50 hover:file:bg-indigo-300/50"
        type="text" @input="throwEmit">
</template>

<script setup>
import { defineEmits, onMounted, onUnmounted, reactive } from 'vue';
import { tap } from '../Helpers';
const emit = defineEmits(['onInput']);

const elements = reactive({
    input: null,
})
function onKeypress(event) {
    const keyCode = event.keyCode;
    if (keyCode < 48 || keyCode > 57) return event.preventDefault();
}
onMounted(() => {
    if (elements.input.type.toLowerCase() === "number")
        elements.input.addEventListener("keypress", onKeypress)

})
onUnmounted(() => {
    if (elements.input.type.toLowerCase() === "number")
        elements.input.removeEventListener("keypress", onKeypress)
})


function throwEmit(event) {
    const payload = event.target;
    const files = "files" in payload && payload?.files?.length > 0 ? payload.files : null;

    if (files) {  // if the input type is file then we need to send the emit among the files
        emit(`onInput`, {
            payload, value: payload.value, files, file: files[0],
        })
    } else {
        emit(`onInput`, {
            payload, value: payload.value,
        })
    }

}
</script>


