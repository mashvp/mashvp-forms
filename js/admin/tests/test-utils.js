import escapeHtml from 'lodash.escape';

export const init = (callback) =>
  document.addEventListener('DOMContentLoaded', callback);

export const clamp = (n, min, max) => Math.min(Math.max(n, min), max);

export const isNullOrUndefined = (value) => value == undefined;

export const createTaggedTemplateString = ({
  separator = '',
  escape = false,
}) => (strings, ...values) =>
  strings
    .map(
      (string, i) =>
        string +
        (isNullOrUndefined(values[i])
          ? ''
          : escape
            ? escapeHtml(values[i])
            : values[i])
    )
    .join('')
    .trim()
    .split('\n')
    .map((line) => line.trim())
    .filter((line) => line.length)
    .join(separator);

export const html = createTaggedTemplateString({
  separator: '\n',
  escape: true,
});
