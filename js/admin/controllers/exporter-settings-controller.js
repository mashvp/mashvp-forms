import escapeHtml from 'lodash.escape';
import ApplicationController from '../../common/application-controller';

import { html } from '../../common/templates';

export default class extends ApplicationController {
  static targets = ['formatSelect', 'container'];

  connect() {
    super.connect();

    this.later(() => {
      if (this.hasFormatSelectTarget && this.formatSelectTarget.value) {
        this.updateTemplate({
          currentTarget: { value: this.formatSelectTarget.value },
        });
      }
    }, 100);
  }

  clearSettings() {
    [...this.element.querySelectorAll('tr.exporter-setting')].forEach((row) =>
      row.remove()
    );
  }

  updateTemplate(event) {
    const { currentTarget } = event;

    if (currentTarget && currentTarget.value) {
      this.clearSettings();
      this.fetchAndCreateSettingsFor(currentTarget.value);
    }
  }

  fetchAndCreateSettingsFor(format) {
    if (window.ajaxurl) {
      fetch(
        `${window.ajaxurl}?action=mvpf__get_exporter_settings&export_format=${format}`,
        { method: 'GET' }
      )
        .then((response) => {
          if (response.ok) {
            return response.json();
          }

          throw new Error(response);
        })
        .then((json) => {
          const { success, message, data } = json;

          if (!success) {
            throw new Error(message);
          }

          this.createSettings(data);
        })
        .catch((error) => console.error(error));
    }
  }

  createSettingInput(key, { type, default: defaultValue, values }) {
    const inputName = `mvpf_es__${key}`;

    switch (type) {
      case 'select': {
        const valuesTemplates = Object.entries(values).map(
          ([valueKey, value]) => {
            const isCurrent = valueKey === defaultValue;

            return html`<option
              value="${escapeHtml(valueKey)}"
              ${isCurrent ? 'selected' : ''}
            >
              ${value}
            </option>`.safe();
          }
        );

        return html`<select name="${inputName}">
          ${valuesTemplates}
        </select>`;
      }

      case 'checkbox': {
        const checked = defaultValue ? html`checked="checked"`.safe() : '';

        return html`
          <input type="hidden" name="${inputName}" value="" />
          <input type="checkbox" name="${inputName}" ${checked} />
        `;
      }

      default: {
        return html``;
      }
    }
  }

  createSettings(settings) {
    Object.entries(settings).forEach(([key, setting]) => {
      const { label } = setting;
      const inputTemplate = this.createSettingInput(key, setting);

      const settingTemplate = html`
        <tr class="exporter-setting">
          <th>${label}</th>
          <td>${inputTemplate.safe()}</td>
        </tr>
      `;

      this.containerTarget.appendChild(settingTemplate.toFragment());
    });
  }
}
