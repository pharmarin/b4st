import { Provider } from 'react-redux';
import { createStore } from 'redux';
import Store from './store/configureStore';
import App from './components/App';

ReactDOM.render(
  <Provider store={ Store }>
    <App />
  </Provider>,
  document.getElementById('archive-aromatherapie')
);
