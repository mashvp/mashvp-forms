import ApplicationController from '../../application-controller';

import { html } from '../../templates';
import { _x } from '../../i18n';

export default class extends ApplicationController {
  static targets = ['list'];

  connect() {
    Object.entries(this.initialValue).forEach(([value, label]) =>
      this.add({ value, label })
    );

    Object.defineProperty(this.element, 'name', {
      get() {
        return this.getAttribute('name');
      },
    });

    Object.defineProperty(this.element, 'value', {
      get: () => {
        return this.serialize();
      },
    });
  }

  get initialValue() {
    const raw = this.data.get('initial-value');

    if (raw) {
      try {
        return JSON.parse(atob(raw));
      } catch (err) {
        console.warn(err);

        return {};
      }
    }

    return {};
  }

  get newOption() {
    const container = document.createElement('div');

    container.innerHTML = html`
      <li class="option">
        <label class="value">
          <span>${_x('Value', 'Select field option attribute', 'mashvp-forms')}</span>
          <input
            type="text"
            data-name="value"
            data-action="input->form--select-options#save"
          />
        </label>

        <label class="label">
          <span>${_x('Label', 'Select field option attribute', 'mashvp-forms')}</span>
          <input
            type="text"
            data-name="label"
            data-action="input->form--select-options#save"
          />
        </label>

        <button
          type="button"
          class="button button-link button--delete dashicons dashicons-trash"
          data-action="form--select-options#remove"
        ></button>
      </li>
    `;

    return container.firstElementChild;
  }

  serialize() {
    return [...this.listTarget.querySelectorAll('li.option')].reduce(
      (acc, option) => {
        const valueInput = option.querySelector('input[data-name="value"]');
        const labelInput = option.querySelector('input[data-name="label"]');

        return { ...acc, [valueInput.value]: labelInput.value };
      },
      {}
    );
  }

  applyInitialDataToLastOption(value, label) {
    const lastOption = this.listTarget.lastElementChild;

    if (lastOption) {
      const valueInput = lastOption.querySelector('input[data-name="value"]');
      const labelInput = lastOption.querySelector('input[data-name="label"]');

      valueInput.value = value;
      labelInput.value = label;
    }
  }

  add({ value, label } = { value: null, label: null }) {
    console.log('add???');

    this.listTarget.appendChild(this.newOption);

    if (value || label) {
      this.applyInitialDataToLastOption(value, label);
    }
  }

  remove(event) {
    console.log('remove');
    const { target } = event;
    const { parentElement: item } = target;

    this.listTarget.removeChild(item);
    this.save();
  }

  save() {
    const event = new Event('select-options:save');

    this.element.dispatchEvent(event);
  }
}
