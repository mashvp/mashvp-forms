import React, { useState } from 'react';
import classNames from 'classnames';
import { Container } from 'react-smooth-dnd';
import { v4 as generateUUID } from 'uuid';

import Item from './Item';

import { toolbarItems } from '../utils';
import { __ } from '../../../../i18n';

const Toolbar = () => {
  const [open, setOpen] = useState(false);

  const getChildPayload = (index) => ({
    id: generateUUID(),
    attributes: toolbarItems[index],
  });

  const toolbarClasses = classNames('stage--toolbar', { open });
  const dndClasses = classNames('stage--toolbar-dnd', { open });
  const rubbishClasses = classNames('stage--rubbish-bin', 'dashicons', {
    open,
  });

  const buttonIconClasses = classNames('dashicons', {
    'dashicons-arrow-right': !open,
    'dashicons-arrow-up': open,
  });

  return (
    <div className="stage--toolbar-wrapper">
      <div className={toolbarClasses}>
        <div className={dndClasses}>
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
      </div>

      <div className={rubbishClasses}>
        <Container
          behaviour="drop-zone"
          dragClass="dragging"
          dropClass="dropping dismissing"
          shouldAcceptDrop={() => true}
        />
      </div>

      <button
        type="button"
        className="stage--toolbar-toggle"
        onClick={() => setOpen(!open)}
      >
        <span className={buttonIconClasses} />
        <span>{__('Fields list', 'mashvp-forms')}</span>
      </button>
    </div>
  );
};

Toolbar.propTypes = {};

export default Toolbar;
