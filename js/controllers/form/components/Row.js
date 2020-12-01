import React from 'react';
import PropTypes from 'prop-types';
import { Container, Draggable } from 'react-smooth-dnd';
import classNames from 'classnames';

import Item from './Item';

import { attributesPropType } from './utils';
import { __ } from '../../../i18n';

const Row = ({ id, items, onDrop, getItemPayload }) => (
  <Draggable className="row">
    <div
      className={classNames('row--inner', { empty: items.length === 0 })}
      data-message-on-empty={__('Drop items here', 'mashvp-forms')}
    >
      <div className="row--handle dashicons"></div>
      <Container
        className="row--contents"
        orientation="horizontal"
        groupName="group-row"
        dragClass="dragging"
        dropClass="dropping"
        onDrop={(event) => onDrop(id, event)}
        getChildPayload={(index) => getItemPayload(id, index)}
        dropPlaceholder={{
          showOnTop: true,
          animationDuration: 150,
          className: 'drop-preview item',
        }}
      >
        {items.map(({ id: id, attributes }) => (
          <Item key={id} id={id} attributes={attributes} />
        ))}
      </Container>
    </div>
  </Draggable>
);

Row.propTypes = {
  id: PropTypes.string.isRequired,
  items: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.string.isRequired,
      attributes: attributesPropType,
    })
  ).isRequired,
  onDrop: PropTypes.func.isRequired,
  getItemPayload: PropTypes.func.isRequired,
};

export default Row;
