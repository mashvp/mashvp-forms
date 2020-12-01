import PropTypes from 'prop-types';

import { _x, __ } from '../../../i18n';

export const applyDrag = (arr, dragResult) => {
  const { removedIndex, addedIndex, payload } = dragResult;
  if (removedIndex === null && addedIndex === null) return arr;

  const result = [...arr];
  let itemToAdd = payload;

  if (removedIndex !== null) {
    itemToAdd = result.splice(removedIndex, 1)[0];
  }

  if (addedIndex !== null) {
    result.splice(addedIndex, 0, itemToAdd);
  }

  return result;
};

export const generateItems = (count, creator) => {
  const result = [];
  for (let i = 0; i < count; i++) {
    result.push(creator(i));
  }
  return result;
};

/**
 * Returns the current datetime formatted as yyyy-MM-ddThh:mm
 */
export const getCurrentFormattedDate = () => {
  const now = new Date();

  // Who tf made getYear return only the last two digits
  const year = now.getFullYear();

  // Who tf made this 0-indexed
  const month = (now.getMonth() + 1).toString().padStart(2, '0');

  // Who tf made getDay return the day of the week
  const day = now.getDate().toString().padStart(2, '0');

  const hours = now.getHours().toString().padStart(2, '0');
  const minutes = now.getMinutes().toString().padStart(2, '0');

  return `${year}-${month}-${day}T${hours}:${minutes}`;
};

export const noop = () => {};

export const inputTypes = [
  'button',
  'checkbox',
  'color',
  'datetime-local',
  'email',
  'file',
  'number',
  'password',
  'radio',
  'range',
  'reset',
  'submit',
  'tel',
  'text',
  'textarea',
  'url',
];

export const additionalTypes = ['message', 'group'];

export const itemTypes = [...inputTypes, ...additionalTypes];

export const attributesPropType = PropTypes.shape({
  label: PropTypes.string,
  name: PropTypes.string,
  type: PropTypes.oneOf(itemTypes).isRequired,
}).isRequired;

export const toolbarItems = [
  {
    type: 'separator',
    label: __('Inputs', 'mashvp-forms'),
  },
  {
    type: 'text',
    label: __('Text', 'mashvp-forms'),
    defaultValue: _x('Text', 'Text field default value', 'mashvp-forms'),
  },
  {
    type: 'email',
    label: __('Email', 'mashvp-forms'),
    defaultValue: _x(
      'user@example.com',
      'Email field default value',
      'mashvp-forms'
    ),
  },
  {
    type: 'password',
    label: __('Password', 'mashvp-forms'),
    defaultValue: _x(
      'password',
      'Password field default value',
      'mashvp-forms'
    ),
  },
  {
    type: 'tel',
    label: __('Phone number', 'mashvp-forms'),
    defaultValue: _x(
      '(541) 754-3010',
      'Phone number field default value',
      'mashvp-forms'
    ),
  },
  {
    type: 'url',
    label: __('URL', 'mashvp-forms'),
    defaultValue: _x(
      'http://example.com/',
      'URL field default value',
      'mashvp-forms'
    ),
  },
  {
    type: 'number',
    label: __('Number', 'mashvp-forms'),
    defaultValue: 123,
  },
  {
    type: 'datetime-local',
    label: __('Date and time', 'mashvp-forms'),
    defaultValue: getCurrentFormattedDate(),
  },
  {
    type: 'file',
    label: __('File upload', 'mashvp-forms'),
  },
  {
    type: 'textarea',
    label: __('Text area', 'mashvp-forms'),
    defaultValue: _x(
      'Multi-line text',
      'Text area field default value',
      'mashvp-forms'
    ),
  },

  {
    type: 'separator',
    label: __('Composite', 'mashvp-forms'),
  },
  {
    type: 'group',
    label: __('Full name', 'mashvp-forms'),
    children: [
      {
        type: 'text',
        label: __('First name', 'mashvp-forms'),
        defaultValue: _x(
          'John',
          'First name field default value',
          'mashvp-forms'
        ),
        autocomplete: 'given-name',
      },
      {
        type: 'text',
        label: __('Middle name', 'mashvp-forms'),
        defaultValue: _x(
          'H.',
          'Middle name field default value',
          'mashvp-forms'
        ),
        autocomplete: 'additional-name',
        optional: true,
      },
      {
        type: 'text',
        label: __('Last name', 'mashvp-forms'),
        defaultValue: _x(
          'Doe',
          'Last name field default value',
          'mashvp-forms'
        ),
        autocomplete: 'family-name',
      },
    ],
  },

  {
    type: 'separator',
    label: __('Controls', 'mashvp-forms'),
  },
  {
    type: 'submit',
    label: __('Submit form button', 'mashvp-forms'),
    defaultValue: _x('Send', 'Submit form field default value', 'mashvp-forms'),
  },
  {
    type: 'reset',
    label: __('Reset form button', 'mashvp-forms'),
    defaultValue: _x('Reset', 'Reset form field default value', 'mashvp-forms'),
  },
  {
    type: 'button',
    label: __('Generic button', 'mashvp-forms'),
    defaultValue: _x(
      'Generic button',
      'Generic button field default value',
      'mashvp-forms'
    ),
  },

  {
    type: 'separator',
    label: __('Custom content', 'mashvp-forms'),
  },
  {
    type: 'message',
    label: __('Text message', 'mashvp-forms'),
    defaultValue: _x(
      'You can insert text anywhere in the form',
      'Text message field default value',
      'mashvp-forms'
    ),
  },
];
