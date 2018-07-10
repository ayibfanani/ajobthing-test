<template>
    <div>
        <alert v-if="is_errors"></alert>
        <form @submit.prevent="store">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" v-model="job.title" class="form-control" placeholder="Title">
                    </div>

                    <div class="form-group">
                        <label>Body</label>
                        <textarea v-model="job.body" class="form-control" cols="30" rows="10" placeholder="What you need..."></textarea>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Budget</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">$</span>
                                </div>

                                <input type="number" class="form-control" placeholder="Your budget" v-model="job.budget"/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label>Status</label>
                            <select v-model="job.status" class="form-control">
                                <option value="0">Draft</option>
                                <option value="1">Publish</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button class="btn btn-primary btn-lg" type="submit" :disabled="loading">Add</button>
                <a href="/home" class="btn btn-default btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['api_token', 'encoded_job'],

        created() {
            if (typeof this.encoded_job === 'string' && JSON.parse(this.encoded_job).length != 0) {
                this.job = JSON.parse(this.encoded_job);
            }
        },

        data() {
            return {
                loading: false,
                is_errors: false,
                job: {
                    title: '',
                    body: '',
                    budget: 0,
                    status: 0
                }
            }
        },

        methods: {
            store() {
                const vm = this;
                const api_token = this.api_token;

                vm.loading = true;
                axios.defaults.headers.common['Authorization'] = `Bearer ${api_token}`;
                axios.post(`/api/jobs`, this.job)
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
