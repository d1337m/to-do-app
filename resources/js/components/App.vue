<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel navbar-static-top">
            <div class="container">
                <router-link :to="{name: 'home'}" class="navbar-brand">Bifrost</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <router-link :to="{ name: 'login' }" class="nav-link" v-if="!isLoggedIn">Login</router-link>
                        <router-link :to="{ name: 'register' }" class="nav-link" v-if="!isLoggedIn">Register</router-link>
                        <li class="nav-link" v-if="isLoggedIn"> Hi, {{name}}</li>
                        <router-link :to="{ name: 'dashboard' }" class="nav-link" v-if="isLoggedIn">Board</router-link>
                        <a class="nav-link" v-if="isLoggedIn" @click="logout()">Log out</a>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>    
    export default {
        data(){
            return {
                isLoggedIn : null,
                name : null
            }
        },
        mounted(){
            this.isLoggedIn = localStorage.getItem('jwt')
            this.name = localStorage.getItem('user')
        },
        methods: {
            logout() {
                localStorage.clear('jwt')
                localStorage.clear('user')

                this.$router.go('/login')
            }
        }
    }
</script>

<style scoped>
.sidenav {
    height: 100%;
    width: 10%;
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    background-color: black;
    overflow-x: hidden;
    padding-top: 20px;
}
.sidenav a {
    padding: 6px 8px 6px 16px;
    text-decoration: none;
    font-size: 25px;
    color: #818181;
    display: block;
    text-align: center;
}
.sidenav a:hover {
    color: #f1f1f1;
}
.nav-link {
    cursor: pointer;
}
.navbar-light {
    background-color: #F6F8FA;
}
</style>