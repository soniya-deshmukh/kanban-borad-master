<template>
  <form
    class="relative mb-3 flex flex-col justify-between bg-white rounded-md shadow overflow-hidden"
    @submit.prevent="handleAddNewTask" >
    <div class="kanban-item__content">
      <input type="text" v-model.trim="newTask.content" ref='inputContent' class="kanban-item__input"  placeholder="Enter new task"/>
      <div v-show="errorMessage">
        <span class="text-xs text-red-500">
          {{ errorMessage }}
        </span>
      </div>
    </div>
    <div class="p-3 flex justify-between items-end text-sm bg-gray-100">
      <button @click="$emit('task-canceled')" type="reset" class="kanban-item__button" >
      <span class="material-icons" style="font-size: 23px;">cancel</span>
      </button>
      <button type="submit" class="kanban-item__button" >
      <span class="material-icons" style="font-size: 23px;">add</span>
      </button>
    </div>
  </form>
</template>
<script>
import axios from "axios"; 
export default {
  props: {
    statusId: Number
  },
  data() {
    return {
      newTask: {
        content: "",
        description: "",
        status_id: null
      },
      errorMessage: ""
    };
  },
  mounted() {
     this.$nextTick(function () {
      this.$refs.inputContent.focus()
    }) 
    this.newTask.status_id = this.statusId;
  },
  methods: {
    handleAddNewTask() {
      // Basic validation so we don't send an empty task to the server
        if (!this.newTask.content) {
            this.errorMessage = "This field is required";
            this.$nextTick(function () {
              this.$refs.inputContent.focus()
            })
            return;
        }
      // Send new task to server
         axios
        .post("./api/addtask", this.newTask)
        .then(res => {
          // Tell the parent component we've added a new task
          this.$emit("task-added", res.data);
        })
        .catch(function (error) {
              console.log(error);
          });
    },
  }
};
</script>
<style lang="scss" scoped>
.kanban-item {
  background-color: #FFF;
  padding: 15px;
  border-radius: 5px;
  display: flex;
  margin-bottom: 7px;
}

.kanban-item__content {
  flex: 1;
}

.kanban-item__button {
  background: transparent;
  color: #8A94A5;
  outline: 0;
  border: 0;
  cursor: pointer;
  padding: 0;
}

.kanban-item__input {
  height: 30px;
  width: 100%;
}
</style>
