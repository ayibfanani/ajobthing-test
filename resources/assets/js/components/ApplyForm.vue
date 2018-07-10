<template>
    <div>
        <alert v-if="is_errors"></alert>
        <form @submit.prevent="apply" v-if="auth !== null">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <h4>{{ job.title }}</h4>
                    </div>

                    <hr/>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <i class="fa fa-user"></i> {{ `${job.user.firstname} ${job.user.lastname}` }}
                            </div>
                            <div class="pull-right text-danger">
                                <i class="fa fa-dollar"></i> {{ job.budget }}
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>

                    <div class="form-group">
                        <p>{{ job.body }}</p>
                    </div>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="card-header">Your Bid</div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Amount</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">$</span>
                                </div>

                                <input type="number" name="fr_budget" v-model="data.fr_budget" class="form-control" placeholder="Your budget"/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label>Completed At</label>
                            <input type="date" name="completed_at" v-model="data.completed_at" class="form-control" placeholder="Completed At"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Messages</label>
                        <textarea name="messages" class="form-control" v-model="data.messages" cols="30" rows="10" placeholder="Write a message to job poster..."></textarea>
                    </div>
                </div>
            </div>

            <br>
            <p class="help-block text-muted">
                Your point will reduced <strong class="text-danger">2</strong> Points,
                so it will be <strong class="text-danger">{{ reduced_points }}</strong> Points,
                if you apply to this job.
            </p>

            <br>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button class="btn btn-primary btn-lg" type="button" :disabled="is_applied" v-if="is_applied">Applied</button>
                <button class="btn btn-primary btn-lg" type="submit" v-else>Apply</button>
                <a href="/home" class="btn btn-default btn-lg">Back</a>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            encoded_auth: {
                type: String
            }, encoded_job: {
                type: String,
            }, api_token: {
                type: String
            }, is_applied_string: {
                type: String,
                default: () => 'false'
            }
        },

        created() {
            if (this.is_applied_string === 'true') {
                this.is_applied = true;
            }

            if (typeof this.encoded_auth === 'string' && JSON.parse(this.encoded_auth).length != 0) {
                this.auth = JSON.parse(this.encoded_auth);
            }

            if (typeof this.encoded_job === 'string' && JSON.parse(this.encoded_job).length != 0) {
                this.job = JSON.parse(this.encoded_job);
            }
        },

        computed: {
            reduced_points() {
                return parseInt(this.auth.points) - 2;
            }
        },

        data() {
            return {
                is_applied: false,
                loading: false,
                auth: null,
                is_errors: false,
                data: {
                    job_id: '',
                    fr_budget: 0,
                    completed_at: '',
                    messages: ''
                },
                job: {
                    title: '',
                    body: '',
                    budget: 0,
                    status: 0
                }
            }
        },

        methods: {
            apply() {
                const vm = this;
                const api_token = this.api_token;

                vm.loading = true;
                axios.defaults.headers.common['Authorization'] = `Bearer ${api_token}`;
                axios.post(`/api/jobs/${vm.job.id}/apply`, this.data)
                    .then(({ data }) => {
                        vm.loading = false;
                        window.location.href = '/home';
                    })
                    .catch(({ response }) => {
                        vm.loading = false;
                        vm.is_errors = true;

                        setTimeout(() => {
                            vm.is_errors = false;
                        }, 5000)
                    })
            }
        }
    }
</script>
