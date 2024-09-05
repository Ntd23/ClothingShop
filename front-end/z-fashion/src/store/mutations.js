// import router from '../router';
//auth
export const LOGIN=(state, user, token)=> {
  state.isLoggedIn = true;
  state.user= user;
  state.token= token;
  localStorage.setItem('user', JSON.stringify(user));
  // localStorage.setItem('expirationTokenTime', expirationTokenTime);
};

export const LOGOUT=(state)=> {
  state.isLoggedIn= false;
  state.user= '';
  state.token= '';
  localStorage.removeItem('user');
  localStorage.removeItem('token');
}

export const ADMIN_LOGIN=(state, user)=> {
  localStorage.setItem('user', JSON.stringify(user));
  state.user= user;
}
//end auth

//message
export const SET_MESSAGE=(state, message_success)=> {
  state.message_success= message_success;
}

export const CLEAR_MESSAGE=(state)=> {
  state.message_success= null
}

//result search
export const SET_RESULT_SEARCH= (state, results_filter)=> {
  state.results_search= results_filter;
}
//result filter
export const SET_RESULT_FILTER= (state, results_filter)=> {
  state.results_filter= results_filter;
}

