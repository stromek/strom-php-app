import {USER_AUTH} from "./user.actions";

const getInitialState = function() {
  return {
    user : {
      name: null,
      code: null,
      hash: null,
      emailAddress: null,
      avatarURL: null,
      createdAt: {
        "date": null,
        "timezone_type": 0,
        "timezone": null
      }
    }
  }
};

export default function(state = getInitialState(), {type, payload}) {
  switch(type) {
    case USER_AUTH:
      console.info("[user] setting current user");
      return {...state, user : {...state.user, ...payload}};
  }

  return state;
}
