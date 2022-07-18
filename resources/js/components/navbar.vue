<template>
    <MDBNavbar expand="lg" position="top" light  style="background-color: white" container>
        <!-- Toggle button -->
        <MDBNavbarToggler
            target="#navbarCenterAlignExample"
            @click="collapse1 = !collapse1"
        ></MDBNavbarToggler>
        <!-- Collapsible wrapper -->
        <MDBCollapse id="navbarCenterAlignExample" v-model="collapse1">
            <MDBNavbarNav center>
                <!-- Center links -->
                <MDBNavbarBrand href="/">
                    <img
                        src="https://mdbootstrap.com/img/logo/mdb-transaprent-noshadows.webp"
                        height="30"
                        alt=""
                        loading="lazy"
                    />
                </MDBNavbarBrand>
                <form class="d-flex w-50">
                    <input
                        type="search"
                        class="form-control"
                        placeholder="Поиск..."
                        aria-label="Search"
                        v-model="searchWord"
                    />
                    <MDBBtn  @click="searchPosts" outline="primary">
                        <i class="bi bi-search fs-6"></i>
                    </MDBBtn>
                </form>
                <a href="/sign-up" class="btn btn-primary" style="font-size: 13px"><strong>Регистрация</strong></a>
                <MDBDropdown class="nav-item" v-model="dropdown6">
                    <MDBDropdownToggle tag="a" class="nav-link" @click="dropdown6 = !dropdown6"
                    ><img
                        src="https://mdbootstrap.com/img/Photos/Avatars/img (31).webp"
                        class="rounded-circle"
                        height="26"
                        alt=""
                        loading="lazy"
                    />
                    </MDBDropdownToggle>
                    <MDBDropdownMenu>
                        <MDBDropdownItem href="/profile">Профиль</MDBDropdownItem>
                        <MDBDropdownItem href="/log-in">Войти</MDBDropdownItem>
                        <MDBDropdownItem @click="logout" href="">Выход</MDBDropdownItem>
                    </MDBDropdownMenu>
                </MDBDropdown>

                <!-- Center links -->
            </MDBNavbarNav>

        </MDBCollapse>
        <!-- Collapsible wrapper -->
    </MDBNavbar>
</template>

<script>
import {
    MDBNavbar,
    MDBNavbarToggler,
    MDBNavbarBrand,
    MDBNavbarNav,
    MDBCollapse,
    MDBBtn,
    MDBDropdown,
    MDBDropdownToggle,
    MDBDropdownMenu,
    MDBDropdownItem,
    MDBIcon,
} from 'mdb-vue-ui-kit';
import axios from "axios";
export default {
    name: "navbar",
    components: {
        MDBNavbar,
        MDBNavbarToggler,
        MDBNavbarBrand,
        MDBNavbarNav,
        MDBCollapse,
        MDBBtn,
        MDBDropdown,
        MDBDropdownToggle,
        MDBDropdownMenu,
        MDBDropdownItem,MDBIcon
    },
    data(){
        return{
            collapse1:false,
            dropdown6:false,
            dropdown1:false,
            searchWord:'',
        }
    },
    mounted() {

    },
    methods: {
        handleLogout() {

        },
        searchPosts(){
            localStorage.setItem('searchWord',this.searchWord.trim())
            window.location.assign('/')
        },
        logout(e) {
            console.log('ss')
            e.preventDefault()
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response)
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}
</script>

<style scoped>

</style>
