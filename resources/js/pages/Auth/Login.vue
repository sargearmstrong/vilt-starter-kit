<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { LogIn } from '@lucide/vue';

import Alert from '@/components/ui/alert/Alert.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Sign In" />

    <GuestLayout>
        <Card>
            <CardHeader>
                <CardTitle class="flex items-center gap-2">
                    <LogIn class="h-5 w-5 text-blue-600" />
                    Sign in
                </CardTitle>
            </CardHeader>
            <CardContent>
                <Alert
                    v-if="form.errors.email"
                    class="mb-5 border-red-200 bg-red-50 text-red-900"
                >
                    {{ form.errors.email }}
                </Alert>

                <form class="space-y-5" @submit.prevent="submit">
                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            autocomplete="email"
                            autofocus
                            type="email"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="password">Password</Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            autocomplete="current-password"
                            type="password"
                        />
                    </div>

                    <label
                        class="flex items-center gap-2 text-sm text-slate-700"
                    >
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600"
                        />
                        Remember me
                    </label>

                    <Button
                        class="w-full"
                        type="submit"
                        :disabled="form.processing"
                    >
                        Sign in
                    </Button>
                </form>
            </CardContent>
        </Card>
    </GuestLayout>
</template>
