import React from 'react';
import { Container } from 'react-smooth-dnd';
import { v4 as generateUUID } from 'uuid';

import Item from './Item';

import { toolbarItems } from './utils';

const Toolbar = () => {
  const getChildPayload = (index) => ({
    id: generateUUID(),
    attributes: toolbarItems[index],
  });

  return (
    <div className="stage--toolbar">
      <div className="stage--toolbar-dnd">
        <Container
          behaviour="copy"
          groupName="group-row"
          dragClass="dragging"
          dropClass="dropping"
          nonDragAreaSelector=".no-drag"
          getChildPayload={getChildPayload}
        >
          {toolbarItems.map((attributes) => {
            const { type } = attributes;

            if (type === 'separator') {
              const { label } = attributes;

              return (
                <div key={`separator-${label}`} className="separator no-drag">
                  <span>{label}</span>
                </div>
              );
            }

            return (
              <Item
                key={type}
                id={type}
                type={type}
                attributes={attributes}
                inert
              />
            );
          })}
        </Container>
      </div>

      <div className="stage--rubbish-bin dashicons">
        <Container
          behaviour="drop-zone"
          dragClass="dragging"
          dropClass="dropping dismissing"
          shouldAcceptDrop={() => true}
        />
      </div>
    </div>
  );
};

Toolbar.propTypes = {};

export default Toolbar;
