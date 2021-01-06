import applicationController from '../application-controller';

export default class extends applicationController {
  copy() {
    this.element.focus();
    this.element.select();

    document.execCommand('copy');

    this.data.set('copied', '');

    this.later(() => {
      document.getSelection().removeAllRanges();

      this.later(() => {
        this.element.select();

        this.later(() => {
          document.getSelection().removeAllRanges();
          this.later(() => {
            this.element.blur();
            this.removeCopiedStyled();
          }, 800);
        }, 100);
      }, 100);
    }, 100);
  }

  removeCopiedStyled() {
    this.data.delete('copied');
  }
}
