<template>
    <navbar></navbar>
    <div class="container">
    </div>
    <div class="bg-light bg-gradient">
        <router-view/>
    </div>
</template>


<script>
import axios from "axios"
import navbar from "./navbar.vue"

export default {
    name: 'App',
    components: {
        navbar,
    },
    data(){
        return{

        }
    },
    beforeCreate() {

    },
    created() {

    },
    methods: {

    },
}

</script>

<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}

</style>
