<template>
      <MDBRow class=" w-100 p-0">
            <MDBCol col="1">
            </MDBCol>
            <MDBCol>
                <div class="pb-4 mb-4">
                <div class="d-flex bg-gradient bg-light position-fixed w-100" style="z-index: 11">
                    <MDBBtnGroup id="filter-target" class=" m-2" >
              <MDBBtn
                   title="Фильтр"
        v-on:click="dropdown = !dropdown"
        class="dropdown-toggle btn-sm ">
                  <i class="bi bi-funnel-fill"></i>
              </MDBBtn>
      <MDBDropdown  v-model="dropdown" target="#filter-target">
        <MDBDropdownMenu aria-labelledby="dropdownMenuButton">
          <div class="d-flex align-items-center justify-content-center">
            <p class="m-1"><strong>Сортировать по:</strong></p>
          </div>
          <MDBDropdownItem @click="changeFilter(1)" href="#">Количество лайков(возр)</MDBDropdownItem>
          <MDBDropdownItem @click="changeFilter(2)" href="#">Количество лайков(уб)</MDBDropdownItem>
          <MDBDropdownItem @click="changeFilter(3)" href="#">Сначала новые</MDBDropdownItem>
          <MDBDropdownItem @click="changeFilter(4)" href="#">Сначала старые</MDBDropdownItem>
                    <div class="d-lg-none d-md-none align-items-center justify-content-center">
                      <div class="m-2   align-items-center justify-content-center text-nowrap"
                     v-for="category in categories" :key="category.id">
          <MDBCheckbox
    tag="span"
    :id="category.id"
    :btnCheck="true"
    labelClass="btn btn-info rounded-pill btn-sm "
    :label="category.name"
    @click="changeCtg(category.id)"
  />
      </div>
          </div>
        </MDBDropdownMenu>
      </MDBDropdown>
    </MDBBtnGroup>
                <div class="m-2  d-lg-block d-none align-items-center justify-content-center text-nowrap"
                     v-for="category in categories" :key="category.id">
          <MDBCheckbox
    tag="span"
    :id="category.id"
    :btnCheck="true"
    labelClass="btn btn-info rounded-pill btn-sm "
    :label="category.name"
    @click="changeCtg(category.id)"
          />
            </div>
                    <MDBBtn
                        @click="() => {modalContent = '@getbootstrap';varyingExampleModal = true;}"
                        tag="a" title="Добавить пост"  class="btn-sm mt-2 float-end"  href="#!" floating>
                                     <i class="bi bi-plus fs-5"></i>
                    </MDBBtn>
                    </div>
                </div>
            </MDBCol>
            <MDBCol col="1"></MDBCol>
      </MDBRow>
    <MDBRow class="mb-3 w-100 p-0">
        <MDBCol col="1"></MDBCol>
          <MDBCol>
            <PostCard v-cloak type="all" :page="pageNum" :grid="grid" :chCategories="categories1" :filter="filter"
                      :key="componentKey" @updatePages="getPages">
            </PostCard>
              <nav aria-label="Page navigation example">
                  <ul class="pagination justify-content-center">
                      <li class="page-item">
                          <a class="page-link" href="#" aria-label="Previous" @click="prevPage">
                              <span aria-hidden="true">&laquo;</span>
                          </a>
                      </li>
                      <li v-for="(link,key) in links" :key="key" class="page-item">
                          <a class="page-link" href="#" @click="pageNum=link.label; this.componentKey += 1;">
                              <span aria-hidden="true">{{link.label}}</span>
                          </a>
                      </li>
                      <li class="page-item">
                          <a class="page-link" href="#" aria-label="Next" @click="nextPage">
                              <span aria-hidden="true">&raquo;</span>
                          </a>
                      </li>
                  </ul>
              </nav>
          </MDBCol>
        <MDBCol col="1"></MDBCol>
  </MDBRow>
<!--Модальное окно-->
        <MDBModal
            id="varyingExampleModal"
            tabindex="-1"
            labelledby="varyingExampleModalLabel"
            v-model="varyingExampleModal">
            <MDBModalHeader>
                <MDBModalTitle id="varyingExampleModalLabel"> Новый пост </MDBModalTitle>
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
                        <MDBRow >
                            <MDBCol v-for="(file, key) in urls" :key="key">
                                <div style="max-width: 120px; position: relative">
                                    <p :title="file.name" class="overflow-hidden">{{ file.name }}</p>
                                    <img :src="file.url" alt="..." style="max-width: 120px">
                                    <a class="btn btn-info btn-sm" v-on:click="removeFile(key)">Удалить</a>
                                </div>
                            </MDBCol>
                        </MDBRow>
                    </div>
                <br>
            </MDBModalBody>
            <MDBModalFooter>

                <MDBBtn v-on:click="savePost" color="primary"> Сохранить пост </MDBBtn>
            </MDBModalFooter>
        </MDBModal>
