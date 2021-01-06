import ApplicationController from '../../application-controller';

import {
  attributeLabels,
  autocompleteValues,
  htmlButtonTypes,
} from './react-app/utils';

import { html, safeHtml } from '../../templates';
import { _x } from '../../i18n';

import {
  FIELD_ATTRIBUTES_UPDATED,
  FIELD_SELECTED,
} from '../../pubsub-messages';

export default class extends ApplicationController {
  #fieldData;

  static targets = ['default', 'options', 'fieldsContainer'];

  connect() {
    this.subscribe(FIELD_SELECTED, (_, fieldData) => {
      const { id, selected, type, attributes } = fieldData;

      if (selected) {
        this.#fieldData = fieldData;
        this.showOptions({ id, type, attributes });
      } else {
        this.#fieldData = null;
        this.showDefault();
      }
    });
  }

  get fieldID() {
    return this.#fieldData?.id;
  }

  showDefault() {
    this.optionsTarget.classList.add('hidden');
    this.defaultTarget.classList.remove('hidden');
    this.dropAllOptionFields();
  }

  dropAllOptionFields() {
    while (this.fieldsContainerTarget.firstElementChild) {
      this.fieldsContainerTarget.removeChild(
        this.fieldsContainerTarget.firstElementChild
      );
    }
  }

  makeSelect(attribute, values, currentValue) {
    const optionTemplates = safeHtml`${Object.entries(values)
      .map(([key, label]) =>
        key === currentValue
          ? safeHtml`<option value=${key} selected>${label}</option>`
          : safeHtml`<option value=${key}>${label}</option>`
      )
      .join('')}`;

    return html`
      <select name="${attribute}" data-action="input->form--field-options#save">
        ${optionTemplates}
      </select>
    `;
  }

  createInputForOptionField({ fieldType, name: attributeName, value }) {
    const markup = (() => {
      switch (attributeName) {
        case 'label':
        case 'placeholder':
        case 'className':
        case 'defaultValue':
        case 'value': {
          const inputType = (() => {
            if (attributeName === 'defaultValue') {
              if (fieldType === 'checkbox') {
                return 'checkbox';
              }

              if (fieldType === 'number') {
                return 'number';
              }
            }

            if (fieldType === 'message' && attributeName === 'value') {
              return 'textarea';
            }

            return 'text';
          })();

          if (inputType === 'checkbox') {
            console.log(fieldType, attributeName, value);

            return html`<input
              name="${attributeName}"
              type="${inputType}"
              ${value ? 'checked' : ''}
              data-action="input->form--field-options#save"
            />`;
          }

          if (inputType === 'textarea') {
            return html`<textarea
              name="${attributeName}"
              type="${inputType}"
              data-action="input->form--field-options#save"
            >${value}</textarea>`;
          }

          return html`<input
            name="${attributeName}"
            type="${inputType}"
            value="${value}"
            data-action="input->form--field-options#save"
          />`;
        }

        case 'autocomplete': {
          return this.makeSelect(attributeName, autocompleteValues, value);
        }

        case 'htmlType': {
          return this.makeSelect(attributeName, htmlButtonTypes, value);
        }

        case 'required':
        case 'optional':
        case 'readonly':
        case 'disabled':
        case 'showLabel': {
          return html`<input
            name="${attributeName}"
            type="checkbox"
            ${value ? 'checked' : ''}
            data-action="input->form--field-options#save"
          />`;
        }

        case 'options': {
          return html`
            <div
              class="select-options"
              name="${attributeName}"
              data-controller="form--select-options"
              data-action="select-options:save->form--field-options#save"
              data-form--select-options-initial-value="${btoa(JSON.stringify(value))}"
            >
              <ul
                class="options-list"
                data-target="form--select-options.list"
              ></ul>
              <div class="actions">
                <button class="button" type="button" data-action="form--select-options#add">
                  ${_x('Add an option', 'Select field options', 'mashvp-forms')}
                </button>
              </div>
            </div>
          `;
        }

        default: {
          return html`<p
            data-field="${fieldType}"
            data-attribute="${attributeName}"
          >
            No attribute handler registered
          </p>`;
        }
      }
    })();

    const inputContainer = document.createElement('div');

    inputContainer.classList.add('input-container');
    inputContainer.innerHTML = markup;

    return inputContainer;
  }

  createOptionField({ id, fieldType, name, label, value }) {
    const li = document.createElement('li');
    const labelElement = document.createElement('label');
    const span = document.createElement('span');

    li.classList.add('option-field', name);
    li.dataset.id = id;

    span.textContent = label;

    labelElement.appendChild(span);
    labelElement.appendChild(
      this.createInputForOptionField({ fieldType, name, value })
    );
    li.appendChild(labelElement);

    this.fieldsContainerTarget.appendChild(li);
  }

  showOptions({ id, type, attributes }) {
    this.dropAllOptionFields();
    this.optionsTarget.classList.remove('hidden');
    this.defaultTarget.classList.add('hidden');

    const skipAttributes = ['type', '_skip', ...(attributes._skip || [])];

    Object.entries(attributes).forEach(([key, value]) => {
      if (!skipAttributes.includes(key)) {
        const label = attributeLabels[key] || attributeLabels['undefined'];

        this.createOptionField({
          id,
          name: key,
          fieldType: type,
          label,
          value,
        });
      }
    });
  }

  getInputValue(input) {
    if (input.type === 'checkbox') {
      return input.checked;
    }

    return input.value;
  }

  get serializedAttributes() {
    return [...this.fieldsContainerTarget.querySelectorAll('[name]')].reduce(
      (acc, input) => ({ ...acc, [input.name]: this.getInputValue(input) }),
      {}
    );
  }

  save() {
    this.publish(FIELD_ATTRIBUTES_UPDATED, {
      id: this.fieldID,
      attributes: this.serializedAttributes,
    });
  }
}
