import React from 'react';
import PropTypes from 'prop-types';
import { Container } from 'react-smooth-dnd';

import Row from './Row';

const Stage = ({ rows, onDrop, onItemDrop, getItemPayload }) => (
  <div className="stage--container">
    <Container
      onDrop={onDrop}
      lockAxis="y"
      dragHandleSelector=".row--handle"
      groupName="group-stage"
      dragClass="dragging"
      dropClass="dropping"
      onDragStart={() => document.body.classList.add('dnd-dragging')}
      onDragEnd={() => document.body.classList.remove('dnd-dragging')}
      dropPlaceholder={{
        showOnTop: true,
        animationDuration: 150,
        className: 'drop-preview row',
      }}
    >
      {rows.map(({ id, items }) => (
        <Row
          key={id}
          id={id}
          items={items}
          onDrop={onItemDrop}
          getItemPayload={getItemPayload}
        />
      ))}
    </Container>
  </div>
);

Stage.propTypes = {
  rows: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)).isRequired,
  onDrop: PropTypes.func.isRequired,
  onItemDrop: PropTypes.func.isRequired,
  getItemPayload: PropTypes.func.isRequired,
};

export default Stage;
