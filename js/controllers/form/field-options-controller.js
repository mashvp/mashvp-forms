import ApplicationController from '../../application-controller';
import { FIELD_SELECTED } from '../../pubsub-messages';

export default class extends ApplicationController {
  static targets = ['default', 'options'];

  connect() {
    this.subscribe(FIELD_SELECTED, (_, { selected, id, data }) => {
      if (selected) {
        console.log('[field-options] Selected:', id, data);
        this.showOptions();
      } else {
        console.log('[field-options] Selected: NONE');
        this.showDefault();
      }
    });
  }

  showDefault() {
    this.optionsTarget.classList.add('hidden');
    this.defaultTarget.classList.remove('hidden');
  }

  showOptions() {
    this.optionsTarget.classList.remove('hidden');
    this.defaultTarget.classList.add('hidden');
  }
}
