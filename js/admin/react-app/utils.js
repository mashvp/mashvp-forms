import PropTypes from 'prop-types';

import { _x, __ } from '../../common/i18n';

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

export const shouldShowLabel = (type) => {
  switch (type) {
    case 'checkbox':
    case 'submit':
    case 'reset':
    case 'button':
      return false;

    default:
      return true;
  }
};

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
  'select',
  'submit',
  'tel',
  'text',
  'textarea',
  'url',
  'hidden',
];

export const additionalTypes = ['message', 'group'];

export const itemTypes = [...inputTypes, ...additionalTypes];

export const attributesPropType = PropTypes.shape({
  label: PropTypes.string,
  name: PropTypes.string,
  defaultValue: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.number,
    PropTypes.bool,
  ]),
  value: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.number,
    PropTypes.bool,
  ]),
  options: PropTypes.objectOf(PropTypes.string),
  placeholder: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  autocomplete: PropTypes.string,
  required: PropTypes.bool,
  optional: PropTypes.bool,
  readonly: PropTypes.bool,
  disabled: PropTypes.bool,
  showLabel: PropTypes.bool,
  htmlType: PropTypes.oneOf(['button', 'submit', 'reset']),
  dateTimeType: PropTypes.oneOf(['datetime-local', 'date', 'time']),
  accept: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.arrayOf(PropTypes.string),
  ]),
  min: PropTypes.number,
  max: PropTypes.number,
  step: PropTypes.number,
  multipleChoice: PropTypes.bool,
  successMessage: PropTypes.string,
  className: PropTypes.string,
}).isRequired;

export const attributeLabels = {
  type: _x('Type', 'Field attribute label', 'mashvp-forms'),
  id: _x('ID (technical identifier)', 'Field attribute label', 'mashvp-forms'),
  label: _x('Label', 'Field attribute label', 'mashvp-forms'),
  name: _x('Technical name', 'Field attribute label', 'mashvp-forms'),
  defaultValue: _x('Default value', 'Field attribute label', 'mashvp-forms'),
  value: _x('Value', 'Field attribute label', 'mashvp-forms'),
  options: _x('Options', 'Field attribute label', 'mashvp-forms'),
  placeholder: _x('Placeholder', 'Field attribute label', 'mashvp-forms'),
  autocomplete: _x('Autocomplete', 'Field attribute label', 'mashvp-forms'),
  required: _x('Required', 'Field attribute label', 'mashvp-forms'),
  optional: _x('Hide field', 'Field attribute label', 'mashvp-forms'),
  readonly: _x('Read-only', 'Field attribute label', 'mashvp-forms'),
  disabled: _x('Disabled', 'Field attribute label', 'mashvp-forms'),
  showLabel: _x('Show label', 'Field attribute label', 'mashvp-forms'),
  accept: _x('Allowed file types', 'Field attribute label', 'mashvp-forms'),
  multipleChoice: _x(
    'Multiple choice',
    'Field attribute label',
    'mashvp-forms'
  ),
  min: _x('Minimum', 'Field attribute label', 'mashvp-forms'),
  max: _x('Maximum', 'Field attribute label', 'mashvp-forms'),
  step: _x('Step', 'Field attribute label', 'mashvp-forms'),
  dateTimeType: _x(
    'Date and/or time type',
    'Field attribute label',
    'mashvp-forms'
  ),
  htmlType: _x(
    'Type',
    'Field attribute label (HTML button type)',
    'mashvp-forms'
  ),
  successMessage: _x('Sucess message', 'Field attribute label', 'mashvp-forms'),
  className: _x(
    'Additional CSS classes',
    'Field attribute label',
    'mashvp-forms'
  ),

  undefined: _x('Unknown attribute', 'Field attribute label', 'mashvp-forms'),
};

