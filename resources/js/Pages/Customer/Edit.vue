<template>

    <Head>
        <title>Edit Customer</title>
        <meta name="Edit Customer" content="Edit Customer" />
    </Head>

    <AuthLayout>
        <Card>
            <template v-slot:title>
                <font-awesome-icon class=" fas fa-user-circle" />
                <h1 class="text-lg font-semibold">EDIT CUSTOMER</h1>
            </template>

            <main class="grid grid-cols-1 gap-4 lg:grid-cols-2">

                <div class="space-y-1 lg:col-span-1">
                    <Input :value="form.name" @onInput="({ value }) => (form.name = value)" placeholder="Fullname"
                        class="rounded placeholder:text-slate-700 " />
                    <Alert :message="form.errors.name" />
                </div>

                <div class="space-y-1 lg:col-span-1">
                    <Input :value="form.phone_number" @onInput="({ value }) => (form.phone_number = value)"
                        placeholder="Phone Number (62XXXXxxxxXXXX)" class="rounded placeholder:text-slate-700 " />
                    <Alert :message="form.errors.phone_number" />
                </div>

                <div class="space-y-1 lg:col-span-2 ">
                    <Textarea :value="form.address" @onChange="({ value }) => (form.address = value)"
                        placeholder="Address" class="rounded placeholder:text-slate-700 " />
                    <Alert :message="form.errors.address" />
                </div>

            </main>

            <div class="my-4 space-x-1">
                <Button @onClick="form.patch(route(`customers.update`, props.customer.id))"
                    class="px-2 py-1 rounded">SAVE</Button>
                <Button @onClick="form.reset()"
                    class="px-2 py-1 text-white bg-yellow-500 rounded focus:bg-yellow-500/90 hover:bg-yellow-500/90 focus:ring-4 focus:ring-yellow-500/25">RESET</Button>
            </div>
        </Card>
    </AuthLayout>
</template>

<script setup>
import AuthLayout from "../../Layouts/AuthLayout.vue";
import Alert from "../../Components/Alert.vue";
import Card from "../../Components/Card.vue";
import Button from "../../Components/Button.vue";
import Textarea from "../../Components/Textarea.vue";
import Input from "../../Components/Input.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";

const props = defineProps(['customer']);

const form = useForm({
    name: props.customer.name,
    phone_number: props.customer.phone_number,
    address: props.customer.address,
})

</script>