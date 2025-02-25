import {configureStore} from '@reduxjs/toolkit';
import {combineReducers} from 'redux'


import controlReducer from './control/control.reducers.js';
import appReducer from './app/app.reducers.js';

const rootReducer = combineReducers({
  app : appReducer,
  control : controlReducer,
});

export default function() {
  return configureStore({
    reducer : rootReducer
  });
}
