<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group mt-3">
                <textarea name="body"
                          id="body"
                          class="form-control"
                          placeholder="Have something to say?"
                          rows="5"
                          required
                          v-model="body">
                </textarea>
            </div>

            <button type="submit"
                    class="btn btn-primary"
                    @click="addReply">Post</button>
        </div>


        <p class="text-center mt-3" v-else>
            Please <a href="/login">sign in </a> to participate in
            this discussion
        </p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: ''
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', { body: this.body })
                    .catch(error => {
                      flash(error.response.data, 'danger');
                    })
                    .then(({data}) => {
                      this.body = ''

                      flash('Your reply has been posted');

                      this.$emit('created', data);
                    });
            }
        }
    }
</script>
