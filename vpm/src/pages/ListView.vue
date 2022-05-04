<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
        Todo List
        <button class="btn btn-primary bt-write" @click="writeTodo">글작성</button>
        <div class="input-group mr-2 search">
          <input 
            class="form-control"
            placeholder="검색"
            type="text"
            v-model="searchTxt"
            @keyup.enter="getTotalSearch()"
          >
        </div>
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Title</th>
              <th>Complete</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item,index) in todos" :key="index">
              <td>{{index+1}}</td>
              <td><span @click="moveDetail(item.id)" class="detail">{{item.title}}</span></td>
              <td>
                <input type="checkbox" class="ml-2 mr-2" :id="item.id" v-model="item.active" @change="toggleTodo(item)">
                <span :class="item.active == false ? 'active' : ''">
                  {{ item.active ? "완료" : "진행중" }}
                </span>
              </td>
              <td>
                <div class="btn-group" role="group">
                  <button class="btn btn-primary" @click="editTodo(item.id)">수정</button>
                  <button class="btn btn-danger" @click="openModal(item.id)">삭제</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <nav aria-label="Page navigation example" v-show="page_total > 1">
      <ul class="pagination">
        <li class="page-item" v-show="page_now != 1"><a class="page-link" href="#" @click="getInfo(page_now-1)">Previous</a></li>
        <li class="page-item" v-for="item in page_total" :key="item">
          <a class="page-link" href="#" @click="getInfo(item)" :class="page_now == (item) ? 'active': '' ">{{item}}</a></li>
        <li class="page-item" v-show="page_now != page_total" @click="getInfo(page_now+1)"><a class="page-link" href="#">Next</a></li>
      </ul>
    </nav>
  </div>
  <teleport to='#popup'>
    <ModalWin 
      v-if="showModal"
      @close="closeModal"
      @delete="deleteTodo"
    >
      <template v-slot:title>삭제</template>
      <template v-slot:body>정말 삭제하시겠습니까?</template>
    </ModalWin>
  </teleport>
</template>

<script>
import { ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import ModalWin from '@/components/ModalWin.vue'
export default {
  components : {
    ModalWin
  },
  setup() {
    const todos = ref([]);
    const getInfo = (_page = 1) => {
      page_now.value = _page;
      if(searchActive.value == true) {
        searchTodo(page_now.value);
        return;
      }
      fetch(`http://bluej1996.dothome.co.kr/data_read.php?page_now=${page_now.value}&data_count=${data_count}`)
      .then(res => res.json())
      .then(data => {
        todos.value = data.result
        for(let item of todos.value){
          if(item.complete === '0'){
            item.active = false;
          }else{
            item.active = true;
          }
        }
      })
      .catch()
    }
    const showModal = ref(false);
    const deleteId = ref(null);
    const closeModal = () => {
      showModal.value = false;
      deleteId.value = null;
    }
    const openModal = (_id) => {
      deleteId.value = _id;
      showModal.value = true;
    }

    const deleteTodo = () => {
      fetch(`http://bluej1996.dothome.co.kr/data_delete.php?id=${deleteId.value}`)
      .then(res => res.json())
      .then(data => {
        if(data.result == 1){
          showModal.value = false;
          deleteId.value = null;
          getInfo();
        }else{
          console.log('삭제에 실패했습니다.')
        }
      })
      .catch()
    }
    const toggleTodo = (_obj) => {
        if(_obj.active == true) {
          _obj.complete = '1';
        }else{
          _obj.complete = '0';
        }
      fetch(`http://bluej1996.dothome.co.kr/data_update.php?id=${_obj.id}&title=${_obj.title}&body=${_obj.body}&complete=${_obj.complete}`)
      .then(res => res.json())
      .then(data => {
        console.log(data)
      })
      .catch();
    }
    const router = useRouter();
    const moveDetail = (_id) => {
      router.push({
        name: 'Detail',
        params: {
          id: _id
        }
      })
    }
    const editTodo = (_id) => {
      router.push({
        name: 'Update',
        params: {
          id: _id,
        }
      });
    }
    const writeTodo = () => {
      router.push({name: 'Create'})
    }
    let data_total = ref(0);
    let data_count = 5;
    let page_total = ref(0);
    let page_now = ref(1);
    
    const getTotal = () => {
      fetch(`http://bluej1996.dothome.co.kr/data_total.php`)
      .then(res => res.json())
      .then(data => {
        data_total.value = data.total;
        page_total.value = Math.ceil(data_total.value / data_count);
        page_now.value = 1;
        getInfo();
      })
      .catch()
    }
    getTotal();

    const searchActive = ref(false);
    const searchTxt = ref('');
    let searchTimer = null;

    watch(searchTxt, () => {
      clearTimeout(searchTimer);
      if(searchTxt.value !== '') {
        searchActive.value = true;
        searchTimer = setTimeout(() => {
          getTotalSearch();
        },1000);
      }else{
        searchActive.value = false;
        getTotal();
      }
    });

    const getTotalSearch = () => {
      clearTimeout(searchTimer);
      fetch(`http://bluej1996.dothome.co.kr/data_total_search.php?title=${searchTxt.value}`)
      .then(res => res.json())
      .then(data => {
        data_total.value = data.total;
        page_total.value = Math.ceil(data_total.value / data_count);
        page_now.value = 1;
        searchTodo();
      })
      .catch();
    }

    const searchTodo = () => {
      fetch(`http://bluej1996.dothome.co.kr/data_read_search.php?page_now=${page_now.value}&data_count=${data_count}&title=${searchTxt.value}`)
      .then(res => res.json())
      .then(data => {
        todos.value = data.result;
        for(let item of todos.value){
          if(item.complete === '0'){
            item.active = false;
          }else{
            item.active = true;
          }
        }
      })
      .catch();
    }

    return {
      todos,
      deleteTodo,
      moveDetail,
      editTodo,
      writeTodo,
      page_total,
      getInfo,
      page_now,
      closeModal,
      openModal,
      showModal,
      toggleTodo,

      searchTxt,
      getTotalSearch
    }
  }
}
</script>

<style>
  .detail{
    text-decoration: underline;
    color: #000;
    cursor: pointer;
  }
  .detail:hover{
    color: hotpink;
  }
  .bt-write {
    float: right;
  }
  .active{
    background: bisque;
  }
  .search{
    width: 80%;
    float: right;
  }
</style>