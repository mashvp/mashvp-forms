/* eslint-disable no-undef */

import { Application } from '@hotwired/stimulus';
import { definitionsFromContext } from '@hotwired/stimulus-webpack-helpers';

const initStimulus = () => {
  const application = Application.start();

  const context = require.context(
    './controllers',
    true,
    /(?<!\.disabled)\.js$/
  );

  application.load(definitionsFromContext(context));
};

initStimulus();
