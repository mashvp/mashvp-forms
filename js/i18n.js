const noop = (str) => str;

const I18n = window.wp?.i18n || {
  __: noop,
  _x: noop,
  _n: noop,
  _nx: noop,
  sprintf: noop,
};

const { __: wp__, _x: wp_x, _n: wp_n, _nx: wp_nx, sprintf: wp_sprintf } = I18n;

export const __ = (string, domain = 'mashvp-forms') => wp__(string, domain);

export const _x = (string, context, domain = 'mashvp-forms') =>
  wp_x(string, context, domain);

export const _n = (singular, plural, number, domain = 'mashvp-forms') =>
  wp_n(singular, plural, number, domain);

export const _nx = (
  singular,
  plural,
  number,
  context,
  domain = 'mashvp-forms'
) => wp_nx(singular, plural, number, context, domain);

export const sprintf = wp_sprintf;
