<script>
  
export const store = new Vuex.Store({
  state: {
    user : null,
    boards: null,
    lists: null,
    documentTypes: null,
    documents: null
  },
  
  getters: {
    getUser: (state, getters) => {
      return state.user;
    },
    getBoards: (state) => {
      return state.boards;
    },
    getLists: (state) => {
      return state.lists;
    },
    getDocumentTypes: (state) => {
      return state.documentTypes;
    },
    getDocuments: (state) => {
      return state.documents;
    }
  },

  mutations: {
    setUser : (state,payload) => {
      state.user = payload
    },
    setBoards: (state,payload) => {
      state.boards = payload;
    },
    addBoard: (state, payload) => {
      state.boards.push(payload);
    },
    setLists: (state,payload) => {
      state.lists = payload
    },
    addList: (state, payload) => {
      state.lists.push(payload);
    },
    setDocumentTypes: (state, payload) => {
      state.documentTypes = payload
    },
    addDocumentType: (state, payload) => {
      state.documentTypes.push(payload);
    },
    setDocuments: (state, payload) => {
      state.documents = payload
    },
    addDocument: (state, payload) => {
      state.document.push(payload);
    },
  },
  
  actions:{
   GET_USER : async (context,payload) => {
      let { data } = await axios.get('/api/users')
      context.commit('SET_USER',data)
   },
   GET_BOARDS : async (context,payload) => {
      let { data } = await axios.get('/api/boards')
      context.commit('SET_BOARDS',data)
   },
   GET_LISTS : async (context,payload) => {
      let { data } = await axios.get('/api/boards/' + payload.id + '/lists')
      context.commit('SET_LISTS',data)
   },
   GET_DOCUMENT_TYPES: async (context, payload) => {
      let {data} = await axios.get('/api/document_types');
      context.commit('SET_DOCUMENT_TYPES', data)
    },
   SAVE_BOARD : async (context,payload) => {
      axios.post('/api/boards', {
          name: payload.title,
          description: payload.desc,
          created: new Date(),
          modified: new Date(),
          total: 0,
          completed: 0,
          userid: "/api/users/1"
        }).then(response => {
        context.dispatch('GET_BOARDS');      
      })
   },
   EDIT_BOARD : async (context, payload) => {
     axios.put('/api/boards/' + payload.id, {
       name: payload.title,
       description: payload.description,
       modified: new Date()
     }).then(response => {
         context.dispatch('GET_BOARDS');
     });
   },
    DELETE_BOARD: async (context, payload) => {
      axios.delete('/api/boards/' + payload.id).then(response => {
        context.dispatch('GET_BOARDS');        
      });
    },
   
   SAVE_LIST : async (context,payload) => {
      axios.post('/api/lists', {
          boardId: "/api/boards/" + payload.boardid,
          name: "New List",
          position: payload.length
        }).then(response => {
        context.dispatch('GET_LISTS', payload.boardid);      
      })
   },
   EDIT_LIST : async (context, payload) => {
     axios.put('/api/lists/' + payload.id, {
       name: payload.title,
       description: payload.description,
       modified: new Date()
     }).then(response => {
         context.dispatch('GET_LISTS', payload.boardid);
     });
   },
    DELETE_LIST: async (context, payload) => {
      axios.delete('/api/lists/' + payload.id).then(response => {
        context.dispatch('GET_LISTS');        
      });
    }
 }
})
export default {}
</script>