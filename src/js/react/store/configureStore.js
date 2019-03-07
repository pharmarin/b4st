import { createStore } from 'redux';
import apiActions from './reducers/apiReducer'

export default createStore(apiActions)
