import ApplicationController from '../../../common/application-controller';

import { _x } from '../../../common/i18n';
import { html, safeHtml } from '../../../common/templates';

export default class extends ApplicationController {
  static targets = ['output', 'list'];

  connect() {
    this.initialData.forEach(({ email, condition }) => {
      if (email) {
        const item = this.add({}, email);

        if (condition && 'attribute' in condition) {
          this.addCondition(
            { target: item.querySelector('button.button--add') },
            condition
          );
        }
      }
    });

    this.bind(document.getElementById('post'), 'submit', () => {
      this.outputTarget.value = this.serialize();
    });
  }

  get initialData() {
    try {
      return JSON.parse(this.data.get('initial-data') || '[]');
    } catch (err) {
      console.warn('Cannot decode initial data:', err);

      return [];
    }
  }

  get items() {
    return [...this.listTarget.querySelectorAll('.email-item')];
  }

  getItemCondition(item) {
    const condition = item.querySelector('li.condition');

    if (condition) {
      const attribute = condition.querySelector('.attribute')?.value;
      const operator = condition.querySelector('.operator')?.value;
      const value = condition.querySelector('.value')?.value;

      return { attribute, operator, value };
    }

    return null;
  }

  serialize() {
    return JSON.stringify(
      this.items
        .map((item) => {
          const email = item.querySelector('[data-name="email"]')?.value;
          const condition = this.getItemCondition(item);

          return { email, condition };
        })
        .filter(({ email }) => email && email.length > 0)
    );
  }

  /* eslint-disable indent */
  get newItemContent() {
    return html`
      <li class="email-item">
        <div class="actions">
          <button
            type="button"
            class="button button-link button--delete dashicons dashicons-trash"
            data-action="form--email-settings#delete"
          ></button>
        </div>

        <input
          data-name="email"
          type="email"
          autocomplete="email"
          placeholder="${_x(
            'user@example.com',
            'Email field placeholder example',
            'mashvp-forms'
          )}"
        />

        <ul class="conditions">
          <header>
            <button
              type="button"
              class="button button--add"
              data-action="form--email-settings#addCondition"
            >
              <span class="label"
                >${_x('Condition', 'Form options', 'mashvp-forms')}</span
              >
              <span class="dashicons dashicons-plus"></span>
            </button>
          </header>
        </ul>
      </li>
    `;
  }
  /* eslint-enable indent */

  get newItem() {
    const container = document.createElement('div');

    container.innerHTML = this.newItemContent;

    return container.firstElementChild;
  }

  get fieldsData() {
    const container = document.getElementById('_mashvp-forms__fields');

    if (!container) return null;

    return JSON.parse(container.value);
  }

  get fieldsItems() {
    const data = this.fieldsData;

    if (!data) return [];

    return data.rows
      .reduce((acc, { items }) => [...acc, ...items], [])
      .map(({ id, attributes }) => ({ id, name: attributes.label }));
  }

  truncateString(str, length) {
    if (str.length <= length) {
      return str;
    }

    return `${str.substring(0, length)}…`;
  }

  /* eslint-disable indent */
  get attributeOptions() {
    return safeHtml`
      ${this.fieldsItems
        .map(
          ({ id, name }) =>
            html`<option value="${id}">
              ${this.truncateString(name, 42)}
            </option>`.string
        )
        .join('')}
    `;
  }
  /* eslint-enable indent */

  get newConditionContent() {
    return html`
      <li class="condition">
        <span class="if"
          >${_x('IF', 'Form settings email condition', 'mashvp-forms')}</span
        >

        <select class="attribute">
          ${this.attributeOptions}
        </select>

        <select class="operator">
          <option value="==">=</option>
          <option value="!=">≠</option>
          <option value="LIKE">LIKE</option>
        </select>

        <input
          type="text"
          class="value"
          placeholder="${_x('Value', 'Field attribute label', 'mashvp-forms')}"
        />

        <button
          type="button"
          class="button button-link button--delete dashicons dashicons-minus"
          data-action="form--email-settings#deleteCondition"
        ></button>
      </li>
    `;
  }

  get newCondition() {
    const container = document.createElement('div');

    container.innerHTML = this.newConditionContent;

    return container.firstElementChild;
  }

  add(_, initialValue = null) {
    const item = this.newItem;

    this.listTarget.appendChild(item);

    if (initialValue) {
      const emailInput = item.querySelector('[data-name="email"]');

      if (emailInput) {
        emailInput.value = initialValue;
      }
    }

    return item;
  }

  delete(event) {
    const { target } = event;
    const item = target?.parentElement?.parentElement;

    if (item) {
      this.listTarget.removeChild(item);
    }
  }

  setCondition(condition, { attribute, operator, value }) {
    const attributeInput = condition.querySelector('.attribute');
    const operatorInput = condition.querySelector('.operator');
    const valueInput = condition.querySelector('.value');

    if (attributeInput && operatorInput && valueInput) {
      attributeInput.value = attribute;
      operatorInput.value = operator;
      valueInput.value = value;
    }
  }

  addCondition(event, initialData = null) {
    const { target } = event;
    const container = target?.parentElement?.parentElement;

    if (container) {
      const last = container.lastElementChild;

      if (last && last.classList.contains('condition')) {
        return false;
      }

      const condition = this.newCondition;

      container.appendChild(condition);

      if (initialData) {
        this.setCondition(condition, initialData);
      }
    }
  }

  deleteCondition(event) {
    const { target } = event;
    const item = target?.parentElement;

    if (item && item.classList.contains('condition')) {
      item.parentElement.removeChild(item);
    }
  }
}
