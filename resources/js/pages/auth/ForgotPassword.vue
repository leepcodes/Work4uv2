<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    email: '',
});

const submitted = ref(false);

// function submit() {
//     form.post(route('password.email'), {
//         onSuccess: () => {
//             submitted.value = true;
//         },
//     });
// }
</script>

<template>
    <Head title="Forgot Password" />

    <div class="flex min-h-screen items-center justify-center bg-background px-4">
        <div class="w-full max-w-md space-y-6">
            <div class="space-y-2 text-center">
                <h1 class="text-2xl font-semibold tracking-tight text-foreground">
                    Forgot your password?
                </h1>
                <p class="text-sm text-muted-foreground">
                    Enter your email and we'll send you a reset link.
                </p>
            </div>

            <div v-if="submitted" class="rounded-lg border border-border bg-muted px-4 py-3 text-sm text-foreground">
                If an account with that email exists, a password reset link has been sent.
            </div>

            <form v-else class="space-y-4" @submit.prevent="">
                <div class="space-y-1">
                    <label for="email" class="text-sm font-medium text-foreground">
                        Email address
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="you@example.com"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground shadow-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                    />
                    <p v-if="form.errors.email" class="text-xs text-destructive">
                        {{ form.errors.email }}
                    </p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:opacity-90 disabled:opacity-50"
                >
                    {{ form.processing ? 'Sending...' : 'Send Reset Link' }}
                </button>
            </form>

            <p class="text-center text-sm text-muted-foreground">
                Remember your password?
                <!-- <a :href="route('login')" class="font-medium text-foreground underline underline-offset-4 hover:opacity-80">
                    Back to login
                </a> -->
            </p>
        </div>
    </div>
</template>