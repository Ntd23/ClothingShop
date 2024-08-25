export const LOGIN= (state)=> {
  state.isAuthenticated= true; //ON
}

export const LOGOUT= (state)=> {
  state.isAuthenticated= false; //OFF
}
