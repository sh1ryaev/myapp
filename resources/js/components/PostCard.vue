<template>
  <loading :active='isLoading' :is-full-page="fullPage" :loader='loader' />
  <div v-if="searchWord.length>0" class="mb-5">
    <span class="m-1" >Поиск по запросу <strong>{{searchWord}}</strong></span>
    <span><a class="btn btn-light btn-sm rounded-pill" @click="clearFilter" href=""><i class="fas fa-times"></i></a></span>
  </div>
 <MasonryWall v-cloak :items="posts" :ssr-columns="1" :column-width="columnWidth" :gap="30">
    <template #default="{ item }">
 <MDBCard @mouseover="active = true; item_id=item.id" @mouseleave="active = false; item_id=item.id" class=" p-1 mx-auto" >
          <div v-if="active & item_id===item.id" class="d-flex align-items-center" style="position: absolute; z-index: 4">

          </div>
   <div>
          <div v-if="item.photos.length>1">
            <transition name="fade" mode="out-in">
              <div v-if="active& item_id===item.id" class="mask rounded" style="background-image: linear-gradient(to top, #e6e9f0 0%, #eef1f5 100%); z-index: 0 "></div>
    </transition>
             <carousel :items-to-show="1" :wrap-around="true">
    <slide v-for="(slide,index) in item.photos" :key="index">
      <div class="carousel__item">
        <MDBCardImg @click="showLightBox(item.photos)" :src="'../storage/image/news/origin/'+slide.post_photo" alt="..." style="z-index: -1;" />
      </div>
    </slide>

    <template #addons>
      <navigation />
      <pagination />
    </template>
  </carousel>
          </div>
<div v-else>
  <a @click="showLightBox(item.photos)"><img class="rounded d-block w-100" v-bind:src="'../storage/image/news/origin/'+item.photos[0].post_photo" alt="..." style="width: 100%;  object-fit: cover;">
    <transition name="fade" mode="out-in">
              <div v-if="active& item_id===item.id" class="mask rounded" style="background-color: rgba(0, 0, 0, 0.2); z-index: 0 "></div>
    </transition>
  </a>
</div>
   </div>
   <transition name="fade" mode="out-in">
     <a v-if="active & item_id===item.id & type!='mypost' & item.photos.length>1"  href="/profile"
                       style="position: absolute;bottom: 3%;left:3%">
              <img class=" m-1" v-bind:src="'https://www.glazestock.com/img/anon.0bb7b666.png'" alt="..."
                   style="width: 40px;height: 40px; border-radius: 50%;">
            <span class="text-muted" style="font-size: 10px"><strong>{{item.username}}</strong></span>
            </a>
        <a v-else-if="active & item_id===item.id & type!='mypost' & item.photos.length==1" href="/profile"
                       style="position: absolute;bottom: 3%;left:3%">
              <img class=" m-1" v-bind:src="item.user_photo" alt="..."
                   style="width: 40px;height: 40px; border-radius: 50%;">
            <span class="text-light" style="font-size: 10px"><strong>{{item.username}}</strong></span>
            </a>
     </transition>
   <transition name="fade" mode="out-in" style="width: 100%">
         <MDBCardBody v-if="active & item_id===item.id"  style="position: absolute;">
      <MDBCardTitle
                    style="position: absolute; left: 0%;top: 0%; font-size: 14px;color: white">
        <a class=" btn btn-light btn-sm text-muted"  role="button" @click="changeCat(item.cat_id)" style="font-size: 10px">
          <strong>{{item.category}}</strong>
        </a>
      </MDBCardTitle>
                    <div v-if="type==='mypost'" style="position: absolute; right: 0%;top:0%">
                <MDBBtn  @click="editPost(item)" color="primary" floating title="Редактировать пост" style=" right: 2%">
                <MDBIcon icon="edit"></MDBIcon>
              </MDBBtn>
              <MDBBtn @click="deletePost(item.id)" color="primary" floating title="Удалить пост">
                  <MDBIcon icon="trash"></MDBIcon>
              </MDBBtn>
              </div>
      <div v-if="type!='mypost'">
      <transition name="bounce" mode="out-in">
          <div :key="'del'+item.id" v-if="item.is_liked >0" style="position: absolute; right:3%;top:3%">
           <MDBBtn size="sm" v-on:click.once=" delLikePost(item.id)" color="danger" rounded>
               <i class="bi bi-suit-heart-fill fs-6"></i>
               <span style="font-size: 16px"><strong>{{" "+item.like_count}}</strong> </span>
          </MDBBtn>
      </div>
      <div v-else :key="'do'+item.id" style="position: absolute; right:3%;top:3%">
           <MDBBtn size="sm" v-on:click.once="doLikePost(item.id)" color="light" rounded >
               <i class="bi bi-suit-heart-fill fs-6"></i>
             <span style="font-size: 16px"><strong>{{" "+item.like_count}}</strong> </span>
          </MDBBtn>
      </div>
      </transition>
      </div>
    </MDBCardBody>
   </transition>
  </MDBCard>
  <div class="mb-0">
    <a class="btn btn-link" @click="showPost(item.id)" style="color: black">
      <p class="mb-0" style="font-family: Montserrat; font-size: 14px"><strong>{{item.title}}</strong></p>
    </a>
                 <p><small style="font-size: 12px" class="text-muted">Опубликовано {{item.created_at}}</small></p>

  </div>
    </template>
  </MasonryWall>
  <div v-cloak>
      <div v-show="isBlank" style="padding: 250px">
      <h1 v-show="notFound">Ничего не найдено!</h1>
    </div>
  </div>
  <!-- Button trigger modal -->
               <vue-easy-lightbox
      escDisabled
      moveDisabled
      :visible="visible"
      :imgs="imgs"
      :index="index"
      @hide="handleHide"
    ></vue-easy-lightbox>
