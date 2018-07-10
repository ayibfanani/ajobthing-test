<template>
    <div class="alert alert-primary" role="alert">
        Your account is <strong>Basic</strong> plan, you want to hire someone? <a href="" class="alert-link" @click.prevent="upgrade">Upgrade</a> your account to Client.
    </div>
</template>

<script>
    export default {
        methods: {
            upgrade(e) {
                if (confirm('Are you sure?')) {
                    window.location.href = `/upgrade`;
                }
            }
        }
    }
</script>
