import React from 'react';
import ReactDOM from 'react-dom';

import applicationController from '../../application-controller';
import { FIELDS_UPDATED } from '../../pubsub-messages';
import App from './components/App';

export default class extends applicationController {
  static targets = ['root', 'output'];

  connect() {
    ReactDOM.render(<App />, this.rootTarget);

    this.subscribe(FIELDS_UPDATED, (_, { json }) => {
      this.outputTarget.value = json;
    });
  }

  disconnect() {
    super.disconnect();

    ReactDOM.unmountComponentAtNode(this.rootTarget);
  }
}
