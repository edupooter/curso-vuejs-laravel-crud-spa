<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="/">
                    Vue.js SPA + Laravel API
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"
                    >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <router-link
                                exact :to="{ name: 'posts.index' }"
                                class="nav-link"
                                active-class="active-menu-item"
                                >
                                Posts list
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link
                                exact :to="{ name: 'posts.create' }"
                                class="nav-link"
                                active-class="active-menu-item"
                                >
                                Add new post
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <router-view></router-view>
            </div>
        </main>
    </div>
</template>

<script>
    export default {};
</script>

<style>
    .active-menu-item {
        font-weight: bold;
    }
</style>
