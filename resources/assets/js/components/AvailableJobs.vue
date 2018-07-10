<template>
    <ul class="list-group list-group-flush">
        <li v-if="jobs.length != 0" class="list-group-item" v-for="(job, index) in jobs" :key="index">
            <div class="row">
                <div class="col-md-9">{{ job.title }}</div>
                <div class="col-md-3">
                    <div class="pull-right">
                        <a class="btn btn-sm" :href="`/jobs/${job.id}/apply`" :class="!is_enabled(job) ? 'disabled btn-secondary' : 'btn-primary'">
                            Apply
                        </a>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            encoded_auth: {
                type: String,
            },
            encoded_jobs: {
                type: String
            },
        },

        created() {
            this.auth = typeof this.encoded_auth === 'string'
                ? JSON.parse(this.encoded_auth)
                : null;

            this.jobs = typeof this.encoded_jobs === 'string'
                ? JSON.parse(this.encoded_jobs)
                : [];
        },

        data() {
            return {
                auth: null,
                jobs: []
            }
        },

        methods: {
            is_enabled(job) {
                const auth = this.auth;

                return auth.points >= 2 && $.inArray(job.id, auth.job_ids) == -1;
            }
        }
    }
</script>
