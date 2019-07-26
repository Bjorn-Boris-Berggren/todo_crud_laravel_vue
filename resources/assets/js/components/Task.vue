/**
* Task.vue
* This represents each task in the table. The user can remove, edit and mark tasks 
*    
*/
<template>
  <tr>
    <td>{{task.created_at}}</td>
    <td>
      <input type="text" v-model="task.title" />
    </td>
    <td>
      <button @click="remove" class="btn btn-pill btn-danger">Remove</button>
    </td>
    <td>
      <button @click="edit" class="btn btn-pill btn-warning">Edit</button>
    </td>
    <td>
      <input type="checkbox" @change="edit" v-model="task.done" />
    </td>
  </tr>
</template>

<script>
export default {
  data() {
    return {};
  },

  methods: {
    // Deletes the task
    remove() {
      this.$emit("delete", this.task.id);
    },
    // Edits the task
    edit() {
      this.$emit("update", this.task.id, this.task.title, this.task.done);
    }
  },

  props: ["task"]
};
</script>

<style>
</style>