import React from "react";
import ReactDOM from "react-dom";
import "./index.css";
import "bootstrap/dist/css/bootstrap.css";
import App from "./App";
import { Provider } from "react-redux";
import configureStore from "./store/index";
import { PersistGate } from 'redux-persist/integration/react'

const initialState = {
  user: {
    isAuthenticated: false,
    profile: {},
    authError:""
  },
};
const {store, persistor} = configureStore(initialState);

ReactDOM.render(
  <React.StrictMode>
    <Provider store={store}>
      <PersistGate loading={null} persistor={persistor}>
         <App />
      </PersistGate>
    </Provider>
  </React.StrictMode>,
  document.getElementById("root")
);
