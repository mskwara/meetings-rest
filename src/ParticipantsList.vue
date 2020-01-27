<template>
  <div>
    <ol class="list-group">
      <li :key="person.id" v-for="person in list" class="list-group-item">
        {{ person.firstname }}
        {{ person.lastname }}
        <button type="button" class="btn btn-danger delete" @click="deleteUser(person)">Delete</button>
      </li>
    </ol>
  </div>
</template>

<script>
  export default {
    props: ['list'],
    methods: {
      deleteUser(person){
        this.$emit('deleted', person);
      }
    }
  };
</script>

<style>
.delete {
  float: right;
}
</style>
