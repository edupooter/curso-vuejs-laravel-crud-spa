<template>
    <div>
        <form @submit.prevent="submitForm">
            <div class="form-group">
                <label for="">Post title:</label>
                <input type="text" v-model="fields.title" class="form-control" placeholder="" aria-describedby="helpId">
                <small id="helpId" class="text-muted">Enter the post title</small>
                <div class="alert alert-danger" v-if="errors && errors.title">
                    {{ errors.title[0] }}
                </div>
            </div>

            <div class="form-group">
                <label for="">Post text:</label>
                <textarea rows="10" v-model="fields.post_text" class="form-control" placeholder="" aria-describedby="helpIdText"></textarea>
                <small id="helpIdText" class="text-muted">Enter the post text</small>
                <div class="alert alert-danger" v-if="errors && errors.post_text">
                    {{ errors.post_text[0] }}
                </div>
            </div>

            <div class="form-group">
                <label for="">Category:</label>
                <select v-model="fields.category_id" class="form-control" placeholder="" aria-describedby="helpIdCategory">
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
                <small id="helpIdCategory" class="text-muted">Select a category</small>
                <div class="alert alert-danger" v-if="errors && errors.category_id">
                    {{ errors.category_id[0] }}
                </div>
            </div>

            <div class="form-group">
                <input
                    type="submit"
                    class="btn btn-primary"
                    :value="sendingForm ? 'Sending...' : 'Save Post'"
                    :disabled="sendingForm"
                    />
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                categories: {},
                fields: {
                    title: '',
                    post_text: '',
                    category_id: '',
                },
                errors: {
                    title: '',
                    post_text: '',
                    category_id: '',
                },
                sendingForm: false
            }
        },
        mounted() {
            axios.get('api/categories')
                .then(response => {
                    const { data: categories } = response;
                    this.categories = categories.data;
                })
        },
        methods: {
            submitForm() {
                this.sendingForm = true;

                axios.post('api/posts', this.fields)
                    .then(response => {
                        this.$router.push('/');
                    }).catch(error => {
                        const { status: statusCode, data: { errors } } = error.response;
                        if (statusCode === 422) {
                            this.errors = errors;
                        }
                        this.sendingForm = false;
                    });
            }
        },
    }
</script>

<style>

</style>
