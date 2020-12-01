import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

import { inputTypes, noop } from '../utils';

const InputField = ({ type, attributes }) => {
  const { name, defaultValue } = attributes;

  const classes = classNames({
    'button button-large': ['submit', 'reset', 'button'].includes(type),
    'button-primary': type === 'submit',
  });

  if (type === 'button') {
    return (
      <button className={classes} name={name} type="button" tabIndex="-1">
        {defaultValue}
      </button>
    );
  }

  if (type === 'textarea') {
    return (
      <textarea
        classNames={classes}
        name={name}
        value={defaultValue}
        tabIndex="-1"
        onChange={noop}
      />
    );
  }

  return (
    <input
      className={classes}
      type={type}
      name={name}
      value={defaultValue}
      tabIndex="-1"
      onChange={noop}
    ></input>
  );
};

InputField.propTypes = {
  type: PropTypes.string.isRequired,
  attributes: PropTypes.shape({
    type: PropTypes.oneOf(inputTypes).isRequired,
    name: PropTypes.string,
    defaultValue: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
    autocomplete: PropTypes.string,
  }).isRequired,
};

InputField.defaultProps = {
  attributes: { defaultValue: '', autocomplete: null },
};

export default InputField;
