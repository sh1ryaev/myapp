<template>
    <MDBContainer class="pt-5">
      <MDBRow class="mb-3 w-100 p-5">
      <MDBCol tag="form"  class="col-md-4" >
        <div class="position-fixed">
          <div class="mb-3">
          <img class="img-fluid shadow-2-strong" src="https://www.glazestock.com/img/anon.0bb7b666.png" alt="..." style="border-radius: 50%; width: 200px; height: 200px">
            <MDBBtn v-on:click="createUserPhoto" color="primary" size="sm" floating>
                <i class="bi bi-plus fs-5"></i>
              <input type="file" class="form-control" name="photo" id="file" ref="file" v-on:change="createUserPhoto"
                     style="opacity: 0%; position: absolute; top:0%">
            </MDBBtn>
          </div>
        <MDBInput
            class="mb-3"
        label="Имя пользователя"
        v-model="username"
        invalidFeedback="Укажите имя пользователя"
        validFeedback="Отлично!"
        required
      />
        <MDBInput
            class="mb-3"
        label="Имя"
        v-model="first_name"
        invalidFeedback="Укажите имя"
        validFeedback="Отлично!"
        required
      />
      <MDBInput
          class="mb-3"
        label="Фамилия"
        v-model="last_name"
        invalidFeedback="Укажите фамилию"
        validFeedback="Отлично!"
        required
      />
        <div class="d-grid gap-2">
          <MDBBtn v-on:click="submitForm" color="primary" type="submit">Сохранить изменения</MDBBtn>
          <MDBBtn tag="a" @click="
      () => {
        modalContent = '@getbootstrap';
        varyingExampleModal = true;
      }" color="light" >Изменить email</MDBBtn>
          <MDBBtn v-on:click="changePassword" color="light" type="">Изменить пароль</MDBBtn>
        </div>
        </div>

      </MDBCol>
        <MDBCol class="col-md-8">
            <MDBBtnGroup>
    <MDBRadio :btnCheck="true" :wrap="false" labelClass="btn btn-primary" label="Мои посты" name="options" value="mypost"
      v-model="radio" />
    <MDBRadio :btnCheck="true" :wrap="false" labelClass="btn btn-primary" label="Мне нравится" name="options" value="likedpost"
      v-model="radio" />
  </MDBBtnGroup>
          <div v-if="radio==='mypost'">
            <PostCard ></PostCard>
          </div>
          <div v-else>
            <PostCard ></PostCard>
              <!--v-bind:type="radio"-->
          </div>
        </MDBCol>
</MDBRow>
      </MDBContainer>
  <!--Модальное окно-->
  <MDBModal
    id="varyingExampleModal"
    tabindex="-1"
    labelledby="varyingExampleModalLabel"
    v-model="varyingExampleModal"
  >
    <MDBModalHeader>
      <MDBModalTitle id="varyingExampleModalLabel"> Редактировать пост </MDBModalTitle>
    </MDBModalHeader>
    <MDBModalBody>
      <form>
        <div class="mb-3">
          <label for="title" class="col-form-label">Заголовок: </label>
          <input
            type="text"
            class="form-control"
            id="title"
            v-model="new_email"
          />
        </div>
      </form>
    </MDBModalBody>
    <MDBModalFooter>
      <MDBBtn color="primary"> Сохранить пост </MDBBtn>
    </MDBModalFooter>
  </MDBModal>
</template>

<script>
import {
  MDBCol,
  MDBRow,
  MDBInput,
  MDBContainer,
  MDBBtn,
  MDBRadio,
  MDBBtnGroup,
  MDBModal,
  MDBModalHeader, MDBModalTitle, MDBModalBody, MDBModalFooter

} from "mdb-vue-ui-kit";
    import PostCard from "./PostCard";
  import axios from "axios";
  export default {
    name: "UserProfile",
    data(){
      return{
        user:null,
        username:'',
          first_name:'',
          last_name:'',
          email:'',
          checkForm:null,
        photo:'',
        categories1:[],
        radio:'likedpost',
        current_password:'',
        new_email:'',
        re_new_email:'',
        new_password:'',
        re_new_password:'',
        varyingExampleModal:false,
      }
    },
    components: {
      MDBCol,
      MDBRow,
      MDBInput,
      MDBContainer,
      MDBBtn,
      MDBRadio,
      MDBBtnGroup,
      PostCard,
       MDBModal,
      MDBModalHeader,
      MDBModalTitle,
      MDBModalBody,
      MDBModalFooter,
    },
     mounted () {
      this.get_User()
       this.get_UserPhoto()
       this.checkForm = e => {
          e.target.classList.add("was-validated");
        };
    },
    methods: {
      changeEmail(){
        axios.post('/users/set_email/',{current_password:'',new_email:'',re_new_email:''})
            .then(response=>{console.log(response)})
            .catch(error => {
              console.log(error)
            })
      },
      changePassword(){
        axios.post('/users/set_password/',{new_password:'',re_new_password:'',current_password:''})
            .then(response=>{console.log(response)})
            .catch(error => {
              console.log(error)
            })
      },
      get_User(){
          console.log('------------------window.laravel.user')
          axios.get('/api/user/').then(response=>console.log(response))
              .catch(error=>console.log(error))
          axios.get('/api/v1/users/me/', {headers: {Authorization: `JWT ${localStorage.getItem('token')}`}})
              .then(response => {this.user = response.data;
              this.username=response.data.username;
              this.first_name=response.data.first_name;
              this.last_name=response.data.last_name;
              this.email=response.data.email;
              });
        },
      get_UserPhoto(){
        axios.get('/user-photo/'+localStorage.getItem('user_id')+'/')
        .then(response=>{this.photo=response.data})
      },
      submitForm() {
        axios.put('/api/v1/users/'+localStorage.getItem('user_id')+'/',
            {username:this.username,first_name:this.first_name,last_name:this.last_name},
            {headers: {Authorization: `JWT ${localStorage.getItem('token')}`}})
        .then(response=>{console.log(response.data)})
        .catch(error => {
              console.log(error)
            })
      },
      createUserPhoto(){
        this.file = this.$refs.file.files[0];
        console.log(this.file)
        let formData = new FormData();
        formData.append('cover', this.file);
        formData.append('id_user', localStorage.getItem('user_id'));
        axios.post('/user-photo/'+localStorage.getItem('user_id')+'/',formData,{headers:{
          'Content-Type': 'multipart/form-data'
        }})
      .then(response=>{
        console.log(response)

      })
      .catch(error =>{
        console.log(error)
      })
      },
      refreshJWT() {
        console.log(localStorage.getItem('refresh'))
        axios.post('/api/v1/jwt/refresh/', {refresh:localStorage.getItem('refresh')})
        .then(response => {
              console.log(response)
          const token = response.data.access
              this.$store.commit('setToken', token)
        localStorage.setItem('token',token)
            })
            .catch(error => {
              console.log(error)
            })
      }
    }

  }
</script>

<style scoped>

</style>
