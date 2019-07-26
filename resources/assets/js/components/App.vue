/**
* App.vue
* Main file for the table. The user can create, read, update and delete tasks. 
*
* Author Björn Berggren   
*/

<template>
  <div class="app-component">
    <loading :active.sync="isLoading"></loading>
    <p class="text-danger">{{output.message}}</p>

    <table class="table">
      <thead>
        <tr>
          <th>Created</th>
          <th>Task</th>
          <th>Remove</th>
          <th>Edit</th>
          <th>Done</th>
        </tr>
      </thead>
      <tbody>
        <task-component
          v-for="task in tasks"
          :key="task.id"
          :task="task"
          @delete="remove"
          @update="edit"
        ></task-component>

        <tr>
          <td>
            <input v-model="task.title" type="text" id="task" class="form-control" />
          </td>

          <td>
            <button @click="store" class="btn btn-pill btn-success">Add</button>
          </td>
          <br />

          <input type="hidden" name="_token" v-bind:value="csrf" />
        </tr>
        <tr></tr>
      </tbody>
    </table>
  </div>
</template>


<script>
import TaskComponent from "./Task.vue";
import Loading from "vue-loading-overlay";
import Error from "./vue-error-handler.js";

import "vue-loading-overlay/dist/vue-loading.min.css";
export default {
  data() {
    return {
      isLoading: false,
      tasks: [],
      task: { title: "", done: "", id: false },
      tempTask: { title: "", done: "", id: false },
      messages: { error: "No empty fields are allowed" },
      output: { message: "" },
      csrf: ["csrf", "id"]
    };
  },
  methods: {
    // Gets all the tasks avaible from TaskController
    getTasks() {
      window.axios.get("/api/tasks").then(({ data }) => {
        data.forEach(task => {
          this.tasks.push(task);
        });
      });
    },
    // Posts the new task to the TaskController
    store() {
      if (this.task.title.trim()) {
        this.isLoading = true;
        this.output.message = "";
        axios
          .post("/api/tasks", this.task)
          .then(newTask => {
            this.tasks.push(newTask.data);
            this.task.title = "";
            this.isLoading = false;
          })
          .catch(editedTask => {
            this.isLoading = false;
            this.output.message = editedTask.message;
          });
      } else {
        this.output.message = this.messages.error;
        this.isLoading = false;
      }
    },
    // Patchs the edited task to the TaskController
    edit(id, title, done) {
      this.isLoading = true;
      this.output.message = "";
      this.tempTask.id = id;
      this.tempTask.done = done;
      this.tempTask.title = title;

      if (this.tempTask.title.trim()) {
        axios
          .patch(`/api/tasks/${id}`, this.tempTask)
          .then(editedTask => {
            let index = this.tasks.findIndex(task => task.id === id);
            this.tasks[index].title = editedTask.data.title;
            this.tempTask.title = "";
            this.tempTask.id = 0;
            this.tempTask.done = false;
            this.isLoading = false;
          })
          .catch(editedTask => {
            this.isLoading = false;
            // Receives error code 404 if fails
            this.output.message = editedTask.message;
          });
      } else {
        this.tasks = [];
        this.getTasks();
        this.output.message = this.messages.error;
        this.isLoading = false;
      }
    },
    // Delete the task to TaskController that will be removed
    remove(id) {
      this.isLoading = true;
      this.output.message = "";

      axios
        .delete(`/api/tasks/${id}`)
        .then(() => {
          let index = this.tasks.findIndex(task => task.id === id);
          this.tasks.splice(index, 1);
          this.isLoading = false;
        })
        .catch(editedTask => {
          this.isLoading = false;
          this.output.message = editedTask.message;
        });
    }
  },
  created() {
    this.getTasks();
  },

  components: { TaskComponent, Loading }
};
</script>


<style>
</style>