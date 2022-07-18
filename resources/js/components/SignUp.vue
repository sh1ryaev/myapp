<template>
    <MDBRow center class="d-flex align-items-center" style="height: 650px">

        <MDBCol col="4">
            <div class="sign-in" >
                <h1>Регистрация</h1>
                <div v-if="errors.length>0" class="bg-danger">
                    {{errors}}
                </div>
                <form>
                    <MDBInput
                        class="mb-3"
                        type="email"
                        label="Введите email"
                        v-model="email"
                        invalidFeedback="Укажите email"
                        validFeedback="Отлично!"
                        required
                    />
                    <MDBInput
                        class="mb-3"
                        type="text"
                        label="Введите имя пользователя"
                        v-model="username"
                        invalidFeedback="Введите имя пользователя"
                        validFeedback="Отлично!"
                        required
                    />
                    <MDBInput
                        class="mb-3"
                        type="text"
                        label="Введите имя"
                        v-model="first_name"
                        invalidFeedback="Введите имя"
                        validFeedback="Отлично!"
                        required
                    />
                    <MDBInput
                        class="mb-3"
                        type="text"
                        label="Введите фамилию"
                        v-model="last_name"
                        invalidFeedback="Введите фамилию"
                        validFeedback="Отлично!"
                        required
                    />
                    <MDBInput
                        class="mb-3"
                        type="password"
                        label="Введите пароль"
                        v-model="password"
                        invalidFeedback="Введите пароль"
                        validFeedback="Отлично!"
                        required
                    />
                    <MDBInput
                        class="mb-3"
                        type="password"
                        label="Повторите пароль"
                        v-model="re_password"
                        invalidFeedback="Повторите пароль"
                        validFeedback="Отлично!"
                        required
                    />
                    <MDBBtn v-on:click="submitForm" color="primary"> Войти </MDBBtn>
                </form>
            </div>
        </MDBCol>

    </MDBRow>
</template>

<script>
import { MDBInput, MDBBtn, MDBRow, MDBCol } from 'mdb-vue-ui-kit';
import axios from "axios"
export default {
    name: "SignUp",
    components:{
        MDBInput,
        MDBBtn,
        MDBCol,
        MDBRow,
    },
    data(){
        return{
            username:'',
            password:'',
            re_password:'',
            first_name:'',
            last_name:'',
            email:'',
            errors:'',
        }
    },
    mounted() {

    },
    methods: {
        submitForm(){
            axios.post('/api/auth/register/', {
                password:this.password,
                name:this.first_name,
                email:this.email,
            })
                .then(function (response) {
                    console.log(response)
                    console.log(response.statusText)
                    this.status=response.statusText
                    window.location.assign('/')
                })

        }
    }
}
</script>

<style scoped>

</style>
