import React from 'react';

import { Provider } from 'react-redux'
import store from "./slice/store.js";

import LayoutComponent from './page/layout/layout.container'

import 'normalize.css'
import '../css/normalize.scss'
import '../css/app.scss'
import {APP_POST_MESSAGE_ACCEPT} from "./slice/app/app.constants";


if(window && 'onmessage' in window) {
  window.onmessage = (e) => {
    const payload = e.data || {}

    if(payload['source'] !== APP_POST_MESSAGE_ACCEPT.SOURCE) {
      return;
    }

    if(payload['payload'] && payload['type']) {
      let action = {
        type: payload.type,
        payload: payload.payload,
      };

      store.dispatch(action);
    }else {
      console.warn("Missing key 'payload' or 'type' in postMessage payload");
    }
  }
}


const App = ({ children }) => {
  return (
    <Provider store={store}>
      <LayoutComponent />
    </Provider>
  );
};

export default App;