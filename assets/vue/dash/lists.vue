<template>
  <draggable v-model="lists" @end="sort">
    <div class="lixx column is-4" v-for="(list, index) in lists" :key="list.id">
      <div class="list column is-12 animated fadeInUp" @animationend="animateit">
        <header>
          <p
            v-text="list.name"
            @click="active"
            @keydown.enter.prevent="update(index, $event)"
            @blur="update(index, $event)"
            contenteditable
            spellcheck="false"
          ></p>
          <span
            v-show="!loader"
            @click="remove(index)"
            class="delete head is-pulled-right"
          ></span>
          <span v-show="loader" class="is-loading is-pulled-right"></span>
        </header>
        <list ref="list" :listid="list.id"></list>
        <form
          @submit.prevent="add(index)"
          method="post"
          class="column addtask"
        >
          <div class="field has-addons">
            <div class="control w-100">
              <dropdown
                v-on:selected="select"
                :options="doctypes"
                :disabled="false"
                name="form"
                :maxItem="10"
                placeholder="Add a form to this loan..">
              </dropdown>
            </div>
            <div class="control">
              <button
                @click.prevent="add(index)"
                type="submit"
                class="addcard button is-primary"
              >
                <span class="fa fa-plus"></span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </draggable>
</template>

<script>
import list from "./list";
import draggable from "vuedraggable";
import dropdown from 'vue-simple-search-dropdown';
import { EventBus } from "./../eventbus.vue";

export default {
  components: {
    list: list,
    draggable: draggable,
    dropdown: dropdown
  },

  data() {
    return {
      lists: "",
      loader: false,
      card: [],
      doctypes: '',
      sel: ""
    };
  },

  props: {
    boardid: String
  },
  methods: {
    select(sel) {
      this.sel = sel;
    },
    add(index) {
      if (this.sel == "") return false;
      let val = index + 1;
      document
        .querySelector(".lixx:nth-child(" + val + ") .addcard")
        .classList.add("is-loading");
      axios
        .post("/api/cards", {
          listId: "/api/lists/" + this.lists[index].id,
          typeId: "/api/document_types/" + this.sel.id,
          userId: this.$root.user.id,
          name: this.sel.name,
          position: this.$refs.list[index].cards.length,
          status: false,
          fileLink: "",
          form: ""
        })
        .then(response => {
          this.$refs.list[index].cards.push(response.data);
          document
            .querySelector(".lixx:nth-child(" + val + ") .addcard")
            .classList.remove("is-loading");
          document.querySelector(
            ".lixx:nth-child(" + val + ") form .input"
          ).value = "";
        });
    },
    create() {
      axios
        .post("/api/lists", {
          boardId: "/api/boards/" + this.boardid,
          name: "New List",
          position: this.lists.length
        })
        .then(response => {
          this.lists.push(response.data);
          EventBus.$emit("listcreated");
        });
    },

    active(event) {
      event.target.classList.add("active");
    },

    update(index, event) {
      event.target.blur();
      event.target.classList.remove("active");
      let src = event.target.innerText;
      if (src != this.lists[index].name) {
        this.lists[index].name = src;
        this.loader = true;
        axios
          .put("/api/lists/" + this.lists[index].id, { name: src })
          .then(response => {
            this.loader = false;
          });
      }
    },

    remove(index) {
      this.loader = true;
      axios.delete("/api/lists/" + this.lists[index].id).then(response => {
        this.$delete(this.lists, index);
        this.loader = false;
        this.sort();
      });
    },

    sort() {
      this.lists.forEach((element, index) => {
        axios.put("/api/lists/" + this.lists[index].id, {
          position: index
        });
      });
    },
    animateit(event) {
      event.target.classList.remove('animated');
    }
  },

  mounted: function() {
    axios.get("/api/document_types").then(response => {
      this.doctypes = response.data;
    });
    axios.get("/api/boards/" + this.boardid + "/lists").then(response => {
      this.lists = response.data;
      this.lists.forEach(item => {
        this.card.push("");
      });
    });
  }
};
</script>
