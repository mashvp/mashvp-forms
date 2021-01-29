import React from 'react';
import ReactDOM from 'react-dom';

import ApplicationController from '../../../common/application-controller';
import App from '../../react-app/components/App';

import { FIELDS_UPDATED } from '../../../common/pubsub-messages';

export const renderToDOM = (root, props = {}) => {
  if (root) {
    ReactDOM.render(<App {...props} />, root);
  }
};

export default class extends ApplicationController {
  static targets = ['root', 'output'];

  connect() {
    renderToDOM(this.rootTarget, { initialData: this.initalRows });

    this.subscribe(FIELDS_UPDATED, (_, { json }) => {
      this.outputTarget.value = json;
    });
  }

  disconnect() {
    super.disconnect();

    ReactDOM.unmountComponentAtNode(this.rootTarget);
  }

  get initialData() {
    try {
      return JSON.parse(this.outputTarget.value);
    } catch (error) {
      return { rows: [] };
    }
  }

  get initalRows() {
    return this.initialData?.rows;
  }
}
