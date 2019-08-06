/**
* App.vue
* Main file for the form on the client-side. The user can create, read, update and delete tasks. 
*
* Author Björn Berggren   
*/

<template>
  <div class="app-component">
    <loading :active.sync="isLoading"></loading>
    <p class="text-danger">{{txtLabelOutputToUser.message}}</p>

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
            <input v-model="taskAdd.title" type="text" id="task" class="form-control" />
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
    // The objects and arrays used by this form
    return {
      // Turns the loading-icon on and off
      isLoading: false,
      // Will Contain all the tasks
      tasks: [],
      // Will contain the newly added task
      taskAdd: { title: "", done: "", id: false },
      // Will contain the newly deited task
      taskEdit: { title: "", done: "", id: false },
      // Contains the client-side messages to user
      predefinedMessagesToUser: {
        errorAdd:
          "The text-field cannot be empty while adding tasks, please add a task name",
        errorEdit:
          "The task text-field cannot be empty while editing the task, please add a task name"
      },
      // Changes here will be automatically displayed for the user
      txtLabelOutputToUser: { message: "" },
      csrf: ["csrf", "id"]
    };
  },
  methods: {
    // Gets all the tasks avaible from TaskController
    getTasks() {
      window.axios
        .get("/api/tasks")
        .then(({ data }) => {
          data.forEach(task => {
            this.tasks.push(task);
          });
        })
        .catch(data => {
          this.txtLabelOutputToUser.message = data.message;
        });
    },
    // Posts the new task to the TaskController
    store() {
      if (this.taskAdd.title.trim()) {
        this.isLoading = true;
        this.txtLabelOutputToUser.message = "";
        axios
          .post("/api/tasks", this.taskAdd)
          .then(createdTaskResponse => {
            this.tasks.push(createdTaskResponse.data);
            this.taskAdd.title = "";
            this.isLoading = false;
          })
          .catch(createdTaskResponse => {
            this.isLoading = false;
            this.txtLabelOutputToUser.message = createdTaskResponse.message;
          });
      } else {
        this.txtLabelOutputToUser.message = this.predefinedMessagesToUser.errorAdd;
        this.isLoading = false;
      }
    },
    // Patchs the edited task to the TaskController
    edit(id, title, done) {
      this.isLoading = true;
      this.txtLabelOutputToUser.message = "";
      this.taskEdit.id = id;
      this.taskEdit.done = done;
      this.taskEdit.title = title;

      if (this.taskEdit.title.trim()) {
        axios
          .patch(`/api/tasks/${id}`, this.taskEdit)
          .then(editedTaskResponse => {
            let index = this.tasks.findIndex(task => task.id === id);
            this.tasks[index].title = editedTaskResponse.data.title;
            this.taskEdit.title = "";
            this.taskEdit.id = 0;
            this.taskEdit.done = false;
            this.isLoading = false;
          })
          .catch(editedTaskResponse => {
            this.isLoading = false;
            // Receives error code 404 if fails
            this.txtLabelOutputToUser.message = editedTaskResponse.message;
          });
      } else {
        this.tasks = [];
        this.getTasks();
        this.txtLabelOutputToUser.message = this.predefinedMessagesToUser.errorEdit;
        this.isLoading = false;
      }
    },
    // Delete the task to TaskController that will be removed
    remove(id) {
      this.isLoading = true;
      this.txtLabelOutputToUser.message = "";

      axios
        .delete(`/api/tasks/${id}`)
        .then(deletedTaskResponse => {
          let index = this.tasks.findIndex(task => task.id === id);
          this.tasks.splice(index, 1);
          this.isLoading = false;
        })
        .catch(deletedTaskResponse => {
          this.isLoading = false;
          this.txtLabelOutputToUser.message = deletedTaskResponse.message;
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