<template>
    <div>
        <alert v-if="is_errors"></alert>
        <ul class="list-group list-group-flush">
            <li class="list-group-item" v-if="applier.length != 0" v-for="(a, index) in applier" :key="index">
                <div class="row">
                    <div class="col-md-9">
                        <div class="media">
                            <img class="mr-3" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2264%22%20height%3D%2264%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_16482d1a0ff%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_16482d1a0ff%22%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2213.84375%22%20y%3D%2236.5%22%3E64x64%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" alt="Generic placeholder image">
                            <div class="media-body">
                                <h5 class="mt-0">{{ `${a.firstname} ${a.lastname}` }}</h5>
                                <p style="margin: 0;">Bid: <span class="text-danger">${{ a.pivot.fr_budget }}</span></p>
                                <p>Completed At: <strong>{{ a.pivot.completed_at }}</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <p class="pull-right">
                            <a href="" class="btn btn-success" @click.prevent="markComplete(a.pivot.job_id, a.pivot.user_id)" v-if="a.pivot.status == 'accepted'">
                                <i class="fa fa-check"></i> Mark as completed
                            </a>
                            <span v-else-if="a.pivot.status == 'completed'" class="text-success"><i class="fa fa-check"></i> Completed</span>
                            <a href="" class="btn btn-primary" @click.prevent="accept(a.id)" v-else>Accept</a>
                        </p>
                        <p class="pull-right">
                            <button type="button"
                                class="btn btn-secondary"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="top"
                                :data-content="a.pivot.messages"
                            >Messages</button>
                        </p>
                    </div>
                </div>
            </li>

            <li class="list-group-item" v-if="applier.length == 0">
                <p class="text-muted text-center">There is no freelancer apply.</p>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            job_id: {
                type: String,
            },
            api_token: {
                type: String
            },
            encoded_applier: {
                type: String
            }
        },

        created() {
            this.applier = typeof this.encoded_applier === 'string'
                ? JSON.parse(this.encoded_applier)
                : null;
        },

        mounted() {
            $('[data-toggle="popover"]').popover({
                trigger: 'hover'
            })
        },

        data() {
            return {
                is_errors: false,
                applier: []
            }
        },

        methods: {
            markComplete(job_id, user_id) {
                const vm = this;

                if (confirm('Are you sure?')) {
                    const data = {
                        user_id
                    };

                    axios.defaults.headers.common['Authorization'] = `Bearer ${vm.api_token}`;
                    axios.post(`/api/jobs/${job_id}/markcomplete`, data)
                        .then(({ data }) => {
                            location.reload();
                        }).catch(({ response }) => {
                            vm.is_errors = true;

                            setTimeout(() => {
                                vm.is_errors = false;
                            }, 5000)
                        });
                }
            },
            accept(applier_id) {
                const vm = this;

                if (confirm('Are you sure?')) {
                    const data = {
                        user_id: applier_id
                    };

                    axios.defaults.headers.common['Authorization'] = `Bearer ${vm.api_token}`;
                    axios.post(`/api/jobs/${vm.job_id}/accept`, data)
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
