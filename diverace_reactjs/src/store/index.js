import { createStore, applyMiddleware, compose  } from "redux";
import thunk from 'redux-thunk';
import { persistStore , persistReducer } from "redux-persist";
import storage from 'redux-persist/lib/storage'  // defaults to localStorage for web
import { createLogger } from 'redux-logger'
import rootReducer from "./reducers/index";

export default function configureStore(initialState) {
  const logger = createLogger()
  const persistConfig = {
    key: 'root',
    storage,
    // blacklist:['SummaryReducer']  // which reducer want to not store
    //whitelist: ['authType'] // which reducer want to store
  }
 
  const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
  const persistedReducer = persistReducer(persistConfig, rootReducer)

  const store = createStore(
    persistedReducer,
    initialState,
    composeEnhancers(
      applyMiddleware(thunk)
    )
    //window.__REDUX_DEVTOOLS_EXTENSION__&& window.__REDUX_DEVTOOLS_EXTENSION__() || compose
  );
  const persistor = persistStore(store);

  return { store, persistor };
}
