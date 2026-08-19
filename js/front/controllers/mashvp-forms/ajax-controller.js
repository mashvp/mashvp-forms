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
        target.innerHTML = defaultSuccessMessage || message;
      } else {
        target.innerHTML = message;
      }
    });
  }

  get nonceFieldName() {
    return window?.__mvpf?.nonceFieldName;
  }

  get nonceField() {
    const name = this.nonceFieldName;

    return name ? this.element.querySelector(`[name="${name}"]`) : null;
  }

  async refreshNonce() {
    const field = this.nonceField;
    const fieldName = this.nonceFieldName;

    if (!field || !fieldName) {
      return;
    }

    try {
      const response = await fetch(this.ajaxURL, {
        method: 'POST',
        credentials: 'same-origin',
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded',
        }),
        body: new URLSearchParams({ action: 'mvpf_refresh_nonce' }),
      });

      if (!response.ok) {
        return;
      }

      const json = await response.json();
      const nonce = json[fieldName];

      if (nonce) {
        field.value = nonce;
      }
    } catch {
      // Fail silently, submission proceeds with the existing field value.
    }
  }

  async sendForm() {
    if (this.status !== 'sending') {
      this.setStatusMessage('sending');
      this.status = 'sending';

      await this.refreshNonce();

      fetch(this.ajaxURL, {
        method: 'POST',
        credentials: 'same-origin',
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Mvpf-Method': 'AJAX',
        }),
        body: this.formParams,
      })
        .then((response) => {
          if (response.ok) {
            return response.json();
          }

          // TODO: Translate this message
          throw new Error('Error while submitting form');
        })
        .then((json) => {
          const { success, message } = json;

          this.setStatusMessage(success ? 'success' : 'error', message);
          this.dispatch('ajax-submitted', { success, message });
        })
        .catch((error) => {
          this.setStatusMessage('error', error);
          this.dispatch('ajax-submitted', { success: false, error });
        })
        .finally(() => {
          this.status = null;
        });
    }
  }

  handleSubmit(event) {
    event.preventDefault();
    this.sendForm();
  }

  dispatch(name, data = {}) {
    const event = new Event(`mvpf:${name}`);

    event.detail = data;

    this.element.dispatchEvent(event);
  }
}