export const toolbarItems = [
  {
    type: 'separator',
    label: __('Inputs', 'mashvp-forms'),
  },
  {
    type: 'text',
    label: __('Text', 'mashvp-forms'),
    defaultValue: '',
    placeholder: _x('Text', 'Text field placeholder example', 'mashvp-forms'),
    autocomplete: '',
    required: false,
    className: '',
    name: '',
  },
  {
    type: 'email',
    label: __('Email', 'mashvp-forms'),
    defaultValue: '',
    placeholder: _x(
      'user@example.com',
      'Email field placeholder example',
      'mashvp-forms'
    ),
    autocomplete: 'email',
    required: false,
    className: '',
    name: '',
  },
  {
    type: 'password',
    label: __('Password', 'mashvp-forms'),
    defaultValue: '',
    placeholder: _x(
      '********',
      'Password field placeholder example',
      'mashvp-forms'
    ),
    autocomplete: 'new-password',
    required: false,
    className: '',
    name: '',
  },
  {
    type: 'tel',
    label: __('Phone number', 'mashvp-forms'),
    defaultValue: '',
    placeholder: _x(
      '(541) 754-3010',
      'Phone number field placeholder example',
      'mashvp-forms'
    ),
    autocomplete: 'tel',
    required: false,
    className: '',
    name: '',
  },
  {
    type: 'url',
    label: __('URL', 'mashvp-forms'),
    defaultValue: '',
    placeholder: _x(
      'http://example.com/',
      'URL field placeholder example',
      'mashvp-forms'
    ),
    autocomplete: '',
    required: false,
    className: '',
    name: '',
  },
  {
    type: 'number',
    label: __('Number', 'mashvp-forms'),
    defaultValue: '',
    placeholder: 123,
    autocomplete: '',
    required: false,
    className: '',
    name: '',
  },
  {
    type: 'datetime-local',
    label: __('Date and time', 'mashvp-forms'),
    dateTimeType: 'datetime-local',
    defaultValue: getCurrentFormattedDate(),
    placeholder: '',
    autocomplete: '',
    required: false,
    className: '',
    name: '',
  },
  {
    type: 'select',
    label: _x('Select', 'Form field type', 'mashvp-forms'),
    placeholder: _x(
      'Menu of options',
      'Select field placeholder example',
      'mashvp-forms'
    ),
    options: {},
    defaultValue: '',
    required: false,
    className: '',
    name: '',
  },
  //
  // TODO: File uploads are not currently handled
  //
  // {
  //   type: 'file',
  //   label: __('File upload', 'mashvp-forms'),
  //   accept: '',
  //   required: false,
  //   className: '',
  // },
  {
    type: 'textarea',
    label: __('Text area', 'mashvp-forms'),
    defaultValue: '',
    placeholder: _x(
      'Multi-line text',
      'Text area field placeholder example',
      'mashvp-forms'
    ),
    autocomplete: '',
    required: false,
    className: '',
    name: '',
  },
  {
    type: 'checkbox',
    label: __('Checkbox', 'mashvp-forms'),
    defaultValue: false,
    showLabel: true,
    required: false,
    className: '',
    name: '',
  },
  {
    type: 'range',
    label: __('Range', 'mashvp-forms'),
    defaultValue: 1,
    min: 1,
    max: 10,
    step: 1,
    required: false,
    className: '',
    name: '',
  },

  {
    type: 'separator',
    label: __('Composite', 'mashvp-forms'),
  },
  {
    type: 'choice-list',
    label: __('Choice list', 'mashvp-forms'),
    multipleChoice: false,
    options: {
      one: _x('One', 'Choice list field default option', 'mashvp-forms'),
      two: _x('Two', 'Choice list field default option', 'mashvp-forms'),
      three: _x('Three', 'Choice list field default option', 'mashvp-forms'),
    },
    defaultValue: null,
    required: false,
    className: '',
    name: '',
  },

  // {
  //   type: 'group',
  //   label: __('Full name', 'mashvp-forms'),
  //   className: '',
  //   children: [
  //     {
  //       type: 'text',
  //       attributes: {
  //         label: __('First name', 'mashvp-forms'),
  //         defaultValue: '',
  //         placeholder: _x(
  //           'John',
  //           'First name field placeholder example',
  //           'mashvp-forms'
  //         ),
  //         autocomplete: 'given-name',
  //         required: false,
  //         className: '',
  //       },
  //     },
  //     {
  //       type: 'text',
  //       attributes: {
  //         label: __('Middle name', 'mashvp-forms'),
  //         defaultValue: '',
  //         placeholder: _x(
  //           'H.',
  //           'Middle name field placeholder example',
  //           'mashvp-forms'
  //         ),
  //         autocomplete: 'additional-name',
  //         optional: true,
  //         required: false,
  //         className: '',
  //       },
  //     },
  //     {
  //       type: 'text',
  //       attributes: {
  //         label: __('Last name', 'mashvp-forms'),
  //         defaultValue: '',
  //         placeholder: _x(
  //           'Doe',
  //           'Last name field placeholder example',
  //           'mashvp-forms'
  //         ),
  //         autocomplete: 'family-name',
  //         required: false,
  //         className: '',
  //       },
  //     },
  //   ],
  // },

  {
    type: 'separator',
    label: __('Controls', 'mashvp-forms'),
  },
  {
    _skip: ['label'],
    type: 'submit',
    label: __('Submit form button', 'mashvp-forms'),
    value: _x('Send', 'Submit form field placeholder example', 'mashvp-forms'),
    className: '',
    name: '',
  },
  {
    _skip: ['label'],
    type: 'reset',
    label: __('Reset form button', 'mashvp-forms'),
    value: _x('Reset', 'Reset form field placeholder example', 'mashvp-forms'),
    className: '',
    name: '',
  },
  {
    _skip: ['label'],
    type: 'button',
    label: __('Generic button', 'mashvp-forms'),
    value: _x(
      'Generic button',
      'Generic button field placeholder example',
      'mashvp-forms'
    ),
    htmlType: 'button',
    className: '',
    name: '',
  },

  {
    type: 'separator',
    label: __('Miscellaneous', 'mashvp-forms'),
    className: '',
  },
  {
    _skip: ['label'],
    type: 'message',
    label: __('Text message', 'mashvp-forms'),
    value: _x(
      'You can insert text anywhere in the form',
      'Text message field placeholder example',
      'mashvp-forms'
    ),
    className: '',
  },
  {
    _skip: ['label'],
    type: 'horizontal-separator',
    label: __('Horizontal separator', 'mashvp-forms'),
    value: '',
    className: '',
  },
  {
    _skip: ['label', 'value'],
    type: 'builtin-status-message-zone',
    label: __('Status message zone', 'mashvp-forms'),
    value: _x(
      'Status messages are inserted in this zone if the form runs in AJAX mode.',
      'Builtin message zone placeholder example',
      'mashvp-forms'
    ),
    successMessage: _x(
      'Message sent successfully',
      'Submission default success message',
      'mashvp-forms'
    ),
    className: '',
  },
  {
    _skip: ['placeholder'],
    type: 'hidden',
    label: __('Hidden', 'mashvp-forms'),
    id: '',
    value: '',
    placeholder: __('Hidden value', 'mashvp-forms'),
    className: '',
  },
];

