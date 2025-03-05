import {APP_STATE} from "./app.constants";

const getInitialState = function() {
  return {
    state : APP_STATE.INITIALIZING
  }
};

export default function(state = getInitialState(), {type, payload}) {
  switch(type) {
    case APP_STATE.LOADING:
      return {...state, state : APP_STATE.LOADING};

    case APP_STATE.DONE:
      return {...state, state : APP_STATE.DONE};
  }

  return state;
}
