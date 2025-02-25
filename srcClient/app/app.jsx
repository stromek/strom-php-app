import React from 'react';

import { Provider } from 'react-redux'
import configureStore from "./slice/store.js";

import LayoutComponent from './page/layout/layout.container'


import 'normalize.css'
import '../css/app.scss'

const store = configureStore();

const App = ({ children }) => {
  return (
    <Provider store={store}>
      <LayoutComponent />
    </Provider>
  );
};

export default App;