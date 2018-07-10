<template>
    <ul class="list-group list-group-flush">
        <li v-if="jobs.length != 0" class="list-group-item" v-for="(job, index) in jobs" :key="index" style="background: transparent;">
            <div class="row">
                <div class="col-md-9">{{ job.title }}</div>
                <div class="col-md-3">
                    <div class="btn-group pull-right" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cog"></i> Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" :href="`/jobs/${job.id}/applier`">
                                <i class="fa fa-users"></i> Freelancer Submit
                            </a>
                            <!-- <a class="dropdown-item" :href="`/jobs/${job.id}/edit`">
                                <i class="fa fa-eye"></i> Edit
                            </a> -->
                            <!-- <a class="dropdown-item" href="#">
                                <i class="fa fa-trash"></i> Remove
                            </a> -->
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <li v-if="jobs.length == 0" class="list-group-item">
            <p class="text-center text-muted">There is no job posted.</p>
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
        }
    }
</script>
