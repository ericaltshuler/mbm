<script>
getters: {
  user: (state, getters) => {
    return state.user;
  },
  boards: (state, getters) => {
    return state.boards;
  },
  lists: (state, getters) => {
    return state.lists;
  },
  documentTypes: (state, getters) => {
    return state.documentTypes
  },
  documents: (state, getters) => {
    return state.documents
  }
},
export default {}
</script>