import ApplicationController from '../../../common/application-controller';

export default class extends ApplicationController {
  static targets = ['statusMessage'];

  get ajaxURL() {
    return window?.__mvpf?.adminAjax?.url;
  }

  get formData() {
    return new FormData(this.element);
  }

  get formParams() {
    return new URLSearchParams(this.formData);
  }

  set status(value) {
    if (value) {
      this.data.set('status', value);
    } else {
      this.data.delete('status');
    }
  }

  get status() {
    return this.data.get('status');
  }

  setStatusMessage(status, message = '') {
    this.statusMessageTargets.forEach((target) => {
      const { defaultSuccessMessage } = target.dataset;

      target.dataset.status = status;

      if (status === 'success') {
        target.innerText = defaultSuccessMessage || message;
      } else {
        target.innerText = message;
      }
    });
  }

  sendForm() {
    if (this.status !== 'sending') {
      this.setStatusMessage('sending');
      this.status = 'sending';

      fetch(this.ajaxURL, {
        method: 'POST',
        credentials: 'same-origin',
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded',
          'x-mvpf-method': 'AJAX',
        }),
        body: this.formParams,
      })
        .then((response) => {
          if (response.ok) {
            return response.json();
          }
        })
        .then((json) => {
          const { success, message } = json;

          this.setStatusMessage(success ? 'success' : 'error', message);
        })
        .catch((error) => {
          this.setStatusMessage('error', error);
        })
        .finally(() => {
          this.status = null;

          this.element.dispatchEvent(new Event('mvpf:ajax-submitted'));
        });
    }
  }

  handleSubmit(event) {
    event.preventDefault();
    this.sendForm();
  }
}
