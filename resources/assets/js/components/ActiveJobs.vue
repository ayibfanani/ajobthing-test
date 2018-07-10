<template>
    <div>
        <alert v-if="is_errors"></alert>
        <ul class="list-group list-group-flush">
            <li v-if="jobs.length != 0" class="list-group-item" v-for="(job, index) in jobs" :key="index">
                <div class="row">
                    <div class="col-md-9">{{ job.title }}</div>
                    <div class="col-md-3">
                        <div class="text-success pull-right" v-if="job.pivot.status == 'completed'">
                            <i class="fa fa-check"></i> Completed
                        </div>
                        <div class="text-warning pull-right" v-else>
                            <i class="fa fa-info-circle"></i> On Progress
                        </div>
                    </div>
                </div>
            </li>

            <li v-if="jobs.length == 0" class="list-group-item">
                <p class="text-center text-muted">There is no job active.</p>
            </li>
        </ul>
    </div>
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
                is_errors: false,
                auth: null,
                jobs: []
            }
        },
    }
</script>
