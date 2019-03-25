import { Provider } from 'react-redux';
import { render } from 'react-dom';
import { createStore } from 'redux';
import Store from './store/configureStore';
import App from './components/App';

render(
  <Provider store={ Store }>
    <App />
  </Provider>,
  document.getElementById('archive-aromatherapie')
)
