import {createStore} from "vuex";
import * as mutations from './mutations';
import * as actions from './actions';

export default createStore({
  state: {
    //auth
    isLoggedIn: !!localStorage.getItem('token'),
    token: localStorage.getItem('token') || '',
    user: JSON.parse(localStorage.getItem('user')) || '',
    message_success: null,
    //end auth,

    //result search or filter
    results_search: [],
    results_filter: [],
  },
  mutations,
  actions,
})
