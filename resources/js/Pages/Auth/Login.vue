<template>
    <Head title="Login"></Head>

    <GuestLayout>
        <Card class="w-full lg:w-4/12">
            <form
                class="py-2 space-y-4"
                @submit.prevent="form.post(route('users.login'))"
            >
                <div class="text-slate-700">
                    <h1 class="text-xl font-semibold uppercase">
                        Login Account
                    </h1>
                    <span>Sign In to your account</span>
                </div>
                <hr class="border border-1" />

                <div class="flex items-center bg-gray-200 rounded">
                    <font-awesome-icon
                        icon="fas fa-envelope "
                        class="px-2"
                    ></font-awesome-icon>
                    <Input
                        @onInput="({ value }) => (form.email = value)"
                        class="rounded-r"
                        placeholder="Email Address"
                    />
                </div>

                <div class="flex items-center bg-gray-200 rounded">
                    <font-awesome-icon
                        icon="fas fa-lock "
                        class="px-2"
                    ></font-awesome-icon>
                    <Input
                        type="password"
                        @onInput="({ value }) => (form.password = value)"
                        class="rounded-r"
                        placeholder="Password"
                    />
                </div>

                <Alert
                    @onClose="form.clearErrors()"
                    type="error"
                    :message="form.errors.email || form.errors.password"
                />
                <Alert
                    @onClose="$page.props.flash.message = null"
                    type="info"
                    :message="$page.props.flash.message"
                />

                <div class="flex justify-end">
                    <Link
                        :href="route('users.forgot-password-page')"
                        class="text-blue-500 underline"
                        >Forgot Password?
                    </Link>
                </div>

                <!-- :disabled="form.processing" -->
                <Button
                    type="submit"
                    class="w-full py-1 lg:py-1.5 text-center rounded"
                    ><span>Login</span></Button
                >
            </form>
        </Card>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '../../Layouts/GuestLayout.vue';
import Card from '../../Components/Card.vue';
import Alert from '../../Components/Alert.vue';
import Button from '../../Components/Button.vue';
import Input from '../../Components/Input.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';
import { reactive } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    email: '',
    password: ''
});
</script>
