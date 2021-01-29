import React from 'react';
import PropTypes from 'prop-types';

const Output = ({ name, value }) => (
  <textarea name={name} id={name} value={value} readOnly />
);

Output.propTypes = {
  name: PropTypes.string,
  value: PropTypes.string,
};

Output.defaultProps = {
  name: '_mashvp-forms__fields',
  value: '',
};

export default Output;
