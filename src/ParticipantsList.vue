<template>
  <div>
    <ol class="list-group">
      <li :key="person.id" v-for="person in list" class="list-group-item">
        {{ person.firstname }}
        {{ person.lastname }}
      </li>
    </ol>
  </div>
</template>

<script>
  export default {
    props: ['list'],
  };
</script>
