<template>
  <ul class="list-group column is-12">
    <draggable v-model="cards" group="cards" @end="sort">
      <li
        v-for="(card, index) in cards"
        v-bind:class="{ grenit: card.status != 0 }"
        class="task has-text-dark animated fadeIn"
      >
        <label class='formco' @click="clickit(index)">
          <img src='/images/form.png'
            class="formico"
          >
          <span
            v-bind:class="{ underrline: card.status != 0 }"
            class="taskname"
            v-text="card.name"
          ></span>
        </label>
        <span v-if="card.status === true" @click="download(index)" class="fas fa-download is-pulled-right"></span>        
        <span @click="del(index, $event)" class="delete is-pulled-right"></span>
      </li>
    </draggable>
  </ul>
</template>

<script>
import draggable from "vuedraggable";
import { EventBus } from "./../eventbus.vue";
export default {
  components: {
    draggable: draggable
  },
  data: function() {
    return {
      cards: ""
    };
  },
  props: {
    listid: Number
  },

  methods: {
    clickit(index) {
      EventBus.$emit("openform", this.cards[index]);
    },
    del(index, event) {
      event.target.classList.add("is-loading");
      event.target.classList.remove("delete");
      axios.delete("/api/cards/" + this.cards[index].id).then(response => {
        this.$delete(this.cards, index);
        this.sort();
      });
    },
    sort() {
      this.cards.forEach((element, index) => {
        axios.put("/api/cards/" + this.cards[index].id, {
          position: index
        });
      });
    },
    download(index) {
      window.location = "/download?filename=submission-" + this.$root.user.id + "-" + this.cards[index].id + ".pdf";
    }
  },

  mounted() {
    axios.get("/api/lists/" + this.listid + "/cards").then(response => {
      this.cards = response.data;
    });
  }
};
</script>
