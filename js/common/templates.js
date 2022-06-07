import escapeHtml from 'lodash.escape';
import isPlainObject from 'lodash.isplainobject';

export const clamp = (n, min, max) => Math.min(Math.max(n, min), max);

export const isNullOrUndefined = (value) => value == undefined;

export const isTaggedTemplateString = (value) =>
  value &&
  isPlainObject(value) &&
  '__templateString' in value &&
  value.__templateString;

export const isTaggedTemplateStringArray = (array) =>
  array &&
  Array.isArray(array) &&
  array.every((value) => isTaggedTemplateString(value));

export const createTaggedTemplateString =
  ({ separator = '', escape = false }) =>
    (strings, ...values) => ({
      __templateString: true,
      escape,

      string: strings
        .map((string, i) => {
          if (isNullOrUndefined(values[i])) {
            return string;
          }

          const value = values[i];

          if (isTaggedTemplateStringArray(value)) {
            return (
              string +
            value.reduce((acc, subString) => {
              if (subString.escape) {
                return acc + escapeHtml(subString.string);
              }

              return acc + subString.string;
            }, '')
            );
          }

          if (isTaggedTemplateString(value)) {
            if (!value.escape) {
              return string + value.string;
            }
          }

          if (escape) {
            return string + escapeHtml(value);
          }

          return string + value;
        })
        .join('')
        .trim()
        .split('\n')
        .map((line) => line.trim())
        .filter((line) => line.length)
        .join(separator),

      toString() {
        return this.string;
      },

      safe() {
        this.escape = false;

        return this;
      },

      toFragment() {
        const template = document.createElement('template');

        template.innerHTML = this.string;

        return template.content.cloneNode(true);
      },
    });

export const css = createTaggedTemplateString({
  separator: ' ',
  escape: false,
});

export const html = createTaggedTemplateString({
  separator: '\n',
  escape: true,
});

export const safeHtml = createTaggedTemplateString({
  separator: '\n',
  escape: false,
});