<MDBModal
    id="varyingExampleModal"
    tabindex="-1"
    labelledby="varyingExampleModalLabel"
    v-model="exampleModal"
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
            v-model="title"
          />
        </div>
        <div class="mb-3">
          <label for="message-text" class="col-form-label">Описание:</label>
          <textarea class="form-control" id="message-text" v-model="description"></textarea>
        </div>
        <div class="mb-3">
          <p class="col-form-label">Категория:</p>
          <div v-for="category in categories" :key="category.id">
              <input  v-model="ch_category" v-bind:value="category.id" class="form-check-input float-start m-1" type="radio" v-bind:id="'radio'+category.id">
              <label class="form-check-label float-start m-1" v-bind:for="'radio'+category.id">{{category.name}}</label>
          </div>
        </div>
        <div class="mb-3">
          <MDBFile
          v-model="files"
          multiple
          label="Выберите изображения:"
          v-on:change="handleFileUploads"
          />
        </div>
      </form>
      <div class="large-12 medium-12 small-12 cell">
  <div v-for="(file, key) in files" :key="key" class="file-listing">{{ file.name }} <span class="remove-file" v-on:click="removeFile( key )">{{key}}Remove</span></div>
</div>
<br>
    </MDBModalBody>
    <MDBModalFooter>
      <MDBBtn v-on:click="savePost" color="primary"> Сохранить пост </MDBBtn>
    </MDBModalFooter>
  </MDBModal>
