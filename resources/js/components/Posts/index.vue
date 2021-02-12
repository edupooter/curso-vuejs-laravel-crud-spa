<template>
    <div>
        <select v-model="category_id" class="form-control col-md-3">
            <option value="">-- Choose category -- </option>
            <option v-for="category in categories" :key="category.id"
                :value="category.id">
                {{ category.name }}
            </option>
        </select>
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <a href="#" @click.prevent="changeSort('title')">Title</a>
                        <span v-if="this.sort_field == 'title' && this.sort_direction == 'asc'">
                            &uarr;
                        </span>
                        <span v-if="this.sort_field == 'title' && this.sort_direction == 'desc'">
                            &darr;
                        </span>
                    </th>
                    <th>
                        <a href="#" @click.prevent="changeSort('post_text')">Post text</a>
                        <span v-if="this.sort_field == 'post_text' && this.sort_direction == 'asc'">
                            &uarr;
                        </span>
                        <span v-if="this.sort_field == 'post_text' && this.sort_direction == 'desc'">
                            &darr;
                        </span>
                    </th>
                    <th>
                        <a href="#" @click.prevent="changeSort('created_at')">Created date</a>
                        <span v-if="this.sort_field == 'created_at' && this.sort_direction == 'asc'">
                            &uarr;
                        </span>
                        <span v-if="this.sort_field == 'created_at' && this.sort_direction == 'desc'">
                            &darr;
                        </span>
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="post in posts.data" :key="post.id">
                    <td scope="row">{{ post.title }}</td>
                    <td>{{ post.post_text.substring(0, 50) }}</td>
                    <td>{{ post.created_at }}</td>
                    <td>
                        <router-link
                            class="btn btn-info btn-sm"
                            :to="{ name: 'posts.edit', params: { id: post.id } }">
                            Edit
                        </router-link>
                        <button class="btn btn-danger btn-sm"
                            @click="deletePost(post.id)">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <pagination :data="posts" @pagination-change-page="getResults"></pagination>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                posts: {},
                categories: {},
                category_id: '',
                sort_field: 'created_at',
                sort_direction: 'desc',
            }
        },
        mounted() {
            this.getResults();
            this.getCategories();
        },
        watch: {
            category_id(value) {
                this.getResults();
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get(
                    '/api/posts?page=' + page
                    + '&category_id=' + this.category_id
                    + '&sort_field=' + this.sort_field
                    + '&sort_direction=' + this.sort_direction
                    ).then((response) => {
                        this.posts = response.data;
                    }
                );
            },
            getCategories() {
                axios.get('/api/categories').then((response) => {
                    this.categories = response.data.data;
                });
            },
            changeSort(field) {
                if (this.sort_field === field) {
                    this.sort_direction = this.sort_direction === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sort_field = field;
                    this.sort_direction = 'asc';
                }
                this.field = field;
                this.getResults();
            },
            deletePost(postId) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        axios.delete(`/api/posts/${postId}`)
                            .then(response => {
                                this.$swal('Post deleted');
                                this.getResults();
                            }).catch(error => {
                                this.$swal({ icon: 'error', title: 'Error happened' });
                            });
                    }
                });
            }
        }
    }
</script>

<style>

</style>
