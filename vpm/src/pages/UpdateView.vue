<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
        Edit
      </div>
      <div class="card-body">
        <form @submit.prevent="onSubmit">
          <div class="form-group">
            <label for="">제목</label>
            <input type="text"
              class="form-control" name="title" id="title" aria-describedby="helpId" placeholder="" v-model="todo.title">
            <small id="helpId" class="form-text text-muted">제목을 입력하세요.</small>
          </div>

          <div class="form-group">
            <label for=" body">내용</label>
            <textarea v-model="todo.body" class="form-control" name="body" id="body" rows="3"></textarea>
            <small id="helpId" class="form-text text-muted">내용을 입력하세요.</small>
          </div>
          
          <div class="form-group">
            <label>완료여부</label>
            <input type="checkbox" class="ml-2 mr-2" :id="todo.id" v-model="todo.active" @change="toggleTodo">
            <span class="form-check-label" :class="todo.active == false ? 'active' : '' ">
              {{ todo.active ? "완료" : "진행중" }}
            </span>
          </div>
          
          <div class="btn-group">
            <div class="btn-group" role="group" aria-label="">
              <button type="submit" class="btn btn-primary" :disabled="todoUpdate">확인</button>
              <button type="button" class="btn btn-danger" @click="moveList">취소</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { reactive, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import _ from 'lodash'
import { useToast } from '@/composables/toast.js'

export default {
  components: {
  },
  setup(){
    const todo = reactive({
      title: '',
      complete: false,
      body: '',
      id: 0,
      active: false
    });
    const originalTodo = reactive({
      title: '',
      complete: false,
      body: '',
      id: 0,
      active: false
    });
    const todoUpdate = computed( () => {
      return _.isEqual(todo, originalTodo);
    });
    const route = useRoute();
    const getInfo = () => {
      fetch(`http://bluej1996.dothome.co.kr/data_read_id.php?id=${route.params.id}`)
        .then(res => res.json())
        .then(data => {
          todo.title = data.result[0].title;
          todo.body = data.result[0].body;
          todo.complete = data.result[0].complete;
          todo.id = data.result[0].id;
          if(todo.complete === '0') {
            todo.active = false;
          }else{
            todo.active = true;
          }

          originalTodo.title = data.result[0].title;
          originalTodo.body = data.result[0].body;
          originalTodo.complete = data.result[0].complete;
          originalTodo.id = data.result[0].id;
        })
        .catch()
    }
    getInfo();
    const router = useRouter();
    
    const {
        toastMessage,
        toastShow,
        triggerToast
    } = useToast();

    const toggleTodo = () => {
      if(todo.active == true) {
        todo.complete = '1';
      }else{
        todo.complete = '0';
      }
    }
    const onSubmit = () => {
      if(!todo.title) {
        triggerToast('제목을 입력하세요.')
        return;
      }
      if(!todo.body){
        triggerToast('내용을 입력하세요.')
        return;
      }
      fetch(`http://bluej1996.dothome.co.kr/data_update.php?id=${todo.id}&title=${todo.title}&body=${todo.body}&complete=${todo.complete}`)
        .then(res => res.json())
        .then(data => {
          if(data.result == 1){
            router.push({
              name: 'Detail',
              params: {
                id: todo.id
              }
            })
          }
        })
        .catch()
        
    }
    const moveList = () => {
        router.push({name: 'List'});
    }
    return {
      todo,
      onSubmit,
      moveList,
      todoUpdate,
      toggleTodo,
      toastMessage,
      toastShow,
      triggerToast
    }
  }
}
</script>

<style>

</style>