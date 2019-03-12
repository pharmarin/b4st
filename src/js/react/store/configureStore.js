import { applyMiddleware, createStore } from 'redux';
import apiActions from './reducers/apiReducer'
import logger from 'redux-logger'

const store = createStore(
  apiActions,
  undefined,
  applyMiddleware(logger)
)

export default store
