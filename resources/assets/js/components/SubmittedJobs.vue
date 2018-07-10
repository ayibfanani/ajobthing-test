<template>
    <div>
        <alert v-if="is_errors"></alert>
        <ul class="list-group list-group-flush">
            <li v-if="jobs.length != 0" class="list-group-item" v-for="(job, index) in jobs" :key="index">
                <div class="row">
                    <div class="col-md-9">{{ job.title }}</div>
                    <div class="col-md-3">
                        <div class="btn-group pull-right" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-cog"></i> Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <!-- <a class="dropdown-item" :href="`/jobs/${job.id}/apply`">
                                    <i class="fa fa-eye"></i> Detail
                                </a> -->
                                <a class="dropdown-item" href="#" @click.prevent="cancel(job.id)">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <li v-if="jobs.length == 0" class="list-group-item">
                <p class="text-center text-muted">There is no job submitted.</p>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            api_token: {
                type: String,
                default: () => null
            },
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

        methods: {
            cancel(job_id) {
                const vm = this;

                if (confirm('Are you sure?')) {
                    const data = {};

                    axios.defaults.headers.common['Authorization'] = `Bearer ${vm.api_token}`;
                    axios.post(`/api/jobs/${job_id}/cancel`, data)
                        .then(({ data }) => {
                            location.reload();
                        }).catch(({ response }) => {
                            vm.is_errors = true;

                            setTimeout(() => {
                                vm.is_errors = false;
                            }, 5000)
                        });
                }
            }
        }
    }
</script>
