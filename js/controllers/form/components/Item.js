import React, { useCallback, useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import { Draggable } from 'react-smooth-dnd';
import { publish } from 'pubsub-js';

import InputField from './Items/InputField';

import { attributesPropType, inputTypes } from './utils';
import { useSubscriber } from './hooks';
import { FIELD_SELECTED } from '../../../pubsub-messages';
import { __ } from '../../../i18n';

const renderField = (type, { attributes }) => {
  if (inputTypes.includes(type)) {
    return <InputField type={type} attributes={attributes} />;
  }

  switch (type) {
    case 'group': {
      const { children } = attributes;

      return (
        <div className="field--group">
          {children.map((attributes, index) => {
            const classes = classNames('field--group-item', {
              optional: attributes.optional,
            });

            return (
              <div key={`child-${index}`} className={classes}>
                <span className="field--group-item-label">
                  {attributes.label}
                </span>
                {renderField(attributes.type, { attributes })}
              </div>
            );
          })}
        </div>
      );
    }

    case 'message':
      return (
        <p className="message">{attributes.value || attributes.defaultValue}</p>
      );
    default:
      return (
        <p className="error">{__('Unknown field type', 'mashvp-forms')}</p>
      );
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
        publish(FIELD_SELECTED, { id, selected: true, attributes });
      }
    }, [selected]);

    const classes = [
      'row--item-contents',
      'label',
      selected ? 'selected' : null,
    ]
      .filter((v) => v)
      .join(' ');

    return (
      <Draggable key={id} className="row--item">
        <div className={classes} onClick={selectItem}>
          <div className="row--item-contents--inner">
            <span className="item--handle name">{label}</span>
            <div className="item--preview">
              {renderField(type, { id, attributes })}
            </div>
          </div>
        </div>
      </Draggable>
    );
  }

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