export const autocompleteValues = {
  '': _x('(none)', 'Input autocomplete value', 'mashvp-forms'),
  off: _x('Off', 'Input autocomplete value', 'mashvp-forms'),
  on: _x('On', 'Input autocomplete value', 'mashvp-forms'),
  name: _x('Name', 'Input autocomplete value', 'mashvp-forms'),
  'honorific-prefix': _x(
    'Honorific prefix',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'given-name': _x(
    'Given (first) name',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'additional-name': _x(
    'Additional (middle) name',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'family-name': _x(
    'Family (last) name',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'honorific-suffix': _x(
    'Honorific suffix',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  nickname: _x('Nickname', 'Input autocomplete value', 'mashvp-forms'),
  email: _x('Email address', 'Input autocomplete value', 'mashvp-forms'),
  username: _x('Username', 'Input autocomplete value', 'mashvp-forms'),
  'new-password': _x(
    'New password',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'current-password': _x(
    'Current password',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'one-time-code': _x(
    'One time code',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'organization-title': _x(
    'Job title',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  organization: _x('Company name', 'Input autocomplete value', 'mashvp-forms'),
  'street-address': _x(
    'Street address',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'address-line1': _x(
    'Address line 1',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'address-line2': _x(
    'Address line 2',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'address-line3': _x(
    'Address line 3',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'address-level4': _x(
    'Address administrative level 4',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'address-level3': _x(
    'Address administrative level 3',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'address-level2': _x(
    'Address administrative level 2',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'address-level1': _x(
    'Address administrative level 1',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  country: _x('Country', 'Input autocomplete value', 'mashvp-forms'),
  'country-name': _x(
    'Country name',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'postal-code': _x('Postal code', 'Input autocomplete value', 'mashvp-forms'),
  'home city': _x('City', 'Input autocomplete value', 'mashvp-forms'),
  'cc-name': _x('Credit card name', 'Input autocomplete value', 'mashvp-forms'),
  'cc-given-name': _x(
    'Credit card given name',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'cc-additional-name': _x(
    'Credit card additional name',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'cc-family-name': _x(
    'Credit card family name',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'cc-number': _x(
    'Credit card number',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'cc-exp': _x(
    'Credit card expiration',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'cc-exp-month': _x(
    'Credit card expiration month',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'cc-exp-year': _x(
    'Credit card expiration year',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'cc-csc': _x(
    'Credit card security code',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'cc-type': _x('Credit card type', 'Input autocomplete value', 'mashvp-forms'),
  'transaction-currency': _x(
    'Transaction currency',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'transaction-amount': _x(
    'Transaction amount',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  language: _x('Language', 'Input autocomplete value', 'mashvp-forms'),
  bday: _x('Birthday', 'Input autocomplete value', 'mashvp-forms'),
  'bday-day': _x('Birthday day', 'Input autocomplete value', 'mashvp-forms'),
  'bday-month': _x(
    'Birthday month',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'bday-year': _x('Birthday year', 'Input autocomplete value', 'mashvp-forms'),
  sex: _x('Gender', 'Input autocomplete value', 'mashvp-forms'),
  tel: _x('Telephone', 'Input autocomplete value', 'mashvp-forms'),
  'tel-country-code': _x(
    'Telephone country code',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'tel-national': _x(
    'Telephone national (without country code)',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'tel-area-code': _x(
    'Telephone area code',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'tel-local': _x(
    'Telephone local (without country and area code)',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  'tel-extension': _x(
    'Telephone extension',
    'Input autocomplete value',
    'mashvp-forms'
  ),
  impp: _x('IMPP', 'Input autocomplete value', 'mashvp-forms'),
  url: _x('URL', 'Input autocomplete value', 'mashvp-forms'),
  photo: _x('Photo URL', 'Input autocomplete value', 'mashvp-forms'),
};

export const htmlButtonTypes = {
  button: _x('Button', 'Button HTML type', 'mashvp-forms'),
  submit: _x('Submit', 'Button HTML type', 'mashvp-forms'),
  reset: _x('Reset', 'Button HTML type', 'mashvp-forms'),
};

export const htmlDateTimeTypes = {
  'datetime-local': _x('Date and time', 'DateTime HTML type', 'mashvp-forms'),
  date: _x('Date', 'DateTime HTML type', 'mashvp-forms'),
  time: _x('Time', 'DateTime HTML type', 'mashvp-forms'),
};

// Define deeper translations here as they don't get picked up by the generator
() => {
  _x('Add an option', 'Select field options', 'mashvp-forms');
  _x('Condition', 'Form options', 'mashvp-forms');
  _x('user@example.com', 'Email field placeholder example', 'mashvp-forms');
  _x('IF', 'Form settings email condition', 'mashvp-forms');
  _x('Value', 'Field attribute label', 'mashvp-forms');
  _x('Value', 'Select field option attribute', 'mashvp-forms');
  _x('Label', 'Select field option attribute', 'mashvp-forms');
};
