<template>
  <modal
    ref="modal"
    :title="card.name"
    confirmbutton
    closebutton
    confirmtext="Submit Form"
    validate
    v-on:confirm="confirm"
    v-on:validate="validate"
    v-on:clear="clear"
  >
    <div slot="content" class="formschema">
    <transition name="fade" mode="out-in">
    <form-schema-native v-if="!show" ref="formschema" :schema="schema" v-model="model" @submit="validate">
    </form-schema-native>
    </transition>
      <transition name="fade" mode="out-in">
      <div v-if="show" class="download m-top-20">
        <div class="has-text-centered">
          <input :checked="showcheck" type="checkbox" id="cbtest" />
          <label for="cbtest" class="check-box"></label> 
        </div>
       <p class="has-text-centered">This form has been completed successfully.</p>
      </div>
      </transition>
    </div>
    <button slot="button"  v-if="link" class="downloadbutton is-primary button" type="button is-primary" @click.prevent="download">Download <span class="fas fa-download m-left-10"></span></button>
  </modal>
</template>
<script>
import FormSchema from '@formschema/native';
import modal from "./../../base/modal.vue";
import { EventBus } from "./../../eventbus.vue";

export default {
  components: {
    modal: modal,
    "form-schema-native": FormSchema
  },
  data() {
    return {
      show: false,
      showcheck: false,
      card: "",
      schema: {},
      model: {},
      formOptions: {
        validateAfterLoad: true,
        validateAfterChanged: true,
        validateAsync: true
      },
      form: ""
    };
  },
  mounted() {
    EventBus.$on("openform", this.open);
  },
  computed: {
    link() {
      return this.card.status === true;
    }
  },

  methods: {
    validate() {
      this.$refs.modal.confirm();
    },

    confirm() {
      axios
        .put("/api/cards/" + this.card.id, {
          form: JSON.stringify(this.model),
          status: true
        })
        .then(response => {
        this.show = true;
        this.$refs.modal.loader = false;
          setTimeout(() => {
            this.showcheck = true;
          }, 1000);
        });
    },

    clear() {
      this.showcheck = false;
      this.$refs.formschema.load({}, {});
    },

    open(card) {
      this.show = false;      
      this.$refs.modal.open();      
      this.card = card;
      axios.get(card.typeId).then(
        response => {
          this.schema = JSON.parse(response.data.form);
          if (card.form == "") {
            this.$refs.formschema.load(this.schema);
          } else {
            this.model = JSON.parse(card.form);           
            this.$refs.formschema.load(this.schema, this.model);
          }
      })
    },
     download() {
      window.location = "/download?filename=submission-" + this.$root.user.id + "-" + this.card.id + ".pdf";
    }
  }
};
</script>