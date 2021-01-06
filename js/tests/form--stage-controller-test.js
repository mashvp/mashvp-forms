import ReactDOM from 'react-dom';
import { Application } from 'stimulus';

import FormStageController from 'form/stage-controller';

import { html } from './test-utils';

describe('FormStageController', () => {
  const originalRender = ReactDOM.render;
  const originalGetElement = window.document.getElementById;

  beforeEach(() => {
    window.document.getElementById = () => true;
    ReactDOM.render = jest.fn();

    const initialData = {
      valid: true,
      rows: [
        { valid: true, id: 'mock-1', items: [] },
        { valid: true, id: 'mock-2', items: [] },
      ],
    };

    document.body.innerHTML = html`
      <div class="mashvp-forms form-fields--wrapper">
        <div class="stage-wrapper" data-controller="form--stage">
          <div class="stage--root" data-target="form--stage.root"></div>

          <input
            type="hidden"
            name="_mashvp-forms__fields"
            id="_mashvp-forms__fields"
            data-target="form--stage.output"
            value="${JSON.stringify(initialData)}"
            readonly
          />
        </div>
      </div>
    `;

    const application = Application.start();

    application.register('form--stage', FormStageController);
  });

  afterAll(() => {
    window.document.getElementById = originalGetElement;
    ReactDOM.render = originalRender;
  });

  describe('#connect', () => {
    it('Mounts the React app on connect', () => {
      expect(ReactDOM.render).toHaveBeenCalled();
    });
  });

  describe('#initialData', () => {
    it('Gets the initial data', () => {
      const wrapper = document.querySelector('.stage-wrapper');
      const { formStageController } = wrapper;

      expect(formStageController.initialData).toHaveProperty('valid', true);
    });
  });

  describe('#initalRows', () => {
    it('Gets the initial rows', () => {
      const wrapper = document.querySelector('.stage-wrapper');
      const { formStageController } = wrapper;

      expect(formStageController.initalRows).toEqual(
        expect.arrayContaining([
          expect.objectContaining({
            valid: true,
          }),
        ])
      );
    });
  });
});
