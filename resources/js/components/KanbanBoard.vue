<template>
   <div v-if="items"> 
    <draggable class="kanban-board" group="groups" v-model="items" @end="() => {}">
      <kanban-column v-for="(group, groupId) in items" :key="'group_' + groupId" v-bind:label="group.label || 'Untitled'" @create="openAddTaskForm(group.id)">
        <draggable class="kanban-board__drop-area" group="{name: 'tasks_' + groupId, put: true}" v-model="items[groupId].tasks" @end="handleTaskMoved">
          <kanban-item v-for="(card, cardId) in group.tasks" :key="'card_' + cardId" v-model="card.content" 
           @change="editTask($event, card.id)"  @delete="deleteTask(card)" />
        </draggable>
          <AddTaskForm v-if="newTaskForStatus === group.id" :status-id="group.id"  v-on:task-canceled="closeAddTaskForm" v-on:task-added="handleTaskAdded" />
      </kanban-column>
    </draggable>
  </div> 
</template>
<script>
// Components
import KanbanColumn from './KanbanColumn'
import KanbanItem from './KanbanItem'
import draggable from 'vuedraggable'
import axios from "axios"; 
import AddTaskForm from './AddTaskForm'
export default {
  components: {
    KanbanColumn,
    KanbanItem,
    draggable,
    AddTaskForm
  },
  data() 
  {
    return {
      items: [],
      newTaskForStatus: 0,
      newTaskAdd:{
        content:''
      }
    }
  },
  mounted() {
    this.loadItems();
  },
  methods: {
    handleTaskMoved(evt) {
      axios.put("./api/tasks/sync", { columns: this.items }).catch(err => {
        console.log(err.response);
      });
    },
    openAddTaskForm(statusId) {
      this.newTaskForStatus = statusId;
    },
    closeAddTaskForm() {
      this.newTaskForStatus = 0;
    },
    editTask(newTask, taskId) {
      this.newTaskAdd.content = newTask;
      // Send update task to server
       axios
        .put("./api/editTask/"+taskId, this.newTaskAdd)
        .then(this.loadItems())
         /* .then((response) => {
            console.log(response.data);
          }) */
        .catch(function (error) {
              console.log(error);
          }); 
    },
    handleTaskAdded(newTask) {
      console.log(newTask);
      // Find the index of the status where we should add the task 
      const statusIndex = this.items.findIndex(
        status => status.id === newTask.status_id
      );
      // Add newly created task to our column
      this.items[statusIndex].tasks.push(newTask);
      // Reset and close the AddTaskForm
      this.closeAddTaskForm();
    },
    deleteTask(card){
      const statIndex = this.items.findIndex(
        status => status.id === card.status_id
      ); 
      // Find the index of  task 
      const taskIndex = this.items[statIndex].tasks.findIndex(
        task => task.id === card.id
      );
      //this.items[statIndex].tasks[taskIndex].splice(taskIndex, 1)
        axios.delete(`./api/deletetask/${card.id}`)
           .then(this.loadItems())
           .catch(function (error) {
              console.log(error);
          });  
    },
    loadItems: function () {
      axios.get('./api/tasks')
          .then((response) => {
              this.items = response.data;
          })
          .catch(function (error) {
              console.log(error);
          });
    },
  },
}
</script>
<style lang="scss" scoped>
.kanban-board {
  width: 1200px;
  display: flex;
  gap: 15px;
  font-family: 'Nunito', sans-serif;
  color: #8A94A5;
}
.kanban-board__drop-area {
  flex: 1;
  min-height: 400px;
}
</style>
