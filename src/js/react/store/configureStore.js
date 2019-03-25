import { applyMiddleware, combineReducers, createStore } from 'redux';
import apiReducer from './reducers/apiReducer';
import appReducer from './reducers/appReducer';
import logger from 'redux-logger';

const rootReducer = combineReducers({
  apiReducer,
  appReducer
})

export default createStore(
  rootReducer,
  applyMiddleware(logger)
)
