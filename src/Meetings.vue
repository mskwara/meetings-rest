<template>
  <div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand">Participants list</a>
      </div>
    </nav>
    <div class="container mt-3">
      <div>
        <div v-if="people.length > 0">
          <h2>The people already signed in:</h2>
          <participants-list :list="people" @deleted="deleteParticipant($event)"></participants-list>
        </div>
        <em v-else>Sorry, nobody is here :-(</em>
        <div class="lds-hourglass" v-if="listLoading == true"></div>
      </div>
      <div class="row">
        <div class="col-md-6 offset-md-3 mt-3">
          <div class="card">
            <h4 class="card-header">Add new participant</h4>
            <new-participant-form class="card-body" @added="addNewParticipant($event)"></new-participant-form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import ParticipantsList from "./ParticipantsList.vue";
  import NewParticipantForm from "./NewParticipantForm.vue";

  export default {
    components: {ParticipantsList, NewParticipantForm},
    data() {
      return {
        people: [],
        osoba: {},
        listLoading: true
      };
    },
    methods: {
      addNewParticipant(participant) {
        this.$http.post('add', participant);
        this.getListData();
      },
      deleteParticipant(participant) {
        this.$http.delete('delete/'+participant.id);
        this.getListData();
      },
      getListData(){
        this.$http.get('participants').then(response => {
          this.people = response.body;
          this.listLoading = false;
        });
      },
    },
    mounted() {
      this.getListData();

      var myInterval = setInterval(this.getListData, 1000);
    }
  };
</script>
<style>
.lds-hourglass {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-hourglass:after {
  content: " ";
  display: block;
  border-radius: 50%;
  width: 0;
  height: 0;
  margin: 8px;
  box-sizing: border-box;
  border: 32px solid #cef;
  border-color: #cef transparent #cef transparent;
  animation: lds-hourglass 1.2s infinite;
}
@keyframes lds-hourglass {
  0% {
    transform: rotate(0);
    animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
  }
  50% {
    transform: rotate(900deg);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }
  100% {
    transform: rotate(1800deg);
  }
}

</style>
