import {APP_AUTH} from "./app.actions.js";

const getInitialState = function() {
  return {
    auth : {
      // Unused
      key : '',
      secret: ''
    }
  }
};

export default function(state = getInitialState(), {type, payload}) {

  switch(type) {
    case APP_AUTH:
      console.info("[auth] setting clientSecret");
      return {...state, auth: {...state.auth, secret: payload.secret}};
  }

  return state;
}
