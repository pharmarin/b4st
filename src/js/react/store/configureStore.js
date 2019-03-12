import { applyMiddleware, createStore } from 'redux';
import apiActions from './reducers/apiReducer'
import logger from 'redux-logger'

export default createStore(
  apiActions,
  applyMiddleware(logger)
)
