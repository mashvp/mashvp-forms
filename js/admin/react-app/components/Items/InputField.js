import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

import { attributesPropType, inputTypes, noop } from '../../utils';
import isPlainObject from 'lodash.isplainobject';

const InputField = ({ type, id, attributes }) => {
  const name = id;

  const {
    label,
    defaultValue,
    value,
    placeholder,
    options,
    dateTimeType,
    min,
    max,
    step,
  } = attributes;

  const currentValue = value || defaultValue || '';

  const classes = classNames({
    'button button-large': ['submit', 'reset', 'button'].includes(type),
    'button-primary': type === 'submit',
  });

  if (type === 'button') {
    return (
      <button className={classes} name={name} type="button" tabIndex="-1">
        {currentValue}
      </button>
    );
  }

  if (type === 'textarea') {
    return (
      <textarea
        className={classes}
        name={name}
        value={currentValue}
        placeholder={placeholder}
        tabIndex="-1"
        onChange={noop}
      />
    );
  }

  if (type === 'select') {
    return (
      <select className={classes} value={defaultValue || ''} readOnly>
        {placeholder && (
          <option value="" disabled hidden>
            {placeholder}
          </option>
        )}

        {isPlainObject(options) &&
          Object.entries(options).map(([value, label]) => (
            <option key={value} value={value}>
              {label}
            </option>
          ))}
      </select>
    );
  }

  if (['checkbox', 'radio'].includes(type)) {
    const { showLabel } = attributes;

    return (
      <div className={`${type}-wrapper`}>
        <input
          className={classes}
          type={type}
          name={name}
          checked={currentValue}
          tabIndex="-1"
          onChange={noop}
        ></input>

        {showLabel && <span className={`${type}-label`}>{label}</span>}
      </div>
    );
  }

  if (type === 'datetime-local') {
    return (
      <input
        className={classes}
        type={dateTimeType || type}
        name={name}
        value={currentValue}
        placeholder={placeholder}
        tabIndex="-1"
        onChange={noop}
      ></input>
    );
  }

  if (type === 'range') {
    return (
      <input
        className={classes}
        type={type}
        name={name}
        value={currentValue}
        min={min}
        max={max}
        step={step}
        placeholder={placeholder}
        tabIndex="-1"
        onChange={noop}
      ></input>
    );
  }

  return (
    <input
      className={classes}
      type={type}
      name={name}
      value={currentValue}
      placeholder={placeholder}
      tabIndex="-1"
      onChange={noop}
    ></input>
  );
};

InputField.propTypes = {
  type: PropTypes.oneOf(inputTypes).isRequired,
  id: PropTypes.string.isRequired,
  attributes: attributesPropType,
};

InputField.defaultProps = {
  attributes: { label: '', defaultValue: '', autocomplete: null },
};

export default InputField;
