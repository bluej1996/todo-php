<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
        Todo List
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
              <td>{{item.complete}}</td>
              <td>
                <div class="btn-group" role="group">
                  <button class="btn btn-primary">편집</button>
                  <button class="btn btn-danger" @click="deleteTodo(item.id)">삭제</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
export default {
  setup() {
    const todos = ref([]);
      const getInfo = () => {
        fetch('http://bluej1996.dothome.co.kr/data_read.php')
        .then(res => res.json())
        .then(data => {
          todos.value = data.result
        })
        .catch()
      }
      getInfo();

      const deleteTodo = (_id) => {
        fetch(`http://bluej1996.dothome.co.kr/data_delete.php?id=${_id}`)
        .then(res => res.json())
        .then(data => {
          if(data.resul == 1){
            getInfo();
          }else{
            console.log('삭제에 실패했습니다.')
          }
        })
        .catch()

      }
      const router = useRouter();
      const moveDetail = (_id) => {
        console.log(_id);
        router.push({
          name: 'Detail',
          params: {
            id: _id
          }
        })
      }
    return {
      todos,
      deleteTodo,
      moveDetail
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
</style>