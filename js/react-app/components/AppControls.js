import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../i18n';

const AppControls = ({ addRow }) => (
  <div className="stage--controls">
    <button
      type="button"
      className="button button-large"
      onClick={addRow}
    >
      {__('Add row', 'mashvp-forms')}
    </button>
  </div>
);

AppControls.propTypes = {
  addRow: PropTypes.func.isRequired,
};

export default AppControls;
