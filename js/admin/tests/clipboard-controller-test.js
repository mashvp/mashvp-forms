import { Application } from 'stimulus';
import ClipboardController from 'clipboard-controller';

import { html } from './test-utils';

describe('ClipboardController', () => {
  describe('#copy', () => {
    beforeEach(() => {
      document.body.innerHTML = html`
        <input
          type="text"
          id="input"
          value="[mashvp-form id=123]"
          data-controller="clipboard"
          data-action="click->clipboard#copy blur->clipboard#removeCopiedStyled"
          readonly
        />
      `;

      const application = Application.start();

      application.register('clipboard', ClipboardController);
      document.execCommand = jest.fn();
    });

    it('Copies the contents of the input to the clipboard on click', () => {
      const input = document.getElementById('input');

      input.click();

      expect(document.execCommand).toHaveBeenCalledWith('copy');
    });
  });
});