</template>

<script>
  import { MDBCol,MDBCheckbox,MDBIcon, MDBRow, MDBBtn, MDBModal, MDBModalHeader, MDBModalTitle, MDBModalBody, MDBModalFooter,MDBFile, MDBBtnGroup,MDBDropdown,MDBDropdownMenu, MDBDropdownItem} from "mdb-vue-ui-kit";
  import axios from "axios";
  import PostCard from "./PostCard";
  import { ref } from 'vue'



  export default {
    name: "HomePage",
    data(){
      return{
        posts:[],
        like_posts:[],
        photos:[],
        categories:[],
        categories1:[],
        varyingExampleModal:false,
        filterModal:false,
        files:'',
        title:'',
        description:'',
        ch_category:'',
        componentKey: 0,
        grid: false,
        filter:3,
        filterName:'',
        dropdown:false,
        pageNum:1,
          urls:[],
          links:[],
          isAuth:false,
      }
    },
    components: {
      MDBCol,
      MDBRow,
      MDBBtn,
      MDBModal,
      MDBModalHeader,
      MDBModalTitle,
      MDBModalBody,
      MDBModalFooter,
      MDBFile,
      MDBBtnGroup,
      MDBDropdown,MDBDropdownMenu, MDBDropdownItem,MDBIcon,MDBCheckbox,
      PostCard
    },
    mounted () {
        console.log(this.$my)
      this.getCategories();
    },
      methods: {

          getPages(links){
              console.log("----links--------")
              var res = [];
              for(var i=1;i<links.links.length-1;i++)
              {
                  res.push(links.links[i])
              }
              this.links=res
              console.log(this.links)
          },
      changeCtg(id){
        if(this.categories1.includes(id)===false){
          this.categories1.push(id)
        }
        else{
          const index = this.categories1.indexOf(id);
          if (index > -1) {this.categories1.splice(index, 1);}
        }
        this.componentKey += 1;
      },
      forceRerender() {

    },
        prevPage(){
              if(this.pageNum!=1)
              {
                  this.pageNum-=1
                  this.componentKey += 1;
              }
        },
        nextPage(){
              if(this.pageNum!=this.links.length)
              {
                  this.pageNum+=1
                  this.componentKey += 1;
              }
        },
        changeFilter(num){
          this.filter=num

          if(num===1)
          {
            this.filterName = 'Количество лайков(возр)'
          }
          else if(num===2)
          {
            this.filterName = 'Количество лайков(уб)'
          }
          else if(num===3)
          {
            this.filterName = 'Сначала новые'
          }
          else if(num===4)
          {
            this.filterName = 'Сначала старые'
          }
          this.componentKey+=1
        },
        changeGrid(){
          this.grid=true
          this.componentKey += 1;
        },
         changeGrid1(){
          this.grid=false
           this.componentKey += 1;
        },
              getCategories(){

            axios.get('/api/categories/').then(response => this.categories = response.data);
        },
        handleFileUploads(e){
          this.files = this.$refs.files;
            console.log('---------files-------------')
            //const file = e.target.files[0];
            var res = []
            for(var i=0;i<e.target.files.length;i++)
            {
                res.push({url:URL.createObjectURL(e.target.files[i]),
                    name:e.target.files[i].name
                })
            }
            this.urls=res
        },
        removeFile(key){
            var a = []
            var b = []
            for(var i = 0; i < this.files.length; i++){
                let file = this.files[i];
                a[i]=file
                b[i]=this.urls[i]
            }
            a.splice( key, 1 );
            b.splice( key, 1 );
            this.files=a
            this.urls=b
        },
        savePost(){
                    axios
      .post('/api/posts/',{
          user_id:localStorage.getItem('user_id'),
          title:this.title,
          description:this.description,
          category_id:this.ch_category
  })
      .then(response=>{
        console.log(response)
          this.varyingExampleModal=false;
          alert('Пост успешно создан!')
        console.log(ref(["file"]).value)
        for( var i = 0; i < this.files.length; i++ ){
          let file = this.files[i];
          var formData = new FormData();
          formData.append("post_photo", file);
        formData.append("post_id", response.data.post.id);
        console.log(file)
            console.log(response.data.post.id)

            axios.post('/api/posts-photos/',formData,{headers:{
          'Content-Type': 'multipart/form-data'
        }})
      .then(response=>{
        console.log(response)
      })
      .catch(error =>{
        console.log(error)
      })
        }
      })
      .catch(error =>{
        console.log(error)
      })
        }
      }}
</script>

<style lang="scss">
[v-cloak] {
    display: none !important;
}
.fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
    opacity: 0;
}
</style>
