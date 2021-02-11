<template>
    <div>
        <form @submit.prevent="submitForm">
            <div class="form-group">
                <label for="">Post title:</label>
                <input type="text" v-model="fields.title" class="form-control" placeholder="" aria-describedby="helpId">
                <small id="helpId" class="text-muted">Enter the post title</small>
            </div>

            <div class="form-group">
                <label for="">Post text:</label>
                <textarea rows="10" v-model="fields.post_text" class="form-control" placeholder="" aria-describedby="helpIdText"></textarea>
                <small id="helpIdText" class="text-muted">Enter the post text</small>
            </div>

            <div class="form-group">
                <label for="">Category:</label>
                <select v-model="fields.category_id" class="form-control" placeholder="" aria-describedby="helpIdCategory">
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
                <small id="helpIdCategory" class="text-muted">Select a category</small>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" value="form_submitting">
                    Submit
                </button>
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
                }
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
                axios.post('api/posts', this.fields)
                    .then(response => {
                        this.$router.push('/');
                    });

            }
        },
    }
</script>

<style>

</style>
