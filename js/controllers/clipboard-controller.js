import applicationController from '../application-controller';

export default class extends applicationController {
  copy() {
    this.element.focus();
    this.element.select();

    document.execCommand('copy');

    this.data.set('copied', '');
    this.later(() => this.removeCopiedStyled(), 1200);
  }

  removeCopiedStyled() {
    this.data.delete('copied');
  }
}
