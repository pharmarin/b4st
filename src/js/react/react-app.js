import { Provider } from 'react-redux';
import { render } from 'react-dom';
import { createStore } from 'redux';
import store from './store/configureStore';
import App from './components/App';

render(
  <Provider store={store}>
    <App />
  </Provider>,
  document.getElementById('archive-aromatherapie')
)
