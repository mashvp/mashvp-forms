import React, { useCallback, useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import { Draggable } from 'react-smooth-dnd';
import { publish } from 'pubsub-js';

import InputField from './Items/InputField';

import { attributesPropType, inputTypes, shouldShowLabel } from '../utils';
import { useSubscriber } from './hooks';
import { FIELD_SELECTED } from '../../pubsub-messages';
import { __ } from '../../i18n';

const renderField = (type, { id, attributes }) => {
  if (inputTypes.includes(type)) {
    return <InputField type={type} id={id} attributes={attributes} />;
  }

  switch (type) {
    case 'group': {
      const { children } = attributes;

      return (
        <div className="field--group">
          {children.map(({ type, attributes }, index) => {
            const classes = classNames('field--group-item', {
              optional: attributes.optional,
            });

            return (
              <div key={`child-${index}`} className={classes}>
                {shouldShowLabel(type) && (
                  <span className="field--group-item-label">
                    {attributes.label}
                  </span>
                )}

                {renderField(type, { id, attributes })}
              </div>
            );
          })}
        </div>
      );
    }

    case 'choice-list': {
      const { multipleChoice, options, defaultValue } = attributes;
      const type = multipleChoice ? 'checkbox' : 'radio';

      const defaultValues = (defaultValue || '')
        .split(',')
        .map((v) => v.trim());

      return (
        <ul className="choice-list">
          {Object.entries(options).map(([name, label], index) => (
            <li key={`${id}-${name}-${index}`}>
              <InputField
                type={type}
                id={id}
                attributes={{
                  label,
                  showLabel: true,
                  defaultValue: defaultValues.includes(name),
                }}
              />
            </li>
          ))}
        </ul>
      );
    }

    case 'message': {
      return (
        <p className="message">{attributes.value || attributes.defaultValue}</p>
      );
    }

    case 'horizontal-separator': {
      return (
        <hr
          className="horizontal-separator"
          data-value={attributes.value || attributes.defaultValue}
        />
      );
    }

    default: {
      return (
        <p className="error">{__('Unknown field type', 'mashvp-forms')}</p>
      );
    }
  }
};

const Item = ({ id, attributes, inert }) => {
  const [selected, setSelected] = useState(false);
  const { label, type } = attributes;

  if (!inert) {
    // Unselect on delete
    useEffect(() => () => publish(FIELD_SELECTED, { id, selected: false }), []);

    // React to selection change
    useSubscriber(FIELD_SELECTED, (_, { id: fieldID, selected }) => {
      setSelected(selected && fieldID === id);
    });

    // Notify app of selection change
    const selectItem = useCallback(() => {
      if (selected) {
        publish(FIELD_SELECTED, { id, selected: false });
      } else {
        publish(FIELD_SELECTED, { id, selected: true, type, attributes });
      }
    }, [selected]);

    const classes = classNames('row--item-contents', 'label', {
      selected: selected,
    });

    const innerClasses = classNames('row--item-contents--inner', {
      'no-label': 'showLabel' in attributes && !attributes.showLabel,
    });

    return (
      <Draggable key={id} className="row--item">
        <div className={classes} onClick={selectItem}>
          <div className={innerClasses}>
            {shouldShowLabel(type) && (
              <p className="item--label">
                <span className="item--label-name">{label}</span>

                {attributes.required && (
                  <span className="item--label-required">*</span>
                )}
              </p>
            )}

            <div className="item--preview">
              {renderField(type, { id, attributes })}
            </div>
          </div>
        </div>
      </Draggable>
    );
  }

  // Inert item in toolbar
  return (
    <Draggable key={id} className="row--item inert">
      <div className="row--item-contents label inert">
        <div className="row--item-contents--inner">
          <span className="item--handle name">{label}</span>

          <div className="item--preview">
            {renderField(type, { id, attributes })}
          </div>
        </div>
      </div>
    </Draggable>
  );
};

Item.propTypes = {
  id: PropTypes.string.isRequired,
  attributes: attributesPropType,

  inert: PropTypes.bool,
};

Item.defaultProps = {
  inert: false,
};

export default Item;
