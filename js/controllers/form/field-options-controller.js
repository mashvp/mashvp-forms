import ApplicationController from '../../application-controller';

import {
  attributeLabels,
  autocompleteValues,
  htmlButtonTypes,
  htmlDateTimeTypes,
} from '../../react-app/utils';

import { html, safeHtml } from '../../templates';
import { _x } from '../../i18n';

import {
  FIELD_ATTRIBUTES_UPDATED,
  FIELD_SELECTED,
  FIELD_OPTIONS_REBUILD_REQUESTED,
} from '../../pubsub-messages';

export default class extends ApplicationController {
  #fieldData;
  #rebuildSubscription;

  static targets = ['default', 'options', 'fieldsContainer'];

  connect() {
    this.subscribe(FIELD_SELECTED, (_, fieldData) => {
      const { id, selected, type, attributes } = fieldData;

      if (selected) {
        this.#fieldData = fieldData;
        this.showOptions({ id, type, attributes });

        this.#rebuildSubscription = this.subscribe(
          FIELD_OPTIONS_REBUILD_REQUESTED,
          (_, { attributes: newAttributes }) => {
            this.showOptions({ id, type, attributes: newAttributes });
          }
        );
      } else {
        this.#fieldData = null;
        this.showDefault();

        if (this.#rebuildSubscription) {
          this.unsubscribe(this.#rebuildSubscription);
          this.#rebuildSubscription = null;
        }
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

  makeSelect(attribute, values, currentValue, additionalActions = []) {
    const optionTemplates = safeHtml`${Object.entries(values)
      .map(([key, label]) =>
        key === currentValue
          ? safeHtml`<option value=${key} selected>${label}</option>`
          : safeHtml`<option value=${key}>${label}</option>`
      )
      .join('')}`;

    const actions = ['input->form--field-options#save', ...additionalActions]
      .filter((v) => v)
      .join(' ');

    return html`
      <select name="${attribute}" data-action="${actions}">
        ${optionTemplates}
      </select>
    `;
  }

  createInputForOptionField({
    fieldType,
    name: attributeName,
    value,
    attributes,
  }) {
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

              if (fieldType === 'datetime-local') {
                return attributes?.dateTimeType;
              }
            }

            if (fieldType === 'message' && attributeName === 'value') {
              return 'textarea';
            }

            return 'text';
          })();

          if (inputType === 'checkbox') {
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

        case 'dateTimeType': {
          return this.makeSelect(attributeName, htmlDateTimeTypes, value, [
            'input->form--field-options#rebuild',
          ]);
        }

        case 'required':
        case 'optional':
        case 'readonly':
        case 'disabled':
        case 'showLabel':
        case 'multipleChoice': {
          return html`<input
            name="${attributeName}"
            type="checkbox"
            ${value ? 'checked' : ''}
            data-action="input->form--field-options#save"
          />`;
        }

        case 'min':
        case 'max':
        case 'step': {
          return html`<input
            name="${attributeName}"
            type="number"
            value="${value}"
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
              data-form--select-options-initial-value="${JSON.stringify(value)}"
            >
              <ul
                class="options-list"
                data-target="form--select-options.list"
              ></ul>
              <div class="actions">
                <button
                  class="button"
                  type="button"
                  data-action="form--select-options#add"
                >
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

  createOptionField({ id, fieldType, name, label, value, attributes }) {
    const li = document.createElement('li');
    const container = document.createElement('div');
    const span = document.createElement('span');

    container.classList.add('option-field--container');

    li.classList.add('option-field', name);
    li.dataset.id = id;

    span.textContent = label;

    const input = this.createInputForOptionField({
      fieldType,
      name,
      value,
      attributes,
    });

    container.appendChild(span);
    container.appendChild(input);

    li.appendChild(container);

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

          attributes,
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

  rebuild() {
    this.publish(FIELD_OPTIONS_REBUILD_REQUESTED, {
      id: this.fieldID,
      attributes: this.serializedAttributes,
    });
  }

  save() {
    this.publish(FIELD_ATTRIBUTES_UPDATED, {
      id: this.fieldID,
      attributes: this.serializedAttributes,
    });
  }
}
