import ApplicationController from '../../../common/application-controller';

export default class extends ApplicationController {
  #submitButtons;

  static targets = ['recaptcha'];

  connect() {
    window[this.callbackName] = (token) => {
      this.callbackHandler(token);
    };

    this.recaptchaTarget.dataset.callback = this.callbackName;

    this.submitButtons.forEach((button) => {
      this.bind(button, 'click', (event) => this.handleBeforeSubmit(event));
    });

    this.bind(this.element, 'mvpf:ajax-submitted', () => {
      const { grecaptcha } = window;

      delete this.element.dataset.token;

      if (grecaptcha) {
        grecaptcha.reset();
      }
    });
  }

  get formID() {
    return this.element.dataset.formId;
  }

  get callbackName() {
    return `mvpf__recaptcha_callback__${this.formID}`;
  }

  get submitButtons() {
    return (this.#submitButtons ||= [
      ...this.element.querySelectorAll('[type="submit"]'),
    ]);
  }

  handleBeforeSubmit(event) {
    const { grecaptcha } = window;

    if (grecaptcha && !this.element.dataset.token) {
      event.preventDefault();
      grecaptcha.execute();
    }
  }

  callbackHandler(token) {
    this.element.dataset.token = token;

    // Manually resubmit
    if (this.submitButtons && this.submitButtons.length > 0) {
      this.submitButtons[this.submitButtons.length - 1].click();
    }
  }
}