</template>
<script>
import { MDBCard, MDBCardBody, MDBCardTitle, MDBBtn,
      MDBModal,
      MDBModalHeader,
      MDBModalTitle,
      MDBModalBody,MDBCardImg,
      MDBModalFooter,MDBIcon} from "mdb-vue-ui-kit";
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';
import axios from "axios";
import MasonryWall from "@yeger/vue-masonry-wall";
import VueEasyLightbox from 'vue-easy-lightbox'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: "PostCard",
      data(){
      return{
        isBlank:true,
        notFound:false,
        searchWord:'',
        title:'',
        description:'',
        files:'',
        ch_category:'',
        item_id:'',
        exampleModal:false,
        posts:[],
        like_posts:[],
        photos:[],
        categories:[],
        categories1:[],
        user_photo:[],
        columnWidth:10000,
        componentKey:0,
        active:false,

        classObject: {
      'col-md-6': false
    },
        //LightBox
        imgs:'', // Img Url , string or Array of string
        visible: false,
        index: 0, // default: 0
        //loader
        isLoading: false,
        fullPage: true,
        loader: 'bars',
      }
    },
    components: {
      MDBCard,
      MDBCardBody,
      MDBCardTitle,
      //MDBCardText,
      MDBBtn,
      MDBModal,
      MDBModalHeader,
      MDBModalTitle,
      MDBModalBody,
      MDBModalFooter,
      MDBIcon,
      MasonryWall,
      VueEasyLightbox,
      Carousel, Slide, Pagination, Navigation,MDBCardImg,
      Loading
    },
  props: {
    type: String,
    chCategories: Array,
    grid: String,
    filter: Number,
    page:Number,
  },
  mounted () {
                this.isLoading = true;

    if(localStorage.getItem('user_id')==null){
      localStorage.setItem('user_id',0)
    }
    if(localStorage.getItem('searchWord'))
    {
      this.searchWord = localStorage.getItem('searchWord')
    }
    this.getCategories();
    if(this.grid===true)
    {
      this.classObject={'col-md-6':true}
    }
    if(this.type==='all')
    {
     this.categories1 = this.chCategories;
     this.columnWidth = 250
    }
    // if(this.$store.state.isAuth)
    // {
    //  this.get_Posts();
    //   this.getLikePosts();
    // }
    // else {
      this.get_Posts();
    //}
    },
  methods:{
    showPost(item){
      localStorage.setItem('id_post',item)
      window.location.assign('/post')
    },
    clearFilter()
    {
      this.categories1=''
      localStorage.setItem('searchWord','')
    },
    showLightBox(item){
            var res = []
            for(var i=0;i<item.length;i++)
            {
              var itm = '../storage/image/news/origin/'+item[i].post_photo
              res.push(itm)
            }
            console.log(res)
            this.imgs=res;
            this.index=0
            this.visible = true
    },
     showMultiple() {
       this.show()
     },
     show() {
       this.visible = true
     },
     handleHide() {
       this.visible = false
     },
    changeCat(id){
      this.categories1=[]
      this.categories1[0]=id
      this.get_Posts()
    },
    getLikePosts(){
          axios.get('/user-like/'+localStorage.getItem('user_id')+'/').then(response => {
            let ans = []
            for( var i = 0; i < response.data.length; i++ )
            {
              ans[i] = response.data[i].id_post
            }
            this.like_posts = ans
          });
        },
    getCategories(){
            axios.get('/api/categories/').then(response => {
                this.categories = response.data;
                console.log(response)
            });
        },
        get_Posts() {
          let str = this.categories1.join('%')
          let search = localStorage.getItem('searchWord')
            if(search==null)
            {
                search=''
            }
          axios.get('/api/posts?cat_id='+str+'&search='+search+'&page='+this.page).then(response => {
              this.$emit('updatePages', {
                  links:response.data.links
              })
              let ans = []
              var res=[]
              var iter=0
              var posts = Object.values(response.data.data)
              console.log(response.data.data)
                for (var i = 0; i < posts.length; i++) {
                    const d = new Date(posts[i].created_at);
                    var options = {hour:'numeric', minute:'numeric', year: 'numeric', month: 'long', day: 'numeric' };
                    let created_at = d.toLocaleDateString('ru-RU', options)
                    var user_photo=posts[i].user.covers
                    if(user_photo.length==0){
                        user_photo='https://www.glazestock.com/img/anon.0bb7b666.png'
                    }
                    var post = {
                        id: posts[i].id,
                        id_user: posts[i].user_id,
                        title: posts[i].title,
                        description: posts[i].description,
                        created_at: created_at,
                        like_count: posts[i].like_count,
                        is_liked: 0,
                        category: posts[i].category.name,
                        photos: posts[i].covers,
                        username: posts[i].user.name,
                        user_photo: user_photo,
                        date:posts[i].created_at,
                        cat_id:posts[i].category.id
                    }
                    if (this.type === 'mypost' && posts[i].id_user == localStorage.getItem('user_id')) {
                        res[iter] = post
                        iter += 1
                    } else if (this.type === 'likedpost' && is_liked > 0) {
                        res[iter] = post
                        iter += 1
                    } else if (this.type === 'all') {
                        res[i] = post
                    }
                }

                this.like_posts = ans
                if(this.filter==1)
                {
                  res.sort(function (a, b) {
                  return b.like_count - a.like_count})
                }
                else if(this.filter==2)
                {
                  res.sort(function (a, b) {
                  return a.like_count - b.like_count})
                }
                else if(this.filter==3)
                {
                  res.sort(function (a, b) {
                  return new Date(b.date) - new Date(a.date)})
                }
                else if(this.filter==4)
                {
                  res.sort(function (a, b) {
                  return new Date(a.date) - new Date(b.date)})
                }
                if(res.length>0){
                  this.isBlank=false
                }
                else{
                  this.notFound=true
                }
                this.posts = res
              this.isLoading = false;


          });
        },
        editPost(item)
        {
          this.exampleModal=true
          this.title=item.title
          this.description=item.description
          this.ch_category =item.cat_id
          this.item_id=item.id
        },
    savePost()
    {
      axios.put('posts/'+this.item_id+'/',
          {title:this.title,description:this.description,category_id:this.ch_category,id_user:localStorage.getItem('user_id')})
      .then(response=>{
        console.log(response)
        alert('Изменения успешно сохранены!')
        this.componentKey += 1
      })
    },
    deletePost(id){
      axios.delete('posts/'+id+'/')
          .then(response=>{
            console.log(response)
            alert('Пост успешно удален!!')
            this.componentKey += 1;
          })
    },
    doLikePost(id) {
          if(this.$store.state.isAuth)
          {
             setTimeout(() => {
                      console.log('sd')
        }, 1000);
            axios.post('/user-like/',{id_user:localStorage.getItem('user_id'),id_post:id}).then(response=>{console.log(response);this.get_Posts()})
          }
      },
        delLikePost(id){
          if(this.$store.state.isAuth)
          {
            axios.post('/user-dellike/',{id_user:localStorage.getItem('user_id'),id_post:id}).then(response => {

            console.log(response)
            this.get_Posts()
          });
          }
        },
  },

}

</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital@0;1&display=swap');
[v-cloak] {
    display: none !important;
}
.bounce-enter-active {
  animation: bounce-in .4s;
}
.bounce-leave-active {
  animation: bounce-in .4s reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}
.carousel__next{
 right: 5%!important;
}
.carousel__prev{
  left: 5%!important;
}
.carousel__prev, .carousel__next{
  background-color: white;
  opacity: 0.55;
  color: black;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
  opacity: 0;
}


</style>
