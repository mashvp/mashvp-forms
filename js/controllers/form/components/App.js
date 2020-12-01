import React, { useCallback, useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import { publish } from 'pubsub-js';
import { v4 as generateUUID } from 'uuid';

import Stage from './Stage';
import Toolbar from './Toolbar';
import AppControls from './AppControls';

import { applyDrag, attributesPropType } from './utils';
import { FIELDS_UPDATED } from '../../../pubsub-messages';

const App = ({ initialData }) => {
  const [rows, setRows] = useState(initialData);

  const serializeData = () => JSON.stringify({ rows });

  const handleOnDrop = useCallback((event) => setRows(applyDrag(rows, event)), [
    rows,
  ]);

  const handleOnItemDrop = useCallback(
    (rowID, dropResult) => {
      if (dropResult.removedIndex !== null || dropResult.addedIndex !== null) {
        const row = rows.find(({ id }) => id === rowID);

        if (row) {
          const { items } = row;
          const index = rows.indexOf(row);
          const newRow = { ...row, items: applyDrag(items, dropResult) };

          rows.splice(index, 1, newRow);

          setRows([...rows]);
        }
      }
    },
    [rows]
  );

  const getItemPayload = useCallback(
    (rowID, index) => {
      return rows.find((p) => p.id === rowID)?.items?.[index];
    },
    [rows]
  );

  const addRow = useCallback(() => {
    setRows([...rows, { id: generateUUID(), items: [] }]);
  }, [rows]);

  useEffect(() => {
    publish(FIELDS_UPDATED, { json: serializeData() });
  }, [rows]);

  return (
    <>
      <Toolbar />

      <Stage
        rows={rows}
        onDrop={handleOnDrop}
        onItemDrop={handleOnItemDrop}
        getItemPayload={getItemPayload}
      />

      <AppControls addRow={addRow} />
    </>
  );
};

App.propTypes = {
  initialData: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.string.isRequired,
      items: PropTypes.arrayOf(
        PropTypes.shape({
          id: PropTypes.string.isRequired,
          attributes: attributesPropType,
        })
      ).isRequired,
    })
  ),
};

App.defaultProps = {
  initialData: [],
};

export default App;
