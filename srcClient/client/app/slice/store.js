import {configureStore} from '@reduxjs/toolkit';
import {combineReducers} from 'redux'


import userReducer from './user/user.reducers.js';
import controlReducer from './control/control.reducers.js';
import appReducer from './app/app.reducers.js';

const rootReducer = combineReducers({
  app : appReducer,
  user: userReducer,
  control : controlReducer,
});



function createStore() {
  return configureStore({
    reducer : rootReducer
  });
}

export default createStore();